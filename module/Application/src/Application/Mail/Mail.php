<?php
namespace Application\Mail;

use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Message;
use Zend\View\Model\ViewModel;

use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * O Basico para enviar email
 * Configura o tranporte e a view a ser utilizadas para obody do email.
 *
 * @author Paulo Watakabe <watakabe05@gmail.com>
 * @version 1.0
 * @since 07-02-2016
 */
class Mail {
    
    /**
     *
     * @var \Zend\Mail\Transport\Smtp 
     */
    protected $transport;
    /**
     *
     * @var \Zend\View\View
     */
    protected $view;
    
    /**
     *
     * @var \Zend\Mime\Message 
     */
    protected $body;
    
    /**
     *
     * @var \Zend\Mail\Message 
     */
    protected $message;
    
    /**
     *
     * @var string 
     */
    protected $subject;
    
    /**
     * Contem as variaveis que serão renderizadas na view.
     * 
     * @var Mixed | nothing
     */
    protected $data;
    
    /**
     *
     * @var string 
     */
    protected $to;
    
    /**
     *
     * @var string 
     */
    protected $name;
    
    /**
     *
     * @var array of \Zend\Mime\Part
     */
    protected $parts = [];

    /**
     * Configura o basico para enviar emai.
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 07-02-2016
     * @param \Zend\Mail\Transport\Smtp $transport Classe responsavel pelo envio(tranporte)
     * @param \Zend\View\View $view Classe que fará a renderização do body do email
     */
    public function __construct(SmtpTransport $transport, $view) {
        $this->transport = $transport;
        $this->view = $view;
        $this->message = new Message;
    }
    
    /**
     * Setar o assunto do email
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 07-02-2016
     * @param string $subject Assunto do email
     * @return \Application\Mail\Mail
     */
    public function setSubject($subject) {
        $this->subject = $subject;
        return $this;
    }
    
    /**
     * Destinatario do email
     * Nome do destinatario opcional
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 07-02-2016
     * @param string $to   Email do destinatario
     * @param string $name Opcional
     * @return \Application\Mail\Mail
     */
    public function setTo($to, $name = NULL) {
        $this->to = $to;
        $this->name = $name;
        return $this;
    }
    
    /**
     * Variaveis do body do email a serem rederizadas
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 07-02-2016
     * @param mixed $data Variaveis do body do email
     * @return \Application\Mail\Mail
     */
    public function setData($data) {
        $this->data = $data;
        return $this;
    }
    
    /**
     * Retorna o corpo do email rederizado para fazer o envio.
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 07-02-2016
     * @param string $path   Caminho do template
     * @param string $layout Nome do template
     * @param array $data    Variaveis do template
     * @return void 
     */
    public function renderView($path, $layout, array $data) {
        $model = new ViewModel;
        $model->setTemplate("{$path}{$layout}.phtml");
        $model->setOption('has_parent', true);
        $model->setVariables($data);
        return $this->view->render($model);
    }
    
    /**
     * Atraves da extensão do arquivo seta o type do mine part
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 07-02-2016
     * @param type $extension Extensão do arquivo
     * @return string
     */
    public function getTypeOfExtension($extension) {
        switch ($extension){
            case 'pdf':
                return 'application/pdf';
            case 'png':
                return 'image/png';
            case 'jpg':
                return 'image/jpeg';
            default :
                return 'image/jpeg';
        }        
    }
    
    /**
     * Faz o anexo do arquivo no email
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 07-02-2016
     * @param string $path Caminho absoluto do arquivo
     * @param string $type Tipo do mine part caso omitido pegará  o type atraves da extensão do arquivo
     * @return boolean True caso de sucesso ou falso para erro
     */
    public function addAttachment($path, $type = '') {
        if(!file_exists($path)){
            echo '<pre>Arquivo nao existe para ser anexado ao email ', $path, '</pre>';
            return FALSE;
        }
        $path_parts = pathinfo($path);
        if(empty($type)){
            $type = $this->getTypeOfExtension($path_parts['extension']);
        }
        $attached = new MimePart(fopen($path, 'r'));
        $attached->type = $type;
        $attached->encoding    = \Zend\Mime\Mime::ENCODING_BASE64;
        $attached->disposition = \Zend\Mime\Mime::DISPOSITION_ATTACHMENT;
        $attached->setFileName($path_parts['basename']);
        $this->parts[] = $attached;
        return TRUE;
    }
    
    /**
     * Prepara o email com os dados configurados na classe.
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 07-02-2016
     * @param string $path Caminho para template
     * @param string $layout Nome do template
     * @param array $attachments Com path dos arquivos a serem anexados
     * @return \Application\Mail\Mail
     */
    public function prepare($path, $layout, $attachments = []) {
        $html = new MimePart($this->renderView($path, $layout, $this->data));        
        $html->type = "text/html";
        $this->parts[] = $html;
        
        foreach ($attachments as $attachment) {
            $this->addAttachment($attachment);
        }
        
        $this->body = new MimeMessage();
        $this->body->setParts($this->parts);
        $config = $this->transport->getOptions()->toArray();
        $this->message->addFrom($config['connection_config']['from'])
                ->setSubject($this->subject)
                ->setBody($this->body)
                ->setEncoding('UTF-8');
        
        return $this;        
    }
    
    /**
     * Retorna a classe message para configurações extras
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 07-02-2016
     * @return \Zend\Mail\Message
     */
    public function getMessage() {
        return $this->message;
    }
    
    /**
     * Usa o transport para fazer o envio do email.
     * 
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @version 1.0
     * @since 07-02-2016
     */
    public function send() {
        $this->transport->send($this->message);
    }
    
}

