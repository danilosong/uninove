<?php

namespace Adm\Service;

use Doctrine\ORM\EntityManager;
/**
 * Description of Logradouro Service
 * @author Danilo Dorotheu <danilo.dorotheu@live.com>
 */
class Logradouro extends \Application\Service\AbstractService{

    public function __construct(EntityManager $em, $fatherService = FALSE) {
        parent::__construct($em, $fatherService) ;
        $this->basePath = 'Adm\Entity\\';
        
        $this->entity = $this->basePath . "Logradouro";        
        $this->id = 'idLogradouro';
        
        $this->setDataRefArray([
            'tipoLogradouro' => $this->basePath . 'TipoLogradouro',
            'bairro'         => $this->basePath . 'Bairro'
        ]);
        
    } 
    
    /**
     * Logica validação de um endereço se basea no seu cep
     * - Em caso de update se o registro ja existir e nada for alterado abort update
     * - O CEP deve existir no BD de endereco caso contrario retorna erro.
     * - Encontrado o registro no Bd de endereco verifica se existe no BD adm
     * - - Sim retorna o registro encontrado
     * - - Não faz a inclusão e retorna o registro inserido 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0       
     * @since 10-11-2016 
     * @param string $opt
     * @return boolean | array Array com mensagem de erros coletadas
     */    
    public function isValid($opt) {
        !isset($this->data['nomeLogradouro']) && $this->data['nomeLogradouro'] = '';
        empty($this->data['nomeLogradouro'])  && $this->data['nomeLogradouro'] = 'Sem Nome';
        
        if(!isset($this->data['cep'][7])){
            return ['O campo cep não foi preenchido.'];
        }
        
        // aborta update por entender que nada foi alterado
        if($opt == 'update' AND $this->entityReal instanceof $this->entity AND $this->entityReal->getNomeLogradouro() == $this->data['nomeLogradouro']){
            return ['abort',  $this->entityReal, 'Logradouro encontrado com sucesso!!'];
        }
        // procura o CEP no BD de endereco
        /* @var $rpEnd \Endereco\Entity\Repository\EnderecoRepository */
        $rpEnd     = $this->em->getRepository("\Endereco\Entity\Endereco");
        /* @var $endFinded \Endereco\Entity\Endereco */
        $endFinded = $rpEnd->findAllByCep($this->data['cep']);
        if($endFinded){
            return $this->saveEndereco($opt, $endFinded, $rpEnd);
        }
        // procura o CEP no BD de cidade
        /* @var $rpCid \Endereco\Entity\Repository\CidadeRepository */
        $rpCid     = $this->em->getRepository("\Endereco\Entity\Cidade");
        /* @var $cidFinded \Endereco\Entity\Cidade */
        $cidFinded = $rpCid->findByCepUnico($this->data['cep']);
        if($cidFinded){
            return $this->saveEnderecoCidade($opt, $cidFinded, $rpCid);
        }
        return ['Este CEP não existe no BD de endereços so sistema!!!'];
        
    }

    public function saveEnderecoCidade($opt, \Endereco\Entity\Cidade $cidFinded, \Endereco\Entity\Repository\CidadeRepository $rpCid) {
        if(empty($this->data['nomeLogradouro'])){
            return ['O nome do Logradouro não pode estar vazio!!!'];
        }
        if(empty($this->data['tipoLogradouro']['tipo'])){
            return ['Deve ser escolhido o tipo de logradouro!!!'];
        }
        // valida a rua se ja exite no bd de adm
        /* @var $finded \Adm\Entity\Logradouro */
        $finded = $this->getRepository()->findOneByNomeLogradouro($this->data['nomeLogradouro']);
        if( $finded instanceof $this->entity
        AND $finded->getBairro('nomeBairro') == $this->data['bairro']['nomeBairro']
        ){
            $this->entityReal      = $finded;
            $this->data[$this->id] = $finded->getId();
            // aborta o insert ou update devolvendo o registro encontrado
            return ['abort',  $this->entityReal, 'Logradouro encontrado com sucesso!!'];
        }
        $this->data['bairro']['bairroCodigo']                           = '';
        $this->data['bairro']['cidade']['cidadeCodigo']                 = $cidFinded->getcidadeCodigo();
        $this->data['bairro']['cidade']['nomeCidade']                   = $cidFinded->getcidadeDescricao();
        $this->data['bairro']['cidade']['estado']['ufCodigo']           = $cidFinded->getUfCodigo()->getUfCodigo();
        $this->data['bairro']['cidade']['estado']['uf']                 = $cidFinded->getufCodigo()->getUfDescricao();
        $this->data['bairro']['cidade']['estado']['pais']['nomePais']   = 'BRASIL';
        // caso de insert retorna pois a restante da logica concluirá o registro.
        if($opt == 'insert'){
            return true; 
        }
        // caso de update vai ter que incluir este registro e abortar update.
        unset($this->data[$this->id]);// retira o id se houver para fazer a inserção
        if($this->insert() instanceof $this->entity){
            // aborta o update devolvendo o registro que acabou de ser incluido
            return ['abort',  $this->entityReal, 'Logradouro encontrado com sucesso!!'];
        }
        return ['Houve um erro na inclusão do registro de cep sem logradouro !!'];       
        
    }

    public function saveEndereco($opt, \Endereco\Entity\Endereco $endFinded, \Endereco\Entity\Repository\EnderecoRepository $rpEnd) {
        $arrayEnd = $rpEnd->sepTipoRuaNumero($endFinded->getEnderecoLogradouro());
        // valida a rua se ja exite no bd de adm
        $finded = $this->getRepository()->findOneByNomeLogradouro($arrayEnd['rua']);
        if($finded instanceof $this->entity){
            $this->entityReal      = $finded;
            $this->data[$this->id] = $finded->getId();
            // aborta o insert ou update devolvendo o registro encontrado
            return ['abort',  $this->entityReal, 'Logradouro encontrado com sucesso!!'];
        }
        $this->data['tipoLogradouro']['tipo']                           = $arrayEnd['tipo'];
        $this->data['nomeLogradouro']                                   = $arrayEnd['rua'];
        $this->data['bairro']['bairroCodigo']                           = $endFinded->getBairroCodigo()->getBairroCodigo();
        $this->data['bairro']['nomeBairro']                             = $endFinded->getBairroCodigo()->getBairroDescricao();
        $this->data['bairro']['cidade']['cidadeCodigo']                 = $endFinded->getBairroCodigo()->getCidadeCodigo()->getcidadeCodigo();
        $this->data['bairro']['cidade']['nomeCidade']                   = $endFinded->getBairroCodigo()->getCidadeCodigo()->getcidadeDescricao();
        $this->data['bairro']['cidade']['estado']['ufCodigo']           = $endFinded->getBairroCodigo()->getCidadeCodigo()->getufCodigo()->getUfCodigo();
        $this->data['bairro']['cidade']['estado']['uf']                 = $endFinded->getBairroCodigo()->getCidadeCodigo()->getufCodigo()->getUfDescricao();
        $this->data['bairro']['cidade']['estado']['pais']['nomePais']   = 'BRASIL';
        // caso de insert retorna pois a restante da logica concluirá o registro.
        if($opt == 'insert'){
            return true; 
        }
        // caso de update vai ter que incluir este registro e abortar update.
        unset($this->data[$this->id]);// retira o id se houver para fazer a inserção
        if($this->insert() instanceof $this->entity){
            // aborta o update devolvendo o registro que acabou de ser incluido
            return ['abort',  $this->entityReal, 'Logradouro encontrado com sucesso!!'];
        }
        return ['Houve um erro na inclusão do registro!!'];       
        
    }

}
