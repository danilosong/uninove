<?php

/*
 * To change this license 
 */

namespace Application\Service;

use Doctrine\ORM\EntityManager;

/**
 * Description of TableConfigPersonal
 *
 * @author Paulo Watakabe <watakabe05@gmail.com>
 * @since 10-07-2016
 */
class TableConfigPersonal extends AbstractService {

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

        $this->entity = $this->basePath . "TableConfigPersonal";

        $this->setDataRefArray([
            'ref_tableConfig' => $this->basePath . "TableConfig",
            'ref_tableConfigColun' => $this->basePath . "TableConfigColun",
            'ref_usuario' => $this->basePath . "Usuario",
            'ref_role' => $this->basePath . "AppRole",
            'createdBy' => $this->basePath . 'Usuario',
            'updatedBy' => $this->basePath . 'Usuario',
        ]);
    }

    /**
     * Trata os dados antes de persisti-los
     * 
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @since 15-07-2016
     * @param string $tipo
     */
    public function trataData($tipo) {
        // Caso seja definido um usuário, então ignorar role. Se definido role,
        // ignorar o usuário
        if (empty($this->data['usuario']['idUsuario'])) {
            $this->data['usuario'] = null;
        } else {
            $this->data['role'] = null;
        }
    }

}
