<?php

/*
 * To change this license 
 */

namespace Application\Controller;

/**
 * Description of ChamadoRespostas
 *
 * @author Danilo Song <danilosong@outlook.com>
 * @date   25-09-2017
 */
class ChamadoRespostasController extends CrudController {
    
    /**
     * Cria regra de visualizacao a partir do status da ocorrencia. Por default,
     * ocorrencia inativa não é exibida.
     * @todo Ainda não foi implementado a opcao 'exibir inativos' na view. Caso
     * esta opcao seja implementada, deve ser passado o parametro 
     * 'showInativos: TRUE'.
     * @author Danilo Song <danilosong@outlook.com>
     * @since 25-09-2017
     * @param type $filtro
     * @param array $orderBy
     * @param \Doctrine\ORM\QueryBuilder $list
     * @return type
     */
    public function indexAction($filtro = array(), array $orderBy = array(), \Doctrine\ORM\QueryBuilder $list = null) {
        $request  = $this->getRequest();
        $data     = [];
        $this->defaultOrderBy = ['createdAt' => 'DESC'];
        if($request->isPost()){
            $data = $request->getPost()->toArray();
            $list = $this->getService()->pesquisa($data);
        }
        return parent::indexAction($filtro, $orderBy, $list);
    }
    /**
     * Cria regra de visualizacao a partir da indexAction,
     * exibe a tabela do chamadoResposta no formulário de chamadoResposta
     * @author Danilo Song <danilosong@outlook.com>
     * @since 18-10-2017
     */
    public function tabelaRespostaAction() {
        return $this->indexAction();
    }
    
    /**
     * Metodo para ser sobreescrito em casos em que new action precisa de um entidade configurada para o form
     * Os parametro sao os ponteiro logo a alteração deles ira alterar tb do metodo newAction
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0
     * @since 10-10-2017
     * @param array  $dataView  Array com informações basica como titulos etc.
     * @param \Application\Form\ChamadoResposta $form      
     * @param object $request
     * @param string $resul
     * @param string $entity
     * @return \Application\Entity\ChamadoResposta | NULL
     */
    public function getEntityForNew(&$dataView, &$form, &$request, &$resul) {
            $dataView = $this->dataView;
            $request  = $this->request;
            $id       = $this->params()->fromRoute('id', 0);
            if(!empty($id)){
                $this->sessao('chamado', $id);
                $resul = $this->getEm()->find('\Application\Entity\Chamado', $id);
            }
            if($form->has('chamado')){
                $form->get('chamado')->setValue($id);
            }
            return null;
    }
    
    /**
     * Metodo para redireciona o fluxo do sistema apos um insert ou update 
     * Por padrao apos um insert ou update com sucesso vai ser redirecionado para o index do controller em execução
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0
     * @since 18-10-2017
     * @param string $action Nome da action que esta solicitando os parametros de redirect
     * @param object $resul  Contem o resultado da operação uma entidade 
     * @return array Obrigatorio retornar um array vazio ou com os dados de redirecionamento
     */
    public function getRedirect($action, $resul) {
        switch ($action) {
            case 'new':
                $this->sessao('chamado', $resul->getId());
                return array('controller' => 'chamados', 'action' => 'index');

            default:
                return parent::getRedirect($action, $resul);
        }
    }    
}

