<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AppRole
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="app_role", indexes={@ORM\Index(name="fk_app_role_app_role", columns={"parent_id"})})
 * @ORM\Entity(repositoryClass="\Application\Entity\Repository\AppRoleRepository")
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
class AppRole  extends AbstractEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_role", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idRole;

    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", length=45, nullable=false)
     */
    private $nome;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_admin", type="boolean", nullable=true)
     */
    private $isAdmin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * @var \Application\Entity\AppRole
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\AppRole")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id_role")
     * })
     */
    private $parent;

    public function __construct(array $options = []) {
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
        parent::__construct($options);
    }
    
    /**
     * A descrição da Regra para
     * @return string
     */
    public function __toString() {
        return $this->getNome();
    }
    
    public function toArray($limite=0, $prefix='') {
        return [
            'id'      => $this->idRole,
            'idRole'  => $this->idRole,
            'nome'    => $this->nome,
            'isAdmin' => $this->isAdmin,
            'parent'  => isset($this->parent) ? $this->parent->getId() : FALSE,
        ];
    }

    /**
     * Metodo padrão para o campo key da tabela
     * @return integer
     */
    public function getId() {
        return $this->getIdRole();
    }
    
    /**
     * Metodo padrão para setar o campo key da tabela
     * @param type $id
     * @return \Application\Entity\AppRole
     */
    public function setId($id) {
        return $this->setIdRole($id);
    }

    /**
     * 
     * @return integer
     */
    public function getIdRole() {
        return $this->idRole;
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
     * @return boolean
     */
    public function getIsAdmin() {
        return $this->isAdmin;
    }

    /**
     * 
     * @return \DateTime | string
     */
    public function getCreatedAt($obj = FALSE) {
        if($obj){
            return $this->createdAt;
        }else{
            return $this->dateToStr($this->createdAt);
        }
    }

    /**
     * 
     * @return \DateTime | string
     */
    public function getUpdatedAt($obj = FALSE) {
        if($obj){
            return $this->updatedAt;
        }else{
            return $this->dateToStr($this->updatedAt);
        }
    }

    /**
     * 
     * @return \Application\AppRole
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * 
     * @param type $idRole
     * @return \Application\Entity\AppRole
     */
    public function setIdRole($idRole) {
        $this->idRole = $idRole;
        return $this;
    }

    /**
     * 
     * @param type $nome
     * @return \Application\Entity\AppRole
     */
    public function setNome($nome) {
        $this->nome = $nome;
        return $this;
    }

    /**
     * 
     * @param type $isAdmin
     * @return \Application\Entity\AppRole
     */
    public function setIsAdmin($isAdmin) {
        $this->isAdmin = $isAdmin;
        return $this;
    }

    /**
     * 
     * @param \DateTime $createdAt
     * @return \Application\Entity\AppRole
     */
    public function setCreatedAt($createdAt) {
        if(is_string($createdAt)){
            $createdAt = $this->strToDate($createdAt);
        }
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @ORM\PrePersist
     * @param \DateTime $updatedAt
     * @return \Application\Entity\AppRole
     */
    public function setUpdatedAt() {
        $this->updatedAt = new \DateTime("now");
        return $this;
    }

    /**
     * 
     * @param \Application\AppRole | NULL $parent
     * @return \Application\Entity\AppRole
     */
    public function setParent($parent) {
        $this->parent = $parent;
        return $this;
    }

}

