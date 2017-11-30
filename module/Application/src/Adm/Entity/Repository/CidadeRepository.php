<?php

namespace Adm\Entity\Repository;

/**
 * Description of CidadeRepository
 * @author Danilo Dorotheu <danilo.dorotheu@live.com>
 */
class CidadeRepository extends \Application\Entity\Repository\AbstractRepository {
    
    
    public function findOneByCidadeEstado($cidade, $estado) {
        /* @var $qb  \Doctrine\ORM\QueryBuilder */
        $qb = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('e')
            ->from($this->_entityName, 'e')
            ->join('e.estado', 'uf')    
            ->where("e.nomeCidade  LIKE :cidade")
            ->andWhere("uf.nomeEstado  LIKE :estado")
            ->setParameters(compact("cidade", "estado"))
        ;
        $r = $qb->setMaxResults(1)->getQuery()->getResult();
        if(!empty($r)){
            return $r[0];
        }
    }
    
}
