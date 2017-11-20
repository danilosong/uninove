<?php

namespace Application\Controller;

use Zend\Authentication\AuthenticationService,
    Zend\Authentication\Storage\Session as SessionStorage;
use Application\Form\Login as LoginForm;

class AuthController extends CrudController {
    
    public function __construct($name = 'auth', $module = 'Application') {
        parent::__construct($name, $module);        
        $this->service = $this->moduloName . "\Service\Auth" ;
        $this->controller = $this->name;
    }

    public function indexAction($filtro = array(), array $orderBy = array(), \Doctrine\ORM\QueryBuilder $list = null) {
        $form = new LoginForm($this->getTerminalBoolean());
        $error = false;
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            $data = $request->getPost()->toArray();
            $subOpcao = $data['subOpcao'] ?? false ;
            if ($subOpcao == 'passwd' and $form->isValid()) {
                // Criando Storage para gravar sessão da authtenticação
                $sessionStorage = new SessionStorage($this->moduloName);
                $auth = new AuthenticationService;
                $auth->setStorage($sessionStorage); // Definindo o SessionStorage para a auth
                $authAdapter = $this->getServiceLocator()->get($this->moduloName . "\Auth\Adapter");
                $authAdapter->setUsername($data['email'])->setPassword($data['password']);
                $result = $auth->authenticate($authAdapter);
                if ($result->isValid()) {
                    $sessionStorage->write($auth->getIdentity()['user'], null);
                    if($this->getUser()){
                        return $this->setRedirect(['controller' => 'index']);
                    }
                    $error = true;
                } else {
                    $error = true;
                }
            }
            if($subOpcao == 'logon'){
                $data['subOpcao'] = 'passwd';
                $form->setData($data);
            }
        }else{
            $opt = $this->params()->fromQuery('opt', '');
            if($opt == 'reset'){
                $login  = $this->params()->fromQuery('l', 0);
                $resul = $this->getService()->reset($login);
            }
        }
        $dataView = $this->getDataView('Login no Sistema.');
        $dataView['loginName'] = isset($data['email']) ? $data['email'] : ''; 
        $this->layout()->setVariable('login', isset($data['email']) ? $data['email'] : false);
        return $this->makeView(compact("form","error","dataView", "resul"));
    }

    public function logoutAction() {
        $auth = new AuthenticationService;
        $auth->setStorage(new SessionStorage($this->moduloName));
        $auth->clearIdentity();

        return $this->redirect()->toRoute('application-auth');
    }

}
