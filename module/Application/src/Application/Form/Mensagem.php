<?php

/*
 * To change this license 
 */

namespace Application\Form;

/**
 * Description of Mensagem
 *
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
class Mensagem extends AbstractForm{
 
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 05-11-2017
     * @param boolean $filter Por padrão(TRUE) carrega os filtros do form
     */
    public function setInputs($filter = TRUE) {
//        if ($filter) {
//            $this->setInputFilter(new Filter\MensagemFilter($this->name, $this->ret, $this->getTargetForm()->prefix));
//        }        
        
        $this->setInputHidden('idMensagem');
        
        $this->setInputTextArea('texto', 'Texto :');
        
        $this->setSimpleText('link');
    }  
    
}
