<?php

namespace Adm\Form;

/**
 * Description of Logradouro
 * @author Danilo Dorotheu <danilo.dorotheu@live.com>
 */
class Logradouro extends AdmAbstractForm {

    private $bairro;
    private $tipoLogradouro;

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
        if("funcionario" === $formParent){
            $fields = [
                "nomeLogradouro",
                "cep",
            ];

            $this->bairro->preFormInit($formParent);
            $this->tipoLogradouro->preFormInit($formParent);
        }
        $this->setArrayDisabled($fields);
        
        if (!empty($formParent)) {
            $this->getTargetForm()->removePreFix();
        }
    }

    public function setInputs($filter = TRUE) {
        if ($filter) {
            $this->setInputFilter(new Filter\LogradouroFilter($this->name, $this->ret, $this->getTargetForm()->prefix));
        }

        $this->setInputHidden('idLogradouro');

        $this->setInputText('nomeLogradouro', 'Nome do Logradouro', ["Placeholder" => "Nome do Logradouro"]);
        $this->setInputText('cep', 'CEP', ["Placeholder" => "CEP"]);

        if ($this->ret == FALSE) {
            $selectStatus = $this->getParametroChave("status_tabela");
            $this->setInputSelect('status', 'Status', $selectStatus);

            $bairros = $this->getAllBairros();
            $this->setInputSelect('bairro', 'Bairro ', $bairros);

            $tipoLogradouros = $this->getAllTipoLogradouros();
            $this->setInputSelect('tipoLogradouro', 'Tipo Logradouro ', $tipoLogradouros);
        } else {
            $this->bairro = new Bairro($this->em, TRUE);
            $this->bairro->getBaseForm($this->getTargetForm(), $filter);

            $this->tipoLogradouro = new TipoLogradouro($this->em, TRUE);
            $this->tipoLogradouro->getBaseForm($this->getTargetForm(), $filter);
        }
    }
    
    /**
     * 
     * @param boolean $filter
     */
    public function setInputsForBlocoItem($filter = TRUE) {
        $this->setInputHidden('idLogradouro');
        $this->setInputHidden('nomeLogradouro');
        $this->setInputHidden('cep');
    }

    public function getAllBairros() {
        /* @var $repository \Tcmed\Entity\Repository\BairroRepository */
        $repository = $this->em->getRepository($this->moduloName . "\Entity\Bairro");
        return $repository->fetchPairs('getNomeBairro');
    }

    public function getAlltipoLogradouros() {
        /* @var $repository \Tcmed\Entity\Repository\TipoLogradouroRepository */
        $repository = $this->em->getRepository($this->moduloName . "\Entity\TipoLogradouro");
        return $repository->fetchPairs('getTipo');
    }

}
