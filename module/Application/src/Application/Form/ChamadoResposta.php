<?php

namespace Application\Form;


/**
 * Description of ChamadoResposta
 *
 * @author Danilo Song  <danilosong@outlook.com>
 * @since 25/09/2017
 */
class ChamadoResposta extends AbstractForm{

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
            $this->setInputFilter(new Filter\ChamadoRespostaFilter($this->name, $this->ret, $this->getTargetForm()->prefix));
        }

        $this->setInputHidden('id');
        
        $selectSituacao = $this->getParametroChave("select_chamado_situacao", true);
        $this->setInputSelect('situacao', 'Situação', $selectSituacao);
        
        $this->setInputHidden('chamado');
        
        $this->setInputText('texto', 'Resposta:');
        
        $this->setInputText('anexoPath', 'Anexar Arquivo');
        
        $this->setInputText('copiaPara', 'Cópia Para:', ['Placeholder' => 'Digite o nome do usuário']);
        
        $selectStatus = $this->getParametroChave("status_tabela", FALSE);
        $this->setInputSelect('status', 'Status', $selectStatus,  ["value" => "ATIVO"]);
    }
}