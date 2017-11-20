<?php

namespace Adm\Form\Filter;

/**
 * @author Danilo Song <danilosong@outlook.com>
 * @version 1.0  
 * @since 24-05-2017
 */
class TesteFilter extends AdmAbstractFilter{

    public function getFilters($ret) {
         $this->notEmpty('teste');
         
    }
}
