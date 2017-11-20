<?php
/*
 * License GPL .
 * 
 */

namespace Application\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;
use \Zend\Session\Container as SessionContainer;

/**
 * Image Helper
 *  Gera um hash e link com o caminha da imagem que esteja em um diretorio não visivel do servidor.
 *  Ao receber o hash verifica a sessao do usuario e procura a imagem e retorna para browser.
 * 
 * @author Paulo Watakabe <watakabe05@gmail.com>
 * @since 26-01-2016
 */
class Image extends AbstractHelper {

    /**
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0 
     * @since 25-04-2016 
     * @var \Zend\Session\Container 
     */
    private $sc;

    /**
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0 
     * @since 25-04-2016 
     * @var \Application\View\Helper\Param 
     */    
    private $param;   
    
    /**
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0 
     * @since 25-04-2016 
     * @var \Application\View\Helper\Acl
     */
    private $acl;   
    
    /**
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0 
     * @since 25-04-2016 
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;   
    
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 05-05-2016
     * @param \Application\View\Helper\Param $param
     * @param \Application\View\Helper\Acl $acl
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct(\Application\View\Helper\Param $param = NULL, \Application\View\Helper\Acl $acl = NULL, \Doctrine\ORM\EntityManager $em = NULL) {
        $this->param = $param;
        $this->acl   = $acl;
        $this->em    = $em;
    } 
    
    /**
     * Metodo invoke para gerar o src criptografado ou retornar a propria instancia do helper
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 05-05-2016
     * @param string  $path
     * @param boolean $forever
     * @return \Application\View\Helper\Image | string
     */
    public function __invoke($path = '', $forever = false) {
        if(empty($path)){
            return $this;               
        }
        return $this->generateSrcCrypt($path, $forever);
    }
    
    /**
     * Caminho contendo o arquivo para gerar o link protegido.
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 05-05-2016
     * @version 1.5
     * @since 07-04-2017  Adição do parametro forever
     * @param string  $path
     * @param boolean $forever Parametro que fixa o link para abrir mesmo 
     * @return string
     */
    public function generateSrcCrypt($path, $forever=false) {
        $code = md5(uniqid(rand(), true));
        $this->sessao($code,$path);
        if($forever){
            $entity = new \Application\Entity\AppTemporario([
                'path'   => $path
                ,'code'  => $code
            ]);
            $this->em->persist($entity);
            $this->em->flush();
        }
        $src = "/app/images/get?img=" . $code;
        return $src;
    }
    
    /**
     * Retorna o binario da imagem do path guardado do hash gerado anteriormente
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 05-05-2016
     * @return Binario 
     */
    public function deCrypt() {
        $code = isset($_GET['img']) ? $_GET['img'] : '' ;
        $path = '';
        if(!empty($code)){
            $path = $this->sessao($code);
            if(!is_string($path) or empty($path)){
                $entity = $this->em->getRepository('\Application\Entity\AppTemporario')->findOneByCode($code);
                if($entity){
                    $path = $entity->getPath();
                }
            }
        }
        if(empty($path) OR !file_exists($path)){
            if($this->param){
                $path = $this->param->__invoke('ALL', 'APP_NO_IMAGEM');
            }
        }
        $ext = substr(trim($path), -3);
        switch ($ext){
            case 'peg':
            case 'jpg':
                if (function_exists("imagejpeg")) {
                    header("Content-type: image/jpeg");
                    imagejpeg(imagecreatefromjpeg($path));
                    die;
                }
                break;
            case 'gif':
                if (function_exists("imagegif")) {
                    header("Content-type: image/gif");
                    imagegif(imagecreatefromgif($path));
                    die;
                }                
                break;
            case 'png':
                if (function_exists("imagepng")) {
                    header("Content-type: image/png");
                    
                    /**
                     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
                     * Aplica a transparência para as imagens PNG
                     */
                    $img = imagecreatefrompng($path);
                    imagealphablending($img, true);
                    imagesavealpha($img, true);
                    
                    imagepng($img);
                    die;
                }                
                break;
            case 'bmp':
                if (function_exists("imagewbmp")) {
                    header("Content-type: image/vnd.wap.wbmp");
                    imagewbmp(imagecreatefromwbmp($path));
                    die;
                }                
                break;
        }   
        header("Content-type: image/jpeg");
        $im  = imagecreate(300, 60); /* Create a black image */
        $bgc = imagecolorallocate($im, 255, 255, 255);
        $tc  = imagecolorallocate($im, 0, 0, 0);
        imagefilledrectangle($im, 0, 0, 300, 60, $bgc);
        /* Output an errmsg */
        imagestring($im, 8, 10, 10, "Imagem não encontrada!! " . $ext, $tc);
        imagejpeg($im);   
        die;
    }
    
    /**
     * Pegar Uma sessao existente ou criar um nova para manipulação de dados.
     * Caso passado a key retorna somente o valor guardado na sessao caso exista
     * Caso passado a key e valor sera guardado na sessao sobreescrevendo o antigo se houver.
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0 
     * @since 25-04-2016 
     * @param bollean | string $key    chave valor guardado na sessao
     * @param bollean | mixed $valor   Valor a ser guardado na sessao 
     * @return \Zend\Session\Container | Value in sessao
     */
    public function sessao($key = false, $valor = NULL) {
        if(is_null($this->sc)){
            $this->sc = new SessionContainer('Application');
        }
        if($key){
            if(!is_null($valor)){
                $this->sc->$key = $valor;
            }else{
                return $this->sc->$key;
            }
        }
        return $this->sc;
    }

}
