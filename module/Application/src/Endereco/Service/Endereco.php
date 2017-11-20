<?php

/*
 * To change this license 
 */

namespace Endereco\Service;

use Doctrine\ORM\EntityManager;
/**
 * Description of Endereco Service
 *
 * @author Paulo Watakabe
 */
class Endereco extends \Application\Service\AbstractService{

    public function __construct(EntityManager $em) {
        parent::__construct($em);
        $this->basePath = 'Endereco\Entity\\';
        
        $this->entity = $this->basePath . "Endereco";        
        $this->id = 'enderecoCodigo';
        
    }        
    
}
