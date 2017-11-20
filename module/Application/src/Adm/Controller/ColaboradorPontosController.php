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
class ColaboradorPontosController extends AdmCrudController {
    
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
     * @param \Adm\Entity\ColaboradorPonto $entity
     */
    public function changeEntityForEdit(&$dataView, $form, $request, &$resul, $entity) {
        $input = 'colaboradorNome';
        if($request->isGet() AND $entity AND $form->has($input)){
            $form->get($input)->setValue($entity->getColaborador('usuario', ['nome']));
        }
    }
    
    /**
     * Metodo para ser sobreescrito em casos em que new action precisa de um entidade configurada para o form
     * Os parametro sao os ponteiro logo a alteração deles ira alterar tb do metodo newAction
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 30-05-2017
     * @param array  $dataView  Array com informações basica como titulos etc.
     * @param object $form      
     * @param object $request
     * @param string $resul
     * @return type
     */
    public function getEntityForNew(&$dataView, &$form, &$request, &$resul) {
        $input = 'colaboradorNome';
        if($request->isGet() AND $form->has($input)){
            /* @var $colaborador \Adm\Entity\Colaborador */
            $colaborador = $this->getEm()->getRepository('\Adm\Entity\Colaborador')->findOneByUsuario($this->getUser('id'));
            if($colaborador){
               $form->get($input)->setValue($colaborador->getUsuario('nome'));
               $form->get('colaborador')->setValue($colaborador->getId());
               $form->setReadOnly();
               $form->setEntrada($colaborador);
            }
        }
    }

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
        /* @var $colaborador \Adm\Entity\Colaborador */
        $colaborador = $this->getEm()->getRepository('\Adm\Entity\Colaborador')->findOneByUsuario($this->getUser('id'));
        $request = $this->getRequest();
            $data = ['data' => date('d/m/Y')];
            if($request->isPost()){
                $data = $request->getPost()->toArray();
            }
                if($colaborador OR !empty($data)){
                    $list = $this->getService()->pesquisa($data, $colaborador);
                }
        return parent::indexAction($filtro, $orderBy, $list);
        
    }
    
    /**
     * Gera um relatório da tela colaboradorPonto
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0
     * @param array $filtro    Com parametros da entidade para serem filtrados
     * @param array $orderBy   Com parametros da entidade para serem ordenados
     * @param array $list      É uma querybuilder personalizada que vai direto para paginação
     * @return \Zend\View\Model\ViewModel | no return
     */    
    public function relatorioAction() {
        $request = $this->getRequest();
        $dataView = $this->getDataView();
        $form     = $this->getForm();
        $resul = [];
        $data  = [];
        if($request->isPost()){
            $data = $request->getPost()->toArray();
            $qb = $this->getService()->pesquisa($data);
            $resul = $qb->getQuery()->getResult();
            $ini = $this->getService()->strToDate($data['filtro1']);
            $fim = $this->getService()->strToDate($data['filtro2']);
            $this->sessao('dataPonto', $data);
            // compact("ini","fim") = array('ini' => $ini, 'fim' => $fim)
        }
        return $this->makeView(compact("dataView", "resul", "form", "ini", "fim"));
    }
    
    
    public function showPdfAction() {
        $dataView = $this->getDataView();
        return $this->makeView(compact("dataView"));
    }
    
    public function getPdfAction() {
        $dataView = $this->getDataView();
        $form     = $this->getForm();
        $data     = $this->sessao('dataPonto');
        $ini      = $this->getService()->strToDate($data['filtro1']);
        $fim      = $this->getService()->strToDate($data['filtro2']);
        $qb       = $this->getService()->pesquisa($data);
        $resul    = $qb->getQuery()->getResult();
        $user     = $this->getUser();
        $param    = $this->getParam();
        $image    = $this->getImage();
        $pdf      = $this->getPdfModel();
        $pdf->setVars(compact("param","image","resul","data","dataView","form", "ini", "fim", "user"));
        $pdf->getMpdf()->SetMargins(15,15, 15);
        $pdf->printPdf('', 'I');
        die;
    }
}
