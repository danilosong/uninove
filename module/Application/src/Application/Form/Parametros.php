<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Form;

/**
 * Description of User
 *
 * @author Paulo Watakabe
 */
class Parametros extends AbstractForm{
//    
//
//    public function __construct($name = 'parametros', $options = [], $ret = false) {
//        if (is_object($name) AND $name instanceof \Doctrine\ORM\EntityManager) {
//            $this->em = $name;
//        }
//        if (is_bool($name)) {
//            $ret = $name;
//        }
//        if (is_bool($options)) {
//            $ret = $options;
//            $options = [];
//        }
//        if(is_object($ret)){
//            $this->setController($ret);
//            $ret = false;
//        }
//        $this->ret = $ret;
//        parent::__construct('parametros', $options);
////        $this->moduloName = "Tcmed";
//        if ($this->ret == FALSE) {
//            $this->ret = TRUE;
//            $this->setAllInputs();
//        }
//    }    
    
    
    public function setInputs($filter = TRUE) {
        if ($filter) {
            $this->setInputFilter(new Filter\ParametrosFilter($this->name, $this->getRet(), $this->getTargetForm()->prefix));
        }    
        
        $this->setInputHidden('idParame');
        
        $this->setInputText2('chave', 'Chave : ',['placeholder'=>'Digite chave']);
        
        $this->setInputText2('conteudo', 'Conteudo : ',['placeholder'=>'Digite conteudo']);
        
        $this->setInputText2('descricao', 'Descricao : ',['placeholder'=>'Digite descriçao']);
        
        $selectOptions = ["ativo" => "Ativo", "inativo"=>"Inativo", "cancelado" => "Cancelado"];
        $this->setInputSelect('status', 'Status: ', $selectOptions);
        
    }
}
