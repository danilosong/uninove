<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Adm\Form;

/**
 * Description of FormFilter
 *
 * @author Danilo Song <danilosong@outlook.com>
 */
class FormFilter extends \Application\Form\FormFilter {
    //put your code here
    public function setInputs($filter = TRUE) {
        parent::setInputs($filter);
        
        $this->setInputCheckbox("show", "Exibir");        

    }
}
