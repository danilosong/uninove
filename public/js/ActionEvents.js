/*
 * Project: actionEvents.js
 * Description: Centralizador de Ações do sistema
 * Date: 31_06_2015
 */

/**
 * Initialize Actions
 */
if (!window.App) {
    window.App = {};
    window.App.SETTINGS = {};
}

/**
 * Acoes Eventos
 * @class ActionEvents
 * @param  {[type]} $) { var $doc [description]
 * @return {[type]}    [description]
 */
var actionEvents = (function ($) {
    /**
     * @type {jQuery}
     */
    var $doc = $(document);
    /**
     * Public Events
     */
    return {
        /**
         * Executa acoes do onClick
         * @param {jQuery|String} attr Elemento DOM que sera observado
         * @param {function} execMe Funcao de execucao quando ocorrencia ocorre
         * @author Danilo Dorotheu
         * @version 1.0
         */
        click: function (attr, execMe) {
            $doc.on("click", attr, execMe);
            return false;
        },
        /**
         * Executa acoes do click (Após sair da pagina, evento é desligado)
         * @param {jQuery|String} attr Elemento DOM que sera observado
         * @param {function} execMe Funcao de execucao quando ocorrencia ocorre
         * @author Danilo Dorotheu
         * @version 1.0
         */
        clickme: function (attr, execMe) {
            $(attr).click(execMe);
            return false;
        },
        /**
         * Executa acoes do onClick
         * OBS: Método suicida
         * @param {jQuery|String} attr Elemento DOM que sera observado
         * @param {function} execMe Funcao de execucao quando ocorrencia ocorre
         * @author Danilo Dorotheu
         * @version 1.0
         */
        oneClick: function (attr, execMe) {
            $doc.one("click", attr, execMe);
            return false;
        },
        /**
         * Executa acoes do onChange
         * @param {jQuery|String} attr Elemento DOM que sera observado
         * @param {function} execMe Funcao de execucao quando ocorrencia ocorre
         * @author Danilo Dorotheu
         * @version 1.0
         */
        change: function (attr, execMe) {
            $doc.on("change", attr, execMe);
            return false;
        },
        /**
         * Executa acoes do onChange
         * OBS: Metodo suicida
         * @param {jQuery|String} attr Elemento DOM que sera observado
         * @param {function} execMe Funcao de execucao quando ocorrencia ocorre
         * @author Danilo Dorotheu
         * @version 1.0
         */
        oneChange: function (attr, execMe) {
            $doc.one("change", attr, execMe);
            return false;
        },
        /**
         * Executa acoes do onKeyUp
         * @param {jQuery|String} attr Elemento DOM que sera observado
         * @param {function} execMe Funcao de execucao quando ocorrencia ocorre
         * @author Danilo Dorotheu
         * @version 1.0
         */
        keyup: function (attr, execMe) {
            $doc.on("keyup", attr, execMe);
            return false;
        },
        /**
         * Executa acoes do onKeyUp
         * OBS:Metodo suicida
         * @param {jQuery|String} attr Elemento DOM que sera observado
         * @param {function} execMe Funcao de execucao quando ocorrencia ocorre
         * @author Danilo Dorotheu
         * @version 1.0
         */
        oneKeyup: function (attr, execMe) {
            $doc.one("keyup", attr, execMe);
            return false;
        },
        /**
         * Executa acoes do onKeyDown
         * @param {jQuery|String} attr Elemento DOM que sera observado
         * @param {function} execMe Funcao de execucao quando ocorrencia ocorre
         * @author Danilo Dorotheu
         * @version 1.0
         */
        keyDown: function (attr, execMe) {
            $doc.on("keydown", attr, execMe);
            return false;
        },
        /**
         * Executa funcoes do onKeyPress
         * @param {jQuery|String} attr Elemento DOM que sera observado
         * @param {function} execMe Funcao de execucao quando ocorrencia ocorre
         * @author Danilo Dorotheu
         * @version 1.0
         */
        keyPress: function (attr, execMe) {
            attr = $(document.getElementById(attr))
            $doc.on("keypress", attr, execMe);
            return false;
        },
        /**
         * Executa acoes de clickOut do elemento
         * @param {jQuery|String} $container Elemento DOM que sera observado
         * @param {function} execMe Funcao de execucao quando ocorrencia ocorrer
         * @author Danilo Dorotheu
         * @version 1.0
         */
        clickOut: function ($container, execMe) {
            $container = $($container);
            $doc.mouseup(function (e) {
                if (!$container.is(e.target) // if the target of the click isn't the container...
                        && $container.has(e.target).length === 0) // ... nor a descendant of the container
                {
                    execMe();
                }
            });
        }

    };

})(jQuery);