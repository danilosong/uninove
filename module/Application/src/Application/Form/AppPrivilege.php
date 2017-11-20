<?php

/*
 * To change this license 
 */

namespace Application\Form;

/**
 * Description of AppPrivilege
 *
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
class AppPrivilege extends AbstractForm{

    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 05-11-2017
     * @param boolean $filter Por padrão(TRUE) carrega os filtros do form
     */
    public function setInputs($filter = TRUE) {
        if ($filter) {
            $this->setInputFilter(new Filter\AppPrivilegeFilter($this->name, $this->ret, $this->getTargetForm()->prefix));
        }
        
        $this->setInputHidden('idPrivilege');
        
        $this->setInputText2('nome', 'Nome: ',['placeholder'=>'Entre com o nome']);
        
        $selectOptionsRole = $this->getAllRoles();
        $this->setInputSelect('role', 'Role: ', $selectOptionsRole,['placeholder'=>'Regra para']);
        
        $selectOptionsResouce = $this->getAllResources();
        $this->setInputSelect('resource', 'Resource: ', $selectOptionsResouce,['placeholder'=>'Recurso do sistema']);
        
    }   
        
    public function getAllRoles() {
        /* @var $repository \Application\Entity\Repository\AppRoleRepository */
        $repository = $this->em->getRepository($this->moduloName . "\Entity\AppRole");
        return $repository->fetchParent();
    }
    
    public function getAllResources() {
        /* @var $repository \Application\Entity\Repository\AppResourceRepository */
        $repository = $this->em->getRepository($this->moduloName . "\Entity\AppResource");
        return $repository->fetchPairs();
    }
}
