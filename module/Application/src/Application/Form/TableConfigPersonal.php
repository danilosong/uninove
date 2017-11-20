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
class TableConfigPersonal extends AbstractForm {

//    public function __construct($name = '', $options = array(), $ret = false) {
//        $this->redirectParams($name, $options, $ret);
//        parent::__construct('tableConfigPersonal', $options);
//        if ($this->ret == FALSE) {
//            $this->setAllInputs();
//        }
//    }

    public function setInputs($filter = TRUE) {
        if ($filter) {
            $this->setInputFilter(new Filter\TableConfigPersonalFilter($this->name, $this->ret, $this->getTargetForm()->prefix, 'getFilters'));
        }
        $this->setInputHidden('id');

        $this->setInputText2('seq', 'Seq');

        $select = $this->getAllTableConfig();
        $this->setInputSelect('tableConfig', 'Table Config', $select);

        $this->setSelectTableConfigColun();

        $select3 = $this->getAllAppRole();
        $this->setInputSelect('role', 'Role', $select3);

        (new Usuario($this->em, TRUE))->getBaseForm($this->getTargetForm(), FALSE);
    }

    public function getAllTableConfig($first = TRUE) {
        $repository = $this->em->getRepository($this->moduloName . "\Entity\TableConfig");
        return $repository->fetchPairs('getEntityPath', $first);
    }
    
    public function getAllAppRole($first = TRUE) {
        $repository = $this->em->getRepository($this->moduloName . "\Entity\AppRole");
        return $repository->fetchPairs('getNome', $first);
    }

    /**
     * Além de setar os dados para reenviálos à view, o elemento tableConfigColun
     * será atualizado com os novos options
     * 
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @since 14-07-2016
     * @param array $data
     * @return type
     */
    public function setData($data) {
        if(isset($data['tableConfig']) and !empty($data['tableConfig'])){
            $this->setSelectTableConfigColun($data['tableConfig']);
        }
        return parent::setData($data);
    }
    
    /**
     * Quando este método é chamado, se o elemento tableConfigColun ainda
     * não estiver definido, então defini-lo. Caso contrário, serão atualizadas
     * as options deste elemento
     * 
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @since 14-07-2016
     * @param boolean|int $id
     * @param boolean $first
     */
    public function setSelectTableConfigColun($id = FALSE, $first = TRUE) {
        $select2 = $this->getAllTableConfigColun($id, $first);
        if ($this->has('tableConfigColun')) {
            $this->get('tableConfigColun')->setValueOptions($select2);
        }else{
            $this->setInputSelect('tableConfigColun', 'Table Config Colun', $select2);
        }
    }

    /**
     * Retorna todos os registros baseados no ID de um tableConfig ou uma mensagem
     * de 'selecione um Table Config' caso o $this->idTableConfig for NULL
     * 
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @since 14-07-2016
     * @param boolean|int $id
     * @param boolean $first Quando TRUE, retorna também o 'Selecione na lista'
     */
    public function getAllTableConfigColun($id = FALSE, $first = TRUE) {
        if (!$id) {
            return ['' => 'Selecione um Table Config'];
        }
        $repository = $this->em->getRepository($this->moduloName . "\Entity\TableConfigColun");
        return $repository->fetchPairs('getLabel', ["tableConfig" => $id], $first);
    }

}
