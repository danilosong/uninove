<?php

/*
 * To change this license 
 */

namespace Application\Form\Filter;

/**
 * Validação do form do TableConfig
 *
 * @author Danilo Dorotheu <danilo.dorotheu@live.com>
 */
class TableConfigFilter extends AbstractFilter{
    
    public function getFilters($ret) {
        $this->notEmpty('entityPath');        
    }
}
