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
class User extends AbstractForm{
    
//    
//    public function __construct($name = '', $options = array(), $ret = false) {
//        $this->redirectParams($name, $options, $ret);
//        parent::__construct('user', $options);
//        if($this->ret == FALSE){
//            $this->setAllInputs();
//        }
//    } 
    
    public function setInputs($filter=TRUE) {
//        if($filter){
//            $this->setInputFilter(new Filter\UserFilter($this->name, $this->ret, $this->getTargetForm()->prefix));
//        }  
        
        $this->setInputHidden('idUser');
        
        $this->setInputText2('usuarioId', 'ID do usuario: ',['placeholder'=>'Valor numerico']);
        
        $this->setInputText2('nome', 'Nome: ',['placeholder'=>'Entre com o nome visivel no chat']);
        
        $selectOptionsTipo = ["online" => "Online","busy" => "Ocupado","offline" => "Ausente",];
        $this->setInputSelect('statusChat', 'Status no chat: ', $selectOptionsTipo,['placeholder'=>'Se é Online, Off, ausente e etc...']);
        
        $this->setInputText2('statusMsg', 'Mensagem de Status: ',['placeholder'=>'Digite aqui']);       
        
        $selectOptions = ["A" => "Ativo","C" => "Cancelado"];
        $this->setInputSelect('status', 'Status: ', $selectOptions);  
        
        $optUser = $this->getAllPairsForUser();
        $this->setInputMultiCheckbox('contatos', 'Contatos Chat', $optUser);
        
    }
    
    /**
     * Pegar todos os usuario cadastrados e gera um array com id e nome
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @return array array com id e nome
     */
    public function getAllPairsForUser() {
        /* @var $repository \Application\Entity\Repository\UserRepository */
        $repository = $this->em->getRepository("\Application\Entity\User");
        return $repository->fetchPairs('getNome',FALSE);
    }
    
}
