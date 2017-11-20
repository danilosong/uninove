<?php

namespace Application\Fixture;

use Doctrine\Common\DataFixtures\AbstractFixture,
    Doctrine\Common\DataFixtures\OrderedFixtureInterface,
    Doctrine\Common\Persistence\ObjectManager;
use Application\Conexao\Mysql;

/**
 * @author Paulo Watakabe <watakabe05@gmail.com>
 * @tutorial https://github.com/doctrine/data-fixtures
 * @version 1.0
 */
class LoadUser extends AbstractFixture implements OrderedFixtureInterface {

    public $entity;
    
    /**
     * Vai acumulando os usuarios usados(cache)
     * 
     * @var array
     */
    public $usuario = [];
    
     /**
     *
     * @var \Application\Entity\Repository\UsuarioRepository
     */
    public $rpUsuario;
    
    /**
     * Conexão extra com o BD utilizando PDO
     * 
     * @var \Application\Conexao\Mysql 
     */
    public $mysql;
    
    /**
     * Dados a serem gravados no bd
     * 
     * @var array 
     */
    public $data = [];

    public function getOrder() {
        return 6;
    }
    
    /**
     * 
     * @return \Application\Conexao\Mysql
     */
    public function getMysql() {
        if(is_null($this->mysql)){
            $this->mysql = new Mysql();
        }
        return $this->mysql;
    }

    public function load(ObjectManager $manager) {
        $this->entity = 'Application\Entity\User';
        echo 'entity ', $this->entity;
        // Limpar tabela
        if(!is_null($manager)){
            $this->getMysql()->truncate($this->entity, $manager);
            $this->getMysql()->truncate($this->entity, $manager, 'contacts');
            $this->em = $manager;
        }
        $this->rpUsuario     = $this->em->getRepository("\Application\Entity\Usuario");
        
        /* @var $usuario \Application\Entity\Usuario */ 
        $usuarios = $this->rpUsuario->findAll();
        foreach ($usuarios as $usuario) {
            $this->get($usuario->getNickname(),$usuario->getNome());
        }
        
        // Retorna o data pois sera tratado na fixture filha
        if(is_null($manager)){
            return $this->data;
        }
        
        foreach ($this->data as $item) {
            $this->em->persist(new $this->entity($item));
        }
        $manager->flush();

        $users = $manager->getRepository($this->entity)->findAll();
        foreach ($users as $user) {
            $user->myContatos = $users;
            $manager->persist($user);
        }

        $manager->flush();

        $this->em->flush();

        echo ' ok itens ', count($this->data) , PHP_EOL; 
    }
    
    public function getUsuario($usuario) {
        if (!isset($this->usuario[$usuario])) {
            $this->usuario[$usuario] = $this->rpUsuario->findOneByNickname($usuario);
            if (!$this->usuario[$usuario]) {
                throw new \Exception('Usuario nao encontrado '. $usuario);
            }
        }
        return $this->usuario[$usuario];
    }
    
    /**
     * Gera um registro para parametro em form de array e o inclui em data
     * 
     * @author Allan Davini <allan_mv19@hotmail.com>
     * @param string $usuario
     * @param string $nome
     * @param string $statusChat
     * @param datetime $statusDatetime
     * @param string $statusMsg
     * @param boolean $status
     * @param datetime $access_datetime
     */
    public function get(
            $usuario, 
            $nome, 
            $statusChat="online", 
            $statusDatetime="2015-07-11 09:41:04", 
            $statusMsg="Olá sou novo por aqui!", 
            $status="ATIVO", 
            $access_datetime= NULL
    ) {
        $this->data[] = 
            [
                "usuarioId"        => $this->getUsuario($usuario)->getId(),
                "nome"             => $nome,
                "statusChat"       => $statusChat,
                "statusDatetime"   => $statusDatetime,
                'statusMsg'        => $statusMsg,
                "status"           => $status,
                'access_datetime'  => $access_datetime
            ];
        }
}
