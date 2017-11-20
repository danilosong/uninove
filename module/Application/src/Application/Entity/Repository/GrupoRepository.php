<?php

/*
 * License
 */

namespace Application\Entity\Repository;

/**
 * Description of GrupoRepository
 *
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
class GrupoRepository extends AbstractRepository {
    //put your code here
    
    public function __construct($em,$class) {
        parent::__construct($em, $class);
    }
}
