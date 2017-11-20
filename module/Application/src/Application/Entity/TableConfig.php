<?php

namespace Application\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * TableConfig
 * tabela de configuracao
 * Guarda configuraçao da tabela para ser personalizada no index
 *
 * @ORM\Table(name="app_table_config", 
 *      indexes={@ORM\Index(name="fk_app_table_config_entity_path_idx", columns={"entity_path"})},
 *      indexes={@ORM\Index(name="fk_app_table_config_created_by_id_idx", columns={"created_by_id"})},
 *      indexes={@ORM\Index(name="fk_app_table_config_updated_by_id_idx", columns={"updated_by_id"})}
 * )
 * @ORM\Entity(repositoryClass="\Application\Entity\Repository\TableConfigRepository")
 * @author Paulo Watakabe <watakabe05@gmail.com>
 * @since 29-05-2016     
 */
class TableConfig extends AbstractEntity
{
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
     * @ORM\Column(name="entity_path", type="string", length=200, nullable=false)
     */
    private $entityPath;

    /**
     * @var string
     *
     * @ORM\Column(name="caption", type="string", length=200, nullable=true)
     */
    private $caption;

    /**
     * @var string
     *
     * @ORM\Column(name="tr_option", type="string", length=150, nullable=true)
     */
    private $trOption;

    /**
     * @var string
     *
     * @ORM\Column(name="td_option", type="string", length=150, nullable=true)
     */
    private $tdOption;

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

    /**
     * Array com os dados para fazer os sets Automaticamente na entidade
     * Set a data de Criação da entidade
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 29-05-2016  
     * @param array $data
     */
    public function __construct($data = []) {
        $this->setCreatedAt();
        parent::__construct($data);
    } 
    
    /**
     * @ORM\PreUpdate
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 29-05-2016  
     * @param \Doctrine\ORM\EntityManager $manager
     */
    public function beforeUpdate($manager) {
        $this->setUpdatedAt();
    }
    
    /**
     * Alias para o toArrayNew 
     * Extrai todos os metodos get para um array de dados.
     * Caso encontre um get que retorne um objeto ira tentar em converte-lo para um array de dados 
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 10-07-2016  
     * @param integer $limite Indica o numero de galhos que deverá percorrer dentro da arvore do array
     * @param string $prefix Não usar existe apenas para compatibilidade e evitar os warnings do PHP7
     */
    public function toArray($limite = 0, $prefix = '') {
        return parent::toArrayNew($limite);
    }
    
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 03-06-2016           
     * @param integer $id
     * @return \Application\Entity\TableConfig
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 03-06-2016           
     * @return integer
     */
    public function getId() {
        return $this->id;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 03-06-2016           
     * @param string $entityPath
     * @return \Application\Entity\TableConfig
     */
    public function setEntityPath($entityPath) {
        $this->entityPath = $entityPath;
        return $this;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 03-06-2016           
     * @return string
     */
    public function getEntityPath() {
        return $this->entityPath;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 03-06-2016           
     * @param string $caption
     * @return \Application\Entity\TableConfig
     */
    public function setCaption($caption) {
        $this->caption = $caption;
        return $this;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 03-06-2016           
     * @return string
     */
    public function getCaption() {
        return $this->caption;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 03-06-2016           
     * @param string $trOption
     * @return \Application\Entity\TableConfig
     */
    public function setTrOption($trOption) {
        $this->trOption = $trOption;
        return $this;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 03-06-2016           
     * @return string
     */
    public function getTrOption() {
        return $this->trOption;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 03-06-2016           
     * @param string $tdOption
     * @return \Application\Entity\TableConfig
     */
    public function setTdOption($tdOption) {
        $this->tdOption = $tdOption;
        return $this;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 03-06-2016           
     * @return string
     */
    public function getTdOption() {
        return $this->tdOption;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 03-06-2016           
     * @param \Application\Entity\Usuario $createdBy
     * @return \Application\Entity\TableConfig
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
     * @since 03-06-2016   
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
        $method = 'get' . ucfirst($get);
        if(empty($params)){
            return $this->createdBy->$method();
        }
        return call_user_func_array([$this->createdBy, $method], $params);
            
    }
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 03-06-2016           
     * @param \Application\Entity\Usuario $updatedBy
     * @return \Application\Entity\TableConfig
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
     * @since 03-06-2016   
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
        $method = 'get' . ucfirst($get);
        if(empty($params)){
            return $this->updatedBy->$method();
        }
        return call_user_func_array([$this->updatedBy, $method], $params);
            
    }
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 03-06-2016           
     * @param \DateTime | string $createdAt
     * @return \Application\Entity\TableConfig
     */
    public function setCreatedAt($createdAt = '') {
        $this->createdAt = $this->strToDate($createdAt);
        return $this;
    }       
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 03-06-2016   
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
     * @since 03-06-2016           
     * @param \DateTime | string $updatedAt
     * @return \Application\Entity\TableConfig
     */
    public function setUpdatedAt($updatedAt = '') {
        $this->updatedAt = $this->strToDate($updatedAt);
        return $this;
    }       
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 03-06-2016   
     * @param boolean | string $obj  true  retorna o objeto DateTime, falso formatação d/m/Y,  string = 'full' formatação d/m/Y H:i:s ou a formatação especificada na string
     * @param boolean | string $full falso formatação definida pelo param $obj se string formatação especificada no conteudo
     * @return \DateTime | string
     */
    public function getUpdatedAt($obj = FALSE, $full = FALSE) {
        return $this->dateToStr($this->updatedAt, $full, $obj);
    }
           
}

