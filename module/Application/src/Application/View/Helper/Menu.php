<?php
/*
 * License GPL .
 * 
 */

namespace Application\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;

/**
 * Menu
 * Helper para pegar os menus do sistema e gerar atalhos ou menus personalizados
 * 
 * @author Paulo Cordeiro Watakabe <watakabe05@gmail.com>
 * @since 11-02-2016
 */
class Menu extends AbstractHelper {

    /**
     * Manipular o banco de dados
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * Helper que faz a verificação de privelegio do papel ao recurso.
     * 
     * @var \Application\View\Helper\Acl
     */
    protected $acl;
    
    /**
     * Helper url para geração dos links do menus baseado em rotas / controller / actions
     * 
     * @var \Application\View\Helper\Url
     */
    protected $url;
    
    /**
     * O array ja parametrizado para geração dos menus
     *
     * @var array
     */
    protected $array = [];
    
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 12-02-2016
     * @param type $sm
     */
    public function __construct(\Application\View\Helper\Acl $acl, \Doctrine\ORM\EntityManager $em, \Application\View\Helper\Url $url) {
        $this->acl = $acl;
        $this->em = $em;
        $this->url = $url;
    }    
    
    /**
     * Metodo invoke chamado pela view ao executar esta classe 
     *      Retorna um array com todos os menus do sistema
     *      Somente os menus que o usuario tem acesso no sistema 
     *      Usa o helper url para montar o link de acesso
     *      Faz uma verificação de acl caso os parametros for validados
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 12-02-2016
     * @param array | string $opt Opções para montagem 
     * @return array Com os itens de menus para acesso montados e acesso verificados
     */
    public function __invoke($opt = []) {
        if(empty($this->array)){
            $this->makeArrayMenu();
        }
        return $this->array;
    }
    
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 12-02-2016
     */
    public function makeArrayMenu() {
        $menus = $this->em->getRepository('\Application\Entity\AppMenu')->findBy([],['ordem' => 'ASC']);
        $ord = '';
        $ordem = '';
        $subMenu = '';
        $deny1 = $deny2 = false;
        /* @var $acl \Application\View\Helper\Acl  */
        $acl = $this->acl;
        /* @var $menu \Application\Entity\AppMenu  */
        foreach ($menus as $menu) {
            $ordem = explode( '.', $menu->getOrdem());
            $resource = $menu->getResource();
            if(!empty($resource)){ 
                $permission = true ;
                if($acl->getAcl()->hasResource($resource) and !$acl($resource, $menu->getPrivilege())){
                    $permission = false ;
                }
//                $resource = str_replace('/default', '', $menu->getRoute()) . ':' . $menu->getController() . ':*' ;
//                if($acl->getAcl()->hasResource($resource) and $acl($resource, 'allow')){
//                    $permission = true ;
//                }
//                if($acl->getAcl()->hasResource($resource) and $acl($resource, 'deny')){
//                    $permission = false ;
//                }
                if(!$permission){
                    $deny1 = (count($ordem) == 1) ? true : false;  // Caso for um item de Head bloquear tb o SubMenu e Menus
                    $deny2 = (count($ordem) == 2) ? true : false;  // Caso for um item de SubMenu bloquear tb os Menus
                    continue;
                }
            }
            if(count($ordem) == 1){
                $ord = $menu->getLabel();
                $this->array[$ord]['options'] = $this->getOptionsOfMenu($menu);
                $deny1 = false; // Desbloqueia o acesso ao SubMenus e Menus
                continue;
            }
            if($deny1){
                continue;
            }
            if(count($ordem) == 2){
                $subMenu = $menu->getLabel();
                $this->array[$ord][$subMenu]['options'] = $this->getOptionsOfMenu($menu);
                $deny2 = false; // Desbloqueia o acesso ao Menus
                continue;
            }
            if($deny1 OR $deny2){
                continue;
            }
            $this->array[$ord][$subMenu][$menu->getLabel()]['options'] = $this->getOptionsOfMenu($menu);
        }
    }
    
    /**
     * Monta os options do menu usando a entidade AppMenu
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 12-02-2016
     * @param \Application\Entity\AppMenu $menu
     * @return array Array com a configuração do menu
     */
    public function getOptionsOfMenu($menu) {
        $url = $this->url;
        return [
            'url' => (!empty($menu->getController())) ? $url($menu->getRoute(),['controller' => $menu->getController(), 'action' => $menu->getAction()],[],false,true) : '', 
            'desc' => $menu->getDescricao(), 
            'icon' => $menu->getIcons(),
            'class' => $menu->getClass(),
        ];        
    }

    /**
     * Retorna o caminho da classe
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 12-02-2016
     * @return string
     */
    public function __toString() {
        return '\Application\View\Helper\Menu';
    }
    
}
