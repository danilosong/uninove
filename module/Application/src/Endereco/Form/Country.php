<?php

/*
 * To change this license 
 */

namespace Endereco\Form;

/**
 * Description of Country
 *
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
class Country extends \Application\Form\AbstractForm{
    
    
    public function __construct($name = 'Country', $options = array()) {
        if(is_object($name) AND $name instanceof \Doctrine\ORM\EntityManager){         
            $this->em = $name;
        }
        parent::__construct('country', $options);
        
        $this->setInputFilter(new Filter\CountryFilter);
        
        $this->setSimpleText('iso');
        
        $this->setSimpleText('iso3');
        
        $this->setSimpleText('numcode');
        
        $this->setSimpleText('nome');
        
        $csrf = new \Zend\Form\Element\Csrf('security');
        $this->add($csrf);
        
        $this->setInputSubmit('submit', 'Salvar');
        
    }
    
}
