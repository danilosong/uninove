<?php

namespace Application\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * AppLog
 *
 * @ORM\Table(name="app_log", indexes={@ORM\Index(name="fk_app_log_usuario1_idx", columns={"usuario_id"})})
 * @ORM\Entity(repositoryClass="\Application\Entity\Repository\AppLogRepository")
 * @author Paulo Watakabe <watakabe05@gmail.com>
 */
class AppLog extends AbstractEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_log", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idLog;

    /**
     * @var string
     *
     * @ORM\Column(name="controller", type="string", length=30, nullable=false)
     */
    private $controller;

    /**
     * @var string
     *
     * @ORM\Column(name="action", type="string", length=30, nullable=false)
     */
    private $action;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data", type="datetime", nullable=true)
     */
    private $data;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=20, nullable=true)
     */
    private $ip;

    /**
     * @var string
     *
     * @ORM\Column(name="tabela", type="string", length=300, nullable=true)
     */
    private $tabela;

    /**
     * @var string
     *
     * @ORM\Column(name="id_da_tabela", type="string", length=30, nullable=true)
     */
    private $idDaTabela;

    /**
     * @var string
     *
     * @ORM\Column(name="de_para", type="text", length=65535, nullable=true)
     */
    private $dePara;

    /**
     * @var string
     *
     * @ORM\Column(name="obs", type="string", length=300, nullable=true)
     */
    private $obs;

    /**
     * @var \Application\Entity\Usuario
     *
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario_id", referencedColumnName="id_usuario")
     * })
     */
    private $usuario;
    
    /**
     * Metodo construtor
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @param array $data Com os campos para hidratar a entidade
     */
    public function __construct($data) {
        $this->data = new \DateTime('now');
        parent::__construct($data);
    }    

    /**
     * Metodo padrão para o campo key da tabela
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @return integer
     */
    public function getId() {
        return $this->getIdLog();
    }
    
    /**
     * Metodo padrão para setar o campo key da tabela
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @param type $id
     * @return \Application\Entity\Enviado
     */
    public function setId($id) {
        return $this->setIdLog($id);
    }

    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @return integer
     */
    public function getIdLog() {
        return $this->idLog;
    }

    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @return string
     */
    public function getController() {
        return $this->controller;
    }

    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @return string
     */
    public function getAction() {
        return $this->action;
    }

    /**
     * 
     * @return string | object
     */
    public function getData($obj = FALSE, $full = FALSE) {
        return $this->dateToStr($this->data, $full, $obj);
    }

    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @return string
     */
    public function getIp() {
        return $this->ip;
    }

    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @return string
     */
    public function getTabela() {
        return $this->tabela;
    }

    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @return string
     */
    public function getIdDaTabela() {
        return $this->idDaTabela;
    }

    /**
     * 
     * @return string
     */
    public function getDePara() {
        return $this->dePara;
    }

    /**
     * 
     * @return string
     */
    public function getObs() {
        return $this->obs;
    }

    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @return \Application\Entity\Usuario
     */
    public function getUsuario() {
        return $this->usuario;
    }

    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @param integer $idLog
     * @return \Application\Entity\AppLog
     */
    public function setIdLog($idLog) {
        $this->idLog = $idLog;
        return $this;
    }

    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @param string $controller
     * @return \Application\Entity\AppLog
     */
    public function setController($controller) {
        $this->controller = $controller;
        return $this;
    }

    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @param string $action
     * @return \Application\Entity\AppLog
     */
    public function setAction($action) {
        $this->action = $action;
        return $this;
    }

    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @param \DateTime | string $data Obs a string deve esta no formato dd/mm/yyyy para conversão correta
     * @return \Application\Entity\AppLog
     */
    public function setData($data) {
        $this->data = $this->strToDate($data);
        return $this;
    }

    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @param string $ip
     * @return \Application\Entity\AppLog
     */
    public function setIp($ip) {
        $this->ip = $ip;
        return $this;
    }

    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @param string $tabela
     * @return \Application\Entity\AppLog
     */
    public function setTabela($tabela) {
        $this->tabela = $tabela;
        return $this;
    }

    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @param string $idDaTabela
     * @return \Application\Entity\AppLog
     */
    public function setIdDaTabela($idDaTabela) {
        $this->idDaTabela = $idDaTabela;
        return $this;
    }

    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @param string $dePara
     * @return \Application\Entity\AppLog
     */
    public function setDePara($dePara) {
        $this->dePara = $dePara;
        return $this;
    }

    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @param string $obs
     * @return \Application\Entity\AppLog
     */
    public function setObs($obs) {
        $this->obs = $obs;
        return $this;
    }

    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @param \Application\Entity\Usuario $usuario
     * @return \Application\Entity\AppLog
     */
    public function setUsuario(\Application\Entity\Usuario $usuario) {
        $this->usuario = $usuario;
        return $this;
    }


}

