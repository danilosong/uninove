<?php

namespace Adm\Form;

/**
 * 
 * @author Danilo Song <danilosong@outlook.com>
 * @version 1.0  
 * @since 16-06-2017
 */
class Saida extends AdmAbstractForm{

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
            $this->setInputFilter(new Filter\SaidaFilter($this->name, $this->ret, $this->getTargetForm()->prefix));
        }

        $this->setInputHidden('id');
        
        $this->setInputHidden('usuario');
        
        $this->setInputHidden('produto');
        
        $this->setInputHidden('pedidoItem');
        
        $this->setInputText('usuarioNome', 'Usuario');
        
        $this->setInputText('produtoNome', 'Produto',             ["Placeholder" => "Digite o produto"]);
        
        $this->setInputText('qtd', 'Quantidade',                  ["Placeholder" => "Digite a quantidade"]);
        
        $this->setInputText('total', 'Total',                     ["Placeholder" => "Digite o total"]);
        
        $this->setInputText('createdAt', 'Data',                  ["value" => date('d/m/Y')]);
        
        $this->setInputText('obs', 'Observação',                  ["Placeholder" => "Digite a obeservação"]);
        
        $selectConj = $this->getParametroChave("conjunto_tabela");
        $this->setInputSelect('conjunto', 'Conjunto', $selectConj);
        
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
