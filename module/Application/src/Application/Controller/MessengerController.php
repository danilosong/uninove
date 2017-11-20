<?php

namespace Application\Controller;

class MessengerController extends CrudController {

    public function __construct() {
        parent::__construct('messenger');
    }
    /**
     * Envia uma nova mensagem
     * 
     * @since 14/03/2016
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @return type
     */
    public function enviarMensagemAction() {
        $dataPost = $this->getRequest()->getPost();
        $result = $this->getService()->enviarMensagem($dataPost);
        return $this->makeView(compact("result"));
    }
    /**
     * Renderiza o HTML do chat
     *
     * @return Zend\View\Model\ViewModel
     */
    public function getHtmlAction() {
        $service = $this->getService();
        $user = $service->whoIAm($this->getUser());
        return $this->makeView(compact("user"));
    }
    /**
     * Retorna todas as mensagens
     * 
     * @since 14/03/2016    
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @return type
     */
    public function getMensagensAction() {
        $mensagens = $this->getService()->getMensagens();
        return $this->makeView(compact("mensagens"));
    }
    /**
     * Devolte a tela do chat um arquivo html simples
     *
     * @return Zend\View\Model\ViewModel
     */
    public function getHistoryAction() {
        $dataPost = $this->getRequest()->getPost();
        $service = $this->getService();
        $data = $service->getHistory($dataPost);
        return $this->makeView(compact("data"));
    }
    /**
     * Retorna a lista de contatos e grupos deste usuario
     * 
     * @return type
     */
    public function listaContatosGruposAction() {
        /* @var $user \Application\Entity\User */
        $user = $this->getEm()->getRepository("\Application\Entity\User")->findOneByUsuarioId($this->getUser("id"));
        $usuarios = $grupos = [];
        if(is_null($user)){
            return $this->makeView(compact("usuarios", "grupos"));
        }
        $user->setAccessDatetime("");
        $this->getEm()->persist($user);
        $this->getEm()->flush();
        
        $usuarios = $user->listarMyContatos();
        $grupos = $user->listarGrupos();
        return $this->makeView(compact("usuarios", "grupos"));
    }
    /**
     * Retorna as informações do meu usuario
     * 
     * @return type
     */
    public function getMeuUsuarioAction() {
        $meuUsuario = $this->getService()->getMeuUsuario();
        return $this->makeView(compact("meuUsuario"));
    }

    /**
     * Atualiza cadastro de seu novo status.
     * @return Zend\View\Model\ViewModel
     */
    public function sendStatusAction() {
        $request = $this->getRequest()->getPost()->toArray();
        $data = $this->getService()->getChangeStatusUser($request);
        return $this->makeView(compact("data"));
    }

    /**
     * Atualiza messenger se algum contato dele trocou de status ou msg de status.
     * @return Zend\View\Model\ViewModel
     */
    public function receiveStatusAction() {
        /*@var $service \Application\Service\Messenger*/
        $dataPost = $this->getRequest()->getPost();
        $service = $this->getService();
        $data = $service->getUpgradedStatusUser($dataPost);
        return $this->makeView(compact("data"));
    }
    /**
     * Pegar todos os contatos e grupos do usuario para carregar na lista.
     *
     * @obsolete
     * @return Zend\View\Model\ViewModel
     */
    public function receiveContactsAction() {
        /* @var $repository \Application\Entity\Repository\ContatoRepository */
        $repository = $this->getEm()->getRepository($this->moduloName . "\Entity\Contato");
        $data = $repository->getMyContactAndGrupos($this->getUser());
        $service = $this->getService();
        $user = $service->whoIam($this->getUser());
        return $this->makeView(compact("data", "user"));
    }

    /**
     * Busca na base de dados todas as mensagem não recebidas
     * Carrega as mensagem ainda não recebidas e marca as mesmas para não receber em
     * duplicidade.
     * @return Zend\View\Model\ViewModel
     */
    public function receiveMsgAction() {
        /* @var $repository \Application\Entity\Repository\EnviadoRepository */
        $repository = $this->getEm()->getRepository($this->moduloName . "\Entity\Enviado");
        $mensagens = $repository->getMsgNotReceived($this->getUser());
        return $this->makeView(compact('mensagens'));
    }

    /**
     * Grava mensagem enviado pelo chat para um contato ou grupo
     *
     * @return Zend\View\Model\ViewModel
     */
    public function sendMsgAction() {
        $request = $this->getRequest();
        $msgdata = $request->getPost();
        $service = $this->getService();
        $service->sendMensagem($msgdata);
        return $this->makeView([]);
    }

    /**
     * Devolve para o chat os dados do usuario do chat
     *
     * @return Zend\View\Model\ViewModel
     */
    public function whoiamAction() {
        $service = $this->getService();
        $meUser = $service->whoIam($this->getUser());

        return $this->makeView(compact('meUser'));
    }

    /**
     * Altera msg de status do chat
     *
     * @return Zend\View\Model\ViewModel
     */
    public function editMsgStatusAction() {
        $data = $this->getService()->ChangeStatusMsgUser($this->getRequest()->getPost());
        return $this->makeView(compact("data"));
    }
    

}
