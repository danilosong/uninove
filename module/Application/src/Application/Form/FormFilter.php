<?php

/*
 * License
 */

namespace Application\Form;

/**
 * Description of Grupo
 *
 * @author Danilo Dorotheu
 */
class FormFilter extends AbstractForm{
 
    /**
     * Injeta outro formulario como depencia padrão é sem dependencia.
     *  
     * @var string caminho para instanciar e injetar outro formulario
     */
    protected $dependencia;


    public function __construct($name = '', $options = array(), $ret = false) {
        if(isset($options['dependencia'])){
            $this->dependencia = $options['dependencia'];
            unset($options['dependencia']);
        }
        parent::__construct($name, $options, $ret);
    } 
    
    public function setInputs($filter=TRUE) {
//        if($filter){
//            $this->setInputFilter(new Filter\FormFilter($this->name, $this->ret, $this->getTargetForm()->prefix));
//        }           
        
        $this->setInputHidden('ret', ['value' => '#inter']);
        $this->setInputHidden('page', ['value' => '1']);
        $this->setInputText('pageRange','Paginas',['value' => '20']);
        $options = ['10' => '10 registros', '20' => '20 registros', '50' => '50 registros', '100' => '100 registros', '250' => '250 registros', '500' => '500 registros', '1000' => '1.000 registros'];
        $this->setInputSelect('limitePag', 'Registro por pagina', $options, ['value' => '100', 'onChange' => 'atualizaPag()']);
        $this->setInputHidden('filters'  ,['value' => '']);
        $this->setInputHidden('ini'      ,['value' => '']);
        $this->setInputHidden('fim'      ,['value' => '']);
        $this->setInputHidden('opcao'    ,['value' => '']);
        $this->setInputCheckbox("showInativo", "Exibir Inativos");
        
        $this->setInputText('filtro1', '');
        $this->setInputText('filtro2', '');
        $this->setInputText('filtro3', '');
        $this->setInputText('filtro4', '');
        $this->setInputText('filtro5', '');
        $this->setInputText('filtro6', '');
        $this->setInputText('filtro7', '');
        $this->setInputText('filtro8', '');
        $this->setInputText('filtro9', '');
        $this->setInputText('filtro0', '');
        
        // Carregar filtros especificos para o index caso houver necessidade
        if($this->dependencia){
            $this->dependencia  = new $this->dependencia($this->em,['controller' => $this->controller], TRUE);
            $this->dependencia->getBaseForm($this->getTargetForm(), $filter);
        }        
    }
    
    /**
     * Metodo usado para setar campos do form ex: desabilita-los
     * Este metodo é chamado por padrao pelo helperForm na view pelo metodo formInit()
     * Caso existir um form de dependencia chama o preFormInit se houver.
     */
    public function preFormInit() {
        parent::preFormInit();
        if($this->dependencia){
            $this->dependencia->preFormInit();
        }
    }
    
    /**
     * Devia o fluxo para o form dependente
     * 
     * @author Paulo Watakabe
     * @version 1.0
     * @since 06-04-2016  
     * @param string $name
     * @param array $arguments
     */
    public function __call($name, $arguments) {        
        if (method_exists($this->dependencia, $name)) {
            return call_user_func_array([$this->dependencia, $name], $arguments);
        }
    }
    
    /**
     * retorna o form que esta como dependente
     * 
     * @author Paulo Watakabe
     * @version 1.0
     * @since 22-05-2017
     * @return \Application\Form\AbstractForm
     */
    public function getDependencia() {
        return $this->dependencia;
    }
}

