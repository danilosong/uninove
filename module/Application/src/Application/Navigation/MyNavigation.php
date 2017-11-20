<?php

/*
 * To change this license 
 */

namespace Application\Navigation;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Navigation\Service\DefaultNavigationFactory;

/**
 * Description of MyNavigation
 * Recuperar menu que esta cadastrado no BD
 *
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
 
class MyNavigation extends DefaultNavigationFactory{
         
    
    protected function getPages(ServiceLocatorInterface $serviceLocator){
        if (null === $this->pages) {
            /* @var $em \Doctrine\ORM\EntityManager */
            /* @var $rp \Application\Entity\Repository\appMenuRepository */
            $em = $serviceLocator->get('Doctrine\ORM\EntityManager');            
            $acl = $serviceLocator->get('Application\Permissions\Acl');
            $us = $serviceLocator->get('UserIdentity');
            $rp = $em->getRepository('Application\Entity\AppMenu'); 
            $configuration = $rp->getNavigationArray($this->getName(), $acl, $us());
            if (!isset($configuration['navigation'])) {
                throw new Exception\InvalidArgumentException('Could not find navigation configuration key');
            }
            if (!isset($configuration['navigation'][$this->getName()])) {
                throw new Exception\InvalidArgumentException(sprintf(
                    'Failed to find a navigation container by the name "%s"',
                    $this->getName()
                ));
            }
 
            $application = $serviceLocator->get('Application');
            $routeMatch  = $application->getMvcEvent()->getRouteMatch();
            $router      = $application->getMvcEvent()->getRouter();
            $pages       = $this->getPagesFromConfig($configuration['navigation'][$this->getName()]);
 
            $this->pages = $this->injectComponents($pages, $routeMatch, $router);
        }
        return $this->pages;
    }   
    
}
