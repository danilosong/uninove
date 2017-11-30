<?php

namespace Adm\Form;

/**
 * 
 * @author Danilo Song <danilosong@outlook.com>
 * @version 1.0  
 * @since 16-06-2017
 */
class PedidoItem extends AdmAbstractForm{

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
            $this->setInputFilter(new Filter\PedidoItemFilter($this->name, $this->ret, $this->getTargetForm()->prefix));
        }

        $this->setInputHidden('id');
        
        $this->setInputHidden('pedido');
        
        $this->setInputHidden('produto');
        
        $this->setInputText('createdAt', 'Data',                ['value' => date('d/m/Y')]);
        
        $this->setInputText('pedidoNumero', 'Pedido',           ["Placeholder" => "Digite o pedido"]);
        
        $this->setInputText('produtoNome', 'Produto',           ["Placeholder" => "Digite o nome do produto"]);
        
        $this->setInputText('qtd', 'Quantidade',                ["Placeholder" => "Digite a quantidade"]);
        
        $this->setInputText('valor', 'Valor',                   ["Placeholder" => "Digite o valor"]);
        
        $this->setInputText('total', 'Total',                   ["readOnly"    => "true"]);
        
        $this->setInputSubmit('submit', 'Salvar');
    }

}
