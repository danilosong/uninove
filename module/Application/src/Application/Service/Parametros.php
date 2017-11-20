<?php

/*
 * License GPL .
 * 
 */

namespace Application\Service;

use Doctrine\ORM\EntityManager;

/**
 * Description of Parametros
 *
 * @author Paulo Watakabe <watakabe05@gmail.com>
 * @version 1.0  
 * @since 21-07-2016  
 */
class Parametros extends AbstractService{

    /**
     * Serviço basico para manipular entida e suas relações caso houver
     * Insert, Update e validação do registro.
     * Obs tem redirecionamento de atributos se um object for passado em $fatherService sera colocado em $controller e atribuido falso ao mesmo
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.2
     * @param EntityManager $em              Gerenciador ORM de dados do Doctrine
     * @param boolean       $fatherService   Opcional serve para padronizar o flush em caso de chamadas recursivas ou chamado por outro serviço
     */
    public function __construct(EntityManager $em, $fatherService = FALSE) {
        parent::__construct($em, $fatherService);
        $this->entity = $this->basePath . "Parametros";
        $this->id = 'idParame';
    }
    
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 21-07-2016 
     * @param array $data
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function pesquisa($post) {        
        $filter = $this->getBasicaFilters($post);
        /* @var $rp \Application\Entity\Repository\ParametrosRepository */
        $rp = $this->em->getRepository($this->entity);

        return $rp->pesquisa($filter);
    }

    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 21-07-2016 
     * @param type $data
     * @return type
     */
    public function getBasicaFilters($data) {
        if(!empty($data['chave'])){
            return ['chave' => $data['chave']];
        }
        if(!empty($data['id'])){
            $ent = $this->em->find($this->entity, $data['id']);
            /* @var $ent \Application\Entity\Parametros */
            if ($ent){
                return ['chave' => $ent->getChave()];
            }            
        }
        return [];
    }
}
