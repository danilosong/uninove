<?php

namespace Adm\Service;

use Doctrine\ORM\EntityManager;

/**
 * Description of PedidoItem
 *
 * @author Danilo Song <danilosong@outlook.com>
 * @since 14/06/2017
 */
class PedidoItem extends AdmAbstractService{

    /**
     * Valor anterior da qtdreceb do pedidoitem.
     * @var integer
     */
    protected $qtdReceb = 0;
    
    public function __construct(EntityManager $em, $fatherService = FALSE) {
        parent::__construct($em, $fatherService);
        $this->basePath = 'Adm\Entity\\';

        $this->entity = $this->basePath . "PedidoItem";

        $this->setDataRefArray([
            'ref_createdBy'   => '\Application\Entity\Usuario',
            'ref_updatedBy'   => '\Application\Entity\Usuario',
            'ref_pedido'      => '\Adm\Entity\Pedido',
            'ref_produto'     => '\Adm\Entity\Produto',
        ]);
    }
    
    /**
     * Verifica 
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 24/03/2017
     * @return mixed
     */
    public function isValidInsert() {
        
        $finded = $this->getRepository()->findOneBy([
            'pedido'   => $this->data['pedido']
            ,'produto' => $this->data['produto']
        ]);
        if($finded){
            $this->data[$this->id] = $finded->getId();
            $this->data['status']  = 'ATIVO';
            $resul = $this->update($this->data);
            if(is_object($resul)){
                return ['abort'];
            }
            return ['abort', $finded, 'Item já cadastrado no pedido e não pode ser alterado!!!', 'error'];
        }
        return TRUE;
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
     * 
     * @param \Adm\Entity\PedidoItem $pedidoItem
     */
    public function execBackJobs(\Adm\Entity\PedidoItem $pedidoItem) {
        /* @var $srvPedido \Adm\Service\Pedido */
        $srvPedido = $this->getService('\Adm\Service\Pedido');
        $srvPedido->upgradeFromItem($pedidoItem);
        /* @var $srvProduto \Adm\Service\Produto */
        $srvProduto = $this->getService('\Adm\Service\Produto');
        if($pedidoItem->getQtdReceb()){
            if($pedidoItem->getStatus() == 'ATIVO'){
                $srvProduto->addQtd($pedidoItem->getProduto(), $pedidoItem->getQtdReceb() * $pedidoItem->getProduto()->getCompraQtd());
                $srvProduto->updateValor($pedidoItem->getProduto(), $pedidoItem->getValor());
            }else{
                $srvProduto->subQtd($pedidoItem->getProduto(), $pedidoItem->getQtdReceb() * $pedidoItem->getProduto()->getCompraQtd());
            }
            $srvPedido->updateStatus($pedidoItem->getPedido());
        }
        if($this->qtdReceb){
            $srvProduto->subQtd($pedidoItem->getProduto(), $this->qtdReceb * $pedidoItem->getProduto()->getCompraQtd());
        }
    }
        
    /**
     * Esta função é modelo
     * Função a ser sobreescrita para quando houver necessidade de pegar os campos que foram alterados na entidade
     * para registro no log
     *
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @param \Adm\Entity\PedidoItem $ent
     */
    public function getDiff($ent) {
        $this->qtdReceb = $ent->getQtdReceb();
        parent::getDiff($ent);
    }
    
    public function delete($id, $forceRemove = FALSE, $status = 'RECEBIDO') {
        $resul = parent::delete($id, $forceRemove, $status);
        $this->execBackJobs($this->entityReal);
        return $resul;
    }

}
