<?php

/*
 * To change this license
 */

namespace Application\Entity\Repository;
/**
 * Description of Teste
 *
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
class TesteRepository extends AbstractRepository {

    public function fetchPairs($methd = 'getNome', $filters = [], $first = true, $orderBy = NULL) {
        /* @var $entity \Application\Entity\Teste */
        $entities = $this->findAll();
        $array = [];
        foreach ($entities as $entity) {
            $array[$entity->getId()] = $entity->getCampo1();
        }
        return $array;
    }
}
