<?php

/*
 * License
 */

namespace Application\Form;

/**
 * Description of Contato
 *
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
class Contato extends AbstractForm{
    
//    public function __construct($name = '', $options = array(), $ret = false) {
//        $this->redirectParams($name, $options, $ret);
//        parent::__construct('contato', $options);
//        if($this->ret == FALSE){
//            $this->setAllInputs();
//        }
//    } 
    
    public function setInputs($filter=TRUE) {
        if($filter){
            $this->setInputFilter(new Filter\ContatoFilter($this->name, $this->ret, $this->getTargetForm()->prefix, 'getFilters'));
        }    
        
        $this->setInputHidden('idContato');
        
        $this->setInputHidden('userUser');
        $attributes = ['placeholder' => 'Digite aqui nome PESQUISAR!',
                       'onKeyUp' => 'autoCompUser();',
                       'autoComplete'=>'off']; 
        $this->setInputText2('userUser_nome', 'User: ',$attributes);
        
        $this->setInputHidden('grupoGrupo');
        $attributes['onKeyUp'] = 'autoCompGrupo();';
        $this->setInputText2('grupoGrupo_nome', 'Grupo: ',$attributes);
        
        $this->setInputHidden('contatoUser');
        $attributes['onKeyUp'] = 'autoCompContato();';
        $this->setInputText2('contatoUser_nome', 'Contato: ',$attributes);
        
    }
}
