<?php

namespace Application\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;

/**
 * PartialObj
 * View Helper para trabalhar com pedaços do html
 * Possivel passar uma referencia do obj a ser trabalhado na view.
 * Possivel passar um array como dados para view.
 * @author Paulo Cordeiro Watakabe <watakabe05@gmail.com>
 */
class PartialObj extends AbstractHelper {

    /**
     * Nome do modulo da Aplicação default /var/www/tcmed/.
     * @var string
     */
    protected $base;
    
    /**
     * Nome do modulo da Aplicação default Application.
     * @var string
     */
    protected $mod;
    
    /**
     * Nome do sub modulo da Aplicação default application
     * @var string 
     */
    protected $subMod;
    
    /**
     * Guarda items temporarios do objetos
     * @var mixed
     */
    protected $temp;
    
    /**
     * Metodo magico que é acionado quando acessado esta classe pela view
     * Configura o modulo e subModulo a ser trabalhado.
     * 
     * @param string $subMod  Nome do sub modulo da Aplicação default application
     * @param string $mod     Nome do modulo da Aplicação default Application
     * @param string $base     Nome do modulo da Aplicação default /var/www/tcmed
     * @return \Application\View\Helper\PartialObj
     */
    public function __invoke( $subMod = '', $mod = '', $base = '') {
        if(empty($subMod)){
            $this->subMod = 'application' ;
        }else{
            $this->subMod = $subMod ;
        }
        if(empty($mod)){
            $this->mod = 'Application' ;
        }else{
            $this->mod = $mod ;
        }
        if(empty($base)){
            $this->base = getcwd() ;
        }else{
            $this->base = $base ;
        }
        return $this;
    }

    /**
     * Valida arquivo phtml
     * Chama o arquivo phtml e o renderiza na tela
     * @param string $arq
     * @param object $obj
     * @param array $d
     */
    public function partial($arq = '', $obj = null, array $d = []) {        
//        $dirBase = '/var/www/tcmed/module/Application/view/';
        if(substr_count($arq, '/') == 1){
            $arq = $this->subMod . '/' . $arq ;
        }
        $fullpath = $this->base. '/module/' . $this->mod . '/view/' . $arq . '.phtml';        
        if(file_exists($fullpath)){
            include $fullpath;
        }else{
            echo '<h1>Partial não encontrado em ' . $fullpath;
            echo '<h1>contados / ' . substr_count($arq, '/');
        }                
    }
    
    /**
     * Metodo magico que é acionado quando uma view incluida chama ela uma outra instancia para inclusão
     * Configura o modulo e subModulo a ser trabalhado.
     * 
     * @param string $subMod  Nome do sub modulo da Aplicação default application
     * @param string $mod     Nome do modulo da Aplicação default Application
     * @param string $base     Nome do modulo da Aplicação default /var/www/tcmed
     * @return \Application\View\Helper\PartialObj
     */
    public function partialObj($subMod = '', $mod = '', $base = '') {
        $this->__invoke($subMod, $mod, $base);
        return $this;
    }

    /**
     * Devido a um erro de execução que diz que a classe nao pode ser convertida em string
     * Colocando esse metodo o erro some
     * @return string
     */
    public function __toString() {
        return 'Application\View\Helper\PartialObj';
    }
    
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 18-10-2016 
     * @param type $property
     * @return mixed
     */
    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return NULL;
    }

    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 18-10-2016 
     * @param string $property
     * @param mixed $value
     */
    public function __set($property, $value) {
        $this->$property = $value;
    }
}
