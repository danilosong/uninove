<?php

/*
 * To change this license
 */

namespace Application\Entity\Repository;

/**
 * Description of AppPrivilegeRepository
 *
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
class AppPrivilegeRepository extends AbstractRepository {

    public function fetchPairs($methd = 'getNome', $filters = array(), $first = false, $orderBy = NULL) {
        return parent::fetchPairs($methd, $filters, $first, $orderBy);
    }

}
