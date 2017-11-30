<?php

namespace Adm\Form\Filter;

/**
 * @author Danilo Song <danilosong@outlook.com>
 * @version 1.0  
 * @since 31-06-2017
 */
class SaidaFilter extends AdmAbstractFilter{
    
    /**
     * Retorna os filtros para o formulário da Saida.
     *
     * @author Danilo Song <danilosong@outlook.com>
     * @since 31/06/2017
     * @param boolean $ret
     */
    public function getFilters($ret) {
         $this->notEmpty('produtoNome');
         $this->notEmpty('qtd');
         $this->notEmpty('conjunto');
    }

}

