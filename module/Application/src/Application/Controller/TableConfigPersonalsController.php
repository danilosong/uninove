<?php

/*
 * License
 */

namespace Application\Controller;

use \Zend\Http\Request;
/**
 * Description of GrupoRepository
 *
 * @author Danilo Dorotheu <danilo.dorotheu@live.com>
 */
class TableConfigPersonalsController extends CrudController {

    private $data;

    public function __construct() {
        parent::__construct('tableConfigPersonal');
        $this->setFormWithEntityManager(TRUE);
        $this->setTitle("Configurações Personalizadas da Tabela <small>(Table Config Personals)</small>");
    }

    /**
     * Verifica os dados do request. Quando retornar FALSE, nada será executado
     * na classe mãe getEntityForNew.
     * 
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @since 14-07-2016
     * @param object $request
     * @return boolean
     */
    public function checkDataForm($request, $form) {
        if (!$request->isPost()) {
            return FALSE;
        }
        $this->data = $request->getPost()->toArray();
        
        if (isset($this->data['subOpcao']) and 'setTableConfig' == $this->data['subOpcao']) {
            $request->setMethod(Request::METHOD_GET);
            $form->setData($this->getFormatFormData($this->data));
        }
        
        return TRUE;
    }

    /**
     * Antes de persistir dados, verifica se no request existe a flag 
     * 'setTableConfig'. Se sim, transformar o tipo do request para GET e devolver
     * os dados pra view com os campos preenchidos
     * 
     * @param type $dataView
     * @param type $form
     * @param type $request
     * @param type $resul
     */
    public function getEntityForNew(&$dataView, &$form, &$request, &$resul) {
        $this->checkDataForm($request, $form);
    }
    
    /**
     * Antes de persistir dados, verifica se no request existe a flag 
     * 'setTableConfig'. Se sim, transformar o tipo do request para GET e devolver
     * os dados pra view com os campos preenchidos
     * 
     * @param type $dataView
     * @param type $form
     * @param type $request
     * @param type $resul
     * @param type $entity
     */
    public function changeEntityForEdit(&$dataView, $form, $request, &$resul, $entity) {
        $this->checkDataForm($request);
    }
    
}
