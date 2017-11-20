<?php
/*
 * License GPL .
 * 
 */

namespace Application\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;

/**
 * Param
 * View Helper para Buscar parametros para exibir na visualização
 *      Passa a chave e o indice do array a ser pesquisado
 *      Possibilidade de usar call back para facilitar a chamada
 * 
 * @author Paulo Cordeiro Watakabe <watakabe05@gmail.com>
 */
class Param extends AbstractHelper {

    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;
    
    /**
     * Tabela parametros do sistema
     * 
     * @var string 
     */
    protected $entParam;
    
    /**
     * Tabela parametros do sistema
     * 
     * @var \Application\Entity\Repository\ParametrosRepository 
     */
    protected $rpParam;
    
    /**
     * key do registro da tabela parametros array para pegar o valor
     * 
     * @var string 
     */
    protected $keyParam;
    
    /**
     * key do array para pegar o valor
     * 
     * @var string 
     */
    protected $key;
    
    /**
     * Valor que esta em determinada key de um arra de parametro normalmente uma string.
     * 
     * @var mix 
     */
    protected $value;
    
    /**
     * Array de dados de um chave especifica da tabela parametro
     * 
     * @var array 
     */
    protected $array = [];

    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param type $sm
     */
    public function __construct($sm) {
        $this->em = $sm->get('Doctrine\ORM\EntityManager');
        $this->entParam = "\Application\Entity\Parametros";
        $this->rpParam = $this->em->getRepository($this->entParam);
    }    
    
    /**
     * Metodo invoke chamado pela view ao executar esta classe 
     * Retorna sua propria instacia se nenhum parametro for passado.
     * Executa a busca na cache se ja existe o parametro caso não busca na base de dados
     * 
     * @param string $key      chave para pegar a descição do item
     * @param string $keyParam chave para pegar todas as descrições
     * @return \Application\View\Helper\Param | string
     */
    public function __invoke($key='', $keyParam='') {
        if(empty($key) AND empty($keyParam)){
            return $this;               
        }
        $this->setKeyParam($keyParam);
        return $this->getValue($key);
    }

    /**
     * Retorna o caminha da classe
     * 
     * @return string
     */
    public function __toString() {
        return '\Application\View\Helper\Param';
    }
    
    /**
     * Retorna todos os itens de determinada chave
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 26-05-2017 
     * @return string
     */
    public function getItensFor($keyParam) {
        if(!isset($this->array[$keyParam])){
            $this->array[$keyParam] = $this->rpParam->fetchPairs($keyParam, FALSE);
            $this->keyParam = $keyParam;
        }
        return $this->array[$keyParam];
    }
    
    /**
     * Retorna a chave dos registros do BD
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @return string
     */
    public function getKeyParam() {
        return $this->keyParam;
    }

    /**
     * Retorna a chave do item que buscou no BD.
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @return string
     */
    public function getKey() {
        return $this->key;
    }

    /**
     * Retorna o item do chave existente.
     * Caso key for empty sera considera como zero motivo compatibilidade com doctrine com valores boolean
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param string $key Se for empty sera atruido zero a ele
     * @return string valor que esta no item da array de dados com a chave atual
     */
    public function getValue($key='') {
        if(empty($key)){            
            $key = '0';            
        }
        if(isset($this->array[$this->keyParam][$key])){
            $this->value = $this->array[$this->keyParam][$key];
            return $this->value;            
        }
        return 'não encontrado ' . $key;
    }

    /**
     * Grava no array os dados com a keyParam que foi passada.
     * Caso já exista apenas atualiza a keyParam
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param type $keyParam
     * @return \Application\View\Helper\Param
     */
    public function setKeyParam($keyParam='') {
        if(empty($keyParam)){
            return $this;            
        }
        if(!isset($this->array[$keyParam])){
            $this->array[$keyParam] = $this->rpParam->fetchPairs($keyParam, FALSE);
        }
        $this->keyParam = $keyParam;
        return $this;
    }

}
