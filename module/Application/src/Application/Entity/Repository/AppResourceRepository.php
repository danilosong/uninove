<?php

/*
 * To change this license
 */

namespace Application\Entity\Repository;

/**
 * Description of AppResourceRepository
 *
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
class AppResourceRepository extends AbstractRepository {

    public function fetchPairs($methd = 'getName', $filters = array(), $first = false, $orderBy = NULL) {
        return parent::fetchPairs($methd, $filters, $first, $orderBy);
    }

}
