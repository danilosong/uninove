<?php

/*
 * To change this license 
 */

namespace Application\Service;

use Doctrine\ORM\EntityManager;

/**
 * Description of Auth
 *
 * @author Paulo Watakabe <watakabe05@gmail.com>
 * @since 10-07-2016
 */
class Auth extends AbstractService{

    /**
     * Serviço basico para manipular entida e suas relações caso houver
     * Insert, Update e validação do registro.
     * Obs tem redirecionamento de atributos se um object for passado em $fatherService sera colocado em $controller e atribuido falso ao mesmo
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.2
     * @param EntityManager $em              Gerenciador ORM de dados do Doctrine
     * @param boolean       $fatherService   Opcional serve para padronizar o flush em caso de chamadas recursivas ou chamado por outro serviço
     */
    public function __construct(EntityManager $em, $fatherService = FALSE) {
        parent::__construct($em, $fatherService);
        
        $this->entity = $this->basePath . "Auth";        
        
    }
    
    public function reset($login) {
        $rpUs = $this->em->getRepository('\Application\Entity\Usuario');
        $usuario = $rpUs->findOneByNickname($login);
        $msg = [];
        /* @var $usuario \Application\Entity\Usuario */
        if($usuario){
            if(empty($usuario->getEmail())){
                $msg[] = 'Usuario sem email cadastrado !!!';
                $msg[] = 'Peça para o administrador ou gerente alterar sua senha!!!';
            }else{
                $this->sendEmailResetPass($usuario);
                $msg[] = 'Um email de redefinição de senha foi enviado para o email do usuario ' . $login;
                $msg[] = 'Verifique seu email e siga as instruções para redefinir sua senha!!!';
            }
        }else{
            $msg[] = 'Usuario com este login/email não encontrado!!!';
        }
        return [false, $msg];
    }

    public function sendEmailResetPass(\Application\Entity\Usuario $usuario) {
        // gerar um hash de controle para ser usado uma vez
        $usuario->setUpdatedAt();
        $usuario->setActivationKey(md5($usuario->getEmail() . $usuario->getUpdatedAt('Ymdhis')));
        $this->em->persist($usuario);
        $this->em->flush();
        
        $options["to"] = $usuario->getEmail();
        $options["subject"]  = "Alteração de senha solicitada";
        $options["data"] = [
            'usuario' => $usuario
        ];

        $senderEmail = $this->getController()->getEmail();
        $senderEmail->enviaEmail($options, 'reset-pass'); 

    }

}
