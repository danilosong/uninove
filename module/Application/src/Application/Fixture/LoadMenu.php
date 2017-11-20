<?php

namespace Application\Fixture;

use Doctrine\Common\DataFixtures\AbstractFixture,
    Doctrine\Common\DataFixtures\OrderedFixtureInterface,
    Doctrine\Common\Persistence\ObjectManager;
use Application\Conexao\Mysql;

/**
 * @author Danilo Dorotheu <danilo.dorotheu@live.com>
 * @author Paulo Watakabe <watakabe05@gmail.com>
 * @tutorial https://github.com/doctrine/data-fixtures
 * @version 2.0
 * @since 21-025-2016
 */
class LoadMenu extends AbstractFixture implements OrderedFixtureInterface {

    /**
     * Contem caminho para a entidade a ser trabalhada
     * @var string
     */
    public $entity;

    /**
     * Entity Manager
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    public $em;

    /**
     * Contem a ordem dos menus e dentro de quem esta contido a proxima sequencia
     * @var array 
     */
    public $order = [];
    
    public $mysql;
    
    public $data;

    public function getOrder() {
        return 5;
    }
    
    /**
     * 
     * @return \Application\Conexao\Mysql
     */
    public function getMysql() {
        if(is_null($this->mysql)){
            $this->mysql = new Mysql();
        }
        return $this->mysql;
    }

    public function load(ObjectManager $manager = null) {
        $this->entity = 'Application\Entity\AppMenu';
        echo 'entity ', $this->entity;
        // Limpar tabela
        if(!is_null($manager)){
            $this->em = $manager;
            $this->getMysql()->truncate($this->entity,$manager);
        }
        
        /**====================================================================+ 
         * -- SISTEMA ---------------------------------------------------------|
         * +===================================================================+
         * | Menu que vai conter menus dentro
         */
        $sisOrder = $this->getOrderOfMenu('+');
        $menuSistema = $this->getArrayData('Funcionalidades do Sistema', 'Sistema', $sisOrder, 'fa fa-cogs', '', 'app/default','','','','','','menu:app:sistema','allow');
        $entSistema = new $this->entity($menuSistema);
        $manager->persist($entSistema);         
        
        /**+====================================================================
         * | SISTEMA -> CADASTRO  
         * +====================================================================
         * | SubMenu que vai conter menus dentro
         */
        $cadOrder = $this->getOrderOfMenu($sisOrder,'+');
        $menuCadastro = $this->getArrayData('Casdastros do Sistema', 'Cadastro', $cadOrder, 'fa fa-pencil', $entSistema, 'app/default','','','nav nav-second-level');
        $entCadastro = new $this->entity($menuCadastro);
        $manager->persist($entCadastro);
        
        // Menus que serao inseridos dentros do submenu
        $this->data[]= $this->getArrayData('Menu do sistema'                , 'Menu'               , $this->getOrderOfMenu($sisOrder,$cadOrder), 'fa fa-pencil', $entCadastro, 'app/default','appMenus','index','nav nav-third-level');
        $this->data[]= $this->getArrayData('roles'                          , 'Papeis'             , $this->getOrderOfMenu($sisOrder,$cadOrder), 'fa fa-pencil', $entCadastro, 'app/default','appRoles','index');
        $this->data[]= $this->getArrayData('resources'                      , 'Recursos'           , $this->getOrderOfMenu($sisOrder,$cadOrder), 'fa fa-pencil', $entCadastro, 'app/default','appResources','index');
        $this->data[]= $this->getArrayData('privileleges'                   , 'Privilegios'        , $this->getOrderOfMenu($sisOrder,$cadOrder), 'fa fa-pencil', $entCadastro, 'app/default','appPrivileges','index');
        $this->data[]= $this->getArrayData('parametros'                     , 'Parametros'         , $this->getOrderOfMenu($sisOrder,$cadOrder), 'fa fa-pencil', $entCadastro, 'app/default','parametros','index');
        $this->data[]= $this->getArrayData('CRUD para usuario'              , 'Usuarios do Sistema', $this->getOrderOfMenu($sisOrder,$cadOrder), 'fa fa-pencil', $entCadastro, 'app/default','usuarios','index');
        $this->data[]= $this->getArrayData('Mensagem do chat'               , 'Mensagem'           , $this->getOrderOfMenu($sisOrder,$cadOrder), 'fa fa-pencil', $entCadastro, 'app/default','mensagems','index');
        $this->data[]= $this->getArrayData('teste de cadastro'              , 'Teste Crud'         , $this->getOrderOfMenu($sisOrder,$cadOrder), 'fa fa-pencil', $entCadastro, 'app/default','testes','index');
        $this->data[]= $this->getArrayData('Gerador Gets Sets'              , 'Gerar Gets Sets'    , $this->getOrderOfMenu($sisOrder,$cadOrder), 'fa fa-pencil', $entCadastro, 'app/default','testes','generate');
        
        /**+====================================================================
         * | SISTEMA CHAT -> CADASTRO  
         * +====================================================================
         * | SubMenu que vai conter menus dentro
         */
        $chatOrder = $this->getOrderOfMenu($sisOrder,'+');
        $menuChat  = $this->getArrayData('Sistema Chat', 'Chat Cadastros', $chatOrder, 'fa fa-pencil', $entSistema, 'app/default','','','nav nav-second-level');
        $entChat = new $this->entity($menuChat);
        $manager->persist($entChat);
        
        // Menus que serao inseridos dentros de menu ou submenus
        $this->data[]= $this->getArrayData('Usuario do chat'                , 'user'               , $this->getOrderOfMenu($sisOrder,$chatOrder), 'fa fa-pencil', $entChat    , 'app/default','users','index','nav nav-third-level');
        $this->data[]= $this->getArrayData('Registrar os envios de mensagem', 'Enviado'            , $this->getOrderOfMenu($sisOrder,$chatOrder), 'fa fa-pencil', $entChat    , 'app/default','enviados','index');
        $this->data[]= $this->getArrayData('Contatos'                       , 'Contatos'           , $this->getOrderOfMenu($sisOrder,$chatOrder), 'fa fa-pencil', $entChat    , 'app/default','contatos','index');
        $this->data[]= $this->getArrayData('Grupos'                         , 'Grupos'             , $this->getOrderOfMenu($sisOrder,$chatOrder), 'fa fa-pencil', $entChat    , 'app/default','grupos','index');
        $this->data[]= $this->getArrayData('AppLog'                         , 'AppLog'             , $this->getOrderOfMenu($sisOrder,$chatOrder), 'fa fa-pencil', $entChat    , 'app/default','applogs','index');
        
        /**+====================================================================
         * | CEP
         * +====================================================================
         * | SubMenu que vai conter menus dentro
         */
        $endOrder = $this->getOrderOfMenu($sisOrder, '+');
        $menuEnd = $this->getArrayData('CEP Modulo', 'CEP Modulo', $endOrder, 'fa fa-pencil', $entSistema, 'app/default', '', '', 'nav nav-second-level');
        $entEnd = new $this->entity($menuEnd);
        $manager->persist($entEnd);

        $this->data[] = $this->getArrayData('Cadastro de Enderecos', 'Ruas'   , $this->getOrderOfMenu($sisOrder, $endOrder), 'fa fa-pencil', $entEnd, 'end/default', 'enderecos', 'index', 'nav nav-third-level');
        $this->data[] = $this->getArrayData('Cadastro de Bairros'  , 'Bairros', $this->getOrderOfMenu($sisOrder, $endOrder), 'fa fa-pencil', $entEnd, 'end/default', 'bairros'  , 'index', ''                   );
        $this->data[] = $this->getArrayData('Cadastro de Cidades'  , 'Cidade' , $this->getOrderOfMenu($sisOrder, $endOrder), 'fa fa-pencil', $entEnd, 'end/default', 'cidades'  , 'index', ''                   );
        $this->data[] = $this->getArrayData('Cadastro de estados'  , 'Estado' , $this->getOrderOfMenu($sisOrder, $endOrder), 'fa fa-pencil', $entEnd, 'end/default', 'ufs'      , 'index', ''                   );
        $this->data[] = $this->getArrayData('Cadastro de paises'   , 'Paises' , $this->getOrderOfMenu($sisOrder, $endOrder), 'fa fa-pencil', $entEnd, 'end/default', 'Countrys' , 'index', ''                   );

        foreach ($this->data as $item) {
            $ent = new $this->entity($item);
            $manager->persist($ent);
        }

        $manager->flush();
        
        echo ' ok itens ', count($this->data) , PHP_EOL;
        
    }

    /**
     * Metodo para montar array com dados para menu
     * @param string $desc
     * @param string $label
     * @param string $ordem
     * @param string $icons
     * @param object $InMen
     * @param string $route
     * @param string $controller
     * @param string $action
     * @param string $pagesContainerClass
     * @param string $class
     * @param string $wrapClass
     * @param string $resource
     * @param string $privilege
     * @param string $atributos
     * @return array
     */
    public function getArrayData($desc, $label, $ordem, $icons, $InMen, $route, $controller = '', $action = '', $pagesContainerClass = '', $class = '', $wrapClass = '', $resource = '', $privilege = '', $atributos = '') {
        $data = [
            "Descricao" => $desc,
            "Label" => $label,
            "Ordem" => $this->makeFormat($ordem),
            "Icons" => $icons,
//            "InMenu"              => $InMen,
            "Route" => $route,
            "Controller" => $controller,
            "Action" => $action,
            "PagesContainerClass" => $pagesContainerClass,
            "Class" => $class,
            "WrapClass" => $wrapClass,
            "Resource" => $resource,
            "Privilege" => $privilege,
            "Atributos" => $atributos,
        ];
        if (!empty($InMen)) {
            $data['InMenu'] = $InMen;
        }
        return $data;
    }

    /**
     * Pega a string do menu e formata para inclusão no banco.
     * Objetivo e nao depender do usuario para o correto funcionamento do ordeBy do BD
     * @param string $ordem
     * @return string formatada para sequencia do menu
     */
    public function makeFormat($ordem) {
        $split = explode('.', $ordem);
        $newOrdem = '';
        $sep = '';
        foreach ($split as $value) {
            $newOrdem = $newOrdem . $sep . str_pad($value, 3, "0", STR_PAD_LEFT);
            $sep = '.';
        }
        return $newOrdem;
    }

    public function getOrderOfMenu($menu, $subMenu = '', $subSubMenu = '+') {
        if ($menu == '+') {
            if (empty($this->order)) {
                $this->order['menu'] = 1;
            } else {
                $this->order['menu'] ++;
            }
            return $this->order['menu'];
        }
        if ($subMenu == '+') {
            if (!isset($this->order[$menu]['subMenu'])) {
                $this->order[$menu]['subMenu'] = 1;
            } else {
                $this->order[$menu]['subMenu'] ++;
            }
            return $menu . '.' . $this->order[$menu]['subMenu'];
        }
        if ($subSubMenu == '+') {
            if (!isset($this->order[$menu][$subMenu])) {
                $this->order[$menu][$subMenu]['subSubMenu'] = 1;
            } else {
                $this->order[$menu][$subMenu]['subSubMenu'] ++;
            }
        }
        return $subMenu . '.' . $this->order[$menu][$subMenu]['subSubMenu'];
    }

}
