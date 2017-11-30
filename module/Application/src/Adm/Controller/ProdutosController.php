<?php

/*
 * License GPL .
 * 
 */

namespace Adm\Controller;

/**
 * Description of ProdutosController
 *
 * @author Danilo Song <danilosong@outlook.com>
 * @since 14-06-2017
 */
class ProdutosController extends AdmCrudController {
    
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
        $data = [];
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
        $this->sessao('produto', $data);
        return $this->makeView(compact("dataView"));
    }

    /**
     * Gera o conteudo da pagina de pdf a partir do relatorio gerado
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0
     * @since 06-07-2017
     * @var $saida \Adm\Entity\Saida
     */
    public function getPdfAction() {
        
        $dataView   = $this->getDataView();
        $data       = $this->sessao('produto');
        $qb         = $this->getService()->pesquisa($data);
        $resul      = $qb->getQuery()->getResult();        
        $param      = $this->getParam();
        $user       = $this->getUser();
        $image      = $this->getImage();
        $pdf        = $this->getPdfModel();
        $pdf->setVars(compact("param","image","resul","data","dataView", "user"));
        $pdf->getMpdf()->SetMargins(5,5,5);
        $pdf->printPdf('', 'I');
        die;
    }
}