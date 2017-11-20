<?php

/*
 * To change this license 
 */

namespace Endereco\Controller;

/**
 * Description of EnderecosController
 *
 * @author Paulo Watakabe
 */
class CompletesController extends \Application\Controller\CrudController {
   
    
    
    public function __construct() {
        parent::__construct('complete','Endereco');
        $this->route = 'end/default'; 
        $this->routeAjax = "end/ajax"; 
    }
    
    /**
     * Busca o endereço baseado no cep passado
     * @return \Zend\View\Model\ViewModel
     */
    public function buscaCepAction(){
        /* @var $srv  \Endereco\Service\Complete  */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $cep = $this->getRequest()->getPost('cep');  
        }else{
            $cep =  $this->params()->fromQuery('cep','');
        }
        $srv = $this->getService();
        $resultado = $srv->getCep($cep);     
        return $this->makeView(compact("resultado", "cep"));
    }
    
    public function buscaRuaAction() {    
        /* @var $srv  \Endereco\Service\Complete  */
        $request = $this->getRequest();    
        if ($request->isPost()) {
            $logradouro = $this->getRequest()->getPost('logradouro');  
            $tipoLogradouro = $this->getRequest()->getPost('tipoLogradouro');  
        }else{
            $logradouro =  $this->params()->fromQuery('logradouro','');
            $tipoLogradouro =  $this->params()->fromQuery('tipoLogradouro','');
        }
        $srv = $this->getService();
        $data = $srv->getRua($tipoLogradouro, $logradouro);     
        return $this->makeView(compact("data", "tipoLogradouro", "logradouro"));
    }

}
