<?php

namespace Endereco\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Endereco
 *
 * @ORM\Table(name="endereco", indexes={@ORM\Index(name="bairro_codigo", columns={"bairro_codigo"})})
 * @ORM\Entity(repositoryClass="\Endereco\Entity\Repository\EnderecoRepository")
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
class Endereco extends \Application\Entity\AbstractEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="endereco_codigo", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $enderecoCodigo;

    /**
     * @var \Endereco\Entity\Bairro
     *
     * @ORM\ManyToOne(targetEntity="\Endereco\Entity\Bairro")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="bairro_codigo", referencedColumnName="bairro_codigo")
     * })
     */
    private $bairroCodigo;

    /**
     * @var string
     *
     * @ORM\Column(name="endereco_cep", type="string", length=9, nullable=true)
     */
    private $enderecoCep;

    /**
     * @var string
     *
     * @ORM\Column(name="endereco_logradouro", type="string", length=72, nullable=true)
     */
    private $enderecoLogradouro;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo_logradouro", type="string", length=20, nullable=true)
     */
    private $tipoLogradouro;

    /**
     * @var string
     *
     * @ORM\Column(name="logradouro", type="string", length=72, nullable=true)
     */
    private $logradouro;

    /**
     * @var string
     *
     * @ORM\Column(name="numero", type="string", length=15, nullable=true)
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="endereco_complemento", type="string", length=72, nullable=true)
     */
    private $enderecoComplemento;
    
    public function __toString() {
        return $this->getEnderecoLogradouro() . ' ' . $this->getBairroCodigo()->getBairroDescricao() ;
    }

    /**
     * Alias getId
     * @return integer
     */
    public function getId() {
        return $this->getEnderecoCodigo();
    }
    
    /**
     * Metodo padrão para setar o campo key da tabela
     * @param string $id
     * @return \Endereco\Entity\Endereco
     */
    public function setId($id) {
        return $this->setEnderecoCodigo($id);
    }
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 16-11-2016           
     * @param integer $enderecoCodigo
     * @return \Endereco\Entity\Endereco
     */
    public function setEnderecoCodigo($enderecoCodigo) {
        $this->enderecoCodigo = $enderecoCodigo;
        return $this;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 16-11-2016           
     * @return integer
     */
    public function getEnderecoCodigo() {
        return $this->enderecoCodigo;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 16-11-2016           
     * @param \Endereco\Entity\Bairro $bairroCodigo
     * @return \Endereco\Entity\Endereco
     */
    public function setBairroCodigo(\Endereco\Entity\Bairro $bairroCodigo = NULL) {
        $this->bairroCodigo = $bairroCodigo;
        return $this;
    }
        
    /**
     * Retorna a entidade  ou um atributo se for passado por parametro.
     * Caso passe o parametro e a relação for a relação for null será retornado string '-'           
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 16-11-2016   
     * @param string $get  Nome do method get a ser retornado da relação.
     * @param array  $params Parametro(s) a serem usado neste  get da relação.
     * @return \Endereco\Entity\Bairro
     */
    public function getBairroCodigo($get='', Array $params=[]) {
        if(empty($get)){
            return $this->bairroCodigo;
        }
        if(is_null($this->bairroCodigo)){
            return '-';
        }
        $method = !method_exists($this->bairroCodigo, $get) ? 'get' . ucfirst($get) : $get;
        if(empty($params)){
            return $this->bairroCodigo->$method();
        }
        return call_user_func_array([$this->bairroCodigo, $method], $params);
            
    }
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 16-11-2016           
     * @param string $enderecoCep
     * @return \Endereco\Entity\Endereco
     */
    public function setEnderecoCep($enderecoCep) {
        $this->enderecoCep = $enderecoCep;
        return $this;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 16-11-2016           
     * @return string
     */
    public function getEnderecoCep() {
        return $this->enderecoCep;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 16-11-2016           
     * @param string $enderecoLogradouro
     * @return \Endereco\Entity\Endereco
     */
    public function setEnderecoLogradouro($enderecoLogradouro) {
        $this->enderecoLogradouro = $enderecoLogradouro;
        return $this;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 16-11-2016           
     * @return string
     */
    public function getEnderecoLogradouro() {
        return $this->enderecoLogradouro;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 16-11-2016           
     * @param string $tipoLogradouro
     * @return \Endereco\Entity\Endereco
     */
    public function setTipoLogradouro($tipoLogradouro) {
        $this->tipoLogradouro = $tipoLogradouro;
        return $this;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 16-11-2016           
     * @return string
     */
    public function getTipoLogradouro() {
        return $this->tipoLogradouro;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 16-11-2016           
     * @param string $logradouro
     * @return \Endereco\Entity\Endereco
     */
    public function setLogradouro($logradouro) {
        $this->logradouro = $logradouro;
        return $this;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 16-11-2016           
     * @return string
     */
    public function getLogradouro() {
        return $this->logradouro;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 16-11-2016           
     * @param string $numero
     * @return \Endereco\Entity\Endereco
     */
    public function setNumero($numero) {
        $this->numero = $numero;
        return $this;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 16-11-2016           
     * @return string
     */
    public function getNumero() {
        return $this->numero;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 16-11-2016           
     * @param string $enderecoComplemento
     * @return \Endereco\Entity\Endereco
     */
    public function setEnderecoComplemento($enderecoComplemento) {
        $this->enderecoComplemento = $enderecoComplemento;
        return $this;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 16-11-2016           
     * @return string
     */
    public function getEnderecoComplemento() {
        return $this->enderecoComplemento;
    }
    
}

