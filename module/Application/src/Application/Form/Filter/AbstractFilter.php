<?php

namespace Application\Form\Filter;

use Zend\InputFilter\InputFilter;

/**
 * AbstractFilter
 * Metodos abstraidos e encapsulados de filtro para usar no form
 * 
 * @author Paulo Cordeiro Watakabe <watakabe05@gmail.com>
 */
class AbstractFilter extends InputFilter {

    /**
     * Prefixo para nome dos inputs do another form
     * @var array
     */
    protected $prefix = [];

    /**
     * Se campo a ser criado precisa se prefixado ou não
     * @var boolean
     */
    protected $ret;

    /**
     * Na da classe Filtro
     * @var string
     */
    protected $name;

    /**
     * Nome do metodo a ser carregado o padrão é getFilters
     * @var string
     */
    public $method;

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
     * Em qual classe deve ser gerado o filtro por padrão é nele mesmo
     * @var \Zend\InputFilter\targetFilter
     */
    protected $targetFilter;

    /**
     * Metodo para carregamento dos filtros do form prefixando conforme parametros. 
     * Se for um filtro que nao é dependencia para outros e não tem dependencia a carregar ja carrega os filtros no construct
     *  
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param string   $name     O nome do filtro
     * @param boolean  $ret      Diz se este filtro vai ser carregado em outro filtro
     * @param array    $prefix   Para prefixar os campos caso esteja sendo carregado em outro
     * @param string   $method   Metodo a ser chamado contendo os filtros a serem carregados default getFilters()
     */
    public function __construct($name = '', $ret = false, $prefix = [], $method = 'getFilters') {
        $this->ret = $ret;
        $this->setTargetFilter($this);
        $this->name = $name;
        $this->prefix = $prefix;
        $this->method = $method;
//        if($ret == FALSE){
//            $this->getBasefilters($this->ret,$this->method);
//        }
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
        if (is_null($this->controller)) {
            $this->controller = $controller;
        }
        return $this;
    }

    /**
     * Recebe o nome da função a ser chamada e valida se existe.
     * Executa a função chamada passando boolean como parametro
     * Opção de fazer redirecionamento de paramentro caso o 1º parametro passado for string
     * @param boolean $ret    true diz que os filtros estao sendo injetados em outro filter
     *                        false diz que os filtros vão ser injetados nele mesmo
     * @param string $method  metodo a ser chamado para pegar os filtros default getFilters
     *                        passa como parametro o $ret descrito acima
     */
    public function getBasefilters($ret = FALSE, $method = 'getFilters') {
        if (is_string($ret) AND $ret != 'first') {
            $method = $ret;
            $ret = FALSE;
        }
        if (method_exists($this, $method) == FALSE) {
            echo '<h1>ATENÇÃO METODO ' . $method . ' NÃO EXISTE NA CLASSE ' . $this->name . ' called in abstractFilter';
            die;
        }
        $this->$method($ret);
    }

    /**
     * Pega a referencia da variavel a ser tradada e prefixa a mesma ou não
     * @param string $name
     * @return nothing
     */
    public function preFixName(&$name) {
        if (empty($this->prefix) OR $this->ret == FALSE) {
            return;
        }
        $basePrefix = '';
        foreach ($this->prefix as $key => $prefix) {
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
     * Adiciona outros filtros para complementar o filtro dinamico
     * @param \Application\Form\Filter\AbstractFilter $objFilter
     * @param string $method  metodo a ser chamado para pegar os filtros default getFilters
     */
    public function addAnotherFilter($objFilter, $method = 'getFilters') {
        //Diz para o filtro onde ele deve injetar os filtros
        $objFilter->setTargetFilter($this);
        //Pede para gerar os filtros
        $objFilter->getBasefilters($this->ret, $method);
    }

    /**
     * Seta o filtro onde ele deve incluir os filtros.
     * @param \Zend\InputFilter\InputFilter $objFilter
     */
    public function setTargetFilter($objFilterTarget) {
        $this->targetFilter = $objFilterTarget;
    }

    /**
     * Não permitir campo vazio.
     * @param type $name do input a validar
     */
    public function notEmpty($name) {
        $this->preFixName($name);
        if (is_null($this->targetFilter)) {
            $this->targetFilter = $this;
        }
        $this->targetFilter->add(array(
            'name' => $name,
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array('isEmpty' => 'Não pode estar em branco'),
                    ),
                ),
            ),
        ));
    }

    /**
     * Verifica se um campo do form é igual ao outro
     * @param string $name
     * @param string $to
     * @param string $msg opcional padrão é para senhas
     */
    public function identical($name, $to, $msg = 'A senha não confere') {
        $this->preFixName($name);
        $this->targetFilter->add(array(
            'name' => $name,
            'validators' => array(
                array(
                    'name' => 'Identical',
                    'options' => array(
                        'token' => $to,
//                        'messages' => array('isEmpty' => $msg),
                        'message' => $msg,
                    ),
                ),
            )
        ));
    }

    /**
     * Valida o formato do email
     * @param type $name
     * @param type $messageString opcional padrão é para email
     */
    public function email($name, $messageString = 'Digite um email valido') {
        $this->preFixName($name);
        $validator = new \Zend\Validator\EmailAddress();
//        $validator->setOptions(array('domain'=>false));   
        $validator->setMessage($messageString);
        $this->targetFilter->add(array(
            'name' => $name,
            'validators' => array($validator)
        ));
    }

    /**
     * Forçar a não validar estes campos
     * Especie do bug no zf2 que força a validação dos campos selects
     * @param string $name
     */
    public function emptyTrue($name) {
        $this->preFixName($name);
        $this->targetFilter->add(array(
            'name' => $name,
            'required' => false,
        ));
    }

    /**
     * Validador do campo CPF
     * @param string $name do input a validar
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @since 20/01/2016
     */
    public function validarCpf($name) {
        $this->preFixName($name);
        if (is_null($this->targetFilter)) {
            $this->targetFilter = $this;
        }
        $this->targetFilter->add(array(
            'name' => $name,
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => [
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array('isEmpty' => 'Campo não pode estar em branco'),
                    ),
                ),
                array(
                    'name' => 'Adm\Form\Validator\ValidaCpf',
                ),
            ],
        ));
    }

    /**
     * Valida o CNPJ
     * @param string $name do input a validar
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @since 20/01/2016
     */
    public function validarCnpj($name) {
        $this->preFixName($name);
        if (is_null($this->targetFilter)) {
            $this->targetFilter = $this;
        }
        $this->targetFilter->add(array(
            'name' => $name,
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => [
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array('isEmpty' => 'Campo não pode estar em branco'),
                    ),
                ),
                array(
                    'name' => 'Adm\Form\Validator\ValidaCnpj',
                )
            ],
        ));
    }

    /**
     * Valida o CNPJ
     * @param string $name do input a validar
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @since 20/01/2016
     */
    public function validarPis($name) {
        $this->preFixName($name);
        if (is_null($this->targetFilter)) {
            $this->targetFilter = $this;
        }
        $this->targetFilter->add(array(
            'name' => $name,
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => [
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array('isEmpty' => 'Campo não pode estar em branco'),
                    ),
                ),
                array(
                    'name' => 'Adm\Form\Validator\ValidaPis',
                ),
            ],
        ));
    }

}
