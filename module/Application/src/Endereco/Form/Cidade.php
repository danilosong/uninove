<?php

/*
 * To change this license 
 */

namespace Endereco\Form;

/**
 * Description of Cidade
 *
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
class Cidade extends EnderecoAbstractForm{
    
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
            $this->setInputFilter(new Filter\CidadeFilter($this->name, $this->ret, $this->getTargetForm()->prefix));
        }
        
        $this->setSimpleText('cidadeCodigo');
        
        $this->setSimpleText('ufCodigo');
        
        $this->setSimpleText('cidadeDescricao');
        
        $this->setSimpleText('cidadeCep');
        
        $csrf = new \Zend\Form\Element\Csrf('security');
        $this->add($csrf);
        
        $this->setInputSubmit('submit', 'Salvar');
        
    }
    
}
