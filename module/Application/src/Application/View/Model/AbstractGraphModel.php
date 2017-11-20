<?php

namespace Application\View\Model;

/*
 * license is normal license :P
 */

/**
 * Description of GraphModel
 * 
 * Biblioteca jpgraph para gera graficos para relatorios do sistema
 *
 * @author PauloWatakabe <watakabe05@gmailcom>
 * @since 27-01-2016
 */
abstract class AbstractGraphModel  {
    
    
    /**
     * Instacia da classe jpgraph que gerara os graficos
     * 
     * @var \Graph
     */
    protected $graph ;
    
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
     * Dados para view 
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
        $this->template = $this->trataNomeCamelCase($this->action);
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
        throw new \Exception('Arquivo não encontrado ' . $fullpath);
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
    
    /**
     * Imprime o grafico baseado em um phtml 
     * @author PauloWatakabe <watakabe05@gmailcom>
     * @param integer $qtdPag
     */
    public function printGraph($download=FALSE, $name='') {        
//        $fullpath = "/var/www/tcmed/module/Application/view/tcmed/ficha-clinicas/get-pdf.phtml";            
        $fullpath = $this->getTemplateFullPath($this->template); 
        extract($this->data, EXTR_PREFIX_SAME, "rw");
        
        if(file_exists($fullpath)){    
            include $fullpath;                            
        }  else {
            throw new \Exception('Arquivo não encontrado ' . $fullpath);
        }
        
        if($download){
            if(empty($name)){
                $name = 'grafico_' . date('d-m-Y-H-i-s') . '.jpg';
            }
            $this->render($name);
        }else{
            $this->render();
        }
    }
    
    /**
     * Envia o grafico renderizado para o browser ou salva em disco
     * streamed back to the browser
     * @author PauloWatakabe <watakabe05@gmailcom>
     * @param string $destinationPath caminho absoluto para gravação
     */
    public function render($destinationPath='') {
        // Display the graph
        $this->graph->Stroke($destinationPath);
    }
    
    /**
     * Retorna a instancia existente da classe Graph
     * 
     * @author PauloWatakabe <watakabe05@gmailcom>
     * @return \Graph
     */
    public function getGraph() {
        return $this->graph;
    }  
    
}
