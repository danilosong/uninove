<?php

/*
 * To change this license 
 */

namespace Endereco\Service;

use Doctrine\ORM\EntityManager;
/**
 * Description of Uf Service
 *
 * @author Paulo Watakabe
 */
class Uf extends \Application\Service\AbstractService{

    public function __construct(EntityManager $em) {
        parent::__construct($em);
        $this->basePath = 'Endereco\Entity\\';
        
        $this->entity = $this->basePath . "Uf";        
        $this->id = 'ufCodigo';
        
    }        
    
}
