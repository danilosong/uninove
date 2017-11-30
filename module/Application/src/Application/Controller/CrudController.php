<?php

/*
 * License GPL .
 * 
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\View\Model\PdfModel;
use Zend\Paginator\Paginator,
    Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator,
    DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use \Zend\Session\Container as SessionContainer;

/**
 * Description of CrudController
 *
 * Tem as actions basicas para o Crud no BD
 *      new
 *      update
 *      delete
 *      index    faz a listagem dos registro com paginação
 * 
 * Paramentros para:
 *      log fazer ou não.
 *      servico se instancia pelo service manager ou direto.
 *      form se instancia com entitymanager ou não 
 * 
 * @author Paulo Cordeiro Watakabe <watakabe05@gmail.com>
 */
abstract class CrudController extends AbstractActionController {

    /**
     * Manipulador de entidades do Doctrine
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * O nome do Modulo
     * @var string
     */
    protected $moduloName;

    /**
     * Nome base para configurar o controller, serviço, entidade, form
     * @var string 
     */
    protected $name;

    /**
     * Título das telas da view
     * @var string 
     */
    protected $title;

    /**
     * Caminho para instanciar o serviço
     * @var string 
     */
    protected $service;

    /**
     * Objeto serviço do controller
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 03-08-2016  
     * @var \Application\Service\AbstractService 
     */
    protected $serviceObj;

    /**
     * Caminho para instanciar o entidade
     * @var string 
     */
    protected $entity;

    /**
     * Caminho para instanciar o form
     * @var string 
     */
    protected $form;

    /**
     * Rota do Zend padrão "app/default"
     * @var string 
     */
    protected $route;

    /**
     * Rota do Zend para ajax padrão "app/ajax"
     * @var string 
     */
    protected $routeAjax;

    /**
     * Nome do controller
     * @var string 
     */
    public $controller;

    /**
     * Dados do usuario em formato de array
     * @var array 
     */
    protected $user;

    /**
     * Objeto para ser renderizado 
     * @var Zend\View\Model\ViewModel 
     */
    protected $view;

    /**
     * Paginação dos dados
     * @var Zend\Paginator\Paginator
     */
    protected $paginator;

    /**
     * Numero da pagina a ser exibida na tela
     * @var integer 
     */
    protected $page;

    /**
     * Define se retorna ou nao uma viewmodel das actions
     * @var boolean 
     */
    protected $render = TRUE;

    /**
     * Define se em caso de sucesso no edit ou new vai redirecionar o tela ou não
     * @author PauloWatakabe <watakabe05@gmailcom>
     * @since 22-03-2017
     * @var boolean 
     */
    public    $redirecionar = TRUE;

    /**
     * Parametriza as actions padrão para fazer log.
     * 
     * @var boolean Padrão é false (não fazer log).
     */
    protected $log = FALSE;

    /**
     * Parametriza se o service vai ser instanciado pelo service manager padrão false
     * @var boolean
     */
    protected $haveServiceLocatorService = FALSE;

    /**
     * Parametriza se o form precisa do entitymanager padrão é false
     * @var boolean 
     */
    protected $formWithEntityManager = FALSE;

    /**
     * Parametriza se o form precisa do entitymanager padrão é false
     * @var boolean 
     */
    protected $formWithController = FALSE;

    /**
     *
     * @var \Application\View\Helper\Acl 
     */
    protected $acl;

    /**
     *
     * @var \Application\View\Helper\Param 
     */
    protected $param;

    /**
     *
     * @var \Application\View\Helper\Table 
     */
    protected $table;

    /**
     *
     * @var \Application\View\Helper\Image 
     */
    protected $image;

    /**
     *
     * @var array
     */
    protected $dataView;

    /**
     * Injeta outro formulario como depencia em FormFilter.
     *  
     * @var array Que faz parte das opçoes do form
     */
    protected $dependencia = [];

    /**
     * @var [boolean|string] Carrega mensagem para ser exibida na view
     */
    private $message = FALSE;

    /**
     * @var \Application\Service\Email 
     */
    private $email;

    /**
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0 
     * @since 25-04-2016 
     * @var \Zend\Session\Container 
     */
    private $sc;

    /**
     * Ordenador padrão dos dados .
     *  
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0 
     * @since 27-04-2016 
     * @var array Com coluna e a direção da ordenação
     */
    protected $defaultOrderBy = [];
    
    /**
     * Contém o flashMessenger
     * 
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @since 24-01-2017
     * @var \Application\Service\FlashMessenger 
     */
    protected $flashMessenger;

    /**
     * Inicia o flashMessenger
     * 
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @since 24-01-2017
     * @return \Application\Service\FlashMessenger
     */
    public function flashMessenger() {
        if(!$this->flashMessenger) {
            $this->flashMessenger = new \Application\Service\FlashMessenger();
        }
        
        return $this->flashMessenger;
    }
    
    /**
     * Faz a configuração do controller para o funcionamento das action basicas
     * Sempre o nome dever ser com a primeira letra minuscula e no singular.
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param string $name nome base para a configuração do controller 
     * @param string $module Modulo padrão do sistema default 'Application'
     */
    public function __construct($name = '', $module = 'Application') {
        // filtra o nome da classe retirando o namespace e a palavra no sController e colocar para minusculo. PS para funcionario deve estar no mesmo nameSpace 
        $name = (!empty($name)) ? $name : lcfirst(str_replace('sController', '', str_replace(__NAMESPACE__ . '\\', '', get_class($this))));
        $this->name = ($name);
        $this->moduloName = $module;
        $this->entity = $this->moduloName . "\Entity\\" . ucfirst($this->name);
        $this->form = $this->moduloName . "\Form\\" . ucfirst($this->name);
        $this->service = $this->moduloName . "\Service\\" . ucfirst($this->name);
        $this->controller = $this->name . "s";
        $this->route = "app/default";
        $this->routeAjax = "app/ajax"; // @todo sem uso atualmente possivel abandono dessa variavel
        $this->setFormWithEntityManager(true);
    }

    /**
     * Pegar Uma sessao existente ou criar um nova para manipulação de dados.
     * Caso passado a key retorna somente o valor guardado na sessao caso exista
     * Caso passado a key e valor sera guardado na sessao sobreescrevendo o antigo se houver.
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0 
     * @since 25-04-2016 
     * @param bollean | string $key    chave valor guardado na sessao
     * @param bollean | mixed $valor   Valor a ser guardado na sessao 
     * @return \Zend\Session\Container | Value in sessao
     */
    public function sessao($key = false, $valor = NULL) {
        if (is_null($this->sc)) {
            $this->sc = new SessionContainer($this->moduloName);
        }
        if ($key) {
            if (!is_null($valor)) {
                $this->sc->$key = $valor;
            } else {
                return $this->sc->$key;
            }
        }
        return $this->sc;
    }

    /**
     * Gerencia o array de ordenacao para usar no doctrine.
     * Guardar cada nova ordenacao na sessao para manter na proxima leitura
     * Apaga a sessao de ordenação quando não for recebido um post
     * Segue a seguinte sequencia para ordenação ASC , DESC e remove do array
     * Sempre regrava a sessao com a nova ordenação
     * 
     * Opcional flag que avisa ser uma ordenação dentro do post array('tableObs' => 'Ordenador')
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @since 26-04-2016
     * @version 1.0
     * @param array $orderBy Ponteiro para array com os campos para ordenar
     * @param \Zend\Http\Request $request Classe para manipular variaveis do formulario html
     * @return nothing
     */
    public function checkHaveOrderBy(&$orderBy, $request) {
        if (!$request->isPost() OR $request->getPost('fakePost', '0') == '1') {
            $this->sessao('orderBy' . $this->name, $this->defaultOrderBy);
            $orderBy = $this->defaultOrderBy;
            return;
        }
        $data = $request->getPost()->toArray();
        $oldOrderBy = $this->sessao('orderBy' . $this->name);
        if (NULL !== $oldOrderBy) {
            $orderBy = array_merge($oldOrderBy, $orderBy);
        }
        if (isset($data['addOrder'])) { // Tem no post addOrder
            if (isset($orderBy[$data['addOrder']])) { // Se ja exite na sessao inverte a ordem
                if ($orderBy[$data['addOrder']] == 'ASC') {
                    $orderBy[$data['addOrder']] = 'DESC'; // altera para decrescente 
                } else {
                    unset($orderBy[$data['addOrder']]); // se for DESC remove o campo 
                }
            } else {
                $orderBy[$data['addOrder']] = 'ASC'; // adiciona um novo 
            }
        }
        $this->sessao('orderBy' . $this->name, $orderBy); // registra na sessao o array
    }

    /**
     * Faz listagem dos dados baseado nos parametros passados
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param array $filtro    Com parametros da entidade para serem filtrados
     * @param array $orderBy   Com parametros da entidade para serem ordenados
     * @param array $list      É uma querybuilder personalizada que vai direto para paginação
     * @return \Zend\View\Model\ViewModel | no return
     */
    public function indexAction($filtro = [], array $orderBy = [], \Doctrine\ORM\QueryBuilder $list = null) {
        /* var $list  \Doctrine\ORM\QueryBuilder */
        // Redireciona paramentro filtro para list 
        if ($filtro instanceof \Doctrine\ORM\QueryBuilder) {
            $list = $filtro;
            $filtro = [];
        }
        // Cria um list padrao
        if (is_null($list)) {
            $list = $this->getEm()
                    ->createQueryBuilder()
                    ->select('e')
                    ->from($this->entity, 'e');
        }
        //Montar Filtros
        if (!empty($filtro) AND ! is_null($list)) {
            $and = $where = '';
            foreach ($filtro as $key => $value) {
                $cond = is_numeric($value)? " = " : " LIKE ";
                
                // Quando $value for array, então gerar query com o IN
                is_array($value)   && $cond = " IN ("; 
                $aux = is_array($value)? ")" : "";
                
                $where .= $and . ' e.' . $key . $cond . ':' . $key . $aux;
                $and = ' AND';
            }
            $list->where($where)->setParameters($filtro);
        }
        $request = $this->getRequest();
        $this->checkHaveOrderBy($orderBy, $request);
        //Montar Ordenação
        foreach ($orderBy as $key => $value) {
            if (strpos($key, '.') === false) {
                $list->addOrderBy('e.' . $key, $value);
            } else {
                $list->addOrderBy($key, $value);
            }
        }

        $form     = $this->getFormFilter();
        $data     = $this->sessao('post');  // Pega os dados do post se houver
        $dataView = $this->getDataView('Exibindo ' . ucfirst($this->getTitle()));
        
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
        }else{
            if(isset($data['subOpcaoPag'])){
                unset($data['subOpcaoPag']); // retirar essa variavel quando for carregada da sessão
            }
        }
        !is_array($data) && $data = [];
        $form->setData($this->getFormatFormData($data));
        $this->changeIndexAction($form, $dataView, $data);
        $this->page = $this->params()->fromRoute('page', $form->get('page')->getValue());
        if(isset($data['subOpcaoPag']) AND $data['subOpcaoPag'] == 'loadPage'){
            $this->sessao('page.' . $this->name ,$this->page);
            $this->sessao('post', $data);  
        }else{
            $this->sessao('post', false);  // reseta os dados que estavam ou não na sessao post
        }
        
        if (!$request->isPost()) {
            $this->page = $this->sessao('page.' . $this->name);
        }

        $doctrinePaginator = new DoctrinePaginator($list);
        $paginatorAdapter = new PaginatorAdapter($doctrinePaginator);
        $this->paginator = new Paginator($paginatorAdapter);
        $this->paginator->setCurrentPageNumber($this->page);
        $quantPag = $request->getPost('limitePag', $form->get('limitePag')->getValue());
        $this->paginator->setDefaultItemCountPerPage($quantPag);
        $this->paginator->setPageRange($this->getRequest()->getPost('pageRange', $form->get('pageRange')->getValue()));
        if ($this->render) {
            return $this->makeView(['data' => $this->paginator, 'page' => $this->page, 'dataView' => $dataView, 'form' => $form, 'post' => $data]);
        } else {
            return ['data' => $this->paginator, 'page' => $this->page, 'dataView' => $dataView, 'form' => $form, 'post' => $data];
        }
    }

    /**
     * Metodo a ser usado para modicações no form, dataView e post retornado a view.
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @since 07-12-2016
     * @version 1.0
     * @param \Application\Form\FormFilter $form
     * @param array $dataView Referencia da variavel no index
     * @param array $post     Referencia da variavel no index
     */
    public function changeIndexAction($form, &$dataView, &$post) {
        // Do some thing
    }

    /**
     * Pegar o formfilter instaciado e configurado
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @since 15-04-2016
     * @version 1.0
     * @param string $dep parametriza o formulario da dependencia.
     */
    public function getFormFilter($dep = '') {
        !empty($dep) && $this->setDependencia($dep);
        $form = $this->moduloName . "\Form\FormFilter";
        return new $form($this->getEm(), $this->dependencia, $this);
    }

    /**
     * Seta um título para ser utilizado nas páginas da view
     * 
     * @param string $title
     * @return \Application\Controller\CrudController
     */
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    /**
     * Retorna o título das telas da view.
     * OBS: Caso não tenha um título definido, será retornado o valor contido
     * na variavel name;
     * @return string
     */
    public function getTitle() {
        return ($this->title) ? $this->title : $this->name;
    }

    /**
     * Caminho do form a ser injetado em filters caso houver necessidade de filtros especificos 
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param string $dependencia Caminho do form a ser injetado em filters
     */
    public function setDependencia($dependencia) {
        $this->dependencia['dependencia'] = $this->moduloName . "\Form\\" . ucfirst($dependencia);
    }

    /**
     * Metodo usado para converte os prefix do form html em array simples
     * Necessario para validaçao dos filters em forms que tem dependencia de outros forms 
     * Percorre todos os itens do post desmontando array em itens comuns do post  
     *  
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param array multidimencional $data
     * @return array simples
     */
    public function getFormatFormData(array $data) {
        $newData = [];
        $this->extractData($newData, $data);
        return $newData;
    }

    /**
     * Metodo resursivo para transformar array multidimensional em array simples baseado em seus prefix
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * // Implementado logica para nao prefixar array que tinha Ids pois da problema por Exe: com multicheckbox 
     * @since 07/04/2016
     * @version 1.2
     * @param array $newData
     * @param array $data
     * @param string $prefix
     */
    public function extractData(&$newData, $data, $prefix = '') {
        foreach ($data as $key => &$value) {
            // verificar se é um array numerico para não fazer prefixagem
            $flag = TRUE;
            if (is_array($value)) {
                foreach ($value as $k => &$vlr) { // @todo essa parte esta sobre observação pois verificava o valor se era numerico 07-12-2016
                    if (!is_numeric($k)) {
                        $flag = FALSE;
                        break;
                    }
                }
                unset($vlr);
            }
            if (!is_array($value) OR $flag) {
                if (empty($prefix)) {
                    $newData[$key] = $value;
                } else {
                    $newData[$prefix . '[' . $key . ']'] = $value;
                }
                continue;
            }
            if (empty($prefix)) {
                $pf = $key;
            } else {
                $pf = $prefix . '[' . $key . ']';
            }
            $this->extractData($newData, $value, $pf);
        }
        unset($value);
    }

    /**
     * Exibi o form para inclusao de um novo registro.
     * Faz a validaçao do form e chama o serviço para incluir no banco
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since ?
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.5 usar flash messenger para exibir os erros relacionado a filtros
     * @since 20-06-2017
     * @return \Zend\View\Model\ViewModel | redirect 302 | nothing
     */
    public function newAction() {
        $dataView = $this->getDataView('Novo ' . $this->getTitle(), 'new');
        $form = $this->getForm();
        $request = $this->getRequest();
        $resul = '';
        $entity = $this->getEntityForNew($dataView, $form, $request, $resul);
        if ($request->isPost()) {
            $form->setData($this->getFormatFormData($request->getPost()->toArray()));
            if ($form->isValid()) {
                $service = $this->getService();
                $resul = $service->insert($this->getDataWeb($request));
                if ($this->log AND ! is_array($resul)) {
                    $service->log($this->controller, 'new', 'Inseriu um novo Registro');
                }
                if (!is_array($resul) AND $this->render AND $this->redirecionar) {
                    return $this->setRedirect($this->getRedirect('new', $resul));
                }
            } else {
                $this->flashMessenger()->addErrorMessage('Erros de preenchimento acusado(s) pelo filter:');
                foreach ($form->getMessages() as $message){
                    $this->flashMessenger()->addErrorMessage($message);
                }
            }
        }
        if ($this->render OR is_array($resul)) {
            return $this->makeView(compact("form", "dataView", "resul", "entity"), $this->getPathViewDefault() . 'form.phtml');
        }
        return $resul;
    }

    /**
     * Metodo para ser sobreescrito em casos em que new action precisa de um entidade configurada para o form
     * Os parametro sao os ponteiro logo a alteração deles ira alterar tb do metodo newAction
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 31-01-2016
     * @param array  $dataView  Array com informações basica como titulos etc.
     * @param object $form      
     * @param object $request
     * @param string $resul
     * @return type
     */
    public function getEntityForNew(&$dataView, &$form, &$request, &$resul) {
        return null;
    }

    /**
     * Exibi o form para alteraçao de um registro.
     * Faz a validaçao do form e chama o serviço para salvar no banco
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since ?
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.5 usar flash messenger para exibir os erros relacionado a filtros
     * @since 20-06-2017
     * @return \Zend\View\Model\ViewModel | redirect 302 | nothing
     */
    public function editAction() {
        $dataView = $this->getDataView('Editando ' . $this->getTitle(), 'edit');
        $form = $this->getForm();
        $request = $this->getRequest();
        $entity = null;
        $id = $this->params()->fromRoute('id', 0);
        if ($request->isPost()) {
            $post = $request->getPost()->toArray();
            isset($post['id']) && !empty($post['id']) && $id = $post['id'];
        }
        if ($id != 0) {
            $entity = $this->getEm()->find($this->entity, $id);
            $form->setData($this->getFormatFormData($entity->toArray()));
        }
        $resul = '';
        $this->changeEntityForEdit($dataView, $form, $request, $resul, $entity);
        if ($request->isPost()) {
            $form->setData($this->getFormatFormData($request->getPost()->toArray()));
            if ($form->isValid()) {
                $service = $this->getService();
                $resul = $service->update($this->getDataWeb($request, 'updatedBy'));
                if ($this->log AND ! is_array($resul)) {
                    $service->log($this->controller, 'edit', 'Editou o Registro');
                }
                if (!is_array($resul) AND $this->render AND $this->redirecionar) {
                    return $this->setRedirect($this->getRedirect('edit', $resul));
                }
            } else {
                $this->flashMessenger()->addErrorMessage('Erros de preenchimento acusado(s) pelo filter:');
                foreach ($form->getMessages() as $message){
                    $this->flashMessenger()->addErrorMessage($message);
                }
            }
        }


        if ($this->render OR is_array($resul)) {
            return $this->makeView(compact("form", "dataView", "entity", "resul"), $this->getPathViewDefault() . 'form.phtml');
        }
        return $resul;
    }

    /**
     * Metodo opcional para alterar a entidade que sera usada no form na action edit podendo alterar os seguintes parametros
     *     dataview para alterar parametros para phtml
     *     form     para alterar o formulario a ser renderizado
     *     request  para alterar e verificar fluxo do action
     *     resul    para alterar parametros para phtml
     *     entity   Entidade em que se esta sendo trabalhada
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 01-03-2016
     * @param array  $dataView
     * @param object $form
     * @param object $request
     * @param string $resul
     * @param object $entity
     */
    public function changeEntityForEdit(&$dataView, $form, $request, &$resul, $entity) {
        
    }

    /**
     * Metodo para redireciona o fluxo do sistema apos um insert ou update 
     * Por padrao apos um insert ou update com sucesso vai ser redirecionado para o index do controller em execução
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 29-01-2016
     * @param string $action Nome da action que esta solicitando os parametros de redirect
     * @param object $resul  Contem o resultado da operação uma entidade 
     * @return array Obrigatorio retornar um array vazio ou com os dados de redirecionamento
     */
    public function getRedirect($action, $resul) {
        return [];
    }

    /**
     * Apaga um registro do banco ou seta status da entidade como 'inativo',
     * recebendo como paramentro seu id. Se $forceRemove=TRUE, 
     * Entao registro e apagado definitivamente da base.
     * Faz a validaçao do form e chama o serviço para salvar no banco
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @since 02-11-2016 Logica para pegar o id do post se houver
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @since 29-04-2016  Adicao do $forceRemove
     * @version 1.8
     * @return redirect 302 | nothing
     */
    public function deleteAction($id = "", $forceRemove = FALSE) {
        if (empty($id)) {
            $id = $this->params()->fromRoute('id', '0');
        }
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost()->toArray();
            isset($post['id']) && !empty($post['id']) && $id = $post['id'];
            $this->sessao('post', $post);
        } else {
            $this->sessao('post', false);
        }

        if ($id != '0') {
            $service = $this->getService();
            $resul = $service->delete($id, $forceRemove);
            if (!$resul) {
                $this->flashMessenger()->addErrorMessage("Registro com o id = $id não foi excluido !!!");
            }
        } else {
            $this->flashMessenger()->addErrorMessage("Erro o id esta vazio ou zerado !!!");
        }
        if ($this->render) {
            return $this->setRedirect($this->getRedirect("delete", $id));
        } else {
            return $id;
        }
    }

    /**
     * Retorna o serviço que foi configurado para este controller
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @return \Application\Controller\service
     */
    public function getService() {
        if($this->serviceObj){
            return $this->serviceObj;
        }
        if ($this->haveServiceLocatorService) {
            $this->serviceObj = $this->getServiceLocator()->get($this->service);
            return $this->serviceObj->setController($this);
        }
        $this->serviceObj = new $this->service($this->getEm());
        return $this->serviceObj->setController($this);
    }

    /**
     * Retorna o form que foi configurado para este controller
     * Aceita passar parametro para form da action
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.2
     * @return \Application\Controller\form
     */
    public function getForm($opt = []) {
        if ($this->formWithEntityManager AND $this->formWithController) {
            return new $this->form($this->getEm(), array_merge(['controller' => $this], $opt));
        }
        if ($this->formWithController) {
            return new $this->form($opt, $this);
        }
        if ($this->formWithEntityManager) {
            return new $this->form($this->getEm(), $opt);
        }
        return new $this->form($opt);
    }

    /**
     * Seta se a instancia do servico desse controller vem do service locator ou 
     * Se instancia uma classe padrão com entity manager
     * Padrão é um serviço padrão com entity manager
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param boolean $haveServiceLocatorService
     * @return \Application\Controller\CrudController
     */
    public function setHaveServiceLocatorService($haveServiceLocatorService) {
        $this->haveServiceLocatorService = $haveServiceLocatorService;
    }

    /**
     * Seta se a instancia do form desse controller com ou sem entity manager 
     * Padrão é sem entity Manager
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param boolean $formWithEntityManager
     */
    public function setFormWithEntityManager($formWithEntityManager) {
        $this->formWithEntityManager = $formWithEntityManager;
    }

    /**
     * Seta se a instancia do form com um link para controller
     * Padrão é false
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param boolean $formWithController
     */
    public function setFormWithController($formWithController) {
        $this->formWithController = $formWithController;
    }

    /**
     * Setar o CRUD para fazer ou não o log as actions padrão.
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param boolean $boolean
     */
    public function setLog($boolean) {
        $this->log = $boolean;
    }

    /**
     * Pegar os dados do formulario e acrecenta os id do usuario que esta trabalhando no registro
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param \Zend\Http\Request $request
     * @param string $option
     * @return array
     */
    public function getDataWeb(\Zend\Http\Request $request, $option = 'createdBy') {
        $data = $request->getPost()->toArray();
        $user = $this->getUser();
        if ($user) {
            $data[$option] = $user['idUsuario'];
            $data['dataUser'] = $user;
        }
        return $data;
    }

    /**
     * Define se o redirecionamento vai ter parametros para dizer se tem o ajax
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param array $param
     * @return \Response
     */
    public function setRedirect($param = []) {
        $param['controller'] = isset($param['controller']) ? $param['controller'] : $this->controller;
        $param['action'] = isset($param['action']) ? $param['action'] : 'index';
        // Problema ao voltar para o index coloca paramentros que não podem existir na rota da paginação
        if ($this->getTerminalBoolean()) {
            $param['ajax'] = $this->getTerminalStr();
            if (strpos($this->route, 'default') !== false AND ! isset($param['id'])) {
                $this->route = str_replace('default', 'paginatorAjax', $this->route);
            }
        }
        return $this->redirect()->toRoute($this->route, $param);
    }

    /**
     * Pega ou cria a instancia do DoctrineManage
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEm() {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->em;
    }

    /**
     * Pega ou cria a instancia do ACL Helper
     * 
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.2
     * @return \Application\View\Helper\Acl 
     */
    public function getAcl() {
        if (null === $this->acl) {
            $this->acl = $this->getServiceLocator()
                    ->get('ViewHelperManager')
                    ->get('Acl');
            $this->acl->setUser($this->getUser());
        }
        return $this->acl;
    }

    /**
     * Verifica e retorna, se o usuário possui ou não o acesso baseado nos parametros
     * $resource, $privilege e $role
     * OBS: Caso o parametro $role contenha o valor 'noAdmin' e o usuário logado
     * seja um admin, então negar o valor resultante da verificação ACL. Desta 
     * forma, é possível contornar problema de retornar VERDADE para regras
     * de limitação, sendo que admin não pode ter limitações; 
     * 
     * @param string $resource
     * @param string|null $privilege
     * @param string|null $role
     * @return boolean
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @since 04-04-2016
     */
    public function checkAccess($resource = '', $privilege = null, $role = null) {
        $acl = $this->getAcl();
        $negateAdmin = false;
        if ("noAdmin" == $role) {
            $negateAdmin = $role;
            $role = null;
        }

        $resul = $acl($resource, $privilege, $role);

        if ($negateAdmin and "admin" == $this->getUser('role')) {
            return !$resul;
        }

        return $resul;
    }

    /**
     * Pega uma instancia do Param Helper
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 13-01-2016
     * @return \Application\View\Helper\Param
     */
    public function getParam() {
        if (null === $this->param) {
            $this->param = $this->getServiceLocator()
                    ->get('ViewHelperManager')
                    ->get('Param');
        }
        return $this->param;
    }

    /**
     * Pega uma instancia do Param Helper
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 25-05-2017
     * @return \Application\View\Helper\Table
     */
    public function getTable() {
        if (null === $this->table) {
            $this->table = $this->getServiceLocator()
                    ->get('ViewHelperManager')
                    ->get('Table');
        }
        return $this->table;
    }

    /**
     * Pega uma instancia do Image Helper
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 17-08-2016
     * @return \Application\View\Helper\Image
     */
    public function getImage() {
        if (null === $this->image) {
            $this->image = $this->getServiceLocator()
                    ->get('ViewHelperManager')
                    ->get('Image');
        }
        return $this->image;
    }

    /**
     * Facilita a montagem da view para o navegador.
     * Pode passar o segundo argumento como string que será colocado na var $layout
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param array   $params
     * @param boolean $terminal 
     * @param string  $layout
     * @return Zend\View\Model\ViewModel
     */
    protected function makeView($params, $terminal = '', $layout = '') {
        if (2 == func_num_args() && !is_bool($terminal)) {
            $layout = $terminal;
            $terminal = '';
        }
        if (empty($terminal)) {
            $terminal = $this->getTerminalBoolean();
        }
        $params['dataView']['ajax'] = $terminal;
        $this->view = new ViewModel($params);
        $this->view->setTerminal($terminal);
        if (!empty($layout)) {
            $this->view->setTemplate($layout);
        }
        return $this->view;
    }

    /**
     * Procura por parametros que indicam se ajax ou não
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @return boolean
     */
    protected function getTerminalBoolean() {
        $ajax = FALSE;
        if (isset($_GET['ajax']) AND $_GET['ajax'] == 'ok') {
            $ajax = TRUE;
        }
        if ($this->params()->fromRoute('ajax', 'no') == 'ok') {
            $ajax = TRUE;
        }
        if (!$ajax) {
            $this->layout('layout/layout-ajax');
        }
        return $ajax;
    }

    /**
     * retorna uma string para montagem de url com parametro ajax
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @return string
     */
    protected function getTerminalStr() {
        if ($this->getTerminalBoolean()) {
            return 'ok';
        }
        return 'no';
    }

    /**
     * Pega o caminho padrão para os arquivos phtml do controller atual
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @return string
     */
    protected function getPathViewDefault($controller = '') {
        if (empty($controller)) {
            $controller = $this->controller;
        }
        $arr = preg_split('/(?=[A-Z])/', $controller);
        if (count($arr) > 1) {
            $controller = '';
            foreach ($arr as $key => $value) {
                $controller .= (($key > 0) ? '-' : '') . strtolower($value);
            }
        }
        return strtolower($this->moduloName) . "/" . $controller . "/";
    }

    /**
     * Configura os titulos basicos para exiber nas actions
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.5 Melhoria Incluir itens a ser retornado antes de chamar a action
     * @since 14-11-2016
     * @param string $titulo
     * @param string $action
     * @return array
     */
    public function getDataView($titulo = '', $action = 'index') {
        if (is_bool($titulo)) {
            return $this->dataView;
        }
        $dataView = [
            'titulo' => $titulo,
            'action' => $action,
            'controller' => $this->controller,
            'route' => $this->route,
            'module' => $this->moduloName,
            'orderBy' => is_null($this->sessao('orderBy' . $this->name)) ? [] : $this->sessao('orderBy' . $this->name),
        ];
        if(is_array($this->dataView)){
            $this->dataView = array_merge($dataView,$this->dataView);
        }else{
            $this->dataView = $dataView;
        }
        return $this->dataView;
    }

    /**
     * Setar o controller para retorna ou não um view para tela
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param boolean $render default TRUE
     */
    public function setRender($render) {
        $this->render = $render;
    }

    /**
     * Guardar ou recuperar dados dos filtros para paginação
     * Ao fazer uma pesquisa com filtros os filtros é salvo em cache
     * Ao trocar de pagina e não existir o Post os filtros salvo anteriormente é recuperado
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @return array 
     */
    public function filtrosDaPaginacao() {
        //
        $this->sc = new SessionContainer($this->moduloName);
        $post = $this->getRequest()->isPost();
        if (is_int($this->params()->fromRoute('page')) AND $post) {
            $data = $this->getRequest()->getPost()->toArray();
            $this->sc->data = $data;
        }
        if (is_array($this->sc->data)) {
            return $this->sc->data;
        } else {
            return [];
        }
    }

    /**
     * Busca no sessao os dados do usuario do sistema.
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.2
     * @param string $indice Opcional recupera somente o valor do indice que foi passado
     * @return array Conteum todos os dados do usuario na sessão.
     */
    public function getUser($indice = "") {

        if (is_null($this->user)) {
            $this->user = $this->getServiceLocator()
                    ->get('ViewHelperManager')
                    ->get('UserIdentity');
            $this->user = $this->user->__invoke();
        }
        if (empty($indice)) {
            return $this->user;
        }
        return $this->user[$indice];
    }

    /**
     * Retorna o usuário da sessao (ou o usuário do ID definido no parametro $id)
     * 
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @since 08-07-2016
     * @param int $id Quando há um $id, retornar o Usuario referente a este $id
     * @return \Application\Entity\Usuario
     */
    public function getUserObject($id = FALSE) {
        if (!$id) {
            $id = $this->getUser('id');
        }

        return $this->getEm()->find("\Application\Entity\Usuario", $id);
    }

    /**
     * 
     * Configura um chamada para o repositorio que
     * Faz uma busca no BD pela requisição Ajax com parametro de busca
     * Na view retorna os dados no formato texto para o js exibir para o usuario
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @return \Zend\View\Model\ViewModel
     */
    public function autoCompAction($column = '', $valor = '', $anotherFilters = []) {
        if (is_array($column)) {
            $anotherFilters = $column;
            $column = "";
        }
        $subOpcao = "";
        $rules = "";
        if (empty($column)) {
            $data = $this->getRequest()->getPost()->toArray();
            $subOpcao = (isset($data["subOpcao"])) ? $data["subOpcao"] : "";
            $column = isset($data['column']) ? $data['column'] : 'nome';
            $valor = isset($data['data']) ? $data['data'] : '';
            $rules = isset($data["rules"]) ? $data["rules"] : FALSE;
        }
        $valor = trim($valor);

        //Com esta tag, é possível buscar todos os valores da base
        if ("*" == $valor) {
            $valor = "";
        }

        $anotherFilters = ($rules) ? $rules : $anotherFilters;

        $repository = $this->getEm()->getRepository($this->entity);
        $resultSet = $repository
                ->autoComp($column, $valor . '%', $anotherFilters);

        if (!$resultSet) {// Caso não encontre nada ele tenta pesquisar em toda a string
            $resultSet = $repository
                    ->autoComp($column, '%' . $valor . '%', $anotherFilters);
        }

        if ($this->render) {
            return $this->makeView(compact("resultSet", "subOpcao", "data"), TRUE);
        } else {
            return ["resultSet" => $resultSet, "subOpcao" => $subOpcao, "data" => $data];
        }
    }

    /**
     * Retorna um novo model para fazer o pdf que usa o plugin mpdf
     * 
     * @author PauloWatakabe <watakabe05@gmailcom>
     * @param string $template   Por padrão o template é a action
     * @return \Application\View\Model\PdfModel
     */
    public function getPdfModel($template = '') {
        $array = explode('\\', $this->params('controller'));
        $array[] = $this->params('action');
        $pdf = new PdfModel($array[3], lcfirst($array[2]), lcfirst($array[0]));
        if (!empty($template)) {
            $pdf->setTemplate($template);
        }
        return $pdf;
    }

    /**
     * Retorna um novo model para fazer o grafico que usa o jpgrafic
     * 
     * @author PauloWatakabe <watakabe05@gmailcom>
     * @since 30-01-2016
     * @param string $template   Por padrão o template é a action
     * @return \Application\View\Model\GraphModel
     */
    public function getGraphModel($template = '', $classPath = 'Application\View\Model\GraphModel') {
        if($classPath == 'GraphPie'){
            $classPath = 'Application\View\Model\GraphPieModel';
        }
        $array = explode('\\', $this->params('controller'));
        $array[] = $this->params('action');
        $graph = new $classPath($array[3], lcfirst($array[2]), lcfirst($array[0]));
        if (!empty($template)) {
            $graph->setTemplate($template);
        }
        return $graph;
    }

    /**
     * 
     * @param type $message
     * @param type $type
     */
    public function showMessage($message, $type = "success") {
        $functionMessenger = "add" . ucfirst($type) . "Message";
        if (method_exists($this->flashMessenger(), $functionMessenger)) {
            $this->flashMessenger()->{$functionMessenger}($message);
        } else {
            $this->flashMessenger()->addSuccessMessage($message);
        }
    }

    /**
     * Retorna ou gera e retorna uma instancia do serviço de email para enviar um email
     * Usa o service locator para carregar as dependencias e configura o serviço com dados default
     * 
     * @author PauloWatakabe <watakabe05@gmailcom>
     * @since 04-02-2016
     * @return \Application\Service\Email
     */
    public function getEmail() {
        if (is_null($this->email)) {
            $transport = $this->getServiceLocator()->get('Application\Mail\Transport');
            $this->view = $this->getServiceLocator()->get('View');
            $this->email = new \Application\Service\Email($this->getEm(), $transport, $this->view);
            $this->email->setController($this);
            $this->email->setTemplatePath($this->getPathViewDefault());
            $this->email->setTemplate($this->params('action'));
        }
        return $this->email;
    }

    /**
     * Alias para parent::params()
     * @param type $str
     * @return type
     */
    public function params($str = FALSE) {
        if (!$str) {
            return parent::params();
        }
        return parent::params($str);
    }
    
    /**
     * Metodo para verificar se no request tem as flags para atualizar tela
     *    loadCad Carregar os dados de funcionario ou funcionaro baseado nos dados do post.
     *    Normalmente utilizado em conjunto com getEntityForNew changeEntityForEdit
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 19-10-2016
     * @version 1.2 Incluir a possibilidade de carregar os campos deixados na sessao.  
     * @since 22-03-2017
     * @param \Zend\Stdlib\RequestInterface $request
     * @return boolean|array
     */
    public function checkDataForm($request) {
        if (!$request->isPost()) {
            $data = $this->sessao('post');
        }else{
            $data = $request->getPost()->toArray();
        }
        if (!isset($data['subOpcao'])) {
            return false;
        }
        if ('loadCad' !== $data['subOpcao']) {
            return false;
        }
        return $data;
    }  

}
