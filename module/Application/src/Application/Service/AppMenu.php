<?php

/*
 * To change this license 
 */

namespace Application\Service;

use Doctrine\ORM\EntityManager;
/**
 * Description of AppMenu
 *
 * @author Paulo Watakabe
 */
class AppMenu extends AbstractService{

    public function __construct(EntityManager $em) {
        parent::__construct($em);
        
        $this->entity = $this->basePath . "AppMenu";        
        $this->id = 'idMenu';
        
        $this->setDataRefArray([
            'inMenu' => $this->basePath . 'AppMenu'
        ]);
    }        
    
}
