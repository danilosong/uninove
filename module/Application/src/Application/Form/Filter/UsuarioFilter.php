<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Form\Filter;

/**
 * Validação do form do Usuario
 *
 * @author Paulo Cordeiro Watakabe <watakabe05@gmail.com>
 */
class UsuarioFilter extends AbstractFilter{
    
    protected $action;


    /**
     * 
     * @param string  $name
     * @param boolean $ret
     * @param array   $prefix
     * @param string  $method
     * @param string  $action
     */
    public function __construct($name = '', $ret = false, $prefix = array(), $method = 'getFilters', $action = 'new') {
        $this->action = $action;
        parent::__construct($name, $ret, $prefix, $method);
    }
    
    public function getFilters($ret) {
        
        $this->notEmpty('nomeUsuario');  
        
        $this->notEmpty('nickname');        
        
        $this->email('emailUsuario');
        
        if ($this->action === 'new'){
            $this->notEmpty('senhaUsuario');               
            $this->identical('confirmation', 'senhaUsuario');
        }
    }
}
