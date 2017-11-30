<?php

/*
 * License GPL .
 * 
 */

namespace Adm\Controller;

/**
 * Description of FornecedorsController
 *
 * @author Danilo Song <danilosong@outlook.com>
 * @since 14-06-2017
 */
class FornecedorsController extends AdmCrudController {

    /**
     * Metodo opcional para alterar a entidade que sera usada no form na action edit podendo alterar os seguintes parametros
     *     dataview para alterar parametros para phtml
     *     form     para alterar o formulario a ser renderizado
     *     request  para alterar e verificar fluxo do action
     *     resul    para alterar parametros para phtml
     *     entity   Entidade em que se esta sendo trabalhada
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0
     * @since 20-06-2017
     * @param array  $dataView
     * @param \Adm\Form\Fornecedor $form
     * @param object $request
     * @param string $resul
     * @param \Adm\Entity\Fornecedor $entity
     */
    public function changeEntityForEdit(&$dataView, $form, $request, &$resul, $entity) {
        if($entity){
            $enderecoArray = $entity->getEndereco('toArray',[4, 'endereco']);
            $form->setData($enderecoArray);
        }
    }
}