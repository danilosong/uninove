<?php

namespace Application\Controller;

/**
 * Description of TableConfigColuns
 *
 * @author Danilo Dorotheu <danilo.dorotheu@live.com>
 * @since 14-07-2016
 */
class TableConfigColunsController extends CrudController
{
    public function __construct() {
        parent::__construct('tableConfigColun');
        $this->route = 'app/default';
        $this->routeAjax = "app/ajax";
        $this->setFormWithEntityManager(true);
        $this->setFormWithController(true);
        $this->setTitle("Configurações das Colunas da Tabela <small>(Table Config Colun)</small>");
    }
}
