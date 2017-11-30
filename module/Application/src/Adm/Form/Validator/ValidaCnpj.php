<?php

namespace Adm\Form\Validator;

/**
 * Validador de Cnpj
 * @author Danilo Dorotheu <danilo.dorotheu@live.com>
 */
class ValidaCnpj extends \Zend\Validator\AbstractValidator {

    const NUMERO = 'numero';

    protected $messageTemplates = array(
        self::NUMERO => "'%value%' CNPJ Inválido!!",
    );
    
    protected $serviceLocator;

    public function isValid($value) {
        $this->setValue($value);

        // Marcação especial para que não faça a validação
        if(strpos($value, "*") !== false){
            return true;
        }
        
        if (!$this->validarCnpj($value)) {
            $this->error(self::NUMERO);
            return false;
        }

        return true;
    }
    /**
     * Validador de CNPJ
     * 
     * @link https://gist.github.com/guisehn/3276302 Autor do código de validacao
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @param string $cnpj Numeros do CNPJ para validacao
     * @return boolean
     */
    function validarCnpj($cnpj) {
        $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
        // Valida tamanho
        if (strlen($cnpj) != 14)
            return false;
        // Valida primeiro dígito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        if ($cnpj{12} != ($resto < 2 ? 0 : 11 - $resto))
            return false;
        // Valida segundo dígito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        return $cnpj{13} == ($resto < 2 ? 0 : 11 - $resto);
    }

}
