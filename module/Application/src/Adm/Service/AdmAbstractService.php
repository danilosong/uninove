<?php

/*
 * License GPL .
 * 
 */

namespace Adm\Service;

/**
 * Description of AdmAbstractService
 *
 * @author Danilo Song <danilosong@outlook.com>
 * @since 24-05-2017
 */
class AdmAbstractService extends \Application\Service\AbstractService {
    
    /**
     * Usado para guardar objetos temporários
     */
    protected $objectEntity;

    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 24-05-2017
     * @param \Doctrine\ORM\EntityManager $em
     * @param boolean | Service Class $fatherService
     */
    public function __construct(\Doctrine\ORM\EntityManager $em, $fatherService = FALSE) {
        parent::__construct($em, $fatherService);
        $this->basePath = 'Adm\Entity\\';
        $this->entity = $this->basePath . __CLASS__;
        $this->id = 'id';
    }

}
