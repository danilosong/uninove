<?php

namespace Application\Controller;

class AppRolesController extends CrudController {

    public function __construct() {
        parent::__construct('appRole');
        $this->setFormWithEntityManager(TRUE);
    }

    
    public function testeAction()
    {
        $acl = $this->getServiceLocator()->get("Application\Permissions\Acl");
        echo $acl->isAllowed("Redator","Posts","Excluir")? "Permitido" : "Negado";
        die;
    }
    
}
