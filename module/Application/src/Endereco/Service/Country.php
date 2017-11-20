<?php

/*
 * To change this license 
 */

namespace Endereco\Service;

use Doctrine\ORM\EntityManager;
/**
 * Description of Country Service
 *
 * @author Paulo Watakabe
 */
class Country extends \Application\Service\AbstractService{

    public function __construct(EntityManager $em) {
        parent::__construct($em);
        $this->basePath = 'Endereco\Entity\\';
        
        $this->entity = $this->basePath . "Country";        
        $this->id = 'iso';
        
    }        
    
}
