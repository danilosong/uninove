<?php

/*
 * To change this license 
 */

namespace Application\Form\Filter;

/**
 * Validação do form do TableConfigPersonal
 *
 * @author Danilo Dorotheu <danilo.dorotheu@live.com>
 */
class TableConfigPersonalFilter extends AbstractFilter{
    
    public function getFilters($ret) {
        $this->notEmpty('tableConfig');
        $this->notEmpty('tableConfigColun');
        $this->notEmpty('seq');
    }
}
