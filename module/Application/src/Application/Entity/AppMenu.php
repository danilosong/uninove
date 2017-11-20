<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AppMenu
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="app_menu", indexes={@ORM\Index(name="fk_menu_menu1_idx", columns={"in_menu"})})
 * @ORM\Entity(repositoryClass="\Application\Entity\Repository\AppMenuRepository")
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
class AppMenu extends AbstractEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_menu", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idMenu;

    /**
     * @var string
     *
     * @ORM\Column(name="descricao", type="string", length=45, nullable=true)
     */
    private $descricao;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=45, nullable=true)
     */
    private $label;

    /**
     * @var string
     *
     * @ORM\Column(name="route", type="string", length=45, nullable=true)
     */
    private $route;

    /**
     * @var string
     *
     * @ORM\Column(name="controller", type="string", length=45, nullable=true)
     */
    private $controller;

    /**
     * @var string
     *
     * @ORM\Column(name="action", type="string", length=45, nullable=true)
     */
    private $action;

    /**
     * @var string
     *
     * @ORM\Column(name="atributos", type="string", length=45, nullable=true)
     */
    private $atributos;

    /**
     * @var string
     *
     * @ORM\Column(name="icons", type="string", length=250, nullable=true)
     */
    private $icons;

    /**
     * @var string
     *
     * @ORM\Column(name="class", type="string", length=45, nullable=true)
     */
    private $class;

    /**
     * @var string
     *
     * @ORM\Column(name="pagesContainerClass", type="string", length=45, nullable=true)
     */
    private $pagesContainerClass;

    /**
     * @var string
     *
     * @ORM\Column(name="wrapClass", type="string", length=45, nullable=true)
     */
    private $wrapClass;

    /**
     * @var string
     *
     * @ORM\Column(name="resource", type="string", length=100, nullable=true)
     */
    private $resource;

    /**
     * @var string
     *
     * @ORM\Column(name="privilege", type="string", length=45, nullable=true)
     */
    private $privilege;

    /**
     * @var string
     *
     * @ORM\Column(name="ordem", type="string", length=45, nullable=true)
     */
    private $ordem;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * @var \Application\Entity\AppMenu
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\AppMenu")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="in_menu", referencedColumnName="id_menu")
     * })
     */
    private $inMenu;

    public function __construct(array $options = []) {
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
        parent::__construct($options);
    }
    
    /**
     * A descrição da Regra para
     * @return string
     */
    public function __toString() {
        return $this->getDescricao();
    }

    /**
     * Metodo padrão para o campo key da tabela
     * @return integer
     */
    public function getId() {
        return $this->getIdMenu();
    }
    
    /**
     * Metodo padrão para setar o campo key da tabela
     * @param type $id
     * @return \Application\Entity\AppMenu
     */
    public function setId($id) {
        return $this->setIdMenu($id);
    }

    /**
     * 
     * @return integer
     */
    public function getIdMenu() {
        return $this->idMenu;
    }

    /**
     * 
     * @return string
     */
    public function getDescricao() {
        return $this->descricao;
    }

    /**
     * 
     * @return string
     */
    public function getLabel() {
        return $this->label;
    }

    /**
     * 
     * @return string
     */
    public function getRoute() {
        return $this->route;
    }

    /**
     * 
     * @return string
     */
    public function getController() {
        return $this->controller;
    }

    /**
     * 
     * @return string
     */
    public function getAction() {
        return $this->action;
    }

    /**
     * 
     * @return string
     */
    public function getAtributos() {
        return $this->atributos;
    }

    /**
     * 
     * @return string
     */
    public function getIcons() {
        return $this->icons;
    }

    /**
     * 
     * @return string
     */
    public function getClass() {
        return $this->class;
    }

    /**
     * 
     * @return string
     */
    public function getPagesContainerClass() {
        return $this->pagesContainerClass;
    }

    /**
     * 
     * @return string
     */
    public function getWrapClass() {
        return $this->wrapClass;
    }

    /**
     * 
     * @return string
     */
    public function getOrdem() {
        return $this->ordem;
    }

    /**
     * 
     * @return string
     */    
    public function getResource() {
        return $this->resource;
    }

    /**
     * 
     * @return string
     */    
    public function getPrivilege() {
        return $this->privilege;
    }

    /**
     * 
     * @return \DateTime | string
     */
    public function getCreatedAt($obj = FALSE) {
        if($obj){
            return $this->createdAt;
        }else{
            return $this->dateToStr($this->createdAt);
        }
    }

    /**
     * 
     * @return \DateTime | string
     */
    public function getUpdatedAt($obj = FALSE) {
        if($obj){
            return $this->updatedAt;
        }else{
            return $this->dateToStr($this->updatedAt);
        }
    }

    /**
     * 
     * @return type
     */
    public function getInMenu() {
        return $this->inMenu;
    }

    /**
     * 
     * @param type $idMenu
     * @return \Application\Entity\AppMenu
     */
    public function setIdMenu($idMenu) {
        $this->idMenu = $idMenu;
        return $this;
    }

    /**
     * 
     * @param type $descricao
     * @return \Application\Entity\AppMenu
     */
    public function setDescricao($descricao) {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * 
     * @param type $label
     * @return \Application\Entity\AppMenu
     */
    public function setLabel($label) {
        $this->label = $label;
        return $this;
    }

    /**
     * 
     * @param type $route
     * @return \Application\Entity\AppMenu
     */
    public function setRoute($route) {
        $this->route = $route;
        return $this;
    }

    /**
     * 
     * @param type $controller
     * @return \Application\Entity\AppMenu
     */
    public function setController($controller) {
        $this->controller = $controller;
        return $this;
    }

    /**
     * 
     * @param type $action
     * @return \Application\Entity\AppMenu
     */
    public function setAction($action) {
        $this->action = $action;
        return $this;
    }

    /**
     * 
     * @param type $atributos
     * @return \Application\Entity\AppMenu
     */
    public function setAtributos($atributos) {
        $this->atributos = $atributos;
        return $this;
    }

    /**
     * 
     * @param type $icons
     * @return \Application\Entity\AppMenu
     */
    public function setIcons($icons) {
        $this->icons = $icons;
        return $this;
    }

    /**
     * 
     * @param type $class
     * @return \Application\Entity\AppMenu
     */
    public function setClass($class) {
        $this->class = $class;
        return $this;
    }

    /**
     * 
     * @param type $pagesContainerClass
     * @return \Application\Entity\AppMenu
     */
    public function setPagesContainerClass($pagesContainerClass) {
        $this->pagesContainerClass = $pagesContainerClass;
        return $this;
    }

    /**
     * 
     * @param type $wrapClass
     * @return \Application\Entity\AppMenu
     */
    public function setWrapClass($wrapClass) {
        $this->wrapClass = $wrapClass;
        return $this;
    }

    /**
     * 
     * @param string $resource
     * @return \Application\Entity\AppMenu
     */
    public function setResource($resource) {
        $this->resource = $resource;
        return $this;
    }

    /**
     * 
     * @param string $privilege
     * @return \Application\Entity\AppMenu
     */
    public function setPrivilege($privilege) {
        $this->privilege = $privilege;
        return $this;
    }
    
    /**
     * 
     * @param type $ordem
     * @return \Application\Entity\AppMenu
     */
    public function setOrdem($ordem) {
        $this->ordem = $ordem;
        return $this;
    }

    /**
     * 
     * @param \DateTime $createdAt
     * @return \Application\Entity\AppRole
     */
    public function setCreatedAt($createdAt) {
        if(is_string($createdAt)){
            $createdAt = $this->strToDate($createdAt);
        }
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @ORM\PrePersist
     * @param \DateTime $updatedAt
     * @return \Application\Entity\AppRole
     */
    public function setUpdatedAt() {
        $this->updatedAt = new \DateTime("now");
        return $this;
    }

    /**
     * 
     * @param \Application\Entity\AppMenu $inMenu
     * @return \Application\Entity\AppMenu
     */
    public function setInMenu(\Application\Entity\AppMenu $inMenu = NULL) {
        $this->inMenu = $inMenu;
        return $this;
    }


}

