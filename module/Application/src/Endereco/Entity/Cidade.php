<?php

namespace Endereco\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cidade
 *
 * @ORM\Table(name="cidade")
 * @ORM\Entity(repositoryClass="\Endereco\Entity\Repository\CidadeRepository")
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
class Cidade extends \Application\Entity\AbstractEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="cidade_codigo", type="integer", length=2, nullable=false)
     * @ORM\Id
     */
    private $cidadeCodigo;

    /**
     * @var \Endereco\Entity\Uf
     *
     * @ORM\ManyToOne(targetEntity="\Endereco\Entity\Uf")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="uf_codigo", referencedColumnName="uf_codigo")
     * })
     */
    private $ufCodigo;

    /**
     * @var string
     *
     * @ORM\Column(name="cidade_descricao", type="string", nullable=true)
     */
    private $cidadeDescricao;

    /**
     * @var string
     *
     * @ORM\Column(name="cidade_cep", type="string", length=255, nullable=false)
     */
    private $cidadeCep;

    /**
     * Metodo padrão para o campo key da tabela
     * @return string
     */
    public function getId() {
        return $this->cidadeCodigo;
    }
    
    /**
     * Metodo padrão para setar o campo key da tabela
     * @param string $id
     * @return \Application\Entity\AppMenu
     */
    public function setId($id) {
        return $this->setcidadeCodigo($id);
    }

    /**
     * 
     * @return integer
     */
    public function getcidadeCodigo() {
        return $this->cidadeCodigo;
    }

    /**
     * 
     * @return string
     */
    public function getufCodigo() {
        return $this->ufCodigo;
    }

    /**
     * 
     * @return string
     */
    public function getcidadeDescricao() {
        return $this->cidadeDescricao;
    }

    /**
     * 
     * @return string
     */
    public function getcidadeCep() {
        return $this->cidadeCep;
    }

    /**
     * 
     * @param string $cidadeCodigo
     * @return \Endereco\Entity\Cidade
     */
    public function setcidadeCodigo($cidadeCodigo) {
        $this->cidadeCodigo = $cidadeCodigo;
        return $this;
    }

    /**
     * 
     * @param string $ufCodigo
     * @return \Endereco\Entity\Cidade
     */
    public function setufCodigo($ufCodigo) {
        $this->ufCodigo = $ufCodigo;
        return $this;
    }

    /**
     * 
     * @param string $cidadeDescricao
     * @return \Endereco\Entity\Cidade
     */
    public function setcidadeDescricao($cidadeDescricao) {
        $this->cidadeDescricao = $cidadeDescricao;
        return $this;
    }

    /**
     * 
     * @param string $cep
     * @return \Endereco\Entity\Cidade
     */
    public function setcidadeCep($cidadeCep) {
        $this->cidadeCep = $cidadeCep;
        return $this;
    }

}

