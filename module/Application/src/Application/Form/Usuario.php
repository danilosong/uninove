<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Form;

/**
 * Description of User
 *
 * @author Paulo Watakabe
 */
class Usuario extends AbstractForm{
    
    
//    public function __construct($name = '', $options = array(), $ret = false) {
//        $this->redirectParams($name, $options, $ret);
//        parent::__construct('usuario', $options);
//        if($this->ret == FALSE){
//            $filter = isset($options['filter'])? $options['filter']: true;
//            $this->setAllInputs($filter);
//        }
//    } 
    
    public function setInputs($filter=TRUE) {
        if($filter){
            $action = (is_object($this->controller)) ? $this->controller->params('action') : 'new';
            $this->setInputFilter(new Filter\UsuarioFilter($this->name, $this->ret, $this->getTargetForm()->prefix, 'getFilters', $action));
        }    
        
        $this->setInputHidden('idUsuario');
        
        $this->setInputHidden('pathFoto');
        
        //$this->setInputHidden('activationKey'); // 03/12/2017 campo apagando a geração automatica na entity usuario
        
        $this->setFileUpload('uploadFoto', 'Foto do perfil');
        
        $this->setInputText2('nomeUsuario', 'Nome: ',['placeholder'=>'Entre com o nome']);
        
        $this->setInputText2('nickname', 'Login: ',['placeholder'=>'Entre com o Login']);
        
        $this->setInputText2('senhaUsuario', 'Password: ',['placeholder'=>'Entre com o senha']);
        
        $this->setInputText2('confirmation', 'Redigite: ',['placeholder'=>'Redigite a senha']);
        
        $this->setInputText2('emailUsuario', 'Email: ',['placeholder'=>'Entre com o email']);
        
        $selectOptions = ["A" => "Ativo","C" => "Cancelado"];
        $this->setInputSelect('situacao', 'Status: ', $selectOptions);
        
        $this->setInputText('lembreteSenha', 'Lembrete: ',['placeholder'=>'Entre com o lembrete para senha']);
        
        $selectOptionsTipo = $this->getParametroChave("usuario_tipo_options");
        $this->setInputSelect('tipo', 'Tipo de Usuario: ', $selectOptionsTipo,['placeholder'=>'Se é Admin, Guest, recepção e etc...']);
        
        $selectOptionsRole = $this->getAllRoles();
        $this->setInputSelect('role', 'Papel de : ', $selectOptionsRole,['placeholder'=>'Papel desse usuario no sistema']);
        
        $this->setInputText2('referencia', 'Referencia : ',['placeholder'=>'Matricula ou identificação do sistema anterior']);
        
    }
    
    /**
     * Seta inputs de forma mais básica, utilizada em alguns forms
     * deste projeto
     * 
     * @author Allan Davini <allan_mv19@hotmail.com>
     * @since 04/04/2016
     * @param boolean $filter
     */
    public function setSimpleInputsForUsuarioParams($filter = TRUE) {
        if ($filter) {
            $this->setInputFilter(new Filter\UsuarioFilter($this->name, $this->ret, $this->getTargetForm()->prefix));
        }

        $this->setInputHidden('idUsuario');
        $this->setInputText('nomeUsuario', "Usuario", ["Placeholder" => "Digite o Nome do Usuario"]);
    }
    
    public function getAllRoles() {
        /* @var $repository \Application\Entity\Repository\AppRoleRepository */
        $repository = $this->em->getRepository($this->moduloName . "\Entity\AppRole");
        return $repository->fetchPairs('getNome');
    }
    
}
