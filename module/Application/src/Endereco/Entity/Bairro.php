<?php

namespace Endereco\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bairro
 *
 * @ORM\Table(name="bairro", indexes={@ORM\Index(name="cidade_codigo", columns={"cidade_codigo"})})
 * @ORM\Entity(repositoryClass="\Endereco\Entity\Repository\BairroRepository")
 * @author Danilo Dorotheu
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
class Bairro extends \Application\Entity\AbstractEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="bairro_codigo", type="integer", nullable=false)
     * @ORM\Id
     */
    private $bairroCodigo;

    /**
     * @var \Endereco\Entity\Cidade
     *
     * @ORM\ManyToOne(targetEntity="\Endereco\Entity\Cidade")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cidade_codigo", referencedColumnName="cidade_codigo")
     * })
     */
    private $cidadeCodigo;

    /**
     * @var string
     *
     * @ORM\Column(name="bairro_descricao", type="string", length=72, nullable=true)
     */
    private $bairroDescricao;
    
    /**
     * 
     * @return string
     */
    public function getId() {
        return $this->getBairroCodigo();
    }
    
    /**
     * Metodo padrão para setar o campo key da tabela
     * @param int $id
     */
    public function setId($id) {
        $this->setBairroCodigo($id);
    }
            
    function getBairroCodigo() {
        return $this->bairroCodigo;
    }

    function getCidadeCodigo() {
        return $this->cidadeCodigo;
    }

    function getBairroDescricao() {
        return $this->bairroDescricao;
    }

    function setBairroCodigo($bairroCodigo) {
        $this->bairroCodigo = $bairroCodigo;
    }

    function setCidadeCodigo($cidadeCodigo) {
        $this->cidadeCodigo = $cidadeCodigo;
    }

    function setBairroDescricao($bairroDescricao) {
        $this->bairroDescricao = $bairroDescricao;
    }
}

