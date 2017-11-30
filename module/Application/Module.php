<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Application\Auth\Adapter as AuthAdapter;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\ModuleManager\ModuleManager;

class Module {

    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);    
//        $eventManager->getSharedManager()->attach(
//            'Zend\Mvc\Controller\AbstractActionController', 
//            'dispatch', 
//                function($e){
//                $controller = $e->getTarget();
//                $config = $e->getApplication()->getServiceManager()->get('config');
//                $ajax = 'no';
//                if(isset($_GET['ajax']) AND $_GET['ajax'] == 'ok'){
//                    $ajax = 'ok';       
//                }
//                if($controller->params()->fromRoute('ajax', 'no') == 'ok'){
//                    $ajax = 'ok';  
//                }               
//                if (isset($config['module_layouts'][$ajax])) {
//                    $controller->layout($config['module_layouts'][$ajax]);
//                }
//            }, 
//            100
//        );  
        
//        $eventManager->attach(
//            'dispatch.error',
//            array($this,'noLayout'), 
//            99
//        );
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                    'Adm'         => __DIR__ . '/src/Adm',
                ),
            ),
        );
    }

    public function init(ModuleManager $mm) {
        $sharedEvents = $mm->getEventManager()->getSharedManager();
        
        $sharedEvents->attach(
            "Zend\Mvc\Controller\AbstractActionController", 
            MvcEvent::EVENT_DISPATCH, 
            [$this, 'validaAuth'], 
            100
        );
        $sharedEvents->attach(
                "Zend\Mvc\Controller\AbstractActionController", 
                MvcEvent::EVENT_DISPATCH, 
                [$this, 'validaAcl'], 
                101
        );
    }
    
    public function noLayout($e) {
        $controller = $e->getTarget();
        $config = $e->getApplication()->getServiceManager()->get('config');
        $ajax = 'no';
        if(isset($_GET['ajax']) AND $_GET['ajax'] == 'ok'){
            $ajax = 'ok';       
        }
//        if($controller->params()->fromRoute('ajax', 'no') == 'ok'){
//            $ajax = 'ok';  
//        }               
//        if (isset($config['module_layouts'][$ajax])) {
//            $controller->layout($config['module_layouts'][$ajax]);
//        }        
    }
    
    /**
     * Validador de acesso utilizando regras de ACL baseadas no nome da rota,
     * nome do controller e nome da action. Este evento será chamado em todos
     * os requests do sistema
     * 
     * @param MvcEvent $e
     * @return mixed
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @since 07-04-2016
     */
    public function validaAcl($e) {
        //Verifica se usuário esta logado. Se não estiver, não executar
        //a validacao de ACL
        $auth = new AuthenticationService;
        $auth->setStorage(new SessionStorage("Application"));
        if (!$auth->hasIdentity()) {
            return;
        }

        $controller = $e->getTarget();
        $matchedRoute = $controller->getEvent()->getRouteMatch();
        $params = $matchedRoute->getParams();
        $aux = $matchedRoute->getMatchedRouteName();
        
        $routeName = str_replace("/default", "", $aux);
        $controllerName = $params["__CONTROLLER__"] ?? '';
        $actionName = $params["action"];
        
        /* @var $acl \Application\View\Helper\Acl */
        /* @var $flashMessenger \Zend\Mvc\Controller\Plugin\FlashMessenger */
        $application = $e->getApplication();
        $viewHelper = $application->getServiceManager()->get('ViewHelperManager');
        $acl = $viewHelper->get('Acl');

        $resource = $routeName . ":" . $controllerName . ":" . $actionName;
        $generico = $routeName . ":" . $controllerName . ":*";

        //Se não existir o resource, não executar a validacao de ACL
        if (!$acl->getAcl()->hasResource($resource)) {
            return;
        }
        $permission = true;
        $flashMessenger = $controller->plugin('FlashMessenger');
        
        // não possui acesso especifico a esta action
        if ($acl->getAcl()->hasResource($resource) AND !$acl($resource, "allow")) {
            $permission = false;
        }
        // Usuario tem acesso a qualquer action do controller
        if ($acl->getAcl()->hasResource($generico) AND $acl($generico , "allow")) {
            $permission = true;
        }
        // Usuario nao tem acesso a qualquer action do controller
        if ($acl->getAcl()->hasResource($generico) AND $acl($generico , "deny")) {
            $permission = false;
        }
        // possui acesso definido a esta action
        if ($acl->getAcl()->hasResource($resource) AND $acl($resource, "allow")) {
            $permission = true;
        }
        if($permission){
            return; // permissão aceita ao controller e action.
        }
        // Permissão negada e volta ao menu principal action buildIndex
        $flashMessenger->addErrorMessage("Você não tem permissão para acessar esta área");
        return $controller->redirect()->toRoute($routeName . "/ajax", ["controller" => "index", "action" => "buildIndex", "ajax" => "ok"]);
    }

    public function validaAuth($e) {
        $auth = new AuthenticationService;
        $auth->setStorage(new SessionStorage("Application"));
        if($auth->hasIdentity()){
            return;
        }        
        $controller = $e->getTarget();
        $matchedRoute = $controller->getEvent()->getRouteMatch()->getMatchedRouteName();
        if(strpos($matchedRoute, 'console_') !== FALSE){
            return;
        }
        switch ($matchedRoute){
            case "user-activate":
            case "user-reset":
            case "application-auth":
            case "doctrine_cli":
                return;
            default :    
                echo $matchedRoute;
                return $controller->redirect()->toRoute("application-auth");
        }
    }

    public function getServiceConfig() {
        return array(
            'factories' => array(
                'Application\Mail\Transport' => function($sm) {
                    $config = $sm->get('Config');
                    $transport = new SmtpTransport;
                    $options = new SmtpOptions($config['mail']);
                    $transport->setOptions($options);

                    return $transport;
                },
                'Application\Service\Usuario' => function ($sm) {
                    return new Service\Usuario($sm->get('Doctrine\ORM\EntityManager'), $sm->get('Application\Mail\Transport'), $sm->get('View'));
                },
                'Application\Auth\Adapter' => function($sm) {
                    return new AuthAdapter($sm->get('Doctrine\ORM\EntityManager'));
                },
                'Application\Service\Parametros' => function($sm) {
                    return new Service\Parametros($sm->get('Doctrine\ORM\EntityManager'));
                },
                'Application\Service\User' => function($sm) {
                    return new Service\User($sm->get('Doctrine\ORM\EntityManager'));
                },
                'Application\Service\Grupo' => function($sm) {
                    return new Service\Grupo($sm->get('Doctrine\ORM\EntityManager'));
                },
                'Application\Service\Contato' => function($sm) {
                    return new Service\Contato($sm->get('Doctrine\ORM\EntityManager'));
                },
                'UserIdentity' => function($sm) {
                    return new \Application\View\Helper\UserIdentity();
                },
                'Application\Permissions\Acl' => function($sm){
                    $em = $sm->get('Doctrine\ORM\EntityManager');

                    $repoRole = $em->getRepository("Application\Entity\AppRole");
                    $roles = $repoRole->findAll();

                    $repoResource = $em->getRepository("Application\Entity\AppResource");
                    $resources = $repoResource->findAll();

                    $repoPrivilege = $em->getRepository("Application\Entity\AppPrivilege");
                    $privileges = $repoPrivilege->findAll();

                    return new \Application\Permissions\Acl($roles,$resources,$privileges);
                },
                'Application\Mail\Transport' => function($service) {
                    $config = $service->get('Config');
                    $transport = new SmtpTransport;
                    $options = new SmtpOptions($config['mail']);
                    $transport->setOptions($options);
                    return $transport;
                },
            )
        );
    }
    
    public function getViewHelperConfig() {
        return [
            'invokables' => [
                'UserIdentity' => 'Application\View\Helper\UserIdentity',
                'Table'        => 'Application\View\Helper\Table',
                'FormHelp'     => 'Application\View\Helper\FormHelp',
                'NewMenu'      => 'Application\View\Helper\NewMenu',
                'PartialObj'   => 'Application\View\Helper\PartialObj',
                'GraphBar'     => 'Application\View\Helper\GraphBar',
            ],
            'factories' => [
                'Url'            => function ($helperPluginManager) {
                    $serviceLocator = $helperPluginManager->getServiceLocator();
                    $view_helper =  new \Application\View\Helper\Url();
                    $router = \Zend\Console\Console::isConsole() ? 'HttpRouter' : 'Router';
                    $view_helper->setRouter($serviceLocator->get($router));

                    $match = $serviceLocator->get('application')
                        ->getMvcEvent()
                        ->getRouteMatch();

                    if ($match instanceof RouteMatch) {
                        $view_helper->setRouteMatch($match);
                    }

                    return $view_helper;
                },
                'Param' => function ($helperPluginManager) {                    
                    return new \Application\View\Helper\Param($helperPluginManager->getServiceLocator());
                },
                'Acl' => function ($helperPluginManager) {   
                    $acl = new \Application\View\Helper\Acl($helperPluginManager->getServiceLocator()->get('Application\Permissions\Acl'));
                    $user = $helperPluginManager->getServiceLocator()->get('ViewHelperManager')->get('UserIdentity');
                    $acl->setUser($user());
                    return $acl;
                },
                'Menu' => function ($helperPluginManager) {   
                    $acl = $helperPluginManager->getServiceLocator()->get('ViewHelperManager')->get('Acl');
                    $em   = $helperPluginManager->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                    $url  = $helperPluginManager->getServiceLocator()->get('ViewHelperManager')->get('Url');
                    return new \Application\View\Helper\Menu($acl, $em, $url);
                },
                'Image' => function ($helperPluginManager) {   
                    $param  = $helperPluginManager->getServiceLocator()->get('ViewHelperManager')->get('Param');
                    $acl    = $helperPluginManager->getServiceLocator()->get('ViewHelperManager')->get('Acl');
                    $em     = $helperPluginManager->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                    return new \Application\View\Helper\Image($param, $acl, $em);
                },
                'TableOptions' => function ($helperPluginManager) {   
                    $em    = $helperPluginManager->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                    $table = $helperPluginManager->getServiceLocator()->get('ViewHelperManager')->get('Table');
                    return new \Application\View\Helper\TableOptions($em, $table);
                }
            ],
        ];
    }

}
