<?php

namespace Adm\Form\Filter;

/**
 * @author Danilo Song <danilosong@outlook.com>
 * @version 1.0  
 * @since 19-06-2017
 */
class PedidoFilter extends AdmAbstractFilter{
    
    /**
     * Retorna os filtros para o formulário do pedido.
     *
     * @author Danilo Song <danilosong@outlook.com>
     * @since 20/06/2017
     * @param boolean $ret
     */
    public function getFilters($ret) {
         $this->notEmpty('usuarioNome');
         $this->notEmpty('fornecedorNome');
         $this->notEmpty('dataEntrega');
         $this->notEmpty('status');
         $this->getUsuario('usuarioNome');
    }

}

