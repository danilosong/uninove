<?php

/*
 * License GPL .
 * 
 */

namespace Endereco\Form;

/**
 * Description of AdmAbstractForm
 *
 * @author Danilo Song <danilosong@outlook.com>
 * @since 24-05-2017
 */
class EnderecoAbstractForm extends \Application\Form\AbstractForm {
    
    /**
     * Abstracao basica do construct para este modulo
     * @author Paulo Watakabe <danilosong@outlook.com>
     * @version 1.0  
     * @since 24-05-2017
     * @param string  $name
     * @param array   $options
     * @param boolean $ret
     */
    public function __construct($name = null, $options = array(), $ret = false) {
        if(is_bool($options)){
            $ret     = $options;
            $options = [];
        }
        if(isset($options['ret'])){
            $ret = $options['ret'];
        }
        $this->name  = (is_string($name))? $name: lcfirst(str_replace('\\', '', str_replace(__NAMESPACE__, '', get_class($this))));
        parent::__construct($name, $options, true);
        $this->ret = $ret ;
        $this->setInputHidden('ret',['value' => '#inter']);
        $this->moduloName = "Endereco";
        if ($this->ret == FALSE) {
            $this->ret = TRUE;
            $filters = isset($options["filters"]) ? $options["filters"] : TRUE;
            $this->setAttribute('method', 'post');
            $this->setAttribute('id', 'formSistema');
            $this->setAttribute('name', $this->name);
            $this->setAllInputs($filters);
        }
    }
}
