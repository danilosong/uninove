<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Parametros
 *
 * @ORM\Table(name="parametros")
 * @ORM\Entity(repositoryClass="\Application\Entity\Repository\ParametrosRepository")
 * @ORM\HasLifecycleCallbacks
 * @author Danilo Dorotheu <danilo.dorotheu@live.com>
 */
class Parametros extends \Application\Entity\AbstractEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_parame", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idParame;

    /**
     * @var string
     *
     * @ORM\Column(name="chave", type="string", length=245, nullable=true)
     */
    private $chave;

    /**
     * @var string
     *
     * @ORM\Column(name="conteudo", type="string", length=245, nullable=true)
     */
    private $conteudo;

    /**
     * @var string
     *
     * @ORM\Column(name="descricao", type="string", length=245, nullable=true)
     */
    private $descricao;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=45, nullable=true)
     */
    private $status; 
    
    /**
     * Array para colocar instruções do parametros como filtros permissões etc conforme o projeto.
     * 
     * @var array
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @since 09-02-2017 
     * @ORM\Column(name="opts", type="text", length=65535, nullable=true)
     */
    public $opts;
    
    /**
     * (Alias) Retorna ID do Parametro
     * @return integer
     */
    public function getId() {
        return $this->getIdParame();
    }
    
    /**
     * (Alias) Retorna o ID do Parametro
     * @param string $idParame
     * @return \Parametros
     */
    public function setId($idParame) {
        return $this->setIdParame($idParame);
    }
    
        /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 09-02-2017           
     * @param integer $idParame
     * @return \Application\Entity\Parametros
     */
    public function setIdParame($idParame) {
        $this->idParame = $idParame;
        return $this;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 09-02-2017           
     * @return integer
     */
    public function getIdParame() {
        return $this->idParame;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 09-02-2017           
     * @param string $chave
     * @return \Application\Entity\Parametros
     */
    public function setChave($chave) {
        $this->chave = $chave;
        return $this;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 09-02-2017           
     * @return string
     */
    public function getChave() {
        return $this->chave;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 09-02-2017           
     * @param string $conteudo
     * @return \Application\Entity\Parametros
     */
    public function setConteudo($conteudo) {
        $this->conteudo = $conteudo;
        return $this;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 09-02-2017           
     * @return string
     */
    public function getConteudo() {
        return $this->conteudo;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 09-02-2017           
     * @param string $descricao
     * @return \Application\Entity\Parametros
     */
    public function setDescricao($descricao) {
        $this->descricao = $descricao;
        return $this;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 09-02-2017           
     * @return string
     */
    public function getDescricao() {
        return $this->descricao;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 09-02-2017           
     * @param string $status
     * @return \Application\Entity\Parametros
     */
    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 09-02-2017           
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }
        
    /**
     * Retorna um array ou string caso estiver sem o formato do array
                
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 09-02-2017           
     * @param array | string $opts Default é um array vazio
     * @return \Application\Entity\Parametros
     */
    public function setOpts($opts = []) {
        $this->opts = $this->arrayToStr($opts);
        return $this;
    }       
        
    /**
     * Retorna um array que foi gravado no BD.
     * Se a gravação não estiver no formato de array retorna string          
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 09-02-2017           
     * @return array | string
     */
    public function getOpts($ind = '') {
        $array =  $this->strToArray($this->opts);
        if(!empty($ind)){
            return isset($array[$ind]) ? $array[$ind] : NULL ;
        }
        return $array;
    }
        
}

