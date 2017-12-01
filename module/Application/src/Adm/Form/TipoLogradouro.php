<?php

namespace Adm\Form;

/**
 * Description of TipoLogradouro
 * @author Allan Davini
 */
class TipoLogradouro extends AdmAbstractForm{

    /**
     * - Executa operacoes após form ser carregado
     * - Desabilita campos do form
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @author Paulo Watakabe <paulo.watakabe@tcmed.com.br>
     * @since 08/01/2015
     */
    public function preFormInit($formParent = "") {
        if (!empty($formParent)) {
            $this->getTargetForm()->setPreFix($this->name);
        }

        $fields = [];
        if("funcionario" === $formParent){
            $fields[] = "tipo";
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
            $this->setInputFilter(new Filter\TipoLogradouroFilter($this->name, $this->ret, $this->getTargetForm()->prefix));
        }

        $this->setInputHidden('idTipoLogradouro');

        $this->setInputText('tipo', 'Tipo de Logradouro', ["Placeholder" => "Tipo"]);
        $this->setInputText('sigla', 'Sigla', ["Placeholder" => "Sigla"]);

        if ($this->ret == FALSE) {
            $selectStatus = $this->getParametroChave("status_tabela");
            $this->setInputSelect('status', 'Status', $selectStatus);
        }
    }

}
