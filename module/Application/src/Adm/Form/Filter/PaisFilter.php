<?php

/*
 * To change this license 
 */

namespace Adm\Form\Filter;

/**
 * Validação do form da Pais
 * @author Danilo Dorotheu <danilo.dorotheu@live.com>
 */
class PaisFilter extends AdmAbstractFilter {

    public function getFilters($ret) {

        $this->notEmpty('nomePais');
        
        if($ret == FALSE){
            $this->notEmpty('sigla');
            $this->notEmpty('codigo');
            $this->notEmpty('status');
        }
        
    }

}
