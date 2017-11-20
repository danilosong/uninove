<?php

/*
 * To change this license
 */

namespace Application\Entity\Repository;

/**
 * Description of AppRoleRepository
 *
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
class AppRoleRepository extends AbstractRepository {

    public function fetchParent() {
        /* @var $entity \Application\Entity\AppRole */
        $entities = $this->findAll();
        $array = [];
        foreach ($entities as $entity) {
            $array[$entity->getId()] = $entity->getNome();
        }
        return $array;
    }
}
