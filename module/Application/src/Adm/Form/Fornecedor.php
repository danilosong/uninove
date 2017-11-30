<?php

namespace Adm\Form;

/**
 * 
 * @author Danilo Song <danilosong@outlook.com>
 * @version 1.0  
 * @since 16-06-2017
 */
class Fornecedor extends AdmAbstractForm{

    /**
     * Define os inputs padrões para o form.phtml
     *
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 16-06-2017
     * @param   boolean $filter Busca o filtro referente a esta entidade. Setar false caso não for necessario a aplicacao de filtros
     */
    public function setInputs($filter = TRUE) {
        if ($filter) {
            $this->setInputFilter(new Filter\FornecedorFilter($this->name, $this->ret, $this->getTargetForm()->prefix));
        }

        $this->setInputHidden('id');
        
        $this->setInputText('nomeFornec', 'Nome do fornecedor',       ["Placeholder" => "Digite o nome do fornecedor"]);
        
        $this->setInputText('nomeFantasia', 'Nome fantasia',          ["Placeholder" => "Digite o nome fantasia"]);
        
        $this->setInputText('homePage', 'Home Page',                  ["Placeholder" => "Digite o home page"]);
        
        $this->setInputText('contato', 'Contato',                     ["Placeholder" => "Digite o contato"]);
        
        $this->setInputText('email', 'Email',                         ["Placeholder" => "Digite o email"]);
        
        $this->setInputText('cnpj', 'Cnpj',                           ["Placeholder" => "Digite o cnpj"]);
        
        $this->setInputText('inscricaoEstadual', 'Inscrição Estadual',["Placeholder" => "Digite a incrição estadual"]);
        
        $this->setInputText('telefone', 'Telefone',                   ["Placeholder" => "Digite o DDD+Telefone"]);
        
        $this->setInputText('celular', 'Celular',                     ["Placeholder" => "Digite o DDD+Celular"]);
        
        $selectSetor = $this->getParametroChave("setor_tabela", FALSE);
        $this->setInputSelect('setor', 'Setor', $selectSetor);
        
        $selectStatus = $this->getParametroChave("status_tabela", FALSE);
        $this->setInputSelect('status', 'Status', $selectStatus,  ["value" => "ATIVO"]);
        
        $this->endereco = new \Adm\Form\Endereco($this->em, TRUE);
        $this->endereco->getBaseForm($this->getTargetForm(), FALSE);
        
    }

}
