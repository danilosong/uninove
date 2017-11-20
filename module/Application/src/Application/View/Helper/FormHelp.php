<?php

namespace Application\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;

/**
 * FormHelp
 * View Helper para trabalhar com formularios
 * @author Paulo Cordeiro Watakabe <watakabe05@gmail.com>
 */
class FormHelp extends AbstractHelper {

    /**
     *
     * @var \Zend\Form\View\Helper\Form
     */
    protected $formView;

    /**
     *
     * @var \Zend\Form
     */
    protected $form;

    /**
     * Contem as tags html para exibição dos erros
     * @var string
     */
    protected $inputError;

    /**
     *
     * @var \Zend\Form\View\Helper\FormElementErrors 
     */
    protected $formEleErro;

    /**
     *
     * @var \Zend\Form\View\Helper\FormLabel
     */
    protected $label;

    /**
     *
     * @var \Zend\Form\View\Helper\FormRadio
     */
    protected $radio;

    /**
     *
     * @var \Zend\Form\View\Helper\FormCheckbox
     */
    protected $checkbox;

    /**
     *
     * @var \Zend\Form\View\Helper\FormMultiCheckbox
     */
    protected $multiCheckbox;

    /**
     * Define se label e input vai ser na mesma linha ou não 
     * Padrão é label acima
     * @var boolean 
     */
    protected $horizontal = false;

    /**
     * Tamanho padrao de largura boostrap maximo 12
     * @var int 
     */
    protected $colLarg = 12;

    /**
     * Prefixo para nome dos inputs do another form
     * @var array
     */
    protected $prefix = [];

    /**
     * Guarda o ultimo ID gerado pelo form helper
     * Utilizado por ex: parametrizar a função JS autoComp
     * @var string
     */
    protected $lastId = '';

    /**
     * Guarda quando divs foram abertos para serem todas fechadas.
     * @var integer
     */
    protected $closeDiv = 0;

    /**
     * Metodo magico que é acionado quando acessado esta classe pela view
     * Configura as variaveis e direciona para o metodo requerido
     * @author Paulo Watakabe
     * @version 1.0
     * @param Zend\Form\View\Helper $formView
     * @param Zend\Form $form
     * @param array $options
     * @param array $acao
     */
    public function __invoke($formView, $form = null, $target = '') {
        $this->formView = $formView;
        if (!is_null($form)) {
            $this->setForm($form, $target);
        }
        $this->prefix = [];
        return $this;
    }

    /**
     * Devido a um erro de execução que diz que a classe nao pode ser convertida em string
     * Colocando esse metodo o erro some
     * @author Paulo Watakabe
     * @version 1.0
     * @return string
     */
    public function __toString() {
        return 'Application\View\Helper\FormHelp';
    }

    /**
     * Facilitar a chamada dos metodos para exibir os input individualmente
     * 
     * @author Paulo Watakabe
     * @version 1.0
     * @param string $m
     * @param array $p
     */
    public function __call($m, $p) {
        $func = 'renderInput' . ucfirst($m);
        if (method_exists($this, $func)) {
            return call_user_func_array([$this, $func], $p);
        }
    }

    /**
     * Setar o valor a ser prefixado para busca no formulario
     * @author Paulo Watakabe
     * @version 1.0
     * @param string $preFix
     * @return \Application\View\Helper\FormHelp
     */
    public function setPreFix($preFix = '') {
        if (empty($preFix)) {
            $this->prefix = [];
        }
        $this->prefix[] = $preFix;
        return $this;
    }

    /**
     * Remove o valor prefixado para gerar os nomes dos campos.
     * @author Paulo Watakabe
     * @version 1.0
     * @param string $name
     * @return \Application\View\Helper\FormHelp
     */
    public function removePreFix($name = '') {
        $removed = array_pop($this->prefix);
        if (is_null($removed)) {
            echo '<h2>não existe prefixamento ', $name, '</h2>';
        }
        return $this;
    }

    /**
     * Pega a referencia da variavel a ser tradada e prefixa a mesma ou não
     * Faz o prefixamento em arvoré chegando aos seus galhos.
     * @author Paulo Watakabe
     * @version 1.0
     * @param string $name
     * @return nothing
     */
    public function preFixName(&$name) {
        $prefixs = $this->prefix;
        $this->lastId = $name;
        if (empty($prefixs)) {
            return;
        }
        $basePrefix = '';
        foreach ($prefixs as $key => $prefix) {
            if ($key === 0) {
                $basePrefix = $prefix;
            } else {
                $basePrefix .= '[' . $prefix . ']';
            }
        }

        if (strpos($name, '[') !== FALSE) {
            $name = $basePrefix . '[' . str_replace('[', '][', $name);
        } else {
            $name = $basePrefix . '[' . $name . ']';
        }

        $this->lastId = $name;
    }

    /**
     * Pega o id do ultimo elemente gerado
     * @author Paulo Watakabe
     * @version 1.0
     * @return string
     */
    public function getLastId() {
        return $this->lastId;
    }

    /**
     * Pega o nome do elemento em questão e faz o prefix se necessario.
     * @author Paulo Watakabe
     * @version 1.0
     * @param string $name
     * @return string
     */
    public function getIdFor($name = '') {
        $this->preFixName($name);
        return $name;
    }

    /**
     * Setar Form a ser trabalho no FormHelp
     * Caso exitir o target faz a configuração
     * @author Paulo Watakabe
     * @version 1.0
     * @param objeto $form
     * @param string $target
     */
    public function setForm($form, $target = '') {
        $this->form = $form;
        if (!empty($target)) {
            $this->form->setAttribute('action', $target);
        }
        $this->form->prepare();
    }

    /**
     * Retorna o formulario em que esta sendo trabalhado
     * @author Paulo Watakabe
     * @version 1.0
     * @return \Application\Form\AbstractForm 
     */
    public function getForm() {
        return $this->form;
    }

    /**
     * Renderiza o inicio do form e a parte do fieldset  e inicio do table 
     * para organizar o formulario em colunas recebe no options legend e um input hidden se houver
     * @author Paulo Watakabe
     * @version 1.0
     * @param Array $options 
     */
    public function formInit($legend = '', $options = []) {
        if (isset($options["noPreFormInit"])) {
            unset($options["noPreFormInit"]);
        } else {
            $this->form->preFormInit();
        }
        echo $this->formView->form()->openTag($this->form);
        $this->renderFieldsetIni($legend, $options);
        $this->renderAllHidden();
        return $this;
    }

    /**
     * Renderiza o fim do fieldset, table e form
     * Não renderiza o fim do fieldset e table se for definido noField nas opções
     * Coloca o botao submit se requerido
     * @author Paulo Watakabe
     * @version 1.0
     * @param Array $options
     */
    public function formEnd($options = []) {
        if (!isset($options['noField'])) {
            $this->renderFieldsetFim($options);
        } else {
            if (isset($options['submit'])) {
                $this->renderInputSubmit($options['submit']);
            }
        }
        echo $this->formView->form()->closeTag(), PHP_EOL;
    }

    /**
     * Renderiza o inicio do fieldset  e inicio do table 
     * para organizar o formulario em colunas 
     * Recebe no options legend e um input hidden se houver
     * @author Paulo Watakabe
     * @version 1.0
     * @param Array $options 
     */
    public function renderFieldsetIni($legend = '', array $options = []) {
        $legenda = (!empty($legend)) ? '<legend>' . $legend . '</legend>' . PHP_EOL : '';
        echo "<fieldset ", isset($options['fAtrb']) ? $options['fAtrb'] : '', ">", PHP_EOL, $legenda;
        if (isset($options['beforeLine'])) {
            echo $options['beforeLine'];
        }
        if (!isset($options['notOpenLine'])) {
            $this->openLine();
        }
        return $this;
    }

    /**
     * Renderiza o fim do fieldset, table
     * Coloca o botao submit se requerido
     * @author Paulo Watakabe
     * @version 1.0
     * @param Array $options
     */
    public function renderFieldsetFim($options = []) {
        if (isset($options['afterTable'])) {
            echo $options['afterTable'];
        }
        echo "</fieldset>", PHP_EOL;
        if (isset($options['submit'])) {
            $this->renderInputSubmit($options['submit']);
        }
        return $this;
    }

    /**
     * Renderiza todos os campos ocultos do form na tela
     * @author Paulo Watakabe
     * @version 1.0
     */
    public function renderAllHidden() {
        /* @var $value \Zend\Form\Element */
        $eles = $this->form->getElements();
        foreach ($eles as $key => $value) {
            if ($value->getAttribute('type') == 'hidden' AND $value->getAttribute('render') !== FALSE) {
                $this->renderInputHidden($key);
            }
        }
    }

    /**
     * Renderiza todos os campos ocultos do form na tela
     * Filtrado pelo inicio do nome que normalmente será um prefixo
     * 
     * @author Paulo Watakabe
     * @version 1.0
     * @param string $name
     * @return \Application\View\Helper\FormHelp
     */
    public function renderHiddenFor($name = '') {
        /* @var $value \Zend\Form\Element */
        if (empty($name)) {
            return $this;
        }
        $eles = $this->form->getElements();
        foreach ($eles as $key => $value) {
            if ($value->getAttribute('type') == 'hidden' AND strpos($value->getAttribute('name'), $name) !== false) {
                $this->renderInputHidden($key);
            }
        }
        return $this;
    }

    /**
     * Abre uma linha para colocar os elementos do form
     * @author Paulo Watakabe
     * @version 1.0
     * @return \Application\View\Helper\FormHelp
     */
    public function openLine($attrs = array()) {
        if (empty($attrs)) {
            echo '<div class="row">', PHP_EOL;
            return $this;
        }

        $attrs['class'] = isset($attrs['class']) ? $attrs['class'] . ' row' : 'row';

        $el = '<div ';
        foreach ($attrs as $name => $value) {
            $el .= $name . '="' . $value . '" ';
        }
        $el .= '>';

        echo $el;
    }

    /**
     * Fecha a linha em que foi colocado os elementos do form
     * @author Paulo Watakabe
     * @version 1.0
     * @return \Application\View\Helper\FormHelp
     */
    public function closeLine() {
        echo '</div>', PHP_EOL;
        return $this;
    }

    /**
     * Fecha a linha do elementos e na sequencia abre uma linha nova para os elementos do form
     * @author Paulo Watakabe
     * @version 1.0
     * @return \Application\View\Helper\FormHelp
     */
    public function lineDown() {
        $this->closeLine();
        $this->openLine();
        return $this;
    }

    /**
     * 
     */
    public function jumpLine($lines = 1) {
        $this->closeLine();
        for ($i = 0; $i < $lines; $i++) {
            echo '<br>', PHP_EOL;
        }
        $this->openLine();
        return $this;
    }

    /**
     * Abre uma nova coluna
     * 
     * @author Paulo Watakabe
     * @version 1.0
     * @param string|int $tamanho Tamanho da Coluna 
     * @param string|int $espaco Tamanho do espaco (opcional - 0)
     * @param String $tam [md|sl|xs|lg] (opcional - md)
     * @param Array $atribs [] (opcional - [])
     * @return \Application\View\Helper\FormHelp
     */
    public function openCol($tamanho = '3', $espaco = '', $tam = 'md', $atribs = []) {
        if (is_array($espaco)) {
            $atribs = $espaco;
            $espaco = '';
        } else if (is_array($tam)) {
            $atribs = $tam;
            $tam = 'md';
        }
        $atribs["class"] = isset($atribs["class"]) ? $atribs["class"] : "";

        $espaco = (empty($espaco)) ? "" : "col-" . $tam . "-offset-" . $espaco;
        $col = "col-" . $tam . "-" . $tamanho;
        $atribs["class"] = $espaco . " " . $col . " " . $atribs["class"];
        $this->colLarg = $tamanho;

        $div = "";
        foreach ($atribs as $atrib => $value) {
            $div = $div . $atrib . '="' . $value . '" ';
        }
        echo "<div " . $div . ">";
        return $this;
    }

    /**
     * Fecha a div que foi aberta em openCol
     * @author Paulo Watakabe
     * @version 1.0
     * @return \Application\View\Helper\FormHelp
     */
    public function closeCol() {
        echo '</div>', PHP_EOL;
        return $this;
    }

    /**
     * Baseado na largura da div do boostrap que vai ate 12
     * Faz um redimencionamento para o Label do campo horizontal não ficar estreito
     * @author Paulo Watakabe
     * @version 1.0
     * @return string
     */
    public function getLargForLabelHorizontal(&$opt) {
        switch (true) {
            case isset($opt['labelWidth']) :
                return $opt['labelWidth'];
            case $this->colLarg >= 10 AND $this->colLarg <= 12:
                return '2';
            case $this->colLarg >= 7 AND $this->colLarg <= 9:
                return '3';
            case $this->colLarg >= 4 AND $this->colLarg <= 6:
                return '4';
            case $this->colLarg >= 1 AND $this->colLarg <= 3:
                return '5';
            default:
                return '2';
        }
    }

    /**
     * Metodo principal que faz a preparação das tags em conformidade do bootstrap para renderização
     * Verificação de erros de validação
     * Posiciona label em horizontal e acima
     * Cria ou não label baseado em parametros
     * 
     * @author Paulo Watakabe
     * @version 1.0
     * @param string $name
     * @param object $element
     * @param string $css
     * @param boolean $setFormControl
     * @param array $opt
     * @return string
     */
    public function openDivInput($name, &$element, $css = '', $setFormControl = true, $opt = []) {
        if (is_array($css)) {
            $opt = $css;
            $css = '';
        }
        if (is_array($setFormControl)) {
            $opt = $setFormControl;
            $setFormControl = true;
        }
        if(isset($opt['class'])){
            $css .= ' ' . $opt['class'];
        }
        $this->inputError = $this->getEleErro()->render($element);
        if ($this->inputError) {
            $css .= ' has-error';
        }
        if ($setFormControl) {
            $element->setAttribute('class', 'form-control' . (($css == ' date') ? '' : $css));
        }
        if ($this->horizontal) {
            $element->setLabelAttributes(['class' => 'col-md-' . $this->getLargForLabelHorizontal($opt) . ' control-label']);
        }
        $html = $this->openDiv('class="form-group" id="pop' . $name . '"');
        if (isset($opt['spanLabel'])) {
            $html .= $this->openDiv('class="input-group' . $css . '"');
            if ($opt['spanLabel']) {
                $spanLabelCss = is_bool($opt['spanLabel']) ? '' : ' ' . $opt['spanLabel'];
                $html .= $this->buildSpan(["class" => "input-group-addon" . $spanLabelCss], $element->getLabel());
            }
        } else {
            if (isset($opt['noLabel']) and $opt['noLabel'] === TRUE) {
                $html .= $this->openDiv('class="input-group' . $css . '"');
            } else {
                $html .= $this->getLabel()->openTag($element) . $element->getLabel() . $this->getLabel()->closeTag() . $this->openDiv('class="input-group' . $css . '"');
            }
        }
        return $html . PHP_EOL;
    }

    /**
     * Fecha todas as divs abertas ou apenas as pedidas por paramentros.
     * @author Paulo Watakabe
     * @version 1.0
     * @param integer $qtd
     * @return string
     */
    public function closeDivInput($qtd = 'all') {
        $tot = $this->closeDiv;
        if ($qtd != 'all') {
            $tot = $qtd;
        }
        $closedsDivs = '';
        for ($i = 0; $i <= $tot; $i++) {
            $closedsDivs .= $this->closeDiv();
        }
        return $closedsDivs;
    }

    /**
     * Abre um div e coloca o conteudo de content dentro
     * Faz contagem para depois fechar.
     * 
     * @author Paulo Watakabe
     * @version 1.0
     * @param string $content
     * @return string
     */
    public function openDiv($content = '') {
        $this->closeDiv++;
        return "<div " . $content . ">" . PHP_EOL;
    }

    /**
     * Fecha div aberta somente se existir um div aberta
     * 
     * @author Paulo Watakabe
     * @version 1.0
     * @return string
     */
    public function closeDiv() {
        if ($this->closeDiv == 0) {
            return '';
        }
        $this->closeDiv--;
        return "</div>" . PHP_EOL;
    }

    /**
     * Metodo que busca no Form o elemento a ser trabalho pelo view helper
     * @author Paulo Watakabe
     * @version 1.0
     * @param string $name
     * @return form Element | exception se elemento nao existe ou nao encontrado
     */
    public function getEle($name = '') {
        if (empty($name)) {
            echo '<h1>Erro parametro name vazio<h1>';
            die;
        }
        $namePreFix = $name;
        $this->preFixName($namePreFix);
        $exist = $this->form->has($namePreFix);
        
        if(!$exist AND $this->form->has(ucfirst($namePreFix))){
            $namePreFix = ucfirst($namePreFix) ;
            $this->lastId = $namePreFix;
            $exist = true;
        }
        
        if(!$exist AND $this->form->has(lcfirst($namePreFix))){
            $namePreFix = lcfirst($namePreFix) ;
            $this->lastId = $namePreFix;
            $exist = true;
        }
        
        if(!$exist && $this->form->has($name)){
            $namePreFix = $name;
            $this->lastId = $namePreFix;
            $exist = true;
        }
        if(!$exist){
            $eles = $this->form->getElements();
            foreach ($eles as $value) {
                if(strtolower($value->getAttribute('name')) == strtolower($namePreFix)){
                    $namePreFix = $value->getAttribute('name');
                    $exist = true;
                    $this->lastId = $namePreFix;
                    break;
                }
            }
        }
        if(!$exist){
            $msg =  '<h2>Erro ao tentar carregar input= ' . $name . ' ou ' . $namePreFix . ' talvez não exista no Form or FieldSet</h2>' . '<br>';
//            throw  new \Exception($msg);
            echo $msg;
            $eles = $this->form->getElements();
            foreach ($eles as $value) {
                echo $value->getAttribute('type') , ' ', $value->getAttribute('name'), '<br>'; 
            }
            die;
        }
        return $this->form->get($namePreFix);
    }

    /**
     * retorna o helper form label do ZF2
     * 
     * @author Paulo Watakabe
     * @version 1.0
     * @return \Zend\Form\View\Helper\FormLabel
     */
    public function getLabel() {
        if ($this->label) {
            return $this->label;
        }
        $this->label = $this->formView->formLabel();
        return $this->label;
    }

    /**
     * retorna o helper form checkbox do ZF2
     * 
     * @author Paulo Watakabe
     * @version 1.0
     * @return \Zend\Form\View\Helper\FormCheckbox
     */
    public function getCheckbox() {
        if ($this->checkbox) {
            return $this->checkbox;
        }
        $this->checkbox = $this->formView->formCheckbox();
        return $this->checkbox;
    }

    /**
     * retorna o helper form MultiCheckbox do ZF2
     * 
     * @author Paulo Watakabe
     * @version 1.0
     * @return \Zend\Form\View\Helper\FormMultiCheckbox
     */
    public function getMultiCheckbox() {
        if (is_null($this->multiCheckbox)) {
            $this->multiCheckbox = $this->formView->formMultiCheckbox();
        }
        if ($this->horizontal) {
            $this->multiCheckbox->setSeparator(' | ');
        } else {
            $this->multiCheckbox->setSeparator('<br>');
        }
        return $this->multiCheckbox;
    }

    /**
     * retorna o helper form Radio do ZF2
     * 
     * @author Paulo Watakabe
     * @version 1.0
     * @return \Zend\Form\View\Helper\FormRadio
     */
    public function getRadio() {
        if ($this->radio) {
            return $this->radio;
        }
        $this->radio = $this->formView->formRadio();
        if ($this->horizontal) {
            $this->radio->setSeparator(' | ');
        } else {
            $this->radio->setSeparator('<br>');
        }
        return $this->radio;
    }

    /**
     * Indica a posição da renderização do labels dos campos na tela
     * Padrão é false para que o label seja renderizado acima
     * @author Paulo Watakabe
     * @version 1.0
     * @param boolean $horizontal 
     * @return \Application\View\Helper\FormHelp
     */
    function setHorizontal($horizontal) {
        $this->horizontal = $horizontal;
        return $this;
    }

    /**
     * Indica a posição da renderização do labels dos campos na tela
     * 
     * @author Paulo Watakabe
     * @version 1.0
     * @return bollean
     */
    function getHorizontal() {
        return $this->horizontal;
    }

    /**
     * Renderiza os erros em formato html e destaque em vermelho
     * 
     * @author Paulo Watakabe
     * @version 1.0
     * @return string
     */
    public function getEleErro() {
        if ($this->formEleErro) {
            return $this->formEleErro;
        }
        $this->formEleErro = $this->formView->formElementErrors();
        $this->formEleErro
                ->setMessageOpenFormat('<div class="alert alert-danger" role="alert">')
                ->setMessageSeparatorString('</div><div class="alert alert-danger" role="alert">')
                ->setMessageCloseString('</div>');
        return $this->formEleErro;
    }

    /**
     * Exibição de erro padrão boostrap 3 
     * @author Paulo Watakabe
     * @version 1.0
     * @return string | nothing
     */
    public function showError() {
        if ($this->inputError) {
            $inputError = $this->inputError;
            $this->inputError = false;
            return $inputError . PHP_EOL;
        }
    }

    /**
     * Gera elemento ou uma cadeia de elementos
     * 
     * @version 2.0
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @todo Necessário validar desempenho deste método posteriormente
     * @param string $element Elemento a ser criado
     * @param array $attr Atributos do elemento
     * @param string $content SubElementos do Elemento
     * @param boolean $finally printa ou retorna elemento
     * @return \Application\View\Helper\FormHelp|string
     */
    public function element($element, $attr = [], $content = "", $finally = FALSE) {
        if (!isset($element)) {
            return $this;
        }

        if (is_string($attr)) {
            $content = $attr;
            $attr = [];
        }

        if (is_bool($attr)) {
            $finally = $attr;
            $attr = [];
            $content = "";
        }

        if (is_bool($content)) {
            $finally = $content;
            $content = "";
        }

        $aux = "";
        foreach ($attr as $key => $value) {
            $aux .= " " . $key . "=\"" . $value . "\"";
        }

        $aux = "<" . $element . $aux . ">" . $content . "</" . $element . ">";
        if (!$finally) {
            return $aux;
        }
        echo $aux;
        return $this;
    }

    /**
     * Gerador de Botao
     * 
     * @author Danilo Dorotheu
     * @version 1.0
     * @param array $attr Atributos do botao (Pode ser Omitido)
     * @param string $content Conteudo do botao
     * @param string $type Cor do botao [primary|danger|...] (Pode ser Omitido)
     * @return String
     */
    public function buildButton($attr, $content, $type = "default", $finally = FALSE) {
        $attr["class"] = (isset($attr["class"])) ? $attr["class"] : "";

        //Define as classes do botao
        $attr["class"] = $attr["class"] . " btn btn-" . $type;
        //Define (ou sobrescreve) o tipo do botao (button)
        $attr["type"] = (isset($attr["type"])) ? $attr["type"] : "button";
        //Gera e devolve o elemento
        return $this->element("button", $attr, $content, $finally);
    }

    /**
     * Gerador de Span
     * 
     * @author Danilo Dorotheu
     * @version 1.0
     * @param array $attr Atributos do Span
     * @param type $content Conteudo do Spano
     * @return String
     */
    public function buildSpan($attr, $content = "") {
        //Gera e devolve o elemento
        return $this->element("span", $attr, $content);
    }

    /**
     * Gerador de Icone
     * 
     * @author Danilo Dorotheu
     * @version 1.0
     * @param String $icon Icone
     * @return String
     */
    public function buildIcon($icon) {
        //Se não passar o icone, devolve vazio
        if (!isset($icon)) {
            return "";
        }
        //Gera a classe do icone
        $attr["class"] = "fa fa-" . $icon;
        //Gera e retorna o elemento
        return $this->element("i", $attr);
    }

    public function buildDropdown($list, $attr, $firstVal) {

        $firstVal = (!empty($firstVal)) ? $firstVal : explode(":", $list[0])[0];
        $attr["id"] = (isset($attr["id"])) ? $attr["id"] : "drop";
        $attr["class"] = (empty($attr["class"])) ? "dropdown" : $attr["class"] . " dropdown";

        $a = "";
        foreach ($list as $item) {
            list($text, $value) = explode(":", $item);
            $value = (empty($value) or ! isset($value)) ? $text : $value;
            $a .= $this->element("a", ["value" => $value], $text);
        }
        $li = $this->element("li", [], $a);
        $ul = $this->element("ul", ["class" => "dropdown-menu", "aria-labelledby" => $attr["id"]], $li);

        $span = $this->buildSpan(["class" => "caret"]);

        $button = $this->buildButton([
            "id" => "btn-" . $attr["id"],
            "data-toggle" => "dropdown",
            "aria-haspopup" => "true",
            "aria-expanded" => "false"
                ], $firstVal . " " . $span);

        return $this->element("div", $attr, $button . $ul);
    }

    /**
     * Renderiza o botao de limpar campo Input
     * 
     * @param type $name e Id do Elemento
     * @param type $element Elemento
     * @param array $options Opcoes definidas no ato de construcao do elemento
     * @return String
     * 
     * @author Paulo Watakabe
     * @author Danilo Dorotheu (Modificacoes)
     * @version 1.4
     */
    public function iconClean($name, &$element, $options = []) {

        if (isset($options["clean"]) and ! $options["clean"]) {
            return ""; //Se for falso, retorne ""
        }

        $jq = ' clean'; // Classe css que faz ligação com a função cleaninput criada em myscript.js

        if ($element->getAttribute('readOnly')) {
            $jq = '';
        }
        if ($element->getAttribute('disabled')) {
            $jq = '';
        }
        $middle = "";
        if ((isset($options["extra"]) and ! empty($options["extra"]))) {
            $middle = ' middleButton';
        }
        //Gera botao (com icone)
        $optButton = ["class" => $jq . $middle];

        if (isset($options['cleanId'])) {
            if (isset($options['cleanFunction'])) {
                $optButton["onClick"] = $options['cleanFunction'] . "(" . $options['cleanId'] . ");";
            } else {
                $optButton["onClick"] = 'action.cleanId(' . $options['cleanId'] . ');';
            }
        }

        if (isset($options['dt-clear'])) {
            $optButton["dt-clear"] = $options['dt-clear'];
        }

        $button = $this->buildButton($optButton, $this->buildIcon("remove"));
        //Envolve o botao acima em um span e devolve
        return $this->buildSpan(["class" => "input-group-btn", "name" => $name, "id" => 'clean_' . $element->getAttribute('id')], $button);
    }

    /**
     * Renderiza [botao | label] dentro do input
     * 
     * @param type $name Id do Elemento
     * @param array $options Opcoes do elemento:
     * -> String type: [button|span]
     * -> String text: Texto do elemento
     * -> String icon: Icone do elemento
     * -> String js: Funcao a ser executada dentro do onclick 
     * @return string
     * 
     * @see getIcons
     * @author Danilo Dorotheu 
     * @version 2.0
     */
    public function iconExtra($name, array $options) {
        //TODO: Mudar logica
        if (!(isset($options["extra"]) and ! empty($options["extra"]))) {
            return "";
        }
        // Varios icones usando recursive logic
        if (isset($options['extra'][0])) {
            foreach ($options['extra'] as $extra) {
                echo $this->iconExtra($name, ['extra' => $extra]);
            }
            return '';
        }

        $options = $options["extra"]; //Diminui o caminho
        //Recebe valores de options
        $type  = (isset($options["type"]) and $options["type"] == "label") ? "addon"           : "btn";
        $icon  = (isset($options["icon"]))  ? $options["icon"]  : "";
        $text  = (isset($options["text"]))  ? $options["text"]  : "";
        $title = (isset($options["title"])) ? $options["title"] : "";
        $js    = (isset($options["js"]))    ? $options["js"]    : "";
        $id    = (isset($options["id"]))    ? $options["id"]    : "icon_" . $name;
        $class = (isset($options["class"])) ? $options["class"] : "";
        $data  = (isset($options["data"]))  ? $options["data"]  : [];

        $content = $this->buildIcon($icon) . " " . $text;

        if ($type == "btn") {
            $content = $this->buildButton(array_merge(["id" => $id, "class" => $class, "onclick" => $js, "title" => $title], $data), $content);
        }

        return $this->buildSpan(["class" => "input-group-" . $type], $content);
    }

    /**
     * Monta um na tela baseado nos paramentros passados
     * @author Paulo Watakabe
     * @version 1.0
     * @param string $name
     * @param array $attributes
     * @param string $content
     * @return string
     */
    public function getSpan($name, array $attributes = [], $content = '') {
        if (empty($attributes) OR ! isset($attributes['span_id'])) {
            return '';
        }
        $class = (isset($attributes['class'])) ? ' class="' . $attributes['class'] . '"' : '';
        $style = (isset($attributes['style'])) ? ' style="' . $attributes['style'] . '"' : '';
        return '<span name="span_' . $name . '" id="' . $attributes['span_id'] . '"' . $class . ' ' . $style . '></span>';
    }

    /**
     * @author Paulo Watakabe
     * @version 1.0
     * Gera o botão com o icone do calendario na tela para seleção de data.
     * 
     * @param string $name
     * @param object $element
     * @return string
     */
    public function iconCalend($name, &$element) {
        if ($element->getAttribute('readOnly')) {
            return '';
        }
        return '<span class="input-group-btn"><button class="btn btn-default calendar" fa-calendar" id="calend_' .
                $name . '" type="button"><i class="fa fa-calendar"></i></button></span>';
    }

    /**
     * @author Paulo Watakabe
     * @version 1.0
     * Coloca o conteudo na tela de alinhado conforme parametro
     * @param string $html_partial
     * @param string $align
     * @return string
     */
    public function align($html_partial, $align = 'center') {
        return "<div align='" . $align . "'>" .
                $html_partial .
                "</div>" . PHP_EOL;
    }

    /**
     * @author Danilo Dorotheu
     * @version 1.0
     * Renderiza o gerador de botao
     * @param type $attr
     * @param type $content
     * @param type $type
     * @return \Application\View\Helper\FormHelp
     */
    public function renderInputButton($attr, $content, $type = "default") {
        echo $this->buildButton($attr, $content, $type);
        return $this;
    }

    /**
     * @author Danilo Dorotheu
     * @version 1.0
     * 
     * @param type $list
     * @param type $attr
     * @param type $firstVal
     * @return \Application\View\Helper\FormHelp
     */
    public function renderInputDropdown($list = [], $attr = [], $firstVal = "") {
        echo $this->buildDropdown($list, $attr, $firstVal);
        return $this;
    }

    /**
     * @author Paulo Watakabe
     * @version 1.0
     * Renderiza o input hidden 
     * @param String $name
     */
    public function renderInputHidden($name) {
        echo $this->formView->formHidden($this->getEle($name)), PHP_EOL;
        return $this;
    }

    /**
     * @author Paulo Watakabe
     * @version 1.2
     * Renderiza um input com botão de limpar e outro botão passado por parametro 
     * Caso exista o parametro span renderiza sua tag
     * Redireciona Label para setar corretamente o imput
     * 
     * @param string $name
     * @param array $options Obrigatorio para ser um campo com input
     * @param array $attributes
     * @return \Application\View\Helper\FormHelp
     */
    public function renderInputIcone($name, array $options, array $attributes = []) {
        return $this->renderInputText($name, $options, $attributes);
    }

    public function setAtributesAndOptions(&$element, array &$params) {
        if (empty($params)) {
            return;
        }
        // Redireciona Label para setar corretamente o imput
        if (isset($params['label'])) {
            $params['options']['label'] = $params['label'];
            unset($params['label']);
        }
        if (isset($params['options'])) {
            $element->setOptions($params['options']);
            unset($params['options']);
        }
        $element->setAttributes($params);
    }

    /**
     * @author Paulo Watakabe
     * @version 1.0
     * Renderiza o input text com um botao para limpar o conteudo
     * Caso exista msg de erro sera exibo em vermelho
     * 
     * @param String $name
     * @param array $options
     * @param array $attributes
     * @return \Application\View\Helper\FormHelp
     */
    public function renderInputText($name, array $options = [], array $attributes = []) {
        /* @var $element \Zend\Form\Element\Text */
        $element = $this->getEle($name);
        $this->setAtributesAndOptions($element, $attributes);
        echo $this->openDivInput($name, $element, $options),
        $this->doIfHasDisabledInput($name, $element),
        $this->formView->formText($element),
        $this->iconClean($name, $element, $options),
        $this->iconExtra($name, $options),
        $this->closeDivInput(),
        $this->getSpan($name, $options),
        $this->showError()
        ;

        if (isset($options["mask"])) {
            echo
            PHP_EOL .
            '<script type="text/javascript">' .
            "$$('#" . $element->getAttribute('id') . "').mask('" . $options["mask"] . "');" .
            '</script>' .
            PHP_EOL;
        }
        return $this;
    }

    /**
     * Renderiza o input text com a mascara de CPF
     * 
     * @param String $name
     * @param array $options
     * @param array $attributes
     * @return \Application\View\Helper\FormHelp
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @since 26/02/2016
     */
    public function renderInputCpf($name, array $options = [], array $attributes = []) {
        if (!isset($options["mask"]) or TRUE == $options["mask"]) {
	    $options["mask"] = (isset($options["mask"]) && "string" == gettype($options["mask"])) ? $options["mask"] : "999.999.999-99";
        }
        return $this->renderInputText($name, $options, $attributes);
    }

    /**
     * Renderiza o input text com a mascara de CNPJ
     * 
     * @param String $name
     * @param array $options
     * @param array $attributes
     * @return \Application\View\Helper\FormHelp
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @since 26/02/2016
     */
    public function renderInputCnpj($name, array $options = [], array $attributes = []) {
        if (!isset($options["mask"]) or TRUE == $options["mask"]) {
            $options["mask"] = (isset($options["mask"]) && "string" == gettype($options["mask"])) ? $options["mask"] : "99.999.999/9999-99";
        }
        return $this->renderInputText($name, $options, $attributes);
    }

    /**
     * Renderiza o input text com a mascara de PIS
     * 
     * @param String $name
     * @param array $options
     * @param array $attributes
     * @return \Application\View\Helper\FormHelp
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @since 26/02/2016
     */
    public function renderInputPis($name, array $options = [], array $attributes = []) {
        $options = array_merge($options, ["mask" => "999.9999.999-9"]);
        return $this->renderInputText($name, $options, $attributes);
    }

    public function renderInputFile($name, array $options = [], array $attributes = []) {
        /* @var $element \Zend\Form\Element\File */
        $element = $this->getEle($name);
        $this->setAtributesAndOptions($element, $attributes);
        echo $this->openDivInput($name, $element, $options),
        $this->formView->formFile($element),
        $this->iconClean($name, $element, $options),
        $this->iconExtra($name, $options),
        $this->closeDivInput(),
        $this->getSpan($name, $options),
        $this->showError()
        ;
        return $this;
    }

    /**
     * @author Paulo Watakabe
     * @version 1.0
     * Renderiza o input textArea com um botao para limpar o conteudo
     * Caso exista msg de erro sera exibo em vermelho
     * @param String $name
     * @param array $opts
     * @param array $attributes
     * @return \Application\View\Helper\FormHelp
     */
    public function renderInputTextArea($name, $opts = [], array $attributes = []) {
        /* @var $element \Zend\Form\Element\TextArea */
        $element = $this->getEle($name);
        $this->setAtributesAndOptions($element, $attributes);
        echo $this->openDivInput($name, $element, $opts),
        $this->doIfHasDisabledInput($name, $element),
        $this->formView->formTextarea($element),
        $this->iconClean($name, $element, $opts),
        $this->iconExtra($name, $opts),
        $this->closeDivInput(),
        $this->showError();
        return $this;
    }

    /**
     * @author Paulo Watakabe
     * @version 1.0
     * Renderiza e posiciona o input Submit na tela
     * @param String $name
     * @param String $pos    lefth = -1, center = 0, right = 1  default is center
     * @return \Application\View\Helper\FormHelp
     */
    public function renderInputSubmit($name, $pos = '0', $attr = []) {
        /* @var $element \Zend\Form\Element\Submit */
        $element = $this->getEle($name);
        if (is_array($pos)) {
            $attr = $pos;
            $pos = isset($pos['pos']) ? $pos['pos'] : '0';
        }
        $this->setAtributesAndOptions($element, $attr);
        switch ($pos) {
            case '1':
                echo $this->align($this->formView->formSubmit($element), 'right');
                break;
            case '-1':
                echo $this->align($this->formView->formSubmit($element), 'lefth');
                break;
            case '0':
                echo $this->align($this->formView->formSubmit($element));
                break;
            default:
                echo $this->formView->formSubmit($element);
        }
        return $this;
    }

    /**
     * @author Paulo Watakabe
     * @version 1.0
     * Renderiza e posiciona o input button na tela
     * @param String $name
     * @param String $pos    lefth = -1, center = 0, right = 1  default is center
     * @return \Application\View\Helper\FormHelp
     */
    public function renderInputButtonOnly($name, $pos = '0', $attr = []) {
        /* @var $element \Zend\Form\Element\Button */
        $element = $this->getEle($name);
        if (is_array($pos)) {
            $attr = $pos;
            $pos = isset($pos['pos']) ? $pos['pos'] : '0';
        }
        $this->setAtributesAndOptions($element, $attr);
        switch ($pos) {
            case '1':
                echo $this->align($this->formView->formButton($element), 'right');
                break;
            case '-1':
                echo $this->align($this->formView->formButton($element), 'lefth');
                break;
            case '0':
                echo $this->align($this->formView->formButton($element));
                break;
            default:
                echo $this->formView->formButton($element);
        }
        return $this;
    }

    /**
     * @author Paulo Watakabe
     * @version 1.0
     * Renderiza o input Selec
     * Caso exista msg de erro sera exibo em vermelho
     * @param String $name
     * @return \Application\View\Helper\FormHelp
     */
    public function renderInputSelect($name, $opts = [], $attr = []) {
        /* @var $element \Zend\Form\Element\Submit */
        $element = $this->getEle($name);
        $this->setAtributesAndOptions($element, $attr);
        $selectHtml = $this->openDivInput($name, $element, $opts);

        $selectHtml .= $this->doIfHasDisabledInput($name, $element);
        $selectHtml .= $this->formView->formSelect($element);

        echo
        $selectHtml,
        $this->iconClean($name, $element, $opts),
        $this->iconExtra($name, $opts),
        $this->closeDivInput(),
        $this->showError();
        return $this;
    }

    /**
     * @author Paulo Watakabe
     * @version 1.0
     * Renderiza o input text com um botao para limpar o conteudo e outro para 
     * escolher um data para o preencimento
     * Caso exista msg de erro sera exibo em vermelho
     * @param String $name
     * @return \Application\View\Helper\FormHelp
     */
    public function renderInputCalend($name, $options = [], $attr = []) {
        /* @var $element \Zend\Form\Element\Text */
        $element = $this->getEle($name);
        $this->setAtributesAndOptions($element, $attr);

        $this->preFixName($name);
        echo $this->openDivInput($name, $element, ' container-calendar', $options),
        $this->doIfHasDisabledInput($name, $element);
        $element->setAttribute('class', 'form-control date');

        // Valores default para o calendário
        $aux = isset($options['params']) ? $options['params'] : [];
        $defaults = array_merge([
            'format' => 'dd/mm/yyyy',
            'language' => 'pt-BR',
            'forceParse' => FALSE,
            'autoclose' => TRUE,
            'todayHighlight' => TRUE,
            'maxViewMode' => 2
        ], $aux);

        $paramsCalendar = json_encode($defaults);

        $mask = '';
        if(!isset($options['noMask']) or !$options['noMask']){
            $defaultMask = str_ireplace(['d', 'm', 'y'], '9', $defaults['format']);
            $mask = isset($options['mask']) ? $options['mask'] : $defaultMask;
        }

        /*
         * Renderiza o html do calendário 
         */
        echo $this->formView->formText($element),
        '<span class="input-group-btn">',
        $this->iconClean($name, $element, $options),
        $this->iconCalend($name, $element),
        $this->iconExtra($name, $options),
        '</span>',
        $this->closeDivInput();
        /*
         * Renderiza o js do calendário 
         */
        echo '<script type="text/javascript">'
        . PHP_EOL
        . "$$('#{$name}').datepicker({$paramsCalendar});" . PHP_EOL
        . "$$('#{$name}').closest('.container-calendar').find('.clean').click(function(){"
        . "      $$('#{$name}').datepicker('clearDates');"
        . "});"
        . "$$('#{$name}').closest('.container-calendar').find('.calendar').click(function(){"
        . "      $$('#{$name}').focus();"
        . "});"
        . "$$('#{$name}').mask('{$mask}');"
        . ""
        . '</script>'
        . PHP_EOL
        . $this->showError();
        
        return $this;
    }

    /**
     * @author Paulo Watakabe
     * @version 1.0
     * Renderiza o input password com um botao para limpar o conteudo
     * Caso exista msg de erro sera exibo em vermelho
     * @param String $name
     * @return \Application\View\Helper\FormHelp
     */
    public function renderInputPassword($name, $opt = [], $attr = []) {
        /* @var $element \Zend\Form\Element\Password */
        $element = $this->getEle($name);
        $this->setAtributesAndOptions($element, $attr);
        echo $this->openDivInput($name, $element, ' passwd', $opt),
        $this->formView->formPassword($element),
        $this->iconClean($name, $element),
        $this->iconExtra($name, $opt),
        $this->closeDivInput(),
        $this->showError();
        return $this;
    }

    /**
     * @author Paulo Watakabe
     * @version 1.0
     * Gera o script em jquery para inserir a mascara de moeda no campo
     * @param string $name      Nome do campo que vai ter a mascara
     * @param string $symbol    Para exibir ou não o simbolo da moeda
     * @param string $dec       Quantidade de casas decimais do campo
     * @param string $thousands Separador para milhar default '.'
     * @return string 
     */
    public function getMoedaMascara($name, $symbol, $dec, $thousands = '.') {
        $this->preFixName($name);
        return PHP_EOL .
                '<script language="javascript">' .
                '$(function(){action.getEl("#' .
                $name .
                '").maskMoney({symbol:"R$ ", showSymbol:' .
                $symbol .
                ', thousands:"' .
                $thousands .
                '", decimal:",", symbolStay:true, precision:' .
                $dec .
                '});});' .
                '</script>' . PHP_EOL;
    }

    /**
     * Renderiza o input text no estilo moeda com um botao para limpar o conteudo  
     * Adiciona js para mascara de moeda
     * Caso exista msg de erro sera exibo em vermelho
     * 
     * @author Paulo Watakabe
     * @version 1.0
     * @param string $name    Nome do element form a ser renderizado
     * @param array $opt      ['symbo']Para exibir ou não o simbolo da moeda e ['dec'] Quantidade de casas decimais do campo
     * @param array $attr     Atributos para parametriza o elemento do form
     * @return \Application\View\Helper\FormHelp
     */
    public function renderInputMoeda($name, $opt = [], $attr = []) {
        /* @var $element \Zend\Form\Element\Text */
        if (!isset($opt['symbol'])) {
            $opt['symbol'] = 'true';
        }
        if (!isset($opt['dec'])) {
            $opt['dec'] = '2';
        }
        if (!isset($opt['thousands'])) {
            $opt['thousands'] = '.';
        }
        $element = $this->getEle($name);
        $attr['style'] = ((isset($attr['style'])) ? 'text-align:right; ' . $attr['style'] : 'text-align:right;');
        $this->setAtributesAndOptions($element, $attr);
        echo $this->openDivInput($name, $element, ' passwd', $opt),
        $this->doIfHasDisabledInput($name, $element),
        $this->formView->formText($element),
        $this->iconClean($name, $element, $opt),
        $this->iconExtra($name, $opt),
        $this->getMoedaMascara($name, $opt['symbol'], $opt['dec'], $opt['thousands']),
        $this->closeDivInput(),
        $this->showError();
        return $this;
    }

    /**
     * @author Paulo Watakabe
     * @version 1.0
     * Renderiza o input text no estilo float com um botao para limpar o conteudo  
     * Adiciona js para mascara de decimal
     * Caso exista msg de erro sera exibo em vermelho
     * @param String $name
     * @param array $opt      ['symbol']Para exibir ou não o simbolo da moeda e ['dec'] Quantidade de casas decimais do campo
     * @param array $attr     Atributos para parametriza o elemento do form
     */
    public function renderInputFloat($name, $opt = [], $attr = []) {
        return $this->renderInputMoeda($name, array_merge($opt, ['symbol' => 'false']), $attr);
    }

    /**
     * @author Paulo Watakabe
     * @version 1.0
     * Renderiza o input text no estilo float com um botao para limpar o conteudo  
     * Adiciona js para mascara de decimal
     * Caso exista msg de erro sera exibo em vermelho
     * @param String $name
     * @param array $opt      ['symbol']Para exibir ou não o simbolo da moeda e ['dec'] Quantidade de casas decimais do campo
     * @param array $attr     Atributos para parametriza o elemento do form
     */
    public function renderInputNumber($name, $opt = [], $attr = []) {
        return $this->renderInputMoeda($name, array_merge($opt, ['symbol' => 'false', 'dec' => '0', 'thousands' => '']), $attr);
    }

    /**
     * @author Paulo Watakabe
     * @version 1.0
     * Renderiza o input text no estilo float com um botao para limpar o conteudo  
     * Adiciona js para mascara de 4 decimal
     * Caso exista msg de erro sera exibo em vermelho
     * @param String $name
     * @param array $opt      ['symbo']Para exibir ou não o simbolo da moeda e ['dec'] Quantidade de casas decimais do campo
     * @param array $attr     Atributos para parametriza o elemento do form
     */
    public function renderInputFloat4($name, $opt = [], $attr = []) {
        return $this->renderInputMoeda($name, array_merge($opt, ['symbol' => 'false', 'dec' => '4']), $attr);
    }

    /**
     * Renderiza o input text no estilo float com um botao para limpar o conteudo  
     * Adiciona js para mascara de 8 decimal
     * Caso exista msg de erro sera exibo em vermelho
     * @param String $name
     * @param array $opt      ['symbo']Para exibir ou não o simbolo da moeda e ['dec'] Quantidade de casas decimais do campo
     * @param array $attr     Atributos para parametriza o elemento do form
     */
    public function renderInputFloat8($name, $opt = [], $attr = []) {
        return $this->renderInputMoeda($name, array_merge($opt, ['symbol' => 'false', 'dec' => '8']), $attr);
    }

    /**
     * Renderiza o input Radio com um botao para limpar o conteudo
     * Caso exista msg de erro sera exibo em vermelho
     * @author Paulo Watakabe
     * @version 1.0
     * @param type $name
     * @param array $opt      Opções do elemento e de renderização
     * @param array $attr     Atributos para parametriza o elemento do form
     * @return \Application\View\Helper\FormHelp
     */
    public function renderInputRadio($name, $opt = [], $attr = []) {
        /* @var $element \Zend\Form\Element\Radio */
        $element = $this->getEle($name);
        $this->setAtributesAndOptions($element, $attr);
        $css = '';
        if (isset($opt['align'])) {
            if ($opt['align'] == 'right') {
                $css = '" style="width:100%; text-align:right';
            }
            if ($opt['align'] == 'center') {
                $css = '" style="width:100%; text-align:center';
            }
        }
        echo $this->openDivInput($name, $element, $css, FALSE, $opt);

        echo $this->doIfHasDisabledInput($name, $element);
        if ($this->horizontal) {
            $element->setLabelAttributes(['class' => 'radio-inline']);
        }
        if (!isset($opt['sep'])) {
            $opt['sep'] = ($this->horizontal) ? ' | ' : '<br>';
        }
        if (isset($opt['partial']['ini'])) {
            $list = $element->getValueOptions();
            $element->setValueOptions($this->slice($list, $opt['partial']['ini'], isset($opt['partial']['fim']) ? $opt['partial']['fim'] : null));
        }
        echo $this->getRadio()->setSeparator($opt['sep'])->render($element),
        $this->iconExtra($name, $opt),
        $this->closeDivInput(),
        $this->showError();
        if (isset($opt['partial']['ini'])) {
            $element->setValueOptions($list);
        }
        return $this;
    }

    /**
     * Retorna o array cortado conforme os parametros do de inicio e fim.
     * Obs a funcao array_slice estava refazendo o indice o que não era esperado visto que o indice é a chave do regitro.
     * 
     * @author Paulo Watakabe
     * @version 1.0
     * @param array $array
     * @param integer $ini
     * @param integer $fim
     * @return array
     */
    public function slice(Array $array, $ini, $fim = null) {
        $newArray = [];
        $ind = -1;
        foreach ($array as $key => $value) {
            $ind++;
            if ($ind < $ini) {
                continue;
            }
            if (!is_null($fim) AND $ind >= $fim) {
                continue;
            }
            $newArray[$key] = $value;
        }
        return $newArray;
    }

    /**
     * 
     * @author Paulo Watakabe
     * @version 1.0
     * @param Array $opt contem as opcoes do checkbos como style position 
     * @param Object $element Objeto zend form checkbox
     * @param String $func Nome do metodo a ser chamado para renderização 
     * @param String $style Comando css para alterar visualização
     * @return String  contendo o html para visualização
     */
    public function positionLabelOfCheckbox($opt, &$element, $func = 'getCheckbox', $style = 'font-weight:bold') {
        $htmlCheckbox = '';
        if (isset($opt['style'])) {
            $style = $opt['style'];
        }
        $labelAttributes = ['style' => $style];
        isset($opt['labelWidth']) && $labelAttributes['class'] = 'col-md-' . $opt['labelWidth'] . ' control-label';
        if (isset($opt['position']) AND $opt['position'] == 'right') {
            $htmlCheckbox .= $this->$func()->render($element);
            $element->setLabelAttributes($labelAttributes);
            $htmlCheckbox .= $this->getLabel()->openTag($element) . $element->getLabel() . $this->getLabel()->closeTag() . (($this->horizontal) ? ' ' : '<br>');
        } else {
            if (!isset($opt['position']) OR $opt['position'] != FALSE) {
                $element->setLabelAttributes($labelAttributes);
                $htmlCheckbox .= $this->getLabel()->openTag($element) . $element->getLabel() . $this->getLabel()->closeTag() . (($this->horizontal) ? ' ' : '<br>') ;
                $element->setLabelAttributes([]);
                $htmlCheckbox .= $this->$func()->render($element);
            } else {
                $htmlCheckbox .= $this->$func()->render($element);
            }
        }
        return $htmlCheckbox;
    }

    /**
     * Renderiza o input Checkbox 
     * Caso exista msg de erro sera exibo em vermelho
     * @author Paulo Watakabe
     * @version 1.0
     * @param type $name
     * @param array $opt      Opções do elemento e de renderização
     * @param array $attr     Atributos para parametriza o elemento do form
     * @return \Application\View\Helper\FormHelp
     */
    public function renderInputCheckbox($name, $opt = [], $attr = []) {
        /* @var $element \Zend\Form\Element\Checkbox */
        $element = $this->getEle($name);
        $this->setAtributesAndOptions($element, $attr);
        $htmlCheckbox = '';
        $htmlCheckbox .= $this->openDivInput($name, $element, '', FALSE, array_merge(['noLabel' => TRUE], $opt));

        $htmlCheckbox .= $this->doIfHasDisabledInput($name, $element);
        if ($this->horizontal) {
            $element->setLabelAttributes(['class' => 'checkbox-inline']);
        }
        $htmlCheckbox .= $this->positionLabelOfCheckbox($opt, $element) .
                $this->iconExtra($name, $opt) .
                $this->closeDivInput() .
                $this->showError();
        echo $htmlCheckbox;
        return $this;
    }

    /**
     * Renderiza o input Checkbox com um botao para limpar o conteudo
     * Caso exista msg de erro sera exibo em vermelho
     * @author Paulo Watakabe
     * @version 1.0
     * @param type $name
     * @param array $opt      Opções do elemento e de renderização
     * @param array $attr     Atributos para parametriza o elemento do form
     * @return \Application\View\Helper\FormHelp
     */
    public function renderInputMultiCheckbox($name, $opt = [], $attr = []) {
        /* @var $element \Zend\Form\Element\MultiCheckbox */
        $element = $this->getEle($name);
        !isset($opt['noLabel']) && $opt['noLabel'] = TRUE;
        $this->setAtributesAndOptions($element, $attr);
        $htmlCheckbox = $this->openDivInput($name, $element, ' col-md-12', FALSE, $opt);
        $htmlCheckbox .= $this->doIfHasDisabledInput($name, $element);
        if ($this->horizontal) {
            $element->setLabelAttributes(['class' => 'checkbox-inline']);
        }
        if (isset($opt['partial']['ini'])) {
            $list = $element->getValueOptions();
            $element->setValueOptions($this->slice($list, $opt['partial']['ini'], isset($opt['partial']['fim']) ? $opt['partial']['fim'] : null));
        }
        $htmlCheckbox .= $this->positionLabelOfCheckbox($opt, $element, 'getMultiCheckbox');
        if (isset($opt['partial']['ini'])) {
            $element->setValueOptions($list);
        }
        if (!isset($opt['noClean'])) {
            $htmlCheckbox .= $this->iconClean($name, $element);
        }
        $htmlCheckbox .=
                $this->iconExtra($name, $opt) .
                $this->closeDivInput() .
                $this->showError();
        echo $htmlCheckbox;
        return $this;
    }

    /**
     * Verifica se campo esta desativado e gerar o campo oculto para manter seu valor padrão.
     * 
     * @author Paulo Watakabe
     * @version 1.0
     * @param string $name
     * @param object $element
     * @return string
     */
    public function doIfHasDisabledInput($name, $element) {
        if ($element->getAttribute('disabled')) {
            $this->preFixName($name);
            return '<input type="hidden" name="' . $name . '" value="' . $element->getValue() . '">';
        }
        return '';
    }

}
