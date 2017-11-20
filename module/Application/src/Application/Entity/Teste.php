<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Teste
 *
 * @ORM\Table(name="teste")
 * @ORM\Entity(repositoryClass="\Application\Entity\Repository\TesteRepository")
 */
class Teste extends AbstractEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_teste", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idTeste;

    /**
     * @var string
     *
     * @ORM\Column(name="campo1", type="string", length=100, nullable=false)
     */
    private $campo1;

    /**
     * @var string
     *
     * @ORM\Column(name="campo2", type="string", length=100, nullable=false)
     */
    private $campo2;

    /**
     * @var string
     *
     * @ORM\Column(name="campo3", type="string", length=100, nullable=false)
     */
    private $campo3;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datetime", type="datetime", nullable=true)
     */
    private $datetime;

    /**
     * Metodo padrão para o campo key da tabela
     * @return integer
     */
    public function getId() {
        return $this->getIdTeste();
    }
    
    /**
     * Metodo padrão para setar o campo key da tabela
     * @param type $id
     * @return \Application\Entity\Teste
     */
    public function setId($id) {
        return $this->setIdTeste($id);
    }

    /**
     * 
     * @return integer
     */
    public function getIdTeste() {
        return $this->idTeste;
    }

    /**
     * 
     * @return string
     */
    public function getCampo1() {
        return $this->mask("##.###.###/####-##", $this->campo1);
    }

    /**
     * 
     * @param string $campo1
     * @return \Application\Entity\Teste
     */
    public function setCampo1($campo1) {
        $this->campo1 = $this->clean($campo1,14);
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getCampo2() {
        return $this->mask("###.###.###-##", $this->campo2);
    }

    /**
     * 
     * @param string $campo1
     * @return \Application\Entity\Teste
     */
    public function setCampo2($campo2) {
        $this->campo2 = $this->clean($campo2,11);
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getCampo3() {
        return $this->mask("(##)####-####", $this->campo3);
    }

    /**
     * 
     * @param string $campo1
     * @return \Application\Entity\Teste
     */
    public function setCampo3($campo3) {
        $this->campo3 = $this->clean($campo3, FALSE);
        return $this;
    }

    /**
     * 
     * @param integer $idTeste
     * @return \Application\Entity\Teste
     */
    public function setIdTeste($idTeste) {
        $this->idTeste = $idTeste;
        return $this;
    }

    /**
     * 
     * @return \Datetime | string
     */
    public function getDatetime($obj = FALSE, $full = FALSE) {
        return $this->dateToStr($this->datetime, $full, $obj);
    }

    /**
     * 
     * @param \DateTime | string $datetime
     * @return \Application\Entity\Enviado
     */
    public function setDatetime( $datetime) {
        $this->datetime = $this->strToDate($datetime);
        return $this;
    }


}

