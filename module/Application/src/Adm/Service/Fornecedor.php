<?php

namespace Adm\Service;

use Doctrine\ORM\EntityManager;

/**
 * Description of Fornecedor
 *
 * @author Danilo Song <danilosong@outlook.com>
 * @since 14/06/2017
 */
class Fornecedor extends AdmAbstractService{

    public function __construct(EntityManager $em, $fatherService = FALSE) {
        parent::__construct($em, $fatherService);
        $this->basePath = 'Adm\Entity\\';

        $this->entity = $this->basePath . "Fornecedor";

        $this->setDataRefArray([
            'ref_createdBy'   => '\Application\Entity\Usuario',
            'ref_updatedBy'   => '\Application\Entity\Usuario',
            'endereco'        => '\Adm\Entity\Endereco',
        ]);
    }
}
