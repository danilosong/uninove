<?php

/*
 * To change this license 
 */

namespace Application\Service;

use Doctrine\ORM\EntityManager;

/**
 * Description of Messenger
 *
 * @author Paulo Watakabe
 */
class Messenger extends AbstractService {

    public function __construct(EntityManager $em) {
        parent::__construct($em);
    }

    /**
     * Envia a mensagem para o(s) usuario(s do grupo)
     * 
     * @since 14-03-2016
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @param type $dataRequest
     */
    public function enviarMensagem($dataRequest) {
        $meuUsuario = $$this->em->getRepository("\Application\Entity\User")->findOneBy(['usuarioId' => $this->getUser('id')]);
        $this->setFlush(FALSE);
        $serviceEnviado = new Enviado($this->em, $this);
        $mensagem = (new Mensagem($this->em, $this))->insert(["texto" => $dataRequest["mensagem"]]);

        $result;
        if ("grupo" == $dataRequest["tipo"]) {
            $grupo = $this->em->find($this->basePath . "Grupo", $dataRequest["toUser"]);
            $usuarios = $grupo->listarUsers();

            foreach ($usuarios as $usuario) {
                $enviado["mensagemMensagem"] = $mensagem;
                $enviado["fromUser"] = $meuUsuario;
                $enviado["toUser"] = $usuario;
                $enviado["dateEnviado"] = new \DateTime("now");
                $enviado["toGrupo"] = $grupo;
                $result = $serviceEnviado->insert($enviado);
            }
        } else {
            $contato = $this->em->getReference($this->basePath . "User", $dataRequest["toUser"]);

            $enviado = $this->createMensagem($dataRequest);
            $enviado["mensagemMensagem"] = $mensagem;
            $enviado["fromUser"] = $meuUsuario;
            $enviado["dateEnviado"] = new \DateTime("now");
            $enviado["toUser"] = $contato;
            $result = $serviceEnviado->insert($enviado);
        }

        $this->em->flush();
        return $result;
    }

    /**
     * Retorna o usuário da sessão e atualiza o status deste para online
     */
    public function getMeuUsuario() {
        /* @var $meuUsuario \Application\Entity\User */
        $meuUsuario = $this->em->getRepository("\Application\Entity\User")->findOneBy(['usuarioId' => $this->getUser('id')]);
        if(is_null($meuUsuario)){
            $msg = 'Não foi encontrado um user de chat cadastrado para o usuario ' . $this->getUser('id') . $this->getUser('nome');
            throw new \Exception($msg);
        }
        $meuUsuario->setStatusChat("online");
        $meuUsuario->setStatusDatetime("");
        $this->em->persist($meuUsuario);
        $this->em->flush();

        return $meuUsuario;
    }

    /**
     * Retorna a lista de mensagens do contato 
     * 
     * @param int $meuId
     * @param type $idContato
     */
    public function getMensagens() {
        $entUser = $this->em->getRepository("\Application\Entity\User")->findOneBy(['usuarioId' => $this->getUser('id')]);
        if(is_null($entUser)){
            return [];
        }
        $repository = $this->em->getRepository("Application\Entity\Enviado");
        /* @var $messages \Application\Entity\Enviado */
        $messages = $repository->findBy([
            "toUser" => $entUser->getId(),
            "dateRecebido" => NULL
        ]);


        /* @var $messages \Application\Entity\Enviado */
        foreach ($messages as $message) {
            $message->setDateRecebido(new \DateTime("now"));
        }
        $this->em->flush();

        return $messages;
    }

    /**
     * Registra a Mensagem a ser enviada e 
     * registra na tabela enviados para quem deve receber
     * @param Array $data
     */
    public function sendMensagem($data) {
        /* @var $user \Application\Entity\User */
        $userRepository = $this->em->getRepository($this->basePath . "User");
        $user = $userRepository->findOneByIdUser(str_replace('us', '', $data['userby']));

        if (0 === strpos($data['userto'], 'us')) {
            $userTo = $userRepository->findOneByIdUser(str_replace('us', '', $data['userto']));
        } else {
            /* @var $grupoTo \Application\Entity\Grupo */
            $grupoRepository = $this->em->getRepository($this->basePath . "Grupo");
            $grupoTo = $grupoRepository->findOneByIdGrupo(str_replace('gr', '', $data['userto']));
            $userTo = FALSE;
            if (!$grupoTo) {
                echo 'erro 3 ';
                return;
            }
        }

        $serviceMensagem = new Mensagem($this->em);
        $entityMensagem = $serviceMensagem->insert(['texto' => $data['msg']]);

        $serviceEnviado = new Enviado($this->em);
        $dataEnvio['mensagemMensagem'] = $entityMensagem;
        $dataEnvio['fromUser'] = $user;
        $dataEnvio['dateEnviado'] = new \DateTime("now");

        if (!$userTo) {
            $dataEnvio['toGrupo'] = $grupoTo;
            $ContatoRepository = $this->em->getRepository($this->basePath . "Contato");
            $contatosGrupo = $ContatoRepository->findByGrupoGrupo($grupoTo->getId());
            /* @var $contatoGrupo \Application\Entity\Contato */
            foreach ($contatosGrupo as $contatoGrupo) {
                if ($contatoGrupo->getContatoUser()->getId() == $user->getId()) {
                    continue;
                }
                $dataEnvio['toUser'] = $contatoGrupo->getContatoUser();
                $serviceEnviado->insert($dataEnvio);
            }
        } else {
            $dataEnvio['toUser'] = $userTo;
            $serviceEnviado->insert($dataEnvio);
        }
    }

    /**
     * Busca o user do chat que esta ligado ao usuario do sistema
     * @param string $meUser
     * @return \Application\Entity\User
     */
    public function whoIam($meUser) {
        return $this->em->getRepository("\Application\Entity\User")->findOneBy(['usuarioId' => $this->getUser('id')]);
    }

    /**
     * Retorna a lista de contatos e Grupos
     * 
     * @param \Application\Entity\User $user
     * @return type
     */
    public function getContatos() {
        /* @var $user \Application\Entity\User */
        $user = $this->em->getRepository("\Application\Entity\User")->findOneBy(['usuarioId' => $this->getUser('id')]);
        return [
            "Users" => $user->listarMyContatos(),
            "Grupos" => $user->listarGrupos()
        ];
    }

    /**
     * Busca no enviado as mensagens enviadas e recebidas em um determinado periodo
     * Baseado no filtros passado
     * 
     * @param type $dataPost
     * @return array of \Application\Entity\Enviado
     */
    public function getHistory($dataPost) {
        /* @var $repositoryEnviado \Application\Entity\Repository\EnviadoRepository */
        $repositoryEnviado = $this->em->getRepository($this->basePath . "Enviado");

        /* @var $date \DateTime */
        $date = new \DateTime('now');
        switch ($dataPost['periodo']) {
            case 'semana':
                $date->sub(new \DateInterval('P7D'));
                break;
            case 'mes':
                $date->sub(new \DateInterval('P1M'));
                break;
            default:
                $date->sub(new \DateInterval('P1D'));
                $date->setTime(23, 59, 59);
        }
        
        /* @var $user \Application\Entity\User */
        $user = $this->em->getRepository("\Application\Entity\User")->findOneBy(['usuarioId' => $this->getUser('id')]);        

        return $repositoryEnviado->getEnviados([
            'tipoContato' => $dataPost['tipoContato'],
            'dateEnviado' => $date,
            'idUser' => $user->getId(),
            'from' => $dataPost['idContato']
        ]);
    }

    /**
     * Busca no contato os contatos que atualizaram seus status recentemente
     * Baseado no filtros passado
     * @param array $dataPost
     * @return array of \Application\Entity\Contato
     */
    public function getUpgradedStatusUser($dataPost) {
        /* @var $repositoryUser \Application\Entity\Repository\UserRepository */
        $repositoryUser = $this->em->getRepository($this->basePath . "User");
        $repositoryUser->upgradeMeIsOnline(str_replace('us', '', $dataPost['userId']));
        /* @var $repositoryContato \Application\Entity\Repository\ContatoRepository */
        $repositoryContato = $this->em->getRepository($this->basePath . "Contato");

        $where = 'c.userUser = :userUser and co.statusDatetime > :statusDatetime';
        $parameters['userUser'] = str_replace('us', '', $dataPost['userId']);
        $parameters['statusDatetime'] = new \DateTime('now');
        $parameters['statusDatetime']->sub(new \DateInterval('PT2M'));

        return $repositoryContato->getUpgradedStatusUser($where, $parameters);
    }

    /**
     * Altera no Bd o user que alterou seu status no chat 
     * @param array $dataPost
     * @return boolean (true caso altere com sucesso)
     */
    public function getChangeStatusUser($dataPost) {
        /* @var $user \Application\Entity\User */
        $user = $this->em->getRepository("\Application\Entity\User")->findOneBy(['usuarioId' => $this->getUser('id')]);
        if($user){
            $user->setStatusDatetime("");
            $user->setAccessDatetime("");
            $user->setStatusChat($dataPost["status"]);
            $this->em->persist($user);
            $this->em->flush();
        }
        return $user;
    }

    /**
     * Altera no Bd o user que alterou sua mensagem de status no chat 
     * @param array $dataPost
     * @return boolean (true caso altere com sucesso)
     */
    public function ChangeStatusMsgUser($dataPost) {
        $serviceUser = new User($this->em);
        $data['idUser'] = str_replace('us', '', $dataPost['userId']);
        $data['statusMsg'] = $dataPost['statusMsg'];
        $data['statusDatetime'] = new \DateTime('now');
        $entityUser = $serviceUser->update($data);
        if ($entityUser) {
            return TRUE;
        }
        return FALSE;
    }

}
