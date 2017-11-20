<?php

/*
 * To change this license 
 */

namespace Endereco\Service;

use Doctrine\ORM\EntityManager;
/**
 * Description of Cidade Service
 *
 * @author Paulo Watakabe
 */
class Cidade extends \Application\Service\AbstractService{

    public function __construct(EntityManager $em) {
        parent::__construct($em);
        $this->basePath = 'Endereco\Entity\\';
        
        $this->entity = $this->basePath . "Cidade";        
        $this->id = 'cidadeCodigo';
        
    }        
    
}
