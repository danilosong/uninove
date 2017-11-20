<?php

/*
 * To change this license 
 */

namespace Application\Service;

use Doctrine\ORM\EntityManager;

/**
 * Description of TableConfigColun
 *
 * @author Danilo Dorotheu <danilo.dorotheu@live.com>
 * @since 13-07-2016
 */
class TableConfigColun extends AbstractService{

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
        
        $this->entity = $this->basePath . "TableConfigColun";        
        
        $this->setDataRefArray([
            'ref_tableConfig' => $this->basePath . "TableConfig",
            'createdBy' => $this->basePath . 'Usuario',
            'updatedBy' => $this->basePath . 'Usuario',
        ]);
    }
    
    /**
     * Função para algum tratamento previo do valores a serem inseridos no BD
     * 
     * @author Paulo Watakabe  <watakabe05@gmail.com>
     * @version 1.0
     * @param string $tipo Informa se esta tratando do insert ou update
     */
    public function trataData($tipo) {
        if($tipo == 'insert'){
            if(empty($this->data['default'])){
                $this->data['default'] = '0' ;
            }
        }
        parent::trataData($tipo);
    }
}
