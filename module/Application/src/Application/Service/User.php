<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Service;

use Doctrine\ORM\EntityManager;
use Zend\Stdlib\Hydrator;
/**
 * Description of User
 *
 * @author Paulo Watakabe
 */
class User extends AbstractService{

    public function __construct(EntityManager $em) {
        parent::__construct($em);
        
        $this->entity = $this->basePath . "User";        
        $this->id = 'idUser';
        
        $this->setDataRefArray([
            'array_contatos' => $this->entity
        ]);
    }
    
}
