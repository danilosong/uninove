<?php

/*
 * License GPL .
 * 
 */

namespace Adm\Controller;

/**
 * Description of PedidosController
 *
 * @author Danilo Song <danilosong@outlook.com>
 * @since 14-06-2017
 */
class PedidosController extends AdmCrudController {
    
    /**
     * Metodo para ser sobreescrito em casos em que new action precisa de um entidade configurada para o form
     * Os parametro sao os ponteiro logo a alteração deles ira alterar tb do metodo newAction
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 30-05-2017
     * @param array  $dataView  Array com informações basica como titulos etc.
     * @param \Adm\Form\Pedido $form      
     * @param object $request
     * @param string $resul
     * @return \Adm\Entity\Pedido
     */
    public function getEntityForNew(&$dataView, &$form, &$request, &$resul) {
        $input = 'usuarioNome';
        if($request->isGet() AND $form->has($input)){
            /* @var $usuario \Adm\Entity\Pedido */
            $usuario = $this->getUser();
            if($usuario){
               $form->get($input)->setValue($usuario['nomeUsuario']);
               $form->get('usuario')->setValue($usuario['id']);
               $form->setReadOnly();
            }
        }
    }
    
    /**
     * Metodo para redireciona o fluxo do sistema apos um insert ou update 
     * Por padrao apos um insert ou update com sucesso vai ser redirecionado para o index do controller em execução
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0
     * @since 21-06-2017
     * @param string $action Nome da action que esta solicitando os parametros de redirect
     * @param object $resul  Contem o resultado da operação uma entidade 
     * @return array Obrigatorio retornar um array vazio ou com os dados de redirecionamento
     */
    public function getRedirect($action, $resul) {
        switch ($action) {
            case 'new':
            case 'edit':
                $this->sessao('pedido', $resul->getId());
                return array('controller' => 'pedidoItems', 'action' => 'new');

            default:
                return parent::getRedirect($action, $resul);
        }
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
     * @param \Adm\Form\Pedido $form
     * @param object $request
     * @param string $resul
     * @param \Adm\Entity\Pedido $entity
     */
    public function changeEntityForEdit(&$dataView, $form, $request, &$resul, $entity) {
        if($entity){
            if($form->has('fornecedorNome')){
                $form->get('fornecedorNome')->setValue($entity->getFornecedor('nomeFornec'));                        
            }
            if($form->has('usuarioNome')){
                $form->get('usuarioNome')->setValue($entity->getUsuario('nome'));                        
            }
        }
    }
    
    /**
     * Gera um relatório do Pedido de compra
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0
     * @since 29-06-2017
     * @param array $filtro    Com parametros da entidade para serem filtrados
     * @param array $orderBy   Com parametros da entidade para serem ordenados
     * @param array $list      É uma querybuilder personalizada que vai direto para paginação
     * @return \Zend\View\Model\ViewModel | no return
     */    
    public function relatorioAction() {
        $dataView = $this->getDataView();
        $form     = $this->getForm();
        $entity = null;
        $id = $this->params()->fromRoute('id', 0);
        if($id){
            $entity = $this->getEm()->find($this->entity, $id);
            $this->sessao('pedido', $id);
        }
        
        return $this->makeView(compact("dataView", "entity", "form"));
    }
    
    /**
     * Redireciona e cria uma pagina em pdf
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0
     * @since 29-06-2017
     * @return \Zend\View\Model\ViewModel
     */    
    public function showPdfAction() {
        $dataView = $this->getDataView();
        return $this->makeView(compact("dataView"));
    }

    /**
     * Gera o conteudo da pagina de pdf a partir do relatorio gerado
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0
     * @since 29-06-2017
     */
    public function getPdfAction() {
        $dataView = $this->getDataView();
        $param = $this->getParam();
        $id = $this->sessao('pedido');
        $entity = $this->getEm()->find($this->entity, $id);
        $this->sessao('pedido', $id);
        $image = $this->getImage();
        $pdf = $this->getPdfModel();
        $pdf->setVars(compact("image","dataView", "param", "entity"));
        $pdf->getMpdf()->SetMargins(15,15, 15);
        $pdf->printPdf('', 'I');
        die;
    }
    
    /**
     * Receber pedido
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0
     * @since 29-06-2017
     * @param array $filtro    Com parametros da entidade para serem filtrados
     * @param array $orderBy   Com parametros da entidade para serem ordenados
     * @param array $list      É uma querybuilder personalizada que vai direto para paginação
     * @return \Zend\View\Model\ViewModel | no return
     */  
    public function receberPedidoAction() {
        $dataView = $this->getDataView();
        $form     = $this->getForm();
        $entity = null;
        $id = $this->params()->fromRoute('id', 0);
        if($id){
            $entity = $this->getEm()->find($this->entity, $id);
            $this->sessao('pedido', $id);
        }
        return $this->makeView(compact("dataView", "entity", "form"));
    }
    
    /**
     * Faz listagem dos dados baseado nos parametros passados
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0
     * @param array $filtro    Com parametros da entidade para serem filtrados
     * @param array $orderBy   Com parametros da entidade para serem ordenados
     * @param array $list      É uma querybuilder personalizada que vai direto para paginação
     * @return \Zend\View\Model\ViewModel | no return
     */
    public function indexAction($filtro = array(), array $orderBy = array(), \Doctrine\ORM\QueryBuilder $list = null) {
        $request = $this->getRequest();
            $data = ['data' => date('d/m/Y')];
            if($request->isPost()){
                $data = $request->getPost()->toArray();
                $list = $this->getService()->pesquisa($data);
            }
            return parent::indexAction($filtro, $orderBy, $list);
    }
    
    /**
     * Recebe os dados do post e Save o registro resposta.
     * 
     * @author Danilo Song  <danilosong@outlook.com>
     * @version 1.0
     * @since 18-09-2017
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