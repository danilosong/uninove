<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AppPrivilege
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="app_privilege", indexes={@ORM\Index(name="fk_app_privilege_app_role1", columns={"role_id"}), @ORM\Index(name="fk_app_privilege_app_resource1", columns={"resource_id"})})
 * @ORM\Entity(repositoryClass="\Application\Entity\Repository\AppPrivilegeRepository")
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
class AppPrivilege extends AbstractEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_privilege", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPrivilege;

    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", length=45, nullable=false)
     */
    private $nome;

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
     *   @ORM\JoinColumn(name="role_id", referencedColumnName="id_role")
     * })
     */
    private $role;

    /**
     * @var \Application\Entity\AppResource
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\AppResource")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="resource_id", referencedColumnName="id_resource")
     * })
     */
    private $resource;

    public function __construct(array $options = []) {
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
        parent::__construct($options);
    }
    
    /**
     * A descrição do privilegio
     * @return string
     */
    public function __toString() {
        return $this->getNome();
    }

    /**
     * Metodo padrão para o campo key da tabela
     * @return integer
     */
    public function getId() {
        return $this->getIdPrivilege();
    }
    
    /**
     * Metodo padrão para setar o campo key da tabela
     * @param type $id
     * @return \Application\Entity\AppPrivilege
     */
    public function setId($id) {
        return $this->setIdPrivilege($id);
    }

    /**
     * 
     * @return integer
     */
    public function getIdPrivilege() {
        return $this->idPrivilege;
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
     * @return \Application\Entity\AppRole
     */
    public function getRole() {
        return $this->role;
    }

    /**
     * 
     * @return \Application\Entity\AppResource
     */
    public function getResource() {
        return $this->resource;
    }

    /**
     * 
     * @param integer $idPrivilege
     * @return \Application\Entity\AppPrivilege
     */
    public function setIdPrivilege($idPrivilege) {
        $this->idPrivilege = $idPrivilege;
        return $this;
    }

    /**
     * 
     * @param string $nome
     * @return \Application\Entity\AppPrivilege
     */
    public function setNome($nome) {
        $this->nome = $nome;
        return $this;
    }

    /**
     * 
     * @param \DateTime | string $createdAt
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
     * @param \Application\Entity\AppRole $role
     * @return \Application\Entity\AppPrivilege
     */
    public function setRole(\Application\Entity\AppRole $role) {
        $this->role = $role;
        return $this;
    }

    /**
     * 
     * @param \Application\Entity\AppResource $resource
     * @return \Application\Entity\AppPrivilege
     */
    public function setResource(\Application\Entity\AppResource $resource) {
        $this->resource = $resource;
        return $this;
    }


}

