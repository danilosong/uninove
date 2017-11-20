<?php

/*
 * To change this license 
 */

namespace Endereco\Service;

use Doctrine\ORM\EntityManager;
/**
 * Description of Bairro Service
 *
 * @author Danilo Dorotheu
 */
class Bairro extends \Application\Service\AbstractService{

    public function __construct(EntityManager $em) {
        parent::__construct($em);
        $this->basePath = 'Endereco\Entity\\';
        
        $this->entity = $this->basePath . "Bairro";        
        $this->id = 'bairroCodigo';
        
    }        
    
}
