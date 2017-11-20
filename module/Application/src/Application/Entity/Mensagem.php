<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EntityMensagem
 *
 * @ORM\Table(name="mensagem")
 * @ORM\Entity(repositoryClass="\Application\Entity\Repository\MensagemRepository")
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
class Mensagem extends AbstractEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_mensagem", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idMensagem;

    /**
     * @var string
     *
     * @ORM\Column(name="texto", type="text", length=65535, nullable=true)
     */
    private $texto;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=150, nullable=true)
     */
    private $link;
    

    public function __toString() {
        return $this->getTexto();
    }

    /**
     * Metodo padrão para o campo key da tabela
     * @return integer
     */
    public function getId() {
        return $this->getIdMensagem();
    }
    
    /**
     * Metodo padrão para setar o campo key da tabela
     * @param type $id
     * @return \Application\Entity\AppMenu
     */
    public function setId($id) {
        return $this->setIdMensagem($id);
    }    

    /**
     * 
     * @return integer
     */
    public function getIdMensagem() {
        return $this->idMensagem;
    }

    /**
     * 
     * @return string
     */
    public function getTexto() {
        return $this->texto;
    }

    /**
     * 
     * @return string
     */
    public function getLink() {
        return $this->link;
    }

    /**
     * 
     * @param integer $idMensagem
     * @return \Application\Entity\EntityMensagem
     */
    public function setIdMensagem($idMensagem) {
        $this->idMensagem = $idMensagem;
        return $this;
    }

    /**
     * 
     * @param string $texto
     * @return \Application\Entity\EntityMensagem
     */
    public function setTexto($texto) {
        $this->texto = $texto;
        return $this;
    }

    /**
     * 
     * @param string $link
     * @return \Application\Entity\EntityMensagem
     */
    public function setLink($link) {
        $this->link = $link;
        return $this;
    }

}

