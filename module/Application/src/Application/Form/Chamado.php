<?php

namespace Application\Form;


/**
 * Description of Chamado
 *
 * @author Danilo Song  <danilosong@outlook.com>
 * @since 12/09/2017
 */
class Chamado extends AbstractForm{

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
            $this->setInputFilter(new Filter\ChamadoFilter($this->name, $this->ret, $this->getTargetForm()->prefix));
        }

        $this->setInputHidden('id');
        
        $this->setInputHidden('usuarioId');
        
        $this->setInputText('titulo', 'Título', ['Placeholder' => 'Título do chamado']);
        
        $this->setInputTextArea('desc', 'Descrição do chamado', ['Placeholder' => 'Descreva o chamado']);
        
        $this->setFileUpload('anexoPath', 'Anexar um arquivo');
        
        /**
         *  Formulário de Categoria da C.I
         *  Detalhe!! o primeiro campo do array de options deve estar vazio...
         */
        $this->setInputMultiCheckbox('categoria', '', [
                                                           'Erro'           =>  'Erro', 
                                                           'Sistema'        =>  'Sistema', 
                                                           'Processo'       =>  'Processo', 
                                                           'Melhoria'       =>  'Melhoria', 
                                                           'Comunicação'    =>  'Comunicação',
                                                           'Dúvida'         =>  'Dúvida', 
                                                           'Questionamento' =>  'Questionamento',
                                                           'Outros'         =>  'Outros',
                                                                       ]);
        
        $this->setInputText('copiaPara', 'Cópia Para:', ['Placeholder' => 'Digite o nome do usuário']);
        
        $selectPrioridade = $this->getParametroChave("select_chamado_prioridade");
        $this->setInputSelect('prioridade', 'Prioridade do chamado', $selectPrioridade);
        
        /**
         * @example padrao para construir nome para chave no parametro para itens do form
         */
        $selectSetor = $this->getParametroChave("select_chamado_setor");
        $this->setInputSelect('setor', 'Setor', $selectSetor);
        
        $selectStatus = $this->getParametroChave("status_tabela", false);
        $this->setInputSelect('status', 'Status', $selectStatus);
    }
}
