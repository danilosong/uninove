<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Service;

use Doctrine\ORM\EntityManager;
use Zend\Stdlib\Hydrator;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Application\Mail\Mail;
/**
 * Description of User
 *
 * @author Paulo Watakabe
 */
class Usuario extends AbstractService{

    protected $transport;
    protected $view;

    public function __construct(EntityManager $em, SmtpTransport $transport, $view) {
        parent::__construct($em);
        
        $this->entity = $this->basePath . "Usuario";        
        $this->id = 'idUsuario';
        
        $this->setDataRefArray([
            'role' => $this->basePath . 'AppRole'
        ]);
        
        $this->transport= $transport;
        $this->view = $view;
    }
    
    public function saveLogo($image) {
        $param = $this->getController()->getParam();
        $photoDir = $param('all', 'usuarioDir');
        if($photoDir == 'não encontrado all'){
            $this->showMessage("Não foi encontrado um diretorio valido para arquivos de foto!!!", "error");
            return '';
        }
        if(!file_exists($photoDir)){
            mkdir($photoDir);
        }
        $oldPath = $this->entityReal->getPathFoto();
        if (!empty($oldPath)) {
            unlink($photoDir. $oldPath);
        }
        $tipo = strtolower(str_replace('.', '', strstr($image['name'], '.')));
        if($tipo != 'jpg'){
            $this->showMessage("A foto deve ser do tipo jpg !!!", "error");
            return '';
        }
        $newPath = 'foto_perfil_' . $this->data[$this->id] . '.' . $tipo;
        move_uploaded_file($image['tmp_name'], $photoDir . $newPath);
        return $newPath;
    }

    public function trataData($opc) {
        if ("update" === $opc) {
            $image = $this->getController()->params()->fromFiles('uploadFoto');
            if (!empty($image["name"])) {
                $this->data["pathFoto"] = $this->saveLogo($image);
            }
        } else { //insert
        }
    }
    
    public function insert(array $data = [], $sendEmail = true) {
        /* @var $entity \Application\Entity\Usuario */
        $entity = parent::insert($data);
        if(!$entity instanceof $this->entity){
            return $entity;
        }
        $dataEmail = array('nome'=>$entity->getNome(), 'activationKey'=> $entity->getActivationKey());
        $serviceUserChat = new User($this->em);
        $serviceUserChat->insert([
            'usuarioId' => $entity->getId(),
            'nome' => $entity->getNickname(),
            'statusChat' => 'online',
            'statusDatetime' => new \DateTime('now'),
            'statusMsg' => 'Olá sou novo por aqui!',
            'status' => 'A',
        ]);
        $options["to"] = $entity->getEmail();
        $options["subject"]  = "Confirmação de cadastro";
        $options["data"] = $dataEmail;

        $image = $this->getController()->params()->fromFiles('uploadFoto');
        if (!empty($image["name"])) {
            $entity->setPathFoto($this->saveLogo($image));
            $this->em->persist($entity);
            $this->em->flush();
        }
        if($sendEmail){
            $senderEmail = $this->getController()->getEmail();
            $senderEmail->enviaEmail($options, 'add-user'); 
        }

        return $entity;
    }
    
    public function activate($key)
    {
        $repo = $this->em->getRepository($this->entity);
        
        $user = $repo->findOneByActivationKey($key);
        
        if($user && !$user->getActive())
        {
            $user->setActive(true);
            
            $this->em->persist($user);
            $this->em->flush();
            
            return $user;
        }
    }
    
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 13-01-2017
     * @param array $data
     * @return \Application\Entity\Usuario | Array com mensagem de erros
     */
    public function update(array $data = []) {
        if(!empty($data)){
            $this->data = $data;
        }
        if (empty($this->data['senhaUsuario'])) {
            unset($this->data['senhaUsuario']);
        } 
        return parent::update();
    }
    
    /**
     *            
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 03-11-2017   
     * @return boolean | array
     */
    public function isValidInsert() {
        $finded = $this->getRepository()->findOneBy(['nickname' => $this->data['nickname']]);
        if($finded){
            $msg = 'Já existe um usuario com este Login por favor escolher outro!!!';
            $this->showMessage($msg,'error');
            return [$msg];
        }
        
        return parent::isValidInsert();
    }
}
