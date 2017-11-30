<?php

namespace Adm\Form\Filter;

/**
 * @author Danilo Song <danilosong@outlook.com>
 * @version 1.0  
 * @since 19-06-2017
 */
class FornecedorFilter extends AdmAbstractFilter{

    /**
     * Retorna os filtros para o formulário do fornecedor.
     *
     * @author Danilo Song <danilosong@outlook.com>
     * @since 19-06-2017
     * @param boolean $ret
     */
    public function getFilters($ret) {
         $this->notEmpty('nomeFornec');
         $this->notEmpty('telefone');
         $this->notEmpty('cnpj');
         $this->notEmpty('setor');
         $this->validarCnpj('cnpj');
    }

}

