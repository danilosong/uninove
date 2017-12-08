<?php

namespace Adm\Service;

use Doctrine\ORM\EntityManager;
/**
 * Description of TipoLogradouro Service
 * @author Allan Davini
 */
class TipoLogradouro extends \Application\Service\AbstractService{

    public function __construct(EntityManager $em, $fatherService = FALSE) {
        parent::__construct($em, $fatherService) ;
        $this->basePath = 'Adm\Entity\\';
        
        $this->entity = $this->basePath . "TipoLogradouro";        
        $this->id = 'idTipoLogradouro';
        
        
        
    }   
    
    /**
     * 
     * Verica se o endereco ja existe no update caso nao tenta incluir.
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @since 10/11/2016
     * @version 1.0
     * @param string $opt
     * @return boolean | array Array com mensagem de erros coletadas
     */
    public function isValid($opt) {
        if(!isset($this->data['tipo']) OR empty($this->data['tipo'])){
            return ['O tipo do endereço é campo obrigatorio!!'];
        }
        // procura o tipo no BD de adm
        $finded = $this->getRepository()->findOneByTipo($this->data['tipo']);
        if($finded){
            // caso encontre o registro aborta insert ou update
            return ['abort',  $finded, 'Tipo Logradouro encontrado com sucesso!!'];
        }
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
