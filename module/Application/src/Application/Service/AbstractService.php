<?php

/*
 * License GPL .
 *
 */

namespace Application\Service;

use Doctrine\ORM\EntityManager;
use Zend\Stdlib\Hydrator;

/**
 * Description of AbstractService
 *
 * Tem os metodos basicos para o Crud no BD
 *     Insert,
 *     Update
 *     Delete
 * Os metodos basico para facilitar a Validação.
 *     validação do registro.
 * Os metodos basico para facilitar a verificação do antes e depois
 *     Diferença entre o registrado e a alteração
 * Os metodos basico para facilitar a o inserção do log
 * @author Paulo Cordeiro Watakabe <watakabe05@gmail.com>
 */
abstract class AbstractService {

    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * Path para entidade a ser trabalhada
     *
     * @var string
     */
    protected $entity;

    /**
     * Esta variavel é instaciada ao fazer insert, update e delete
     * Contem um objeto da instancia $this->entity
     *
     * @var {$this->entity}
     */
    protected $entityReal;

    /**
     * Caminha para a pasta base de Entidades
     * @var string
     */
    protected $basePath;

    /**
     * Dados do form a serem validados
     * @var array
     */
    protected $data;

    /**
     * Campos do formulario que tem relacionamento.
     * Passar o id do campo e o caminho para entidade que será referenciada.
     * @var array
     */
    protected $dataRef = [];

    /**
     * Campos do formulario que tem relacionamento.
     * Converte um chave diferente do padrão que deveria ser id para outro nome.
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @since 05/04/2016
     * @var array
     */
    protected $dataRefKey = [];

    /**
     * String no formato para gravação de alterações feitas no registro
     * Formato campo  nome; valor antes; valor depois;
     * @var string
     */
    protected $dePara = 'Campo;Antes;Depois|';

    /**
     * Chave no array que representa o id do registro default id
     * @var string
     */
    protected $id;

    /**
     * Para Casos em que não se pode validar registro
     * @var boolean
     */
    protected $validate = TRUE;

    /**
     * Define se vai comitar as alterações do BD
     * Para controle de alterações e melhorar desempenho
     * @var boolean
     */
    protected $flush = true;

    /**
     * Dados do usuario em formato de array
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @var array
     */
    protected $user;

    /**
     * Controller em que esta sendo trabalho
     *      Acessar seu metodos e o principal ter acesso ao service locator.
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.2
     * @var \Application\Controller\CrudController
     */
    protected $controller;
    /**
     * Service pai
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.2
     * @var object of service
     */
    protected $fatherService;

    /**
     * repository da entitade do serviço
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @var \Doctrine\ORM\EntityRepository The repository class.
     */
    protected $rp;

    /**
     * Geração de mensagem no serviço e nos serviços dependentes.
     * Padrão é FALSE para gerar as mensagems do serviço
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 21-11-2016
     * @var bollean
     */
    public $supressMsg = FALSE;

    /**
     * Serviço basico para manipular entida e suas relações caso houver
     * Insert, Update e validação do registro.
     * Obs tem redirecionamento de atributos se um object for passado em $fatherService sera colocado em $controller e atribuido falso ao mesmo
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.2
     * @param EntityManager $em              Gerenciador ORM de dados do Doctrine
     * @param boolean       $fatherService   Opcional serve para padronizar o flush em caso de chamadas recursivas ou chamado por outro serviço
     */
    public function __construct(EntityManager $em, $fatherService = FALSE) {
        $this->em = $em;
        $this->basePath = 'Application\Entity\\';
        $this->id = 'id';
        if ($fatherService) {
            $this->fatherService = $fatherService;
            $this->setFlush($fatherService->getFlush());
            $this->setController($this->fatherService->getController());
            $this->supressMsg = $fatherService->supressMsg;
        }
    }

    /**
     * Retorna o repository da entidade do serviço
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 05-09-2016
     * @return \Application\Entity\Repository\AbstractRepository The repository class.
     */
    public function getRepository() {
        if (is_null($this->rp)) {
            $this->rp = $this->em->getRepository($this->entity);
        }
        return $this->rp;
    }

    /**
     * Converte item vazios para null para fazer a gravação no banco.
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @since 05/04/2016
     * @version 1.0
     */
    public function convEmptyForNull() {
        foreach ($this->data as &$data){
            if(empty($data) AND '0' !== $data AND 0 !== $data){
                $data = NULL;
            }
        }
        unset($data);
    }

    /**
     * Inserir um novo registro  no banco.
     * opção de validar e retorna falso e a lista de erros encontrada caso invalido
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param array $data
     * @return obejct | array
     */
    public function insert(array $data = []) {
        if (!empty($data)) {
            $this->data = $data;
        }
        if($this->userIsReadOnly()){
            $this->showMessage("Usuario com permissão somente de Leitura!!!", "error");
            return [FALSE, ['Usuario com permissão somente de Leitura!!!']];
        }
        // Corrige o campo alterado por para criado por
        if(isset($this->data['updatedBy'])){
            $this->data['createdBy'] = $this->data['updatedBy'];
            unset($this->data['updatedBy']);
        }
        $this->trataData('insert');
        if ($this->validate) {
            $validated = $this->isValid('insert');
            if ($validated !== TRUE) {
                if(is_array($validated) AND $validated[0] =='abort'){
                    $opt = $validated[3] ?? 'success';
                    !$this->fatherService && isset($validated[2]) && $this->showMessage($validated[2], $opt);
                    return $validated[1];
                }
                !$this->fatherService and $this->showMessage("Falha na inclusao", "error");
                return [FALSE, $validated];
            }
        }
        $this->trataDataPos('insert');
        $this->setReferences();

        $this->convEmptyForNull();
        $this->entityReal = new $this->entity($this->data);

        $this->em->persist($this->entityReal);
        if ($this->getFlush()) {
            $this->em->flush();
            $this->data[$this->id] = $this->entityReal->getId();
            !$this->fatherService and $this->showMessage("Incluído com Sucesso!", "success");
        }
        return $this->entityReal;
    }

    /**
     * Atuliza um registro existente no banco
     * opção de verificar se existe diferença entre o registro a atualizar com o existente
     * opção de validar e retorna falso e a lista de erros encontrada caso invalido
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param array $data
     * @return type
     */
    public function update(array $data = []) {
        if (!empty($data)) {
            $this->data = $data;
        }
        if($this->userIsReadOnly()){
            $this->showMessage("Usuario com permissão somente de Leitura!!!", "error");
            return [FALSE, ['Usuario com permissão somente de Leitura!!!']];
        }
        $this->entityReal = $this->em->getReference($this->entity, $this->data[$this->id]);
        //corrigi o campo de criado por para alterado por
        if(isset($this->data['createdBy'])){
            $this->data['updatedBy'] = $this->data['createdBy'];
            unset($this->data['createdBy']);
        }
        $this->trataData('update');
        if ($this->validate) {
            $validated = $this->isValid('update');
            if ($validated !== TRUE) {
                if(is_array($validated) AND $validated[0] =='abort'){
                    $opt = $validated[3] ?? 'success';
                    !$this->fatherService && isset($validated[2]) && $this->showMessage($validated[2], $opt);
                    return $validated[1];
                }
                !$this->fatherService and $this->showMessage("Falha na atualização", "error");
                return [FALSE, $validated];
            }
        }
        $this->trataDataPos('update');
        $this->getDiff($this->entityReal);

        switch (TRUE) {
            case empty($this->dePara):
                return $this->entityReal;

            case $this->dePara == 'force':
                continue;
        }

        $this->setReferences();

        $this->convEmptyForNull();
        (new Hydrator\ClassMethods)->hydrate($this->data, $this->entityReal);
        $this->em->persist($this->entityReal);
        if ($this->getFlush()) {
            $this->em->flush();
            !$this->fatherService and $this->showMessage("Atualizado com Sucesso!", "success");
        }
        return $this->entityReal;
    }

    /**
     * Metodo que aliado ao controller para a inclusão do log.
     * Apos fazer a inserção dos dados no BD o controller decide se vai querer registrar o log desse evento
     *
     *
     * @param string $controller   Qual controller esta fazendo solicitando o serviço
     * @param string $action       Qual action esta fazendo solicitando o serviço
     * @param string $obs          Obs do evento em questão
     */
    public function log($controller, $action, $obs = '') {
        $idDaTabela = ($this->entityReal) ? $this->entityReal->getId() : 'no Entity';
        (new AppLog($this->em, $this))->AddLog($controller, $action, $obs, $this->entity, $idDaTabela, $this->dePara);
    }

    /**
     * Esta função é modelo
     * Função a ser sobreescrita para quando houver necessidade de pegar os campos que foram alterados na entidade
     * para registro no log
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param object $ent
     */
    public function getDiff($ent) {
        $this->diffAfterBefore('nome do campo', 'valor antes de alterar', 'valor depois de alterado');
        $this->dePara = 'force';
    }

    /**
     * Esta função é modelo
     * Função a ser sobreescrita para quando houver necessidade de validar os dados a ser inseridos
     * para registro no log
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param string $opt Contem o valor insert | update para pode direcionar algumas validação
     * @return boolean
     */
    public function isValid($opt) {
        // Logica de validação e retorna false em caso de
        if($opt == 'insert'){
            return $this->isValidInsert();
        }else{
            return $this->isValidUpdate();
        }
    }    
    
    /**
     * Verifica 
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 24/03/2017
     * @return mixed
     */
    public function isValidInsert() {
        return true;        
    }
    
    /**
     * Verifica 
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 24/03/2017
     * @return mixed
     */
    public function isValidUpdate() {
        return true;
    }

    /**
     * Se a opcao forceRemove for false e se
     * a entidade possuir o metodo 'setStatus',
     * então o registro da entidade o campo Status será alterado para inativo
     * da base de dados. Caso contrario, o status da entidade
     * é setada como 'inativo'
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since ?
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @version 1.5 Adicao do $forceRemove
     * @since ?
     * @author Danilo Song  <danilosong@outlook.com>
     * @version 1.6 Adicao do status como parametro
     * @since 11-07-2017
     * @param integer $id
     * @return integer
     */
    public function delete($id, $forceRemove = FALSE, $status = 'INATIVO') {
        $this->entityReal = $this->em->getReference($this->entity, $id);
        if ($this->entityReal) {
            if (method_exists($this->entityReal, 'setStatus') and ! $forceRemove) {
                $this->entityReal->setStatus($status);
                $this->em->persist($this->entityReal);
            } else {
                $this->em->remove($this->entityReal);
            }
            if ($this->getFlush()) {
                $this->em->flush();
                !$this->fatherService and $this->showMessage("Excluído com sucesso!", "success");
            }
            return $id;
        }
        $this->setMessage("Falha na exclusao", "error");
        return false;
    }

    /**
     * Parametriza varias referencia de entidades atraves de um array
     * Não apaga as referencias anteriores
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param array $option
     */
    public function setDataRefArray(array $option = [], $keyRefs = []) {
        if (empty($this->dataRef)) {
            $this->dataRef    = $option;
            $this->dataRefKey = $keyRefs;
        } else {
            $this->dataRef    = array_merge($this->dataRef, $option);
            $this->dataRefKey = array_merge($this->dataRefKey, $keyRefs);
        }
    }

    /**
     * Parametriza uma referencia de entidades
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param string $key
     * @param string $entityPath
     */
    public function setDataRef($key = '', $entityPath = '') {
        $this->dataRef[$key] = $entityPath;
    }

    /**
     * Decide se faz a inserção ou atualização dos dados no BD
     * $data com dados que serão salvo no BD
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param array $data Com os dados a serem manipulado no BD
     * @return object A entidade que esta sendo manipulada
     */
    public function save($data) {
        $this->data = $data;
        if (empty($this->data[$this->id])) {
            return $this->insert($data);
        } else {
            return $this->update($data);
        }
    }

    /**
     * Setas as referencias se parametrizadas pelo SetDataRef
     * Se o campo de referencia contiver um array de dados sub entende-se que e uma dependencia de outro serviço e transfere a responsabilidade de inclusao.
     * Ignora referencias que não estão na var data
     * Adicionado Array references para gravar dados em campos do tipo arraycollection do doctrine
     * Ao prefixar a relacao com ref_ em vez de instaciar o service target apenas faz a referencia mesmo sendo um array
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.6
     */
    public function setReferences() {
        //Pega uma referencia do registro no doctrine
        foreach ($this->dataRef as $key => $value) {
            if (0 === strpos($key, 'array_')) {
                $inputName = substr($key, 6);
                $this->setArrayReferences($inputName, $value);
                continue;
            }
            if (0 === strpos($key, 'ref_')) {
                $key = substr($key, 4);
                isset($this->data[$key]) && $this->undoArrayForId($key, $this->getKeyForRelation($key));
            }
            if (!isset($this->data[$key])) {
                continue;
            }
            if (empty($this->data[$key])) {
                $this->data[$key] = NULL;
            }
            if (!is_array($this->data[$key])) {
                $this->data[$key] = $this->idToReference($key, $value);
                continue;
            }
            $srvLocal = str_replace('Entity', 'Service', $value);
            $srv = new $srvLocal($this->em, $this);
            if(!isset($this->data[$key]['createdBy'])){
                $this->data[$key]['createdBy'] = isset($this->data['createdBy']) ? $this->data['createdBy'] : $this->data['updatedBy'];
            }
            $ent = $srv->save($this->data[$key]);
            if(!is_object($ent)){
                unset($this->data[$key]);
                if(!$this->supressMsg AND is_array($ent[1])){
                    foreach ($ent[1] as $msg) {
                        $this->showMessage($msg, "warning");
                    }
                }
            }else{
                $this->data[$key] = $ent;
            }
        }
    }

    /**
     * Melhoria na relacao com outras entidades
     * Não era possivel pegar o id do array para passar como parametro relacao quando o nome do campo é diferente do id da relacao.
     * Para contornar esse problema foi implementada um conversor de campo para id que quando registrado sera esse o id da relacao a ser usado
     * Funcao converter o padrao id do nome do campo key da relacao
     * Caso não for padrão e estiver registro no array de conversao
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @since 05/04/2016
     * @version 1.0
     * @param string $key nome do input a ser convertido
     * @return string Nome do campo da relacao ou o default que é o id
     */
    public function getKeyForRelation($key) {
        return isset($this->dataRefKey[$key]) ? $this->dataRefKey[$key] : 'id';
    }

    /**
     * Metodo auxiliar do setReferences
     * Objetivo de pegar um input do tipo array com os ids e converte em references para suas entidades
     * Caso for passado uma string em vez do array sub entende que o objetivo é limpar todas referencias da entidade
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param string $input     Nome do input
     * @param string $entity    Caminho para entidade target
     * @return nothing          Sem retorno pois trabalha a variavel global data
     */
    public function setArrayReferences($input, $entity) {
        if (!isset($this->data[$input])) {
            return;
        }
        if (is_string($this->data[$input])) {
            $this->data[$input] = [];
            return;
        }
        $refs = [];
        foreach ($this->data[$input] as $id) {
            if(is_array($id)){
                $srvLocal = str_replace('Entity', 'Service', $entity);
                $srv = new $srvLocal($this->em, $this);
                $ent = $srv->save($id);
                if ($ent) {
                    $refs[] = $ent;
                }
            }else{
                $refs[] = $this->em->getReference($entity, $id);
            }
        }
        $this->data[$input] = $refs;
    }

    /**
     * Converte o id de um campo em um referencia a sua entidade da relação
     * Caso nao exista o campo ou seja branco retorna null
     * Caso for um objecto se for da mesma instancia retorna ele mesmo caso nao retorna null
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param string $index   Indice do array a ser feita a ligação
     * @param string $entity  Caminho para a Entity
     * @return boolean
     */
    public function idToReference($index, $entity) {
        if ((!isset($this->data[$index])) OR ( empty($this->data[$index]))) {
            return NULL;
        }
        if (is_object($this->data[$index])) {
            return ($this->data[$index] instanceof $entity) ? $this->data[$index] : NULL;
        }
        return $this->em->getReference($entity, $this->data[$index]);
    }

    /**
     * Converte o id de um registro dependente em um Entity so array data global
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param string $index   Indice do array a ser feita a ligação
     * @param string $entity  Caminho para a Entity
     */
    public function idToEntity($index, $entity) {
        if ((!isset($this->data[$index])) OR ( empty($this->data[$index]))) {
            echo "erro no indice e nao pode ser carregar entity ", $index;
            return FALSE;
        }

        if (is_object($this->data[$index])) {
            if ($this->data[$index] instanceof $entity)
                return TRUE;
            else
                return FALSE;
        }

        $this->data[$index] = $this->em->find($entity, $this->data[$index]);
    }

    /**
     * Faz a comparação de alteração e retorna uma string no formato para gravação 3 campo separado por virgula.
     * E incrementa ou nao o resultado na variavel global
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param string $input
     * @param string $after
     * @param string $before
     * @return string
     */
    public function diffAfterBefore($input, $after, $before) {
        switch (true){
            case $before instanceof \DateTime:
                $compare = ($after instanceof \DateTime) ? $after->format('d/m/Y') : $after;
                if ($compare != $before->format('d/m/Y')) {
                    $this->dePara .= $input . ';' . $compare . ';' . $before->format('d/m/Y') . '|';
                }
                break;

            case is_numeric($before):
                $before = $this->strToFloat($before);
                $after  = $this->strToFloat($after);
                if ($after != $before) {
                    $this->dePara .= $input . ';' . number_format($after, 2, ',', '.') . ';' . number_format($before, 2, ',', '.') . '|';
                }

                break;

            default :
            if ($after != $before) {
                $this->dePara .= $input . ';' . $after . ';' . $before . '|';
            }
        }
    }

    /**
     * Faz a comparação de alteração e retorna uma string no formato para gravação 3 campo separado por virgula.
     * E incrementa ou nao o resultado na variavel global
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param string $input
     * @param string $after
     * @param string $before
     * @return string
     */
    /**
     * Methodo para facilitar busca pela diferença de valdo do antes e depois
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 27-08-2016
     * @param object $entity Entidade que contem os valores do antes
     * @param string $index  Indice do imput a ser verificado
     * @param string $label  Opcional Descritivo para ficar no log sobre o campo ou será seu proprio nome
     * @param string $method Opcional Faz um tratamento no valor usando o methodo da propria entidade
     * @return boolean Em caso de não conseguir a comparação corretamente ser retornado falso
     */
    public function getDiffBetween($entity, $index, $label='', $method='', $methodParams=null) {
        if(empty($index) OR !isset($this->data[$index])){
            return false;
        }
        $get = 'get' . ucfirst($index);
        if(!method_exists($entity, $get)){
            return false;
        }
        empty($label) && $label = ucfirst($index);
        if(empty($method)){
            $before = $this->data[$index];
        }else{
            if(!method_exists($entity, $method)){
                return false;
            }
            $before = $entity->$method($this->data[$index]);
        }
        if(is_null($methodParams)){
            $this->diffAfterBefore($label, $entity->$get(), $before);
        }else{
            if(!is_array($methodParams)){
                $methodParams = [$methodParams];
            }
            $this->diffAfterBefore($label, call_user_func_array([$entity, $get], $methodParams), $before);
        }
        return true;
    }

    /**
     * Para situaçoes em que não se deve validar o regitro no BD
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     */
    public function notValidateNew() {
        $this->validate = FALSE;
    }

    /**
     * Se vai comitar as operações do BD.
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param boolen $flush
     * return this
     */
    public function setFlush($flush) {
        $this->flush = ( $flush ) ? TRUE : FALSE;
        return $this;
    }

    /**
     * Se vai comitar as operações do BD.
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * return boolean
     */
    public function getFlush() {
        if (is_null($this->flush)) {
            $this->flush = TRUE;
        }
        return ( $this->flush ) ? TRUE : FALSE;
    }

    /**
     * Baseado na $key busca os registro cadastrados e monta um array para select
     * @author Paulo Watakabe
     * @version 1.0
     * @param string $key
     * @param boolean $first
     * @return array
     *
     */
    public function getParametroChave($key, $first = TRUE) {
        return $this->em->getRepository('Application\Entity\Parametros')->fetchPairs($key, $first);
    }

    /**
     * Facilitar a chamada dos metodos que esta no controller
     *
     * @author Paulo Watakabe
     * @version 1.0
     * @param string $m
     * @param array $p
     */
    public function __call($m, $p) {
        if (method_exists($this->controller, $m)) {
            return call_user_func_array([$this->controller, $m], $p);
        }
    }

    /**
     * Setar uma ligação com controller para acessar principalmente o service locator.
     *
     * @author Paulo Watakabe  <watakabe05@gmail.com>
     * @version 1.0
     * @param object $controller
     * @return \Application\Form\($AbstractForm_name)
     */
    public function setController($controller) {
        $this->controller = $controller;
        return $this;
    }

    /**
     * Pegar a ligação com controller para acessar principalmente o service locator.
     *
     * @author Paulo Watakabe  <watakabe05@gmail.com>
     * @version 1.0
     * @return \Application\Controller\CrudController of Controller
     */
    public function getController() {
        return $this->controller;
    }

    /**
     * E alguns caso apesar do form carregar na tela um outro form(dependente)
     * Ao chegar no service nao queira alterar ou incluir os dados e sim apenas fazer um referencia ao registro
     * Neste caso o indice array do form dependente deve ser convertido em uma campo somente com o id do registro
     *
     * @author Paulo Watakabe  <watakabe05@gmail.com>
     * @version 1.1
     * @since 28/10/2016  Verificar se o indice existe no array usando funcao array_key_exists pois isset retorna false
     * @param string $ind
     * @param string $ind2
     * @return boolean
     */
    public function undoArrayForId($ind = '', $ind2 = '') {
        if(is_object($this->data[$ind])){
            return TRUE;
        }
        if (isset($this->data[$ind][$ind2])) {
            $this->data[$ind] = $this->data[$ind][$ind2];
            return TRUE;
        }
        // procura pelo 'id' . $key que esta fora do padrão do doctrine
        if (is_array($this->data[$ind]) AND array_key_exists($ind2 . ucfirst($ind), $this->data[$ind])) {
            $this->data[$ind] = $this->data[$ind][$ind2 . ucfirst($ind)];
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Função para algum tratamento previo do valores a serem inseridos no BD
     * Executada antes da validação dos dados
     *
     * @author Paulo Watakabe  <watakabe05@gmail.com>
     * @version 1.0
     * @param string $tipo Informa se esta tratando do insert ou update
     */
    public function trataData($tipo) {

    }

    /**
     * Função para algum tratamento previo do valores a serem inseridos no BD
     * Executada apos fazer a validação dos dados
     *      *
     * @author Paulo Watakabe  <watakabe05@gmail.com>
     * @version 1.0
     * @param string $tipo Informa se esta tratando do insert ou update
     */
    public function trataDataPos($tipo) {

    }

    /**
     * Verifica se o usuario somente pode consultar
     *
     * @author Paulo Watakabe  <watakabe05@gmail.com>
     * @version 1.0
     * $since 21/03/2016
     * @return boolean
     */
    public function userIsReadOnly() {
        $role = $this->getUser('role');
        if('readOnly' == $role){
            return TRUE;
        }
        return false;
    }

    /**
     * Parametriza se o service faz a validação do registro ou não
     *
     * @author Paulo Watakabe  <watakabe05@gmail.com>
     * @version 1.0
     * $since 01/04/2016
     * @return boolean
     */
    function getValidate() {
        return $this->validate;
    }

    /**
     * Parametriza se o service faz a validação do registro ou não
     *
     * @author Paulo Watakabe  <watakabe05@gmail.com>
     * @version 1.0
     * @since 01/04/2016
     * @param boolean $validate
     */
    function setValidate($validate) {
        $this->validate = $validate;
    }

    /**
     * Objetivo de retorna a entity que esta sendo trabalhada na operações de delete, insert e update
     *
     * @author Paulo Watakabe  <watakabe05@gmail.com>
     * @version 1.0
     * @since 26/08/2016
     * @return object | NULL  Dependendo da operação retorna e entity ou null se não foi feita nenhuma operação
     */
    public function getEntityReal() {
        return $this->entityReal;
    }

    /**
     *
     * @author Paulo Watakabe  <watakabe05@gmail.com>
     * @version 1.0
     * @since 26/08/2016
     * @param string $key  Obrigatorio chave a ser usada na pesquisa e no filtro
     * @param array $arrayFilter Obrigatorio Um array para montar o filtro
     * @param array $arrayData   Obrigatorio Um array para se pesquisar o filtro
     * @param string $relation Opcional a ser colocado para indicar a relação com outras tabelas
     * @param mixed $default Opcional valor padrao para o filtro se existir
     */
    public function setFilter($key, array &$arrayFilter,array &$arrayData, $relation = '', $default = 'empty') {
        if(empty($key)){
            return;
        }
        !empty($relation) && $relation = $relation . '.';
        if(isset($arrayData[$key]) && !empty($arrayData[$key])){
            if(is_array($arrayData[$key])){
                $arrayFilter[$relation . $key] = ['IN(' =>$arrayData[$key]];
            }else{
                $arrayFilter[$relation . $key] = $arrayData[$key];
            }
            return;
        }
        if($default != 'empty'){
            $arrayFilter[$relation . $key] = $default;
        }
    }

    /**
     * Converte a variavel do tipo float para string para exibição
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @param float $float       Float a ser trabalhado
     * @param int $dec           Numero de casa decimais a colocar na formataçao padrão 2
     * @param boolean $format    Diz deve formatar para string ou retorna sem formatação
     * @return string | float    Retorna um string formatada para exibição ou o proprio float
     */
    public function floatToStr($float, $dec = 2, $format = TRUE) {
        if (is_bool($dec)) {
            $format = $dec;
            $dec = 2;
        }
        if (!$format) {
            return $float;
        }
        if(!is_numeric($float)){
            return 0.0;
        }
        return number_format($float, $dec, ',', '.');
    }

    /**
     * Faz tratamento na variavel string se necessario antes de converte em float
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @param String $check variavel a ser convertida se tratada se necessario
     * @return String $check no formato float para gravação pelo doctrine
     */
    public function strToFloat($check) {
        if (is_string($check)) {
            //Retira tudo que não for numero e virgula e depois converte virgula em ponto
            return (floatval(str_replace(",", ".", preg_replace("/[^0-9,-]/", "", $check))));
        }
        return $check;
    }

    /**
     * Converte um string para obj datetime se for um string valida
     * Caso o parametro for um object datetime retornara ele proprio
     * Faz tratamento da string
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @param string | \DateTime $strDateTime
     * @return \DateTime
     */
    public function strToDate($strDateTime = '') {
        if ($strDateTime instanceof \DateTime) {
            return $strDateTime;
        }
        switch (TRUE) {
            case empty($strDateTime):
                return new \DateTime('now');

            case (isset($strDateTime[2]) AND $strDateTime[2] == '/'):
                if (isset($strDateTime[15])) {
                    $dh = explode(' ', $strDateTime);
                    $d = explode('/', $dh[0]);
                    $h = $dh[1];
                } else {
                    $d = explode('/', $strDateTime);
                    $h = '';
                }
                $s = $d[2] . '-' . $d[1] . '-' . $d[0] . $h;
                break;

            case ($strDateTime == '-'):
                $s = '1901-01-01 12:00:00';
                break;
            case ('NULL' == strtoupper($strDateTime)):
                return NULL;
            default:
                $s = $strDateTime;
        }
        return new \DateTime($s);
    }

    /**
     * Converte um obj datetime para string para exibição html
     * Caso $full for string ele usa como parametro para formatação da data
     * Caso $full for falso  ele converte como parametro para formatação da data de d/m/Y
     * Caso $obj contiver a string "full" parametriza $full para 'd/m/Y h:m'
     * Caso $obj for True retorna o proprio object
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @param \DateTime $date
     * @param string | bollean $full
     * @param string | bollean $obj
     * @return string | \DateTime
     */
    public function dateToStr($date, $full = false, $obj = false) {
        if ($obj === TRUE) {
            return $date;
        }
        if ($obj === 'full') {
            $full = 'd/m/Y H:i:s';
        } else {
            if (is_string($obj)) {
                $full = $obj;
            }
        }
        if (!is_string($full)) {
            $full = 'd/m/Y';
        }
        if ($date instanceof \DateTime) {
            if ($date->format('Y-m-d') == '1901-01-01') {
                return '-';
            }
            return $date->format($full);
        } else {
            return '-';
        }
    }

    /**
     * Valida os dados do arquivo a ser salvo pela funcao saveFile
     *
     * @author  Danilo Dorotheu <danilo.dorotheu@live.com>
     * @since   22-03-2017
     * @version 1
     * @param   mixed $file    [description]
     * @param   mixed $options [description]
     * @return  mixed (Quando array, significa que encontrou erros)
     */
    public function validaSaveFile($file, $options) {
        if(!is_array($file)) {
            return [
                "error"    => "Arquivo inválido!!!",
                "messages" => [],
                "type"     => 1
            ];
        }

        if(empty($file['tmp_name'])) {
            return [
                "error"    => "Arquivo temporário não encontrado!!!",
                "messages" => [],
                "type"     => 2
            ];
        }

        if(!isset($options['path'])) {
            return [
                "error"    => "Destino não encontrado!!!",
                "messages" => [],
                "type"     => 3
            ];
        }

        if(isset($options['validators'])) {
            $adapter = new \Zend\File\Transfer\Adapter\Http();
            $adapter->setValidators($options['validators'], $file["name"]);
            if (!$adapter->isValid()) {
                return [
                    "error"    => "Arquivo ({$file['name']}) inválido!!!",
                    "messages" => $adapter->getMessages(),
                    "type"     => 4
                ];
            }
        }

        return TRUE;
    }

    /**
     * Salva a imagem no servidor
     *
     * @author  Danilo Dorotheu <danilo.dorotheu@live.com>
     * @since   21-03-2017
     * @version 1
     * @param   mixed $uploadedImage
     *          Quando string, lê os parametros da imagem referente ao nome do
     *          campo que importou a mesma.
     *
     * @param   mixed $options
     *         (name => nome da imagem, path => destino, pref => prefixo)
     *
     * @return  mixed
     */
    public function saveFile($file = [], $options = []) {
        (is_string($file))    && $file = $this->params()->fromFiles($file);
        (is_string($options)) && $options = ["path" => $options];

        $valida = $this->validaSaveFile($file, $options);
        if(is_array($valida)) {
            return $valida;
        }

        list($name, $type) = explode('.', $file['name']);

        $name = (isset($options['name']) and !empty($options['name']))
              ? $options['name'] : $name;

        $pref = (isset($options['pref']) and !empty($options['pref']))
              ? $options['pref'] : ""   ;

        $name = strtolower($name);
        $path = $options['path'];

        $filename = "{$name}{$pref}.{$type}";
        move_uploaded_file($file['tmp_name'], $path . $filename);

        return $filename;
    }
    
    /**
     * Centralizador e facilitador para instanciar outro serviços
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 26-04-2017
     * @param type $servicePath
     * @return \Application\Service\AbstractService
     */
    public function getService($servicePath) {
        return new $servicePath($this->em, $this);
    }

}
