<?php

namespace Adm\Form;

/**
 * 
 * @author Danilo Song <danilosong@outlook.com>
 * @version 1.0  
 * @since 16-06-2017
 */
class Pedido extends AdmAbstractForm{

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
            $this->setInputFilter(new Filter\PedidoFilter($this->name, $this->ret, $this->getTargetForm()->prefix));
        }

        $this->setInputHidden('id');
        
        $this->setInputHidden('usuario');
        
        $this->setInputHidden('fornecedor');
        
        $this->setInputText('usuarioNome', 'Nome');
        
        $this->setInputText('fornecedorNome', 'Nome do fornecedor', ['Placeholder' => 'Nome do fornecedor']);
        
        $this->setInputText('createdAt', 'Data do pedido',        ["value" => date('d/m/Y')]);
        
        $this->setInputText('dataEntrega', 'Data de entrega',     ["value" => date('d/m/Y')]);
        
        $this->setInputText('qtd', 'Quantidade',                  ["Placeholder" => "Digite a quantidade"]);
        
        $this->setInputText('frete', 'Frete',                     ["Placeholder" => "Digite o valor do frete"]);
        
        $this->setInputText('desconto', 'Desconto',               ["Placeholder" => "Digite o valor do desconto"]);
        
        $this->setInputText('total', 'Total',                     ["Placeholder" => "Digite o total"]);
        
        $this->setInputText('obs', 'Observação',                  ["Placeholder" => "Digite a obeservação"]);
        
        $this->setInputText('vendedor', 'Vendedor',               ["Placeholder" => "Digite o nome do vendedor"]);
        
        $selectStatus = $this->getParametroChave("status_pedido");
        $this->setInputSelect('status', 'Status', $selectStatus);
        
        $this->setInputText2('valor'      , 'Valor:');  
        
        $this->setInputSubmit('submit', 'Salvar');
        
    }
    
    public function setReadOnly() {
        $inputs = [
            'usuarioNome'
        ];
        foreach ($inputs as $input){
            $this->readOnly($input);
        }
    }
    
    public function readOnly($input) {
        if($this->has($input)){
            $this->get($input)->setAttribute('readOnly', true);
        }
    }

}
