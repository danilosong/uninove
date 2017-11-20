<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * EntityGrupo
 *
 * @ORM\Table(name="grupo")
 * @ORM\Entity(repositoryClass="\Application\Entity\Repository\GrupoRepository")
 */
class Grupo extends AbstractEntity {

    /**
     * @var integer
     *
     * @ORM\Column(name="id_grupo", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idGrupo;

    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", length=45, nullable=true)
     */
    private $nome;

    /**
     * @var string
     *
     * @ORM\Column(name="status_msg", type="string", length=150, nullable=true)
     */
    private $statusMsg;

    /**
     * @var string
     *
     * @ORM\Column(name="status_chat", type="string", length=15, nullable=true)
     */
    private $statusChat;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=45, nullable=true)
     */
    private $status;

    /**
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @var \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="Contato", mappedBy="grupoGrupo", cascade={"persist", "remove"})
     * */
    private $contatos;

     /**
     * @ORM\ManyToMany(targetEntity="\Application\Entity\User", inversedBy="grupos")
     * @ORM\JoinTable(name="users_groups" ,
     *      joinColumns={@ORM\JoinColumn(name="id_grupo", referencedColumnName="id_grupo")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_user", referencedColumnName="id_user")}
     * ))
     */
    private $users;

    public function __construct(array $options = array()) {

        $this->contatos = new ArrayCollection();

        $this->users = new ArrayCollection();

        parent::__construct($options);
    }

    public function __toString() {
        return $this->getNome();
    }

    public function getId() {
        return $this->getIdGrupo();
    }

    public function setId($idGrupo) {
        return $this->setIdGrupo($idGrupo);
    }

    public function getIdGrupo() {
        return $this->idGrupo;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getStatusMsg() {
        return $this->statusMsg;
    }

    public function getStatusChat() {
        return $this->statusChat;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setIdGrupo($idGrupo) {
        $this->idGrupo = $idGrupo;
        return $this;
    }

    public function setNome($nome) {
        $this->nome = $nome;
        return $this;
    }

    public function setStatusMsg($statusMsg) {
        $this->statusMsg = $statusMsg;
        return $this;
    }

    public function setStatusChat($statusChat) {
        $this->statusChat = $statusChat;
        return $this;
    }

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
                if ($contato->getContatoUser()->getId() === $id) {
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
        $contato->setGrupoGrupo(NULL);
    }

    /**
     * Adiciona um contato usando um entidade User
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param \Application\Entity\User $user
     */
    public function addContato(\Application\Entity\User $user) {
        $newContato = new \Application\Entity\Contato(['contatoUser' => $user, 'grupoGrupo' => $this]);
        $this->contatos->add($newContato);
    }

    /**
     * Adiciona um Contato na minha lista de contatos
     * 
     * @param \Application\Entity\User $user
     * @return \Application\Entity\User
     */
    public function addUsers(User $user) {
        
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }
        return $this;
    }

    /**
     * Remove um Contato da minha lista de Contatos
     * 
     * @param \Application\Entity\User $user
     * @return \Application\Entity\User
     */
    public function removeUsers($user) {
        if ($this->users->contains($user)) {
            $this->users->remove($user);
        }
        return $this;
    }

    /**
     * Lista todos os meus grupos
     * 
     * @param array $conditions Condições para localizar dado especifico
     * @return type
     */
    public function listarUsers($conditions = []) {
        if (!empty($conditions)) {
            $criteria = Criteria::create();
            $func = "where";
            foreach ($conditions as $field => $value) {
                $criteria->{$func}(Criteria::expr()->eq($field, $value));
                $func = "orWhere";
            }

            return $this->users->matching($criteria);
        }
        return $this->users;
    }

}
