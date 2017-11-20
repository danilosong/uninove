<?php

namespace Application\Entity\Repository;

/**
 * Description of ParametrosRepository
 *
 * @author Paulo Watakabe <email>watakabe05@gmail.com
 */
class ParametrosRepository extends AbstractRepository {

    /**
     * Busca no BD os registro com a chave do paramentro.
     * Metodo feito em DQL do Doctrine para ter mais desempenho na busca ao banco     * 
     * @author Paulo Watakabe <email>watakabe05@gmail.com
     * 
     * @param type $key
     * @return type
     */
    public function findKey($key, $status = "inativo") {
        $query = $this->getEntityManager()
                ->createQueryBuilder()
                ->select('p.conteudo, p.descricao')
                ->from('Application\Entity\Parametros', 'p')
                ->where(" p.chave LIKE :chave")
                ->andWhere("p.status NOT LIKE :status")
                ->andWhere("p.status NOT LIKE :word")
                ->setParameter('chave', $key)
                ->setParameter('status', $status)
                ->setParameter('word', "Cancelado")
                ->getQuery()
        ;
        return $query->getResult();
    }

    /**
     * Metodo que monta o array de pares que normalmente é usado em select     * 
     * @author Paulo Watakabe <email>watakabe05@gmail.com
     * 
     * @param type $methd
     * @param type $first
     * @return string
     */
    public function fetchPairs($methd = 'getNome', $filters = [], $first = true, $orderBy = NULL) {
        if (is_bool($filters)) {
            $first = $filters;
            $filters = [];
        }
        $dados = $this->findKey($methd);


        $array = [];
        if ($first) {
            $array[''] = 'Selecione na lista';
        }

        if (!is_array($dados)) {
            return $array;
        }
        /* @var $entity \Application\Entity\Parametros */
        foreach ($dados as $dado) {
            $array[$dado['conteudo']] = $dado['descricao'];
        }

        return $array;
    }
    
    
    /**
     * Monta a dql com filtros previavemente tratados no service para pesquisar no banco 
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 21-07-2016 
     * @param array $filters
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function pesquisa($filters) {        
        /* @var $query \Doctrine\ORM\QueryBuilder */
        $query = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('e')
            ->from($this->_entityName, 'e')
        ;
        if(!isset($filters['chave']) OR empty($filters['chave'])){
            $query->groupBy('e.chave');
        }        
        $this->setBasicaFilters($query, $filters);
        return $query;
    }

    
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 21-07-2016 
     * @param type $query
     * @param type $filters
     */    
    public function setBasicaFilters($query, &$filters) {
        foreach ($filters as $key => $value) {
            switch ($key) {
                case 'chave':
                    $query->andWhere('e.chave LIKE :' . $key)->setParameter($key, $value);
                    break;
                default:
                    $query->andWhere('e.' . $key . ' = :' . $key)->setParameter($key, $value);
            }
        }
    }
}
