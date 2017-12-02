<?php

/*
 * To change this license 
 */

namespace Endereco\Form;

/**
 * Description of Bairro
 *
 * @author Danilo Dorotheu
 */
class Bairro extends EnderecoAbstractForm {
        
    /**
     * Define os inputs padrões para o form.phtml
     *
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 02-12-2017
     * @param   boolean $filter Busca o filtro referente a esta entidade. Setar false caso não for necessario a aplicacao de filtros
     */
    public function setInputs($filter = TRUE) {
        if ($filter) {
            $this->setInputFilter(new Filter\BairroFilter($this->name, $this->ret, $this->getTargetForm()->prefix));
        }
        
        $this->setSimpleText('bairroCodigo');
        
        $this->setSimpleText('cidadeCodigo');
        
        $this->setSimpleText('bairroDescricao');
        
        $csrf = new \Zend\Form\Element\Csrf('security');
        $this->add($csrf);
        
        $this->setInputSubmit('submit', 'Salvar');
        
    }
    
}
