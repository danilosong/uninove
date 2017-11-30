<?php

namespace Adm\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cidade
 *
 * @ORM\Table(name="adm_cidade", indexes={
 *      @ORM\Index(name="fk_cidade_estado1_idx"                 , columns={"id_estado"})
 *      ,@ORM\Index(name="fk_Adm_cidade_created_by_id_idx"    , columns={"created_by_id"})
 *      ,@ORM\Index(name="fk_Adm_cidade_updated_by_id_idx"    , columns={"updated_by_id"})
 * })
 * @ORM\Entity(repositoryClass="\Adm\Entity\Repository\CidadeRepository")
 * @author Danilo Dorotheu <danilo.dorotheu@live.com>
 * @ORM\HasLifecycleCallbacks
 */
class Cidade extends \Application\Entity\AbstractEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_cidade", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $idCidade;

    /**
     * @var string
     *
     * @ORM\Column(name="nome_cidade", type="string", length=100, nullable=true)
     */
    protected $nomeCidade;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=10, nullable=false, options={"default" = "ATIVO"})
     */
    protected $status = 'ATIVO';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updatedAt;

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
     * @var \Adm\Entity\Estado
     *
     * @ORM\ManyToOne(targetEntity="\Adm\Entity\Estado")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_estado", referencedColumnName="id_estado")
     * })
     */
    protected $estado;

    /**
     * Construtor
     * @param array $options
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     */
    public function __construct(array $options = array()) {
        $this->createdAt = new \DateTime("now");
        parent::__construct($options);
    }    
    
    /**
     * Alias de metodo
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 15-11-2016 
     * @return int
     */
    public function getId() {
        return $this->getIdCidade();
    }
    
    /**
     * Alias de metodo
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 15-11-2016 
     * @param int $id
     * @return \Adm\Entity\Cidade
     */
    public function setId($id) {
        return $this->setIdCidade($id);
    }
    
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 11-11-2016           
     * @param integer $idCidade
     * @return \Adm\Entity\Cidade
     */
    public function setIdCidade($idCidade) {
        $this->idCidade = $idCidade;
        return $this;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 11-11-2016           
     * @return integer
     */
    public function getIdCidade() {
        return $this->idCidade;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 11-11-2016           
     * @param string $nomeCidade
     * @return \Adm\Entity\Cidade
     */
    public function setNomeCidade($nomeCidade) {
        $this->nomeCidade = $nomeCidade;
        return $this;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 11-11-2016           
     * @return string
     */
    public function getNomeCidade() {
        return $this->nomeCidade;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 11-11-2016           
     * @param string $status
     * @return \Adm\Entity\Cidade
     */
    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 11-11-2016           
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 11-11-2016           
     * @param \DateTime | string $createdAt
     * @return \Adm\Entity\Cidade
     */
    public function setCreatedAt($createdAt = '') {
        $this->createdAt = $this->strToDate($createdAt);
        return $this;
    }       
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 11-11-2016   
     * @param boolean | string $obj  true  retorna o objeto DateTime, falso formatação d/m/Y,  string = 'full' formatação d/m/Y H:i:s ou a formatação especificada na string
     * @param boolean | string $full falso formatação definida pelo param $obj se string formatação especificada no conteudo
     * @return \DateTime | string
     */
    public function getCreatedAt($obj = FALSE, $full = FALSE) {
        return $this->dateToStr($this->createdAt, $full, $obj);
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 11-11-2016           
     * @param \DateTime | string $updatedAt
     * @return \Adm\Entity\Cidade
     */
    public function setUpdatedAt($updatedAt = '') {
        $this->updatedAt = $this->strToDate($updatedAt);
        return $this;
    }       
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 11-11-2016   
     * @param boolean | string $obj  true  retorna o objeto DateTime, falso formatação d/m/Y,  string = 'full' formatação d/m/Y H:i:s ou a formatação especificada na string
     * @param boolean | string $full falso formatação definida pelo param $obj se string formatação especificada no conteudo
     * @return \DateTime | string
     */
    public function getUpdatedAt($obj = FALSE, $full = FALSE) {
        return $this->dateToStr($this->updatedAt, $full, $obj);
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 11-11-2016           
     * @param \Application\Entity\Usuario $createdBy
     * @return \Adm\Entity\Cidade
     */
    public function setCreatedBy(\Application\Entity\Usuario $createdBy = NULL) {
        $this->createdBy = $createdBy;
        return $this;
    }
        
    /**
     * Retorna a entidade  ou um atributo se for passado por parametro.
     * Caso passe o parametro e a relação for a relação for null será retornado string '-'           
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 11-11-2016   
     * @param string $get  Nome do method get a ser retornado da relação.
     * @param array  $params Parametro(s) a serem usado neste  get da relação.
     * @return \Application\Entity\Usuario
     */
    public function getCreatedBy($get='', Array $params=[]) {
        if(empty($get)){
            return $this->createdBy;
        }
        if(is_null($this->createdBy)){
            return '-';
        }
        $method = !method_exists($this->createdBy, $get) ? 'get' . ucfirst($get) : $get;
        if(empty($params)){
            return $this->createdBy->$method();
        }
        return call_user_func_array([$this->createdBy, $method], $params);
            
    }
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 11-11-2016           
     * @param \Application\Entity\Usuario $updatedBy
     * @return \Adm\Entity\Cidade
     */
    public function setUpdatedBy(\Application\Entity\Usuario $updatedBy = NULL) {
        $this->updatedBy = $updatedBy;
        return $this;
    }
        
    /**
     * Retorna a entidade  ou um atributo se for passado por parametro.
     * Caso passe o parametro e a relação for a relação for null será retornado string '-'           
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 11-11-2016   
     * @param string $get  Nome do method get a ser retornado da relação.
     * @param array  $params Parametro(s) a serem usado neste  get da relação.
     * @return \Application\Entity\Usuario
     */
    public function getUpdatedBy($get='', Array $params=[]) {
        if(empty($get)){
            return $this->updatedBy;
        }
        if(is_null($this->updatedBy)){
            return '-';
        }
        $method = !method_exists($this->updatedBy, $get) ? 'get' . ucfirst($get) : $get;
        if(empty($params)){
            return $this->updatedBy->$method();
        }
        return call_user_func_array([$this->updatedBy, $method], $params);
            
    }
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 11-11-2016           
     * @param \Adm\Entity\Estado $estado
     * @return \Adm\Entity\Cidade
     */
    public function setEstado(\Adm\Entity\Estado $estado = NULL) {
        $this->estado = $estado;
        return $this;
    }
        
    /**
     * Retorna a entidade  ou um atributo se for passado por parametro.
     * Caso passe o parametro e a relação for a relação for null será retornado string '-'           
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 11-11-2016   
     * @param string $get  Nome do method get a ser retornado da relação.
     * @param array  $params Parametro(s) a serem usado neste  get da relação.
     * @return \Adm\Entity\Estado
     */
    public function getEstado($get='', Array $params=[]) {
        if(empty($get)){
            return $this->estado;
        }
        if(is_null($this->estado)){
            return '-';
        }
        $method = !method_exists($this->estado, $get) ? 'get' . ucfirst($get) : $get;
        if(empty($params)){
            return $this->estado->$method();
        }
        return call_user_func_array([$this->estado, $method], $params);
            
    }
    
    /**
     * 
     * @return string Com nome da Cidade
     */
    public function __toString() {
        return $this->getNomeCidade();
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
}
