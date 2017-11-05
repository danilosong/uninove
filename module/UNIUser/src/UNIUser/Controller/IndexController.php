<?php

namespace UNIUser\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel;

/**
 * Description of IndexController
 *
 * @author Danilo Song <danilosong@outlook.com>
 */
class IndexController extends AbstractActionController{

    public function registerAction() {

        return new ViewModel();
    }
}
