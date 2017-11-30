<?php

namespace Adm\Form\Filter;

/**
 * @author Danilo Song <danilosong@outlook.com>
 * @version 1.0  
 * @since 19-06-2017
 */
class ProdutoFilter extends AdmAbstractFilter{

    /**
     * Retorna os filtros para o formulário do Produto.
     *
     * @author Danilo Song <danilosong@outlook.com>
     * @since 19-06-2017
     * @param boolean $ret
     */
    public function getFilters($ret) {
         $this->notEmpty('nomeProd');
         $this->notEmpty('valorProd');
         $this->notEmpty('compraQtd');
         $this->notEmpty('saidaQtd');
         $this->notEmpty('setor');
    }

}

