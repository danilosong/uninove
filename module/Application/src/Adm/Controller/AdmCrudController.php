<?php

/*
 * License GPL .
 * 
 */

namespace Adm\Controller;

/**
 * Description of AdmCrudController
 *
 * Tem as actions basicas para o Crud no BD
 *      new
 *      update
 *      delete
 *      index    faz a listagem dos registro com paginação
 * 
 * Paramentros para:
 *      log fazer ou não.
 *      servico se instancia pelo service manager ou direto.
 *      form se instancia com entitymanager ou não 
 * 
 * @author Danilo Song <danilosong@outlook.com>
 * @since 24-05-2017
 */
abstract class AdmCrudController extends \Application\Controller\CrudController {

    public function __construct($name = '', $module = 'Adm') {
        // filtra o nome da classe retirando o namespace e a palavra no sController e colocar para minusculo. PS para funcionar deve estar no mesmo nameSpace 
        $name  = (!empty($name)) ? $name : lcfirst(str_replace('sController', '', str_replace(__NAMESPACE__ . '\\' , '', get_class($this))));
        parent::__construct($name, $module);
        $this->route = 'adm/default'; 
        $this->routeAjax = "adm/ajax"; 
        $this->setDependencia($name);
        $this->setLog(TRUE);
        $this->setFormWithEntityManager(TRUE);
    }
}
