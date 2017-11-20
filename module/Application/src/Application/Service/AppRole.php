<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Service;

use Doctrine\ORM\EntityManager;
/**
 * Description of AppRole
 *
 * @author Paulo Watakabe
 */
class AppRole extends AbstractService{

    public function __construct(EntityManager $em) {
        parent::__construct($em);
        
        $this->entity = $this->basePath . "AppRole";        
        $this->id = 'idRole';
        
        $this->setDataRefArray([
            'parent' => $this->basePath . 'AppRole'
        ]);
    }        
    
}
