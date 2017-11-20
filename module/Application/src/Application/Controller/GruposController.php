<?php

/*
 * License
 */

namespace Application\Controller;

/**
 * Description of GrupoRepository
 *
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */

class GruposController extends CrudController
{

    public function __construct() {
        parent::__construct('grupo');
        $this->setFormWithEntityManager(TRUE);
    }

}

