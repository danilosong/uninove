<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Service;

/**
 * Description of GenerateSetsGets
 *
 * @author user
 */
class GenerateSetsGets {
    protected $returnClass;
    protected $author;
    protected $version;
    protected $date;
    protected $path;
    protected $file;
    protected $listVar = [];
    protected $listDes = [];
    
    public function __construct($options) {
        $this->path = "/var/www/tcmed/module/Application/src/Tcmed/Entity/LiberacaoExame.php";
        $this->returnClass = "\Tcmed\Entity\LiberacaoExame";
        $this->author = "Paulo Watakabe <watakabe05@gmail.com>";
        $this->version = "1.0";
        $this->date = date('d-m-Y');
        
        isset($options['path'])        && $this->path         = $options['path'];
        isset($options['returnClass']) && $this->returnClass  = $options['returnClass'];
        isset($options['author'])      && $this->author       = $options['author'];
        isset($options['version'])     && $this->version      = $options['version'];
        isset($options['date'])        && $this->date         = $options['date'];
//        isset($options['']) && $this->  = $options[''];
    }
    
    public function openFile($path='') {
        !empty($path) && $this->path = $path ;
        $this->file = file($this->path);
    }
    
    public function readListOfVars() {
        $this->listVar = [];
        foreach ($this->file as $line) {
            if(stripos($line, 'protected') === false AND stripos($line, 'public') === false AND stripos($line, 'private') === false){
                continue;
            }
            if(strpos($line, 'function') !== false){
                continue;
            }
            $words = explode(' ', $line);
            foreach ($words as $word){
                if (0 === strpos($word, '$')) {
                    $this->listVar[] = str_replace(['$',';',' ',PHP_EOL], '', $word);
                }
            }
        }
    }
     
    public function readListOfDes() {        
        $this->listDes = [];
        foreach ($this->file as $line) {
            if(stripos($line, '@var') === false){
                continue;
            }
            $words = explode(' ', $line);
            foreach ($words as $key => $word){
                if (stripos($word, '@var') !== false) {
                    $this->listDes[] = str_replace([' ',PHP_EOL],'',$words[$key + 1]);
                }
            }
        }
    }
    
    public function generatePairs($opt) {
        echo '<pre>';
        foreach ($this->listVar as $key => $var) {
            if($opt == '12' OR $opt == '1'){
                $set = $this->generateSeter($var, $this->listDes[$key]);
                echo htmlentities($set) , '<br>';
            }
            if($opt == '12' OR $opt == '2'){
                $get = $this->generateGeter($var, $this->listDes[$key]);
                echo htmlentities($get) , '<br>'; 
            }
        }
        echo '</pre>';
    }
    
    /**
     * Gera o metodo set da do campos lido da classe 
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 18-08-2016  
     * @param string $var  Nome da campo a ser usado para o nome do set
     * @param string $type Define o tipo do set a ser usado ex DateTime um set personalizado para DateTime 
     * @return string      Com o set montando baseado no seu tipo
     */
    public function generateSeter($var, $type) {
        $cvar = ucfirst($var);
        $Svar = '$' . $var;
        $fake = '$this';
        //Define se o metodo vai ter typeHint no metodo para os tipo abaixo não tem typeHint
        if(stripos($type, 'string' ) === false AND 
           stripos($type, 'boolean') === false AND 
           stripos($type, 'integer') === false AND 
           stripos($type, 'int'    ) === false AND
           stripos($type, 'timeInt') === false AND
           stripos($type, 'float'  ) === false
        ){
            $typeHint = $type . ' ';
        }else{
            $typeHint = '';
        }
        if(stripos($type, 'float' ) !== false){
            return $this->floatSet($var, $type, $Svar, $cvar, $fake);
        } 
        if(stripos($type, 'array' ) !== false){
            return $this->arraySet($var, $type, $Svar, $cvar, $fake);
        } 
        if(stripos($type, '\DateTime' ) !== false){
            return $this->dateTimeSet($var, $type, $Svar, $cvar, $fake);
        } 
        if(stripos($type, 'timeInt' ) !== false){
            return $this->timeIntSet($var, $type, $Svar, $cvar, $fake);
        }  
        if(substr_count($type, '\\') >= 3){
            return $this->relationSet($var, $type, $Svar, $cvar, $fake, $typeHint);
        } 
        return $this->normalSet($var, $type, $Svar, $cvar, $fake, $typeHint);
    }
    
    public function floatSet($var, $type, $Svar, $cvar, $fake) {
        $set = <<<EOT
    /**
     * 
     * @author {$this->author}
     * @version {$this->version}  
     * @since {$this->date}           
     * @param {$type} | string {$Svar}
     * @return {$this->returnClass}
     */
    public function set{$cvar}({$Svar} = 0) {
        {$fake}->{$var} = {$fake}->strToFloat({$Svar});
        return {$fake};
    }       
        
EOT;
        return $set; 
    }
    
    public function arraySet($var, $type, $Svar, $cvar, $fake) {
        $set = <<<EOT
    /**
     * Retorna um array ou string caso estiver sem o formato do array
                
     * @author {$this->author}
     * @version {$this->version}  
     * @since {$this->date}           
     * @param {$type} | string {$Svar} Default é um array vazio
     * @return {$this->returnClass}
     */
    public function set{$cvar}({$Svar} = []) {
        {$fake}->{$var} = {$fake}->arrayToStr({$Svar});
        return {$fake};
    }       
        
EOT;
        return $set; 
    }
    
    public function dateTimeSet($var, $type, $Svar, $cvar, $fake){
        $set = <<<EOT
    /**
     * 
     * @author {$this->author}
     * @version {$this->version}  
     * @since {$this->date}           
     * @param {$type} | string {$Svar}
     * @return {$this->returnClass}
     */
    public function set{$cvar}({$Svar} = '') {
        {$fake}->{$var} = {$fake}->strToDate({$Svar});
        return {$fake};
    }       
        
EOT;
        return $set;        
    }
    
    public function timeIntSet($var, $type, $Svar, $cvar, $fake){
        $set = <<<EOT
    /**
     * Converte as horas em minutos para Gravar no BD.
     * Pode converter esses tipos string formatada em H:i(07:35) ou objeto DateTime.
     * Ps caso o parametro for um inteiro não será convertido e o mesmo será retornado.   
     *           
     * @author {$this->author}
     * @version {$this->version}  
     * @since {$this->date}           
     * @param string | \DateTime | int {$Svar}
     * @return {$this->returnClass}
     */
    public function set{$cvar}({$Svar} = '') {
        {$fake}->{$var} = {$fake}->timeToInt({$Svar});
        return {$fake};
    }       
        
EOT;
        return $set;        
    }
    
    public function relationSet($var, $type, $Svar, $cvar, $fake, $typeClone) {        
        $set = <<<EOT
    /**
     * 
     * @author {$this->author}
     * @version {$this->version}  
     * @since {$this->date}           
     * @param {$type} {$Svar}
     * @return {$this->returnClass}
     */
    public function set{$cvar}({$typeClone}{$Svar} = NULL) {
        {$fake}->{$var} = {$Svar};
        return {$fake};
    }
        
EOT;
        return $set;
    }
    
    public function normalSet($var, $type, $Svar, $cvar, $fake, $typeClone) {        
        $set = <<<EOT
    /**
     * 
     * @author {$this->author}
     * @version {$this->version}  
     * @since {$this->date}           
     * @param {$type} {$Svar}
     * @return {$this->returnClass}
     */
    public function set{$cvar}({$typeClone}{$Svar}) {
        {$fake}->{$var} = {$Svar};
        return {$fake};
    }
        
EOT;
        return $set;
    }
    
    /**
     * Gera o metido Geter do campo da entity baseado em seu tipo de variavel
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 18-08-2016  
     * @param string $var   Nome do campo
     * @param string $type  Tipo do campo string, obj, DateTime, etc...
     * @return string       Metodo get no formato para uso na classe
     */
    public function generateGeter($var, $type) {
        $cvar = ucfirst($var);
        $fake = '$this';
        if(stripos($type, 'float' ) !== false){
            return $this->floatGet($var, $type, $cvar, $fake);
        } 
        if(stripos($type, 'array' ) !== false){
            return $this->arrayGet($var, $type, $cvar, $fake);
        } 
        if(stripos($type, '\DateTime' ) !== false){
            return $this->dateTimeGet($var, $type, $cvar, $fake);
        }  
        if(stripos($type, 'timeInt' ) !== false){
            return $this->timeIntGet($var, $type, $cvar, $fake);
        } 
        if(substr_count($type, '\\') >= 3){
            return $this->relationGet($var, $type, $cvar, $fake);
        } 
        return $this->normalGet($var, $type, $cvar, $fake);
    }
    
    public function floatGet($var, $type, $cvar, $fake) { 
        $formated = '$formated';
        $get = <<<EOT
    /**
     * 
     * @author {$this->author}
     * @version {$this->version}  
     * @since {$this->date}           
     * @param boolean | integer {$formated} False para retornar float sem formatação ou integer para qtd de casas decimais padrao 2          
     * @return {$type} | string
     */
    public function get{$cvar}({$formated} = TRUE) {
        return {$fake}->floatToStr({$fake}->{$var}, {$formated});
    }
        
EOT;
        return $get; 
    }
    
    public function arrayGet($var, $type, $cvar, $fake) {
        $get = <<<EOT
    /**
     * Retorna um array que foi gravado no BD.
     * Se a gravação não estiver no formato de array retorna string          
     * @author {$this->author}
     * @version {$this->version}  
     * @since {$this->date}           
     * @return {$type} | string
     */
    public function get{$cvar}() {
        return {$fake}->strToArray({$fake}->{$var});
    }
        
EOT;
        return $get;         
    }
    
    public function dateTimeGet($var, $type, $cvar, $fake) {   
        $full = '$full';
        $obj  = '$obj';
        if($var == 'updatedAt'){
        $get = <<<EOT
    /**
     * 
     * @author {$this->author}
     * @version {$this->version}  
     * @since {$this->date}   
     * @param boolean | string {$obj}  true  retorna o objeto DateTime, falso formatação d/m/Y,  string = 'full' formatação d/m/Y H:i:s ou a formatação especificada na string
     * @param boolean | string {$full} falso formatação definida pelo param {$obj} se string formatação especificada no conteudo
     * @return {$type} | string
     */
    public function get{$cvar}({$obj} = FALSE, {$full} = FALSE) {
        if(is_null({$fake}->getUpdatedBy())){
            return '-';
        }
        return {$fake}->dateToStr({$fake}->{$var}, {$full}, {$obj});
    }
        
EOT;
        }else{
        $get = <<<EOT
    /**
     * 
     * @author {$this->author}
     * @version {$this->version}  
     * @since {$this->date}   
     * @param boolean | string {$obj}  true  retorna o objeto DateTime, falso formatação d/m/Y,  string = 'full' formatação d/m/Y H:i:s ou a formatação especificada na string
     * @param boolean | string {$full} falso formatação definida pelo param {$obj} se string formatação especificada no conteudo
     * @return {$type} | string
     */
    public function get{$cvar}({$obj} = FALSE, {$full} = FALSE) {
        return {$fake}->dateToStr({$fake}->{$var}, {$full}, {$obj});
    }
        
EOT;
        }
        return $get;  
    }
    
    public function timeIntGet($var, $type, $cvar, $fake) {   
        $convert = '$convert';
        $get = <<<EOT
    /**
     * Retorna hora no formato H:i ou um inteiro representando os minutos.
     * 
     * @author {$this->author}
     * @version {$this->version}  
     * @since {$this->date}   
     * @param boolean | string {$convert} Opcional Default true converte int do BD em H:i, Caso falso retorna um inteiro
     * @return int | String formatada em H:i
     */
    public function get{$cvar}({$convert} = TRUE) {
        return {$fake}->intToTime({$fake}->{$var}, {$convert});
    }
        
EOT;
        return $get;  
    }
    
    public function normalGet($var, $type, $cvar, $fake) {
        $get = <<<EOT
    /**
     * 
     * @author {$this->author}
     * @version {$this->version}  
     * @since {$this->date}           
     * @return {$type}
     */
    public function get{$cvar}() {
        return {$fake}->{$var};
    }
        
EOT;
        return $get;       
    }
    
    /**
     * Gera o metodo get com a logica para pegar entidades relacionada.
     * Facilita a chamada de metodos dentro da entidade.
     * Caso passe o parametro e a relação for a relação for null será retornado string '-'  
     *          
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 19-10-2016   
     * @param string $var
     * @param string $type
     * @param string $cvar
     * @param string $fake
     * @return string
     */
    public function relationGet($var, $type, $cvar, $fake) {
        $param = '$params';
        $meth = '$method';
        $get = <<<EOT
    /**
     * Retorna a entidade  ou um atributo se for passado por parametro.
     * Caso passe o parametro e a relação for a relação for null será retornado string '-'           
     * @author {$this->author}
     * @version {$this->version}  
     * @since {$this->date}   
     * @param string {$meth}  Nome do method get a ser retornado da relação.
     * @param array  {$param} Parametro(s) a serem usado neste  get da relação.
     * @return {$type}
     */
    public function get{$cvar}({$meth}='', Array {$param}=[]) {
        return {$fake}->relationGet({$fake}->{$var}, {$meth}, {$param});            
    }
EOT;
        return $get;         
    }
    
    
}
