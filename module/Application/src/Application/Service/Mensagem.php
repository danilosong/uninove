<?php

/*
 * To change this license 
 */

namespace Application\Service;

use Doctrine\ORM\EntityManager;
/**
 * Description of Mensagem
 *
 * @author Paulo Watakabe
 */
class Mensagem extends AbstractService{

    public function __construct(EntityManager $em, $fatherService = FALSE) {
        parent::__construct($em, $fatherService);
        
        $this->entity = $this->basePath . "Mensagem";        
        $this->id = 'idMensagem';
    }        
    
}
