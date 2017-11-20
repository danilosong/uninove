<?php

namespace Adm\Service;

use Doctrine\ORM\EntityManager;

/**
 * Description of Teste
 *
 * @author Danilo Song <danilosong@outlook.com>
 * @since 24/05/2017
 */
class Teste extends AdmAbstractService{

    public function __construct(EntityManager $em, $fatherService = FALSE) {
        parent::__construct($em, $fatherService);
        $this->basePath = 'Adm\Entity\\';

        $this->entity = $this->basePath . "Teste";

        $this->setDataRefArray([
            'ref_createdBy' => '\Application\Entity\Usuario',
            'ref_updatedBy' => '\Application\Entity\Usuario',
        ]);
    }

    /**
     * Verifica 
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0
     * @since 24-05-2017
     * @return boolean
     */
    public function isValidInsert() {
        if ($this->data['tel'] == '1132665850') {
            $this->showMessage("telefone não permitido !!!", "error");
            return false;
        }
        return true;
    }

}
