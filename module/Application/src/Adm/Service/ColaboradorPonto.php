<?php

namespace Adm\Service;

use Doctrine\ORM\EntityManager;

/**
 * Description of ColaboradorPonto
 *
 * @author Danilo Song <danilosong@outlook.com>
 * @since 26/05/2017
 */
class ColaboradorPonto extends AdmAbstractService{

    public function __construct(EntityManager $em, $fatherService = FALSE) {
        parent::__construct($em, $fatherService);
        $this->basePath = 'Adm\Entity\\';

        $this->entity = $this->basePath . "ColaboradorPonto";

        $this->setDataRefArray([
            'ref_createdBy'   => '\Application\Entity\Usuario',
            'ref_updatedBy'   => '\Application\Entity\Usuario',
            'ref_colaborador' => '\Adm\Entity\Colaborador',
        ]);
    }

    /**
     * Função para algum tratamento previo do valores a serem inseridos no BD
     * Executada antes da validação dos dados
     *
     * @author Paulo Watakabe  <watakabe05@gmail.com>
     * @version 1.0
     * @param string $tipo Informa se esta tratando do insert ou update
     */
    public function trataData($tipo) {
        if($tipo == 'insert'){
            $this->data['ip'] = $_SERVER["REMOTE_ADDR"];
            $this->data['hora'] = date('H:i');
            $this->data['data'] = date('d/m/Y');
            $this->data['colaboradorNome'] = $this->name('colaboradorNome');
        }
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
    public function pesquisa($data, $colaborador=null) {
        
        $filters = [];
        $this->setFilter('data', $filters, $data);
        if(isset($data['colaboradorPonto'])){
            $this->setFilter('colaborador', $filters, $data['colaboradorPonto']);
        }
        
        // Filtra somente os pontos desse colaborador
        if($colaborador AND !$colaborador->getUsuario()->getRole(TRUE)->getIsAdmin()){
            $filters['colaborador'] = $colaborador->getId();
        }
        // Converter uma string de data para um objeto datetime
        if(isset($filters['data'])){
            $filters['data'] = $this->strToDate($filters['data']);
        }
        if(isset($data['filtro1']) and isset($data['filtro2'])){
            if(!empty($data['filtro1']) and !empty($data['filtro2'])){
                $ini = $this->strToDate($data['filtro1']);
                $fim = $this->strToDate($data['filtro2']);
                $filters['data'] = ['between' => [$ini, $fim]];
            }            
        }
        return $this->getRepository()->pesquisa($filters);
    }
    
    /**
     * 
     * @author Danilo Song  <danilosong@outlook.com>
     * @version 1.0
     * @since 06-06-2017
     * @param array $data
     * @param \Adm\Entity\Colaborador $colaborador
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getRelatorio($data) {
        $filters = [];
            $this->setFilter('colaborador', $filters, $data['colaboradorPonto']);
        return $this->getRepository()->getRelatorio($filters);
    }
}
