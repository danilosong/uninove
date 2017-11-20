<?php

namespace Application\Service;

use Doctrine\ORM\EntityManager;
/**
 * Description of Chamado
 *
 * @author Danilo Song <danilosong@outlook.com>
 * @date   12-09-2017
 */
class Chamado extends AbstractService{       
    
    public function __construct(EntityManager $em, $fatherService = FALSE) {
        parent::__construct($em, $fatherService);
        $this->basePath = 'Application\Entity\\';

        $this->entity = $this->basePath . "Chamado";

        $this->setDataRefArray([
            'ref_createdBy' => '\Application\Entity\Usuario',
            'ref_updatedBy' => '\Application\Entity\Usuario',
        ]);
    }
    
    /**
     * Função para algum tratamento previo do valores a serem inseridos no BD
     * Executada antes da validação dos dados
     *
     * @author Danilo Song  <danilosong@outlook.com>
     * @version 1.0
     * @date 21-09-2017
     * @param string $tipo Informa se esta tratando do insert ou update
     */
    public function trataData($tipo) {
        parent::trataData($tipo);
        if(empty($this->data['situacao'])){
            $this->data['situacao'] = '1';
        }
        
        if($tipo == "insert"){
            
            $this->data['prazo'] = new \DateTime('now');
            $prioridade = $this->data['prioridade'] ?? false;
            switch ($prioridade) {
                case '1':
                    $this->data['prazo']->add(new \DateInterval('P2D'));
                    $this->data['horas'] = 2880;
                    break;
                case '2':
                    $this->data['prazo']->add(new \DateInterval('P1D'));
                    $this->data['horas'] = 1440;
                    break;
                case '3':
                    $this->data['prazo']->add(new \DateInterval('PT6H'));
                    $this->data['horas'] = 360;
                    break;
                case '4':
                    $this->data['prazo']->add(new \DateInterval('P0DT0H'));
                    break;
            }
        }
    }
    
    /**
     * Atualiza a situação, finalizado e horasTotal do chamado quando é feito a resposta 
     * no chamadoResposta
     * 
     * @author Danilo Song <danilosong@outlook.com>
     * @version 1.0
     * @since 10-10-2017
     * @param \Application\Entity\ChamadoResposta $chamadoResposta
     */
    public function upgradeFromResposta(\Application\Entity\ChamadoResposta $chamadoResposta) {
        $data['id']               = $chamadoResposta->getChamado('id');
        $data['situacao']         = $chamadoResposta->getSituacao();
        $dataChamado['createdAt'] = $chamadoResposta->getChamado()->getCreatedAt('Y/m/d H:i:s');
        $dataChamado['createdAt'] = new \DateTime($dataChamado['createdAt']);
        if($chamadoResposta->getSituacao() == 3 ) { // finalizado
            $data['finalizado'] = new \DateTime('now');
            $data['horasTotal'] = $dataChamado['createdAt']->diff($data['finalizado']);
            $resul = $this->update([
                'id'            => $data['id'],
                'situacao'      => $data['situacao'],
                'finalizado'    => $data['finalizado'],
                'horasTotal'    => $data['horasTotal']->format('%H:%i'),
                    ]);
        }else{
            $resul = $this->update([
                'id'            => $data['id'],
                'situacao'      => $data['situacao'],
                    ]);
            
        }
        if(is_array($resul)){
            $msg = 'Houve erro ao tentar atualizar o chamado' . implode(' | ', $resul);
        }else{
            $msg = 'Salvo com sucesso!!';
        }      
        $this->showMessage($msg);
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
     * @param \Application\Entity\Chamado $chamado
     */
    public function execBackJobs(\Application\Entity\Chamado $chamado) {  
        $this->trySaveAnexo($chamado);
        $this->sendEmailAlert($chamado);
    }
    
    /**
     * Salva o anexo na pasta do chamado e acrescenta caminho na entidade.
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @since 19-10-2017
     * @version 1.0
     * @param \Application\Entity\Chamado $chamado
     * @return nothing
     */
    public function trySaveAnexo(\Application\Entity\Chamado $chamado) {      
        $parametroPath = $this->em->getRepository('\Application\Entity\Parametros')->findOneBy(['chave' => 'path_chamado', 'conteudo' => 'path']);
        if(is_null($parametroPath)){
            $this->showMessage('Não foi encontrado o parametro para local do arquivo path_chamado');
            return;
        }
        $path = $parametroPath->getDescricao() . $chamado->getId() . DIRECTORY_SEPARATOR ;            
        // nao existe o diretorio tenta criar
        if(!file_exists($path) AND !mkdir($path, 0777, TRUE)){
            $error = error_get_last();
            $this->showMessage("Não foi possivel criar o diretorio '" . $path . "'" . $error['message']);
            return;
        }
        $this->data["anexoPath"] = $this->saveFile('anexoPath', [
            'path'       => $path,
//            'name'       => "ppraVigFoto_",
            'pref'       => '',
            'validators' => [
                new \Zend\Validator\File\Size(array('max' => 15360000)) //MAX 15mb filesize
            ]
        ]);
        if(is_array($this->data["anexoPath"])){
            $this->showMessage(implode('\n', $this->data["anexoPath"]));  
            return;
        }
        $anexoPath = $path . $this->data["anexoPath"];
        $anexos = $chamado->getAnexoPath();
        $key    = array_search($anexoPath, $anexos);
        if(!$key){
            $anexos[] = $anexoPath;
            $chamado->setAnexoPath($anexos);
            $this->em->flush($chamado);
        }
    }
    
    /**
     * Função que envia email de acordo com os dados que estão no copiaPara do chamado
     *
     * @author Danilo Song  <danilosong@outlook.com>
     * @version 1.0
     * @date 19-10-2017
     * @param \Application\Entity\Chamado $chamado
     */
    public function sendEmailAlert(\Application\Entity\Chamado $chamado) {
        $emails  = $chamado->getCopiaPara();
        $options = [];
        foreach ($emails as $to => $email){
            if(!isset($options['to'])){
                      $options['to']     = $email;
//                      $options['toName'] = $to;
                      
                continue;
            }
            $options["cc"][$to]    = $email;
        }
        $options["subject"]  = "Aberto chamado: ".$chamado->getTitulo();
        $options["data"]     = ["entity" => $chamado];

        $senderEmail = $this->getController()->getEmail();
        $senderEmail->enviaEmail($options, 'email-chamado');
    }
    
}
