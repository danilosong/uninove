<?php

/*
 * To change this license 
 */

namespace Adm\Form;

/**
 * Description of Pais
 * @author Danilo Dorotheu <danilo.dorotheu@live.com>
 */
class Pais extends AdmAbstractForm {

    /**
     * Executa operacoes após form ser carregado
     * 
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @author Paulo Watakabe <paulo.watakabe@tcmed.com.br>
     * @param string $formParent Recebe o nome do formulário que está carregando
     * este form
     * @since 08/01/2016
     */
    public function preFormInit($formParent = "") {
        if (!empty($formParent)) {
            $this->getTargetForm()->setPreFix($this->name);
        }
        
        $fields = [];
        if ("funcionario" === $formParent) {
            $fields[] = "nomePais";
        }
        $this->setArrayDisabled($fields);

        if (!empty($formParent)) {
            $this->getTargetForm()->removePreFix();
        }
    }

    /**
     * 
     * @param type $filter Busca o filtro referente a esta entidade. Setar false
     * caso não for necessario a aplicacao de filtros
     */
    public function setInputs($filter = TRUE) {
        if ($filter) {
            $this->setInputFilter(new Filter\PaisFilter($this->name, $this->ret, $this->getTargetForm()->prefix));
        }
        $this->setInputHidden('idPais');

        $this->setInputText('nomePais', 'Nome do País', ["Placeholder" => "Digite o Nome do País"]);
        $this->setInputText('sigla', 'Sigla', ["Placeholder" => "Digite a Sigla do País"]);
        $this->setInputText('codigo', 'Código', ["Placeholder" => "Digite o Código do País"]);


        $selectStatus = $this->getParametroChave("status_tabela");
        $this->setInputSelect('status', 'Status', $selectStatus);
    }

}
