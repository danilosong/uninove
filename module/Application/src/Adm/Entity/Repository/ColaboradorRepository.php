<?php

namespace Adm\Entity\Repository;

/**
 * Description of ColaboradorRepository
 *
 * @author Danilo Song <danilosong@outlook.com>
 * @since 25/05/2017
 */
class ColaboradorRepository extends AdmAbstractRepository{
    
    /**
     * Auto complete em ajax esta função retorna as entitys encontradas
     * com a ocorrencia passada por parametro
     * @param string $user
     * @return array
     */
    public function autoComp($column = 'nome', $data = '', $anotherFilters = array(), $orderBy = array(), $qtd = 40) {
        $query = $this->pesquisa(array('us.nomeUsuario' => '%'.$data.'%'));
        
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
     * Monta a dql com filtros previavemente tratados no service para pesquisar no banco 
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 26-12-2016 
     * @param array $filters
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function pesquisa($filters) {
        $qb = parent::pesquisa($filters);
        $qb->join('e.usuario', 'us');
        return $qb;
    }
}
