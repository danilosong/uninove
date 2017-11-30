<?php

namespace Adm\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

/**
 * Pedido
 * @author Danilo Song <danilosong@outlook.com>
 * @since 14/06/2017
 *
 * @ORM\Table(name="adm_pedido", indexes={
 *       @ORM\Index(name="fk_adm_pedido_created_by_id_idx"    , columns={"created_by_id"})
 *      ,@ORM\Index(name="fk_adm_pedido_updated_by_id_idx"    , columns={"updated_by_id"})
 *      ,@ORM\Index(name="fk_adm_pedido_fornecedor_id_idx"    , columns={"fornecedor_id"})
 *      ,@ORM\Index(name="fk_adm_pedido_usuario_id_idx"       , columns={"usuario_id"   })
 * })
 * @ORM\Entity(repositoryClass="\Adm\Entity\Repository\ProdutoRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Pedido extends AdmAbstractEntity {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
    
    /**
     * @var \Adm\Entity\Fornecedor
     *
     * @ORM\ManyToOne(targetEntity="\Adm\Entity\Fornecedor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fornecedor_id", referencedColumnName="id")
     * })
     */
    protected $fornecedor;
    
    /**
     * @var \Application\Entity\Usuario
     *
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario_id", referencedColumnName="id_usuario")
     * })
     */
    protected $usuario;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="qtd", type="integer", nullable=true)
     */
    protected $qtd;
    
    /**
     * @var float
     *
     * @ORM\Column(name="total", type="decimal", precision=20, scale=2, nullable=true)
     */
    protected $total;
    
    /**
     * @var float
     *
     * @ORM\Column(name="frete", type="decimal", precision=20, scale=2, nullable=true)
     */
    protected $frete;
    
    /**
     * @var float
     *
     * @ORM\Column(name="desconto", type="decimal", precision=20, scale=2, nullable=true)
     */
    protected $desconto;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data_entrega", type="datetime", nullable=true)
     */
    protected $dataEntrega;
    
    /**
     * @var text
     *
     * @ORM\Column(name="obs", type="text", nullable=TRUE)
     */
    protected $obs;
    
    /**
     * @var string
     *
     * @ORM\Column(name="vendedor", type="string", nullable=TRUE)
     */
    protected $vendedor;
    
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
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="\Adm\Entity\PedidoItem", mappedBy="pedido", cascade={"persist", "remove"})
     * 
     */
    protected $pedidoItems;
    
    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=45, nullable=false, options={"default" = "ATIVO"})
     */
    protected $status = "ATIVO";
    
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
    
    public function __construct(array $options = array()) {        
        $this->pedidoItems = new ArrayCollection();
        parent::__construct($options);
    }
    
    /**
     * Retorna todos os items deste pedido que esta ativo.
     * 
     * @param array $filters
     * @param boolean $first
     * @return \Doctrine\Common\Collections\ArrayCollection Of \Adm\Entity\PedidoItem
     */
    public function listPedidoItems($filters = ['status' => 'ATIVO']) {
        $criteria = Criteria::create();
        foreach ($filters as $key => $filter){
            $criteria->andWhere(Criteria::expr()->eq($key, $filter));
        }
        return $this->pedidoItems->matching($criteria);
    }
    
      /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @param integer $id
     * @return \Adm\Entity\Pedido
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
     * @param \Adm\Entity\Fornecedor $fornecedor
     * @return \Adm\Entity\Pedido
     */
    public function setFornecedor(\Adm\Entity\Fornecedor $fornecedor = NULL) {
        $this->fornecedor = $fornecedor;
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
     * @return \Adm\Entity\Fornecedor
     */
    public function getFornecedor($method='', Array $params=[]) {
        return $this->relationGet($this->fornecedor, $method, $params);            
    }
    
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @param \Application\Entity\Usuario $usuario
     * @return \Adm\Entity\Pedido
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
     * @return \Adm\Entity\Pedido
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
        if(!$this->qtd) {
            return "-";
        }
        return $this->qtd;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @param float | string $total
     * @return \Adm\Entity\Pedido
     */
    public function setTotal($total = 0) {
        $this->total = $this->strToFloat($total);
        return $this;
    }       
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @param boolean | integer $formated False para retornar float sem formatação ou integer para qtd de casas decimais padrao 2          
     * @return float | string
     */
    public function getTotal($formated = TRUE) {
        return $this->floatToStr($this->total, $formated);
    }
    
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 18/09/2017           
     * @param float | string $frete
     * @return \Adm\Entity\Pedido
     */
    public function setFrete($frete = 0) {
        $this->frete = $this->strToFloat($frete);
        return $this;
    }       
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 18/09/2017           
     * @param boolean | integer $formated False para retornar float sem formatação ou integer para qtd de casas decimais padrao 2          
     * @return float | string
     */
    public function getFrete($formated = TRUE) {
        return $this->floatToStr($this->frete, $formated);
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 18/09/2017           
     * @param float | string $desconto
     * @return \Adm\Entity\Pedido
     */
    public function setDesconto($desconto = 0) {
        $this->desconto = $this->strToFloat($desconto);
        return $this;
    }       
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 18/09/2017           
     * @param boolean | integer $formated False para retornar float sem formatação ou integer para qtd de casas decimais padrao 2          
     * @return float | string
     */
    public function getDesconto($formated = TRUE) {
        return $this->floatToStr($this->desconto, $formated);
    }
    
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @param \DateTime | string $dataEntrega
     * @return \Adm\Entity\Pedido
     */
    public function setDataEntrega($dataEntrega = '') {
        $this->dataEntrega = $this->strToDate($dataEntrega);
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
    public function getDataEntrega($obj = FALSE, $full = FALSE) {
        return $this->dateToStr($this->dataEntrega, $full, $obj);
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 21/06/2017           
     * @param text $obs
     * @return \Adm\Entity\Pedido
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
     * @since 29/06/2017           
     * @param string $vendedor
     * @return \Adm\Entity\Pedido
     */
    public function setVendedor($vendedor) {
        $this->vendedor = $vendedor;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 29/06/2017           
     * @return string
     */
    public function getVendedor() {
        return $this->vendedor;
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
