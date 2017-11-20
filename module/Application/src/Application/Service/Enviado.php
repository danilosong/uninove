<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Service;

use Doctrine\ORM\EntityManager;
/**
 * Description of Enviado
 *
 * @author Paulo Watakabe
 */
class Enviado extends AbstractService {

    /**
     * Parametrizar servico de enviado
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 08-03-2016 
     * @param EntityManager $em   Manipulador do Bd do Doctrine
     * @param type $fatherService Opcional caso for um servico filho para ele se parametrizar baseado no pai
     */
    public function __construct(EntityManager $em, $fatherService = FALSE) {
        parent::__construct($em, $fatherService);

        $this->id = 'idEnviado';
        $this->entity = $this->basePath . "Enviado";

        $this->setDataRefArray([
            'toUser' => $this->basePath . 'User',
            'fromUser' => $this->basePath . 'User',
            'mensagemMensagem' => $this->basePath . 'Mensagem',
        ]);
    }

    /**
     * Cria um registro de enviado utilizando a mensagem, o usuário de origem e 
     * o usuário destino
     * 
     * @param \Application\Entity\Mensagem $mensagem
     * @param \Application\Entity\User $from
     * @param array $to
     */
    public function send($mensagem, $from, $to = []) {
        if(!is_array($to)){
            $to = array($to);
        }
        
        $makeFlush = FALSE;
        $this->setFlush(FALSE);
        
        foreach ($to as $user){
            $this->insert(array(
                "fromUser" => $from,
                "toUser" => $user,
                "mensagemMensagem" => $mensagem,
                "dateEnviado" => new \DateTime("now")
            ));
            $makeFlush = TRUE;
        }
        $makeFlush and $this->em->flush();
    }

}
