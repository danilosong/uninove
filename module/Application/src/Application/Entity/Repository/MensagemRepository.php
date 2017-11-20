<?php

/*
 * To change this license
 */

namespace Application\Entity\Repository;

/**
 * Description of MensagemRepository
 *
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
class MensagemRepository extends AbstractRepository {

    
    public function fetchPairs($methd = 'getNome', $filters = [], $first = true, $orderBy = NULL) {
        /* @var $entity \Application\Entity\Mensagem */
        $entities = $this->findAll();
        $array = [];
        foreach ($entities as $entity) {
            $array[$entity->getId()] = $entity->getTexto();
        }
        return $array;
    }
    
}
