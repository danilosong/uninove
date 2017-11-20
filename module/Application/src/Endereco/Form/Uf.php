<?php

/*
 * To change this license 
 */

namespace Endereco\Form;

/**
 * Description of Uf
 *
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
class Uf extends \Application\Form\AbstractForm{
    
    
    public function __construct($name = 'Uf', $options = array()) {
        if(is_object($name) AND $name instanceof \Doctrine\ORM\EntityManager){         
            $this->em = $name;
        }
        parent::__construct('uf', $options);
        
        $this->setInputFilter(new Filter\UfFilter);
        $this->setSimpleText('ufCodigo');
        
        $this->setSimpleText('ufSigla');
        
        $this->setSimpleText('ufDescricao');
        
        $csrf = new \Zend\Form\Element\Csrf('security');
        $this->add($csrf);
        
        $this->setInputSubmit('submit', 'Salvar');
        
    }
    
}
