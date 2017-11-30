<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Controller;

/**
 * Description of UsersController
 *
 * @author Paulo Watakabe <email watakabe05@gmail.com >
 */
class UsuariosController extends CrudController{

    public function __construct($name = '', $module = 'Application') {
        parent::__construct($name, $module);
        $this->setHaveServiceLocatorService(TRUE);
        $this->setFormWithController(TRUE);
    }

    public function editAction() {        
        $dataView = $this->getDataView('Editando ' . $this->name, 'edit');
        $form = $this->getForm();
        $request = $this->getRequest();
        $entity = null;
        $id = $this->params()->fromRoute('id', 0);

        if ($id != 0) {
            $entity = $this->getEm()->find($this->entity, $id);
            $array = $entity->toArray(TRUE);
            $array['role'] = $entity->getRole(TRUE)->getId();
            unset($array['password']);
            $form->setData($array);
        }

        $resul = '';
        if ($request->isPost()) {
            $form->setData($this->getFormatFormData($request->getPost()->toArray()));
            if ($form->isValid()) {
                $service = $this->getService();
                $resul = $service->update($this->getDataWeb($request, 'updatedBy'));
                if ($this->log AND ! is_array($resul)) {
                    $service->log($this->controller, 'edit', 'Editou o Registro');
                }
                if (!is_array($resul) AND $this->render) {
                    return $this->setRedirect();
                }
            } else {
                echo '<h1>invalidos new</h1> <pre>', var_dump($form->getMessages()), '</pre>';
            }
        }


        if ($this->render OR is_array($resul)) {
            return $this->makeView(compact("form", "dataView", "entity", "resul"), $this->getPathViewDefault() . 'form.phtml');
        }
        return $resul;
    }
    
    public function changePasswdAction() {        
        $dataView = $this->getDataView('Editando ' . $this->name, 'changePasswd');
        $form = $this->getForm(['filters' => false]);
        $request = $this->getRequest();
        $entity = null;
        $id = $this->params()->fromRoute('id', 0);

        $resul = '';
        if ($request->isPost()) {
            $form->setData($this->getFormatFormData($request->getPost()->toArray()));
            if ($form->isValid()) {
                $service = $this->getService();
                $resul = $service->update($this->getDataWeb($request, 'updatedBy'));
                if ($this->log AND ! is_array($resul)) {
                    $service->log($this->controller, 'edit', 'Editou o Registro');
                }
                if (!is_array($resul) AND $this->render) {
                    $param = $this->getParam();
                    $route = $param('route','defaultRoute');
                    return $this->redirect()->toRoute(str_replace('default', 'ajax', $route), array('controller' => 'index', 'action' => 'buildIndex', 'ajax' => 'ok'));
                }
            } else {
                echo '<h1>invalidos new</h1> <pre>', var_dump($form->getMessages()), '</pre>';
            }
        }  else {
            $entity = $this->getEm()->find($this->entity, $id);
            $array = $entity->toArray(TRUE);
            $array['role'] = $entity->getRole(TRUE)->getId();
            unset($array['password']);
            $form->setData($array);
        }

        if ($this->render OR is_array($resul)) {
            return $this->makeView(compact("form", "dataView", "entity", "resul"));
        }
        return $resul;
    }

    /**
     * Remover Caminho para arquivo de foto
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 18-07-2016 
     * @return string
     */
    public function rmImageAction() {
        /* @var $entity \Application\Entity\Usuario */
        $idEntity = $this->params()->fromRoute('id', 0);
        $entity = $this->getEm()->find($this->entity, $idEntity);
        if($entity){
            $entity->setPathFoto('');
            $this->getEm()->persist($entity);
            $this->getEm()->flush();
            echo 'ok';
        }else{
            echo 'Registro deste usuario não foi encontrado.';
        }
        die;
    }
    
    public function resetAction() {
        /* @var $srv \Application\Service\Usuario */
        /* @var $form \Application\Form\Usuario */
        $srv = $this->getService();
           
        $dataView = $this->getDataView('Redefinir senha', 'reset');
        $form = $this->getForm(['filter' => false]);
        $request = $this->getRequest();

        $entity = null;
        $resul = [];
        if($request->isPost()){
            $data   = $request->getPost()->toArray();
            $entity = $this->getEm()->find($this->entity, $data['idUsuario']);
            $check  = md5($entity->getEmail() . $entity->getUpdatedAt('Ymdhis'));
            $activationKey = $data['activationKey'] ?? '';
            if($check != $activationKey){ // a senha já foi alterada ou link não é mais valido
                $resul[] = false;
                $resul[] = ['O tempo de uso do link esta expirado !!!'];
            }else{
                $resul = $srv->update([
                    'idUsuario'      => $data['idUsuario']
                    ,'senhaUsuario'  => $data['senhaUsuario']
                    ,'confirmation'  => $data['confirmation']
                    ,'lembreteSenha' => $data['lembreteSenha']
                    ,'updatedAt'     => ''
                ]);
                if ($this->log AND ! is_array($resul)) {
                    $service->log($this->controller, 'reset', 'Alterou a senha.');
                }
            }
            if (!is_array($resul) AND $this->render) {
                return $this->redirect()->toRoute('home');
            }
        }else{
            $activationKey = $this->params()->fromRoute('key');
            $entity = $this->getEm()->getRepository($this->entity)->findOneByActivationKey($activationKey);
            if($entity){
                $array = $entity->toArray(TRUE);
                $array['role'] = $entity->getRole(TRUE)->getId();
                unset($array['password']);
                $array['activationKey'] = $activationKey;
                $form->setData($array);
            }else{
                $resul[] = false;
                $resul[] = ['Não foi encontrado o usuario para redifinir senha nesse link!!!'];
            }
        }     
        
        if ($this->render OR is_array($resul)) {
            return $this->makeView(compact("form", "dataView", "entity", "resul"));
        }
        return $resul;
    }
    
}
