<?php

namespace Adm\Form\Filter;

/**
 * @author Danilo Song <danilosong@outlook.com>
 * @version 1.0  
 * @since 29-05-2017
 */
class ColaboradorPontoFilter extends AdmAbstractFilter{

    /**
     * Retorna os filtros para o formulário do colaborador.
     *
     * @author Danilo Song <danilosong@outlook.com>
     * @since 29/05/2017
     * @param boolean $ret
     */
    public function getFilters($ret) {
         $this->notEmpty('colaboradorNome');
         $this->getColaborador('colaboradorNome');
    }

}

