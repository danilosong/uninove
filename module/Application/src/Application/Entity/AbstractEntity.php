<?php

/*
 * License
 */

namespace Application\Entity;

use Zend\Stdlib\Hydrator;

/**
 * Description of AbstractEntity
 * Metodos abstraidos comum para todas as entidades
 *
 * @author Paulo Watakabe <watakabe05@gmail.com>
 */
abstract class AbstractEntity {

    /**
     * Parametro necessario para determinar a recursividade do metodo extractObjects
     * Ha casos onde a recursividade pode virar um loop infinito se não houver limite de no da arvore.
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @var integer  Quantidade de nos que será retornado em um array padrão é infinito.
     */
    protected $nivel = 0;

    /**
     * Parametro de controle do nivel de recursividade do metodo extractObjects
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @var integer  Nivel em que se esta extraindo um array do objeto
     */
    private $nivelAtual = 0;

    /**
     * @todo Ajustar todas as entidades para não precisar mais de ser passado paramentro no metodos de setCreatedAt
     * @author Paulo Watakabe <watakabe05@gmail.com>
     */
    public function __construct(array $options = []) {
        if (method_exists($this, 'setCreatedAt')) {
            $this->setCreatedAt(new \DateTime('now'));
            if (method_exists($this, 'setUpdatedAt')) {
                $this->setUpdatedAt();
            }
        }
        (new Hydrator\ClassMethods)->hydrate($options, $this);
    }

    /**
     * Extrai todos os metodos get para um array de dados.
     * Caso encontre um get que retorne um objeto ira tentar em converte-lo para um array de dados
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @param integer $limite Indica o numero de galhos que deverá percorrer dentro da arvore do array
     * @param string  $prefix Coloca um prefixamento inicial no array
     * @return array Array com os dados da entides e pode tb retornar os dados de suas relações
     */
    public function toArray($limite = 0, $prefix = '') {
        $data = [];
        $this->nivelAtual = 0;
        $this->nivel = 0;
        if ($limite === 0) {
            $this->extractObjects($data, $this, $prefix);
        } else {
            $this->nivel = $limite;
            $this->extractObjects($data, $this, $prefix, TRUE);
        }
        return $data;
    }

    /**
     * Extrai todos os metodos get para um array de dados.
     * Caso encontre um get que retorne um objeto ira tentar em converte-lo para um array de dados
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @since 26-02-2016
     * @param integer $limite Indica o numero de galhos que deverá percorrer dentro da arvore do array
     * @return array Array com os dados da entides e pode tb retornar os dados de suas relações
     */
    public function toArrayNew($limite = 0) {
        $data = [];
        $this->nivelAtual = 0;
        $this->nivel = 0;
        if ($limite === 0) {
            $this->extractObjectsFullArray($data, $this);
        } else {
            $this->nivel = $limite;
            $this->extractObjectsFullArray($data, $this, TRUE);
        }
        return $data;
    }

    /**
     * Retorna os dados prefixando o inicio do array
     * Motivo em caso que o form da entidade nao for o form principal
     * Mas sim dependente de outro form
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @param string $prefix
     * @param integer $limite
     * @return array
     */
    public function toArrayPrefixing($prefix = '', $limite = 0) {
        return $this->toArray($limite, $prefix);
    }

    /**
     * Extrair do objeto um array de dados baseados em todos os metodos get existentes na classe
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @param array $dataRet
     * @param object | Entidade $obj
     * @param string $prefix
     */
    public function extractObjects(&$dataRet, &$obj, $prefix = '', $recursive = FALSE) {
        $data = (new Hydrator\ClassMethods())->setUnderscoreSeparatedKeys(FALSE)->extract($obj);
        $this->nivelAtual ++;
        foreach ($data as $key => $value) {
            // value não é um objeto então seta o valor no array e le proximo
            if (!is_object($value)) {
                $dataRet[empty($prefix) ? $key : $prefix . '[' . $key . ']'] = $value;
                continue;
            }
            // recursive é falso e value é um objeto com metodo getId  seta o valor no array e le proximo
            if (!$recursive AND method_exists($value, 'getId')) {
                $dataRet[empty($prefix) ? $key : $prefix . '[' . $key . ']'] = $value->getId();
                continue;
            }
            // recursive é true e value é um objeto sem metodo toArray  seta o objeto no array e le proximo
            if (!method_exists($value, 'toArray')) {
                $dataRet[empty($prefix) ? $key : $prefix . '[' . $key . ']'] = $value;
                continue;
            }
            if ($this->nivel === 0) {
                $this->extractObjects($dataRet, $value, empty($prefix) ? $key : $prefix . '[' . $key . ']', $recursive);
                continue;
            }
            if ($this->nivel >= $this->nivelAtual) {
                $this->extractObjects($dataRet, $value, empty($prefix) ? $key : $prefix . '[' . $key . ']', $recursive);
                continue;
            }
            if (method_exists($value, 'getId')) {
                $dataRet[empty($prefix) ? $key : $prefix . '[' . $key . ']'] = $value->getId();
                continue;
            }
            $dataRet[empty($prefix) ? $key : $prefix . '[' . $key . ']'] = $value;
        }
        $this->nivelAtual --;
    }

    /**
     * Extrair do objeto um array de dados baseados em todos os metodos get existentes na classe
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @since 25-02-2016
     * @param array $dataRet
     * @param object | Entidade $obj
     * @param boolean $recursive
     */
    public function extractObjectsFullArray(&$dataRet, &$obj, $recursive = FALSE) {
        $data = (new Hydrator\ClassMethods())->setUnderscoreSeparatedKeys(FALSE)->extract($obj);
        $this->nivelAtual ++;
        foreach ($data as $key => $value) {
            // value não é um objeto então seta o valor no array e le proximo
            if (!is_object($value)) {
                $dataRet[$key] = $value;
                continue;
            }
            // recursive é falso e value é um objeto com metodo getId  seta o valor no array e le proximo
            if (!$recursive AND method_exists($value, 'getId')) {
                $dataRet[$key] = $value->getId();
                continue;
            }
            // recursive é true e value é um objeto sem metodo toArray  seta o objeto no array e le proximo
            if (!method_exists($value, 'toArray')) {
                $dataRet[$key] = $value;
                continue;
            }
            // recursive é true e value é um objeto com metodo toArray nivel é zero extrai array e le proximo
            if ($this->nivel === 0) {
                $this->extractObjectsFullArray($dataRet[$key], $value, $recursive);
                continue;
            }
            // recursive é true e value é um objeto com metodo toArray nivel é menor que nivel atual extrai array e le proximo
            if ($this->nivel >= $this->nivelAtual) {
                $this->extractObjectsFullArray($dataRet[$key], $value, $recursive);
                continue;
            }
            // recursive é true e value é um objeto com metodo toArray nivel é maior que nivel atual com metodo getId seta o valor no array e le proximo
            if (method_exists($value, 'getId')) {
                $dataRet[$key] = $value->getId();
                continue;
            }
            // recursive é true e value é um objeto com metodo toArray nivel é maior que nivel atual sem metodo getId seta o valor no array e le proximo
            $dataRet[$key] = $value;
        }
        $this->nivelAtual --;
    }

    /**
     * Converte a variavel do tipo float para string para exibição
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @param float $float       Float a ser trabalhado
     * @param int $dec           Numero de casa decimais a colocar na formataçao padrão 2
     * @param boolean $format    Diz deve formatar para string ou retorna sem formatação
     * @return string | float    Retorna um string formatada para exibição ou o proprio float
     */
    public function floatToStr($float, $dec = 2, $format = TRUE) {
        if (is_bool($dec)) {
            $format = $dec;
            $dec = 2;
        }
        if (!$format) {
            return $float;
        }
        if(!is_numeric($float)){
            return 0.0;
        }
        return number_format($float, $dec, ',', '.');
    }

    /**
     * Faz tratamento na variavel string se necessario antes de converte em float
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @param String $check variavel a ser convertida se tratada se necessario
     * @return String $check no formato float para gravação pelo doctrine
     */
    public function strToFloat($check) {
        if (is_string($check)) {
            //Retira tudo que não for numero e virgula e depois converte virgula em ponto
            return str_replace(",", ".", preg_replace("/[^0-9,-]/", "", $check));
        }
        return $check;
    }

    /**
     * Converte um string para obj datetime se for um string valida
     * Caso o parametro for um object datetime retornara ele proprio
     * Faz tratamento da string
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @param string | \DateTime $strDateTime
     * @return \DateTime
     */
    public function strToDate($strDateTime = '') {
        if ($strDateTime instanceof \DateTime) {
            return $strDateTime;
        }
        switch (TRUE) {
            case empty($strDateTime):
                return new \DateTime('now');

            case (isset($strDateTime[2]) AND $strDateTime[2] == '/'):
                if (isset($strDateTime[15])) {
                    $dh = explode(' ', $strDateTime);
                    $d = explode('/', $dh[0]);
                    $h = $dh[1];
                } else {
                    $d = explode('/', $strDateTime);
                    $h = '';
                }
                $s = $d[2] . '-' . $d[1] . '-' . $d[0] . $h;
                break;

            case ($strDateTime == '-'):
                $s = '1901-01-01 12:00:00';
                break;
            case ('NULL' == strtoupper($strDateTime)):
                return NULL;
            default:
                $s = $strDateTime;
        }
        return new \DateTime($s);
    }

    /**
     * Converte um obj datetime para string para exibição html
     * Caso $full for string ele usa como parametro para formatação da data
     * Caso $full for falso  ele converte como parametro para formatação da data de d/m/Y
     * Caso $obj contiver a string "full" parametriza $full para 'd/m/Y h:m'
     * Caso $obj for True retorna o proprio object
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @param \DateTime $date
     * @param string | bollean $full
     * @param string | bollean $obj
     * @return string | \DateTime
     */
    public function dateToStr($date, $full = false, $obj = false) {
        if ($obj === TRUE) {
            return $date;
        }
        if ($obj === 'full') {
            $full = 'd/m/Y H:i:s';
        } else {
            if (is_string($obj)) {
                $full = $obj;
            }
        }
        if (!is_string($full)) {
            $full = 'd/m/Y';
        }
        if ($date instanceof \DateTime) {
            if ($date->format('Y-m-d') == '1901-01-01') {
                return '-';
            }
            return $date->format($full);
        } else {
            return '-';
        }
    }

    /**
     * Converte uma string de dados para um array com indice numero ou a propria string
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since ?
     *
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @version 2.0 (atual) Troca da lógica de conversão anterior para a nova,
     * utilizando o formato JSON para decodificação para o formato de Array
     * @since 09-06-2016
     *
     * @param string $str   String para fazer a conversão em array numerico
     * @return array | string Retorna um array se na string tiver a marcação de array ou a propria string
     */
    public function strToArray($str) {
        if(!is_string($str)){
            return [];
        }
        if(substr($str, 0, 7) == 'array[]'){
            return explode('||', str_replace('array[]||', '', $str));
        }
        if(substr($str, 0, 1) != '[' AND substr($str, 0, 1) != '{'){
            return $str;
        }
        return $this->objectToArray(json_decode($str, TRUE));
    }

    /**
     * Converte recursivamento um objeto json(stdClass) para um associative array
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 23-08-2016
     * @param \stdClass $data
     * @return array
     */
    public function objectToArray($data) {
        // casso item for um object gera um array associativo das variaveis publica do object
        if (is_object($data)) {
            $data = get_object_vars($data);
        }
        // Caso item for um array aplica este metodo em todo os item do array procurando por um objeto
        if (is_array($data)) {
            return array_map(array($this, __FUNCTION__), $data);
        }
        // Caso item não for um object ou array retorna o valor
        return $data;
    }

    /**
     * Converte um array de dados para ser gravado no banco no formato de string
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since ?
     *
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @version 2.0 (atual) Troca da lógica de conversão anterior para a nova,
     * utilizando o formato Array para codificação para o formato de JSON
     * @since 09-06-2016
     *
     * @param array | string $array array com indice numerico ou string
     * @return string array convertido em string ou a propria string se nenhum array for passado
     */
    public function arrayToStr($array) {
        if (is_array($array)) {
            return json_encode($array);
        }
        return $array;
    }

    /**
     * Coloca mascara no numero tanto mascara e numero deve ser passado por parametro
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param string $mask Ex: ##.###.###/####-##
     * @param string $str  Ex: 17804682000198
     * @return string      Ex: 17.804.682/0001-98
     */
    public function mask($mask, $str) {
        for ($i = 0; $i < strlen($str); $i++) {
            if (strpos($mask, "#") !== FALSE) {
                $mask[strpos($mask, "#")] = $str[$i];
            }
        }
        return $mask;
    }

    /**
     * Limpa a string retirando todos os simbolos e caracteres
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param string  $str     String a ser limpa
     * @param boolean $opt     True limpa simbolos e caracteres : falso limpa somente simbolos default é true
     * @param integer $lenght  Define a quantidade de casas e colocará zeros a esquerda para completar o tamanho default é zero(não faz nada)
     * @return string          String limpa conforme os parametros
     */
    public function clean($str, $opt = TRUE, $length = 0) {
        // redireciona parametro se for integer em $opt para $lenght
        if (is_int($opt)) {
            $length = $opt;
            $opt = TRUE;
        }
        // se Não for uma string retorna sem tratamento
        if (!is_string($str)) {
            return $str;
        }
        $reg = ($opt) ? "/[^0-9]/" : "/[^0-9a-zA-Z]/";
        $cleaned = preg_replace($reg, "", $str);
        if ($length > 0) {
            if (strlen($cleaned) > $length) {
                $cleaned = substr($cleaned, 0, $length);
            }
            // colocar zeros a esquerda
            $cleaned = str_pad($cleaned, $length, "0", STR_PAD_LEFT);
        }
        return $cleaned;
    }

    /**
     * Abstração para retornar o objeto ou métodos do objeto de modo encadeado
     *
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @since 28-01-2017
     * @param mixed $object
     * @param string $get
     * @param array $params
     */
    public function relationGet($object = null, $get = '', $params = []) {
        if(empty($get)){
            return $object;
        }
        if(is_null($object)){
            return '-';
        }
        $method = !method_exists($object, $get) ? 'get' . ucfirst($get) : $get;
        if(empty($params)){
            return $object->$method();
        }
        return call_user_func_array([$object, $method], $params);
    }

    /**
     * Converte a hora em um valor inteiro
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 11-09-2017 
     * @param string|\DateTime $strTime Aceita no formato string(12:30) ou object dateTime
     * @return int Formato de horas convertido em valor inteiro(hor * 60 + min)
     */
    public function timeToInt($strTime = '') {
        if(is_int($strTime)){
            return $strTime;
        }
        
        if ($strTime instanceof \DateTime) {
            $strTime = $strTime->format('H:i');
        }

        if (!is_string($strTime) or FALSE == strpos($strTime, ':')) {
            return $this->strToInt($strTime);
        }

        list($hora, $minuto) = explode(":", $strTime);

        // Quando há valores em hora, então multiplicar por 60
        $hora = $this->strToInt($hora) * 60;
        $minuto = $this->strToInt($minuto);
        
        return $hora + $minuto;
    }

    /**
     * Converte um valor inteiro em uma string(hh:mm) de hora
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 11-09-2017 
     * @param int $intTime Valor inteiro para ser convertido em horas
     * @return string(hh:mm)
     */
    public function intToTime($intTime = 0, $convert = TRUE) {
        $hora = 0;
        
        if(preg_match('/[0-9]+:[0-5][0-9]/', $intTime)){
            return $intTime;
        }
        
        if (!$convert) {
            return $intTime;
        }
        
        $intTime = $this->strToInt($intTime);

        if (59 < $intTime) {
            $minuto = $intTime % 60;
            $hora = ($intTime - $minuto) / 60;
        } else {
            $minuto = $intTime;
        }

        if (2 > strlen((string) $hora)) {
            $hora = str_pad((string) $hora, 2, '0', STR_PAD_LEFT);
        }

        $minuto = sprintf("%02d", $minuto);
        return "{$hora}:{$minuto}";
    }

    /**
     * Converte um valor string em inteiro
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 11-09-2017 
     * @param string $value
     * @return int 
     */
    public function strToInt($value) {
        // Ignora qualquer valor que seja diferente de String, int ou float
        if (!is_int($value) and ! is_float($value) and ! is_string($value)) {
            return 0;
        }
        // Quando $value for float, converte para string
        is_float($value) && $value = $this->intToStr($value);

        // Converte o valor string em inteiro ou float e retorna
        return (int) $this->strToFloat($value);
    }

    /**
     * Converte um valor inteiro em String
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 11-09-2017 
     * @param int|float $value
     * @return String 
     */
    public function intToStr($value) {
        // Ignora qualquer valor que seja diferente de String, int ou float
        if (!is_int($value) and ! is_float($value) and ! is_string($value)) {
            return '';
        }
        // se $value for string, converte para int
        is_string($value) && $value = $this->strToInt($value);

        // Se $value for float, converte para int
        is_float($value) && $value = (int) $value;

        // Converte o valor inteiro ou float em string e retorna
        return $this->floatToStr($value, 0) . '';
    }
}
