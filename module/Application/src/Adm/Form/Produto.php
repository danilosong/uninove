<?php

namespace Adm\Form;

/**
 * 
 * @author Danilo Song <danilosong@outlook.com>
 * @version 1.0  
 * @since 14-06-2017
 */
class Produto extends AdmAbstractForm{

    /**
     * Define os inputs padrões para o form.phtml
     *
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14-06-2017
     * @param   boolean $filter Busca o filtro referente a esta entidade. Setar false caso não for necessario a aplicacao de filtros
     */
    public function setInputs($filter = TRUE) {
        if ($filter) {
            $this->setInputFilter(new Filter\ProdutoFilter($this->name, $this->ret, $this->getTargetForm()->prefix));
        }

        $this->setInputHidden('id');
        
        $this->setInputText('createdAt', 'Data de Cadastramento', ["value" => date('d/m/Y'), "readOnly" => "true"]);
        
        $this->setInputText('nomeProd', 'Nome do produto',        ["Placeholder" => "Digite o nome do produto"]);
        
        $this->setInputText('valorProd', 'Valor do produto',      ["Placeholder" => "Digite o valor do produto"]);
        
        $this->setInputText('compraQtd', 'Quantidade da compra',  ["Placeholder" => "Digite a quantidade da compra"]);
        
        $this->setInputText('saidaQtd', 'Quantidade de saida',    ["Placeholder" => "Digite a quantidade de saída"]);
        
        $this->setInputText('estoqueAtual', 'Estoque atual',      ["Placeholder" => "Digite a quantidade do estoque atual"]);
        
        $this->setInputText('estoqueMinimo', 'Estoque minimo',    ["Placeholder" => "Digite a quantidade do estoque minimo"]);
        
        $this->setInputText('estoqueMaximo', 'Estoque maximo',    ["Placeholder" => "Digite a quantidade do estoque maximo"]);
        
        $selectUnidade = $this->getParametroChave("unidade_tabela", FALSE);
        $this->setInputSelect('unidadeEntrada', 'Unidade de entrada', $selectUnidade);
        
        $selectUnidadeSaida = $this->getParametroChave("unidade_tabela", FALSE);
        $this->setInputSelect('unidadeSaida', 'Unidade de saida', $selectUnidadeSaida);
        
        $selectSetor = $this->getParametroChave("setor_tabela");
        $this->setInputSelect('setor', 'Setor', $selectSetor);
        
        $selectStatus = $this->getParametroChave("status_tabela", FALSE);
        $this->setInputSelect('status', 'Status', $selectStatus,  ["value" => "ATIVO"]);
        
        $this->setInputSubmit('submit', 'Salvar');
        
    }

}
