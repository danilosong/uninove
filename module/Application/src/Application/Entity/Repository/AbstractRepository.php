<?php

/*
 * Licenced for AEM and Tecnomed.
 */

namespace Application\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * AbstractRepository
 * Todos os metodos mais usados nas manipulação do BD
 * 
 * @author Paulo Cordeiro Watakabe <watakabe05@gmail.com>
 */
abstract class AbstractRepository extends EntityRepository {
    
    /**
     * Monta a dql com filtros previavemente tratados no service para pesquisar no banco 
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 26-12-2016 
     * @param array $filters
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function pesquisa($filters) {
        $query = $this->getEntityManager()
                ->createQueryBuilder()
                ->select(isset($filters['select']) ? $filters['select'] : 'e')
                ->from($this->_entityName, 'e')
        ;
        unset($filters['select']);
        $this->setBasicaFilters($query, $filters);
        return $query;
    }

    /**
     * Converte uma data string em data object no indice apontado.
     * @param string $date
     * @return \DateTime
     */
    public function dateToObject($date = '') {
        //Trata as variveis data string para data objetos
        if (empty($date)) {
            return FALSE;
        }

        if (is_object($date)) {
            if ($date instanceof \DateTime) {
                return $date;
            } else {
                return FALSE;
            }
        }

        $dat = explode("/", $date);
        return new \DateTime($dat[1] . '/' . $dat[0] . '/' . $dat[2]);
    }

    /**
     * Converte um string para obj datetime se for um string valida
     * Faz tratamento da string 
     * @param type $strDateTime
     * @return \DateTime
     */
    public function strToDate($strDateTime) {
        switch (TRUE) {
            case empty($strDateTime):
                return new \DateTime('now');

            case ($strDateTime[2] == '/'):
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

            default:
                $s = $strDateTime;
        }
        return new \DateTime($s);
    }

    /**
     * Auto complete em ajax esta função retorna as entitys encontradas
     * com a ocorrencia passada por parametro
     * @param string $user
     * @return array
     */
    public function autoComp($column = 'nome', $data = '', $anotherFilters = [], $orderBy = [], $qtd = 40) {

        $query = $this->getEntityManager()
                ->createQueryBuilder()
                ->select('e')
                ->from($this->_entityName, 'e')
                ->where("e." . $column . " LIKE :" . $column)
                ->setParameter($column, $data);

        $this->setBasicaFilters($query, $anotherFilters);
        
        // trata variavel ordenado
        if (!is_array($orderBy)) {
            is_string($orderBy)   && !empty($orderBy)  && $orderBy = ['e.' . $orderBy => 'ASC'];
            !is_array($orderBy)   && $orderBy = [];
        }
        //Montar Ordenação
        foreach ($orderBy as $key => $value) {
            if (strpos($key, '.') === false) {
                $query->addOrderBy('e.' . $key, $value);
            } else {
                $query->addOrderBy($key, $value);
            }
        }
        return $query->setMaxResults($qtd)->getQuery()->getResult();
    } 
    
    /**
     * Metodo basico para converter entradas dos filtros e itens da query
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.5  
     * @since 21-08-2016 
     * @since 31-08-2016 Melhoria fazendo abstração do col para sua table target e verificação do value is null
     * @since 05-10-2016 Implementação do metodo getPersonalFilterFor
     * @param \Doctrine\ORM\QueryBuilder $query
     * @param array $filters
     */      
    public function setBasicaFilters($query, &$filters) {
        foreach ($filters as $key => $value) {
            if (strpos($key, '.') === false) {
                $colJoin = 'e.' . $key;
                $colName = $key;
            } else {
                $colJoin = $key;
                $colName = str_replace('.', '', $key);
            }
            if($this->getPersonalFilterFor($colJoin, $colName, $value, $query)){
                continue;
            }
            switch (TRUE) {
                case is_numeric($value):
                case $value instanceof \DateTime:
                    $query->andWhere($colJoin . ' = :' . $colName)->setParameter($colName, $value);
                    break;

                case is_array($value):
                    $op  = key($value);
                    $vlr = $value[$op];
                    if('between' == $op){
                        $query->andWhere($colJoin . ' '. $op .' :' . $colName.'A AND :' . $colName.'B')->setParameter($colName.'A',$vlr[0])->setParameter($colName.'B',$vlr[1]);
                    }else{
                        $query->andWhere($colJoin . ' '. $op .' :' . $colName . (strpos($op, '(') ? ')' : ''))->setParameter($colName, $vlr);
                    }
                    break;
                
                case (is_string($value) AND "NULL" == strtoupper($value)):
                case is_null($value):
                    $query->andWhere($colJoin . ' IS NULL');
                    break;

                case empty($value):
                    continue;

                default:
                    $query->andWhere($colJoin . ' LIKE :' . $colName)->setParameter($colName, $value);
                    break;
            }
        }
    }
    
    /**
     * Metodo gerenerico para escrita de filtros personalizaveis 
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 05-10-2016 
     * @param string $colJoin Nome da coluna com seu respectivo join na dql
     * @param string $colName Nome da input tratado para não haver itens repetidos
     * @param mixed  $value Valor a ser colocado na coluna da tabela
     * @param \Doctrine\ORM\QueryBuilder $query
     * @return boolean Se o filtro for escrito com sucesso retorna true
     */
    public function getPersonalFilterFor($colJoin, $colName, $value, $query){
        return false;
    }

    /**
     * Retorna um array com os pares(id nome) de todos os dados dessa entidade
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 29-08-2017  
     * @param string  $methd    Metodo a ser chamado para exibir a descrição do item do select
     * @param array   $filters  Filtros para limitar o resultado da consulta
     * @param boolean $first    Se vai exibir o item vazio (selecione na lista)
     * @param string  $orderBy  Define uma regra para ordenação do resultado
     */
    public function fetchPairs($methd = 'getNome', $filters = [], $first = TRUE, $orderBy = NULL) {

        if (is_bool($filters)) {
            $first = $filters;
            $filters = [];
        }

        /* @var $entity \Application\Entity\ENTIDADE */
        $entities = $this->findBy($filters, $orderBy);

        $array = [];
        if ($first) {
            $array[''] = 'Selecione na lista';
        }


        foreach ($entities as $entity) {
            $array[$entity->getId()] = call_user_func(array($entity, $methd));
        }
        return $array;
    }

    /**
     * Facilitador para limpar tabela do bd mesmo com chave estrangeira.
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 05-03-2016  
     * @param string $entity Opcional por padrao ele trunca a propria entity
     * @return boolean Verdadeiro em casode sucesso
     */
    public function truncate($entity = ''){   
        if(empty($entity)){
            $entity = $this->getClassName();
        }
        $manager = $this->getEntityManager();
        $cmd = $manager->getClassMetadata($entity);
        $connection = $manager->getConnection();
        $dbPlatform = $connection->getDatabasePlatform();
        $connection->query('SET FOREIGN_KEY_CHECKS=0');
        $q = $dbPlatform->getTruncateTableSql($cmd->getTableName());
        $connection->executeUpdate($q);
        $connection->query('SET FOREIGN_KEY_CHECKS=1');
        return true;
    }
    
    
    /**
     * Limpa a string retirando todos os simbolos e caracteres 
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @since 08-03-2016 
     * @version 1.0
     * @param string  $str     String a ser limpa
     * @param boolean $opt     True limpa simbolos e caracteres : falso limpa somente simbolos default é true
     * @param integer $lenght  Define a quantidade de casas e colocará zeros a esquerda para completar o tamanho default é zero(não faz nada)
     * @return string          String limpa conforme os parametros
     */
    public function clean($str, $opt= TRUE, $length = 0){
        // redireciona parametro se for integer em $opt para $lenght
        if(is_int($opt)){
            $length = $opt;
            $opt = TRUE;
        }
        // se Não for uma string retorna sem tratamento
        if(!is_string($str)){
            return $str;
        }
        $reg = ($opt) ? "/[^0-9]/" : "/[^0-9a-zA-Z]/";
        $cleaned = preg_replace($reg, "", $str);
        if($length > 0){
            if(strlen($cleaned) > $length){
                $cleaned = substr($cleaned, 0, $length);
            }
            // colocar zeros a esquerda
            $cleaned = str_pad($cleaned, $length, "0", STR_PAD_LEFT);  
        }
        return $cleaned;
    }
}
