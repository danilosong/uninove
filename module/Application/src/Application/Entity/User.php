<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * EntityUser
 *
 * @author Paulo Watakabe <watakabe05@gmail.com>
 * @version 1.0
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="\Application\Entity\Repository\UserRepository")
 */
class User extends AbstractEntity {

    /**
     * @var integer
     *
     * @ORM\Column(name="id_user", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idUser;

    /**
     * Id que faz referencia ao usuario do sistema na tabela usuario caso exista
     * @var integer
     * 
     * @ORM\Column(name="usuario_id", type="integer", nullable=true)
     */
    private $usuarioId;

    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", length=45, nullable=true)
     */
    private $nome;

    /**
     * @var string
     *
     * @ORM\Column(name="status_chat", type="string", length=15, nullable=true)
     */
    private $statusChat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="status_datetime", type="datetime", nullable=true)
     */
    private $statusDatetime = '2001-01-15 12:01:00';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="access_datetime", type="datetime", nullable=true)
     */
    private $accessDatetime = '2001-01-15 12:01:00';

    /**
     * @var string
     *
     * @ORM\Column(name="status_msg", type="string", length=45, nullable=true)
     */
    private $statusMsg;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=10, nullable=true)
     */
    private $status;

    /**
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @var \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="Contato", mappedBy="userUser", cascade={"persist", "remove"})
     * */
    private $contatos;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="myContatos")
     */
    private $contatosWithMe;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="contatosWithMe")
     * @ORM\JoinTable(name="contacts",
     *      joinColumns={@ORM\JoinColumn(name="id_user_contatoswithme", referencedColumnName="id_user")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_user_mycontatos", referencedColumnName="id_user")}
     *      )
     */
    public $myContatos;

    /**
     * @ORM\ManyToMany(targetEntity="\Application\Entity\Grupo", mappedBy="users")
     */
    private $grupos;

    /**
     * @ORM\OneToMany(targetEntity="Enviado", mappedBy="toUser")
     */
    private $enviados;

    public function __construct(array $options = []) {
        $this->statusDatetime = new \DateTime($this->statusDatetime);
        $this->accessDatetime = new \DateTime($this->accessDatetime);

        $this->contatos = new ArrayCollection();

        $this->contatosWithMe = new ArrayCollection();

        $this->myContatos = new ArrayCollection();

        $this->grupos = new ArrayCollection();
        
        $this->enviados = new ArrayCollection();
        
        parent::__construct($options);
    }

    /**
     * Retorna o nome do user no chat
     * @return string
     */
    public function __toString() {
        return $this->getNome();
    }

    /**
     * Alias para metodo getIdUser()
     * @return integer
     */
    public function getId() {
        return $this->getIdUser();
    }

    /**
     * Alias para metodo setIdUser()
     * @param integer $id
     * @return type
     */
    public function setId($id) {
        return $this->setIdUser($id);
    }

    /**
     * 
     * @return integer
     */
    public function getIdUser() {
        return $this->idUser;
    }

    /**
     * ID do usuario na tabela usuario do sistema
     * @return integer
     */
    public function getUsuarioId() {
        return $this->usuarioId;
    }

    /**
     * 
     * @return string
     */
    public function getNome() {
        return $this->nome;
    }

    /**
     * 
     * @return string
     */
    public function getStatusChat() {
        return $this->statusChat;
    }

    /**
     * 
     * @return string | \DateTime
     */
    public function getStatusDatetime($obj = FALSE, $full = FALSE) {
        return $this->dateToStr($this->statusDatetime, $full, $obj);
    }

    /**
     * 
     * @return string | \DateTime
     */
    public function getAccessDatetime($obj = FALSE, $full = FALSE) {
        return $this->dateToStr($this->accessDatetime, $full, $obj);
    }

    public function getStatusMsg() {
        return $this->statusMsg;
    }

    /**
     * 
     * @return string | \DateTime
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * 
     * @param interger $idUser
     * @return \Application\Entity\User
     */
    public function setIdUser($idUser) {
        $this->idUser = $idUser;
        return $this;
    }

    /**
     * O id da tabela usuario do sistema para referencia no chat
     * @param integer $usuarioId
     * @return \Application\Entity\User
     */
    public function setUsuarioId($usuarioId) {
        $this->usuarioId = $usuarioId;
        return $this;
    }

    /**
     * 
     * @param string $nome
     * @return \Application\Entity\User
     */
    public function setNome($nome) {
        $this->nome = $nome;
        return $this;
    }

    /**
     * 
     * @param string $statusChat
     * @return \Application\Entity\User
     */
    public function setStatusChat($statusChat) {
        $this->statusChat = $statusChat;
        return $this;
    }

    /**
     * 
     * @param \DateTime | string   $statusDatetime
     * @return \Application\Entity\User
     */
    public function setStatusDatetime($statusDatetime) {
        $this->statusDatetime = $this->strToDate($statusDatetime);
        return $this;
    }

    /**
     * 
     * @param \DateTime | string   $statusDatetime
     * @return \Application\Entity\User
     */
    public function setAccessDatetime($accessDatetime) {
        $this->accessDatetime = $this->strToDate($accessDatetime);
        return $this;
    }

    /**
     * 
     * @param string $statusMsg
     * @return \Application\Entity\User
     */
    public function setStatusMsg($statusMsg) {
        $this->statusMsg = $statusMsg;
        return $this;
    }

    /**
     * 
     * @param string $status
     * @return \Application\Entity\User
     */
    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }

    /**
     * Lista todos os contatos
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @return array of \Application\Entity\Contato
     */
    public function listContatos() {
        return $this->contatos->toArray();
    }

    /**
     * Setar todos os contatos atraves de um array com a referencia ou as entidades
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param array of \Application\Entity\Contato $arrayContato Array com as Entidades
     * @param boolean $remove                                    Caso somente queira inserir Padrão e True(remove) 
     * @return \Application\Entity\User 
     */
    public function setContatos(array $arrayContato = [], $remove = TRUE) {
        /* @var $contato \Application\Entity\Contato */
        // procurar os que nao vai alterar e os que vai excluir
        foreach ($this->contatos->toArray() as $contato) {
            foreach ($arrayContato as $id => $refContato) {
                if ($contato->getContatoUser()->getId() === $refContato->getId()) {
                    unset($arrayContato[$id]);
                    continue 2;
                }
            }
            if ($remove) {
                $this->rmContato($contato);
            }
        }
        // Incluir os novos contato
        foreach ($arrayContato as $id => $refContato) {
            $this->addContato($refContato);
        }
        return $this;
    }

    /**
     * Retorna um array de dos contatos colocando somente id ou com id e nome 
     * 
     * @return array of ids ou names of user
     */
    public function getContatos($arrayId = TRUE) {
        /* @var $contato \Application\Entity\Contato */
        $ids = [];
        foreach ($this->contatos->toArray() as $contato) {
            if ($arrayId === TRUE) {
                $ids[] = $contato->getContatoUser()->getId();
                continue;
            }
            $ids[$contato->getContatoUser()->getId()] = $contato->getContatoUser()->getNome();
        }
        return $ids;
    }

    /**
     * Remove um contato
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param \Application\Entity\Contato $contato
     */
    public function rmContato(\Application\Entity\Contato $contato) {
        $this->contatos->removeElement($contato);
        $contato->setUserUser(NULL);
        $contato->setContatoUser(NULL);
    }

    /**
     * Adiciona um contato usando um entidade User
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param \Application\Entity\User $user
     */
    public function addContato(\Application\Entity\User $user) {
        $newContato = new \Application\Entity\Contato(['contatoUser' => $user, 'userUser' => $this]);
        $this->contatos->add($newContato);
    }

    /**
     * Lista todos os meus contatos
     * 
     * @param array $conditions Condições para localizar dado especifico
     * @return type
     */
    public function listarMyContatos($conditions = []) {
        if (!empty($conditions)) {
            $criteria = Criteria::create();
            $func = "where";
            foreach ($conditions as $field => $value) {
                $criteria->{$func}(Criteria::expr()->eq($field, $value));
                $func = "orWhere";
            }

            return $this->myContatos->matching($criteria);
        }
        return $this->myContatos;
    }

    /**
     * Adiciona um Contato na minha lista de contatos
     * 
     * @param \Application\Entity\User $user
     * @return \Application\Entity\User
     */
    public function addMyContatos(User $user) {
        if (!$this->myContatos->contains($user)) {
            $this->myContatos->add($user);
        }
        return $this;
    }

    /**
     * Remove um Contato da minha lista de Contatos
     * 
     * @param \Application\Entity\User $user
     * @return \Application\Entity\User
     */
    public function removeMyContatos($user) {
        if ($this->myContatos->contains($user)) {
            $this->myContatos->remove($user);
        }
        return $this;
    }

    /**
     * Lista todos os meus grupos
     * 
     * @param array $conditions Condições para localizar dado especifico
     * @return type
     */
    public function listarGrupos($conditions = []) {
        if (!empty($conditions)) {
            $criteria = Criteria::create();
            $func = "where";
            foreach ($conditions as $field => $value) {
                $criteria->{$func}(Criteria::expr()->eq($field, $value));
                $func = "orWhere";
            }

            return $this->grupos->matching($criteria);
        }
        return $this->grupos;
    }

    /**
     * Lista todos os meus grupos
     * 
     * @param array $conditions Condições para localizar dado especifico
     * @return type
     */
    public function listarEnviados() {
        
        
        if (!empty($conditions)) {
            $criteria = Criteria::create();
            $func = "where";
            foreach ($conditions as $field => $value) {
                $criteria->{$func}(Criteria::expr()->eq($field, $value));
                $func = "orWhere";
            }

            return $this->grupos->matching($criteria);
        }
        return $this->grupos;
    }

}
