<?php

/*
 * To change this license 
 */

namespace Adm\Controller;

/**
 * Description of EnderecosController
 * @author Allan Davini
 */
class EnderecosController extends AdmCrudController {
    
    public function autoCompAction($column = '', $valor = '', $anotherFilters = []) {
        $data = $this->getRequest()->getPost()->toArray();
        return parent::autoCompAction($data['searchBy'], $data['data']);
    }

    
    public function separaAction() {
        /* @var $rp \Endereco\Entity\Repository\EnderecoRepository */
        $rp = $this->getEm()->getRepository('\Endereco\Entity\Endereco');
        $rp->separaAll();
        die;        
    }
}
