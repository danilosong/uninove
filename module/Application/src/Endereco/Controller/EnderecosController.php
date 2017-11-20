<?php

/*
 * To change this license 
 */

namespace Endereco\Controller;

/**
 * Description of EnderecosController
 *
 * @author Paulo Watakabe <watakabe05@gmail.com>
 */
class EnderecosController extends \Application\Controller\CrudController {

    public function __construct() {
        parent::__construct('endereco','Endereco');
        $this->route = 'end/default'; 
        $this->routeAjax = "end/ajax"; 
    }
    
    /**
     * Apenas para testar os niveis de recursividade do to array();
     * @author Paulo Watakabe <watakabe05@gmail.com>
     */
//    public function indexAction() {
//        $end = $this->getEm()->find($this->entity, 1);
//        if ($end){
//            $data = $end->toArray(0);
//            echo '<pre>', var_dump($data), '</pre>';
//        }else{
//            echo 'não encontrado';
//        }
//        die;
//    }

}
