<?php

namespace Adm\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Stdlib\Hydrator;

/**
 * Fornecedor
 * @author Danilo Song <danilosong@outlook.com>
 * @since 14-06-2017
 *
 * @ORM\Table(name="adm_fornecedor", indexes={
 *       @ORM\Index(name="fk_adm_fornecedor_created_by_id_idx"    , columns={"created_by_id"})
 *      ,@ORM\Index(name="fk_adm_fornecedor_updated_by_id_idx"    , columns={"updated_by_id"})
 *      ,@ORM\Index(name="fk_adm_fornecedor_endereco_id_idx"      , columns={"endereco_id"  })
 * })
 * @ORM\Entity(repositoryClass="\Adm\Entity\Repository\ProdutoRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Fornecedor extends AdmAbstractEntity {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @var \Adm\Entity\Endereco
     * @ORM\ManyToOne(targetEntity="\Adm\Entity\Endereco")
     * @ORM\JoinColumn(name="endereco_id", referencedColumnName="id_endereco")
     */
    protected $endereco;
    
    /**
     * @var string
     *
     * @ORM\Column(name="nome_fornec", type="string", nullable=FALSE)
     */
    protected $nomeFornec;
    
    /**
     * @var string
     *
     * @ORM\Column(name="nome_fantasia", type="string", nullable=TRUE)
     */
    protected $nomeFantasia;
    
    /**
     * @var string
     *
     * @ORM\Column(name="home_page", type="string", nullable=TRUE)
     */
    protected $homePage;
    
    /**
     * @var string
     *
     * @ORM\Column(name="contato", type="string", nullable=TRUE)
     */
    protected $contato;
    
    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", nullable=TRUE)
     */
    protected $email;
    
    /**
     * @var string
     *
     * @ORM\Column(name="setor", type="string", nullable=FALSE)
     */
    protected $setor;
    
    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=45, nullable=false, options={"default" = "ATIVO"})
     */
    private $status = "ATIVO";
    
    /**
     * @var string
     *
     * @ORM\Column(name="cnpj", type="string", length=20, nullable=false)
     */
    protected $cnpj;
    
    /**
     * @var string
     *
     * @ORM\Column(name="inscricao_estadual", type="string", length=20, nullable=TRUE)
     */
    protected $inscricaoEstadual;
    
    /**
     * @var string
     *
     * @ORM\Column(name="telefone", type="string", length=20, nullable=TRUE)
     */
    protected $telefone;
    
    /**
     * @var string
     *
     * @ORM\Column(name="celular", type="string", length=20, nullable=true)
     */
    protected $celular;
    
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
     * @return \Adm\Entity\Fornecedor
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
     * @param \Adm\Entity\Endereco $endereco
     * @return \Adm\Entity\Fornecedor   
     */
    public function setEndereco(\Adm\Entity\Endereco $endereco = NULL) {
        $this->endereco = $endereco;
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
     * @return \Adm\Entity\Endereco
     */
    public function getEndereco($method='', Array $params=[]) {
        return $this->relationGet($this->endereco, $method, $params);            
    }
    
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @param string $nomeFornec
     * @return \Adm\Entity\Fornecedor
     */
    public function setNomeFornec($nomeFornec) {
        $this->nomeFornec = $nomeFornec;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @return string
     */
    public function getNomeFornec() {
        return $this->nomeFornec;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @param string $nomeFantasia
     * @return \Adm\Entity\Fornecedor
     */
    public function setNomeFantasia($nomeFantasia) {
        $this->nomeFantasia = $nomeFantasia;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @return string
     */
    public function getNomeFantasia() {
        if(!$this->nomeFantasia) {
            return "-";
        }
        return $this->nomeFantasia;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @param string $homePage
     * @return \Adm\Entity\Fornecedor
     */
    public function setHomePage($homePage) {
        $this->homePage = $homePage;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @return string
     */
    public function getHomePage() {
        if(!$this->homePage) {
            return "-";
        }
        return $this->homePage;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @param string $contato
     * @return \Adm\Entity\Fornecedor
     */
    public function setContato($contato) {
        $this->contato = $contato;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @return string
     */
    public function getContato() {
        if(!$this->contato) {
            return "-";
        }
        return $this->contato;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @param string $email
     * @return \Adm\Entity\Fornecedor
     */
    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @return string
     */
    public function getEmail() {
        if(!$this->email) {
            return "-";
        }
        return $this->email;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @param string $setor
     * @return \Adm\Entity\Fornecedor
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
     * @param string $cnpj
     * @return \Adm\Entity\Fornecedor
     */
    public function setCnpj($cnpj) {
        $this->cnpj = $cnpj;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @return string
     */
    public function getCnpj() {
        return $this->cnpj;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @param string $inscricaoEstadual
     * @return \Adm\Entity\Fornecedor
     */
    public function setInscricaoEstadual($inscricaoEstadual) {
        $this->inscricaoEstadual = $inscricaoEstadual;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @return string
     */
    public function getInscricaoEstadual() {
        if(!$this->inscricaoEstadual) {
            return "-";
        }
            return $this->inscricaoEstadual;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @param string $telefone
     * @return \Adm\Entity\Fornecedor
     */
    public function setTelefone($telefone) {
        $this->telefone = $telefone;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @return string
     */
    public function getTelefone() {
        return $this->telefone;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @param string $celular
     * @return \Adm\Entity\Fornecedor
     */
    public function setCelular($celular) {
        $this->celular = $celular;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 14/06/2017           
     * @return string
     */
    public function getCelular() {
        if(!$this->celular) {
            return "-";
        }
        return $this->celular;
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
