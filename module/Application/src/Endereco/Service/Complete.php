<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Endereco\Service;

use Doctrine\ORM\EntityManager;
/**
 * Description of Complete
 * Le a base de dados procurando por um cep ja cadastrado e retorna os dados para preenchimento.
 * Le a base de dados procurando pelo nome da rua e retorna os dados para preenchimento.
 * 
 * 
 * @author Paulo Watakabe
 */
class Complete extends \Application\Service\AbstractService{

    public function __construct(EntityManager $em) {
        parent::__construct($em);    
    }   
    
    public function getRua($tipoLogradouro, $logradouro) {
        if(empty($logradouro)){
            return [];
        }
        // pesquisar na base de dados se exite a rua
        /* @var $rep  \Endereco\Entity\Repository\EnderecoRepository  */
        $rep = $this->em->getRepository('\Endereco\Entity\Endereco');
        $end = $rep->findByRua($logradouro, $tipoLogradouro);
        $data = [];
        if($end){
            foreach ($end as $ent) {
                $data[] = $this->convertAjaxFormat($ent);
            }
        }        
        return $data;
    }
    
    /**
     * Metodo que faz chamadas para validação do cep
     * Pesquisa pelo cep completo
     * Pesquisa pelo cep unico
     * Faz pesquisa no web service externo
     * Inclui endereçõ do web service externo na propria base de dados
     * @param string $cep
     * @return array
     */
    public function getCep($cep) {
        // validar o cep
        if(!$this->validaCep($cep)){
            return ["resultado"=>"0","resultado_txt"=>"erro ao buscar cep" . $cep];
        }
        // pesquisar na base de dados se exite o cep
        /* @var $rep  \Endereco\Entity\Repository\EnderecoRepository  */
        $rep = $this->em->getRepository('\Endereco\Entity\Endereco');
        $end = $rep->findAllByCep($cep);
        if($end){
            return $this->convertAjaxFormat($end);
        }
        $rep = $this->em->getRepository('\Endereco\Entity\Cidade');
        $end = $rep->findByCepUnico($cep);
        if($end){
            return $this->convertAjaxFormatCepUnico($end);
        }
        // não encontrou busca no web service
        $web = $this->getFromWebService($cep);
        if(!$web){
            return ['resultado'=>'0','resultado_txt'=>'erro ao buscar cep'];
        }
        // Se encontrou cadastra endereco encontrado na base de dados.
        $this->insertCepFull($web);
        return $web;
    }
    
    /**
     * Limpa e verifica dados do cep
     * @param string $cep
     * @return boolean
     */
    public function validaCep(&$cep) {
        $cep = preg_replace("/[^0-9]/", "", $cep);
        if(strlen($cep) != 8){
            return FALSE;
        }
        return $cep;
    }
    
    /**
     * Busca no web service o cep nao encontrado na base de dados.
     * O endereço do web service fica em parametros.
     * Faz uma personalização dos dados encontrados.
     * @param string $cep
     * @return boolean|array
     */
    public function getFromWebService($cep){        
        $url = $this->em->getRepository('Application\Entity\Parametros')->fetchPairs('webServiceForCep',FALSE);
        $retorno = @file_get_contents(key($url) . urlencode($cep)); 
        if(!$retorno){ 
            return FALSE;
        }
        $resultado = json_decode($retorno, true);   
        $resultado['cep'] = $cep;
        $resultado['pais'] = 'Brasil';  
        $resultado["ajax"] = "sim" ;
        return $resultado;       
    }

    /**
     * Pega a entidade que retornou da consulta e monta um array de dados para retorno do tipo cep completo
     * @param \Endereco\Entity\Endereco $ent
     * @return array
     */
    public function convertAjaxFormat($ent) {
        $ret = ["resultado" => "1", "resultado_txt"=>  "sucesso - cep completo"];
        $ret["uf"] = $ent->getBairroCodigo()->getCidadeCodigo()->getufCodigo()->getUfSigla(); 
        $ret["cidade"] = $ent->getBairroCodigo()->getCidadeCodigo()->getcidadeDescricao(); 
        $ret["bairro"] = $ent->getBairroCodigo()->getBairroDescricao(); 
        $tpLogradouro = explode(' ', $ent->getEnderecoLogradouro(), 2);
        $ret["tipo_logradouro"] = $tpLogradouro[0]; 
        $ret["logradouro"] = $tpLogradouro[1]; 
        $ret["complemento"] = $ent->getEnderecoComplemento(); 
        $ret["cep"]    = $ent->getEnderecoCep(); 
        $ret["pais"] = "Brasil" ;
        $ret["ajax"] = "nao" ;
        return $ret;
    }

    /**
     * Pega a entidade que retornou da consulta e monta um array de dados para retorno do tipo cep unico
     * @param \Endereco\Entity\Cidade $ent
     * @return array
     */
    public function convertAjaxFormatCepUnico($ent) {
        $ret = ["resultado" => "2", "resultado_txt"=>  "sucesso - cep único"];
        $ret["uf"] = $ent->getufCodigo()->getUfSigla(); 
        $ret["cidade"] = $ent->getcidadeDescricao(); 
        $ret["bairro"] = ''; 
        $ret["logradouro"] = ''; 
        $ret["tipo_logradouro"] = ''; 
        $ret["cep"]    = $ent->getCidadeCep(); 
        $ret["pais"] = "Brasil" ;
        $ret["ajax"] = "nao" ;
        return $ret;        
    }

    /**
     * Monta dado para incluir um novo cep completo.
     * Caso for um cep unico redireciona para outro metodo
     * @param array $data
     * @return nothing
     */
    public function insertCepFull($data) {
        if ($data['resultado'] == '2'){
            $this->insertCepUnico($data);
            return;
        }
        
        $insert['bairroCodigo'] = '';
        $insert['enderecoCep'] = '';
        $insert['enderecoLogradouro'] = '';
        $insert['enderecoComplemento'] = '';
        
        $insert['cidadeCodigo'] = '';
        $insert['bairroDescricao'] = '';
        
        $insert['ufCodigo'] = '';
        $insert['cidadeDescricao'] = '';
        $insert['cidadeCep'] = '';
        
        $insert['ufSigla'] = '';
        $insert['ufDescricao'] = '';
    }

    /**
     * Monta dado para incluir um novo cep unico.
     * @param array $data
     */
    public function insertCepUnico($data) {
        $insert['bairroCodigo'] = '';
        $insert['enderecoCep'] = '';
        $insert['enderecoLogradouro'] = '';
        $insert['enderecoComplemento'] = '';
        
        $insert['cidadeCodigo'] = '';
        $insert['bairroDescricao'] = '';
        
        $insert['ufCodigo'] = '';
        $insert['cidadeDescricao'] = '';
        $insert['cidadeCep'] = '';
        
        $insert['ufSigla'] = '';
        $insert['ufDescricao'] = '';
    }

}
