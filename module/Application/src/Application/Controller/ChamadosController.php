<?php

/*
 * To change this license 
 */

namespace Application\Controller;

/**
 * Description of Chamados
 *
 * @author Danilo Song <danilosong@outlook.com>
 * @date   12-09-2017
 */
class ChamadosController extends CrudController {
    
    /**
     * Cria regra de visualizacao a partir do status da ocorrencia. Por default,
     * ocorrencia inativa não é exibida.
     * @todo Ainda não foi implementado a opcao 'exibir inativos' na view. Caso
     * esta opcao seja implementada, deve ser passado o parametro 
     * 'showInativos: TRUE'.
     * @author Danilo Song <danilosong@outlook.com>
     * @since 21-09-2017
     * @param type $filtro
     * @param array $orderBy
     * @param \Doctrine\ORM\QueryBuilder $list
     * @return type
     */
    public function indexAction($filtro = array(), array $orderBy = array(), \Doctrine\ORM\QueryBuilder $list = null) {
        $request = $this->getRequest();
        $data = [];
        $this->defaultOrderBy = ['createdAt' => 'DESC'];
        if($request->isPost()){
            $data = $request->getPost()->toArray();
            $list = $this->getService()->pesquisa($data);
        }
        return parent::indexAction($filtro, $orderBy, $list);
    }
}
