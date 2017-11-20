<?php

namespace Application\View\Model;

/*
 * license is normal license :P
 */
require getcwd() . '/vendor/jpgraph/src/jpgraph.php';
require getcwd() . '/vendor/jpgraph/src/jpgraph_line.php';

/**
 * Description of GraphModel
 * 
 * Biblioteca jpgraph para gera graficos para relatorios do sistema
 *
 * @author PauloWatakabe <watakabe05@gmailcom>
 * @since 27-01-2016
 */
class GraphModel {
    
    /**
     * Instacia da classe jpgraph que gerara os graficos
     * 
     * @var \Graph
     */
    protected $graph ;
    
    /**
     * Valores a serem plotados no grafico
     * @var array De numeros
     */
    protected $ydata ;
    
    /**
     * Valores do titulo do eixo X
     * @var array De string
     */
    protected $xdata ;
    
    /**
     * Instacia de \LinePlot
     * @var \LinePlot Instancia da Classe de linhas para graficos 
     */
    protected $lineplot ; 
    
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
        $this->template = $this->trataNomeCamelCase($this->action);
    }
    
    /**
     * Metodo que nao encontrado e verifica se existe o metodo na classe graph ou lineplot caso positivo redireciona
     * @author PauloWatakabe <watakabe05@gmailcom>
     * @param string $m
     * @param array $arguments
     * @return somethink or nothing
     */
    public function __call($m, $arguments) {
        if (method_exists($this->graph, $m)) {
            return call_user_func_array([$this->graph, $m], $arguments);
        }
        if (method_exists($this->lineplot, $m)) {
            return call_user_func_array([$this->lineplot, $m], $arguments);
        }
    }
    
    /**
     * Metodo que cria a instancia do grafico com os parametros basico
     * @author PauloWatakabe <watakabe05@gmailcom>
     * @param integer $height        Altura em pixel do grafico obrigatorio
     * @param integer $width         Largura em pixel do grafico obrigatorio
     * @param array   $options       Opçoes para criar uma lineplot para este grafico
     * @param string  $aCachedName   Noma para ser usando no cacheamento deste grafico(Name for image file in cache directory)
     * @param integer $aTimeout      tempo de vida do cache(Timeout in minutes for image in cache)
     * @param boolean $aInline       (If true the image is streamed back in the call to Stroke() If false the image is just created in the cache)
     */
    public function newGraph($height = 350, $width = 250, array $options = [],$aCachedName='',$aTimeout=0,$aInline=true) {
        $this->graph = new \Graph($height,$width,$aCachedName,$aTimeout,$aInline);
        !empty($options) AND $this->setGraphOptions($options);     
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
            echo '<pre>Arquivo não encontrado.', var_dump($fullpath), '</pre>';
            die;            
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
     * Facilita a configuracao das opcoes basicas de um grafico
     * @author PauloWatakabe <watakabe05@gmailcom>
     * @param array $options  'scale'           se não for especificao usa o padrao textlin
     *                        'lengend'         lengenda do grafico
     *                        'ydata'           um array com valores do eixo y
     *                        'xdata'           um array com valores do eixo x
     *                        'lineplotOptions' um array com valores para configuracao do lineplot cor, tipo linha etc.
     */
    public function setGraphOptions(array $options) {  
        isset($options['scale']) AND $this->setScale(!empty($options['scale']) ? $options['scale'] : 'textlin');
        if(isset($options['ydata'])){
            $this->ydata = $options['ydata'];
            $this->xdata = (isset($options['xdata']) ? $options['xdata'] : false);
            // Create the linear plot
            $this->lineplot = $this->getNewLineplot();
            isset($options['lineplotOptions']) AND $this->setLinePlotOptions($options['lineplotOptions']);     
        }    
        isset($options['legend']) AND $this->graph->title->Set($options['legend']);
    }
    
    /**
     * Configura a cor da linha do lineplot
     * @author PauloWatakabe <watakabe05@gmailcom>
     * @param string $color
     */
    public function setColor($color = 'blue') {
        $this->lineplot->SetColor($color);
    }
    
    /**
     *  Configura a scala da grafico
     *  Facilitador caso primeiro argumento for um array desmonta os dados redirecionando para as variaveis do metodo
     *  Specify x,y scale. Note that if you manually specify the scale
     *  you must also specify the tick distance with a call to Ticks::Set()
     * 
     * @author PauloWatakabe <watakabe05@gmailcom>
     * @param string  $aAxisType
     * @param integer $aYMin
     * @param integer $aYMax
     * @param integer $aXMin
     * @param integer $aXMax
     * @return \Application\View\Model\GraphModel
     */
    public function setScale($aAxisType = 'textlin',$aYMin=1,$aYMax=1,$aXMin=1,$aXMax=1) {
        // Create the graph. These two calls are always required
        if(is_array($aAxisType)){
            isset($aAxisType[4]) && $aXMax = $aAxisType[4];
            isset($aAxisType[3]) && $aXMin = $aAxisType[3];
            isset($aAxisType[2]) && $aYMax = $aAxisType[2];
            isset($aAxisType[1]) && $aYMin = $aAxisType[1];
            $aAxisType = $aAxisType[0];
        }
        $this->graph->SetScale($aAxisType,$aYMin,$aYMax,$aXMin,$aXMax);
        
        return $this;
    }    
    
    /**
     * Adiciona e valida um linha a ser adiciona neste grafico
     * 
     * @author PauloWatakabe <watakabe05@gmailcom>
     * @param array $options      array de opções a serem adicionadas nesta linha
     * @param \LinePlot $lineplot classe lineplot a ser adiciona padrao null
     * @return \Application\View\Model\GraphModel
     */
    public function addLineplot(array $options = [], \LinePlot $lineplot = null) {
        if(!is_null($lineplot)){
            $this->lineplot = $lineplot;
        }
        if(is_null($this->lineplot)){
            echo 'Não foi configurado um classe lineplot valida!!';
            die;
        }
        $this->graph->add($this->lineplot);
        !empty($options) AND $this->setLinePlotOptions($options); 
        return $this;
    }
    
    /**
     * Facilitador configura as opçoes da linha por um array
     * 
     * @author PauloWatakabe <watakabe05@gmailcom>
     * @param array $options
     */
    public function setLinePlotOptions(array $options) {
        $this->addLineplot(); // deve ser adionado antes de setar as opcoes por causa do reset dos valores
        isset($options['marktype'])  AND $this->lineplot->mark->SetType($options['marktype']);
        isset($options['markcolor']) AND $this->lineplot->mark->SetFillColor($options['markcolor']);
        isset($options['markwidth']) AND $this->lineplot->mark->SetWidth($options['markwidth']);
        isset($options['color'])     AND $this->lineplot->SetColor($options['color']);
        isset($options['legend'])    AND $this->lineplot->SetLegend($options['legend']);
        
    }

    /**
     * Setar os valoresa da eixo y
     * 
     * @author PauloWatakabe <watakabe05@gmailcom>
     * @param array $ydata
     */
    public function setYdata(array $ydata) {
        $this->ydata = $ydata;
    }
    
    /**
     * Setar os valoresa da eixo x
     * 
     * @author PauloWatakabe <watakabe05@gmailcom>
     * @param array $xdata
     */
    public function setXdata(array $xdata) {
        $this->xdata = $xdata;
    }
    
    /**
     * Facilitador cria uma instancia lineplot usando como parametros os dados do eixo y e x.
     * 
     * @author PauloWatakabe <watakabe05@gmailcom>
     * @param type $ydata
     * @param type $xdata
     * @return \Application\View\Model\GraphModel
     */
    public function setNewLineplot($ydata, $xdata = false) {
        $this->ydata = $ydata; 
        $this->xdata = $xdata;
        $this->lineplot = new \LinePlot($this->ydata, $this->xdata);
        return $this;
    }
    
    /**
     * Facilitador Cria uma instacia de lineplot com os dados do eixo y e x ja existentes
     * 
     * @author PauloWatakabe <watakabe05@gmailcom>
     * @return \LinePlot
     */
    public function getNewLineplot() {
        $lineplot = new \LinePlot($this->ydata, $this->xdata);
        return $lineplot ;
    }
    
    /**
     * Retorna a instancia existente da classe lineplot
     * 
     * @author PauloWatakabe <watakabe05@gmailcom>
     * @return type
     */
    public function getLineplot() {
        return $this->lineplot ;
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
