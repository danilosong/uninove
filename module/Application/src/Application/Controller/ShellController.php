<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Application\Conexao\Mysql;

class ShellController extends AbstractActionController {

    /**
     *
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;
    protected $service;
    protected $moduleName;
    protected $param;
    
    public $controller;

    /**
     * @var \Application\Service\Email 
     */
    private $email;

    /**
     * @var \Zend\View\Model\ViewModel
     */
    private $view;

    /**
     * @var \Zend\View\Renderer\PhpRenderer
     */
    private $renderer;

    public function __construct($module = "Application") {
        $this->moduleName = ucfirst($module);
        $this->service = "\\" . $this->moduleName . "\Service\\Shell";
        $this->controller = get_class($this);
    }

    public function indexAction() {
        echo "hello", "\r\n";
    }

    /**
     * Seta todos os users offline que estão inativos no sistema por mais de 3 min
     * Adicionar esta linha no crontab do servidor
     * # m  h  dom mon dow   command
     * 03  *    *   *   *   php /var/www/tcmed/public/index.php setOffline
     * 03  *    *   *   *   /usr/bin/php5 /var/www/tcmed/public/index.php setOffline
     */
    public function setofflineAction() {
        $userRep = $this->getEm()->getRepository("\Application\Entity\User");
        $datetime = new \DateTime('now');
        $datetime->sub(new \DateInterval('PT3M'));
        $userRep->setAllInactiveOffLine($datetime);
    }

    /**
     * 
     * @return \Application\Service\Shell
     */
    public function getService() {
        if (is_string($this->service)) {
            $this->service = new $this->service($this->getEm());
            $this->service->setController($this);
        }
        return $this->service;
    }

    /**
     * Pega ou cria a instancia do DoctrineManage
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEm() {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->em;
    }

    /**
     * Pega uma instancia do Param Helper
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 13-01-2016
     * @return \Application\View\Helper\Param
     */
    public function getParam() {
        if (null === $this->param) {
            $this->param = $this->getServiceLocator()
                    ->get('ViewHelperManager')
                    ->get('Param');
        }
        return $this->param;
    }

    /**
     * Retorna ou gera e retorna uma instancia do serviço de email para enviar um email
     * Usa o service locator para carregar as dependencias e configura o serviço com dados default
     * 
     * @author PauloWatakabe <watakabe05@gmailcom>
     * @since 04-02-2016
     * @return \Application\Service\Email
     */
    public function getEmail() {
        if (is_null($this->email)) {
            $transport = $this->getServiceLocator()->get('Application\Mail\Transport');
            
            

            $renderer = new \Zend\View\Renderer\PhpRenderer();
            $resolver = new \Zend\View\Resolver\AggregateResolver();

            $renderer->setResolver($resolver);

            $map = new \Zend\View\Resolver\TemplateMapResolver(array(
                'layout'      => __DIR__ . '/view/layout.phtml',
                'index/index' => __DIR__ . '/view/index/index.phtml',
            ));
            $stack = new \Zend\View\Resolver\TemplatePathStack(array(
                'script_paths' => array(
                    __DIR__ . '/../../../view'
                )
            ));

            $resolver->attach($map)->attach($stack);
            
            
            
            $this->view = new \Zend\View\View();
            $this->view->addRenderingStrategy(function()use($renderer){
                return $renderer;
            }, 1);
//            $this->view->setTerminal(TRUE);
            $this->email = new \Application\Service\Email($this->getEm(), $transport, $this->view);
            $this->email->setController($this);
            $this->email->setTemplate($this->params('action'));
        }
        return $this->email;
    }

    public function setDebugOnAction() {
        $mysql = new Mysql();
        $q = "CREATE TABLE IF NOT EXISTS msg_debug(
                id_msg_debug INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
                identify VARCHAR(300) NULL,
                tabela VARCHAR(300) NULL,        
                msg VARCHAR(300) NULL,
                data_hora TIMESTAMP NOT NULL DEFAULT NOW()
            );
            DROP PROCEDURE IF EXISTS debug;

            CREATE PROCEDURE debug(
                IN pross_identify VARCHAR(300),
                IN name_table VARCHAR(300),
                IN menssage VARCHAR(700)
                )
            BEGIN
                INSERT INTO msg_debug
                    (identify, tabela, msg)
                VALUES
                    (pross_identify, name_table, menssage);     
            END ;

            ";
        $mysql->q($q);
        echo 'Debug esta ligado agora', PHP_EOL;
    }

    public function setDebugOffAction() {
        $mysql = new Mysql();
        $q = "DROP PROCEDURE IF EXISTS debug;
            CREATE PROCEDURE debug(
                IN pross_identify VARCHAR(300),
                IN name_table VARCHAR(300),
                IN menssage VARCHAR(700)
                )
            BEGIN
            END ;
            ";
        $mysql->q($q);
        echo 'Debug esta desligado agora', PHP_EOL;
    }

    public function setGenerateAction() {
        $mysql = new Mysql();
        $fileSQL = '/home/user/ownCloud/Projeto_tcmed_files/Modelagem Dados/Stored Procedures/storeprocedure.query';
        if (file_exists($fileSQL)) {
            $q = file_get_contents($fileSQL);
            $mysql->q($q);
            echo 'Store Procedures Atualizadas', PHP_EOL;
        } else {
            echo 'Erro ao gerar arquivo storeprocedure.query', PHP_EOL;
        }
    }

}
