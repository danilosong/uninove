<?php

/*
 * To change this license 
 */

namespace Application\Form;

/**
 * Description of Teste
 *
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
class Teste extends AbstractForm{
    

//    public function __construct($name = 'Teste', $options = [], $ret = false) {
//        if (is_object($name) AND $name instanceof \Doctrine\ORM\EntityManager) {
//            $this->em = $name;
//        }
//        if (is_bool($name)) {
//            $ret = $name;
//        }
//        if (is_bool($options)) {
//            $ret = $options;
//            $options = [];
//        }
//        if(is_object($ret)){
//            $this->setController($ret);
//            $ret = false;
//        }
//        $this->ret = $ret;
//        parent::__construct('Teste', $options);
////        $this->moduloName = "Tcmed";
//        if ($this->ret == FALSE) {
//            $this->ret = TRUE;
//            $this->setAllInputs();
//        }
//    }    
    
    
    public function setInputs($filter = TRUE) {
        if ($filter) {
            $this->setInputFilter(new Filter\TesteFilter($this->name, $this->getRet(), $this->getTargetForm()->prefix));
        }
        
        $this->setInputHidden('idTeste');
        
        $this->setSimpleText('campo1');
        $this->setSimpleText('campo2');
        $this->setSimpleText('campo3');
//        $selectOptions = $this->getParametroChave('status_tabela',true);
//        $this->setInputSelect('campo4','Status',$selectOptions);
        $this->setSimpleText('datetime');
        
        $this->setSimpleText('cep');
        $this->setSimpleText('tipoLogradouro');
        $this->setInputText('tipoLogradouro', 'tipo logradouro');
        $this->setInputText('logradouro', 'Endereço');
        $this->setSimpleText('numero');
        $this->setSimpleText('complemento');
        $this->setSimpleText('bairro');
        $this->setSimpleText('cidade');
        $this->setSimpleText('estado');
        $this->setSimpleText('pais');
//        
//        if($this->ret){            
//            $parametros = new Parametros($this->em, TRUE);
//            $parametros->getBaseForm($this->getTargetForm(), $filter);
//        }
        $this->setSimpleText('path');
        $this->setSimpleText('returnClass');
        $this->setSimpleText('author');
        $this->setSimpleText('version');
        $this->setSimpleText('date');
        $options = ['12' => 'Gerar Sets e Gets', '1' => 'Only Sets', '2' => 'Only Gets'];
        $this->setInputSelect('opt', 'Tipo de Geração', $options);
        
        $this->setInputButton('altBtn', 'alterar');
        
    }
    
}
