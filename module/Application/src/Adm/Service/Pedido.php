<?php

namespace Adm\Service;

use Doctrine\ORM\EntityManager;

/**
 * Description of Pedido
 *
 * @author Danilo Song <danilosong@outlook.com>
 * @since 14/06/2017
 */
class Pedido extends AdmAbstractService{

    public function __construct(EntityManager $em, $fatherService = FALSE) {
        parent::__construct($em, $fatherService);
        $this->basePath = 'Adm\Entity\\';

        $this->entity = $this->basePath . "Pedido";

        $this->setDataRefArray([
            'ref_createdBy'   => '\Application\Entity\Usuario',
            'ref_updatedBy'   => '\Application\Entity\Usuario',
            'ref_usuario'     => '\Application\Entity\Usuario',
            'ref_fornecedor'  => '\Adm\Entity\Fornecedor',
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
    
    /**
     * Metodo para atualizar frete e desconto do pedido quando alterado.
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 18-09-2017
     * @param array $data
     * @return \Adm\Service\entity
     */
    public function update(array $data = array()) {
        /* @var $resul \Adm\Entity\Pedido */
        $resul =  parent::update($data);
        if($resul instanceof $this->entity){
            $pedidoItems = $resul->listPedidoItems();
            foreach ($pedidoItems as $pedidoItem) {
                $this->upgradeFromItem($pedidoItem);
                break;
            }
        }
        return $resul;
    }
    
    /**
     * Faz atualizacao do pedido os campo qtd e valor.
     * Le todos os itens do pedido e refaz a contagem.
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 23-06-2017
     * @param \Adm\Entity\PedidoItem $pedidoItem
     */
    public function upgradeFromItem($pedidoItem) {
        /* @var $pedido \Adm\Entity\Pedido */
        $pedido = $pedidoItem->getPedido();        
        $items = $pedido->listPedidoItems();
        /* @var $item \Adm\Entity\PedidoItem */
        $qtdItem = 0 ;
        $total   = 0 ;
        foreach ($items as $item) {
            $qtdItem ++;
            $total += $item->getQtd() * $item->getValor(false);
        }
        $total += $pedido->getFrete() - $pedido->getDesconto();
        $result = parent::update([
            'id'         => $pedido->getId()
            ,'qtd'       => $qtdItem
            ,'total'     => $total
            ,'updatedBy' => $pedidoItem->getCreatedBy('id')    
        ]);        
        if(is_object($result)){
            $this->showMessage("Pedido atualizado com sucesso!!!!");
        }else{
            $this->showMessage("Houve um erro ao atualizar o pedido!!!", "error");
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
        if(isset($data['pedido'])){
            if(!empty($data['pedido'])){
                $this->setFilter('status', $filters, $data['pedido']);
                $this->setFilter('fornecedor', $filters, $data['pedido']);
            }
        }
            
        if(isset($data['filtro1']) and isset($data['filtro2'])){
            if(!empty($data['filtro1']) and !empty($data['filtro2'])){
                $ini = $this->strToDate($data['filtro1']);
                $fim = $this->strToDate($data['filtro2']);
                $filters['createdAt'] = ['between' => [$ini, $fim]];
            }            
        }
        return $this->getRepository()->pesquisa($filters);
    }
    
    /**
     * 
     * @author Danilo Song  <danilosong@outlook.com>
     * @version 1.0
     * @since 10-07-2017
     * @param \Adm\Entity\Pedido $pedido
     */
    public function updateStatus($pedido) {
        if($pedido->getStatus() == 'CANCELADO'){
            return;
        }
        $items      = $pedido->listPedidoItems();        
        /* @var $item \Adm\Entity\PedidoItem */
        $qtdRecebido = 0 ;
        $qtdItem     = count($items);
        foreach ($items as $item){
            if($item->getQtdReceb() == $item->getQtd()){
                $qtdRecebido ++;
            }
        }
        if($qtdItem == $qtdRecebido){
            $status = 'RECEBIDO';            
        }else if($qtdRecebido == 0){
            $status = 'ATIVO';
        }else{
            $status = 'RECEBIDO PARCIAL';
        }
        if($pedido->getStatus() != $status){
            $pedido->setStatus($status);
            $this->em->persist($pedido);
            $this->em->flush();
        }
    }
    
    /**
     * Faz a troca de parametro de $status
     * @author Danilo Song  <danilosong@outlook.com>
     * @version 1.0
     * @since 12-07-2017
     */
    public function delete($id, $forceRemove = FALSE, $status = 'CANCELADO') {
        parent::delete($id, $forceRemove, $status);
    }
}
