<?php

/*
 * To change this license
 */

namespace Application\Entity\Repository;

/**
 * Description of AppMenuRepository
 *
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
class AppMenuRepository extends AbstractRepository {
    
    protected $list ;
    protected $acl ;
    protected $role ;

    public function fetchParent() {
        /* @var $entity \Application\Entity\AppMenu */
        $entities = $this->findAll();
        $array = ['' => 'selecione da lista'];
        foreach ($entities as $entity) {
            $array[$entity->getId()] = $entity->getDescricao();
        }
        return $array;
    }
    
    public function isAllowed(\Application\Entity\appMenu $ent) {        
        if(empty($ent->getResource())){
            return TRUE;
        }
        $resource = $ent->getResource();
        $privilege = null ;
        if(!empty($ent->getPrivilege())){
            $privilege = $ent->getPrivilege();
        }        
        return $this->acl->isAllowed($this->role,$resource,$privilege)? TRUE : FALSE;
    }
    
    public function getNavigationArray($name, $acl, $user){
        $this->acl = $acl;
        if($user AND !empty($user['role'])){
            $this->role = $user['role'];
        }else{
            $this->role = 'Visitante';
        }
        /* @var $ent \Application\Entity\appMenu */
        $this->list = $this->findBy([],['ordem'=>'ASC']);
        $configuration = [];
        foreach ($this->list as $ent) {
            if($ent->getInMenu() OR !$this->isAllowed($ent)){
                continue;
            }
            $pages2 = $this->getPagesById($ent->getId());
            $configuration['navigation'][$name][$ent->getId()] = $this->getMenuArray($ent, (!empty($pages2)?TRUE:FALSE));
            if(!empty($pages2)){
                $configuration['navigation'][$name][$ent->getId()]['pages']     = $pages2;                             
            }
        }
//echo '<pre>', var_dump($configuration);
//die;
        return $configuration;
    }

    public function getPagesById($id) {
        /* @var $ent \Application\Entity\appMenu */
        $page = [];
        foreach ($this->list as $ent) {
            if(is_null($ent->getInMenu()) OR $ent->getInMenu()->getId() <> $id OR !$this->isAllowed($ent)){
                continue;
            }
            $pages3 = $this->getPagesById($ent->getId());
            $page[$ent->getId()] = $this->getMenuArray($ent,  (!empty($pages3)?TRUE:FALSE));
            if(!empty($pages3)){
                $page[$ent->getId()]['pages'] = $pages3;
            }
        }
        
        return $page;
    }
    
    public function getMenuArray($ent,$subMenu){
        /* @var $ent \Application\Entity\appMenu */
        $menu = [
           'label'      => $this->makeLabel($ent->getLabel(),$ent->getIcons(),$subMenu), 
           'route'      => $ent->getRoute(),
        ];     
        if(!empty($ent->getController())){
            $menu['controller'] = $ent->getController();
        }      
        if(!empty($ent->getAction())){
            $menu['action'] = $ent->getAction();
        }      
        if(!empty($ent->getWrapclass())){
            $menu['wrapClass'] = $ent->getWrapclass();
        }   
        if(!empty($ent->getClass())){
            $menu['Class'] = $ent->getClass();
        }
        if(!empty($ent->getPagescontainerclass())){
            $menu['pagesContainerClass'] = $ent->getPagescontainerclass();
        }
        if(!empty($ent->getAtributos())){
            $menu['attribs'] = $this->multAtrib($ent->getAtributos());
        }
//        if(!empty($ent->getResource())){
//            $menu['resource'] = $ent->getResource();
//        }
//        if(!empty($ent->getPrivilege())){
//            $menu['privilege'] = $ent->getPrivilege();
//        }
        return $menu;
    }
    
    public function multAtrib($str) {
        $array = explode(',', $str);
        $a = [];
        foreach ($array as $value) {
            $kv = explode('=>', $value);
            $a[$kv[0]] = $kv[1];
        }
        return $a;
    }
    
    public function makeLabel($label, $icon, $subMenu) {
        $lb = '';
        if(!empty($icon)){
            $lb = '<i class="fa ' . $icon . ' fa-fw"></i>';
        }
        if($subMenu){
            return $lb . $label . '<span class="fa arrow"></span>';            
        }
        return $lb . $label;
    }

}
