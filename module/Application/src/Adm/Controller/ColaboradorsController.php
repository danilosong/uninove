<?php

/*
 * License GPL .
 * 
 */

namespace Adm\Controller;

/**
 * Description of colaboradorsController
 *
 * @author Danilo Song <danilosong@outlook.com>
 * @since 25-05-2017
 */
class ColaboradorsController extends AdmCrudController {

    

    /**
     * Metodo opcional para alterar a entidade que sera usada no form na action edit podendo alterar os seguintes parametros
     *     dataview para alterar parametros para phtml
     *     form     para alterar o formulario a ser renderizado
     *     request  para alterar e verificar fluxo do action
     *     resul    para alterar parametros para phtml
     *     entity   Entidade em que se esta sendo trabalhada
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 26-05-2017
     * @param array  $dataView
     * @param object $form
     * @param object $request
     * @param string $resul
     * @param object $entity
     */
    public function changeEntityForEdit(&$dataView, $form, $request, &$resul, $entity) {
        $input = 'usuarioNome';
        if($request->isGet() AND $entity AND $form->has($input)){
            $form->get($input)->setValue($entity->getUsuario('nome'));
        }
    }
    
    /**
     * Cria regra de visualizacao a partir do status da ocorrencia. Por default,
     * ocorrencia inativa não é exibida.
     * @todo Ainda não foi implementado a opcao 'exibir inativos' na view. Caso
     * esta opcao seja implementada, deve ser passado o parametro 
     * 'showInativos: TRUE'.
     * @author Danilo Song <danilosong@outlook.com>
     * @since 05-06-2017
     * @param type $filtro
     * @param array $orderBy
     * @param \Doctrine\ORM\QueryBuilder $list
     * @return type
     */
    public function indexAction($filtro = array(), array $orderBy = array(), \Doctrine\ORM\QueryBuilder $list = null) {

        //faz pesquisa na index
        $request = $this->getRequest();
        $data = [];
        if($request->isPost()){
            $data = $request->getPost()->toArray();
        }
        if(!empty($data)){
            $list = $this->getService()->pesquisa($data);
        }
        return parent::indexAction($filtro, $orderBy, $list);
    }
}