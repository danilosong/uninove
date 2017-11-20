<?php

/*
 * To change this license 
 */

namespace Application\Form;

/**
 * Description of Enviado
 *
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
class Enviado extends AbstractForm{
   
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 05-11-2017
     * @param boolean $filter Por padrão(TRUE) carrega os filtros do form
     */
    public function setInputs($filter = TRUE) {
//        if ($filter) {
//            $this->setInputFilter(new Filter\EnviadoFilter($this->name, $this->ret, $this->getTargetForm()->prefix));
//        }        
                
        $this->setInputHidden('idEnviado');

        $this->setSimpleText('dateEnviado');
        
        $this->setSimpleText('dateRecebido');
        
        $selectOptionsUser = $this->getAllUsers();
        $this->setInputSelect('fromUser', 'Para o usuario: ', $selectOptionsUser,['placeholder'=>'Para o usuario']);
        
        $this->setInputSelect('toUser', 'Do usuario : ', $selectOptionsUser,['placeholder'=>'de quem']);
        
        $selectOptionsMensagem = $this->getAllMensagem();
        $this->setInputSelect('mensagemMensagem', 'Mensagem: ', $selectOptionsMensagem,['placeholder'=>'Mensagem a ser enviada']);
        
    }  
    
    public function getAllUsers() {
        /* @var $repository \Application\Entity\Repository\UserRepository */
        $repository = $this->em->getRepository($this->moduloName . "\Entity\User");
        return $repository->fetchPairs();
    }
    
    public function getAllMensagem() {
        /* @var $repository \Application\Entity\Repository\MensagemRepository */
        $repository = $this->em->getRepository($this->moduloName . "\Entity\Mensagem");
        return $repository->fetchPairs();
    }
}
