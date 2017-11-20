<?php

namespace Endereco\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Country
 *
 * @ORM\Table(name="paises")
 * @ORM\Entity(repositoryClass="\Endereco\Entity\Repository\CountryRepository")
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
class Country extends \Application\Entity\AbstractEntity
{
    /**
     * @var string
     *
     * @ORM\Column(name="iso", type="string", length=2, nullable=false)
     * @ORM\Id
     */
    private $iso;

    /**
     * @var string
     *
     * @ORM\Column(name="iso3", type="string", length=3, nullable=false)
     */
    private $iso3;

    /**
     * @var integer
     *
     * @ORM\Column(name="numcode", type="smallint", nullable=true)
     */
    private $numcode;

    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", length=255, nullable=false)
     */
    private $nome;

    /**
     * Metodo padrão para o campo key da tabela
     * @return string
     */
    public function getId() {
        return $this->getIso();
    }
    
    /**
     * Metodo padrão para setar o campo key da tabela
     * @param string $id
     * @return \Application\Entity\AppMenu
     */
    public function setId($id) {
        return $this->setIso($id);
    }

    /**
     * 
     * @return string
     */
    public function getIso() {
        return $this->iso;
    }

    /**
     * 
     * @return string
     */
    public function getIso3() {
        return $this->iso3;
    }

    /**
     * 
     * @return integer
     */
    public function getNumcode() {
        return $this->numcode;
    }

    /**
     * 
     * @return string
     */
    public function getNome() {
        return $this->nome;
    }

    /**
     * 
     * @param string $iso
     * @return \Endereco\Entity\Country
     */
    public function setIso($iso) {
        $this->iso = $iso;
        return $this;
    }

    /**
     * 
     * @param string $iso3
     * @return \Endereco\Entity\Country
     */
    public function setIso3($iso3) {
        $this->iso3 = $iso3;
        return $this;
    }

    /**
     * 
     * @param integer $numcode
     * @return \Endereco\Entity\Country
     */
    public function setNumcode($numcode) {
        $this->numcode = $numcode;
        return $this;
    }

    /**
     * 
     * @param string $nome
     * @return \Endereco\Entity\Country
     */
    public function setNome($nome) {
        $this->nome = $nome;
        return $this;
    }

}

