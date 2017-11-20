<?php
/*
 * License GPL .
 * 
 */

namespace Application\Controller;

/**
 * Controler para resgatar imagens protegidas.
 * 
 * @author Paulo Watakabe <watakabe05@gmail.com>
 * @since 05-05-2016
 */
class ImagesController extends CrudController {

    public function __construct() {
        parent::__construct('parametro');
    }
    
    public function getAction() {
        return $this->makeView([], TRUE);
    }

}

