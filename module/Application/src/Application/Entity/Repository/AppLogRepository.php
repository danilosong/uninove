<?php

/*
 * License
 */

namespace Application\Entity\Repository;

/**
 * Description of AppLogRepository
 * @author Paulo Watakabe <watakabe05@gmail.com>
 */
class AppLogRepository extends AbstractRepository {
    
    public function __construct($em,$class) {
        parent::__construct($em, $class);
    }
}
