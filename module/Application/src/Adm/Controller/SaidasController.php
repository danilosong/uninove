<?php

/*
 * License GPL .
 * 
 */

namespace Adm\Controller;

/**
 * Description of SaidasController
 *
 * @author Danilo Song <danilosong@outlook.com>
 * @since 14-06-2017
 */
class SaidasController extends AdmCrudController {
    
    /**
     * Metodo para ser sobreescrito em casos em que new action precisa de um entidade configurada para o form
     * Os parametro sao os ponteiro logo a alteração deles ira alterar tb do metodo newAction
     * 
     * @author Danilo Song <danilsong@outlook.com>
     * @version 1.0
     * @since 30-06-2017
     * @param array  $dataView  Array com informações basica como titulos etc.
     * @param \Adm\Form\Saida $form      
     * @param object $request
     * @param string $resul
     * @return \Adm\Entity\Saida
     */
    public function getEntityForNew(&$dataView, &$form, &$request, &$resul) {
        $input = 'usuarioNome';
        if($request->isGet() AND $form->has($input)){
            /* @var $usuario \Adm\Entity\Saida */
            $usuario = $this->getUser();
            if($usuario){
               $form->get($input)->setValue($usuario['nomeUsuario']);
               $form->get('usuario')->setValue($usuario['id']);
               $form->setReadOnly();
            }
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
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0
     * @since 30-06-2017
     * @param array  $dataView
     * @param \Adm\Form\Saida $form
     * @param object $request
     * @param string $resul
     * @param \Adm\Entity\Saida $entity
     */
    public function changeEntityForEdit(&$dataView, $form, $request, &$resul, $entity) {
        if($entity){
            if($form->has('produtoNome')){
                $form->get('produtoNome')->setValue($entity->getProduto('nomeProd'));                        
            }
            if($form->has('usuarioNome')){
                $form->get('usuarioNome')->setValue($entity->getUsuario('nome'));                        
            }
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
    
    /**
     * Cria regra de visualizacao a partir do status da ocorrencia. Por default,
     * ocorrencia inativa não é exibida.
     * @todo Ainda não foi implementado a opcao 'exibir inativos' na view. Caso
     * esta opcao seja implementada, deve ser passado o parametro 
     * 'showInativos: TRUE'.
     * @author Danilo Song <danilosong@outlook.com>
     * @since 03-07-2017
     * @param type $filtro
     * @param array $orderBy
     * @param \Doctrine\ORM\QueryBuilder $list
     * @return type
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
     * Redireciona e cria uma pagina em pdf
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0
     * @since 29-06-2017
     * @return \Zend\View\Model\ViewModel
     */    
    public function showPdfAction() {
        $dataView = $this->getDataView();
        $request = $this->getRequest();
        $data = [];
        if($request->isPost()){
            $data = $request->getPost()->toArray();
        }
        $this->sessao('saida', $data);
        return $this->makeView(compact("dataView"));
    }

    /**
     * Gera o conteudo da pagina de pdf a partir do relatorio gerado
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0
     * @since 29-06-2017
     * @param \Adm\Entity\Saida $saida
     */
    public function getPdfAction() {
        $dataView   = $this->getDataView();
        $data       = $this->sessao('saida');
        $ini        = $this->getService()->strToDate($data['filtro1']);
        $fim        = $this->getService()->strToDate($data['filtro2']);
        $qb         = $this->getService()->pesquisa($data);
        $resul      = $qb->getQuery()->getResult();        
        $param      = $this->getParam();
        $user       = $this->getUser();
        $image      = $this->getImage();
        $pdf        = $this->getPdfModel();
        $pdf->setPagLandescape();
        $pdf->setVars(compact("param","image","resul","data","dataView", "ini", "fim", "user"));
        $pdf->getMpdf()->SetMargins(5,5,5);
        $pdf->printPdf('', 'I');
        die;
    }
    
    /**
     * Metodo para redireciona o fluxo do sistema apos um insert ou update 
     * Por padrao apos um insert ou update com sucesso vai ser redirecionado para o index do controller em execução
     * 
     * @author Danilo Song  <danilosong@outlook.com>
     * @version 1.0
     * @since 13-07-2017
     * @param string $action Nome da action que esta solicitando os parametros de redirect
     * @param object $resul Contem o resultado da operação uma entidade 
     * @return array Obrigatorio retornar um array vazio ou com os dados de redirecionamento
     */
    public function getRedirect($action, $resul) {
        $user = $this->getUser('role');
        if($user != 'admin'){
                return ['action' => 'new'];
        }
        return [];
    }
}