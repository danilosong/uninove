<?php

namespace Adm\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Stdlib\Hydrator;

/**
 * Produto
 * @author Danilo Song <danilosong@outlook.com>
 * @since 13-06-2017
 *
 * @ORM\Table(name="adm_produto", indexes={
 *       @ORM\Index(name="fk_adm_produto_created_by_id_idx"    , columns={"created_by_id"})
 *      ,@ORM\Index(name="fk_adm_produto_updated_by_id_idx"    , columns={"updated_by_id"})
 * })
 * @ORM\Entity(repositoryClass="\Adm\Entity\Repository\ProdutoRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Produto extends AdmAbstractEntity {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @var string
     *
     * @ORM\Column(name="nome_prod", type="string", nullable=FALSE)
     */
    protected $nomeProd;
    
    /**
     * @var float
     *
     * @ORM\Column(name="valor_prod", type="decimal", precision=10, scale=2, nullable=true)
     */
    protected $valorProd;
    
    /**
     * @var string
     *
     * @ORM\Column(name="unidade_entrada", type="string", nullable=false)
     */
    private $unidadeEntrada;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="compra_qtd", type="integer", nullable=false)
     */
    private $compraQtd;
    
    /**
     * @var string
     *
     * @ORM\Column(name="unidade_saida", type="string", nullable=false)
     */
    private $unidadeSaida;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="saida_qtd", type="integer", nullable=false)
     */
    private $saidaQtd;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="estoque_atual", type="integer", nullable=true)
     */
    private $estoqueAtual;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="estoque_minimo", type="integer", nullable=true)
     */
    private $estoqueMinimo;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="estoque_maximo", type="integer", nullable=true)
     */
    private $estoqueMaximo;
    
    /**
     * @var string
     *
     * @ORM\Column(name="setor", type="string", nullable=false)
     */
    private $setor;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data_ult_compra", type="datetime", nullable=true)
     */
    private $dataUltCompra;
    
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
     * @since 13/06/2017           
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
     * @return \Adm\Entity\Produto
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
     * @param string $nomeProd
     * @return \Adm\Entity\Produto
     */
    public function setNomeProd($nomeProd) {
        $this->nomeProd = $nomeProd;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @return string
     */
    public function getNomeProd() {
        return $this->nomeProd;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @param float | string $valorProd
     * @return \Adm\Entity\Produto
     */
    public function setValorProd($valorProd = 0) {
        $this->valorProd = $this->strToFloat($valorProd);
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
    public function getValorProd($formated = TRUE) {
        return $this->floatToStr($this->valorProd, $formated);
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @param string $unidadeEntrada
     * @return \Adm\Entity\Produto
     */
    public function setUnidadeEntrada($unidadeEntrada) {
        $this->unidadeEntrada = $unidadeEntrada;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @return string
     */
    public function getUnidadeEntrada() {
        return $this->unidadeEntrada;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @param integer $compraQtd
     * @return \Adm\Entity\Produto
     */
    public function setCompraQtd($compraQtd) {
        $this->compraQtd = $compraQtd;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @return integer
     */
    public function getCompraQtd() {
        return $this->compraQtd;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @param string $unidadeSaida
     * @return \Adm\Entity\Produto
     */
    public function setUnidadeSaida($unidadeSaida) {
        $this->unidadeSaida = $unidadeSaida;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @return string
     */
    public function getUnidadeSaida() {
        return $this->unidadeSaida;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @param integer $saidaQtd
     * @return \Adm\Entity\Produto
     */
    public function setSaidaQtd($saidaQtd) {
        $this->saidaQtd = $saidaQtd;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @return integer
     */
    public function getSaidaQtd() {
        return $this->saidaQtd;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @param integer $estoqueAtual
     * @return \Adm\Entity\Produto
     */
    public function setEstoqueAtual($estoqueAtual) {
        $this->estoqueAtual = $estoqueAtual;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @return integer
     */
    public function getEstoqueAtual() {
        if(!$this->estoqueAtual) {
            return "-";
        }
        return $this->estoqueAtual;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @param integer $estoqueMinimo
     * @return \Adm\Entity\Produto
     */
    public function setEstoqueMinimo($estoqueMinimo) {
        $this->estoqueMinimo = $estoqueMinimo;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @return integer
     */
    public function getEstoqueMinimo() {
        if(!$this->estoqueMinimo) {
            return "-";
        }
        return $this->estoqueMinimo;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @param integer $estoqueMaximo
     * @return \Adm\Entity\Produto
     */
    public function setEstoqueMaximo($estoqueMaximo) {
        $this->estoqueMaximo = $estoqueMaximo;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @return integer
     */
    public function getEstoqueMaximo() {
        if(!$this->estoqueMaximo) {
            return "-";
        }
        return $this->estoqueMaximo;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @param string $setor
     * @return \Adm\Entity\Produto
     */
    public function setSetor($setor) {
        $this->setor = $setor;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @return string
     */
    public function getSetor() {
        return $this->setor;
    }
    
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 19/06/2017           
     * @param \DateTime | string $dataUltCompra
     * @return \Adm\Entity\Produto
     */
    public function setDataUltCompra($dataUltCompra = '') {
        $this->dataUltCompra = $this->strToDate($dataUltCompra);
        return $this;
    }       
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 19/06/2017   
     * @param boolean | string $obj  true  retorna o objeto DateTime, falso formatação d/m/Y,  string = 'full' formatação d/m/Y H:i:s ou a formatação especificada na string
     * @param boolean | string $full falso formatação definida pelo param $obj se string formatação especificada no conteudo
     * @return \DateTime | string
     */
    public function getDataUltCompra($obj = FALSE, $full = FALSE) {
        return $this->dateToStr($this->dataUltCompra, $full, $obj);
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
     * @since 13/06/2017           
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
     * @since 13/06/2017   
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
     * @since 13/06/2017           
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
     * @since 13/06/2017   
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
     * @since 13/06/2017           
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
     * @since 13/06/2017   
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
     * @since 13/06/2017           
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
     * @since 13/06/2017   
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
