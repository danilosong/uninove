<?php
/*
 * License GPL .
 * 
 */

namespace Application\Service;

use Doctrine\ORM\EntityManager;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Application\Mail\Mail;

/**
 * O Basico para enviar email
 * Atualizada para enviar anexo
 *
 * @author Paulo Cordeiro Watakabe <watakabe05@gmail.com>
 * @since 05-02-2016
 * @version 1.2
 */
class Email extends \Application\Service\AbstractService
{

    /**
     * Classe responsavel pelo envio(transporte) do email
     * 
     * @var \Zend\Mail\Transport\Smtp
     */
    protected $transport;
    
    /**
     * Classe responsavel por renderizar o body do email
     *
     * @var \use Zend\View\View;
     */
    protected $view;
    
    /**
     * Endereço de email a ser enviado
     *
     * @var string 
     */
    protected $to;
    
    /**
     * Lista de endereço que tem copia 
     * Duas opções:
     * 1º somente a lista de email em um array numerico
     * 2º Para colocar o nome do dono do email deve ser enviado [email => nome]
     *    ex: ['watakabe05@gmail.com' => 'Japa']
     *
     * @var array
     */
    protected $cc;
    
    /**
     * Lista de endereço que tem copia oculta
     * Duas opções:
     * 1º somente a lista de email em um array numerico
     * 2º Para colocar o nome do dono do email deve ser enviado [email => nome]
     *    ex: ['watakabe05@gmail.com' => 'Japa']
     *
     * @var array 
     */
    protected $cco;
    
    /**
     * Template a ser rederizado default nome da action.
     *
     * @var string 
     */
    protected $template;
    
    /**
     * Caminho onde se encontra o template default o modulo/controller/
     *
     * @var string 
     */
    protected $templatePath;
    
    /**
     * Faz o registro no log do envio do email default true
     *
     * @var boolean 
     */
    protected $log;
    
    /**
     * Opcional alguma observação a ser inserida no log
     *
     * @var string
     */
    protected $obs = '';
    
    /**
     * Classe que faz o envio do email
     * 
     * @var \Application\Mail\Mail 
     */
    protected $mail;
    
    /**
     * Configura o serviço e liga o log de envio de email
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 07-02-2016
     * @param EntityManager $em  Usado para acessar o BD e é item da configuração basica
     * @param \Zend\Mail\Transport\Smtp $transport Faz o tranport do email
     * @param \Zend\View\View  $view Rederizador para o body do email
     */
    public function __construct(EntityManager $em, SmtpTransport $transport, $view) 
    {
        parent::__construct($em);
        $this->transport = $transport;
        $this->view = $view;        
        $this->log = true;
    }
    
    /**
     * Liga ou desliga o log de email.
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 07-02-2016
     * @param boolean $log
     * @return \Application\Service\Email
     */
    public function setLog($log) {
        $this->log = $log;
        return $this;
    }
    
    /**
     * Retorna a obs incremental do log
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 07-02-2016
     * @return string
     */
    public function getObs() {
        return $this->obs;
    }

    /**
     * Altera a observação a ser colocada no log
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 07-02-2016
     * @param string $obs
     * @return \Application\Service\Email
     */
    public function setObs($obs) {
        $this->obs = $obs;
        return $this;
    }
    
    /**
     * Faz o envio do email que esta configurado no array
     * Atributos do $dataEmail
     *   'to'       => string required
     *   'toName'   => string
     *   'subject'  => string
     *   'cc'       => array of emails
     *   'cco'      => array of emails
     *   'anexos'   => array of path
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 07-02-2016
     * @param array $dataEmail Faz o envio do email que esta configurado no array
     * @param string $template Altera o template do body do email default action name
     */    
    public function enviaEmail(array $dataEmail, $template='') {
        if(!empty($template)){
            $this->template = $template;
        }
        try {
            $this->mail = new Mail($this->transport, $this->view);
            
            $this->setMailTo($dataEmail);
            
            $this->mail->setSubject($dataEmail['subject'])
                ->setData($dataEmail)
                ->prepare($this->templatePath, $this->template, (isset($dataEmail['anexos'])) ? $dataEmail['anexos'] : []);
            
            $this->mail->send();
            
            if($this->log){
                $obs =  'Enviou email para ' . $dataEmail['to'] . ' data ' . (new \DateTime('now'))->format('d/m/Y h:i');
                (new AppLog($this->em,  $this))->AddLog($this->getController()->controller, $this->template, $obs, '', '', $this->obs);
            }
        } catch (Exception $e) {
            die(print_r($e, true));
        }
    }
    
    /**
     * Altera o template padrão do body do email
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 07-02-2016
     * @param string $template
     * @return \Application\Service\Email
     */
    public function setTemplate($template) {
        $this->template = $template;
        return $this;
    }
        
    /**
     * Altera o caminho padrão de onde esta o template
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 07-02-2016
     * @param string $templatePath
     * @return \Application\Service\Email
     */
    public function setTemplatePath($templatePath) {
        $this->templatePath = $templatePath;
        return $this;
    }
    
    /**
     * Uso interno do serviço
     * Seta as endereços que vão receber este email
     * sendo que quando cc ou cco não for um indice numero a string vai ser utilizada como nome do endereço.
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 07-02-2016
     * @param array $dataEmail
     */
    public function setMailTo(&$dataEmail) {
        if(!isset($dataEmail['to']) OR empty($dataEmail['to'])){
            echo '<h3>Não foi parametrizado para quem esse email deve ser enviado index to nao existe!!!';
            die;
        }
        $name = isset($dataEmail['toName']) ? $dataEmail['toName'] : null;
        $this->to = $dataEmail['to'];
        $this->mail->getMessage()->addto($dataEmail['to'], $name);
        if(isset($dataEmail['cc'])){
            $this->cc = $dataEmail['cc'];
            if(is_array($dataEmail['cc'])){
                foreach ($dataEmail['cc'] as $key => $value) {
                    if(empty($value)) {
                        continue;
                    }
                    
                    $namecc = is_numeric($key) ? NULL : $key;
                    $this->mail->getMessage()->addCc($value, $namecc); 
                }
            }
            if(is_string($dataEmail['cc']) and !empty($dataEmail['cc'])){
                $this->mail->getMessage()->addCc($dataEmail['cc']); 
            }
        }
!isset($dataEmail['cco']) && $dataEmail['cco'] = 'paulo@tcmed.com.br';
        if(isset($dataEmail['cco'])){
            $this->cco = $dataEmail['cco'];
            if(is_array($dataEmail['cco'])){
                foreach ($dataEmail['cco'] as $key => $value) {
                    $namecc = is_numeric($key) ? NULL : $key;
                    $this->mail->getMessage()->addBcc($value, $namecc); 
                }
            }
            if(is_string($dataEmail['cco'])){
                $this->mail->getMessage()->addBcc($dataEmail['cco']); 
            }
        }
    }
    
}
