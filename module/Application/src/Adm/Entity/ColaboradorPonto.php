<?php

namespace Adm\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ColaboradorPonto
 * @author Danilo Song <danilosong@outlook.com>
 * @since 25-05-2017
 *
 * @ORM\Table(name="adm_colaborador_ponto", indexes={
 *       @ORM\Index(name="fk_adm_colaborador_ponto_created_by_id_idx"    , columns={"created_by_id"})
 *      ,@ORM\Index(name="fk_adm_colaborador_ponto_updated_by_id_idx"    , columns={"updated_by_id"})
 *      ,@ORM\Index(name="fk_adm_colaborador_ponto_colaborador_id_idx"   , columns={"colaborador_id"})
 * })
 * @ORM\Entity(repositoryClass="\Adm\Entity\Repository\ColaboradorPontoRepository")
 * @ORM\HasLifecycleCallbacks
 */
class ColaboradorPonto extends AdmAbstractEntity {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @var \Adm\Entity\Colaborador
     *
     * @ORM\ManyToOne(targetEntity="\Adm\Entity\Colaborador")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="colaborador_id", referencedColumnName="id")
     * })
     */
    protected $colaborador;

    /**
     * @var time
     *
     * @ORM\Column(name="hora", type="time", nullable=TRUE)
     */
    protected $hora;

    /**
     * @var datetime
     *
     * @ORM\Column(name="data", type="datetime", nullable=TRUE)
     */
    protected $data;
    
    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", nullable=FALSE)
     */
    private $ip;
        
    /**
     * @var string
     *
     * @ORM\Column(name="origem", type="string", nullable=TRUE)
     */
    private $origem;
    
    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", nullable=TRUE, options={"default" = "ENTRADA"})
     */
    private $tipo = "ENTRADA";
    
    /**
     * @var text
     *
     * @ORM\Column(name="obs", type="text", nullable=TRUE)
     */
    private $obs;
    
    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=45, nullable=false, options={"default" = "ATIVO"})
     */
    private $status = "ATIVO";

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
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updatedAt;
    
    //Métodos ==================================================================
    
    /**
     * @ORM\PreUpdate
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 25/05/2017           
     * @param \Doctrine\ORM\Event\PreUpdateEventArgs $event
     */
    public function beforeUpdate($event) {
        $this->setUpdatedAt();
    }
    
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 25/05/2017           
     * @param integer $id
     * @return \Adm\Entity\ColaboradorPonto
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 25/05/2017           
     * @return integer
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 26/05/2017           
     * @param \Application\Entity\Usuario $colaborador
     * @return \Adm\Entity\ColaboradorPonto
     */
    public function setColaborador(\Adm\Entity\Colaborador $colaborador = NULL) {
        $this->colaborador = $colaborador;
        return $this;
    }
        
    /**
     * Retorna a entidade  ou um atributo se for passado por parametro.
     * Caso passe o parametro e a relação for a relação for null será retornado string '-'           
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 26/05/2017   
     * @param string $method  Nome do method get a ser retornado da relação.
     * @param array  $params Parametro(s) a serem usado neste  get da relação.
     * @return \Adm\Entity\Colaborador
     */
    public function getColaborador($method='', Array $params=[]) {
        return $this->relationGet($this->colaborador, $method, $params);            
    }
    
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 25/05/2017           
     * @param time $hora
     * @return \Adm\Entity\ColaboradorPonto
     */
    public function setHora($hora = '') {
        $this->hora = $this->strToDate($hora);
        return $this;
    }    
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 25/05/2017           
     * @return time
     */
    public function getHora($obj = 'H:i', $full = FALSE) {
        return $this->dateToStr($this->hora, $full, $obj);
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook..com>
     * @version 1.0  
     * @since 30/05/2017           
     * @param datetime $data
     * @return \Adm\Entity\ColaboradorPonto
     */
    public function setData($data) {
        $this->data = $this->strToDate($data);
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook..com>
     * @version 1.0  
     * @since 30/05/2017           
     * @return datetime
     */
    public function getData($obj = FALSE, $full = FALSE) {
        return $this->dateToStr($this->data, $full, $obj);
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 25/05/2017           
     * @param string $ip
     * @return \Adm\Entity\ColaboradorPonto
     */
    public function setIp($ip) {
        $this->ip = $ip;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 25/05/2017           
     * @return string
     */
    public function getIp() {
        return $this->ip;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 25/05/2017           
     * @param string $origem
     * @return \Adm\Entity\ColaboradorPonto
     */
    public function setOrigem($origem) {
        $this->origem = $origem;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 25/05/2017           
     * @return string
     */
    public function getOrigem() {
        return $this->origem;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 25/05/2017           
     * @param string $tipo
     * @return \Adm\Entity\ColaboradorPonto
     */
    public function setTipo($tipo) {
        $this->tipo = $tipo;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 25/05/2017           
     * @return string
     */
    public function getTipo() {
        return $this->tipo;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 25/05/2017           
     * @param text $obs
     * @return \Adm\Entity\ColaboradorPonto
     */
    public function setObs($obs) {
        $this->obs = $obs;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 25/05/2017           
     * @return text
     */
    public function getObs() {
        return $this->obs;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 25/05/2017           
     * @param string $status
     * @return \Adm\Entity\ColaboradorPonto
     */
    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 25/05/2017           
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 25/05/2017           
     * @param \Application\Entity\Usuario $createdBy
     * @return \Adm\Entity\ColaboradorPonto
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
     * @since 25/05/2017   
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
     * @since 25/05/2017           
     * @param \Application\Entity\Usuario $updatedBy
     * @return \Adm\Entity\ColaboradorPonto
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
     * @since 25/05/2017   
     * @param string $method  Nome do method get a ser retornado da relação.
     * @param array  $params Parametro(s) a serem usado neste  get da relação.
     * @return \Application\Entity\Usuario
     */
    public function getUpdatedBy($method='', Array $params=[]) {
        return $this->relationGet($this->updatedBy, $method, $params);            
    }
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 25/05/2017           
     * @param \DateTime | string $createdAt
     * @return \Adm\Entity\ColaboradorPonto
     */
    public function setCreatedAt($createdAt = '') {
        $this->createdAt = $this->strToDate($createdAt);
        return $this;
    }       
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 25/05/2017   
     * @param boolean | string $obj  true  retorna o objeto DateTime, falso formatação d/m/Y,  string = 'full' formatação d/m/Y H:i:s ou a formatação especificada na string
     * @param boolean | string $full falso formatação definida pelo param $obj se string formatação especificada no conteudo
     * @return \DateTime | string
     */
    public function getCreatedAt($obj = FALSE, $full = FALSE) {
        return $this->dateToStr($this->createdAt, $full, $obj);
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 25/05/2017           
     * @param \DateTime | string $updatedAt
     * @return \Adm\Entity\ColaboradorPonto
     */
    public function setUpdatedAt($updatedAt = '') {
        $this->updatedAt = $this->strToDate($updatedAt);
        return $this;
    }       
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 25/05/2017   
     * @param boolean | string $obj  true  retorna o objeto DateTime, falso formatação d/m/Y,  string = 'full' formatação d/m/Y H:i:s ou a formatação especificada na string
     * @param boolean | string $full falso formatação definida pelo param $obj se string formatação especificada no conteudo
     * @return \DateTime | string
     */
    public function getUpdatedAt($obj = FALSE, $full = FALSE) {
        if(is_null($this->getUpdatedBy())){
            return '-';
        }
        return $this->dateToStr($this->updatedAt, $full, $obj);
    }
    
}
