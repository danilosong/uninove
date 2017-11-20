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
class TableConfigColun extends AbstractForm {

//    public function __construct($name = '', $options = array(), $ret = false) {
//        $this->redirectParams($name, $options, $ret);
//        parent::__construct('tableConfigColun', $options);
//        if ($this->ret == FALSE) {
//            $this->setAllInputs();
//        }
//    }

    public function setInputs($filter = TRUE) {
        if ($filter) {
            $this->setInputFilter(new Filter\TableConfigColunFilter($this->name, $this->ret, $this->getTargetForm()->prefix));
        }
        $this->setInputHidden('id');

        $this->setInputText2('label', 'Label');
        $this->setInputText2('method', 'Method');
        $this->setInputText2('order', 'Ordenar Coluna');
        $this->setInputText2('param', 'Param');
        $this->setInputText2('trLine', 'tr Line');
        $this->setInputText2('tdLine', 'td Line');
        $this->setInputText2('thCss', 'th CSS');
        $this->setInputText2('thJs', 'th JS');
        $this->setInputText2('thOption', 'th Option');
        $this->setInputText2('tdLine', 'td Line');
        $this->setInputText2('param', 'Param');

        $this->setInputCheckbox("default", "Default");

        $select = $this->getAllTableConfig();
        $this->setInputSelect('tableConfig', 'Table Config', $select);
    }

    public function getAllTableConfig($first = TRUE) {
        $repository = $this->em->getRepository($this->moduloName . "\Entity\TableConfig");
        return $repository->fetchPairs('getEntityPath', $first);
    }

}
