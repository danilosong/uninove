<?php

/*
 * To change this license
 */

namespace Endereco\Entity\Repository;

/**
 * Description of CidadeRepository
 *
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
class CidadeRepository extends \Application\Entity\Repository\AbstractRepository {
    
    /**
     * 
     * @param string $cep
     * @return \Endereco\Entity\Cidade | NULL
     */
    function findByCepUnico($cep) {
        // Monta a dql para fazer consulta no BD Cuidado com relacionamentos tcm zeros para muitos (ex: Grupos)
        $qb = $this->getEntityManager()
            ->createQueryBuilder()
            ->from('Endereco\Entity\Cidade', 'c')
            ->select('c, u')
            ->join('c.ufCodigo', 'u')
            ->where('c.cidadeCep LIKE :cep')
            ->setParameter('cep', $cep)
        ;         
        return $qb->getQuery()->getOneOrNullResult();          
    }

}
