<?php

/*
 * To change this license 
 */

namespace Adm\Service;

use Doctrine\ORM\EntityManager;
/**
 * Description of Bairro Service
 * @author Allan Davini
 */
class Bairro extends \Application\Service\AbstractService{

    public function __construct(EntityManager $em, $fatherService = FALSE) {
        parent::__construct($em, $fatherService) ;
        $this->basePath = 'Adm\Entity\\';
        
        $this->entity = $this->basePath . "Bairro";        
        $this->id = 'idBairro';
        
        $this->setDataRefArray([
            'cidade'                  => $this->basePath . 'Cidade'
            ,'createdBy'              => '\Application\Entity\Usuario'     
            ,'updatedBy'              => '\Application\Entity\Usuario' 
        ]);
        
    }        
     
    
    /**
     * Valida o registro no BD endereco
     * Verica se o endereco ja existe BD adm e incluir se necessario.
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @since 10/11/2016
     * @version 1.0
     * @param string $opt
     * @return boolean | array Array com mensagem de erros coletadas
     */
    public function isValid($opt) {
        if(!isset($this->data['nomeBairro'])){
            $this->data['nomeBairro'] = '';
        }
        // aborta update por entender que nada foi alterado
        $this->data['cidade']['idEstado'] = isset($this->data['cidade']['idEstado']) ? $this->data['cidade']['idEstado'] : 0;
        if($opt == 'update' 
        AND $this->entityReal                  instanceof $this->entity 
        AND $this->entityReal->getNomeBairro()         == $this->data['nomeBairro']
        AND $this->entityReal->getCidade('idCidade')   == $this->data['cidade']['idCidade']
        ){
            return ['abort',  $this->entityReal, 'Logradouro encontrado com sucesso!!'];
        }
        // procura o bairro no BD de adm
        $finded = ($this->data['cidade']['idEstado']) ?
            $this->getRepository()->findOneBy(['nomeBairro' => $this->data['nomeBairro'], 'cidade' => $this->data['cidade']['idEstado']])
            : 
            FALSE;
                
        if($finded){
            // caso encontre o registro aborta insert ou update
            return ['abort',  $finded, 'Tipo Logradouro encontrado com sucesso!!'];
        }
        // Valida o bairro no BD de endereco
        if(!isset($this->data['bairroCodigo']) OR empty($this->data['cidade']['cidadeCodigo'])){
            return ['Erro não tem o codigo do bairro valido!!!'];
        }
        /* @var $endBairro \Endereco\Entity\Bairro */
        $endBairro = $this->em->find('\Endereco\Entity\Bairro', $this->data['bairroCodigo']);
        if($endBairro){
            $this->data['nomeBairro']                     = $endBairro->getBairroDescricao();
            $endCidade = $endBairro->getCidadeCodigo();
        }else{
            $endCidade = $this->em->find('\Endereco\Entity\Cidade', $this->data['cidade']['cidadeCodigo']);
        }
        if(is_null($endCidade)){
            return ['Bairro e cidade não encontrado na pesquisa !!!'];                
        }            
        $this->data['cidade']['cidadeCodigo']         = $endCidade->getId();
        $this->data['cidade']['nomeCidade']           = $endCidade->getcidadeDescricao();
        $this->data['cidade']['estado']['ufCodigo']   = $endCidade->getufCodigo()->getId();
        $this->data['cidade']['estado']['nomeEstado'] = $endCidade->getufCodigo()->getUfDescricao();
        // caso de insert retorna true pois o a restante da logica concluirá o registro.
        if($opt == 'insert'){
            return true; 
        }
        unset($this->data[$this->id]);// retira o id se houver para fazer a inserção
        // caso de update vai ter que incluir este registro e abortar update.
        if($this->insert() instanceof $this->entity){
            // aborta o update devolvendo o registro que acabou de ser incluido
            return ['abort',  $this->entityReal, 'Logradouro encontrado com sucesso!!'];
        }
        return ['Houve um erro na inclusão do registro!!'];    
    }
}
