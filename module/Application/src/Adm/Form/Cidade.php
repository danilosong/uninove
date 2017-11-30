<?php

namespace Adm\Form;

/**
 * Description of Cidade
 * @author Danilo Dorotheu <danilo.dorotheu@live.com>
 */
class Cidade extends AdmAbstractForm {

    private $estado;

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
            $fields[] = "nomeCidade";

            $this->estado->preFormInit($formParent);
        }
        $this->setArrayDisabled($fields);
        
        if (!empty($formParent)) {
            $this->getTargetForm()->removePreFix();
        }
    }

    public function setInputs($filter = TRUE) {
        if ($filter) {
            $this->setInputFilter(new Filter\CidadeFilter($this->name, $this->ret, $this->getTargetForm()->prefix));
        }

        $this->setInputHidden('idCidade');

        $this->setInputText('nomeCidade', 'Nome da Cidade', ["Placeholder" => "Nome da Cidade"]);

        $selectStatus = $this->getParametroChave("status_tabela");
        $this->setInputSelect('status', 'Status', $selectStatus);

        $selectOptionsEstados = $this->getAllEstados();
        $this->setInputSelect('estado', 'Estado ', $selectOptionsEstados);

        $this->estado = new Estado($this->em, TRUE);
        $this->estado->getBaseForm($this->getTargetForm(), $filter);
    }

    public function getAllEstados() {
        /* @var $repository \Tcmed\Entity\Repository\EstadoRepository */
        $repository = $this->em->getRepository($this->moduloName . "\Entity\Estado");
        return $repository->fetchPairs('getNomeEstado');
    }

}
