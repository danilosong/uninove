<?php

/*
 * License GPL .
 * 
 */

namespace Adm\Controller;

/**
 * Description of PedidoItemsController
 *
 * @author Danilo Song <danilosong@outlook.com>
 * @since 14-06-2017
 */
class PedidoItemsController extends AdmCrudController {
    
    /**
     * Faz listagem dos dados baseado nos parametros passados
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param array $filtro    Com parametros da entidade para serem filtrados
     * @param array $orderBy   Com parametros da entidade para serem ordenados
     * @param array $list      É uma querybuilder personalizada que vai direto para paginação
     * @return \Zend\View\Model\ViewModel | no return
     */
    public function indexAction($filtro = array(), array $orderBy = array(), \Doctrine\ORM\QueryBuilder $list = null) {
        $filtro['pedido'] = $this->sessao('pedido');
        $filtro['status'] = 'ATIVO';
        return parent::indexAction($filtro, $orderBy, $list);
    }
    
    /**
     * Metodo para ser sobreescrito em casos em que new action precisa de um entidade configurada para o form
     * Os parametro sao os ponteiro logo a alteração deles ira alterar tb do metodo newAction
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 31-01-2016
     * @param array  $dataView  Array com informações basica como titulos etc.
     * @param \Adm\Form\PedidoItem $form      
     * @param object $request
     * @param string $resul
     * @return \Adm\Entity\PedidoItem | NULL
     */
    public function getEntityForNew(&$dataView, &$form, &$request, &$resul) {
        $pedidoId = $this->sessao('pedido');
        if(!empty($pedidoId)){
            $resul = $this->getEm()->find('\Adm\Entity\Pedido', $pedidoId);
        }
        if($form->has('pedido')){
            $form->get('pedido')->setValue($pedidoId);
        }
        return null;
    }
    

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
     * @since 01-03-2016
     * @param array  $dataView
     * @param  \Adm\Entity\Pedido $form
     * @param object $request
     * @param string $resul
     * @param  \Adm\Entity\PedidoItem  $entity
     */
    public function changeEntityForEdit(&$dataView, $form, $request, &$resul, $entity) {
        $pedidoId = $this->sessao('pedido');
        if(!empty($pedidoId)){
            $resul = $this->getEm()->find('\Adm\Entity\Pedido', $pedidoId);
        }
        if($form->has('produtoNome') AND $entity){
            $form->get('produtoNome')->setValue($entity->getProduto('nomeProd'));
        }
    }
    
    /**
     * Metodo para redireciona o fluxo do sistema apos um insert ou update 
     * Por padrao apos um insert ou update com sucesso vai ser redirecionado para o index do controller em execução
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 29-01-2016
     * @param string $action Nome da action que esta solicitando os parametros de redirect
     * @param object $resul  Contem o resultado da operação uma entidade 
     * @return array Obrigatorio retornar um array vazio ou com os dados de redirecionamento
     */
    public function getRedirect($action, $resul) {
        if($action == 'new'){
            return ['action' => 'new'];
        }
        if($action == 'delete'){
            return ['action' => 'new'];
        }
        if($action == 'edit'){
            return ['action' => 'new'];
        }
    }
    
    /**
     * Recebe os dados do post e Save o registro resposta.
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 17-08-2016
     * @return string with json
     */
    public function saveAction() {
        $request = $this->getRequest();
        $post = $this->getDataWeb($request);
        $post['updatedBy'] = $this->getUser('id');
        $result = $this->getService()->update($post);
        if(is_object($result)){
            $post['result'] = 'ok';
        }else{
            $post['result'] = 'ng';
        }
        $post['messenger']    = $this->flashMessenger()->getCurrentAllMessages();
        $this->flashMessenger()->clearCurrentAllMessages();
        echo json_encode($post);
        die;
    }
}