<?php

/*
 * To change this license 
 */

namespace Adm\Form;

/**
 * Description of Bairro
 * @author Allan Davini
 */
class Bairro extends AdmAbstractForm {

    private $cidade;

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
            $fields[] = "nomeBairro";
            
            $this->cidade->preFormInit($formParent);
        }
        $this->setArrayDisabled($fields);

        if (!empty($formParent)) {
            $this->getTargetForm()->removePreFix();
        }
    }

    public function setInputs($filter = TRUE) {
        if ($filter) {
            $this->setInputFilter(new Filter\BairroFilter($this->name, $this->ret, $this->getTargetForm()->prefix));
        }

        $this->setInputHidden('idBairro');

        $this->setInputText2('nomeBairro', 'Nome do Bairro', ["Placeholder" => "Nome do Bairro"]);

        if ($this->ret == FALSE) {
            $selectStatus = $this->getParametroChave("status_tabela");
            $this->setInputSelect('status', 'Status', $selectStatus);

            $cidades = $this->getAllPairs();
            $this->setInputSelect('cidade', 'Cidade ', $cidades);
        } else {
            $this->cidade = new Cidade($this->em, TRUE);
            $this->cidade->getBaseForm($this->getTargetForm(), $filter);
        }
    }

    public function getAllPairs() {
        /* @var $repository \Application\Entity\Repository\AppMenuRepository */
        $repository = $this->em->getRepository($this->moduloName . "\Entity\Cidade");
        return $repository->fetchPairs('getNomeCidade');
    }

}
