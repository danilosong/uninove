<?php

namespace Application\Controller;

use Application\Form\Usuario as FormUser;

class IndexController extends CrudController {

    public function registerAction() {
        $form = new FormUser();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $service = $this->getServiceLocator()->get($this->moduloName . "\Service\Usuario");
                $entity = $service->insert($request->getPost()->toArray());
                if ($entity) {
                    $fm = $this->flashMessenger()
                            ->setNamespace($this->moduloName)
                            ->addMessage("Usuario cadastrado com sucesso");
                }
                return $this->redirect()->toRoute('user-register');
            }
        }

        $messages = $this->flashMessenger()
                ->setNamespace($this->moduloName)
                ->getMessages();

        $data['titulo'] = 'Novo ' . $this->name;
        $data['action'] = 'new';
        $data['ajax'] = FALSE;
        
        return $this->makeView(compact('form','messages','data'), FALSE, $this->getPathViewDefault('usuarios') . 'form.phtml');
    }

    public function activateAction() {
        $activationKey = $this->params()->fromRoute('key');
        /* @var $userService \Application\Service\Usuario */
        $userService = $this->getServiceLocator()->get($this->moduloName . '\Service\Usuario');
        $result = $userService->activate($activationKey);

        if ($result) {
            return $this->makeView(array('user' => $result),FALSE);
        } else {
            return $this->makeView([],FALSE);
        }
    }

    public function indexAction($filtro = array(), array $orderBy = array(), \Doctrine\ORM\QueryBuilder $list = null) {
        return $this->makeView([]);
    }

    public function logedAction() {
        return $this->makeView([]);
    }

    public function cadastroAction() {
        return $this->makeView([]);
    }    
    
    public function buildIndexAction() {        
        $data = $this->getRequest()->getPost()->toArray();
        
        return $this->makeView(compact("data"));
    }

}
