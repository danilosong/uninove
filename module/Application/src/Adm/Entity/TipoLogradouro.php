<?php

namespace Adm\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoLogradouro
 *  @ORM\Table(name="adm_tipoLogradouro", indexes={
 *  @ORM\Index(name="fk_logradouro_created_by_id_idx"    , columns={"created_by_id"})
 * ,@ORM\Index(name="fk_logradouro_updated_by_id_idx"    , columns={"updated_by_id"})
 * })
 * @ORM\Entity(repositoryClass="\Adm\Entity\Repository\TipoLogradouroRepository")
 * @ORM\HasLifecycleCallbacks
 * @author Danilo Dorotheu <danilo.dorotheu@live.com>
 */
class TipoLogradouro extends \Application\Entity\AbstractEntity {

    /**
     * @var integer
     *
     * @ORM\Column(name="id_tipo_logradouro", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idTipoLogradouro;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=20, nullable=true)
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=10, nullable=false, options={"default" = "ATIVO"})
     */
    private $status='ATIVO';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var \Application\Entity\Usuario
     *
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="created_by_id", referencedColumnName="id_usuario")
     * })
     */
    protected $createdBy;

    /**
     * @var \Application\Entity\Usuario
     *
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="updated_by_id", referencedColumnName="id_usuario")
     * })
     */
    protected $updatedBy;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="sigla", type="string", length=45, nullable=true) 
     */
    private $sigla;
    

    /**
     * Construtor
     * @param array $options
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     */
    public function __construct(array $options = array()) {
        parent::__construct($options);
        $this->createdAt = new \DateTime("now");
    }

    /**
     * Retorna o ID do Tipo de Logradouro
     * @return integer
     */
    public function getIdTipoLogradouro() {
        return $this->idTipoLogradouro;
    }

    /**
     * (Alias) Retorna o ID do Tipo de Logradouro
     * @return string
     */
    public function getId() {
        return $this->getIdTipoLogradouro();
    }

    /**
     * Retorna o nome do Tipo de Logradouro
     * @return string
     */
    public function getTipo() {
        return $this->tipo;
    }

    /**
     * Retorna o status do Tipo de Logradouro
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Retorna data de desenvolvimento da entidade   
     * @return string
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     */
    public function getCreatedAt($obj = FALSE, $full = FALSE) {
        return $this->dateToStr($this->createdAt, $full, $obj);
    }

    /**
     * Retorna data de atualizacao da entidade
     * @return string
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     */
    public function getUpdatedAt($obj = FALSE, $full = FALSE) {
        return $this->dateToStr($this->updatedAt, $full, $obj);
    }

    /**
     * Seta o ID do tipo de logradouro
     * @param integer $idTipoLogradouro
     * @return \Adm\Entity\TipoLogradouro
     */
    public function setIdTipoLogradouro($idTipoLogradouro) {
        $this->idTipoLogradouro = $idTipoLogradouro;
        return $this;
    }

    /**
     * (Alias) Seta o ID do tipo de logradouro
     * @param integer $id
     * @return \Adm\Entity\TipoLogradouro
     */
    public function setId($id) {
        return $this->setIdTipoLogradouro($id);
    }

    /**
     * Seta o nome do tipo de logradouro
     * @param string $tipo
     * @return \Adm\Entity\TipoLogradouro
     */
    public function setTipo($tipo) {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * Retorna o status do tipo de logradouro
     * @param string $status
     * @return \Adm\Entity\TipoLogradouro
     */
    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }

    /**
     * Seta a data de desenvolvimento desta entidade
     * @param \DateTime $createdAt
     * @return \Cid
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     */
    public function setCreatedAt() {
        $this->createdAt = new \DateTime("now");
        return $this;
    }

    /**
     * Seta data de atualizacao da entidade
     * @return \Cid
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     */
    public function setUpdatedAt() {
        $this->updatedAt = new \DateTime("now");
        return $this;
    }
    
    public function __toString() {
        return $this->getTipo();
    }
    /**
     * 
     * @return string
     */
    function getSigla() {
        return $this->sigla;
    }

    /**
     * 
     * @param string $sigla
     */
    function setSigla($sigla) {
        $this->sigla = $sigla;
    }

    /**
     * Realiza modificacoes na entidade exclusivamente no update
     * 
     * @ORM\PreUpdate
     * @param type $param
     */
    public function beforeUpdate() {
        $this->setUpdatedAt();
    }
    
     /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 19/06/2017           
     * @param \Application\Entity\Usuario $createdBy
     * @return \Adm\Entity\Logradouro
     */
    public function setCreatedBy(\Application\Entity\Usuario $createdBy = NULL) {
        $this->createdBy = $createdBy;
        return $this;
    }
        
    /**
     * Retorna a entidade  ou um atributo se for passado por parametro.
     * Caso passe o parametro e a relação for a relação for null será retornado string '-'           
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 19/06/2017   
     * @param string $method  Nome do method get a ser retornado da relação.
     * @param array  $params Parametro(s) a serem usado neste  get da relação.
     * @return \Application\Entity\Usuario
     */
    public function getCreatedBy($method='', Array $params=[]) {
        return $this->relationGet($this->createdBy, $method, $params);            
    }
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 19/06/2017           
     * @param \Application\Entity\Usuario $updatedBy
     * @return \Adm\Entity\Logradouro
     */
    public function setUpdatedBy(\Application\Entity\Usuario $updatedBy = NULL) {
        $this->updatedBy = $updatedBy;
        return $this;
    }
        
    /**
     * Retorna a entidade  ou um atributo se for passado por parametro.
     * Caso passe o parametro e a relação for a relação for null será retornado string '-'           
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 19/06/2017   
     * @param string $method  Nome do method get a ser retornado da relação.
     * @param array  $params Parametro(s) a serem usado neste  get da relação.
     * @return \Application\Entity\Usuario
     */
    public function getUpdatedBy($method='', Array $params=[]) {
        return $this->relationGet($this->updatedBy, $method, $params);            
    }
}
