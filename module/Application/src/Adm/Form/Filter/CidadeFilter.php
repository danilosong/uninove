<?php

namespace Adm\Form\Filter;

/**
 * Validação do form da Cidade
 * @author Danilo Dorotheu <danilo.dorotheu@live.com>
 */
class CidadeFilter extends AdmAbstractFilter{
    
    public function getFilters($ret) {
        if($ret){
            $this->notEmpty('nomeCidade');  
        }else{
            $this->notEmpty('nomeCidade');  
            $this->notEmpty('status');  
            $this->notEmpty('estado');  
        }
    }
}
