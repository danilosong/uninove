<?php

namespace Adm\Form\Validator;

/**
 * Validador de Cnpj
 * @author Danilo Dorotheu <danilo.dorotheu@live.com>
 */
class ValidaPis extends \Zend\Validator\AbstractValidator {

    const NUMERO = 'numero';

    protected $messageTemplates = array(
        self::NUMERO => "'%value%' PIS Inválido!!",
    );
    protected $serviceLocator;

    public function isValid($value) {
        $this->setValue($value);

        if (!$this->validarPis($value)) {
            $this->error(self::NUMERO);
            return false;
        }

        return true;
    }

    /**
     * Função para validar PIS (Programa de Integração Social)
     *
     * @link    http://www.clubedainformatica.com.br/site/2007/11/11/algoritmo-do-pis-programas-de-integracao-social/
     * @param     string $pis PIS que deseja validar
     * @return    bool true caso seje válido, false caso não seje válido
     */
    function validarPis($pis) {
        $pisLimpo = preg_replace('/[^0-9]/', '', $pis);
        $peso = 3;
        $somatorio = 0;

        if ("00000000000" == $pisLimpo OR empty($pisLimpo)) {
            return FALSE;
        }

        for ($i = 0; $i < 10; $i++) {
            $somatorio += $pisLimpo[$i] * $peso;
            $peso = ($peso == 2) ? 9 : $peso - 1;
        }
        $digito = 11 - ($somatorio % 11);
        if (9 < $digito) {
            $digito = 0;
        }

        return $pisLimpo[10] == $digito;
    }

}
