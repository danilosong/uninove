<?php

namespace Adm\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Endereco
 *
 *  @ORM\Table(name="adm_endereco", indexes={
 *  @ORM\Index(name="fk_endereco_created_by_id_idx"    , columns={"created_by_id"})
 * ,@ORM\Index(name="fk_endereco_updated_by_id_idx"    , columns={"updated_by_id"})
   ,@ORM\Index(name="fk_endereco_logradouro1_idx"      , columns={"id_logradouro"})
 * })
 * @ORM\Entity(repositoryClass="\Adm\Entity\Repository\EnderecoRepository")
 * @ORM\HasLifecycleCallbacks
 * @author Allan Davini
 */
class Endereco extends \Application\Entity\AbstractEntity {

    /**
     * @var integer
     *
     * @ORM\Column(name="id_endereco", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idEndereco;

    /**
     * @var string
     *
     * @ORM\Column(name="numero", type="string", length=20, nullable=true)
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="complemento", type="string", length=100, nullable=true)
     */
    private $complemento;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=10, nullable=true, options={"default" = "ATIVO"})
     */
    private $status = 'ATIVO';

    /**
     * @var \Adm\Entity\Logradouro
     *
     * @ORM\ManyToOne(targetEntity="\Adm\Entity\Logradouro")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_logradouro", referencedColumnName="id_logradouro")
     * })
     */
    private $logradouro;

    /**
     * @var string
     *
     * @ORM\Column(name="full_endereco", type="string", length=250, nullable=true, options={"default" = ""})
     */
    private $fullEndereco='';

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

    public function __construct(array $options = array()) {
        parent::__construct($options);
        $this->createdAt = new \DateTime("now");
    }

    /**
     * 
     * @return String
     */
    public function getId() {
        return $this->getIdEndereco();
    }

    /**
     * Metodo padrão para setar o campo key da tabela
     * @param string $id
     * @return \Application\Entity\AppMenu
     */
    public function setId($id) {
        return $this->setIdEndereco($id);
    }

    /**
     * 
     * @return integer
     */
    public function getIdEndereco() {
        return $this->idEndereco;
    }

    /**
     * 
     * @return string
     */
    public function getNumero() {
        return $this->numero;
    }

    /**
     * 
     * @return string
     */
    public function getComplemento() {
        return $this->complemento;
    }

    /**
     * 
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 27-09-2016           
     * @param \Adm\Entity\Logradouro $logradouro
     * @return \Adm\Entity\Endereco
     */
    public function setLogradouro(\Adm\Entity\Logradouro $logradouro = NULL) {
        $this->logradouro = $logradouro;
        return $this;
    }
        
    /**
     * Retorna a entidade  ou um atributo se for passado por parametro.
     * Caso passe o parametro e a relação for a relação for null será retornado string '-'           
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 27-09-2016   
     * @param string $get  Nome do method get a ser retornado da relação.
     * @param array  $params Parametro(s) a serem usado neste  get da relação.
     * @return \Adm\Entity\Logradouro
     */
    public function getLogradouro($get='', Array $params=[]) {
        if(empty($get)){
            return $this->logradouro;
        }
        if(is_null($this->logradouro)){
            return '-';
        }
        $method = 'get' . ucfirst($get);
        if(empty($params)){
            return $this->logradouro->$method();
        }
        return call_user_func_array([$this->logradouro, $method], $params);
            
    }
    
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 17-11-2016           
     * @param string $fullEndereco
     * @return \Adm\Entity\Endereco
     */
    public function setFullEndereco($fullEndereco) {
        $this->fullEndereco = $fullEndereco;
        return $this;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 17-11-2016           
     * @return string
     */
    public function getFullEndereco() {
        return $this->fullEndereco;
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
     * 
     * @param integer $idEndereco
     * @return \Adm\Entity\Endereco
     */
    public function setIdEndereco($idEndereco) {
        $this->idEndereco = $idEndereco;
        return $this;
    }

    /**
     * 
     * @param string $numero
     * @return \Adm\Entity\Endereco
     */
    public function setNumero($numero) {
        $this->numero = $numero;
        return $this;
    }

    /**
     * 
     * @param string $complemento
     * @return \Adm\Entity\Endereco
     */
    public function setComplemento($complemento) {
        $this->complemento = $complemento;
        return $this;
    }

    /**
     * 
     * @param string $status
     * @return \Adm\Entity\Endereco
     */
    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }
   
    /**
     * Seta a data de desenvolvimento desta entidade
     * @param \DateTime $createdAt
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     */
    public function setCreatedAt() {
        $this->createdAt = new \DateTime("now");
        return $this;
    }

    /**
     * Seta data de atualizacao da entidade
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     */
    public function setUpdatedAt() {
        $this->updatedAt = new \DateTime("now");
        return $this;
    }
    
    /**
     * 
     * @param type $string
     * @return string
     */
    public function format($string = "l, n - b, c - e - k") {
        if(empty($string) or is_null($this->getLogradouro())) {
            return "-";
        }
        
        $result = str_split($string);
        $escape = FALSE; 
        
        for($i = 0; $i < sizeof($result); $i++) {
            
            if("%" == $result[$i]) {
                $escape     = !$escape;
                $result[$i] = "";
            }
            
            if($escape) {
                continue;
            }
            
             "l" == $result[$i] && $result[$i] = "{$this->getLogradouro('tipoLogradouro',['tipo'])} {$this->getLogradouro('nomeLogradouro')}";
             "n" == $result[$i] && $result[$i] = $this->getNumero();
             "m" == $result[$i] && $result[$i] = $this->getComplemento();
             "b" == $result[$i] && $result[$i] = $this->getLogradouro('bairro', ['nomeBairro']);
             "c" == $result[$i] && $result[$i] = $this->getLogradouro('bairro', ['cidade', ['nomeCidade']]);
             "e" == $result[$i] && $result[$i] = $this->getLogradouro('bairro', ['cidade', ['estado',['uf']]]);
             "E" == $result[$i] && $result[$i] = $this->getLogradouro('bairro', ['cidade', ['estado',['nomeEstado']]]);
             "p" == $result[$i] && $result[$i] = $this->getLogradouro('bairro', ['cidade', ['estado',['pais',['sigla']]]]);
             "P" == $result[$i] && $result[$i] = $this->getLogradouro('bairro', ['cidade', ['estado',['pais',['nomePais']]]]);
             "k" == $result[$i] && $result[$i] = $this->getLogradouro('cep');
        }
        
        return implode('', $result);
    }

    public function __toString() {
        return $this->format();
    }
    
    /**
     * @deprecated 21-12-2016
     * Utilizar a funcao format
     * @return string
     */
    public function exibeEnderecoSimples() {
        return $this->format('l, n');
    }
    
    /**
     * @deprecated 21-12-2016
     * Utilizar a funcao format
     * @return string
     */
    public function enderecoFormatado() {
        return $this->format('l, n - b, c - e (%cep% k)');
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
