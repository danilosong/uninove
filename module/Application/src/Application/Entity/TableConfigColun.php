<?php

namespace Application\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * TableConfigColun
 * tabela de configuracao das colunas
 * Guarda configuraçao das colunas da tabela para ser personalizada no index
 *
 * @ORM\Table(name="app_table_config_colun", 
 *      indexes={@ORM\Index(name="fk_app_table_config_colun_table_config_idx", columns={"table_config_id"})},
 *      indexes={@ORM\Index(name="fk_app_table_config_colun_created_by_id_idx", columns={"created_by_id"})},
 *      indexes={@ORM\Index(name="fk_app_table_config_colun_updated_by_id_idx", columns={"updated_by_id"})}
 * )
 * @ORM\Entity(repositoryClass="\Application\Entity\Repository\TableConfigColunRepository")
 * @author Paulo Watakabe <watakabe05@gmail.com>
 * @since 10-07-2016     
 */
class TableConfigColun extends AbstractEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

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
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=250, nullable=false)
     */
    protected $label;

    /**
     * @var string
     *
     * @ORM\Column(name="method", type="string", length=250, nullable=false)
     */
    protected $method;

    /**
     * @var string
     *
     * @ORM\Column(name="order_res", type="string", length=250, nullable=true)
     */
    protected $order;

    /**
     * @var array
     *
     * @ORM\Column(name="param", type="text", length=65535, nullable=true)
     */
    protected $param;

    /**
     * @var string
     *
     * @ORM\Column(name="default_res", type="string", length=1, nullable=false)
     */
    protected $default = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="tr_line", type="string", length=200, nullable=true)
     */
    protected $trLine;

    /**
     * @var string
     *
     * @ORM\Column(name="th_css", type="string", length=200, nullable=true)
     */
    protected $thCss;

    /**
     * @var string
     *
     * @ORM\Column(name="th_js", type="string", length=200, nullable=true)
     */
    protected $thJs;

    /**
     * @var string
     *
     * @ORM\Column(name="th_option", type="string", length=200, nullable=true)
     */
    protected $thOption;

    /**
     * @var string
     *
     * @ORM\Column(name="td_line", type="string", length=200, nullable=true)
     */
    protected $tdLine;

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
        return parent::toArrayNew($limite);
    }
    
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 10-07-2016           
     * @param integer $id
     * @return \Application\Entity\TableConfigColun
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
     * @return \Application\Entity\TableConfigColun
     */
    public function setTableConfig(\Application\Entity\TableConfig $tableConfig) {
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
     * @param string $label
     * @return \Application\Entity\TableConfigColun
     */
    public function setLabel($label) {
        $this->label = $label;
        return $this;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 10-07-2016           
     * @return string
     */
    public function getLabel() {
        return $this->label;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 10-07-2016           
     * @param string $method
     * @return \Application\Entity\TableConfigColun
     */
    public function setMethod($method) {
        $this->method = $method;
        return $this;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 10-07-2016           
     * @return string
     */
    public function getMethod() {
        return $this->method;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 10-07-2016           
     * @param string $order
     * @return \Application\Entity\TableConfigColun
     */
    public function setOrder($order) {
        $this->order = $order;
        return $this;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 10-07-2016           
     * @return string
     */
    public function getOrder() {
        return $this->order;
    }
        
    /**
     * Retorna um array ou string caso estiver sem o formato do array
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 12-07-2016           
     * @param array | string $param Default é um array vazio
     * @return \Application\Entity\TableConfigColun
     */
    public function setParam($param = []) {
        $this->param = $this->arrayToStr($param);
        return $this;
    }       
        
    /**
     * Retorna um array que foi gravado no BD.
     * Se a gravação não estiver no formato de array retorna string          
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 12-07-2016           
     * @return array | string
     */
    public function getParam() {
        return $this->strToArray($this->param);
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 12-07-2016           
     * @param string $default
     * @return \Application\Entity\TableConfigColun
     */
    public function setDefault($default) {
        $this->default = $default;
        return $this;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 12-07-2016           
     * @return string
     */
    public function getDefault() {
        return $this->default;
    }
    
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 10-07-2016           
     * @param string $trLine
     * @return \Application\Entity\TableConfigColun
     */
    public function setTrLine($trLine) {
        $this->trLine = $trLine;
        return $this;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 10-07-2016           
     * @return string
     */
    public function getTrLine() {
        return $this->trLine;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 10-07-2016           
     * @param string $thCss
     * @return \Application\Entity\TableConfigColun
     */
    public function setThCss($thCss) {
        $this->thCss = $thCss;
        return $this;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 10-07-2016           
     * @return string
     */
    public function getThCss() {
        return $this->thCss;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 10-07-2016           
     * @param string $thJs
     * @return \Application\Entity\TableConfigColun
     */
    public function setThJs($thJs) {
        $this->thJs = $thJs;
        return $this;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 10-07-2016           
     * @return string
     */
    public function getThJs() {
        return $this->thJs;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 10-07-2016           
     * @param string $thOption
     * @return \Application\Entity\TableConfigColun
     */
    public function setThOption($thOption) {
        $this->thOption = $thOption;
        return $this;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 10-07-2016           
     * @return string
     */
    public function getThOption() {
        return $this->thOption;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 10-07-2016           
     * @param string $tdLine
     * @return \Application\Entity\TableConfigColun
     */
    public function setTdLine($tdLine) {
        $this->tdLine = $tdLine;
        return $this;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 10-07-2016           
     * @return string
     */
    public function getTdLine() {
        return $this->tdLine;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 10-07-2016           
     * @param \Application\Entity\Usuario $createdBy
     * @return \Application\Entity\TableConfigColun
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
     * @param \Application\Entity\Usuario $updatedBy
     * @return \Application\Entity\TableConfigColun
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
     * @return \Application\Entity\TableConfigColun
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
     * @return \Application\Entity\TableConfigColun
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

