<?php

namespace Adm\Form\Filter;

/**
 * @author Danilo Song <danilosong@outlook.com>
 * @version 1.0  
 * @since 19-06-2017
 */
class PedidoItemFilter extends AdmAbstractFilter{
    
    public function getFilters($ret) {
         $this->notEmpty('qtd');
         $this->notEmpty('produtoNome');
         $this->notEmpty('valor');
    }
}

