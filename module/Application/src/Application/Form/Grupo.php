<?php

/*
 * License
 */

namespace Application\Form;

/**
 * Description of Grupo
 *
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
class Grupo extends AbstractForm{
      
    public function setInputs($filter=TRUE) {
        if($filter){
            $this->setInputFilter(new Filter\GrupoFilter($this->name, $this->ret, $this->getTargetForm()->prefix));
        }      
        
        $this->setInputHidden('idGrupo');
        
        $this->setInputText2('nome', 'Nome: ',['placeholder'=>'Entre com o nome visivel no chat']);
        
        $selectOptionsTipo = ["online" => "onLine","offline" => "offLine","ausente" => "Ausente","busy" => "Ocupado"];
        $this->setInputSelect('statusChat', 'Status no chat: ', $selectOptionsTipo,['placeholder'=>'Se é Online, Off, ausente e etc...']);
        
        $this->setInputText2('statusMsg', 'Mensagem de Status: ',['placeholder'=>'Digite aqui']);       
        
        $selectOptions = ["A" => "Ativo","C" => "Cancelado"];
        $this->setInputSelect('status', 'Status: ', $selectOptions);  
        
        $optUser = $this->getAllPairsForUser();
        $this->setInputMultiCheckbox('contatos', 'Pertence a este Grupo', $optUser);
        
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
