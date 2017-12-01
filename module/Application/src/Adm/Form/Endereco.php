<?php

/*
 * To change this license 
 */

namespace Adm\Form;

/**
 * Description of Endereco
 * @author Allan Davini
 */
class Endereco extends AdmAbstractForm {

    private $logradouro;

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
        if ("funcionario" === $formParent) {
            $fields = [
                "numero",
                "complemento",
            ];
            $this->logradouro->preFormInit($formParent);
        }
        $this->setArrayDisabled($fields);

        if (!empty($formParent)) {
            $this->getTargetForm()->removePreFix();
        }
    }

    public function setInputs($filter = TRUE) {
        if ($filter) {
            $this->setInputFilter(new Filter\EnderecoFilter($this->name, $this->ret, $this->getTargetForm()->prefix));
        }
        $this->setInputHidden('idEndereco');

        $this->setInputText('numero', 'Numero', ["Placeholder" => "Número"]);

        $this->setInputText('complemento', 'Complemento', ["Placeholder" => "Compl"]);
        
        $this->setInputText('fullEndereco', 'Endereço antigo:');

        $selectStatus = $this->getParametroChave("status_tabela");
        $this->setInputSelect('status', 'Status', $selectStatus);

        $this->logradouro = new Logradouro($this->em, TRUE);
        $this->logradouro->getBaseForm($this->getTargetForm(), $filter);
    }
    
    public function setInputsForBlocoItem($filter = TRUE) {
        $this->setInputHidden('idEndereco');
        $this->setInputHidden('numero');
        (new \Adm\Form\Logradouro($this->em, TRUE))->getBaseForm($this->getTargetForm(), TRUE, 'setInputsForBlocoItem');
    }

}
