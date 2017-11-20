<?php

/*
 * To change this license 
 */

namespace Application\Form;

/**
 * Description of AppMenu
 *
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
class AppMenu extends AbstractForm{
    
    /**
     * Define os inputs padrões para o form.phtml
     *
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 12-09-2017
     * @param   boolean $filter Busca o filtro referente a esta entidade. Setar false caso não for necessario a aplicacao de filtros
     */
    public function setInputs($filter = TRUE) {
        if ($filter) {
            $this->setInputFilter(new Filter\AppMenuFilter($this->name, $this->ret, $this->getTargetForm()->prefix));
        }
                
        $this->setInputHidden('idMenu');
        
        $this->setInputText2('descricao', 'Descrição: ',['placeholder'=>'Entre com a descrição']);
        $this->setSimpleText('label');
        $this->setSimpleText('route');
        $this->setSimpleText('controller');
        $this->setSimpleText('action');
        $this->setSimpleText('atributos');
        $this->setSimpleText('icons');
        $this->setSimpleText('class');
        $this->setSimpleText('pagesContainerClass');
        $this->setSimpleText('wrapClass');
        $this->setSimpleText('resource');
        $this->setSimpleText('privilege');
        $this->setSimpleText('ordem');
        
        $selectOptionsParent = $this->getAllParents();
        $this->setInputSelect('inMenu', 'Dentro do menu: ', $selectOptionsParent,['placeholder'=>'Este menu pertence a ? ou não']);
    }
    
    public function getAllParents() {
        /* @var $repository \Application\Entity\Repository\AppMenuRepository */
        $repository = $this->em->getRepository($this->moduloName . "\Entity\AppMenu");
        return $repository->fetchParent();
    }
    
    public function setSimpleText($name) {
        $this->setInputText2($name, ucfirst($name). ': ',['placeholder'=>'Digite ' . $name]);
    }
}
