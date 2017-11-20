<?php

/*
 * To change this license 
 */

namespace Application\Navigation;

/**
 * Description of MyNavigationFactory
 *
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
 
class MyNavigationFactory implements FactoryInterface{
    
    public function createService(ServiceLocatorInterface $serviceLocator)    {
        $navigation =  new MyNavigation();
        return $navigation->createService($serviceLocator);
    }
}
