<?php

namespace Application\Service;

use Zend\Mvc\Controller\Plugin\FlashMessenger as messenger;
use ArrayIterator;
use Zend\Session\Container;
use Zend\Session\ManagerInterface as Manager;
use Zend\Stdlib\SplQueue;

/**
 * Sobrescreve o flashMessenger do zend, adicionando funcoes novas para exibir
 * mensagens diferenciadas na view
 *
 * @author Danilo Dorotheu <danilo.dorotheu@live.com>
 * @since 24-01-2017
 */
class FlashMessenger extends messenger{
    /**
     * Default messages namespace
     */
    const NAMESPACE_DEFAULT = 'default';

    /**
     * Success messages namespace
     */
    const NAMESPACE_SUCCESS = 'success';

    /**
     * Warning messages namespace
     */
    const NAMESPACE_WARNING = 'warning';

    /**
     * Error messages namespace
     */
    const NAMESPACE_ERROR = 'danger';

    /**
     * Info messages namespace
     */
    const NAMESPACE_INFO = 'info';

    /**
     * @var Container
     */
    protected $container;

    /**
     * Messages from previous request
     * @var array
     */
    protected $messages = array();

    /**
     * @var Manager
     */
    protected $session;

    /**
     * Whether a message has been added during this request
     *
     * @var bool
     */
    protected $messageAdded = false;

    /**
     * Instance namespace, default is 'default'
     *
     * @var string
     */
    protected $namespace = self::NAMESPACE_DEFAULT;

    /**
     * Set the session manager
     *
     * @param  Manager        $manager
     * @return FlashMessenger
     */
    public function setSessionManager(Manager $manager) {
        $this->session = $manager;

        return $this;
    }

    /**
     * Retrieve the session manager
     *
     * If none composed, lazy-loads a SessionManager instance
     *
     * @return Manager
     */
    public function getSessionManager() {
        if (!$this->session instanceof Manager) {
            $this->setSessionManager(Container::getDefaultManager());
        }

        return $this->session;
    }

    /**
     * Get session container for flash messages
     *
     * @return Container
     */
    public function getContainer() {
        if ($this->container instanceof Container) {
            return $this->container;
        }

        $manager = $this->getSessionManager();
        $this->container = new Container('FlashMessenger', $manager);

        return $this->container;
    }

    /**
     * Change the namespace messages are added to
     *
     * Useful for per action controller messaging between requests
     *
     * @param  string         $namespace
     * @return FlashMessenger Provides a fluent interface
     */
    public function setNamespace($namespace = 'default') {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * Get the message namespace
     *
     * @return string
     */
    public function getNamespace() {
        return $this->namespace;
    }

    /**
     * Add a message
     *
     * @param  string         $message
     * @param  null|string    $namespace
     * @param  null|int       $hops
     * @return FlashMessenger Provides a fluent interface
     */
    public function addMessage($message, $namespace = null, $hops = 1) {
        $container = $this->getContainer();

        if (null === $namespace) {
            $namespace = $this->getNamespace();
        }

        if (!$this->messageAdded) {
            $this->getMessagesFromContainer();
            $container->setExpirationHops($hops, null);
        }

        if (!isset($container->{$namespace}) || !$container->{$namespace} instanceof SplQueue
        ) {
            $container->{$namespace} = new SplQueue();
        }

        $container->{$namespace}->push($message);

        $this->messageAdded = true;

        return $this;
    }

    /**
     * Add a message with "default" type
     *
     * @param  string         $message
     * @return FlashMessenger
     */
    public function addDefaultMessage($message) {
        $this->addMessage($message, self::NAMESPACE_DEFAULT);

        return $this;
    }

    /**
     * Add a message with "info" type
     *
     * @param  string         $message
     * @return FlashMessenger
     */
    public function addInfoMessage($message) {
        $this->addMessage($message, self::NAMESPACE_INFO);

        return $this;
    }

    /**
     * Add a message with "success" type
     *
     * @param  string         $message
     * @return FlashMessenger
     */
    public function addSuccessMessage($message) {
        $this->addMessage($message, self::NAMESPACE_SUCCESS);

        return $this;
    }

    /**
     * Add a message with "warning" type
     *
     * @param string        $message
     * @return FlashMessenger
     */
    public function addWarningMessage($message) {
        $this->addMessage($message, self::NAMESPACE_WARNING);

        return $this;
    }

    /**
     * Add a message with "error" type
     *
     * @param  string         $message
     * @return FlashMessenger
     */
    public function addErrorMessage($message) {
        $this->addMessage($message, self::NAMESPACE_ERROR);

        return $this;
    }

    /**
     * Whether a specific namespace has messages
     *
     * @param  string         $namespace
     * @return bool
     */
    public function hasMessages($namespace = null) {
        if (null === $namespace) {
            $namespace = $this->getNamespace();
        }

        $this->getMessagesFromContainer();

        return isset($this->messages[$namespace]);
    }

    /**
     * Whether "default" namespace has messages
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @return bool
     */
    public function hasDefaultMessages() {
        return $this->hasMessages(self::NAMESPACE_DEFAULT);
    }

    /**
     * Whether "info" namespace has messages
     *
     * @return bool
     */
    public function hasInfoMessages() {
        return $this->hasMessages(self::NAMESPACE_INFO);
    }

    /**
     * Whether "success" namespace has messages
     *
     * @return bool
     */
    public function hasSuccessMessages() {
        return $this->hasMessages(self::NAMESPACE_SUCCESS);
    }

    /**
     * Whether "warning" namespace has messages
     *
     * @return bool
     */
    public function hasWarningMessages() {
        return $this->hasMessages(self::NAMESPACE_WARNING);
    }

    /**
     * Whether "error" namespace has messages
     *
     * @return bool
     */
    public function hasErrorMessages() {
        return $this->hasMessages(self::NAMESPACE_ERROR);
    }

    /**
     * Get messages from a specific namespace
     *
     * @param  string         $namespace
     * @return array
     */
    public function getMessages($namespace = null) {
        if (null === $namespace) {
            $namespace = $this->getNamespace();
        }

        if ($this->hasMessages($namespace)) {
            return $this->messages[$namespace]->toArray();
        }

        return array();
    }

    /**
     * Get messages from "default" namespace
     *
     * @return array
     */
    public function getDefaultMessages() {
        return $this->getMessages(self::NAMESPACE_DEFAULT);
    }

    /**
     * Get messages from "info" namespace
     *
     * @return array
     */
    public function getInfoMessages() {
        return $this->getMessages(self::NAMESPACE_INFO);
    }

    /**
     * Get messages from "success" namespace
     *
     * @return array
     */
    public function getSuccessMessages() {
        return $this->getMessages(self::NAMESPACE_SUCCESS);
    }

    /**
     * Get messages from "warning" namespace
     *
     * @return array
     */
    public function getWarningMessages() {
        return $this->getMessages(self::NAMESPACE_WARNING);
    }

    /**
     * Get messages from "error" namespace
     *
     * @return array
     */
    public function getErrorMessages() {
        return $this->getMessages(self::NAMESPACE_ERROR);
    }

    /**
     * Clear all messages from the previous request & current namespace
     *
     * @param  string $namespace
     * @return bool True if messages were cleared, false if none existed
     */
    public function clearMessages($namespace = null) {
        if (null === $namespace) {
            $namespace = $this->getNamespace();
        }

        if ($this->hasMessages($namespace)) {
            unset($this->messages[$namespace]);

            return true;
        }

        return false;
    }

    /**
     * Apaga todas as mensagens do namespace Default
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @return bool
     */
    public function clearDefaultMessages() {
        return $this->clearMessages(self::NAMESPACE_DEFAULT);
    }

    /**
     * Apaga todas as mensagens do namespace Info
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @return bool
     */
    public function clearInfoMessages() {
        return $this->clearMessages(self::NAMESPACE_INFO);
    }

    /**
     * Apaga todas as mensagens do namespace Error
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @return bool
     */
    public function clearErrorMessages() {
        return $this->clearMessages(self::NAMESPACE_ERROR);
    }

    /**
     * Apaga todas as mensagens do namespace Warning
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @return bool
     */
    public function clearWarningMessages() {
        return $this->clearMessages(self::NAMESPACE_WARNING);
    }

    /**
     * Apaga todas as mensagens do namespace Success
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @return bool
     */
    public function clearSuccessMessages() {
        return $this->clearMessages(self::NAMESPACE_SUCCESS);
    }

    /**
     * Clear all messages from specific namespace
     *
     * @param  string $namespaceToClear
     * @return bool True if messages were cleared, false if none existed
     */
    public function clearMessagesFromNamespace($namespaceToClear) {
        return $this->clearMessages($namespaceToClear);
    }

    /**
     * Clear all messages from the container
     *
     * @return bool True if messages were cleared, false if none existed
     */
    public function clearMessagesFromContainer() {
        $this->getMessagesFromContainer();
        if (empty($this->messages)) {
            return false;
        }
        unset($this->messages);
        $this->messages = array();

        return true;
    }

    /**
     * Check to see if messages have been added to the current
     * namespace within this request
     *
     * @param  string $namespace
     * @return bool
     */
    public function hasCurrentMessages($namespace = null) {
        $container = $this->getContainer();
        if (null === $namespace) {
            $namespace = $this->getNamespace();
        }

        return isset($container->{$namespace});
    }

    /**
     * Check to see if messages have been added to "default"
     * namespace within this request
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @return bool
     */
    public function hasCurrentDefaultMessages() {
        return $this->hasCurrentMessages(self::NAMESPACE_DEFAULT);
    }

    /**
     * Check to see if messages have been added to "info"
     * namespace within this request
     *
     * @return bool
     */
    public function hasCurrentInfoMessages() {
        return $this->hasCurrentMessages(self::NAMESPACE_INFO);
    }

    /**
     * Check to see if messages have been added to "success"
     * namespace within this request
     *
     * @return bool
     */
    public function hasCurrentSuccessMessages() {
        return $this->hasCurrentMessages(self::NAMESPACE_SUCCESS);
    }

    /**
     * Check to see if messages have been added to "warning"
     * namespace within this request
     *
     * @return bool
     */
    public function hasCurrentWarningMessages() {
        return $this->hasCurrentMessages(self::NAMESPACE_WARNING);
    }

    /**
     * Check to see if messages have been added to "error"
     * namespace within this request
     *
     * @return bool
     */
    public function hasCurrentErrorMessages() {
        return $this->hasCurrentMessages(self::NAMESPACE_ERROR);
    }

    /**
     * Get messages that have been added to the current
     * namespace within this request
     *
     * @param  string $namespace
     * @return array
     */
    public function getCurrentMessages($namespace = null) {
        if (null === $namespace) {
            $namespace = $this->getNamespace();
        }

        if ($this->hasCurrentMessages($namespace)) {
            $container = $this->getContainer();
            $messages = $container->{$namespace}->toArray();
            return $messages;
        }

        return array();
    }

    /**
     * Get messages that have been added to the "default"
     * namespace within this request
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @return array
     */
    public function getCurrentDefaultMessages() {
        return $this->getCurrentMessages(self::NAMESPACE_DEFAULT);
    }

    /**
     * Get messages that have been added to the "info"
     * namespace within this request
     *
     * @return array
     */
    public function getCurrentInfoMessages() {
        return $this->getCurrentMessages(self::NAMESPACE_INFO);
    }

    /**
     * Get messages that have been added to the "success"
     * namespace within this request
     *
     * @return array
     */
    public function getCurrentSuccessMessages() {
        return $this->getCurrentMessages(self::NAMESPACE_SUCCESS);
    }

    /**
     * Get messages that have been added to the "warning"
     * namespace within this request
     *
     * @return array
     */
    public function getCurrentWarningMessages() {
        return $this->getCurrentMessages(self::NAMESPACE_WARNING);
    }

    /**
     * Get messages that have been added to the "error"
     * namespace within this request
     *
     * @return array
     */
    public function getCurrentErrorMessages() {
        return $this->getCurrentMessages(self::NAMESPACE_ERROR);
    }
    
    /**
     * Retorna todas as mensagens de todos os tipos do request atual
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @return type
     */
    public function getCurrentAllMessages() {
        return [
            self::NAMESPACE_DEFAULT => $this->getCurrentDefaultMessages(),
            self::NAMESPACE_INFO => $this->getCurrentInfoMessages(),
            self::NAMESPACE_SUCCESS => $this->getCurrentSuccessMessages(),
            self::NAMESPACE_WARNING => $this->getCurrentwarningMessages(),
            self::NAMESPACE_ERROR => $this->getCurrentErrorMessages()
        ];
    }

    /**
     * Get messages that have been added to the current
     * namespace in specific namespace
     *
     * @param  string $namespaceToGet
     * @return array
     */
    public function getCurrentMessagesFromNamespace($namespaceToGet) {
        return $this->getCurrentMessages($namespaceToGet);
    }

    /**
     * Clear messages from the current request and current namespace
     *
     * @param  string $namespace
     * @return bool True if current messages were cleared, false if none existed.
     */
    public function clearCurrentMessages($namespace = null) {
        if (null === $namespace) {
            $namespace = $this->getNamespace();
        }

        if ($this->hasCurrentMessages($namespace)) {
            $container = $this->getContainer();
            unset($container->{$namespace});

            return true;
        }

        return false;
    }

    /**
     * Apaga todas as mensagens de "Default" do request atual
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @return bool
     */
    public function clearCurrentDefaultMessages() {
        return $this->clearCurrentMessages(self::NAMESPACE_DEFAULT);
    }

    /**
     * Apaga todas as mensagens de "Info" do request atual
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @return bool
     */
    public function clearCurrentInfoMessages() {
        return $this->clearCurrentMessages(self::NAMESPACE_INFO);
    }

    /**
     * Apaga todas as mensagens de "Warning" do request atual
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @return bool
     */
    public function clearCurrentWarningMessages() {
        return $this->clearCurrentMessages(self::NAMESPACE_WARNING);
    }

    /**
     * Apaga todas as mensagens de "Success" do request atual
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @return bool
     */
    public function clearCurrentSuccessMessages() {
        return $this->clearCurrentMessages(self::NAMESPACE_SUCCESS);
    }

    /**
     * Apaga todas as mensagens de "Error" do request atual
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @return bool
     */
    public function clearCurrentErrorMessages() {
        return $this->clearCurrentMessages(self::NAMESPACE_ERROR);
    }
    
    /**
     * Apaga todas as mensagens de todos os tipos do request atual
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     */
    public function clearCurrentAllMessages(){
        $this->clearCurrentErrorMessages();
        $this->clearCurrentInfoMessages();
        $this->clearCurrentSuccessMessages();
        $this->clearCurrentWarningMessages();
        $this->clearCurrentErrorMessages();
    }

    /**
     * Clear messages from the current namespace
     *
     * @param  string $namespaceToClear
     * @return bool True if current messages were cleared from the given namespace, false if none existed.
     */
    public function clearCurrentMessagesFromNamespace($namespaceToClear) {
        return $this->clearCurrentMessages($namespaceToClear);
    }

    /**
     * Clear messages from the container
     *
     * @return bool True if current messages were cleared from the container, false if none existed.
     */
    public function clearCurrentMessagesFromContainer() {
        $container = $this->getContainer();

        $namespaces = array();
        foreach ($container as $namespace => $messages) {
            $namespaces[] = $namespace;
        }

        if (empty($namespaces)) {
            return false;
        }

        foreach ($namespaces as $namespace) {
            unset($container->{$namespace});
        }

        return true;
    }

    /**
     * Complete the IteratorAggregate interface, for iterating
     *
     * @return ArrayIterator
     */
    public function getIterator() {
        if ($this->hasMessages()) {
            return new ArrayIterator($this->getMessages());
        }

        return new ArrayIterator();
    }

    /**
     * Complete the countable interface
     *
     * @return int
     */
    public function count() {
        if ($this->hasMessages()) {
            return count($this->getMessages());
        }

        return 0;
    }

    /**
     * Get messages from a specific namespace
     *
     * @param  string $namespaceToGet
     * @return array
     */
    public function getMessagesFromNamespace($namespaceToGet) {
        return $this->getMessages($namespaceToGet);
    }

    /**
     * Pull messages from the session container
     *
     * Iterates through the session container, removing messages into the local
     * scope.
     *
     * @return void
     */
    protected function getMessagesFromContainer() {
        if (!empty($this->messages) || $this->messageAdded) {
            return;
        }

        $container = $this->getContainer();

        $namespaces = array();
        foreach ($container as $namespace => $messages) {
            $this->messages[$namespace] = $messages;
            $namespaces[] = $namespace;
        }

        foreach ($namespaces as $namespace) {
            unset($container->{$namespace});
        }
    }

    /**
     * Renderiza a mensagem
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @param string $message Mensagem
     * @param string $title Titulo da mensagem
     * @param string $type Tipo de mensagem a ser montada
     * @param array $class Classes da div .alert
     * @return string HTML renderizadpoo
     */
    public function render($message, $title = "", $type = "default", $class = []) {
        $htmlRender = '<div class="alert alert-' . $type . ' alert-dismissible" role="alert">';
        $htmlRender .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        $htmlRender .= (empty($title)) ? '<p>' . $message . '</p>' : '<p><b>' . $title . '</b></p><p>' . $message . '</p>';
        $htmlRender .= '</div>';
        return $htmlRender;
    }

    /**
     * Renderiza uma mensagem Default
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @param type $message Mensagem
     * @param type $title Titulo da mensagem
     * @param type $class Classes da div .alert
     */
    public function renderDefault($message, $title = "", $class = []) {
        return $this->render($message, $title, self::NAMESPACE_DEFAULT, $class);
    }

    /**
     * Renderiza uma mensagem Info
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @param type $message Mensagem
     * @param type $title Titulo da mensagem
     * @param type $class Classes da div .alert
     */
    public function renderInfo($message, $title = "", $class = []) {
        return $this->render($message, $title, self::NAMESPACE_INFO, $class);
    }

    /**
     * Renderiza uma mensagem Success
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @param type $message Mensagem
     * @param type $title Titulo da mensagem
     * @param type $class Classes da div .alert
     */
    public function renderSuccess($message, $title = "", $class = []) {
        return $this->render($message, $title, self::NAMESPACE_SUCCESS, $class);
    }

    /**
     * Renderiza uma mensagem Error
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @param type $message Mensagem
     * @param type $title Titulo da mensagem
     * @param type $class Classes da div .alert
     */
    public function renderError($message, $title = "", $class = []) {
        $teste = $this->render($message, $title, self::NAMESPACE_ERROR, $class);
        return $teste;
    }

    /**
     * Renderiza uma mensagem Warning
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @param type $message Mensagem
     * @param type $title Titulo da mensagem
     * @param type $class Classes da div .alert
     */
    public function renderWarning($message, $title = "", $class = []) {
        return $this->render($message, $title, self::NAMESPACE_WARNING, $class);
    }

    /**
     * Renderiza todas as mensagens Default
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @return string
     */
    public function renderAllDefault() {
        $currentDefaultMessages = $this->getCurrentDefaultMessages();
        $this->clearCurrentDefaultMessages();
        foreach ($currentDefaultMessages as $currentDefault) {
            return $this->renderDefault($currentDefault);
        }
    }

    /**
     * Renderiza todas as mensagens Info
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @return string
     */
    public function renderAllInfo() {
        $currentInfoMessages = $this->getCurrentInfoMessages();
        $this->clearCurrentInfoMessages();
        foreach ($currentInfoMessages as $currentInfo) {
            return $this->renderInfo($currentInfo);
        }
    }

    /**
     * Renderiza todas as mensagens Success
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @return string
     */
    public function renderAllSuccess() {
        $currentSuccessMessages = $this->getCurrentSuccessMessages();
        $this->clearCurrentSuccessMessages();
        foreach ($currentSuccessMessages as $currentSuccess) {
            return $this->renderSuccess($currentSuccess);
        }
    }

    /**
     * Renderiza todas as mensagens Error
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @return string
     */
    public function renderAllError() {
        $currentErrorMessages = $this->getCurrentErrorMessages();
        $this->clearCurrentErrorMessages();
        foreach ($currentErrorMessages as $currentError) {
            return $this->renderError($currentError);
        }
    }

    /**
     * Renderiza todas as mensagens Warning
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @return string
     */
    public function renderAllWarning() {
        $currentWarningMessages = $this->getCurrentWarningMessages();
        $this->clearCurrentWarningMessages();
        foreach ($currentWarningMessages as $currentWarning) {
            return $this->renderWarning($currentWarning);
        }
    }

    /**
     * Retorna todas as mensagens de todos os tipos
     * @return string
     */
    public function renderAllMessages() {
        $all = $this->renderAllDefault();
        $all .= $this->renderAllInfo();
        $all .= $this->renderAllSuccess();
        $all .= $this->renderAllWarning();
        $all .= $this->renderAllError();
        return $all;
    }
}
