<?php

/*
 * License GPL .
 * 
 */

namespace Application\Controller;

/**
 * Description of ParametrosController
 * Tabela para cadastrar 
 *    Parametro simples para o funcionanto sistema 
 *    Opções para serem incluidas dentro de selects, radios, multicheckbox sem a necessidade de uma tabela para cada um
 *    
 * @author Paulo Watakabe <watakabe05@gmail.com>
 * @version 1.0  
 * @since 21-07-2016  
 */
class ParametrosController extends CrudController
{
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0 
     * @since 27-04-2016 
     */
    public function __construct($name = 'parametros') {
        parent::__construct($name);
        $this->controller = $this->name ;
        $this->setFormWithEntityManager(TRUE);
    }
    
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0 
     * @since 21-07-2016
     * @param array $filtro    Com parametros da entidade para serem filtrados
     * @param array $orderBy   Com parametros da entidade para serem ordenados
     * @param array $list      É uma querybuilder personalizada que vai direto para paginação
     * @return \Zend\View\Model\ViewModel | no return
     */
    public function indexAction($filtro = array(), array $orderBy = array(), \Doctrine\ORM\QueryBuilder $list = null) {
        /* @var $srv \Application\Service\Parametros */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
        }else{            
            $data['id'] = $this->params()->fromRoute('id', '');
        }
        $srv = $this->getService();
        $list = $srv->pesquisa($data);
        return parent::indexAction($filtro, $orderBy, $list);
    }
    
    /**
     * Metodo para ser sobreescrito em casos em que new action precisa de um entidade configurada para o form
     * Os parametro sao os ponteiro logo a alteração deles ira alterar tb do metodo newAction
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 22-07-2016
     * @param array  $dataView  Array com informações basica como titulos etc.
     * @param objec-t $form      
     * @param object $request
     * @param string $resul
     * @return type
     */
    public function getEntityForNew(&$dataView, &$form, &$request, &$resul) {
        if ($request->isPost()) {
            return null;
        }
        $id = $this->params()->fromRoute('id', '');
        if (empty($id)){
            return null;
        }
        /* @var $entity \Application\Entity\Parametros */
        /* @var $form   \Application\Form\Parametros */
        $entity = $this->getEm()->find($this->entity, $id);
        if($entity AND $form->has('chave')){
            $form->get('chave')->setValue($entity->getChave());
            $form->get('idParame')->setValue($entity->getId());
        }
        return null;
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
        /* @var $resul \Application\Entity\Parametros  */
        if($action == 'delete'){
            return ['action' => 'new', 'id' => $resul];        
        }
        return ['action' => 'new', 'id' => $resul->getId()];        
    }
}

