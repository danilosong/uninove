<?php

namespace Application\View\Model;

/*
 * license is normal license :P
 */

require getcwd() . '/vendor/mpdf60/mpdf.php';
//require '/var/www/tcmed/vendor/mpdf/mpdf.php'; versao 5.4
/**
 * Description of PdfModel
 *
 * @author PauloWatakabe <watakabe05@gmailcom>
 */
class PdfModel {
    /**
     * Nome do modulo da Aplicação default /var/www/tcmed/.
     * @var string
     */
    protected $basePath;
    
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
     * Nome do controller da Aplicação default application
     * @var string 
     */
    protected $controller;
    
    /**
     * Nome da action da Aplicação default application
     * @var string 
     */
    protected $action;
    
    /**
     * Nome da template da Aplicação default application
     * @var string 
     */
    protected $template;
    
    /**
     * Orientação atual da pagina do pdf
     * @var string  Padrão e portrait (retrato)
     */ 
    protected $orientation = 'P'; 
    
    /**
     * Guarda o conteudo html a ser injetado no conversor de htmp para pdf
     * @var string 
     */
    protected $html = '';
    
    /**
     * Plugin para gerar o pdf
     * @var \mPDF
     */
    protected $mpdf;
    
    /**
     * Dados para view do pdf
     * @var array
     */
    protected $data =  [];
    
    /**
     * Construtor que recebe os parametros basicos da configuraçao caso nao for passado assume o valores padrao 
     * 
     * @author PauloWatakabe <watakabe05@gmailcom>
     * @param string $action       Nome da action para usar no template.
     * @param string $controller   Nome do controler para encontrar a pasta do template.
     * @param string $subMod       Nome do subModulo do sistema usado para encontrar o template.
     * @param string $mod          Nome da pasta do modulo.
     * @param string $basePath     Caminho em que se encontra o modulo base.
     */
    public function __construct($action = '', $controller = '', $subMod = '', $mod = '', $basePath = '') {
        $this->subMod     = empty($subMod)     ? 'Application' : $subMod;
        $this->controller = empty($controller) ? 'index'       : $controller;
        $this->action     = empty($action)     ? 'index'       : $action;
        $this->mod        = empty($mod)        ? 'Application' : $mod;
        $this->basePath   = empty($basePath)   ? getcwd()      : $basePath;
        $this->mpdf = new \mPDF();
        $this->template = $this->trataNomeCamelCase($this->action);
//        ini_set( "allow_url_fopen" , "On" );
    }
    
    /**
     * Mudar para modo de impressão paisagem com margens reduzidas
     * 
     * @author PauloWatakabe <watakabe05@gmailcom>
     */
    public function setPagLandescape() {        
        $this->mpdf->AddPage('L','','','','',10,0,10,0,18,12);
        $this->orientation = 'L';
    }
    
    /**
     * Mudar para modo de impressão paisagem com margens reduzidas
     * 
     * @author PauloWatakabe <watakabe05@gmailcom>
     */
    public function setPagPortrait() {        
        $this->mpdf->AddPage('P','','','','',10,0,10,0,18,12);
        $this->orientation = 'P';
    }
    
    /**
     * Converte camelCase para camel-case
     * @author PauloWatakabe <watakabe05@gmailcom>
     * @param string $strController no formato camelCase
     * @return string convertida do camelCase para camel-case
     */
    public function trataNomeCamelCase($strCamelCase) {
        $arr = preg_split('/(?=[A-Z])/', $strCamelCase);
        if (count($arr) > 1) {
            $strCamelCase = '';
            foreach ($arr as $key => $value) {
                $strCamelCase .= (($key > 0) ? '-' : '') . strtolower($value);
            }
        }
        return $strCamelCase;
    }
    
    /**
     * Imprime o pdf baseado em um phtml 
     * 
     * @author PauloWatakabe <watakabe05@gmailcom>
     * @version 1.2
     * @since 10-02-2016
     * @param string $name       Nome do arquivo a ser gerado
     * @param string $download   Tipo de retorno Browser(vazio), Donload(D), salvar em disco(F) e Buffer(S) 
     * @param integer $qtdPag    Quantidade de paginas
     */
    public function printPdf($name='', $download='D', $qtdPag = 1) {        
//        $fullpath = "/var/www/tcmed/module/Application/view/tcmed/ficha-clinicas/get-pdf.phtml";            
        $fullpath = $this->getTemplateFullPath($this->template); 
        extract($this->data, EXTR_PREFIX_SAME, "rw");
        ob_start();
        
        if(file_exists($fullpath)){    
            include $fullpath;                            
        }  else {
            echo '<pre>Arquivo não encontrado.', var_dump($fullpath), '</pre>';
            die;            
        }
        
        $this->html = ob_get_clean();
        $this->mpdf->WriteHTML($this->html);   
        
        if(empty($name)){
            $name = 'pdf_' . date('d-m-Y-H-i-s') . '.pdf';
        }
        $this->mpdf->Output($name, $download);
        return $this->html;// retorna html 
    }
    
    /**
     * full path para abrir o arquivo
     * 
     * @author PauloWatakabe <watakabe05@gmailcom>
     * @param string $template Nome do template
     * @return string Caminho completo para abrir o arquivo
     */
    public function getTemplateFullPath($template) {
        return $this->basePath. '/module/' . $this->mod . '/view/' . $this->subMod . '/'  . $this->trataNomeCamelCase($this->controller) . '/' . $this->trataNomeCamelCase($template) . '.phtml'; 
    }
    
    /**
     * Gera o caminho do partial e verifica se partial existe
     * 
     * @author PauloWatakabe <watakabe05@gmailcom>
     * @param string $template Nome do template
     * @return string Caminho completo para abrir o arquivo
     */
    public function partial($template) {
        $fullpath = $this->getTemplateFullPath($template); 
        if(file_exists($fullpath)){
            return $fullpath;
        }
        echo '<pre>Arquivo não encontrado.', var_dump($fullpath), '</pre>';
        die;
    }
    
    /**
     * Variaveis a serem usadas em todos os templates que existirem
     *  
     * @author PauloWataka be <watakabe05@gmailcom>
     * @param array $data
     */
    public function setVars(array $data=[]) {
        $this->data = $data;
    }
    
    /**
     * Nome do arquivo do template
     * 
     * @author PauloWatakabe <watakabe05@gmailcom>
     * @return string
     */
    public function getTemplate() {
        return $this->template;
    }

    /**
     * Nome do arquivo do template
     * 
     * @author PauloWatakabe <watakabe05@gmailcom>
     * @param string $template
     * @return \Application\View\Model\PdfModel
     */
    public function setTemplate($template) {
        $this->template = $this->trataNomeCamelCase($template);
        return $this;
    }
    
    public function getMpdf() {
        return $this->mpdf;
    }

    
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 26-10-2016 
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
     * @since 26-10-2016 
     * @param string $property
     * @param mixed $value
     */
    public function __set($property, $value) {
        $this->$property = $value;
    }
    
}
