<?php

namespace Application\Form;

use Zend\Form\Form;
use Zend\Authentication\AuthenticationService,
    Zend\Authentication\Storage\Session as SessionStorage;

/**
 * AbstractForm
 * Abstração dos inputs + usados para montagem do from
 * @author Paulo Cordeiro Watakabe <watakabe05@gmail.com>
 * 
 */
abstract class AbstractForm extends Form {

    /**
     * Objeto para manipular dados do BD
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * Para setar o form corretamente para edição de dados
     * @var bollean 
     */
    protected $isEdit = false;

    /**
     * Para setar o form corretamente para edição de dados
     * @var bollean 
     */
    protected $isAdmin = false;

    /**
     * Objeto que pega os dados do usuario armazenado
     * @var \Zend\Authentication\AuthenticationService
     */
    protected $authService;

    /**
     * O nome do Modulo
     * @var array
     */
    protected $defaultAttributes = [];

    /**
     * O nome do Modulo
     * @var string
     */
    protected $moduloName;

    /**
     * Retorno onde diz se este form é o principal ou secundario (default é falso para ser o form principal)
     * 
     * @var boolean default é falso para ser o form principal
     */
    protected $ret;

    /**
     * Formulario a ser montando os imputs padrão é ser ele mesmo
     * @var \Zend\Form\Form
     */
    protected $targetForm;

    /**
     * Prefixo para nome dos inputs do another form
     * @var array
     */
    protected $prefix = [];

    /**
     * O nome do Formularios
     * @var string
     */
    protected $name;

    /**
     * Controller em que esta sendo trabalho 
     *      Acessar seu metodos e o principal ter acesso ao service locator.
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.2
     * @var object of \Application\Controller\($controller_name) 
     */
    protected $controller;

    /**
     * 
     * @author Paulo Watakabe
     * @version 1.0
     * @param string $name Nome do forulario
     * @param array $options parametros do formulario
     */
    public function __construct($name = null, $options = [], $ret = false) {        
        $this->redirectParams($name, $options, $ret);
        if(is_null($this->name)){
            $this->name  = (is_string($name))? $name: lcfirst(str_replace('\\', '', str_replace(__NAMESPACE__, '', get_class($this))));
        }
        parent::__construct($this->name, $options);
        $this->setInputHidden('ret',['value' => '#inter']);
        $this->moduloName = "Application";
        $this->targetForm = $this;
        $this->setDefaultAttributes(['onkeydown' => 'return changeEnterToTab(this,event)']);
        if ($this->ret == FALSE) {
            $this->ret = TRUE;
            $filters = isset($options["filters"]) ? $options["filters"] : TRUE;
            $this->setAttribute('method', 'post');
            $this->setAttribute('id', 'formSistema');
            $this->setAttribute('name', $this->name);
            $this->setIsAdmin();
            $this->setAllInputs($filters);
        }
    }

    /**
     * Faz o redirecionamento das variaveis facilitando a configuração do form para o uso
     * 
     * @author Paulo Watakabe
     * @version 1.0
     * @param \Doctrine\ORM\EntityManager $name    Nome do formulario ou instancia do entity manager
     * @param array                       $options Array com opçoes do form
     * @param boolean                     $ret     default é falso para ser o form principal
     */
    public function redirectParams(&$name, &$options, &$ret) {
        // Redireciona name para entity manager se for um objeto
        if (is_object($name) AND $name instanceof \Doctrine\ORM\EntityManager) {
            $this->em = $name;
        }
        // Redirecionamento de parametro retorno onde diz se este form é o principal ou secundario padrao é falso para ser o principal
        if (is_bool($name)) {
            $ret = $name;
        }
        // Redirecionamento de parametro retorno onde diz se este form é o principal ou secundario padrao é falso para ser o principal
        // redireciona option para ret se for boolean e converte options para array
        if (is_bool($options)) {
            $ret = $options;
            $options = [];
        }
        // Redirecionamento de parametro controller a ser usado no form
        // Redireciona ret para controller se for um objeto e converte ret para falso
        if (is_object($ret)) {
            $this->controller = $ret;
            $ret = FALSE;
        }
        //Quando existir o parametro 'ret' e ele for TRUE, então o Form será criado
        //sem chamar a funcao 'setInputs' no construtor
        if(isset($options["ret"])){
            $ret = $options["ret"];
        }
        // Setar a variavel controller caso exitir no options
        if (isset($options['controller'])) {
            $this->controller = $options['controller'];
            unset($options['controller']);
        }
        $this->ret = $ret;
    }

    /**
     * Metodo usado para setar campos do form ex: desabilita-los
     * Este metodo é chamado por padrao pelo helperForm na view pelo metodo formInit()
     * 
     * OBS : Funcao de override que é executada na montagem do Form 
     * 
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @author Paulo Watakabe <watakabe05@gmail.com>
     */
    public function preFormInit() {
        
    }

    /**
     * Desabilita todos os campos da view
     * presentes no parametro $fields
     * 
     * @param array $fields Array de string contendo o nome
     * do campo a ser desabilitado
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @author Paulo Watakabe <paulo.watakabe@tcmed.com.br>
     * @since 08/01/2016
     * @see preFormInit
     */
    public function setArrayDisabled(array $fields = []) {
        foreach ($fields as $field) {
            $this->getTargetForm()->preFixName($field);
            if ($this->getTargetForm()->has($field)) {
                $this->getTargetForm()->get($field)->setAttribute("disabled", "true");
            }else{
                $message = "O campo " . $field . " não foi encontrado ".
                        "no form para ser desabilitado! Verifique nas funcoes ".
                        "'preFormInit' (presente no Form) se os campos foram ".
                        "digitados erroneamente, ou se o prefixo contém erros";
                
                throw new \Exception($message);
            }
        }
    }

    /**
     * Facilitar a chamada dos metodos que esta no controller
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
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
     * @author Paulo Watakabe <watakabe05@gmail.com>
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
     * @return object of Controller
     */
    public function getController() {
        return $this->controller;
    }

    /**
     * Faz a configuração do form para injetar o itens desse form em outro form 
     * Os inputs injetados serão prefixados e os dados ficaram em array separado
     * Faz o load dos campos que serão compartilhado por outros forms com ou sem filtro
     * @author Paulo Watakabe
     * @version 1.0
     * @param \Zend\Form\Form $targetForm
     */
    public function getBaseForm($targetForm, $filter = TRUE, $method = 'setInputs') {
        $this->setTargetForm($targetForm);
        $this->setPreFix($this->name);
        $this->getBaseElements($filter, $method);
        $this->removePreFix($this->name);
    }

    /**
     * Chamada basica para importar os elementos do form 
     * Normalmente usada no construct de um form padrao que nao é field set de outro form
     * @author Paulo Watakabe
     * @version 1.0
     * @param boolean $filter Diz se o form  vai ter filter
     * @param string $method Diz qual methodo da class em que esta os elementos dess form
     */
    public function setAllInputs($filter = TRUE, $method = 'setInputs') {
        //$csrf = new \Zend\Form\Element\Csrf('security');
        //$this->add($csrf);
        $this->setInputSubmit('submit', 'Salvar');
        $this->getBaseElements($filter, $method);
    }

    /**
     * Recebe o nome da função a ser chamada e valida se existe.
     * Executa a função chamada passando boolean como parametro
     * Faz redirecionamento de paramentro caso o 1º parametro passado for string
     * @author Paulo Watakabe
     * @version 1.0
     * @param boolean $filter
     * @param string $method
     */
    public function getBaseElements($filter = TRUE, $method = 'setInputs') {
        if (is_string($filter)) {
            $method = $filter;
            $filter = TRUE;
        }
        if (method_exists($this, $method) == FALSE) {
            echo '<h1>ATENÇÃO METODO ' . $method . ' NÃO EXISTE NA CLASSE ' . $this->name;
            die;
        }
        $this->$method($filter);
    }

    /**
     * Setar o valor a ser prefixado para busca no formulario
     * @author Paulo Watakabe
     * @version 1.0
     * @param string $preFix
     */
    public function setPreFix($preFix = '') {
        if (empty($preFix)) {
            $this->getTargetForm()->prefix = [];
        }
        $this->getTargetForm()->prefix[] = $preFix;
    }

    /**
     * Remove o valor prefixado para gerar os nomes dos campos.
     * @author Paulo Watakabe
     * @version 1.0
     * @param string $name
     */
    public function removePreFix($name = '') {
        $removed = array_pop($this->targetForm->prefix);
        if (is_null($removed)) {
            echo '<h2>não existe prefixamento ', $name, '</h2>';
        }
    }

    /**
     * Pega a referencia da variavel a ser tradada e prefixa a mesma ou não
     * Faz o prefixamento em arvoré chegando aos seus galhos.
     * @author Paulo Watakabe
     * @version 1.0
     * @param string $name
     * @return nothing
     */
    public function preFixName(&$name) {
        $prefixs = $this->getTargetForm()->prefix;
        if (empty($prefixs)) {
            return;
        }
        $basePrefix = '';
        foreach ($prefixs as $key => $prefix) {
            if ($key == 0) {
                $basePrefix = $prefix;
            } else {
                $basePrefix .= '[' . $prefix . ']';
            }
        }
        if (strpos($name, '[') !== FALSE) {
            $name = $basePrefix . '[' . str_replace('[', '][', $name);
        } else {
            $name = $basePrefix . '[' . $name . ']';
        }
    }

    /**
     * Deve ser setado para que o inputs sejam colocados no form correto
     * @author Paulo Watakabe
     * @version 1.0
     * @param type $form
     */
    public function setTargetForm($form) {
        $this->targetForm = $form;
    }

    /**
     * Retorna a instancia do formulario principal caso nao tenha retorna ele mesmo.
     * @author Paulo Watakabe
     * @version 1.0
     * @return \Application\Form\AbstractForm
     */
    public function getTargetForm() {
        $this->checkTarget();
        if (is_null($this->targetForm)) {
            echo '<h1>Nao foi definido o form target do campo <h1>';
            die;
        }
        return $this->targetForm;
    }

    /**
     * Injeta o input criado em seu form padrão 
     * @author Paulo Watakabe
     * @version 1.0
     * @param type $input
     */
    public function injectInput($input) {
        $this->checkTarget();
        $this->targetForm->add($input);
    }

    /**
     * Seta o filtro onde ele deve incluir os filtros.
     * Seta o controller no filtro para ter acesso principalmente ao service locator
     * SE Já existe um filter setado neste form e apenas adiciona os novos filtros
     * SE Já parametrizou o novo filter e precisa adiciona lo a classe abstrata do form
     * E Novo filter primeiro faz as parametrizações nele antes de adiciona na classe abstrata
     * @author Paulo Watakabe
     * @version 1.0.2
     * @param \Zend\InputFilter\InputFilterInterface $inputFilter
     */
    public function setInputFilter(\Zend\InputFilter\InputFilterInterface $inputFilter, $parent = false) {
        $this->checkTarget();
        // Já existe um filter setado neste form e apenas adiciona os novos filtros
        if (!is_null($this->targetForm->filter)) {
            $this->targetForm->filter->addAnotherFilter($inputFilter, $inputFilter->method);
            return;
        }
        // Já parametrizou o novo filter e precisa adiciona lo a classe abstrata do form
        if ($parent) {
            parent::setInputFilter($inputFilter);
            return;
        }
        // Novo filter primeiro faz as parametrizações nele antes de adiciona na classe abstrata
        $inputFilter->setController($this->controller);
        if (method_exists($inputFilter, $inputFilter->method)) {
            $inputFilter->addAnotherFilter($inputFilter, $inputFilter->method);
        }
        $this->targetForm->setInputFilter($inputFilter, TRUE);
    }

    /**
     * Verifica a existencia de um target caso não exista coloca ele mesmo como target
     * @author Paulo Watakabe
     * @version 1.0
     */
    public function checkTarget() {
        if (isset($this->targetForm) == FALSE) {
            $this->targetForm = $this;
        }
        if (is_null($this->targetForm)) {
            $this->targetForm = $this;
        }
    }

    /**
     * Retorna um array com as opções padrão de um formulario.
     * @author Paulo Watakabe
     * @version 1.0
     * @param boolean $onBlur
     * @return array
     */
    function getDefaultAttributes($onBlur = FALSE) {
        if ($onBlur) {
            return array_merge($this->defaultAttributes, ['onBlur' => 'toUp(this)']);
        }
        return $this->defaultAttributes;
    }

    /**
     * Atributos comuns a todos os campos do formularios
     * @author Paulo Watakabe
     * @version 1.0
     * @param array $defaultAttributes
     * @return \Application\Form\AbstractForm
     */
    function setDefaultAttributes(array $defaultAttributes = []) {
        $this->defaultAttributes = $defaultAttributes;
        return $this;
    }

    /**
     * Cria um campo oculto do form
     * 
     * @author Paulo Watakabe
     * @version 1.0
     * @param string $name       Nome do campo oculto
     * @param array $attributes  Atributos do campo
     * @param array $options     Opções de visualização
     */
    public function setInputHidden($name, array $attributes = [], array $options = []) {
        $this->preFixName($name);
        $hidden = new \Zend\Form\Element\Hidden();
        $hidden->setAttributes(['name' => $name, 'id' => $name]);
        $hidden->setAttributes($attributes);
        $hidden->setOptions($options);
        $this->injectInput($hidden);
    }

    /**
     * Monta os paramentro basicos para se fazer um input text
     * @author Paulo Watakabe
     * @version 1.0
     * @param string $name
     * @param string $label
     * @param array $attributes
     * @param bollean $onblur
     */
    public function setInputText($name, $label, array $attributes = [], $onblur = TRUE) {
        $this->preFixName($name);
        $input = new \Zend\Form\Element\Text();
        $input->setAttributes(['name' => $name, 'id' => $name]);
        $input->setAttributes($this->getDefaultAttributes($onblur));
        $input->setLabel($label);
        if (empty($attributes) == FALSE) {
            $input->setAttributes($attributes);
        }
        $this->injectInput($input);
    }

    /**
     * Monta os paramentro basicos para se fazer um input text
     * Sem a função que coloca o texto em maiusculo
     * @author Paulo Watakabe
     * @version 1.0
     * @param string $name
     * @param string $label
     * @param array $attributes
     */
    public function setInputText2($name, $label, array $attributes = []) {
        $this->setInputText($name, $label, $attributes, FALSE);
    }

    /**
     * Monta os paramentro basicos para se fazer um input text Area
     * @author Paulo Watakabe
     * @version 1.0
     * @param string $name
     * @param string $label
     * @param array $attributes
     */
    public function setInputTextArea($name, $label, array $attributes = array()) {
        $this->preFixName($name);
        $input = new \Zend\Form\Element\Textarea();
        $input->setAttributes(['name' => $name, 'id' => $name]);
        $input->setLabel($label)->setOptions(['rows' => '6', 'cols' => '200']);
        $input->setAttributes($this->getDefaultAttributes());
        if (empty($attributes) == FALSE) {
            $input->setAttributes($attributes);
        }
        $this->injectInput($input);
    }

    /**
     * Monta os paramentro basicos para se fazer um input select
     * @author Paulo Watakabe
     * @version 1.0
     * @param string $name
     * @param string $label
     * @param array  $options
     * @param array  $attributes
     */
    public function setInputSelect($name, $label, array &$options = [], array $attributes = []) {
        $this->preFixName($name);
        $input = new \Zend\Form\Element\Select();
//        $input->setDisableInArrayValidator();
        $input->setAttributes(['name' => $name, 'id' => $name]);
        $input->setAttributes($this->getDefaultAttributes());
        $input->setLabel($label);
        if (!empty($attributes)) {
            $input->setAttributes($attributes);
        }
        $input->setValueOptions($options);
        $this->injectInput($input);
    }

    /**
     * Monta os paramentro basicos para se fazer um input submit
     * @author Paulo Watakabe
     * @version 1.0
     * @param string $name
     * @param string $label
     * @param array  $attributes
     * @param boolean  $ajax     Inserir ou não chamada Ajax default true
     */
    public function setInputSubmit($name, $label, array $attributes = [], $ajax = TRUE) {
        $this->preFixName($name);
        $input = new \Zend\Form\Element\Button();
        $input->setAttributes(['name' => $name, 'id' => 'btnEnviarForm', 'value' => $label, 'class' => 'btn btn-success enviarForm']);
        $input->setLabel($label);
//        if ($ajax) {
//            $input->setAttribute('onClick', 'return saveForm(this);');
////            $input->setAttribute('onClick', 'return action.sendForm(this);');
//        }
        if (empty($attributes) == FALSE) {
            $input->setAttributes($attributes);
        }
        $this->injectInput($input);
    }

    /**
     * Monta os paramentro basicos para se fazer um input button
     * @author Paulo Watakabe
     * @version 1.0
     * @param string $name
     * @param string $label
     * @param array  $attributes
     */
    public function setInputButton($name, $label = '', array $attributes = []) {
        $attributes = array_merge($attributes, ['id' => $name]);
        $this->setInputSubmit($name, $label, $attributes, FALSE);
    }

    public function setInputRadio($name, $label, $options, $attributes = []) {
        $this->preFixName($name);
        $input = new \Zend\Form\Element\Radio();
        $input->setAttributes(['name' => $name, 'id' => $name]);
        $input->setAttributes($this->getDefaultAttributes());
        if (empty($attributes) == FALSE) {
            $input->setAttributes($attributes);
        }
        $input->setLabel($label);
        $input->setValueOptions($options);
        $this->injectInput($input);
    }

    /**
     * Cria um elemento do tipo File upload para form.
     * 
     * @author Paulo Watakabe
     * @version 1.0
     * @since 13-01-2016
     * @param string $name     Nome do campo no form  
     * @param string $label    Exbir um rotulo
     * @param array $options   Array para setar opçoes para class \Zend\Form\Element\File
     * @param array $attributes Array para setar Atributos para class \Zend\Form\Element\File
     */
    public function setFileUpload($name, $label, $options = [], $attributes = []) {
        $this->preFixName($name);
        $input = new \Zend\Form\Element\File();
        $input->setAttributes(['name' => $name, 'id' => $name]);
        $input->setAttributes($this->getDefaultAttributes());
        if (empty($attributes) == FALSE) {
            $input->setAttributes($attributes);
        }
        if (empty($options) == FALSE) {
            $input->setOptions($options);
        }
        $input->setLabel($label);
        $this->injectInput($input);
    }

    /**
     * Funçao basica para inserir um checkbox no formulario
     * 
     * @author Paulo Watakabe
     * @version 1.0
     * @param string $name   
     * @param string $label
     * @param array $options
     * @param array $attributes
     * @return \Zend\Form\Element\Checkbox
     */
    public function setInputCheckbox($name, $label, array $options = [], array $attributes = []) {
        $this->preFixName($name);
        $checkbox = new \Zend\Form\Element\Checkbox();
        $checkbox->setAttributes(['name' => $name, 'id' => $name]);
        $checkbox->setAttributes($this->getDefaultAttributes());
        $checkbox->setUseHiddenElement(FALSE);
        $checkbox->setLabel($label);
        if (!empty($attributes)) {
            $checkbox->setAttributes($attributes);
        }
        $checkbox->setOptions($options);
        $this->injectInput($checkbox);
    }

    /**
     * Funçao basica para inserir um Multi checkbox no formulario
     * 
     * @author Paulo Watakabe
     * @version 1.0
     * @param string $name
     * @param string $label
     * @param array $options
     * @param array $attributes
     * @return \Zend\Form\Element\MultiCheckbox
     */
    public function setInputMultiCheckbox($name, $label, array $options, array $attributes = []) {
        $this->preFixName($name);
        $mCheckebox = new \Zend\Form\Element\MultiCheckbox();
        $mCheckebox->setAttributes(['name' => $name, 'id' => $name]);
        $mCheckebox->setAttributes($this->getDefaultAttributes());
        $mCheckebox->setUseHiddenElement(FALSE);
        if (!empty($attributes)) {
            $mCheckebox->setAttributes($attributes);
        }
        $mCheckebox->setLabel($label)->setOptions(['value_options' => $options]);
        $this->add($mCheckebox);
        $this->injectInput($mCheckebox);
    }

    /**
     * Função para setar varios inputs com com algo padrão
     * Por padrão o array são os inputs visiveis na tela
     * 
     * @author Paulo Watakabe
     * @version 1.0
     * @param string $key
     * @param string $attribute
     * @param array  $inputs
     * @return void no return
     */
    public function addAttributeInputs($key, $attribute, array $inputs = []) {
        if (empty($inputs)) {
            return;
        }
        foreach ($inputs as $input) {
            $this->get($input)->setAttribute($key, $attribute);
        }
    }

    /**
     * Baseado na $key busca os registro cadastrados e monta um array para select
     * @author Paulo Watakabe
     * @version 1.0
     * @param string $key
     * @param boolean $shift
     * @return array
     * 
     */
    public function getParametroSelect($key, $shift = false) {
        $array = $this->em->getRepository($this->moduloName . '\Entity\ParametroSis')->fetchPairs($key);
        if ($shift) {
            $retira = array_shift($array);
        }

        return $array;
    }

    /**
     * Busca os dados do usuario da storage session
     * Retorna a entity com os dados do usuario
     * @author Paulo Watakabe
     * @version 1.0
     * @param Array $data com os campos do registro
     * @return array | boolean
     */
    public function getIdentidade() {
        if (is_object($this->authService)) {
            return $this->authService->getIdentity();
        } else {
            $sessionStorage = new SessionStorage($this->moduloName);
            $this->authService = new AuthenticationService;
            $this->authService->setStorage($sessionStorage);
            if ($this->authService->hasIdentity()) {
                return $this->authService->getIdentity();
            }
        }
        return FALSE;
    }

    /**
     * Set o form se o usuario é admin ou não.
     * @author Paulo Watakabe
     * @version 1.0
     */
    public function setIsAdmin() {
        /* @var $user \Application\Entity\Usuario toArray() */
        $user = $this->getIdentidade();
        if (isset($user['is_admin']) AND ! is_null($user['is_admin'])) {
            $this->isAdmin = TRUE;
        } else {
            $this->isAdmin = FALSE;
        }
    }

    /**
     * Gera um simples input text 
     * @author Paulo Watakabe
     * @version 1.0
     * @param string $name
     */
    public function setSimpleText($name) {
        $this->setInputText2($name, ucfirst($name) . ': ', ['placeholder' => 'Digite ' . $name]);
    }

    /**
     * Gera um simples input text sem a função onblur
     * @author Paulo Watakabe
     * @version 1.0
     * @param type $name
     */
    public function setSimpleText2($name) {
        $this->setInputText($name, ucfirst($name) . ': ', ['placeholder' => 'Digite ' . $name]);
    }

    /**
     * Baseado na $key busca os registro cadastrados e monta um array para select
     * @author Paulo Watakabe
     * @version 1.0
     * @param string $key
     * @param array $filters
     * @param boolean $first
     * @return array
     * 
     */
    public function getParametroChave($key, $filters = [], $first = TRUE) {
        $array = $this->em->getRepository('Application\Entity\Parametros')->fetchPairs($key, $filters, $first);

        return $array;
    }

    /**
     * Retorna a variavel de configuração do form onde diz se este formulario tem dependencia de outro formulario
     * Caso tenha uma dependencia de outro formulario mas que seja necessario carregar seus proprio filtro
     * Deve ser atribuito um valor string no construct do form para que antes de carregar seus inputs tambem seja carregada os filtros
     * No caso se o valor da variavel for first será entendido que será retorna false para o carregamento correto do filter 
     * E atribuito o boolean true para continuar carregando seus forms dependentes
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @return boolean
     */
    public function getRet() {
        if ($this->ret == 'first') {
            $this->ret = TRUE;
            return FALSE;
        }
        return $this->ret;
    }
    
    /**
     * Busca valor do input que esta no form.
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 16-10-2016 
     * @param string $ele Nome do Campo a ser retornado seu valor
     * @param mixed $default Opcional valor a ser retornado caso não exista o input
     * @return string
     */
    public function getValue($ele = '', $default = '') {
        if(empty($ele)){
            return $default;        
        }
        if($this->has($ele)){
            $vlr = $this->get($ele)->getValue();
            return is_null($vlr) ? $default : $vlr;
        }
        return $default;        
    }
    
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 21-12-2016
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEm(): \Doctrine\ORM\EntityManager {
        return $this->em;
    }

    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 21-12-2016
     * @param \Doctrine\ORM\EntityManager $em
     * @return $this
     */
    public function setEm(\Doctrine\ORM\EntityManager $em) {
        $this->em = $em;
        return $this;
    }
    
    /**
     * Seta o nome do formulario
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @since 06/02/2017
     * @version 1
     * @param string $name
     * @return $this
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    
    /**
     * Nome do formulario
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 22-05-2017
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Retorna os prefix registrados no form
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 22-05-2017
     * @return array
     */
    public function getPrefix() {
        return $this->prefix;
    }
}
