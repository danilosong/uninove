<?php

namespace Adm\Form;

/**
 * 
 * @author Danilo Song <danilosong@outlook.com>
 * @version 1.0  
 * @since 24-05-2017
 */
class Teste extends AdmAbstractForm{


    /**
     * Define os inputs padrões para o form.phtml
     *
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 23-05-2017
     * @param   boolean $filter Busca o filtro referente a esta entidade. Setar false caso não for necessario a aplicacao de filtros
     */
    public function setInputs($filter = TRUE) {
        if ($filter) {
            $this->setInputFilter(new Filter\TesteFilter($this->name, $this->ret, $this->getTargetForm()->prefix));
        }

        $this->setInputHidden('id');
        
        $this->setInputText('teste'           , 'Teste:');
        
        $selectStatus = $this->getParametroChave("status_tabela", FALSE);
        $this->setInputSelect('status', 'Status', $selectStatus, ["value" => "ATIVO"]);
        
        $this->setInputSubmit('submit', 'Salvar');
        
    }

}
