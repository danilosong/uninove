<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AppResource
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="app_resource")
 * @ORM\Entity(repositoryClass="\Application\Entity\Repository\AppResourceRepository")
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
class AppResource extends AbstractEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_resource", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idResource;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

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
    
    

    public function __construct(array $options = []) {
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
        parent::__construct($options);
    }
    
    /**
     * A descrição do Recurso
     * @return string
     */
    public function __toString() {
        return $this->getName();
    }

    /**
     * Metodo padrão para o campo key da tabela
     * @return integer
     */
    public function getId() {
        return $this->getIdResource();
    }
    
    /**
     * Metodo padrão para setar o campo key da tabela
     * @param type $id
     * @return \Application\Entity\AppResource
     */
    public function setId($id) {
        return $this->setIdResource($id);
    }

    /**
     * 
     * @return integer
     */
    public function getIdResource() {
        return $this->idResource;
    }

    /**
     * 
     * @return string
     */
    public function getName() {
        return $this->name;
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
     * @param intenger $idResource
     * @return \Application\Entity\AppResource
     */
    public function setIdResource($idResource) {
        $this->idResource = $idResource;
        return $this;
    }

    /**
     * 
     * @param integer $name
     * @return \Application\Entity\AppResource
     */
    public function setName($name) {
        $this->name = $name;
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

}

