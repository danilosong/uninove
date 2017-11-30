<?php

namespace Adm\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Stdlib\Hydrator;

/**
 * PedidoItem
 * @author Danilo Song <danilosong@outlook.com>
 * @since 14/06/2017
 *
 * @ORM\Table(name="adm_pedido_item", indexes={
 *       @ORM\Index(name="fk_adm_pedido_item_created_by_id_idx"    , columns={"created_by_id"})
 *      ,@ORM\Index(name="fk_adm_pedido_item_updated_by_id_idx"    , columns={"updated_by_id"})
 *      ,@ORM\Index(name="fk_adm_pedido_item_pedido_id_idx"        , columns={"pedido_id"    })
 *      ,@ORM\Index(name="fk_adm_pedido_item_produto_id_idx"       , columns={"produto_id"   })
 * })
 * @ORM\Entity(repositoryClass="\Adm\Entity\Repository\ProdutoRepository")
 * @ORM\HasLifecycleCallbacks
 */
class PedidoItem extends AdmAbstractEntity {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @var \Adm\Entity\Pedido
     *
     * @ORM\ManyToOne(targetEntity="\Adm\Entity\Pedido", inversedBy="pedidoItems")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="pedido_id", referencedColumnName="id")
     * })
     */
    protected $pedido;
    
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
     * @var integer
     *
     * @ORM\Column(name="qtd", type="integer", nullable=false)
     */
    private $qtd;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="qtd_receb", type="integer", nullable=true)
     */
    private $qtdReceb;
    
    /**
     * @var float
     *
     * @ORM\Column(name="total", type="decimal", precision=20, scale=2, nullable=true)
     */
    protected $total;
    
    /**
     * @var float
     *
     * @ORM\Column(name="valor", type="decimal", precision=10, scale=2, nullable=true)
     */
    protected $valor;
    
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
     * @return \Adm\Entity\PedidoItem
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
     * @param \Adm\Entity\Pedido $pedido
     * @return \Adm\Entity\PedidoItem
     */
    public function setPedido(\Adm\Entity\Pedido $pedido = NULL) {
        $this->pedido = $pedido;
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
     * @return \Adm\Entity\Pedido
     */
    public function getPedido($method='', Array $params=[]) {
        return $this->relationGet($this->pedido, $method, $params);            
    }
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @param \Adm\Entity\Produto $produto
     * @return \Adm\Entity\PedidoItem
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
     * @param integer $qtd
     * @return \Adm\Entity\PedidoItem
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
     * @since 29/06/2017           
     * @param integer $qtdReceb
     * @return \Adm\Entity\LiberacaoExame
     */
    public function setQtdReceb($qtdReceb = 0) {
        $this->qtdReceb = $qtdReceb;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 29/06/2017           
     * @return integer
     */
    public function getQtdReceb() {
        return $this->qtdReceb;
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
     * @since 14/06/2017           
     * @param float | string $valor
     * @return \Adm\Entity\PedidoItem
     */
    public function setValor($valor = 0) {
        $this->valor = $this->strToFloat($valor);
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
    public function getValor($formated = TRUE) {
        return $this->floatToStr($this->valor, $formated);
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
        return $this->dateToStr($this->updatedAt, $full, $obj);
    }
        
}
