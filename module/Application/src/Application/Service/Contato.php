<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Service;

use Doctrine\ORM\EntityManager;
/**
 * Description of Contato
 *
 * @author Paulo Watakabe
 */
class Contato extends AbstractService{

    public function __construct(EntityManager $em) {
        parent::__construct($em);
        
        $this->entity = $this->basePath . "Contato";        
        $this->id = 'idContato';
        $this->setDataRefArray([
            'userUser' => $this->basePath . 'User', 
            'contatoUser' => $this->basePath . 'User', 
            'grupoGrupo' => $this->basePath . 'Grupo', 
        ]);
    }
        
    
}
