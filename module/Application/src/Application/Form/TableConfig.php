<?php

/*
 * License
 */

namespace Application\Form;

/**
 * Description of Contato
 *
 * @author Danilo Dorotheu <danilo.dorotheu@live.com>
 */
class TableConfig extends AbstractForm {

//    public function __construct($name = '', $options = array(), $ret = false) {
//        $this->redirectParams($name, $options, $ret);
//        parent::__construct('tableConfig', $options);
//        if ($this->ret == FALSE) {
//            $this->setAllInputs();
//        }
//    }

    public function setInputs($filter = TRUE) {
        if ($filter) {
            $this->setInputFilter(new Filter\TableConfigFilter($this->name, $this->ret, $this->getTargetForm()->prefix, 'getFilters'));
        }
        $this->setInputHidden('id');
        $this->setInputText2('entityPath','Path', ['placeholder'=> "Digite aqui, o path da entidade"]);
        $this->setInputText2('caption'   ,'Caption', ['placeholder'=> "Digite aqui, o Caption da tabela"]);
        $this->setInputText2('trOption'  ,'tr Option', ['placeholder'=> "Digite aqui, as opções da <tr>"]);
        $this->setInputText2('tdOption'  ,'td option', ['placeholder'=> "Digite aqui, as opções da <td>"]);

//        $select = $this->getAllPairs();
//        $this->setInputSelect('', '', $select);
    }

}
