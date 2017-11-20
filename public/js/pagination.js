/*
 *  Project: Padronização TCMED - AEMSistemas
 *  Description: 
 *  Author:
 *  License:
 */

// the semi-colon before function invocation is a safety net against concatenated
// scripts and/or other plugins which may not be closed properly.
;
(function ($, window, document, undefined) {

    // undefined is used here as the undefined global variable in ECMAScript 3 is
    // mutable (ie. it can be changed by someone else). undefined isn't really being
    // passed in so we can ensure the value of it is truly undefined. In ES5, undefined
    // can no longer be modified.

    // window is passed through as local variable rather than global
    // as this (slightly) quickens the resolution process and can be more efficiently
    // minified (especially when both are regularly referenced in your plugin).

    var // plugin name
            pluginName = "pagination",
            // key using in $.data()
            dataKey = "plugin_" + pluginName,
            settings = {
                next: "#gnext",
                back: "#gback",
                cont: "#inter",
                menu: "#menu-bar",
                orientation: "left",
                iniArray: 1,
                cursor: 0,
                endArray: 0,
                lenghtArray: 9,
                pages: {
                    menu: {},
                    data: {}
                }
            };

    /**
     * Retorna a pagina com forms fixados
     * 
     * @returns {jQuery} html
     */
    var getPage = function () {
        //Fixa os dados no html
        fixDataForm();
        //Retorna html
        return $(settings.cont).html();
    };

    /**
     * Salva a pagina atual no cache
     * (Alternativa para ser chamado de dentro da classe)
     * 
     * @returns {undefined}
     */
    var savePageCache = function () {
        settings.pages.data[settings.cursor] = getPage();
    };

    /**
     * Salva a pagina atual no cache
     * (Alternativa para ser chamado de fora da classe)
     * 
     * @returns {undefined}
     */
    this.savePage = function () {
        if (settings) {
            savePageCache();
        }
    };

    /**
     * Fixa dados no html 
     * (Trata erro de salvar pagina)
     * 
     * @returns {undefined}
     */
    var fixDataForm = function () {
        //Transforma o container em objeto JQuery
        var cont = $(settings.cont);

        //Pra cada input dentro do container...
        cont.find("input").each(function () {
            //Check os radios e os checks ativos
            if ($(this).prop("checked")) {
                $(this).attr("checked", "checked");
            }
            //Descheck os radios e os checks inativos
            else {
                $(this).removeAttr("checked");
            }
            // Definir o value dos tipos textos, senhas...
            $(this).attr("value", $(this).val());
        });
        //Pra casa select dentro do container...
        cont.find("select").each(function () {
            //Remover :selected dos options nao selecionados
            $(this).find("option").not($(this)
                    .find("option:selected")).removeAttr("selected");
            //Adicionar :selected dos options selecionados
            $(this).find("option:selected").attr("selected", "selected");
        });
        //Pra cada textarea dentro do container...
        cont.find("textarea").each(function () {
            //Definir texto utilizando o value
            $(this).text($(this).val());
        });
    };

    /**
     * Adiciona pagina no cache
     * 
     * @returns {undefined}
     */
    var addPageToLog = function () {
        if (settings) {
            //Se for a primeira posicao
            if (settings.cursor >= settings.iniArray) {
                //Habilita o botao back
                $(settings.back).attr("disabled", false);
            }
            //Incrementa cursor
            settings.cursor++;
            //Define fim do array
            settings.endArray = settings.cursor;
            //Salva a proxima pagina
            savePageCache();
            //Salva o item de menu da pagina
            settings.pages.menu[settings.cursor] = $(settings.menu).find("a.active");
            //Controla tamanho do array
            if (settings.endArray - settings.iniArray > settings.lenghtArray) {
                settings.iniArray++;
            }
            //Desabilita o botao next
            $(settings.next).attr("disabled", true);
        }
    };

    /**
     * Metodo de gerenciamento dos botoes de voltar pagina
     * e avançar pagina
     * 
     * @returns {undefined}
     */
    var managerClickPagin = function () {
        //Transforma os params em objetos JQuery
        var next = $(settings.next);
        var back = $(settings.back);
        var cont = $(settings.cont);

        //Desabilita os botoes de back e next
        back.attr("disabled", true);
        next.attr("disabled", true);

        //Eventos do botao back
        $(document).on("click", settings.back, function () {
            //Habilita o botao next
            next.attr("disabled", false);
            //Salva a pagina atual
            savePageCache();
            //decrementa cursor
            settings.cursor--;
            //Retorna a pagina salva anterior
            cont.html(settings.pages.data[settings.cursor]);
            //Clica no item de menu respectivo a pagina
            $(settings.pages.menu[settings.cursor]).click();
            //Se for a primeira posicao, desabilita o botao voltar
            if (settings.cursor == settings.iniArray) {
                back.attr("disabled", true);
            }
        });

        $(document).on("click", settings.next, function () {
            //Habilita o botao back
            back.attr("disabled", false);
            //Salva a pagina atual
            savePageCache();
            //Incrementa cursor
            settings.cursor++;
            //Retorna a proxima pagina salva 
            cont.html(settings.pages.data[settings.cursor]);
            //Clica no item de menu respectivo a pagina
            $(settings.pages.menu[settings.cursor]).click();
            //Se for a ultima posicao, desabilita o botao avancar
            if (settings.cursor == settings.endArray) {
                next.attr("disabled", true);
            }
        });
    };

    /**
     * 
     * 
     * @returns {undefined}
     */
    var buildHtml = function () {
        $(settings.element).addClass("navbar-form").addClass("navbar-" + settings.orientation);

        //Adiciona botao esquerdo
        $(settings.element).append('<button type="button"'
                + 'class="btn btn-default btn go godown">'
                + '<i class="fa fa-arrow-left"></i>'
                + '</button>');

        //Adiciona botao direito
        $(settings.element).append('<button type="button"'
                + 'class="btn btn-default btn-xs go goup">'
                + '<i class="fa fa-arrow-right"></i>'
                + '</button>');
    };

    var Plugin = function (element, options) {
        this.element = element;
        //Mesclagem das opções externas com internas
        settings = $.extend({}, options, settings);
        settings.element = this.element;
        //Acesso das opções externamente
        this._settings = settings;
        //Inicialização da interface
        this.init(options);
    };

    Plugin.prototype = {
        // initialize options
        init: function () {
            //Salva a primeira pagina
            addPage();
            //Inicializa os eventos back e next
            managerClickPagin();
        },
        addPage: function(){;
            addPageToLog();
        }
    };

    /*
     * Plugin wrapper, preventing against multiple instantiations and
     * return plugin instance.
     */
    $.fn[pluginName] = function (options) {

        var plugin = this.data(dataKey);

        // has plugin instantiated ?
        if (plugin instanceof Plugin) {
            // if have options arguments, call plugin.init() again
            if (typeof options !== 'undefined') {
                plugin.init(options);
            }
        } else {
            plugin = new Plugin(this, options);
            this.data(dataKey, plugin);
        }

        return plugin;
    };

}(jQuery, window, document));