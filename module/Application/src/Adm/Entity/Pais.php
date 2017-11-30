<?php

namespace Adm\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pais
 *
 *  @ORM\Table(name="tcmed_pais", indexes={
 *  @ORM\Index(name="fk_pais_created_by_id_idx"    , columns={"created_by_id"})
 * ,@ORM\Index(name="fk_pais_updated_by_id_idx"    , columns={"updated_by_id"})
 * })
 * @ORM\Entity(repositoryClass="\Tcmed\Entity\Repository\PaisRepository")
 * @ORM\HasLifecycleCallbacks
 * @author Danilo Dorotheu <danilo.dorotheu@live.com>
 */
class Pais extends \Application\Entity\AbstractEntity {

    /**
     * @var integer
     *
     * @ORM\Column(name="id_pais", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPais;

    /**
     * @var string
     *
     * @ORM\Column(name="nome_pais", type="string", length=45, nullable=false)
     */
    private $nomePais;

    /**
     * @var string
     *
     * @ORM\Column(name="sigla", type="string", length=3, nullable=true)
     */
    private $sigla;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo", type="string", length=45, nullable=true)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=10, nullable=false, options={"default" = "ATIVO"})
     */
    private $status='ATIVO';

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
     * Construtor
     * @param array $options
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     */
    public function __construct(array $options = array()) {
        parent::__construct($options);
        $this->createdAt = new \DateTime("now");
    }
    
    /**
     * Retorna o ID do Pais
     * @return integer
     */
    public function getIdPais() {
        return $this->idPais;
    }
    
    /**
     * (Alias) Retorna o ID do Pais
     * @return integer
     */
    public function getId() {
        return $this->getIdPais();
    }
    
    /**
     * Retorna o nome do Pais
     * @return string
     */
    public function getNomePais() {
        return $this->nomePais;
    }

    /**
     * Retorna a sigla do Pais
     * @return string
     */
    public function getSigla() {
        return $this->sigla;
    }
    
    /**
     * Retorna o codigo do Pais
     * @return string
     */
    public function getCodigo() {
        return $this->codigo;
    }

    /**
     * Retorna o status do Pais
     * @return status
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
     * Seta o ID do Pais
     * @param type $idPais
     * @return \Tcmed\Entity\Pais
     */
    public function setIdPais($idPais) {
        $this->idPais = $idPais;
        return $this;
    }

    /**
     * (Alias) Seta o ID do Pais
     * @param type $idPais
     * @return \Tcmed\Entity\Pais
     */
    public function setId($idPais) {
        $this->setIdPais($idPais);
        return $this;
    }

    /**
     * Seta o nome do Pais
     * @param string $nomePais
     * @return \Tcmed\Entity\Pais
     */
    public function setNomePais($nomePais) {
        $this->nomePais = $nomePais;
        return $this;
    }

    /**
     * Seta a sigla do Pais
     * @param string $sigla
     * @return \Tcmed\Entity\Pais
     */
    public function setSigla($sigla) {
        $this->sigla = $sigla;
        return $this;
    }

    /**
     * Seta o codigo do Pais
     * @param string $codigo
     * @return \Tcmed\Entity\Pais
     */
    public function setCodigo($codigo) {
        $this->codigo = $codigo;
        return $this;
    }
    
    /**
     * Seta o status do Pais
     * @param string $status
     * @return \Tcmed\Entity\Pais
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
     * @return \Tcmed\Entity\Logradouro
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
     * @return \Tcmed\Entity\Logradouro
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
