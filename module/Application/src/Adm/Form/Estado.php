<?php

/*
 * To change this license 
 */

namespace Adm\Form;

/**
 *  Description of Estado
 * @author Allan Davini
 */
class Estado extends AdmAbstractForm {

    private $pais;

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
            $fields[] = "uf";

            $this->pais->preFormInit($formParent);
        }
        $this->setArrayDisabled($fields);

        if (!empty($formParent)) {
            $this->getTargetForm()->removePreFix();
        }
    }

    public function setInputs($filter = TRUE) {
        if ($filter) {
            $this->setInputFilter(new Filter\EstadoFilter($this->name, $this->ret, $this->getTargetForm()->prefix));
        }

        $this->moduloName = "Tcmed";

        $this->setInputHidden('idEstado');

        $this->setSimpleText('nomeEstado');

        $this->setSimpleText('uf');

        if ($this->ret == FALSE) {
            $paiss = $this->getAllPairs();
            $this->setInputSelect('pais', 'Pais ', $paiss);

            $selectStatus = $this->getParametroChave("status_tabela");
            $this->setInputSelect('status', 'Status', $selectStatus);
        } else {
            $this->pais = new Pais($this->em, TRUE);
            $this->pais->getBaseForm($this->getTargetForm(), $filter);
        }
    }

    public function getAllPairs() {
        /* @var $repository \Application\Entity\Repository\PaisRepository */
        $repository = $this->em->getRepository($this->moduloName . "\Entity\Pais");
        return $repository->fetchPairs('getNomePais');
    }

    public function getAllEstados() {
        /* @var $repository \Tcmed\Entity\Repository\EstadoRepository */
        $repository = $this->em->getRepository($this->moduloName . "\Entity\Estado");
        return $repository->fetchPairs('getNomeEstado');
    }

}
