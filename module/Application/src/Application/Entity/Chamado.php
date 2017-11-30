<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

/**
 * Chamado
 * @author Danilo Song <danilosong@outlook.com>
 * @since 11-09-2017
 *
 * @ORM\Table(name="app_chamado", indexes={
 *       @ORM\Index(name="fk_app_chamado_created_by_id_idx"    , columns={"created_by_id"})
 *      ,@ORM\Index(name="fk_app_chamado_updated_by_id_idx"    , columns={"updated_by_id"})
 * })
 * @ORM\Entity(repositoryClass="\Application\Entity\Repository\ChamadoRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Chamado extends AbstractEntity
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
     * @var string
     *
     * @ORM\Column(name="titulo", type="string", length=150, nullable=TRUE)
     */
    protected $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="descricao", type="text", length=65535, nullable=TRUE)
     */
    protected $desc;
    
    /**
     * anexo path um array unidimenssional com os seguinte itens cada indice do array.
     *
     * - Caminho do arquivo
     * @var array
     * @ORM\Column(name="anexo_path", type="text", length=65535, nullable=TRUE)
     */
    protected $anexoPath;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="prioridade", type="integer", nullable=TRUE)
     */
    protected $prioridade;
    
    /**
     * categoria é um array multidimenssional com os seguinte itens cada indice do array.
     *
     * -- Tipo do chamado --
     * - erro
     * - ajustes de processo
     * - chamados
     * @var array
     * @ORM\Column(name="categoria", type="text", length=65535, nullable=TRUE)
     */
    protected $categoria;
    
    /**
     * @var string
     *
     * @ORM\Column(name="setor", type="string", length=150, nullable=TRUE)
     */
    protected $setor;
    
    /**
     * copia para é um array multidimenssional com os seguinte itens cada indice do array.
     *
     * -- Lista de chamado --
     * - Para a pessoa que abriu o chamado
     * - Com cópia
     * - Tipo de pessoa
     * @var array
     * @ORM\Column(name="copia_para", type="text", length=65535, nullable=true)
     */
    protected $copiaPara;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="prazo", type="datetime", nullable=true)
     */
    protected $prazo;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="finalizado", type="datetime", nullable=true)
     */
    protected $finalizado;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="situacao", type="integer", nullable=true)
     */
    protected $situacao;

    /**
     * @var timeInt
     *
     * @ORM\Column(name="horas", type="integer", nullable=true)
     */
    protected $horas;

    /**
     * @var timeInt
     *
     * @ORM\Column(name="horas_total", type="integer", nullable=true)
     */
    protected $horasTotal;
    
    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=45, nullable=false, options={"default" = "ATIVO"})
     */
    protected $status = "ATIVO";

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
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="\Application\Entity\ChamadoResposta", mappedBy="chamado", cascade={"persist", "remove"})
     * 
     */
    protected $chamadoRespostas;
    
//    --------------------------------------------------------------------------
    /**
     * @ORM\PreUpdate
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 10/10/2017           
     * @param \Doctrine\ORM\Event\PreUpdateEventArgs $event
     */
    public function beforeUpdate($event) {
        $this->setUpdatedAt();
    }
    
    public function __construct(array $options = array()) {        
        $this->chamadoRespostas = new ArrayCollection();
        parent::__construct($options);
    }
    
    /**
     * Retorna todos os items deste pedido que esta ativo.
     * 
     * @param array $filters
     * @param boolean $first
     * @return \Doctrine\Common\Collections\ArrayCollection Of \Application\Entity\ChamadoResposta
     */
    public function listChamadoRespostas($filters = ['status' => 'ATIVO']) {
        $criteria = Criteria::create();
        foreach ($filters as $key => $filter){
            $criteria->andWhere(Criteria::expr()->eq($key, $filter));
        }
        return $this->chamadoRespostas->matching($criteria);
    }
    
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 11/09/2017           
     * @param integer $id
     * @return \Application\Entity\Chamado
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 11/09/2017           
     * @return integer
     */
    public function getId() {
        return $this->id;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 11/09/2017           
     * @param string $titulo
     * @return \Application\Entity\Chamado
     */
    public function setTitulo($titulo) {
        $this->titulo = $titulo;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 11/09/2017           
     * @return string
     */
    public function getTitulo() {
        return $this->titulo;
    }
        
    /**
     * 
     * @author Danilo Song  <danilosong@outlook.com>
     * @version 1.0  
     * @since 20/09/2017           
     * @param string $desc
     * @return \Application\Entity\Chamado
     */
    public function setDesc($desc) {
        $this->desc = $desc;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song  <danilosong@outlook.com>
     * @version 1.0  
     * @since 20/09/2017           
     * @return string
     */
    public function getDesc() {
        return $this->desc;
    }
        
    /**
     * Retorna um array ou string caso estiver sem o formato do array
                
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 11/09/2017           
     * @param array | string $anexoPath Default é um array vazio
     * @return \Application\Entity\Chamado
     */
    public function setAnexoPath($anexoPath = []) {
        $this->anexoPath = $this->arrayToStr($anexoPath);
        return $this;
    }       
        
    /**
     * Retorna um array que foi gravado no BD.
     * Se a gravação não estiver no formato de array retorna string          
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 11/09/2017           
     * @return array | string
     */
    public function getAnexoPath() {
        return $this->strToArray($this->anexoPath);
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 11/09/2017           
     * @param integer $prioridade
     * @return \Application\Entity\Chamado
     */
    public function setPrioridade($prioridade) {
        $this->prioridade = $prioridade;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 11/09/2017           
     * @return integer
     */
    public function getPrioridade() {
        return $this->prioridade;
    }
        
    /**
     * Retorna um array ou string caso estiver sem o formato do array
                
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 11/09/2017           
     * @param array | string $categoria Default é um array vazio
     * @return \Application\Entity\Chamado
     */
    public function setCategoria($categoria = []) {
        $this->categoria = $this->arrayToStr($categoria);
        return $this;
    }       
        
    /**
     * Retorna um array que foi gravado no BD.
     * Se a gravação não estiver no formato de array retorna string          
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 11/09/2017           
     * @return array | string
     */
    public function getCategoria() {
        return $this->strToArray($this->categoria);
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 11/09/2017           
     * @param string $setor
     * @return \Application\Entity\Chamado
     */
    public function setSetor($setor) {
        $this->setor = $setor;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 11/09/2017           
     * @return string
     */
    public function getSetor() {
        return $this->setor;
    }
        
    /**
     * Retorna um array ou string caso estiver sem o formato do array
                
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 11/09/2017           
     * @param array | string $copiaPara Default é um array vazio
     * @return \Application\Entity\Chamado
     */
    public function setCopiaPara($copiaPara = []) {
        $this->copiaPara = $this->arrayToStr($copiaPara);
        return $this;
    }       
        
    /**
     * Retorna um array que foi gravado no BD.
     * Se a gravação não estiver no formato de array retorna string          
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 11/09/2017           
     * @return array | string
     */
    public function getCopiaPara() {
        return $this->strToArray($this->copiaPara);
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 11/09/2017           
     * @param datetime $prazo
     * @return \Application\Entity\Chamado
     */
    public function setPrazo($prazo = "") {
        $this->prazo = $this->strToDate($prazo);
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 11/09/2017           
     * @param boolean | string $obj  true  retorna o objeto DateTime, falso formatação d/m/Y,  string = 'full' formatação d/m/Y H:i:s ou a formatação especificada na string
     * @param boolean | string $full falso formatação definida pelo param $obj se string formatação especificada no conteudo
     * @return \DateTime | string
     */
    public function getPrazo($obj = FALSE, $full = FALSE) {
        return $this->dateToStr($this->prazo, $full, $obj);
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 11/09/2017           
     * @param datetime $finalizado
     * @return \Application\Entity\Chamado
     */
    public function setFinalizado($finalizado ="") {
        $this->finalizado = $finalizado;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 11/09/2017           
     * @return \DateTime | String
     */
    public function getFinalizado($obj = FALSE, $full = FALSE) {
        return $this->dateToStr($this->finalizado, $full, $obj);
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 11/09/2017           
     * @param integer $situacao
     * @return \Application\Entity\Chamado
     */
    public function setSituacao($situacao) {
        $this->situacao = $situacao;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 11/09/2017           
     * @return integer
     */
    public function getSituacao() {
        return $this->situacao;
    }
        
    /**
     * Converte as horas em minutos para Gravar no BD.
     * Pode converter esses tipos string formatada em H:i(07:35) ou objeto DateTime.
     * Ps caso o parametro for um inteiro não será convertido e o mesmo será retornado.   
     *           
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 11/09/2017           
     * @param string | \DateTime | int $horas
     * @return \Application\Entity\Chamado
     */
    public function setHoras($horas = '') {
        $this->horas = $this->timeToInt($horas);
        return $this;
    }       
        
    /**
     * Retorna hora no formato H:i ou um inteiro representando os minutos.
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 11/09/2017   
     * @param boolean | string $convert Opcional Default true converte int do BD em H:i, Caso falso retorna um inteiro
     * @return int | String formatada em H:i
     */
    public function getHoras($convert = TRUE) {
        return $this->intToTime($this->horas, $convert);
    }
        
    /**
     * Converte as horas em minutos para Gravar no BD.
     * Pode converter esses tipos string formatada em H:i(07:35) ou objeto DateTime.
     * Ps caso o parametro for um inteiro não será convertido e o mesmo será retornado.   
     *           
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 11/09/2017           
     * @param string | \DateTime | int $horasTotal
     * @return \Application\Entity\Chamado
     */
    public function setHorasTotal($horasTotal = '') {
        $this->horasTotal = $this->timeToInt($horasTotal);
        return $this;
    }       
        
    /**
     * Retorna hora no formato H:i ou um inteiro representando os minutos.
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 11/09/2017   
     * @param boolean | string $convert Opcional Default true converte int do BD em H:i, Caso falso retorna um inteiro
     * @return int | String formatada em H:i
     */
    public function getHorasTotal($convert = TRUE) {
        return $this->intToTime($this->horasTotal, $convert);
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 11/09/2017           
     * @param string $status
     * @return \Application\Entity\Chamado
     */
    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 11/09/2017           
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }
    
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 25/05/2017           
     * @param \Application\Entity\Usuario $createdBy
     * @return \Adm\Entity\Colaborador
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
     * @since 25/05/2017   
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
     * @since 25/05/2017           
     * @param \Application\Entity\Usuario $updatedBy
     * @return \Adm\Entity\Colaborador
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
     * @since 25/05/2017   
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
     * @since 25/05/2017           
     * @param \DateTime | string $createdAt
     * @return \Adm\Entity\Colaborador
     */
    public function setCreatedAt($createdAt = '') {
        $this->createdAt = $this->strToDate($createdAt);
        return $this;
    }       
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 25/05/2017   
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
     * @since 25/05/2017           
     * @param \DateTime | string $updatedAt
     * @return \Adm\Entity\Colaborador
     */
    public function setUpdatedAt($updatedAt = '') {
        $this->updatedAt = $this->strToDate($updatedAt);
        return $this;
    }       
        
    /**
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0  
     * @since 25/05/2017   
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