<?php
/*
 * License GPL .
 * 
 */

namespace Application\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;

/**
 * Acl
 * View Helper para Carregar as ACL para vericações na view de Acesso
 *      Possibilidade de usar call back para facilitar a chamada
 * 
 * @author Paulo Cordeiro Watakabe <watakabe05@gmail.com>
 */
class Acl extends AbstractHelper {

    /**
     * Classe faz a verificação de privelegio do papel ao recurso.
     * 
     * @var \Application\Permissions\Acl
     */
    protected $acl;
    
    /**
     * Papel para verificação de privilegios a ser verificado.
     * 
     * @var string
     */
    protected $role;
    
    /**
     * Dados do usuario que esta usando a aplicação.
     * 
     * @var array
     */
    protected $user;
    
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param type $sm
     */
    public function __construct(\Application\Permissions\Acl $acl) {
        $this->acl = $acl;
    }    
    
    /**
     * Metodo invoke chamado pela view ao executar esta classe 
     *      Retorna sua propria instacia se nenhum parametro for passado.
     *      Parametriza o usuario caso for passado um array como parametro
     *      Faz uma verificação de acl caso os parametros for validados
     * 
     * @param string | array $resource Opcional recurso se for um string ou array de dados do usuario
     * @param string $privilege        Opcional qual o privilegio em que esta sendo verificado
     * @param string $role             Opcional qual papel vai ser verifica caso null sera verifica o papel padrão
     * @return \Application\View\Helper\Acl
     */
    public function __invoke($resource = '', $privilege = null, $role = null, $deb = false) {
        if(is_array($resource)){
            $this->setUser($resource);
            $resource = '';
        }
        if(empty($resource) and is_null($privilege)){
            return $this;
        }
        if($deb){
            echo '<pre>';
            \Doctrine\Common\Util\Debug::dump($this->user);
            echo '</pre>'; 
        }
        return $this->isAllowed($resource , $privilege , $role );
    }

    /**
     * Retorna o caminha da classe
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @return string
     */
    public function __toString() {
        return '\Application\View\Helper\Acl';
    }
    
    /**
     * Verifica se o priveligio existe ao parametros passados.
     * Caso o role não for passado sera usado o role padrão da classe
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param string $resource
     * @param string $privilege
     * @param string $role
     * @return boolean
     */
    public function isAllowed($resource = '', $privilege = null, $role = null) {
        if(is_null($role)){
            $role = $this->role;
        }
        if(is_null($role)){
            return false;
        }   
        //Se não existir o resource, não executar a validacao de ACL
        if (!$this->acl->hasResource($resource)) {
            return false;
        }
        if($privilege == 'deny' and $this->user['isAdmin']){
            return false;
        }
        return  $this->acl->isAllowed($role, $resource, $privilege);
    }
    
    /**
     * Retorna a classe com todos os acls
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @return \Application\Permissions\Acl Classe faz a verificação de privelegio do papel ao recurso.
     */
    public function getAcl() {
        return $this->acl;
    }
    
    /**
     * Papel para verificação de privilegios a ser verificado.
     * 
     * @param string $role
     * @return \Application\View\Helper\Acl
     */
    public function setRole($role) {
        $this->role = $role;
        return $this;
    }
    
    /**
     * Dados do usuario que esta usando a aplicação.
     * 
     * @param array $user Dados que normalmente são fornecidos pela sessão
     * @return \Application\View\Helper\Acl
     */
    public function setUser(array $user) {
        $this->user = $user;
        if(isset($this->user['role'])){
            $this->setRole($this->user['role']);
        }
        return $this;
    }

}
