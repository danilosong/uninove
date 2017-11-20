<?php

/*
 * To change this license 
 */

namespace Endereco\Form;

/**
 * Description of Cidade
 *
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
class Cidade extends \Application\Form\AbstractForm{
    
    
    public function __construct($name = 'Cidade', $options = array()) {
        if(is_object($name) AND $name instanceof \Doctrine\ORM\EntityManager){         
            $this->em = $name;
        }
        parent::__construct('cidade', $options);
        
        $this->setInputFilter(new Filter\CidadeFilter);
        
        $this->setSimpleText('cidadeCodigo');
        
        $this->setSimpleText('ufCodigo');
        
        $this->setSimpleText('cidadeDescricao');
        
        $this->setSimpleText('cidadeCep');
        
        $csrf = new \Zend\Form\Element\Csrf('security');
        $this->add($csrf);
        
        $this->setInputSubmit('submit', 'Salvar');
        
    }
    
}
