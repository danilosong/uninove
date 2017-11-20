<?php

/*
 * To change this license 
 */

namespace Application\Form;

/**
 * Description of AppRole
 *
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
class AppRole extends AbstractForm{
    
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 05-11-2017
     * @param boolean $filter Por padrão(TRUE) carrega os filtros do form
     */
    public function setInputs($filter = TRUE) {
        if ($filter) {
            $this->setInputFilter(new Filter\AppRoleFilter($this->name, $this->ret, $this->getTargetForm()->prefix));
        }        
        
        $this->setInputHidden('idRole');
        
        $this->setInputText2('nome', 'Nome: ',['placeholder'=>'Entre com o nome']);
        
        $selectOptionsParent = array_merge([0 => 'Nenhum'], $this->getAllParents());
        $this->setInputSelect('parent', 'Herda: ', $selectOptionsParent,['placeholder'=>'Herda acesso de alguem!']);
        
        $this->setInputCheckbox('isAdmin', 'Admin ?: ', []);
        
    }  
    
    public function getAllParents() {
        /* @var $repository \Application\Entity\Repository\AppRoleRepository */
        $repository = $this->em->getRepository($this->moduloName . "\Entity\AppRole");
        return $repository->fetchParent();
    }
}
