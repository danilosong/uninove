<?php

namespace Application\Controller;

class AppPrivilegesController extends CrudController {

    public function __construct() {
        parent::__construct('appPrivilege');
        $this->setFormWithEntityManager(TRUE);
    }

}

