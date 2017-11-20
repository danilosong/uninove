<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Enviado
 *
 * @ORM\Table(name="enviado", indexes={@ORM\Index(name="fk_enviado_user1_idx", columns={"from_id_user"}), @ORM\Index(name="fk_enviado_user2_idx", columns={"to_id_user"}), @ORM\Index(name="fk_enviado_mensagem1_idx", columns={"mensagem_id_mensagem"})})
 * @ORM\Entity(repositoryClass="\Application\Entity\Repository\EnviadoRepository")
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
class Enviado extends AbstractEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_enviado", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idEnviado;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_enviado", type="datetime", nullable=true)
     */
    private $dateEnviado;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_recebido", type="datetime", nullable=true)
     */
    private $dateRecebido;

    /**
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="from_id_user", referencedColumnName="id_user")
     * })
     */
    private $fromUser;

    /**
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\User", inversedBy="enviados")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="to_id_user", referencedColumnName="id_user")
     * })
     */
    private $toUser;

    /**
     * @var \Application\Entity\Grupo
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Grupo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="to_id_grupo", referencedColumnName="id_grupo")
     * })
     */
    private $toGrupo;

    /**
     * @var \Application\Entity\Mensagem
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Mensagem")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="mensagem_id_mensagem", referencedColumnName="id_mensagem")
     * })
     */
    private $mensagemMensagem;
    
    public function __construct($data) {
        parent::__construct($data);
        $this->dateRecebido = NULL;
    }
    

    /**
     * Metodo padrão para o campo key da tabela
     * @return integer
     */
    public function getId() {
        return $this->getIdEnviado();
    }
    
    /**
     * Metodo padrão para setar o campo key da tabela
     * @param type $id
     * @return \Application\Entity\Enviado
     */
    public function setId($id) {
        return $this->setIdEnviado($id);
    }

    /**
     * 
     * @return integer
     */
    public function getIdEnviado() {
        return $this->idEnviado;
    }

    /**
     * 
     * @return \Datetime | string
     */
    public function getDateEnviado($obj = FALSE, $full = FALSE) {
        return $this->dateToStr($this->dateEnviado, $full, $obj);
    }

    /**
     * 
     * @return \Datetime | string
     */
    public function getDateRecebido($obj = FALSE, $full = FALSE) {
        return $this->dateToStr($this->dateRecebido, $full, $obj);
    }

    /**
     * 
     * @return \Application\Entity\User
     */
    public function getFromUser() {
        return $this->fromUser;
    }

    /**
     * 
     * @return \Application\Entity\User
     */
    public function getToUser() {
        return $this->toUser;
    }

    /**
     * 
     * @return \Application\Entity\Grupo
     */
    public function getToGrupo() {
        return $this->toGrupo;
    }

    /**
     * 
     * @return \Application\Entity\Mensagem
     */
    public function getMensagemMensagem() {
        return $this->mensagemMensagem;
    }
    
    /**
     * 
     * @param integer $idEnviado
     * @return \Application\Entity\Enviado
     */
    public function setIdEnviado($idEnviado){
        $this->idEnviado = $idEnviado;
        return $this;
    }

    /**
     * 
     * @param \DateTime | string $dateEnviado
     * @return \Application\Entity\Enviado
     */
    public function setDateEnviado($dateEnviado) {        
        $this->dateEnviado = $this->strToDate($dateEnviado);
        return $this;
    }

    /**
     * 
     * @param \DateTime | string $dateRecebido
     * @return \Application\Entity\Enviado
     */
    public function setDateRecebido($dateRecebido) {
        $this->dateRecebido = $this->strToDate($dateRecebido);
        return $this;
    }

    /**
     * 
     * @param \Application\Entity\User $fromUser
     * @return \Application\Entity\Enviado
     */
    public function setFromUser(\Application\Entity\User $fromUser) {
        $this->fromUser = $fromUser;
        return $this;
    }

    /**
     * 
     * @param \Application\Entity\User $toUser
     * @return \Application\Entity\Enviado
     */
    public function setToUser(\Application\Entity\User $toUser) {
        $this->toUser = $toUser;
        return $this;
    }

    /**
     * 
     * @param \Application\Entity\Grupo $toGrupo
     * @return \Application\Entity\Enviado
     */
    public function setToGrupo(\Application\Entity\Grupo $toGrupo = null) {
        $this->toGrupo = $toGrupo;
        return $this;
    }

    /**
     * 
     * @param \Application\Entity\Mensagem $mensagemMensagem
     * @return \Application\Entity\Enviado
     */
    public function setMensagemMensagem(\Application\Entity\Mensagem $mensagemMensagem) {
        $this->mensagemMensagem = $mensagemMensagem;
        return $this;
    }
    
    
    
}

