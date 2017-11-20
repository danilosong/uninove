<?php

namespace Application\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * TableConfigPersonal
 * tabela aux de configuracao
 * Faz a ligação entre as colunas disponiveis para acesso com usuaou ou papel no sistema
 *
 * @ORM\Table(name="app_table_config_personal", 
 *      indexes={@ORM\Index(name="fk_app_table_config_personal_table_config_idx"      , columns={"table_config_id"})},
 *      indexes={@ORM\Index(name="fk_app_table_config_personal_table_config_colun_idx", columns={"table_config_colun_id"})},
 *      indexes={@ORM\Index(name="fk_app_table_config_personal_usuario_id_idx"        , columns={"usuario_id"})},
 *      indexes={@ORM\Index(name="fk_app_table_config_personal_role_id_idx"           , columns={"role_id"})},
 *      indexes={@ORM\Index(name="fk_app_table_config_personal_created_by_id_idx"     , columns={"created_by_id"})},
 *      indexes={@ORM\Index(name="fk_app_table_config_personal_updated_by_id_idx"     , columns={"updated_by_id"})}
 * )
 * @ORM\Entity(repositoryClass="\Application\Entity\Repository\TableConfigPersonalRepository")
 * @author Paulo Watakabe <watakabe05@gmail.com>
 * @since 10-07-2016     
 */
class TableConfigPersonal extends AbstractEntity
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
     * @var \Application\Entity\TableConfig
     *
     * @ORM\ManyToOne(targetEntity="\Application\Entity\TableConfig")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="table_config_id", referencedColumnName="id")
     * })
     */
    protected $tableConfig;

    /**
     * @var \Application\Entity\TableConfigColun
     *
     * @ORM\ManyToOne(targetEntity="\Application\Entity\TableConfigColun")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="table_config_colun_id", referencedColumnName="id")
     * })
     */
    protected $tableConfigColun;


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
     *   @ORM\JoinColumn(name="usuario_id", referencedColumnName="id_usuario")
     * })
     */
    protected $usuario;
    
    /**
     * @var \Application\Entity\AppRole
     *
     * @ORM\ManyToOne(targetEntity="\Application\Entity\AppRole")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="role_id", referencedColumnName="id_role")
     * })
     */
    protected $role;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="seq", type="integer", nullable=false)
     */
    private $seq;

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
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 10-07-2016  
     * @param array $options
     */
    public function __construct(array $options = array()) {
        $this->setCreatedAt();
        parent::__construct($options);
    }
    
    /**
     * @ORM\PreUpdate
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 10-07-2016  
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
        $result = parent::toArrayNew($limite);
        
        /**
         * @todo Tive que implementar as linhas contidas neste IF para contornar
         * problema de preenchimento (que deveria ser feito através do método
         * toArrayNew mas por algum motivo, isso não está acontecendo)
         * @author Danilo Dorotheu <danilo.dorotheu@live.com>
         * @since 15-07-2016
         */
        if($this->getUsuario()){
            $result['usuario[nomeUsuario]'] = $this->getUsuario()->getNome();
            $result['usuario[idUsuario]'] = $this->getUsuario()->getId();
        }
        
        return $result;
    }
    
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 10-07-2016           
     * @param integer $id
     * @return \Application\Entity\TableConfigPersonal
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 10-07-2016           
     * @return integer
     */
    public function getId() {
        return $this->id;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 10-07-2016           
     * @param \Application\Entity\TableConfig $tableConfig
     * @return \Application\Entity\TableConfigPersonal
     */
    public function setTableConfig(\Application\Entity\TableConfig $tableConfig = NULL) {
        $this->tableConfig = $tableConfig;
        return $this;
    }
        
    /**
     * Retorna a entidade  ou um atributo se for passado por parametro.
     * Caso passe o parametro e a relação for a relação for null será retornado string '-'           
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 10-07-2016   
     * @param string $get  Nome do method get a ser retornado da relação.
     * @param array  $params Parametro(s) a serem usado neste  get da relação.
     * @return \Application\Entity\TableConfig
     */
    public function getTableConfig($get='', Array $params=[]) {
        if(empty($get)){
            return $this->tableConfig;
        }
        if(is_null($this->tableConfig)){
            return '-';
        }
        $method = 'get' . ucfirst($get);
        if(empty($params)){
            return $this->tableConfig->$method();
        }
        return call_user_func_array([$this->tableConfig, $method], $params);
            
    }
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 10-07-2016           
     * @param \Application\Entity\TableConfigColun $tableConfigColun
     * @return \Application\Entity\TableConfigPersonal
     */
    public function setTableConfigColun(\Application\Entity\TableConfigColun $tableConfigColun = NULL) {
        $this->tableConfigColun = $tableConfigColun;
        return $this;
    }
        
    /**
     * Retorna a entidade  ou um atributo se for passado por parametro.
     * Caso passe o parametro e a relação for a relação for null será retornado string '-'           
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 10-07-2016   
     * @param string $get  Nome do method get a ser retornado da relação.
     * @param array  $params Parametro(s) a serem usado neste  get da relação.
     * @return \Application\Entity\TableConfigColun
     */
    public function getTableConfigColun($get='', Array $params=[]) {
        if(empty($get)){
            return $this->tableConfigColun;
        }
        if(is_null($this->tableConfigColun)){
            return '-';
        }
        $method = 'get' . ucfirst($get);
        if(empty($params)){
            return $this->tableConfigColun->$method();
        }
        return call_user_func_array([$this->tableConfigColun, $method], $params);
            
    }
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 10-07-2016           
     * @param \Application\Entity\Usuario $createdBy
     * @return \Application\Entity\TableConfigPersonal
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
     * @since 10-07-2016   
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
     * @since 10-07-2016           
     * @param \Application\Entity\Usuario $usuario
     * @return \Application\Entity\TableConfigPersonal
     */
    public function setUsuario(\Application\Entity\Usuario $usuario = NULL) {
        $this->usuario = $usuario;
        return $this;
    }
        
    /**
     * Retorna a entidade  ou um atributo se for passado por parametro.
     * Caso passe o parametro e a relação for a relação for null será retornado string '-'           
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 10-07-2016   
     * @param string $get  Nome do method get a ser retornado da relação.
     * @param array  $params Parametro(s) a serem usado neste  get da relação.
     * @return \Application\Entity\Usuario
     */
    public function getUsuario($get='', Array $params=[]) {
        if(empty($get)){
            return $this->usuario;
        }
        if(is_null($this->usuario)){
            return '-';
        }
        $method = 'get' . ucfirst($get);
        if(empty($params)){
            return $this->usuario->$method();
        }
        return call_user_func_array([$this->usuario, $method], $params);
            
    }
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 10-07-2016           
     * @param \Application\Entity\AppRole $role
     * @return \Application\Entity\TableConfigPersonal
     */
    public function setRole(\Application\Entity\AppRole $role = NULL) {
        $this->role = $role;
        return $this;
    }
        
    /**
     * Retorna a entidade  ou um atributo se for passado por parametro.
     * Caso passe o parametro e a relação for a relação for null será retornado string '-'           
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 10-07-2016   
     * @param string $get  Nome do method get a ser retornado da relação.
     * @param array  $params Parametro(s) a serem usado neste  get da relação.
     * @return \Application\Entity\AppRole
     */
    public function getRole($get='', Array $params=[]) {
        if(empty($get)){
            return $this->role;
        }
        if(is_null($this->role)){
            return '-';
        }
        $method = 'get' . ucfirst($get);
        if(empty($params)){
            return $this->role->$method();
        }
        return call_user_func_array([$this->role, $method], $params);
            
    }
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 10-07-2016           
     * @param integer $seq
     * @return \Application\Entity\TableConfigPersonal
     */
    public function setSeq($seq) {
        $this->seq = $seq;
        return $this;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 10-07-2016           
     * @return integer
     */
    public function getSeq() {
        return $this->seq;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 10-07-2016           
     * @param \Application\Entity\Usuario $updatedBy
     * @return \Application\Entity\TableConfigPersonal
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
     * @since 10-07-2016   
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
     * @since 10-07-2016           
     * @param \DateTime | string $createdAt
     * @return \Application\Entity\TableConfigPersonal
     */
    public function setCreatedAt($createdAt = '') {
        $this->createdAt = $this->strToDate($createdAt);
        return $this;
    }       
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 10-07-2016   
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
     * @since 10-07-2016           
     * @param \DateTime | string $updatedAt
     * @return \Application\Entity\TableConfigPersonal
     */
    public function setUpdatedAt($updatedAt = '') {
        $this->updatedAt = $this->strToDate($updatedAt);
        return $this;
    }       
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 10-07-2016   
     * @param boolean | string $obj  true  retorna o objeto DateTime, falso formatação d/m/Y,  string = 'full' formatação d/m/Y H:i:s ou a formatação especificada na string
     * @param boolean | string $full falso formatação definida pelo param $obj se string formatação especificada no conteudo
     * @return \DateTime | string
     */
    public function getUpdatedAt($obj = FALSE, $full = FALSE) {
        return $this->dateToStr($this->updatedAt, $full, $obj);
    }
}

