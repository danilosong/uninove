<?php

/*
 * To change this license
 */

namespace Endereco\Entity\Repository;

/**
 * Description of EnderecoRepository
 *
 * @author Paulo Watakabe <email>watakabe05@gmail.com</email>
 */
class EnderecoRepository extends \Application\Entity\Repository\AbstractRepository {
    
    
    /**
     * 
     * @param string $cep
     * @return \Endereco\Entity\Endereco | NULL
     */
    public function findAllByCep($cep) {
        // Monta a dql para fazer consulta no BD Cuidado com relacionamentos tcm zeros para muitos (ex: Grupos)
        $qb = $this->getEntityManager()
            ->createQueryBuilder()
            ->from('Endereco\Entity\Endereco', 'e')
            ->select('e, b, c, u')
            ->join('e.bairroCodigo', 'b')
            ->join('b.cidadeCodigo', 'c')
            ->join('c.ufCodigo', 'u')
            ->where('e.enderecoCep LIKE :cep')
            ->setParameter('cep', $cep)
        ;         
        return $qb->getQuery()->getOneOrNullResult();       
    }
    
    /**
     * Faz a pesquisa no banco para encontrar ruas
     * @param string $logradouro
     * @param string $tipoLogradouro
     * @return array of \Endereco\Entity\Endereco
     */
    public function findByRua($logradouro, $tipoLogradouro='') {
        // Monta a dql para fazer consulta no BD Cuidado com relacionamentos tcm zeros para muitos (ex: Grupos)
        $qb = $this->getEntityManager()
            ->createQueryBuilder()
            ->from('Endereco\Entity\Endereco', 'e')
            ->select('e, b, c, u')
            ->join('e.bairroCodigo', 'b')
            ->join('b.cidadeCodigo', 'c')
            ->join('c.ufCodigo', 'u')
            ->where('e.enderecoLogradouro LIKE :logradouro')
            ->setParameter('logradouro', '%' . $logradouro . '%')
        ;      
        if(!empty($tipoLogradouro)){
            $qb->andWhere('e.enderecoLogradouro LIKE :tipoLogradouro')
            ->setParameter('tipoLogradouro', $tipoLogradouro . '%');
        }
        return $qb->setMaxResults(20)->getQuery()->getResult();   
    }
    
    /**
     * Separa os itens do endereco tipo, logradouro, numero.
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 16-11-2016 
     * @param string $endereco
     * @return array
     */
    public function sepTipoRuaNumero($endereco) {
        //ex: AVENIDA DOM ANTÔNIO BRANDÃO, 333
        $sepTipoRua = explode(' ', $endereco, 2);
        if(strpos($sepTipoRua[1], ',') !== false){
            $sepRuaNumero = explode(',', $sepTipoRua[1], 1);
            return ['tipo' => $sepTipoRua[0], 'rua' => $sepRuaNumero[0], 'numero' => $sepRuaNumero[1]];
        }        
        return ['tipo' => $sepTipoRua[0], 'rua' => $sepTipoRua[1]];
        
    }
    
    /**
     * Separa o endereco e coloca nas colunas propria de cada item.
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 16-11-2016 
     */
    public function separaAll() {
        echo 'inicio<br>' . PHP_EOL;
        // Monta a dql para fazer consulta no BD Cuidado com relacionamentos tcm zeros para muitos (ex: Grupos)
        $qb = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('e')    
            ->from('Endereco\Entity\Endereco', 'e')
            ->where('e.enderecoCodigo >= :id')
            ->setParameter('id', 708200)
                 ->setMaxResults(100000)    
        ;      
        
        $all = $qb->getQuery()->getResult();   
        /* @var $end \Endereco\Entity\Endereco */
        $walk = $walked = 1000;
        $walking = 0 ;
        foreach ($all as $end) {
            $sep = $this->sepTipoRuaNumero($end->getEnderecoLogradouro());
            $end->setTipoLogradouro($sep['tipo']);
            $end->setLogradouro($sep['rua']);
            isset($sep['numero']) && $end->setNumero($sep['numero']);
            $this->getEntityManager()->persist($end);
            $walking ++;
            if($walking > $walked){
                echo '<pre>', $end->getId() , ' ', var_dump($walking), '</pre>';
                $this->getEntityManager()->flush();
//                $this->getEntityManager()->clear();
                $walked += $walk;                
            }
        }
        $this->getEntityManager()->flush();
        echo '<pre>', var_dump($walking), '</pre>';
        echo 'fim<br>' . PHP_EOL;
    }

}
