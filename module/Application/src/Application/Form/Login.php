<?php

namespace Application\Form;


class Login  extends AbstractForm
{

    /**
     * Define os inputs padrões para o form.phtml
     *
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 12-09-2017
     * @param   boolean $filter Busca o filtro referente a esta entidade. Setar false caso não for necessario a aplicacao de filtros
     */
    public function setInputs($filter = TRUE) {
        
        $this->setInputHidden('subOpcao',['value' => 'logon']);
        
        $this->setDefaultAttributes([]);

        $this->setInputText2('email', 'Login/Email:',['placeholder'=>'Entre com o Email']);
        
        $this->setInputText2('password', 'Senha:',['placeholder'=>'Entre com a senha']);
        
        $this->setInputCheckbox('remember', 'Lembrar:', ['checked_value' => 'remember','unchecked_value' => 'noremember']);

        $this->setInputButton('proximo', 'Próximo ', ['class'=> 'btn btn-primary']);
        
        $this->setInputButton('voltar', 'Alterar Usuário', ['class'=> 'btn btn-warning']);
        
        $this->setInputSubmit('submit', 'Entrar no Sistema ',['onClick' => 'return isValid(this);']);
       
    }
    
}
