<?php

/*
 * To change this license 
 */

namespace Endereco\Form;

/**
 * Description of Bairro
 *
 * @author Danilo Dorotheu
 */
class Bairro extends \Application\Form\AbstractForm{
    
    
    public function __construct($name = 'Bairro', $options = array()) {
        if(is_object($name) AND $name instanceof \Doctrine\ORM\EntityManager){         
            $this->em = $name;
        }
        parent::__construct('bairro', $options);
        
        $this->setInputFilter(new Filter\CountryFilter);
        
        $this->setSimpleText('bairroCodigo');
        
        $this->setSimpleText('cidadeCodigo');
        
        $this->setSimpleText('bairroDescricao');
        
        $csrf = new \Zend\Form\Element\Csrf('security');
        $this->add($csrf);
        
        $this->setInputSubmit('submit', 'Salvar');
        
    }
    
}
