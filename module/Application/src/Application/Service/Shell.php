<?php

/*
 * To change this license 
 */

namespace Application\Service;

use Doctrine\ORM\EntityManager;
/**
 * Description of Shell
 *
 * @author Paulo Watakabe
 */
class Shell extends AbstractService{


    /**
     * Controller em que esta sendo trabalho 
     *      Acessar seu metodos e o principal ter acesso ao service locator.
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.2
     * @var \Application\Controller\CrudController OR ($controller_path) 
     */
    protected $controller;
    
    
    public function __construct(EntityManager $em) {
        parent::__construct($em);
        
//        $this->entity = $this->basePath . "Shell";        
//        $this->id = 'idShell';
        
    }        
    
    /**
     * Setar uma ligação com controller para acessar principalmente o service locator.
     * 
     * @author Paulo Watakabe  <watakabe05@gmail.com>
     * @version 1.0
     * @param object $controller
     * @return \Application\Controller\CrudController OR ($controller_path) 
     */
    public function setController($controller) {
        $this->controller = $controller;
        return $this;
    }
    
}
