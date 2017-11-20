<?php

/*
 * To change this license 
 */

namespace Application\Service;

use Doctrine\ORM\EntityManager;

/**
 * Description of TableConfig
 *
 * @author Paulo Watakabe <watakabe05@gmail.com>
 * @since 10-07-2016
 */
class TableConfig extends AbstractService{

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
        
        $this->entity = $this->basePath . "TableConfig";        
        
        $this->setDataRefArray([
            'createdBy' => $this->basePath . 'Usuario',
            'updatedBy' => $this->basePath . 'Usuario',
        ]);
    }
}
