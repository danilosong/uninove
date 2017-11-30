<?php

namespace Adm\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Stdlib\Hydrator;

/**
 * Saida
 * @author Danilo Song <danilosong@outlook.com>
 * @since 14/06/2017
 *
 * @ORM\Table(name="adm_saida", indexes={
 *       @ORM\Index(name="fk_adm_saida_created_by_id_idx"    , columns={"created_by_id"  })
 *      ,@ORM\Index(name="fk_adm_saida_updated_by_id_idx"    , columns={"updated_by_id"  })
 *      ,@ORM\Index(name="fk_adm_saida_produto_id_idx"       , columns={"produto_id"     })
 *      ,@ORM\Index(name="fk_adm_saida_usuario_id_idx"       , columns={"usuario_id"     })
 * })
 * @ORM\Entity(repositoryClass="\Adm\Entity\Repository\ProdutoRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Saida extends AdmAbstractEntity {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @var \Adm\Entity\Produto
     *
     * @ORM\ManyToOne(targetEntity="\Adm\Entity\Produto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="produto_id", referencedColumnName="id")
     * })
     */
    protected $produto;
    
    /**
     * @var \Application\Entity\Usuario
     *
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario_id", referencedColumnName="id_usuario")
     * })
     */
    private $usuario;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="qtd", type="integer", nullable=false)
     */
    private $qtd;
    
    /**
     * @var string
     *
     * @ORM\Column(name="conjunto", type="string", nullable=TRUE)
     */
    private $conjunto;
    
    /**
     * @var text
     *
     * @ORM\Column(name="obs", type="text", nullable=TRUE)
     */
    private $obs;
    
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
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=45, nullable=false, options={"default" = "ATIVO"})
     */
    private $status = "ATIVO";
    
    //Métodos===================================================================
        
    /**
     * @ORM\PreUpdate
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @param \Doctrine\ORM\Event\PreUpdateEventArgs $event
     */
    public function beforeUpdate($event) {
        $this->setUpdatedAt();
    }
    
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @param integer $id
     * @return \Adm\Entity\Saida
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0
     * @since 14/06/2017           
     * @return integer
     */
    public function getId() {
        return $this->id;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @param \Adm\Entity\Produto $produto
     * @return \Adm\Entity\Saida
     */
    public function setProduto(\Adm\Entity\Produto $produto = NULL) {
        $this->produto = $produto;
        return $this;
    }
        
    /**
     * Retorna a entidade  ou um atributo se for passado por parametro.
     * Caso passe o parametro e a relação for a relação for null será retornado string '-'           
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017   
     * @param string $method  Nome do method get a ser retornado da relação.
     * @param array  $params Parametro(s) a serem usado neste  get da relação.
     * @return \Adm\Entity\Produto
     */
    public function getProduto($method='', Array $params=[]) {
        return $this->relationGet($this->produto, $method, $params);            
    }
      
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @param \Application\Entity\Usuario $usuario
     * @return \Adm\Entity\Saida
     */
    public function setUsuario(\Application\Entity\Usuario $usuario = NULL) {
        $this->usuario = $usuario;
        return $this;
    }
        
    /**
     * Retorna a entidade  ou um atributo se for passado por parametro.
     * Caso passe o parametro e a relação for a relação for null será retornado string '-'           
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017   
     * @param string $method  Nome do method get a ser retornado da relação.
     * @param array  $params Parametro(s) a serem usado neste  get da relação.
     * @return \Application\Entity\Usuario
     */
    public function getUsuario($method='', Array $params=[]) {
        return $this->relationGet($this->usuario, $method, $params);            
    }
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @param integer $qtd
     * @return \Adm\Entity\Saida
     */
    public function setQtd($qtd) {
        $this->qtd = $qtd;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @return integer
     */
    public function getQtd() {
        return $this->qtd;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @param string $conjunto
     * @return \Adm\Entity\Saida
     */
    public function setConjunto($conjunto) {
        $this->conjunto = $conjunto;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @return string
     */
    public function getConjunto() {
        return $this->conjunto;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @param text $obs
     * @return \Adm\Entity\Saida
     */
    public function setObs($obs) {
        $this->obs = $obs;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @return text
     */
    public function getObs() {
        if(!$this->obs) {
            return "-";
        }
        return $this->obs;
    }
    
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @param string $status
     * @return \Adm\Entity\Fornecedor
     */
    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }
    
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @param \Application\Entity\Usuario $createdBy
     * @return \Adm\Entity\Produto
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
     * @since 14/06/2017   
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
     * @since 14/06/2017           
     * @param \Application\Entity\Usuario $updatedBy
     * @return \Adm\Entity\Produto
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
     * @since 14/06/2017   
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
     * @since 14/06/2017           
     * @param \DateTime | string $createdAt
     * @return \Adm\Entity\Produto
     */
    public function setCreatedAt($createdAt = '') {
        $this->createdAt = $this->strToDate($createdAt);
        return $this;
    }       
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017   
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
     * @since 14/06/2017           
     * @param \DateTime | string $updatedAt
     * @return \Adm\Entity\Produto
     */
    public function setUpdatedAt($updatedAt = '') {
        $this->updatedAt = $this->strToDate($updatedAt);
        return $this;
    }       
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017   
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
