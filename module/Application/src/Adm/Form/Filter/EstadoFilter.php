<?php

namespace Adm\Form\Filter;

/**
 * Validação do form da Estado
 * @author Danilo Dorotheu <danilo.dorotheu@live.com>
 */
class EstadoFilter extends \Application\Form\Filter\AbstractFilter{
    
    public function getFilters($ret) {
        
        $this->notEmpty('uf');
        
        if($ret == FALSE){
            $this->notEmpty('nomeEstado');  
            $this->notEmpty('status');  
            $this->notEmpty('pais');  
        }        
    }
}
