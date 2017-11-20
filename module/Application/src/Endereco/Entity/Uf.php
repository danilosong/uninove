<?php

namespace Endereco\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Uf
 *
 * @ORM\Table(name="uf")
 * @ORM\Entity(repositoryClass="\Endereco\Entity\Repository\UfRepository")
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
class Uf extends \Application\Entity\AbstractEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="uf_codigo", type="integer", nullable=false)
     * @ORM\Id
     */
    private $ufCodigo;

    /**
     * @var string
     *
     * @ORM\Column(name="uf_sigla", type="string", length=2, nullable=true)
     */
    private $ufSigla;

    /**
     * @var string
     *
     * @ORM\Column(name="uf_descricao", type="string", length=72, nullable=true)
     */
    private $ufDescricao;
    
    /**
     * Alias getId
     * @return String
     */
    public function getId() {
        return $this->getUfCodigo();
    }
    
    /**
     * Alias setId
     * 
     * @param integer $id
     * @return \Endereco\Entity\Uf
     */
    public function setId($id) {
        $this->setUfCodigo($id);
        return $this;
    }
    
    /**
     * 
     * @return integer
     */    
    public function getUfCodigo() {
        return $this->ufCodigo;
    }

    /**
     * 
     * @return string
     */
    public function getUfSigla() {
        return $this->ufSigla;
    }

    /**
     * 
     * @return string
     */
    public function getUfDescricao() {
        return $this->ufDescricao;
    }

    /**
     * 
     * @param integer $ufCodigo
     * @return \Endereco\Entity\Uf
     */
    public function setUfCodigo($ufCodigo) {
        $this->ufCodigo = $ufCodigo;
        return $this;
    }

    /**
     * 
     * @param string $ufSigla
     * @return \Endereco\Entity\Uf
     */
    public function setUfSigla($ufSigla) {
        $this->ufSigla = $ufSigla;
        return $this;
    }

    /**
     * 
     * @param string $ufDescricao
     * @return \Endereco\Entity\Uf
     */
    public function setUfDescricao($ufDescricao) {
        $this->ufDescricao = $ufDescricao;
        return $this;
    }


}

