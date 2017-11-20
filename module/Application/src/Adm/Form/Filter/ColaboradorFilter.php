<?php

namespace Adm\Form\Filter;

/**
 * @author Danilo Song <danilosong@outlook.com>
 * @version 1.0  
 * @since 25-05-2017
 */
class ColaboradorFilter extends AdmAbstractFilter{

    /**
     * Retorna os filtros para o formulário do colaborador.
     *
     * @author Danilo Song <danilosong@outlook.com>
     * @since 26/05/2017
     * @param boolean $ret
     */
    public function getFilters($ret) {
         $this->notEmpty('usuarioNome');        
         $this->notEmpty('perIni');        
         $this->notEmpty('perFim');        
         $this->notEmpty('horIni');        
         $this->notEmpty('horFim');        
         $this->notEmpty('almoco');            
    }

}

