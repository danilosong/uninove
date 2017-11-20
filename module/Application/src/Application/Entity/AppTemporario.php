<?php

namespace Application\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * AppTemporario
 *
 * @ORM\Table(name="app_temporario", indexes={
 *
 * })
 * @ORM\Entity(repositoryClass="\Application\Entity\Repository\AppTemporarioRepository")
 * @ORM\HasLifecycleCallbacks
 * @author Paulo Watakabe <watakabe05@gmail.com>
 * @version 1.0
 * @since 07-04-2017
 */
class AppTemporario extends AbstractEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=150, nullable=false)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=200, nullable=false)
     */
    private $path;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * Realiza modificacoes na entidade exclusivamente no update
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 07-04-2017
     * @ORM\PreUpdate
     * @param type $param
     */
    public function beforeUpdate() {
        $this->setUpdatedAt();
    }

    /**
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 07-04-2017
     * @param integer $limite Indica o numero de galhos que deverá percorrer dentro da arvore do array
     * @param string  $prefix Coloca um prefixamento inicial no array
     * @return array Array com os dados da entides e pode tb retornar os dados de suas relações
     */
    public function toArray($limite = 0, $prefix = '') {
        if(empty($prefix)){
            return $this->toArrayNew($limite);
        }
        return [$prefix => $this->toArrayNew($limite)];
    }

        /**
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 07-04-2017
     * @param integer $id
     * @return \Application\Entity\AppTemporario
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 07-04-2017
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 07-04-2017
     * @param string $code
     * @return \Application\Entity\AppTemporario
     */
    public function setCode($code) {
        $this->code = $code;
        return $this;
    }

    /**
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 07-04-2017
     * @return string
     */
    public function getCode() {
        return $this->code;
    }

    /**
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 07-04-2017
     * @param string $path
     * @return \Application\Entity\AppTemporario
     */
    public function setPath($path) {
        $this->path = $path;
        return $this;
    }

    /**
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 07-04-2017
     * @return string
     */
    public function getPath() {
        return $this->path;
    }

    /**
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 07-04-2017
     * @param \DateTime | string $createdAt
     * @return \Application\Entity\AppTemporario
     */
    public function setCreatedAt($createdAt = '') {
        $this->createdAt = $this->strToDate($createdAt);
        return $this;
    }

    /**
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 07-04-2017
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
     * @since 07-04-2017
     * @param \DateTime | string $updatedAt
     * @return \Application\Entity\AppTemporario
     */
    public function setUpdatedAt($updatedAt = '') {
        $this->updatedAt = $this->strToDate($updatedAt);
        return $this;
    }

    /**
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 07-04-2017
     * @param boolean | string $obj  true  retorna o objeto DateTime, falso formatação d/m/Y,  string = 'full' formatação d/m/Y H:i:s ou a formatação especificada na string
     * @param boolean | string $full falso formatação definida pelo param $obj se string formatação especificada no conteudo
     * @return \DateTime | string
     */
    public function getUpdatedAt($obj = FALSE, $full = FALSE) {
        return $this->dateToStr($this->updatedAt, $full, $obj);
    }

}
