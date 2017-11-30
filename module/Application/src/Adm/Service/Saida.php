<?php

namespace Adm\Service;

use Doctrine\ORM\EntityManager;

/**
 * Description of Saida
 *
 * @author Danilo Song <danilosong@outlook.com>
 * @since 14/06/2017
 */
class Saida extends AdmAbstractService{
    
    /**
     * Valor anterior da qtd do produto.
     * @var integer
     */
    protected $qtd = 0;

    public function __construct(EntityManager $em, $fatherService = FALSE) {
        parent::__construct($em, $fatherService);
        $this->basePath = 'Adm\Entity\\';

        $this->entity = $this->basePath . "Saida";

        $this->setDataRefArray([
            'ref_createdBy'   => '\Application\Entity\Usuario',
            'ref_updatedBy'   => '\Application\Entity\Usuario',
            'ref_usuario'     => '\Application\Entity\Usuario',
            'produto'         => '\Adm\Entity\Produto',
        ]);
    }
    
    /**
     * Função para algum tratamento previo do valores a serem inseridos no BD
     * Executada antes da validação dos dados
     *
     * @author Danilo Song  <danilosong@outlook.com>
     * @version 1.0
     * @param string $tipo Informa se esta tratando do insert ou update
     */
    public function trataData($tipo) {
        if($tipo == 'insert'){
            $this->data['usuarioNome'] = $this->name('usuarioNome');
        }
    }
    
    public function insert(array $data = array()) {
        $result = parent::insert($data);
        if(is_object($result)){
            $this->execBackJobs($result);
        }
        return $result;
    }
    
    public function update(array $data = array()) {
        $result = parent::update($data);
        if(is_object($result)){
            $this->execBackJobs($result);
        }
        return $result;
    }
    
    public function delete($id, $forceRemove = FALSE, $status = 'ATIVO') {
        $result = parent::delete($id, $forceRemove, $status);
        if($result){
            $this->execBackJobs($this->entityReal);
        }
        return $result;
    }
        
    /**
     * Pegar a qtd da saida antes de ser atualizada para recalcular qtd do estoque atual do produto
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param \Adm\Entity\Saida $ent
     */
    public function getDiff($ent) {
        $this->qtd = $ent->getQtd();
        parent::getDiff($ent);
    }
    
    /**
     * 
     * @param \Adm\Entity\Saida $saida
     */
    public function execBackJobs(\Adm\Entity\Saida $saida) {
        /* @var $srvProduto \Adm\Service\Produto */
        $srvProduto = $this->getService('\Adm\Service\Produto');
        if($saida->getQtd()){
            if($saida->getStatus() == 'ATIVO'){
                $srvProduto->subQtd($saida->getProduto(), $saida->getQtd() * $saida->getProduto()->getSaidaQtd());
            }else{
                $srvProduto->addQtd($saida->getProduto(), $saida->getQtd() * $saida->getProduto()->getSaidaQtd());
            }
        }
        if($this->qtd){
            $srvProduto->addQtd($saida->getProduto(), $this->qtd * $saida->getProduto()->getSaidaQtd());
        }
    }
    
    /**
     * Esta função é modelo
     * Função a ser sobreescrita para quando houver necessidade de validar os dados a ser inseridos
     * para registro no log
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param string $opt Contem o valor insert | update para pode direcionar algumas validação
     * @return boolean
     */
    public function isValid($opt) {
        /* @var $produto \Adm\Entity\Produto */
        $produto = $this->em->find($this->dataRef['produto'], $this->data['produto']);
        if(is_null($produto)){
            $msg = 'Produto não encontrado.';
            $this->showMessage($msg, 'warning');
            return [$msg];
        }
        if($produto->getEstoqueAtual() < $this->data['qtd']){
            $msg = 'Não tem quantidade suficiente em estoque para atender essa saida!!';
            $this->showMessage($msg, 'warning');
            return [$msg];
        }        
        
        // Logica de validação e retorna false em caso de
        if($opt == 'insert'){
            return $this->isValidInsert();
        }else{
            return $this->isValidUpdate();
        }
    }
    
    /**
     * 
     * @author Danilo Song  <danilosong@outlook.com>
     * @version 1.0
     * @since 04-07-2017
     * @param array $data
     * @param \Adm\Entity\Pedido $pedido
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function pesquisa($data) {
        
        $filters = [];
        $this->setFilter('usuario' , $filters, $data['saida']);
        $this->setFilter('produto' , $filters, $data['saida']);
        $this->setFilter('conjunto', $filters, $data['saida']);
        
        if(isset($data['filtro1']) and isset($data['filtro2'])){
            if(!empty($data['filtro1']) and !empty($data['filtro2'])){
                $ini = $this->strToDate($data['filtro1']);
                $fim = $this->strToDate($data['filtro2']);
                $filters['createdAt'] = ['between' => [$ini, $fim]];
            }            
        }
        return $this->getRepository()->pesquisa($filters);
    }
}