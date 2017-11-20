<?php

/*
 * To change this license 
 */

namespace Application\Form;

/**
 * Description of AppResource
 *
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
class AppResource extends AbstractForm{
    
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 05-11-2017
     * @param boolean $filter Por padrão(TRUE) carrega os filtros do form
     */
    public function setInputs($filter = TRUE) {
        if ($filter) {
            $this->setInputFilter(new Filter\AppResourceFilter($this->name, $this->ret, $this->getTargetForm()->prefix));
        }        
        
        $this->setInputHidden('idResource');
        
        $this->setInputText2('name', 'Nome: ',['placeholder'=>'Entre com o nome']);
    }  
}
