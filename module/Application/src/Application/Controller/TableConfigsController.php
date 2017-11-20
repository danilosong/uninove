<?php

/*
 * License
 */

namespace Application\Controller;

/**
 * Description of GrupoRepository
 *
 * @author Danilo Dorotheu <danilo.dorotheu@live.com>
 */


class TableConfigsController extends CrudController
{

    public function __construct() {
        parent::__construct('tableConfig');
        $this->setFormWithEntityManager(true);
        $this->setTitle("Configurações da Tabela <small>(Table Config)</small>");
        $this->setFormWithEntityManager(TRUE);
    }

}

