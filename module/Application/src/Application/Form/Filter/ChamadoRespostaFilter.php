<?php

/*
 * To change this license 
 */

namespace Application\Form\Filter;

/**
 * Validação do form do ChamadoResposta
 *
 * @author Danilo Song <danilosong@outlook.com>
 */
class ChamadoRespostaFilter extends AbstractFilter{
    
    public function __construct() {
        $this->notEmpty('texto');  
        $this->notEmpty('situacao');  
    }
}
