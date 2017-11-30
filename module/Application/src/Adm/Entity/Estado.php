<?php

namespace Adm\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Estado
 *
 * @ORM\Table(name="adm_estado", indexes={
 *       @ORM\Index(name="fk_estado_pais1_idx", columns={"id_pais"})
 *      ,@ORM\Index(name="fk_Adm_estado_created_by_id_idx"    , columns={"created_by_id"})
 *      ,@ORM\Index(name="fk_Adm_estado_updated_by_id_idx"    , columns={"updated_by_id"})
 * 
 * })
 * @ORM\Entity(repositoryClass="\Adm\Entity\Repository\EstadoRepository")
 * @ORM\HasLifecycleCallbacks
 * @author Danilo Dorotheu <danilo.dorotheu@live.com>
 */
class Estado extends \Application\Entity\AbstractEntity{

    /**
     * @var integer
     *
     * @ORM\Column(name="id_estado", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idEstado;

    /**
     * @var string
     *
     * @ORM\Column(name="nome_estado", type="string", length=70, nullable=true)
     */
    private $nomeEstado;

    /**
     * @var string
     *
     * @ORM\Column(name="uf", type="string", length=3, nullable=true)
     */
    private $uf;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=45, nullable=false, options={"default" = "ATIVO"})
     */
    private $status = 'ATIVO';

    /**
     * @var \Adm\Entity\Pais
     *
     * @ORM\ManyToOne(targetEntity="\Adm\Entity\Pais")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_pais", referencedColumnName="id_pais")
     * })
     */
    private $pais;
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
     * Construtor
     * @param array $options
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     */
    public function __construct(array $options = array()) {
        parent::__construct($options);
        $this->createdAt = new \DateTime("now");
    }

    /**
     * 
     * @return string
     */
    public function __toString() {
        return $this->getNomeEstado();
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
     * (Alias) Retorna ID do estado
     * @return integer
     */
    public function getId() {
        return $this->getIdEstado();
    }
    
    /**
     * (Alias) Retorna ID do estado
     * @return integer
     */
    public function setId($id) {
        return $this->setIdEstado($id);
    }    
    
        /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 11-11-2016           
     * @param integer $idEstado
     * @return \Adm\Entity\Estado
     */
    public function setIdEstado($idEstado) {
        $this->idEstado = $idEstado;
        return $this;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 11-11-2016           
     * @return integer
     */
    public function getIdEstado() {
        return $this->idEstado;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 11-11-2016           
     * @param string $nomeEstado
     * @return \Adm\Entity\Estado
     */
    public function setNomeEstado($nomeEstado) {
        $this->nomeEstado = $nomeEstado;
        return $this;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 11-11-2016           
     * @return string
     */
    public function getNomeEstado() {
        return $this->nomeEstado;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 11-11-2016           
     * @param string $uf
     * @return \Adm\Entity\Estado
     */
    public function setUf($uf) {
        $this->uf = $uf;
        return $this;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 11-11-2016           
     * @return string
     */
    public function getUf() {
        return $this->uf;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 11-11-2016           
     * @param string $status
     * @return \Adm\Entity\Estado
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
     * @param \Adm\Entity\Pais $pais
     * @return \Adm\Entity\Estado
     */
    public function setPais(\Adm\Entity\Pais $pais = NULL) {
        $this->pais = $pais;
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
     * @return \Adm\Entity\Pais
     */
    public function getPais($get='', Array $params=[]) {
        if(empty($get)){
            return $this->pais;
        }
        if(is_null($this->pais)){
            return '-';
        }
        $method = !method_exists($this->pais, $get) ? 'get' . ucfirst($get) : $get;
        if(empty($params)){
            return $this->pais->$method();
        }
        return call_user_func_array([$this->pais, $method], $params);
            
    }
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 11-11-2016           
     * @param \DateTime | string $createdAt
     * @return \Adm\Entity\Estado
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
     * @return \Adm\Entity\Estado
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
     * @return \Adm\Entity\Estado
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
     * @return \Adm\Entity\Estado
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
    
    
    
}
