<?php

namespace Adm\Service;

use Doctrine\ORM\EntityManager;
/**
 * Description of Cidade Service
 * @author Danilo Dorotheu <danilo.dorotheu@live.com>
 */
class Cidade extends \Application\Service\AbstractService{

    public function __construct(EntityManager $em, $fatherService = FALSE) {
        parent::__construct($em, $fatherService) ;
        $this->basePath = 'Adm\Entity\\';
        
        $this->entity = $this->basePath . "Cidade";        
        $this->id = 'idCidade';
        
        $this->setDataRefArray([
            'estado'                  => $this->basePath . 'Estado'
            ,'createdBy'              => '\Application\Entity\Usuario'     
            ,'updatedBy'              => '\Application\Entity\Usuario'  
        ]);
        
    }  
    
    /**
     * Valida o registro no BD endereco
     * Verica se o endereco ja existe BD adm e incluir se necessario.
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @since 11/11/2016
     * @version 1.0
     * @param string $opt
     * @return boolean | array Array com mensagem de erros coletadas
     */
    public function isValid($opt) {
        if(!isset($this->data['nomeCidade']) OR empty($this->data['nomeCidade'])){
            return ['O nome do Bairro do endereço é campo obrigatorio!!'];
        }
        // aborta update por entender que nada foi alterado
        $this->data['estado']['idEstado'] = isset($this->data['estado']['idEstado']) ? $this->data['estado']['idEstado'] : 0;
        if($opt == 'update' 
        AND $this->entityReal                  instanceof $this->entity 
        AND $this->entityReal->getNomeCidade()         == $this->data['nomeCidade']
        AND $this->entityReal->getEstado('idEstado')   == $this->data['estado']['idEstado']
        ){
            return ['abort',  $this->entityReal, 'Cidade encontrado com sucesso!!'];
        }
        // procura o bairro no BD de adm
        $finded = (!$this->data['estado']['idEstado']) ?
            $this->getRepository()->findOneByCidadeEstado($this->data['nomeCidade'], $this->data['estado']['nomeEstado'])
            : 
            $this->getRepository()->findOneBy(['nomeCidade' => $this->data['nomeCidade'], 'estado' => $this->data['estado']['idEstado']]);
                
        if($finded){
            // caso encontre o registro aborta insert ou update
            return ['abort',  $finded, 'Cidade encontrado com sucesso!!'];
        }
        
        // Valida o cidade no BD de endereco
        if(!isset($this->data['cidadeCodigo']) OR empty($this->data['cidadeCodigo'])){
            return ['Erro não tem o codigo do cidade valido!!!'];
        }
        /* @var $endCidade \Endereco\Entity\Cidade */
        $endCidade = $this->em->find('\Endereco\Entity\Cidade', $this->data['cidadeCodigo']);
        if(!$endCidade){
            return ['Erro não foi encontrado o cidade no BD endereco!!!'];
            
        }
        $this->data['nomeCidade']         = $endCidade->getcidadeDescricao();
        $this->data['estado']['ufCodigo'] = $endCidade->getufCodigo()->getId();
        $this->data['estado']['uf']       = $endCidade->getufCodigo()->getUfSigla();
        // caso de insert retorna true pois o a restante da logica concluirá o registro.
        if($opt == 'insert'){
            return true; 
        }
        unset($this->data[$this->id]);// retira o id se houver para fazer a inserção
        // caso de update vai ter que incluir este registro e abortar update.
        if($this->insert() instanceof $this->entity){
            // aborta o update devolvendo o registro que acabou de ser incluido
            return ['abort',  $this->entityReal, 'Cidade encontrado com sucesso!!'];
        }
        return ['Houve um erro na inclusão do registro!!'];   
        
        
    }
    
}
