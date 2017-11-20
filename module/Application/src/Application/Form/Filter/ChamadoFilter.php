<?php

/*
 * To change this license 
 */

namespace Application\Form\Filter;

/**
 * Validação do form do Chamado
 *
 * @author Danilo Song <danilosong@outlook.com>
 */
class ChamadoFilter extends AbstractFilter{
    
    /**
     * Retorna os filtros para o formulário do chamado.
     *
     * @author Danilo Song <danilosong@outlook.com>
     * @since 10/10/2017
     * @param boolean $ret
     */
    public function getFilters($ret) {
        $this->notEmpty('titulo');  
        $this->notEmpty('desc');
        $this->notEmpty('prioridade');
        $this->notEmpty('setor');
    }
}
