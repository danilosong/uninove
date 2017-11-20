<?php

namespace Application\Service;

use Doctrine\ORM\EntityManager;
/**
 * Description of ChamadoResposta
 *
 * @author Danilo Song <danilosong@outlook.com>
 * @date   12-09-2017
 */
class ChamadoResposta extends AbstractService{       
    
    public function __construct(EntityManager $em, $fatherService = FALSE) {
        parent::__construct($em, $fatherService);
        $this->basePath = 'Application\Entity\\';

        $this->entity = $this->basePath . "ChamadoResposta";

        $this->setDataRefArray([
            'ref_createdBy'         => '\Application\Entity\Usuario',
            'ref_updatedBy'         => '\Application\Entity\Usuario',
            'ref_chamadoResposta'   => '\Application\Entity\ChamadoResposta',
            'ref_chamado'           => '\Application\Entity\Chamado',
        ]);
    }
    
    public function insert(array $data = array()) {
        $result = parent::insert($data);
        if(is_object($result)){
            $this->execBackJobs($result);
        }
        return $result;
    }
    
    public function update(array $data = array()) {
        $result = parent::update($data);
        if(is_object($result)){
            $this->execBackJobs($result);
        }
        return $result;
    }
    
    /**
     * 
     * @param \Application\Entity\ChamadoResposta $chamadoResposta
     */
    public function execBackJobs(\Application\Entity\ChamadoResposta $chamadoResposta) {
        /* @var $srvChamado \Application\Service\Chamado */
        $srvChamado = $this->getService('\Application\Service\Chamado');
        // Alterar o status de chamado
        $srvChamado->upgradeFromResposta($chamadoResposta);    
        $this->sendEmailAlert($chamadoResposta);
    }
    
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @since 17-10-2017
     * @version 1.0
     * @param array $data
     * @return arrayCollection
     */
    public function pesquisa($data) {
        $filters = $this->getBasicaFilters($data);
        /* @var getRepository \Application\Entity\Repository\ChamadoRespostaRepository */
        return $this->getRepository()->pesquisa($filters);
    }
    
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @since 17-10-2017
     * @version 1.0
     * @param array $arrayData
     * @return array
     */
    public function getBasicaFilters($arrayData = []) {
        $filter = [];
        $this->setFilter('chamado', $filter, $arrayData);
        return $filter;
    }
 
    /**
     * Função para algum tratamento previo do valores a serem inseridos no BD
     * Executada antes da validação dos dados
     *
     * @author Danilo Song  <danilosong@outlook.com>
     * @version 1.0
     * @date 18-10-2017
     * @param string $tipo Informa se esta tratando do insert ou update
     */
    public function trataData($tipo) {
        parent::trataData($tipo);
        
        if($tipo == "insert"){
            if(empty($this->data['anexoPath'])){
                $this->data['anexoPath'] = ["-"];
            }
            if($this->data['copiaPara'] == "{}"){
                $this->data['copiaPara'] = ["-"];
            }
            /* @var $chamado \Application\Entity\Chamado */
            $chamado = $this->em->find('\Application\Entity\Chamado', $this->data['chamado']);
            if($this->data['chamado']){
                $dataChamado = new \DateTime($chamado->getCreatedAt('Y/m/d H:i:s'));
                $createdAt   = new \DateTime('now');
                $horas       = $dataChamado->diff($createdAt);
                
                $this->data['horas'] = $horas->format('%H:%i');
            }
        }
    }
    
    /**
     * Função que envia email de acordo com os dados que estão no copiaPara do chamadoResposta
     *
     * @author Danilo Song  <danilosong@outlook.com>
     * @version 1.0
     * @date 18-10-2017
     * @param \Application\Entity\ChamadoResposta $chamadoResposta
     */
    public function sendEmailAlert(\Application\Entity\ChamadoResposta $chamadoResposta) {
        $emails  = $chamadoResposta->getCopiaPara();
        $options = [];
        foreach ($emails as $to => $email){
            if(!isset($options['to'])){
                      $options['to']     = $email;
//                      $options['toName'] = $to;
                      
                continue;
            }
            $options["cc"][$to]    = $email;
        }
        $options["subject"]  = "Chamado: ".$chamadoResposta->getChamado('titulo')." Respondido por ". $chamadoResposta->getCreatedBy('nome');
        $options["data"]     = ["entity" => $chamadoResposta];

        $senderEmail = $this->getController()->getEmail();
        $senderEmail->enviaEmail($options, 'email-chamado-resposta');
    }
}
