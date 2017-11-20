<?php

/*
 * License GPL .
 * 
 */

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Authentication\AuthenticationService,
    Zend\Authentication\Storage\Session as SessionStorage;

/**
 * Description of UserIdentity
 *
 * Help para retornar todos os dados do usuario em forma de array que estão na sessão.
 * 
 * @author Paulo Cordeiro Watakabe <watakabe05@gmail.com>
 */
class UserIdentity extends AbstractHelper {

    /**
     *
     * @var \Zend\Authentication\AuthenticationService 
     */
    protected $authService;
    
    /**
     *
     * @var \Zend\Authentication\Storage\Session 
     * 
     */
    protected $sessionStorage;

    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param string $namespace
     * @return \Zend\Authentication\AuthenticationService
     */
    public function getAuthService($namespace = "Application") {
        if(!is_null($this->authService)){
            return $this->authService;            
        }
        $this->sessionStorage = new SessionStorage($namespace);
        $this->authService = new AuthenticationService;
        $this->authService->setStorage($this->sessionStorage);
        return $this->authService;
    }

    /**
     * Busca os dados do usuario na sessão e retorna em um array
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param string $namespace
     * @return boolean | array Retorna falso caso não encontre nada
     */
    public function __invoke($namespace = "Application") {
        if ($this->getAuthService($namespace)->hasIdentity()) {
            return $this->getAuthService($namespace)->getIdentity();
        }
        return false;
    }
    
    /**
     * Converte objeto para string com o caminho completo para esta classe
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @return string
     */
    public function __toString() {
        return 'Application\View\Helper\UserIdentity';
    }

}
