<?php

/*
 * To change this license 
 */

namespace Application\Service;

use Doctrine\ORM\EntityManager;
use Application\View\Helper\UserIdentity;

/**
 * Description of AppLog
 *
 * @author Paulo Watakabe <watakabe05@gmail.com>
 */
class AppLog extends AbstractService {

    /**
     * Contem dados basico sobre o usuario do sistema
     * @var array  
     */
    protected $user;

    /**
     * metodo construtor 
     * 
     * @param EntityManager $em Obrigatorio para manipular BD
     * @param \Application\Service\AbstractService $fatherService Opcional para sincronizar configuração
     */
    public function __construct(EntityManager $em, $fatherService = FALSE) {
        parent::__construct($em, $fatherService);

        $this->entity = $this->basePath . "AppLog";
        $this->id = 'idLog';

        $this->setDataRefArray([
            'usuario' => $this->basePath . 'Usuario',
        ]);
    }
    
    /**
     * Faz a insersão do log no BD com os parametros abaixo:
     * 
     * @param string $controller    obrigatorio nome do controller
     * @param string $action        obrigatorio nome do action
     * @param string $obs           opcional obs sobre o evento        
     * @param string $tabela        opcional nome da tabela ou entidade
     * @param string $idDaTabela    opcional id do registro afetado  
     * @param string $dePara        opcional os campos do registro que foram afetados 
     */
    public function AddLog($controller, $action, $obs = '', $tabela = '', $idDaTabela = '', $dePara = '') {
        $this->addLogBy([
            'controller'=> $controller,
            'action'    => $action,
            'obs'       => $obs,
            'tabela'    => $tabela,
            'idDaTabela'=> $idDaTabela,
            'dePara'    => $dePara,
        ]);
    }


    /**
     * Faz a insersão do log no BD com os parametros abaixo:
     * 
     * @param array $params Lista contendo as chaves abaixo listadas, com os valores a ser armazenado em log
     * string controller    obrigatorio nome do controller
     * string action        obrigatorio nome do action
     * string obs           opcional obs sobre o evento        
     * string tabelao       opcional nome da tabela ou entidade
     * string idDaTab       opcional id do registro afetado  
     * string deParao       opcional os campos do registro que foram afetados 
     *
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @version 2.0
     * @since 13-07-2016
     * @return array|\Application\Entity\AppLog
     */
    public function addLogBy(array $params = array()) {
        !isset($params['ip']) && isset($_SERVER['REMOTE_ADDR']) && $params['ip'] = $_SERVER['REMOTE_ADDR'];
        !isset($params['ip']) && $params['ip'] = getHostByName(getHostName());;
        
        !isset($params['usuario']) and $params['usuario'] = $this->getUser()['idUsuario'];

        if (isset($params['dePara'])) {
            $aux = "Campo;Antes;Depois|";
            if ($aux == $params['dePara'] or 'force' == $params['dePara']) {
                $params['dePara'] = '';
            }
        }

        return $this->insert($params);
    }

    /**
     * Busca a identidade do usuario do sistema
     * Devolve um array com os dados basico
     * 
     * @return array dados do usuario
     */
    public function getUser() {
        if (!is_null($this->user)) {
            return $this->user;
        }
        $user = new UserIdentity();
        $this->user = $user();
        if(!$this->user){
            return ['idUsuario' => 1];
        }
        return $this->user;
    }

}
