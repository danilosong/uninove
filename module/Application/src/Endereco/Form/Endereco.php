<?php

/*
 * To change this license 
 */

namespace Endereco\Form;

/**
 * Description of Endereco
 *
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
class Endereco extends \Application\Form\AbstractForm{
    
    
    public function __construct($name = 'Endereco', $options = array()) {
        if(is_object($name) AND $name instanceof \Doctrine\ORM\EntityManager){         
            $this->em = $name;
        }
        parent::__construct('endereco', $options);
        
        $this->setInputFilter(new Filter\EnderecoFilter);
        
        $this->setSimpleText('enderecoCodigo');
        
        $this->setSimpleText('bairroCodigo');
        
        $this->setSimpleText('enderecoCep');
        
        $this->setSimpleText('enderecoLogradouro');
        
        $this->setSimpleText('enderecoComplemento');
        
        $csrf = new \Zend\Form\Element\Csrf('security');
        $this->add($csrf);
        
        $this->setInputSubmit('submit', 'Salvar');
        
    }
    
}
