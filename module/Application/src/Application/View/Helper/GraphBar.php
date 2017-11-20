<?php

namespace Application\View\Helper;

/*
 * license is normal license :P
 */
require getcwd() . '/vendor/jpgraph/src/jpgraph.php';
require getcwd() . '/vendor/jpgraph/src/jpgraph_bar.php';

use Zend\Form\View\Helper\AbstractHelper;

/**
 * GraphBar
 * View Helper para trabalhar com graficos tipo Barras
 * @author Paulo Cordeiro Watakabe <watakabe05@gmail.com>
 * @since 01/07/2016
 * @version 1.0
 */
class GraphBar extends AbstractHelper {

    /**
     * Classe basica para manipular Graficos.
     * @var \Graph
     */
    protected $graph;
    
    /**
     * Thema para graficos .
     * @var \UniversalTheme
     */
    protected $universalTheme;
    
    /**
     * Barra(s) a ser inserido no para graficos .
     * @var array of \BarPlot
     */
    protected $barPlot = [];
    
    /**
     * Array com os callables para configurar barras separadamente .
     * @var array of function
     */
    protected $barPlotCallable = [];
    
    /**
     * Identificação para posterior recuperação do objeto \BarPlot .
     * @var array of key
     */
    protected $barPlotIdent = [];
    
    /**
     * Agrupador de Barra(s) a ser inserido no para graficos .
     * @var \GroupBarPlot
     */
    protected $groupBarPlot;
    
    /**
     * Metodo magico que é acionado quando acessado esta classe pela view
     * Configura o modulo e subModulo a ser trabalhado.
     * 
     * @param string $subMod  Nome do sub modulo da Aplicação default application
     * @param string $mod     Nome do modulo da Aplicação default Application
     * @param string $base     Nome do modulo da Aplicação default /var/www/tcmed
     * @return \Application\View\Helper\GraphBar
     */
    public function __invoke() {
        return $this;
    }
    
    /**
     * Metodo que cria a instancia do grafico com os parametros basico
     * @author PauloWatakabe <watakabe05@gmailcom>
     * @since 02/07/25016
     * @version 1.0
     * @param integer $height        Altura em pixel do grafico obrigatorio
     * @param integer $width         Largura em pixel do grafico obrigatorio
     * @param string  $aCachedName   Noma para ser usando no cacheamento deste grafico(Name for image file in cache directory)
     * @param integer $aTimeout      tempo de vida do cache(Timeout in minutes for image in cache)
     * @param boolean $aInline       (If true the image is streamed back in the call to Stroke() If false the image is just created in the cache)
     * @return \Graph
     */
    public function newGraph($height = 350, $width = 250, $aCachedName='auto',$aTimeout=0,$aInline=true) {
        $this->graph = new \Graph($height,$width,$aCachedName,$aTimeout,$aInline);
        $this->barPlot = [];
        return $this->graph;
    }
    
    /**
     * Gera um thema para o grafico
     * @author PauloWatakabe <watakabe05@gmailcom>
     * @since 03/07/25016
     * @version 1.0
     * @return \UniversalTheme
     */
    public function newUniversalTheme() {
        $this->universalTheme = new \UniversalTheme();
        return $this->universalTheme;
    }
    
    /**
     * Gera um novo objeto BarPlot e armazena e o retorna
     * @author PauloWatakabe <watakabe05@gmailcom>
     * @since 03/07/25016
     * @version 1.0
     * @param array $data Obrigatorio array com os dados para gerar as barras 
     * @param string $identifiquer Opcional um identificados desse objeto
     * @param callable $callable Opcional um callable para fazer configuração da barra apos ser incluido no grafico
     * @return \BarPlot
     */
    public function newBarPlot(array $data, $identifiquer = '', $callable = null) {
        if(is_callable($identifiquer)){
            $callable = $identifiquer;
            $identifiquer = '';
        }
        $this->barPlot[] =  new \BarPlot($data);
        // mover o ponteiro do array para o fim
        end($this->barPlot);
        // registrar identificador
        if(!empty($identifiquer)){
            $this->barPlotIdent[$identifiquer] =  key($this->barPlot);
        }
        // registrar callable
        if(is_callable($callable)){
            $this->barPlotCallable[key($this->barPlot)] = $callable;
        }        
        return $this->barPlot[key($this->barPlot)];
    }
    
    /**
     * Retorna o objeto barplot que esta armazenado 
     * @author PauloWatakabe <watakabe05@gmailcom>
     * @since 03/07/25016
     * @version 1.0
     * @param string $ident Identificador do novo objeto barplot a ser gerado
     * @return \BarPlot | NULL caso não encontre a chave especifica
     */
    public function getBarPlot($ident) {
        if(isset($this->barPlotIdent[$ident])){
            return $this->barPlot[$this->barPlotIdent[$ident]];
        }
        if(isset($this->barPlot[$ident])){
            return $this->barPlot[$ident];
        }
        return null;
    }
    
    /**
     * Agrupador de barras para o grafico 
     * @author PauloWatakabe <watakabe05@gmailcom>
     * @since 03/07/25016
     * @version 1.0
     * @param array $data Array com os objeto barplot a serem agrupados caso vazio sera usado os armazenados anteriormente
     * @param boole $add  True faz a inserção do grupo na classe grafico e executa os callable tb armazenados padrao é true
     * @return \GroupBarPlot
     */
    public function newGroupBarPlot($data = [], $add = true) {
        if(is_bool($data)){
            $add = $data;
            $data = [];
        }
        empty($data) && $data = $this->barPlot;
        $this->groupBarPlot = new \GroupBarPlot($data);
        if($add){
            $this->graph->Add($this->groupBarPlot);            
            foreach ($this->barPlotCallable as $key => $function) {
                // executar o callable para configurar as barras
                $function($this->barPlot[$key]);
            }
        }
        return $this->groupBarPlot;    
    }
    
    /**
     * Gera o grafico que esta configurado na memoria
     * @author PauloWatakabe <watakabe05@gmailcom>
     * @since 03/07/25016
     * @version 1.0
     * @param type $path Opcional local onde sera salvo a imagem do grafico png e jpg suportados caso em branco envia o binario da imagem ao browser
     * @param type $imageHelper Opcional para proteger a imagem do grafico sera gerado um link para ser usado pelo browser na tg img.
     * @return nothing | binario of image for browser
     */
    public function getGraph($path = '', $imageHelper = null) {
        if(empty($path)){
            $this->graph->Stroke();
            return;
        }
        // Mudar padrão para jpg por padrão é gerado formato png
        if(strpos($path, '.jpg') !== false){
            $this->graph->img->SetImgFormat('jpeg');
        }
        $this->graph->Stroke($path);
        if(is_null($imageHelper)){
            return;            
        }
        return $imageHelper->generateSrcCrypt($path);
    }
     
    /**
     * Teste rapido para saber se esta funcionando o gerador de barra
     * @author PauloWatakabe <watakabe05@gmailcom>
     * @since 03/07/25016
     * @version 1.0
     * @return nothing | binario of image for browser
     */
    public function getGraphExemplo($path='') {
        $data1y=array(47,80,40,116);
        $data2y=array(61,30,82,105);
        $data3y=array(115,50,70,93);

        // Create the graph. These two calls are always required
        $graph = $this->newGraph(350, 250);
        $graph->SetScale("textlin");

        $theme_class = $this->newUniversalTheme();
        $graph->SetTheme($theme_class);

        $graph->yaxis->SetTickPositions(array(0,30,60,90,120,150), array(15,45,75,105,135));
        $graph->SetBox(false);

        $graph->ygrid->SetFill(false);
        $graph->xaxis->SetTickLabels(array('A','B','C','D'));
        $graph->yaxis->HideLine(false);
        $graph->yaxis->HideTicks(false,false);

        // Create the bar plots
        $b1plot = $this->newBarPlot($data1y);
        $b2plot = $this->newBarPlot($data2y);
        $b3plot = $this->newBarPlot($data3y);

        // Create the grouped bar plot
        $gbplot = $this->newGroupBarPlot(array($b1plot,$b2plot,$b3plot));
        // ...and add it to the graPH
        $graph->Add($gbplot);

        $b1plot->SetColor("white");
        $b1plot->SetFillColor("#cc1111");

        $b2plot->SetColor("white");
        $b2plot->SetFillColor("#11cccc");

        $b3plot->SetColor("white");
        $b3plot->SetFillColor("#1111cc");

        $graph->title->Set("Bar Plots");

        // Display the graph
        if(strpos($path, '.jpg') !== false){
            $graph->img->SetImgFormat('jpeg');
        }
        $graph->Stroke($path);        
    }
    
}
