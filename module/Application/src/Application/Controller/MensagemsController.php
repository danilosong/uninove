<?php

namespace Application\Controller;


class MensagemsController extends CrudController
{

    public function __construct() {
        parent::__construct('mensagem');
    }

    
    public function indexAction($filtro = [], array $orderBy = [], \Doctrine\ORM\QueryBuilder $list = null) {
        return parent::indexAction([],['texto' => 'ASC']);        
    }
    
}

