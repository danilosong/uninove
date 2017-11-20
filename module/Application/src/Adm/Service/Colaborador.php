<?php

namespace Adm\Service;

use Doctrine\ORM\EntityManager;

/**
 * Description of Colaborador
 *
 * @author Danilo Song <danilosong@outlook.com>
 * @since 25/05/2017
 */
class Colaborador extends AdmAbstractService{

    public function __construct(EntityManager $em, $fatherService = FALSE) {
        parent::__construct($em, $fatherService);
        $this->basePath = 'Adm\Entity\\';

        $this->entity = $this->basePath . "Colaborador";

        $this->setDataRefArray([
            'ref_createdBy' => '\Application\Entity\Usuario',
            'ref_updatedBy' => '\Application\Entity\Usuario',
            'ref_usuario'   => '\Application\Entity\Usuario',
        ]);

    }
    
     /**
     * 
     * @author Paulo Watakabe  <watakabe05@gmail.com>
     * @version 1.0
     * @since 01-06-2017
     * @param array $data
     * @param \Adm\Entity\Colaborador $colaborador
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function pesquisa($data) {
        $filters = [];
        $this->setFilter('id', $filters, $data['colaborador']);
        return $this->getRepository()->pesquisa($filters);
    }
    
}
