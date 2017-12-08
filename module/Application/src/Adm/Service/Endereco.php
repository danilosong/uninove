<?php

/*
 * To change this license 
 */

namespace Adm\Service;

use Doctrine\ORM\EntityManager;
/**
 * Description of Endereco Service
 * @author Allan Davini
 */
class Endereco extends \Application\Service\AbstractService{

    public function __construct(EntityManager $em, $fatherService = FALSE) {
        $this->supressMsg = TRUE;
        parent::__construct($em, $fatherService) ;
        $this->basePath = 'Adm\Entity\\';
        
        $this->entity = $this->basePath . "Endereco";        
        $this->id = 'idEndereco';
        
        $this->setDataRefArray([
            'ref_createdBy'   => '\Application\Entity\Usuario',
            'ref_updatedBy'   => '\Application\Entity\Usuario',
            'logradouro'      => $this->basePath . 'Logradouro',
        ]);
        
    }     
    
    /**
     * Verica se o endereco ja existe no update caso nao tenta incluir.
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @since 10/11/2016
     * @version 1.0
     * @param string $opt Contem o valor insert | update para pode direcionar algumas validação
     * @return boolean | array Array com mensagem de erros coletadas
     */
    public function isValid($opt) {
        if($opt != 'update'){// Somente no update verifica o endereco se existe
            return true;
        }
        // registro existe retorna true para o restante da logica alterar
        if(isset($this->data[$this->id]) AND !empty($this->data[$this->id])){
            return true;            
        }
        // registro não existe tenta incluir
        // caso de update vai ter que incluir este registro e abortar update.
        unset($this->data[$this->id]);// retira o id se houver para fazer a inserção
        if($this->insert() instanceof $this->entity){
            // aborta o update devolvendo o registro que acabou de ser incluido
            return ['abort',  $this->entityReal, 'Endereco adm inserido sucesso!!'];
        }
        return ['Não foi possivel registrar o endereço corretamente!!!'];
    }
    
    /**
     * Copia o Endereco de uma entidade para outra entidade
     * 
     * @param mixed $from
     * @param mixed $to
     * @return mixed
     */
    public function copyEnderecoFrom($from, $to) {
        if(!method_exists($from, 'getEndereco') or !method_exists($to, 'setEndereco')){
            return FALSE;
        }
        
        $newEndereco = clone $from->getEndereco();
        
        //reset timestamps
        $newEndereco->setCreatedAt();
        $newEndereco->setCreatedBy($this->getUser("id"));
        
        
        $to->setEndereco($newEndereco);
        $this->em->persist($newEndereco);
        
        return $to;
    }
    
    /**
     * Setar flush falso e finaliza apenas quando o retorno for a entity
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @since 15/11/2016
     * @version 1.0
     * @param array $data
     * @return \Adm\Service\entity
     */
    public function insert(array $data = array()) {
        $this->setFlush(false);
        $resul = parent::insert($data);
        if($resul instanceof $this->entity){
            $this->em->flush();
        }
        return $resul;
    }
    
    /**
     * Setar flush falso e finaliza apenas quando o retorno for a entity
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @since 15/11/2016
     * @version 1.0
     * @param array $data
     * @return \Adm\Service\entity
     */
    public function update(array $data = array()) {
        $this->setFlush(false);
        $resul = parent::update($data);
        if($resul instanceof $this->entity){
            $this->em->flush();
        }
        return $resul;
    }
}
