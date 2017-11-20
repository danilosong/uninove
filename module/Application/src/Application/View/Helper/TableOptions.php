<?php
/*
 * License GPL .
 * 
 */

namespace Application\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;

/**
 * TableOptions
 * Helper para gerar automaticamente as colunas da visualização dos dados do index.phtml
 * 
 * @author Paulo Cordeiro Watakabe <watakabe05@gmail.com>
 * @since 11-07-2016
 */
class TableOptions extends AbstractHelper {

    /**
     * Manipular o banco de dados
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;    

    /**
     * Manipular o helper table
     *
     * @var \Application\View\Helper\Table
     */
    protected $helperTable;
    
    /**
     * Path para entity target
     *
     * @var string
     */
    protected $entityPath;
    
    /**
     * Path para entity target
     *
     * @var array
     */
    protected $user;
    
    /**
     * Dados para a exibiçao do HEAD 
     *
     * @var array
     */
    protected $head;
    
    /**
     * Opções de configuração para cada td gerado
     *
     * @var array
     */
    protected $tdOpt;
    
    /**
     * Lista de getter para serem feito a ordem carregada
     *
     * @var array
     */
    protected $methods;
    
    /**
     * Lista de parametros para ser usado em cada getter
     *
     * @var array
     */
    protected $param;
    
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 12-02-2016
     * @param type $sm
     */
    public function __construct(\Doctrine\ORM\EntityManager $em, \Application\View\Helper\Table $table) {
        $this->em = $em;
        $this->helperTable = $table;
    }    
    
    /**
     * Metodo invoke chamado pela view ao executar esta classe 
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 11-07-2016
     * @return \Application\View\Helper\TableOptions
     */
    public function __invoke() {
        return $this;
    }
    
    /**
     * Retorna o caminho da classe
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 11-07-2016
     * @return string
     */
    public function __toString() {
        return '\Application\View\Helper\TableOptions';
    }
    
    /**
     * Path para entity target
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 11-07-2016
     * @param string $path
     * @return \Application\View\Helper\TableOptions
     */
    public function setEntityPath($path) {
        $this->entityPath = $path;
        return $this;
    }
    
    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 11-07-2016
     * @param array $user
     * @return \Application\View\Helper\TableOptions
     */
    public function setUser($user) {
        $this->user = $user;
        return $this;
    }

    /**
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 11-07-2016
     * @return \Application\View\Helper\Table
     */
    public function getHelperTable() {
        return $this->helperTable;
    }
    
    /**
     * Metodo Principal para coleta de informações sobre a tabela , colunas , metods, parametros, css, js gravadas no BD
     * Faz diferenciação de usuario , role e defaul nesta ordem.
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 11-07-2016  
     * @return array Com os dados coletados para th
     * @throws \Exception Usuario não setado
     * @throws \Exception Dado em table config nao encontrado
     * @throws \Exception Dado em table config coluns nao encontrado
     */
    public function getHead() {
        /* @var $srv \Application\Entity\Repository\TableConfigPersonalRepository */
        if(!is_array($this->user)){
            throw new \Exception("A configuração do usuario não foi encontrada.");
        }
        $tableConfig = $this->em->getRepository('\Application\Entity\TableConfig')->findOneBy(['entityPath' => $this->entityPath]);
        if(is_null($tableConfig)){
            throw new \Exception("A configuração da entidade " . $this->entityPath . " não foi encontrada.");
        }
        $srv = $this->em->getRepository('\Application\Entity\TableConfigPersonal');
        // procura costomização para o usuario
        $tableConfigPersonal = $srv->getTableConfigByUser($tableConfig->getId(), $this->user['id']);
        // procura costomização para o papel
        if(empty($tableConfigPersonal)){
            $role = $this->em->getRepository('\Application\Entity\AppRole')->findOneBy(['nome' => $this->user['role']]);
            $tableConfigPersonal = $srv->getTableConfigByRole($tableConfig->getId(), $role->getId());
        }
        // procura costomização para o default
        if(empty($tableConfigPersonal)){
            $tableConfigColuns = $this->em->getRepository('\Application\Entity\TableConfigColun')->findBy(['tableConfig' => $tableConfig->getId(), 'default' => '1']);
        }  else {
            $tableConfigColuns = $this->extractColunsOf($tableConfigPersonal);
        }
        if(is_null($tableConfigColuns)){
            throw new \Exception("A configuração das colunas para entidade " . $this->entityPath . " não foi encontrada.");
        }
        return $this->makeHead($tableConfig, $tableConfigColuns);
    }
    
    /**
     * Extrair da personalização a configuração das colunas na ordem escolhida
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 12-07-2016  
     * @param array $tableConfigPersonal Array collection of \Application\Entity\TableConfigPersonal
     * @return array Array collection of \Application\Entity\TableConfigColun
     */
    public function extractColunsOf($tableConfigPersonal) {
        /* @var $personal \Application\Entity\TableConfigPersonal */
        $tableConfigColuns = [];
        foreach ($tableConfigPersonal as $personal) {
            $tableConfigColuns[] = $personal->getTableConfigColun();
        }
        return $tableConfigColuns;
    }
    
    /**
     * Faz a montagem e organização dos dados para montagem do head e parametros para body
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 12-07-2016  
     * @param \Application\Entity\TableConfig $tableConfig
     * @param array $tableConfigColuns Array collection of \Application\Entity\TableConfigColun
     * @return array Com os dados para montagem do Table head
     */
    public function makeHead(\Application\Entity\TableConfig $tableConfig, $tableConfigColuns) {
        /* @var $tableConfigColun \Application\Entity\TableConfigColun */
        if(!empty($tableConfig->getCaption())){
            $this->helperTable->renderCaption($tableConfig->getCaption());
        }
        $this->head    = [];
        $this->methods = [];
        $this->tdOpt   = [];
        foreach ($tableConfigColuns as $key => $tableConfigColun) {
            $this->head[] = [
                  'label'   => $tableConfigColun->getLabel()
                , 'options' => $tableConfigColun->getThOption()   
                , 'order'   => $tableConfigColun->getOrder()   
                , 'css'     => $tableConfigColun->getThCss()
                , 'js'      => $tableConfigColun->getThJs()
            ];
            $this->methods[] = $tableConfigColun->getMethod();
            $this->tdOpt[]   = $tableConfigColun->getTdLine();
            $this->param[]   = is_array($tableConfigColun->getParam()) ? $tableConfigColun->getParam() : [];
            if($tableConfigColun->getLabel() == 'Ação'){
                $this->helperTable->setEditLine($key);
            }
        }
        return $this->head;
    }

    /**
     * Utilizando o helper table faz a rendirazação do:
     *   caption 
     *   table head 
     *   configura o tdOpt 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 12-07-2016  
     * @param array $options Opcional e sem utilidade nesta versão
     */
    public function renderThead($options=[]) {        
        is_null($this->head) && $this->head = $this->getHead();
        // Seta a opção de cada td gerado
        $this->helperTable->setTdopt($this->tdOpt);
        // Gera todo os th da tabela com seus parametros.
        $this->helperTable->renderThead($this->head);
    }

    /**
     * Utilizando o helper faz a renderização dos dados
     *   Dados que foram carregados baseado em configuração do usuario e role(papel).
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0  
     * @since 12-07-2016  
     * @since 14-10-2016 incluir variavel $param  
     * @param object $entity Entidade a ser extraida os dados.
     * @param string $param Opcional parametro a ser inserido no tr.
     */
    public function renderLine($entity, $param='') {
        $data = [];
        foreach ($this->methods as $key => &$method) {
            $data[] = call_user_func_array([$entity, $method], $this->param[$key]);
        }
        $this->helperTable->renderLine($data, $param);
    }
    
}
