<?php

namespace Adm\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bairro
 *
 * @ORM\Table(name="tcmed_bairro", indexes={
 *      @ORM\Index(name="fk_bairro_cidade1_idx", columns={"id_cidade"})
 *      ,@ORM\Index(name="fk_tcmed_estado_created_by_id_idx"    , columns={"created_by_id"})
 *      ,@ORM\Index(name="fk_tcmed_estado_updated_by_id_idx"    , columns={"updated_by_id"})
 * })
 * @ORM\Entity(repositoryClass="\Tcmed\Entity\Repository\BairroRepository")
 * @ORM\HasLifecycleCallbacks
 * @author Allan Davini
 */
class Bairro extends \Application\Entity\AbstractEntity {

    /**
     * @var integer
     *
     * @ORM\Column(name="id_bairro", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idBairro;

    /**
     * @var string
     *
     * @ORM\Column(name="nome_bairro", type="string", length=100, nullable=true)
     */
    private $nomeBairro;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=10, nullable=false, options={"default" = "ATIVO"})
     */
    private $status = 'ATIVO';

    /**
     * @var \Tcmed\Entity\Cidade
     *
     * @ORM\ManyToOne(targetEntity="Cidade")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_cidade", referencedColumnName="id_cidade")
     * })
     */
    private $cidade;

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
     * @return String
     */
    public function getId() {
        return $this->getIdBairro();
    }

    /**
     * Metodo padrão para setar o campo key da tabela
     * @param string $id
     * @return \Application\Entity\AppMenu
     */
    public function setId($id) {
        return $this->setIdBairro($id);
    }

    /**
     * 
     * @return integer
     */
    public function getIdBairro() {
        return $this->idBairro;
    }

    /**
     * 
     * @return string
     */
    public function getNomeBairro() {
        return $this->nomeBairro;
    }

    /**
     * 
     * @return string
     */
    public function getStatus() {
        return $this->status;
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
     * @param integer $idBairro
     * @return \Tcmed\Entity\Bairro
     */
    public function setIdBairro($idBairro) {
        $this->idBairro = $idBairro;
        return $this;
    }

    /**
     * 
     * @param string $nomeBairro
     * @return \Tcmed\Entity\Bairro
     */
    public function setNomeBairro($nomeBairro) {
        $this->nomeBairro = $nomeBairro;
        return $this;
    }

    /**
     * 
     * @param string $status
     * @return \Tcmed\Entity\Bairro
     */
    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }

    /**
     * Seta a data de desenvolvimento desta entidade
     * @param \DateTime $createdAt
     * @return \Tcmed\Entity\Bairro
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     */
    public function setCreatedAt() {
        $this->createdAt = new \DateTime("now");
        return $this;
    }

    /**
     * Seta data de atualizacao da entidade
     * @return \Tcmed\Entity\Bairro
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     */
    public function setUpdatedAt() {
        $this->updatedAt = new \DateTime("now");
        return $this;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 11-11-2016           
     * @param \Application\Entity\Usuario $createdBy
     * @return \Tcmed\Entity\Bairro
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
     * @return \Tcmed\Entity\Bairro
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

    public function __toString() {
        return $this->getNomeBairro();
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
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 10-11-2016           
     * @param \Tcmed\Entity\Cidade $cidade
     * @return \Tcmed\Entity\Bairro
     */
    public function setCidade(\Tcmed\Entity\Cidade $cidade = NULL) {
        $this->cidade = $cidade;
        return $this;
    }
        
    /**
     * Retorna a entidade  ou um atributo se for passado por parametro.
     * Caso passe o parametro e a relação for a relação for null será retornado string '-'           
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 10-11-2016   
     * @param string $get  Nome do method get a ser retornado da relação.
     * @param array  $params Parametro(s) a serem usado neste  get da relação.
     * @return \Tcmed\Entity\Cidade
     */
    public function getCidade($get='', Array $params=[]) {
        if(empty($get)){
            return $this->cidade;
        }
        if(is_null($this->cidade)){
            return '-';
        }
        $method = !method_exists($this->cidade, $get) ? 'get' . ucfirst($get) : $get;
        if(empty($params)){
            return $this->cidade->$method();
        }
        return call_user_func_array([$this->cidade, $method], $params);
            
    }
    
}
