<?php

namespace Adm\Form;

/**
 * 
 * @author Danilo Song <danilosong@outlook.com>
 * @version 1.0  
 * @since 24-05-2017
 */
class Colaborador extends AdmAbstractForm{

    /**
     * Define os inputs padrões para o form.phtml
     *
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 25-05-2017
     * @param   boolean $filter Busca o filtro referente a esta entidade. Setar false caso não for necessario a aplicacao de filtros
     */
    public function setInputs($filter = TRUE) {
        if ($filter) {
            $this->setInputFilter(new Filter\ColaboradorFilter($this->name, $this->ret, $this->getTargetForm()->prefix));
        }

        $this->setInputHidden('id');
        
        $this->setInputHidden('usuario');
        
        $this->setInputText('usuarioNome', 'Usuário',       ["Placeholder" => "Selecione o usuário"]);
        
        $this->setInputText('horIni', 'Horas do início',    ["Placeholder" => "00:00"]);
        
        $this->setInputText('horFim', 'Horas do término',   ["Placeholder" => "00:00"]);
        
        $this->setInputText('perIni', 'Período de início',  ["Placeholder" => "dd/mm/aaaa"]);

        $this->setInputText('perFim', 'Período de término', ["Placeholder" => "dd/mm/aaaa"]);
        
        $this->setInputText('almoco', 'Almoço',             ["Placeholder" => "Tempo de almoço(Min)"]);
        
        $selectStatus = $this->getParametroChave("status_tabela", FALSE);
        $this->setInputSelect('status', 'Status', $selectStatus,  ["value" => "ATIVO"]);
        
        $this->setInputSubmit('submit', 'Salvar');
        
    }

}
