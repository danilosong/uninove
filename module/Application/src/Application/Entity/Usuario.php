<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Math\Rand,
    Zend\Crypt\Key\Derivation\Pbkdf2;

use Zend\Stdlib\Hydrator;

/**
 * Usuario
 *
 * @author Paulo Watakabe
 * @version 1.0
 * @ORM\Table(name="usuario", indexes={
 *      @ORM\Index(name="fk_usuario_app_role1", columns={"role_id"})
 * }) 
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="\Application\Entity\Repository\UsuarioRepository")
 *  
 */
class Usuario extends AbstractEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_usuario", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idUsuario;

    /**
     * @var string
     *
     * @ORM\Column(name="nome_usuario", type="string", length=150, nullable=false)
     */
    private $nomeUsuario;

    /**
     * @var string
     *
     * @ORM\Column(name="nickname", type="string", length=100, nullable=true)
     */
    private $nickname;

    /**
     * @var string
     *
     * @ORM\Column(name="senha_usuario", type="string", length=250, nullable=false)
     */
    private $senhaUsuario;

    /**
     * @var string
     *
     * @ORM\Column(name="email_usuario", type="string", length=250, nullable=true)
     */
    private $emailUsuario;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=250, nullable=true)
     */
    private $salt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="situacao", type="string", length=10, nullable=false)
     */
    private $situacao;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_admin", type="boolean", nullable=true)
     */
    private $isAdmin;

    /**
     * @var string
     *
     * @ORM\Column(name="lembrete_senha", type="string", length=250, nullable=true)
     */
    private $lembreteSenha;

    /**
     * @var string
     *
     * @ORM\Column(name="activation_key", type="string", length=255, nullable=true)
     */
    private $activationKey;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    private $active;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo_usuario", type="string", length=50, nullable=true)
     */
    private $tipo;

    /**
     * @var \Application\Entity\AppRole
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\AppRole", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="role_id", referencedColumnName="id_role")
     * })
     */
    private $role;
    
    /**
     * Caminho para arquivo de foto
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 18-07-2016 
     * @var string 
     * @ORM\Column(name="path_foto", type="string", length=200, nullable=true)
     */
    private $pathFoto;

    /**
     * Guarda a referencia ou id do sistema anterior no adm por exemplo guarda lnk-operador
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 18-07-2016 
     * @var string 
     * @ORM\Column(name="referencia", type="string", length=15, nullable=true)
     */
    private $referencia;

    /**
     * @var string
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 26-01-2017
     * @ORM\Column(name="status", type="string", length=100, nullable=true, options={"default" = "ATIVO"})
     */
    protected $status = "ATIVO";

    /**
     * 
     * @param array $options
     */
    public function __construct(array $options = []) 
    {
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
        
        $this->salt = base64_encode(Rand::getBytes(8, true));
        $this->activationKey = md5($this->emailUsuario . $this->salt);
                
        (new Hydrator\ClassMethods)->hydrate($options, $this);
    }  
    
    /**
     * Retorna um array basico ou um array completo executado pelo hydrator.
     * 
     * @param boolean $parent Opcional para chamar o toArray do hydrator
     * @return array
     */
    public function toArray($parent=FALSE, $prefix='') {
        if($parent){
            return parent::toArray();
        }
        return
            [
               'id'           => $this->getIdUsuario(),
               'idUsuario'    => $this->getIdUsuario(),
               'nomeUsuario'  => $this->getNomeUsuario(),
               'nickname'     => $this->getNickname(),
               'emailUsuario' => $this->getEmailUsuario(),
               'createdAt'    => $this->getCreatedAt(),
               'updatedAt'    => $this->getUpdatedAt(),
               'situacao'     => $this->getSituacao(),
               'isAdmin'      => $this->getIsAdmin(),
               'tipo'         => $this->getTipo(),
               'role'         => $this->getRole(),
               'referencia'   => $this->getReferencia(),
               'status'       => $this->getStatus(),
            ];
    }
    
    /**
     * 
     * @return integer
     */
    public function getId() {
        return $this->getIdUsuario();
    }

    /**
     * 
     * @param integer $id
     * @return \Application\Entity\Usuario
     */
    public function setId($id) {
        $this->setIdUsuario($id);
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getIdUsuario() {
        return $this->idUsuario;
    }

    /**
     * 
     * @return string
     */
    public function getNomeUsuario() {
        return $this->nomeUsuario;
    }

    /**
     * 
     * @return string
     */
    public function getNome() {
        return $this->getNomeUsuario();
    }

    /**
     * 
     * @return string
     */
    public function getNickname() {
        return $this->nickname;
    }

    /**
     * 
     * @return string
     */
    public function getSenhaUsuario() {
        return $this->senhaUsuario;
    }

    /**
     * 
     * @return string
     */
    public function getPassword() {
        return $this->getSenhaUsuario();
    }

    /**
     * 
     * @return string
     */
    public function getEmailUsuario() {
        return $this->emailUsuario;
    }

    /**
     * 
     * @return string
     */
    public function getEmail() {
        return $this->getEmailUsuario();
    }

    /**
     * 
     * @return string
     */
    public function getSalt() {
        return $this->salt;
    }

    /**
     * 
     * @return string
     */
    public function getSituacao() {
        return $this->situacao;
    }

    /**
     * 
     * @return string
     */
    public function getIsAdmin() {
        return $this->isAdmin;
    }

    /**
     * 
     * @return string
     */
    public function getLembreteSenha() {
        return $this->lembreteSenha;
    }

    /**
     * 
     * @return \Application\Entity\AppRole
     */
    public function getRole($obj = FALSE) {
        if($obj){
            return $this->role;            
        }
        return is_null($this->role)? '' : $this->role->getNome();
    }
        
    /**
     * 
     * @param string $idUsuario
     * @return \Application\Entity\Usuario
     */
    public function setIdUsuario($idUsuario) {
        $this->idUsuario = $idUsuario;
        return $this;
    }

    /**
     * 
     * @param string $nomeUsuario
     * @return \Application\Entity\Usuario
     */
    public function setNomeUsuario($nomeUsuario) {
        $this->nomeUsuario = $nomeUsuario;
        return $this;
    }

    /**
     * 
     * @param string $nickname
     * @return \Application\Entity\Usuario
     */
    public function setNickname($nickname) {
        $this->nickname = $nickname;
        return $this;
    }

    /**
     * 
     * @param string $senhaUsuario
     * @return \Application\Entity\Usuario
     */
    public function setSenhaUsuario($senhaUsuario) {
        $this->senhaUsuario = $this->encryptPassword($senhaUsuario);
        return $this;
    }
    
    /**
     * 
     * @param string $password
     * @return string
     */
    public function encryptPassword($password) {
        return base64_encode(Pbkdf2::calc('sha256', $password, $this->salt, 10000, strlen($password * 2)));
    }

    /**
     * 
     * @param string $emailUsuario
     * @return \Application\Entity\Usuario
     */
    public function setEmailUsuario($emailUsuario) {
        $this->emailUsuario = $emailUsuario;
        return $this;
    }

    /**
     * 
     * @param string $salt
     * @return \Application\Entity\Usuario
     */
    public function setSalt($salt) {
        $this->salt = $salt;
        return $this;
    }

    /**
     * 
     * @param string $situacao
     * @return \Application\Entity\Usuario
     */
    public function setSituacao($situacao) {
        $this->situacao = $situacao;
        return $this;
    }

    /**
     * 
     * @param string $isAdmin
     * @return \Application\Entity\Usuario
     */
    public function setIsAdmin($isAdmin) {
        $this->isAdmin = $isAdmin;
        return $this;
    }

    /**
     * 
     * @param string $lembreteSenha
     * @return \Application\Entity\Usuario
     */
    public function setLembreteSenha($lembreteSenha) {
        $this->lembreteSenha = $lembreteSenha;
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getActivationKey() {
        return $this->activationKey;
    }

    /**
     * 
     * @param string $activationKey
     * @return \Application\Entity\Usuario
     */
    public function setActivationKey($activationKey) {
        $this->activationKey = $activationKey;
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getActive() {
        return $this->active;
    }

    /**
     * 
     * @param string $active
     * @return \Application\Entity\Usuario
     */
    public function setActive($active) {
        $this->active = $active;
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getTipo() {
        return $this->tipo;
    }

    /**
     * 
     * @param string $tipo
     * @return \Application\Entity\Usuario
     */
    public function setTipo($tipo) {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * 
     * @param \Application\Entity\AppRole $role
     * @return \Application\Entity\AppPrivilege
     */
    public function setRole(\Application\Entity\AppRole $role) {
        $this->role = $role;
        return $this;
    }
    
    /**
     * Caminho para arquivo de foto
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 18-07-2016 
     * @return string
     */
    public function getPathFoto() {
        return $this->pathFoto;
    }

    /**
     * Caminho para arquivo de foto
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 18-07-2016 
     * @param string $pathFoto
     * @return \Application\Entity\Usuario
     */
    public function setPathFoto($pathFoto) {
        $this->pathFoto = $pathFoto;
        return $this;
    }
    
    /**
     * Guarda a referencia ou id do sistema anterior no adm por exemplo guarda lnk-operador
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 20-07-2016 
     * @return string
     */
    public function getReferencia() {
        return $this->referencia;
    }

    /**
     * Guarda a referencia ou id do sistema anterior no adm por exemplo guarda lnk-operador
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 20-07-2016 
     * @param string $referencia
     * @return \Application\Entity\Usuario
     */
    public function setReferencia($referencia) {
        $this->referencia = $referencia;
        return $this;
    }
    
    
    
    
    
    
    
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 02-01-2017           
     * @param \DateTime | string $createdAt
     * @return \Application\Entity\Usuario
     */
    public function setCreatedAt($createdAt = '') {
        $this->createdAt = $this->strToDate($createdAt);
        return $this;
    }       
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 02-01-2017   
     * @param boolean | string $obj  true  retorna o objeto DateTime, falso formatação d/m/Y,  string = 'full' formatação d/m/Y H:i:s ou a formatação especificada na string
     * @param boolean | string $full falso formatação definida pelo param $obj se string formatação especificada no conteudo
     * @return \DateTime | string
     */
    public function getCreatedAt($obj = FALSE, $full = FALSE) {
        return $this->dateToStr($this->createdAt, $full, $obj);
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 02-01-2017           
     * @param \DateTime | string $updatedAt
     * @return \Application\Entity\Usuario
     */
    public function setUpdatedAt($updatedAt = '') {
        $this->updatedAt = $this->strToDate($updatedAt);
        return $this;
    }       
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 02-01-2017   
     * @param boolean | string $obj  true  retorna o objeto DateTime, falso formatação d/m/Y,  string = 'full' formatação d/m/Y H:i:s ou a formatação especificada na string
     * @param boolean | string $full falso formatação definida pelo param $obj se string formatação especificada no conteudo
     * @return \DateTime | string
     */
    public function getUpdatedAt($obj = FALSE, $full = FALSE) {
        return $this->dateToStr($this->updatedAt, $full, $obj);
    }    

    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 26-01-2017           
     * @param string $status
     * @return \Farol\Entity\Indicador
     */
    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }
        
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 26-01-2017               
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }
}

