<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
/**
 * Table
 * View Helper para exibir dados em tabelas
 * @author Paulo Cordeiro Watakabe <watakabe05@gmail.com>
 */
class 
Table extends AbstractHelper {

    /**
     * com propriedades a serem colocas no td. 
     * @var array $tdopt 
     */
    protected $tdopt;
    
    /**
     * com a lista do conteudo do cabeçalho.
     * @var array $coluns 
     */
    protected $coluns;
    
    /**
     * com a lista do conteudo da linha.
     * @var array $data 
     */
    protected $data;
    
    /**
     * valor numero da coluna que vai ter os botões para edição.
     * @var int $editLine 
     */
    protected $editLine;
    
    /**
     * com a lista do conteudo do rodapé.
     * @var array $foot 
     */
    protected $foot;
    
    /**
     * Colocar uma função para substituir a função de edição padrão.
     * @var lambda
     */
    protected $funcEdit;
    
    /**
     * Array com informações para ordenação e exibição dos titulos.
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 25-04-2016 
     * @var array Detalhes da ordenação ex ['coluna' => 'ASC','colunaB' => 'DESC'].
     */
    protected $orderBy = [];
    
    /**
     * Div que faz a marção de scrol horizontal da table
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 15-08-2016 
     * @var string Detalhes da ordenação ex ['coluna' => 'ASC','colunaB' => 'DESC'].
     */
    protected $tableRespView;

    /**
     * Metodo chamado pela view ao executar esta classe Table
     * @param string $acao metodo a executado
     * @param string $options para os metodos simples
     * @param array  $options para os metodos com mais configuração
     */
    public function __invoke($options = null) {
        $this->resetTableRespView();
        if($options){
            $this->openTable($options);
        }
        return $this;   
    }
    
    /**
     * Colocar um div com a class de resp-table para gerar o scrool horizontal quando o conteudo for maior que a tela
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 11-12-2016
     * @param string $div
     */
    public function resetTableRespView($div='') {
        if(empty($div)){
            $this->tableRespView = '<div class="resp-table">';
        }else{
            $this->tableRespView = $div;
        }
    }
    
    /**
     * Devido a um erro de execução que diz que a classe nao pode ser convertida em string
     * Colocando esse metodo o erro some
     * @return string
     */
    public function __toString() {
        return 'Application\View\Helper\Table';
    }

    /**
     * Abre a tag table com opções default ou opções passadas por parametro.
     * @param array $options
     */
    public function openTable($options) {
        if(!isset($options['noScrool'])){
            echo $this->tableRespView;
            $this->tableRespView = '</div>';
        }else{
            $this->tableRespView = '';
            if(isset($options['default'])){
                $options = $options['default'];
            }else{
                unset($options['noScrool']);
            }
        }
        if($options === TRUE){
            echo '<table class="table table-striped table-bordered table-hover table-condensed">' , PHP_EOL;
            return;
        }
        if(is_string($options)){
            echo '<table class="table table-striped table-bordered table-hover table-condensed ' . $options . '">' , PHP_EOL;                
            return;
        }
        if(is_array($options)){
            echo '<table';  
            foreach ($options as $atributo => $value) {
                echo ' ', $atributo, '="', $value, '"';
            }            
            echo '>';  
            return;
        }
        echo '<table>', PHP_EOL;  
    }

    
    /**
     * Setar a ordenação atual para exibição correta dos indicadores de ordenação.
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0 
     * @since 26-04-2016 
     * @param array $orderBy
     */
    public function setOrderList(array $orderBy) {
        $this->orderBy = $orderBy;
    }
    
    /**
     * Renderiza o cabeçalho html da table e configura classe para proximas chamadas
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.2 
     * @since 25-04-2016 Melhoria Adiciona parametros por array e ordenador padrao de colunas      
     * @version 1.5
     * @since 28-03-2017 Melhoria parametro para controle das tag thead.        
     * @param array $coluns
     * @param string $trOption
     * @param array $css
     * @param array $js
     * @param array $tdOption
     * @param string $showTag
     * @return caso não for passado um array com coluns
     */
    public function renderThead(Array $coluns, $trOption = '', $css = [], $js = [], $tdOption = [], $showTag = 'all') {
        $this->coluns = $coluns;
        if($showTag == 'all' OR $showTag == 'open'){
            echo "<thead class='th'>", PHP_EOL;            
        }
        echo "<tr ", $trOption, ">", PHP_EOL;
        $hidden = '';
        foreach ($this->coluns as $key => $value) {
            if(is_array($value)){
                $opt   = (isset($value['options']))  ? ' ' . $value['options']           : '';
                if(isset($value['order']) AND isset($this->orderBy[$value['order']])){
                    $opt .= ' class="ordenar_' . $this->orderBy[$value['order']] . '"';
                }
                $order = (isset($value['order']))    ? ' onClick="$.orderCol(\'' . $value['order'] . '\', this);"'  : '';
                $lnk   = (isset($value['css']))      ? ' class="' . $value['css'] . '"'  : '';
                $eve   = (isset($value['js']))       ? ' onClick="' . $value['js'] . '"' : '';
                $label = (isset($value['label']))    ? $value['label']                   : '';
            }else{
                $order = '';
                $opt = (isset($tdOption[$key])) ? ' ' . $tdOption[$key]         : '';
                $lnk = (isset($css[$key]))      ? ' class="' . $css[$key] . '"' : '';
                $eve = (isset($js[$key]))       ? ' ' . $js[$key]               : '';
                $label = &$value;
            }            
            echo "\t", '<th', $opt, $lnk, $eve, $order, '>', $label, '<span></span></th>', PHP_EOL;
        }
        foreach ($this->orderBy as $key => $value) {
            $hidden .= '<input type="hidden" name="orderby[' . $key . ']" id="orderby_' . $key . '" value="' . $value . '" />' . PHP_EOL ;
        }
        echo "</tr>", PHP_EOL;
        if($showTag == 'all' OR $showTag == 'close'){
            echo
                "</thead>", PHP_EOL,
                $hidden,
                "<tbody>", PHP_EOL;
        }
        if(is_null($this->editLine)){            
            $this->setEditLine('last');
        }
    }

    /**
     * Renderiza a linha com os dados
     * Faz sterilização dos td conforme parametros se houver
     * Monta td para edição dos registro na posição configurada
     * @param array $options
     */
    public function renderLine(Array $data, $trOpt = '') {
        echo "<tr ", ((!empty($trOpt)) ? $trOpt : ''), ">", PHP_EOL;
        $this->data = $data;
        foreach ($this->data as $key => $value) {
            if($key === $this->editLine){
                $this->renderEditLine($value,$data);
                continue;
            }
            $td = (isset($this->tdopt[$key]))?$this->tdopt[$key]:'';
            echo "\t<td ", $td, ">", $value, "</td>", PHP_EOL;
        }
        echo "</tr>", PHP_EOL;
    }

    /**
     * Renderiza rodape da tabela conforme dados do array
     * @param array $options
     */
    public function renderTfoot(array $data,array $options= []) {
        $this->foot = $data;
        echo     "</tbody>",PHP_EOL
                ,"<tfoot>",PHP_EOL
                ,"<tr>", PHP_EOL;        
        foreach ($this->foot as $key => $value) {
            $td  = (isset($this->tdopt[$key]))?$this->tdopt[$key] . ' ':'';
            $css = (isset($options[$key]))    ?$options[$key]. ' '     :'';        
            echo "\t<td ", $td, $css, ">", $value, "</td>", PHP_EOL;
        }        
        echo "<tr>",PHP_EOL
                ,"</tfoot>", PHP_EOL;
    }

    /**
     * Renderiza td com botões para editar ou deletar registro
     * @param string $value
     */
    public function renderEditLine($value, &$data) {        
        if (is_callable($this->funcEdit)) {
            $lambda = $this->funcEdit;
            $lambda($value, $data);
            return;
        }
        echo "\t", '<td nowrap style="font-size: 14pt;">',
                '<span class="hand" onClick="edit(\'', $value, '\',this)" title="Editar"><i class="fa fa-pencil"></i>','</span>&nbsp;', PHP_EOL,
                '&nbsp;',
                '<span class="hand" onClick="del(\'', $value, '\',this)" title="Excluir"><i class="fa fa-trash"></i></span>',
             "</td>", PHP_EOL;   
    }

    /**
     * Fecha a tag table 
     * Se tiver um rodape apenas fecha table
     * @param string $options
     */
    public function renderCloseTable() {
        if (is_null($this->foot)) {
            echo "</tbody>", PHP_EOL;
        }
        echo <<<EOF
    </table>
{$this->tableRespView}  
<script lang="javascript">        
    $(function () {
        $('.ordenar_DESC').find('SPAN').html('<i class="fa fa-arrow-circle-up"></i>');
        $('.ordenar_ASC' ).find('SPAN').html('<i class="fa fa-arrow-circle-down"></i>');
    });
</script>        
EOF;
        $this->resetTableRespView();
    }

    /**
     * Renderiza a tag caption da tabela
     * @param string $options
     */
    public function renderCaption($options) {
        echo "\t<caption>",
                $options,
             "</caption>";
    }

    /**
     * configura o td de edição do registro 
     * Retorna um int
     * @param string $option
     * @return int
     */
    public function setEditLine($option) {
        switch ($option) {
            case 'first':
                $this->editLine = 0;
                break;
            case 'last':
                $this->editLine =  (count($this->coluns) - 1 );
                break;
            case 'false':
                $this->editLine =  FALSE;
                break;
            default:
                $this->editLine =  $option;
        }
    }
    
    /**
     * Recebe um função lambda para rescrever a funcão de editar e deletar padrão.
     * @param function $lambda
     */
    public function setLambda($lambda) {
        $this->funcEdit = $lambda;
    }
    
    /**
     * Colocar opção que seram inseridas no td para cada linha de registro
     * @param array $options
     */
    public function setTdopt(array $options) {
        $this->tdopt = $options;
    }

}