<?php

/*
 * To change this license 
 */

namespace Application\Form\Filter;

/**
 * Validação do form do TableConfigColun
 *
 * @author Danilo Dorotheu <danilo.dorotheu@live.com>
 */
class TableConfigColunFilter extends AbstractFilter{
    
    public function getFilters($ret) {
        $this->notEmpty('tableConfig');        
        $this->notEmpty('label');        
        $this->notEmpty('method');        
    }
}
