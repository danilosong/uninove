<?php

/*
 * To change this license
 */

namespace Application\Entity\Repository;
/**
 * Description of TableConfigPersonalRepository
 * Metodos basico para manipulação de registros
 *
 * @author Paulo Watakabe <watakabe05@gmail.com>
 * @since 10-07-2016   
 */
class TableConfigPersonalRepository extends AbstractRepository {

    /**
     * Busca a personalização da tabela para o usuario
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 12-07-2016  
     * @param int $tableConfig
     * @param int $user
     * @return array Collection of Application\Entity\TableConfigPersonal OR Empty
     */
    public function getTableConfigByUser($tableConfig, $user) {
        $query = $this->getBasicQuery($tableConfig);
        $query->andWhere('e.usuario = :usuario')
              ->setParameter('usuario', $user)
        ;
        return $query->getQuery()->getResult();
    }
    
    /**
     * Busca a personalização da tabela para o role (papel)
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 12-07-2016  
     * @param int $tableConfig
     * @param int $role
     * @return array Collection of Application\Entity\TableConfigPersonal OR Empty
     */
    public function getTableConfigByRole($tableConfig, $role) {
        $query = $this->getBasicQuery($tableConfig);
        $query->andWhere('e.role = :role')
              ->setParameter('role', $role)
        ;
        return $query->getQuery()->getResult();
    }
    
    /**
     * Query builder basico para buscar a personalização
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 12-07-2016  
     * @param type $tableConfig
     * @return type
     */
    public function getBasicQuery($tableConfig) {
        return $this->getEntityManager()
                ->createQueryBuilder()
                ->select('e')
                ->from($this->_entityName, 'e')
                ->where('e.tableConfig = :tableConfig')
                ->orderBy('e.seq', 'ASC')
                ->setParameter('tableConfig', $tableConfig);
    }
    
}
