<?php

namespace Adm\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Logradouro
 *
 * @ORM\Table(name="adm_logradouro", indexes={
 *       @ORM\Index(name="fk_logradouro_created_by_id_idx"    , columns={"created_by_id"})
 *      ,@ORM\Index(name="fk_logradouro_updated_by_id_idx"    , columns={"updated_by_id"})
 *      ,@ORM\Index(name="fk_logradouro_bairro1_idx"          , columns={"id_bairro"})
 *      ,@ORM\Index(name="fk_logradouro_tipoLogradouro1_idx"  , columns={"id_tipo_logradouro"})
 * })
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="\Adm\Entity\Repository\LogradouroRepository")
 * @ORM\HasLifecycleCallbacks
 * @author Danilo Dorotheu <danilo.dorotheu@live.com>
 */
class Logradouro extends \Application\Entity\AbstractEntity {

    /**
     * @var integer
     *
     * @ORM\Column(name="id_logradouro", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idLogradouro;

    /**
     * @var string
     *
     * @ORM\Column(name="nome_logradouro", type="string", length=60, nullable=true)
     */
    private $nomeLogradouro;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=10, nullable=false, options={"default" = "ATIVO"})
     */
    private $status = 'ATIVO';

    /**
     * @var string
     *
     * @ORM\Column(name="cep", type="string", length=10, nullable=true)
     */
    private $cep;

    /**
     * @var string
     *
     * @ORM\Column(name="compl", type="string", length=100, nullable=true)
     */
    private $compl;
    
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
     * @var \Adm\Entity\Bairro
     *
     * @ORM\ManyToOne(targetEntity="Bairro")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_bairro", referencedColumnName="id_bairro")
     * })
     */
    private $bairro;

    /**
     * @var \Adm\Entity\TipoLogradouro
     *
     * @ORM\ManyToOne(targetEntity="TipoLogradouro")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_tipo_logradouro", referencedColumnName="id_tipo_logradouro")
     * })
     */
    private $tipoLogradouro;

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
     * Retorna o ID do Logradouro
     * @return integer
     */
    public function getIdLogradouro() {
        return $this->idLogradouro;
    }

    /**
     * (Alias) Retorna o ID do Logradouro
     * @return type
     */
    public function getId() {
        return $this->idLogradouro;
    }

    /**
     * Retorna o nome do Logradouro
     * @return string
     */
    public function getNomeLogradouro() {
        return $this->nomeLogradouro;
    }

    /**
     * Retorna o status do Logradouro
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Retorna o complemento do Logradouro
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 18-11-2016 
     * @return string $compl
     */
    public function getCompl() {
        return $this->compl;
    }

    /**
     * Retorna o CEP do Logradouro
     * @return string
     */
    public function getCep($mask = TRUE) {
         if($mask){
             return $this->mask("#####-###", $this->cep);
         }else{
             return $this->cep;
         }         
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
     * Retorna a entidade  ou um atributo se for passado por parametro.
     * Caso passe o parametro e a relação for a relação for null será retornado string '-'           
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @version 2.0  
     * @since 04-10-2016   
     * @param string $get  Nome do method get a ser retornado da relação.
     * @param array  $params Parametro(s) a serem usado neste  get da relação.
     * @return \Adm\Entity\Bairro
     */
    public function getBairro($get = '', Array $params = []) {
        if (empty($get)) {
            return $this->bairro;
        }
        if (is_null($this->bairro)) {
            return '-';
        }
        $method = 'get' . ucfirst($get);
        if (empty($params)) {
            return $this->bairro->$method();
        }
        return call_user_func_array([$this->bairro, $method], $params);
    }

    /**
     * Retorna a entidade  ou um atributo se for passado por parametro.
     * Caso passe o parametro e a relação for a relação for null será retornado string '-'           
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @version 1.0  
     * @since 24-11-2016   
     * @param string $get  Nome do method get a ser retornado da relação.
     * @param array  $params Parametro(s) a serem usado neste  get da relação.
     * @return \Adm\Entity\TipoLogradouro
     */
    public function getTipoLogradouro($get='', Array $params=[]) {
        if(empty($get)){
            return $this->tipoLogradouro;
        }
        if(is_null($this->tipoLogradouro)){
            return '-';
        }
        $method = !method_exists($this->tipoLogradouro, $get) ? 'get' . ucfirst($get) : $get;
        if(empty($params)){
            return $this->tipoLogradouro->$method();
        }
        return call_user_func_array([$this->tipoLogradouro, $method], $params);
            
    }

    /**
     * Seta o ID do Logradouro
     * @param integer $idLogradouro
     * @return \Adm\Entity\Logradouro
     */
    public function setIdLogradouro($idLogradouro) {
        $this->idLogradouro = $idLogradouro;
        return $this;
    }

    /**
     * (Alias) Retorna o ID do Logradouro
     * @param integer $idLogradouro
     * @return \Adm\Entity\Logradouro
     */
    public function setId($idLogradouro) {
        $this->setIdLogradouro($idLogradouro);
        return $this;
    }

    /**
     * Seta o nome do Logradouro
     * @param string $nomeLogradouro
     * @return \Adm\Entity\Logradouro
     */
    public function setNomeLogradouro($nomeLogradouro) {
        $this->nomeLogradouro = $nomeLogradouro;
        return $this;
    }

    /**
     * Seta o Status do Logradouro
     * @param string $status
     * @return \Adm\Entity\Logradouro
     */
    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }

    /**
     * Seta o CEP do Logradouro
     * @param string $cep
     * @return \Adm\Entity\Logradouro
     */
    public function setCep($cep) {
        $this->cep = $this->clean($cep, TRUE, 8);
        return $this;
    }

    /**
     * Seta o complemento do Logradouro
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 18-11-2016 
     * @param string $compl
     * @return \Adm\Entity\Logradouro
     */
    public function setCompl($compl) {
        $this->compl = $compl;
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
     * 
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @version 2.0  
     * @since 04-10-2016           
     * @param \Adm\Entity\Bairro $bairro
     * @return \Adm\Entity\Logradouro
     */
    public function setBairro(\Adm\Entity\Bairro $bairro = NULL) {
        $this->bairro = $bairro;
        return $this;
    }

    /**
     * Seta o Tipo do Logradouro
     * @param \Adm\Entity\TipoLogradouro $tipoLogradouro
     * @return \Adm\Entity\Logradouro
     */
    public function setTipoLogradouro(\Adm\Entity\TipoLogradouro $tipoLogradouro) {
        $this->tipoLogradouro = $tipoLogradouro;
        return $this;
    }

//    public function __toString() {
//        return $this->getTipoLogradouro() . " " . $this->getNomeLogradouro();
//    }

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
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 19-06-2017           
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
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 19-06-2017   
     * @param string $method  Nome do method get a ser retornado da relação.
     * @param array  $params Parametro(s) a serem usado neste  get da relação.
     * @return \Application\Entity\Usuario
     */
    public function getCreatedBy($method='', Array $params=[]) {
        return $this->relationGet($this->createdBy, $method, $params);            
    }
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 19-06-2017           
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
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 19-06-2017   
     * @param string $method  Nome do method get a ser retornado da relação.
     * @param array  $params Parametro(s) a serem usado neste  get da relação.
     * @return \Application\Entity\Usuario
     */
    public function getUpdatedBy($method='', Array $params=[]) {
        return $this->relationGet($this->updatedBy, $method, $params);            
    }

}
