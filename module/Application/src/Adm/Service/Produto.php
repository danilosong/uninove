<?php

namespace Adm\Service;

use Doctrine\ORM\EntityManager;

/**
 * Description of Produto
 *
 * @author Danilo Song <danilosong@outlook.com>
 * @since 14/06/2017
 */
class Produto extends AdmAbstractService{

    public function __construct(EntityManager $em, $fatherService = FALSE) {
        parent::__construct($em, $fatherService);
        $this->basePath = 'Adm\Entity\\';

        $this->entity = $this->basePath . "Produto";

        $this->setDataRefArray([
            'ref_createdBy'   => '\Application\Entity\Usuario',
            'ref_updatedBy'   => '\Application\Entity\Usuario',
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
            $this->data['createdAt'] = date('d/m/Y');
        }
    }
    
    /**
     * Faz atualizacao do pedido os campo qtd e valor.
     * Le todos os itens do pedido e refaz a contagem.
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0
     * @since 30-06-2017
     * @param \Adm\Entity\Produto $produto
     * @param integer $qtd
     */
    public function addQtd($produto, $qtd) {
        $result = $this->update([
            'id'               => $produto->getId()
            ,'estoqueAtual'    => $produto->getEstoqueAtual()  + $qtd
            ,'data_ult_compra' => new \DateTime('now')   
        ]);        
        if(is_object($result)){
            $this->showMessage("Produto atualizado com sucesso!!!!");
        }else{
            $this->showMessage("Houve um erro ao atualizar o pedido!!!", "error");
        } 
    }
    
    /**
     * Faz atualizacao do pedido os campo qtd e valor.
     * Le todos os itens do pedido e refaz a contagem.
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0
     * @since 30-06-2017
     * @param \Adm\Entity\Produto $produto
     * @param integer $qtd
     */
    public function subQtd($produto, $qtd) {
        $result = $this->update([
            'id'               => $produto->getId()
            ,'estoqueAtual'    => $produto->getEstoqueAtual()  - $qtd
            ,'data_ult_compra' => new \DateTime('now')   
        ]);        
        if(is_object($result)){
            $this->showMessage("Produto atualizado com sucesso!!!!");
        }else{
            $this->showMessage("Houve um erro ao atualizar o pedido!!!", "error");
        } 
    }
    
    /**
     * Faz atualizacao do pedido os campo qtd e valor.
     * Le todos os itens do pedido e refaz a contagem.
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0
     * @since 06-07-2017
     * @param \Adm\Entity\Produto $produto
     * @param float $valor
     */
    public function updateValor($produto, $valor) {
        $result = $this->update([
            'id'               => $produto->getId()
            ,'valorProd'       => $valor
            ,'data_ult_compra' => new \DateTime('now')
        ]);        
        if(is_object($result)){
            $this->showMessage("Produto atualizado com sucesso!!!!");
        }else{
            $this->showMessage("Houve um erro ao atualizar o pedido!!!", "error");
        } 
    }
    
     /**
     * 
     * @author Danilo Song  <danilosong@outlook.com>
     * @version 1.0
     * @since 03-07-2017
     * @param array $data
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function pesquisa($data) {
        $filters = [];
        $this->setFilter('id'   , $filters, $data['produto']);
        $this->setFilter('setor', $filters, $data['produto']);
        
        /* @var $qb \Doctrine\ORM\QueryBuilder */
        $qb = $this->getRepository()->pesquisa($filters);
        if(isset($data['show'])){
            $qb->andWhere('e.estoqueAtual <= e.estoqueMinimo');
        }
        return $qb;
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
    
    /**
     * Função que é executada quando há um update ou insert.
     * 
     * @param \Adm\Entity\Produto $produto
     */
    public function execBackJobs(\Adm\Entity\Produto $produto) {
        if($produto->getEstoqueAtual(FALSE) <= $produto->getEstoqueMinimo(FALSE)){
            
            $this->sendEmailAlert($produto);
        }
    }

    /*
     * Função que envia email quando o estoque atual chega ao valor do estoque minimo do produto
     * @since 10-07-2017
     */
    public function sendEmailAlert(\Adm\Entity\Produto $produto) {
        $emails             = $this->getParametroChave('email_estoque_minimo', false);
        $options = [];
        foreach ($emails as $to => $email){
            if(!isset($options['to'])){
                      $options['to']     = $email;
                      $options['toName'] = $to;
                      
                continue;
            }
            $options["cc"][$to]    = $email;
        }
        $options["subject"]  = "Produto - " . $produto->getNomeProd() . " abaixo do minimo!";
        $options["data"]     = ["entity" => $produto];

        $senderEmail = $this->getController()->getEmail();
        $senderEmail->enviaEmail($options, 'email-produto-minimo');
    }

}
