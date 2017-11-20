<?php

namespace Application\Controller;

class EnviadosController extends CrudController {

    public function __construct() {
        parent::__construct('enviado');
        $this->setFormWithEntityManager(TRUE);
    }

    public function indexAction($filtro = [], array $orderBy = [], \Doctrine\ORM\QueryBuilder $list = null) {
        return parent::indexAction([],['dateEnviado' => 'DESC']);
    }
}

