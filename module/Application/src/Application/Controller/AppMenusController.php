<?php

/*
 * To change this license 
 */

namespace Application\Controller;

/**
 * Description of AppMenu
 *
 * @author Paulo Watakabe
 */
class AppMenusController extends CrudController {

    public function __construct() {
        parent::__construct();
        $this->setFormWithEntityManager(TRUE);
    }
    
    /**
     * Metodo para alterar dados que vao para o form
     * Os parametro sao os ponteiro logo a alteração deles ira alterar tb do metodo newAction
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 16-10-2016
     * @param array  $dataView  Array com informações basica como titulos etc.
     * @param \Application\Form\AppMenu $form      
     * @param object $request
     * @param string $resul
     * @return type
     */
    public function getEntityForNew(&$dataView, &$form, &$request, &$resul) {
        $data = $this->checkDataForm($request, $dataView);
        if ($data === false) {
            return NULL;
        }
        // Alterar o request de Post para Get
        $request->setMethod(\Zend\Http\Request::METHOD_GET);
        /* @var $entity \Application\Entity\AppMenu */
        $entity = $this->getem()->find($this->entity, $data['id']);
        if ($entity) {
            $form->setData($this->getFormatFormData($entity->toArrayNew()));
            $form->get('idMenu')->setValue('');
        }
        return NULL;
    }

    /**
     * Metodo para verificar se no request tem as flags para atualizar tela
     *    loadCad Carregar os dados baseado nos dados do post.
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 12-08-2016
     * @param \Zend\Stdlib\RequestInterface $request
     * @return boolean|array
     */
    public function checkDataForm($request) {
        if (!$request->isPost()) {
            return false;
        }
        $data = $request->getPost()->toArray();
        if (!isset($data['subOpcao'])) {
            return false;
        }
        if ('copy' !== $data['subOpcao']) {
            return false;
        }
        return $data;
    }

}

