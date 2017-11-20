<?php

/**
 * Classe sem uso
 * 
 * Foi trocada pelo menu do BD
 */


namespace Application\Navigation\Service;

use Zend\Navigation\Service\AbstractNavigationFactory;

class AdminNavigationFactory extends AbstractNavigationFactory{
    protected function getName() {
        return 'admin';
    }
}