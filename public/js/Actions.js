/*
 * Project: Actions.js
 * Description: Centralizador de Ações do sistema
 * Date: 24_06_2015
 */

/**
 * Initialize Actions
 */
if (!window.App) {
    window.App = {};
    window.App.SETTINGS = {
        /**
         * @property {object} public Armazena variáveis publicas, acessíveis em
         * todo o sistema
         */
        publics: {}
    };
    window.$$; //Alias to getEl
}

/**
 * Actions
 */
var action = (function ($, options, event) {

    //DEFINIÇOES
    String.prototype.repeat = function (num)
    {
        return new Array(num + 1).join(this);
    }

    var errors = {
        serverNotFound: function (url, e) {
            console.error("Servidor '" + url + "' não pode ser localizado!\n"
                    + " Verifique o endereco do servidor! " + e);
        },
        accessDanied: function () {
            console.error("Acesso não permitido");
        }
    };

    var preventDoubleClick = true;

    /**
     * Variáveis privadas
     * (Apenas esta ferramenta conhece
     * as variáveis abaixo listadas)
     * @type type
     */
    var defaults = {
        /**
         * Controlador de eventos da página
         * @returns {undefined}
         */
        events: function () {
            /**
             * Apaga todos os IDs descritos no attr dt-clear
             */
            event.click(".clean", function () {
                var clear = $(this).attr("dt-clear");
                if (clear) {
                    clear = clear.split(",");
                    $.each(clear, function (key, value) {
                        action.getEl(value).val("");
                    });
                }
            });
            /**
             * Limpa o formulário por completo
             */
            event.click(".clearForm", function () {
                $("#inter").find("form").clearForm();
            });

//            event.click(".clearForm", function () {
//                var $form = $("form");
//                $form.find("input").val("");
//                $form.find("textarea").val("");
//                var select = $form.find('select');
//                if (select) {
//                    select.val($('options:first', select).val());
//                }
//            });

            /**
             * Apaga o input com o .clean e coloca foco
             */
            event.click(".clean", function () {
                var obj = $(this).parent().parent();
                var $txt = obj.find('input[type=text]').eq(0);
                $txt.val('');
                if (!$txt.hasClass('calendario')) {
                    $txt.focus();
                }
                obj.find('textarea').val('').focus();
                obj.find('input[type=checkbox]').removeAttr('checked').focus();
                obj.find('input[type=radio]').removeAttr('checked').focus();
                var select = obj.find('select');
                if (select) {
                    select.val($('options:first', select).val()).focus();
                }
            });
        },
    };
    /**
     * @constructor
     */
    return {
        /**
         * Função que testa o Actions carregado
         * @returns {undefined}
         */
        roar: function () {
            console.warn("ROARRR, eu estou funcionando (ou acho que estou)!!");
            console.log(action);
        },
        
        /**
         * função isset equivalente a do php
         * evita erros em variáveis primitivas, bem como matrizes e objetos
         * 
         * @param {Object} obj
         * @autor Paulo Watakabe
         * @since 23-11-2017
         * @version 1.0
         */
        isset : function(obj) {
            var i, max_i;
            if(obj === undefined){ 
                return false;
            }
            for (i = 1, max_i = arguments.length; i < max_i; i++) {
                if (obj[arguments[i]] === undefined) {
                    return false;
                }
                obj = obj[arguments[i]];
            }
            return true;
        },
        /**
         * Salvar em escopo global valores do sistema
         * @param {Object} obj
         * @autor Paulo Watakabe
         * @author Danilo Dorotheu
         * @version 1.0
         */
        setPublic: function (obj, prefix) {
            if (!options.publics) {
                options.publics = {};
            }
            if (prefix) {
                options.publics[prefix] = obj;
            } else {
                if(this.isset(obj)){
                    options.publics = obj;
                }
            }
        },
        /**
         *
         * @param {type} key
         * @param {type} prefix
         * @returns {Mixed|String}
         */
        getPublic: function (key, prefix) {
            if (!options.publics) {
                return '';
            }
            if (prefix) {
                if (key === "all") {
                    if(this.isset(options.publics, prefix)){                        
                        return options.publics[prefix];
                    }
                } else {
                    if(this.isset(options.publics, prefix) && this.isset(options.publics, prefix, key)){                        
                        return options.publics[prefix][key];
                    }
                }
            }
            if (this.isset(options.publics, key)) {
                return options.publics[key];
            }
            return '';
        },
        /**
         * Validador de campos vazios e não preenchidos
         * @param {Object} obj Elementos
         * @param {boolean} convert Converter após checagem?
         * @returns {Boolean|String}
         */
        validFields: function (obj, listIgnore, convert) {
            var valid = true;
            var newObj = {};

            function ignore(key) {
                var exists = false;
                for (var i = 0; i < listIgnore.length; i++) {
                    if (key == listIgnore[i]) {
                        exists = true;
                        break;
                    }
                }
                return exists;
            }

            for (var key in obj) {
                var aux = action.getVal(obj[key]);
                if (!aux || aux == "") {
                    if (ignore(key)) {
                        newObj[key] = aux;
                        continue;
                    }
                    valid = false;
                    break;
                } else {
                    newObj[key] = aux;
                }
            }

            if (valid) {
                return (convert) ? newObj : obj;
            }
            return false;
        },
        /**
         * Procurar um contato dentro de uma lista
         * @param {array} listaContatos Lista de contatos
         * @param {String} crit String que deve ser localizada na lista
         * @returns {obj} contato Objeto que contem a string cont
         * @author Danilo Dorotheu
         * @versin 1.0
         */
        findContact: function (listaContatos, cont) {
            var contato;

            if (!listaContatos)
                return false;

            $.each(listaContatos, function (chave, valor) {
                if (chave == cont) {
                    contato = valor;
                    return;

                } else if (valor.name == cont) {
                    contato = valor;
                    return;
                }
            });

            return contato;
        },
        /**
         * Retorna o ID do contato
         * @param  {[type]} lista [description]
         * @return {[type]}       [description]
         */
        getKeyContact: function (lista) {
            var keyCont;

            $.each(lista, function (key, value) {
                keyCont = key;
                return;
            });

            return keyCont;
        },
        /**
         * Verifica se é grupo ou usuario
         * @param {String} crit
         * @returns {boolean}
         */
        isGroup: function (crit) {
            return crit.indexOf("gr") > -1;
        },
        /**
         * Verificar se navegador é a porcaria do Internet Explorer ou Edge
         * @returns {undefined}
         */
        verificaNavegador: function () {
//            var navegador = action.ObterBrowserUtilizado();
//            if (navegador != 'Internet Explorer' && navegador != 'Edge') {
//                var request = module.Cookie.get('REQUEST');
//                if (request == "") {
//                    request = "0";
//                    module.Cookie.set({
//                        name     : 'REQUEST'
//                        ,value   : "1"
//                        ,expires : '0'
//                    });
//                }
//                if (request == "0") {
//                    while (request == "1") {
//                        request = module.Cookie.get('REQUEST');
//                    }
//                }
//                module.Cookie.set({
//                    name     : 'REQUEST'
//                    ,value   : "0"
//                    ,expires : '0'
//                });
//                module.Cookie.set({
//                    name      : 'PHPSESSID'
//                    , value   : action.getPublic('SESSAO', 'sessao') + 'param' + action.getPublic('LOGIN', 'sessao')
//                    , expires : '0'
//                });
//            }
        },
        /**
         * Centralizador de requisiçoes para o servidor
         * Fas controle de de requests de form de fila
         * Não permite que outro requeste entre sem terminar o que esta em execução
         * Faz o request seguinte aguardar ate que o request em execução acabe
         * Melhorado o sistema de login em varias Abas alterando o cookei da sessao para o usuario da aba que esta acessando
         * @public
         * @param {Object} arg URL do Servidor, Tipo (POST/GET), Dados
         * @returns {AJAX}
         * @author Danilo Dorotheu
         * @author Paulo Watakabe
         * @version 1.5 (15-01-2016)
         */
        requestServer: function (arg) {
            var resul = globalLogonUser.waitForRequestAllow(function(arg){
                action.requestServer(arg);
            },arg);
            if(!resul){
                return ;
            };

            var params = {
                async: true,
            };

            $.extend(params, arg, params);

            globalLogonUser.changeAllCookieForMyLogin();

            params.control = (params.control) ? params.control : "";
            params.url = params.url + params.control + "?ajax=ok&"; //Trata erro de ajax

            if (params.type !== "POST") {
                params.type = "GET";
                params.url = params.url + Math.ceil(Math.random() * 100000);
            }

            params.beforeSend = function () {
                //Inicia o loader caso o params.loader = TRUE
                if (params.loader) {
                    action.loader(true);
                }
            }

            //Envia a requisicao
            var request = $.ajax(params);


            /**
             * Será executado sempre que a requisição falhar
             * OBS: Quando params.showError = FALSE, então não printar erro na
             * tela do usuário
             */
            request.fail(function (msg, status, errorThrown) {
                globalLogonUser.AllowRequest();
                var message = '<dt>Foi encontrado um erro no servidor quando acessado a URL "' + params.url + '".</dt>';

                if (false == params.showError) {
                    return false;
                }

                //Monta a lista com as variaveis
                var renderedVars = "";
                if ("POST" == params.type && params.showErrorArray) {
                    renderedVars += '<div class="alert alert-danger"><h2>Dados enviados para o servidor (POST): </h2><br><br>';
                    renderedVars += "<ul>";
                    $.each(params.clearData, function (key, value) {
                        renderedVars += "<li><strong>" + key + ":&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>" + value + "</li>";
                    });
                    renderedVars += "</div></div>";
                }


                $("#inter").html('<div class="container"> <h1>ERRO "' + msg.status + '" <small>' + errorThrown + '</small></h1><br>' + message + '<br>' + renderedVars + "<br>" + msg.responseText + '</div>');
            });

            //Será executado quando completar a requisicao
            request.complete(function () {
                globalLogonUser.AllowRequest();
                //desliga o loader caso o params.loader = TRUE
                if (params.loader) {
                    action.loader(false);
                }
            });

            request.success(function (data) {
                arg.callback(data, arg);
                /**
                 * @description Salva a nova pagina
                 */
                if (arg.savePage) {
//                        module.Pagination.addPagina(defaults);
                }
            });
        },
        ObterBrowserUtilizado: function () {
            var isOpera = !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;

            var isFirefox = typeof InstallTrigger !== 'undefined';
            var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;

            var isChrome = !!window.chrome && !isOpera;
            var isIE = /*@cc_on!@*/false || !!document.documentMode;

            if (/Edge\/1./i.test(navigator.userAgent)) {
                // this is Microsoft Edge
                return "Edge"; //valor que será retornado
            }
            if (isOpera) {
                return "Opera"; //valor que será retornado
            }
            if (isFirefox) {
                return "Firefox"; //valor que será retornado
            }
            if (isChrome) {
                return "Google Chrome"; //valor que será retornado
            }
            if (isSafari) {
                return "Safari"; //valor que será retornado
            }
            if (isIE) {
                return "Internet Explorer"; //valor que será retornado
            }
            return "Outro"; //valor que será retornado
        },
        /**
         * Alias processa
         */
        processa: function (obj) {
            $.processa(obj);
        },
        /**
         * Retorna o elemento tratado
         * @param {type} el
         * @returns {$}
         */
        getEl: function (el) {
            if (el[0] == ".") {
                el = document.getElementsByClassName(el.substring(1))[0];
            } else {
                el = (el[0] == "#") ? el.substring(1) : el;
                el = document.getElementById(el);
            }
            return $(el);
        },
        /**
         * Retorna a posicao do array
         * @param {type} array
         * @param {type} key
         * @returns {Number}
         */
        getPositionArray: function (array, key) {
            for (var i = 0; i < array.length; i++) {
                if (array[i] === key) {
                    return i;
                }
            }
        },
        /**
         * Simula o innerHTML (Ou o $.html()), com a diferenca
         * de pegar tambem, o elemento externo (ou pai)
         * @returns {string HTML}
         */
        outerHTML: function (element) {
            return $('<a>').append(element.eq(0).clone()).html();
        },
        /**
         * Painel de notificacao de informacoes/ mensagens
         * @see https://developer.mozilla.org/en-US/docs/Web/API/notification
         * @param {type} options
         * @returns {undefined}
         */
        notification: function (options) {
            if (window.Notification) {
                if (!Notification.permission) {
                    Notification.requestPermission();
                }

                if (Notification.permission) {
                    var notification = new Notification(options.title, options);
                    return notification;
                }
            }

//            if (!("Notification" in window)) {
//                console.warn("Este browser não suporta notificações de desktop");
//                return;
//            } else if (Notification.permission === "granted") {
//                var notification = new Notification(options.title, options);
//            } else if (Notification.permission !== 'denied') {
//                Notification.requestPermission(function (permission) {
//                    if (!('permission' in Notification)) {
//                        Notification.permission = permission;
//                    }
//
//                    if (permission === "granted") {
//                        var notification = new Notification(options.title, options);
//                    }
//                });
//            }
        },
        /**
         * [internNotification description]
         * @param  {[type]} params [description]
         * -> {String} text
         * -> {String} type [alert-success| alert-info| alert-warning| alert-danger]
         * @return {[type]}        [description]
         */
        internNotification: function (params) {

            var defs = {
                class: "alert-success",
                head: "",
                msg: "Intern Notification",
                time: 5000
            };

            if (!params.msg) {
                return;
            }

            //Mescla os objetos
            params = $.extend(true, defs, params);

            //Monta o local de notificacao se não existir
            if ($(".notification").html() === undefined) {
                $("body").append('<div class="notification"></div>');
            }

            //gera append
            var teste = $(".notification").prepend('<div class="alert ' + params.class + ' alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>' + params.head + '</strong>' + params.msg + '</div>');

            //Liga
            teste.animate({
                opacity: 1
            }, 1000);

            setTimeout(function () {
                teste.remove();
            }, params.time);
        },
        /**
         * Imagem de carregamento da pagina
         * @param  {[type]} status [description]
         * @return {[type]}        [description]
         */
        loader: function (status) {
            if (status) {
                $("#loaderGif").show('slow');
                return true;
            } else {
                $("#loaderGif").hide('slow');
                return false;
            }
            ;
        },
        /**
         *
         * @param {type} params
         * @returns {undefined}
         */
        load: function (params) {
            //Inicializador do Pagination
//            module.Pagination.init();

            /**
             * Inicializa o paginador do sistema
             */
            module.Paginador.init({
                container: "#inter",
                back: "#godown",
                next: "#goup",
                blockRepeat: true,
            });

            /**
             * @link https://developer.mozilla.org/pt-BR/docs/Web/API/WindowEventHandlers/onbeforeunload
             */
            $(window).bind('beforeunload', function(event){
                return "Se você confirmar, pode perder todos os dados...Deseja continuar? \nDica: Você pode usar o botão voltar do próprio sistema!";
            });

            //Cria alias para metodos muito usados
            $$ = action.getEl;

            /*
             * Funcao que faz o envio do form padrão
             * Evita o duplo click segurando o proximo click em 5 s
             *
             * @author Danilo Dorotheu
             * @author Paulo Watakabe
             * @version 1.5 (15-01-2016)
             * @returns {nothing}
             */
            $.fn.sendForm = function (called, data) {
                if (preventDoubleClick) {
                    setTimeout(function () {
                        preventDoubleClick = true;
                    }, 5000);
                    preventDoubleClick = false;
                } else {
                    console.warn('Segurou o sendForm pois ja foi executado intervalo até a liberação de 5 segundos');
                    return;
                }
                if (called == null) {
                    if (!isValid()) {
                        preventDoubleClick = true;
                        return;
                    }
                } else {
                    if (!called.isValid()) {
                        preventDoubleClick = true;
                        return;
                    }
                }
                if (data == null) {
                    data = '';
                } else {
                    if(data[0] == "{"){
                        data = ',data:' + data ;
                    }else{
                        data = ',data:{' + data + '}';
                    }
                }
                var $form = $(this).closest('form');
                var actionF = $form.attr('action');
                var target = $form.attr('target');
                var ind = actionF.indexOf('})');
                if (target) {
                    actionF = actionF.substr(0, ind) + ",frm:'" + $form.attr('id') + "'" + ",ret:'" + target + "'" + data + actionF.substr(ind);
                } else {
                    actionF = actionF.substr(0, ind) + ",frm:'" + $form.attr('id') + "'" + data + actionF.substr(ind);
                }
                eval(actionF);
            };

            $.fn.clickOut = function (funcao) {
                var $container = $(this);
                $(document).mouseup(function (e) {
                    if (!$container.is(e.target) // Se e.target não for o container
                            && $container.has(e.target).length === 0) // Ou não for filho do container
                    {
                        // Então significa que o click foi fora do container
                        if (funcao) {
                            return funcao();
                        }
                        return true;
                    }
                    return false;
                });
            }


            /*
             * Atrela a Funcao que faz o envio do form ao botão padrão do form
             * Retorna false para evitar que o evento submit do form seja executado
             *
             * @author Danilo Dorotheu
             * @author Paulo Watakabe
             * @version 1.0 (15-01-2016)
             * @returns {boolean}
             */
            $(document).on("click", '#btnEnviarForm', function () {
                $(this).sendForm();
                return false;
            });

            /**
             * Verifica se funcao esta vazia
             *
             * @author Danilo Dorotheu
             * @returns {Boolean}
             */
            $.fn.isEmpty = function () {
                return $(this).val().length < 1;
            };

            /**
             * Torna um input:radio uncheckable, como o checkbox
             * @author Danilo Dorotheu
             * @returns {Actionsaction.$.fn@call;each}
             */
            $.fn.uncheckableRadio = function() {
                return this.each(function() {
                    $(this).mousedown(function() {
                        $(this).data('wasChecked', this.checked);
                    });

                    $(this).click(function() {
                        if ($(this).data('wasChecked'))
                            this.checked = false;
                    });
                });
            };

            /**
             * Valida campos de data
             * @author Danilo Dorotheu
             * @since 14/12/2015
             * @returns {Boolean}
             */
            $.fn.validaData = function () {
                var value = $(this).val();
                if (value.length != 10)
                    return false;
                var dia = value.substr(0, 2);
                var b01 = value.substr(2, 1);
                var mes = value.substr(3, 2);
                var b02 = value.substr(5, 1);
                var ano = value.substr(6, 4);
                if (value.length != 10 || b01 != "/" || b02 != "/" || isNaN(dia) || isNaN(mes) || isNaN(ano) || dia > 31 || mes > 12)
                    return false;
                if ((mes == 4 || mes == 6 || mes == 9 || mes == 11) && dia == 31)
                    return false;
                if (mes == 2 && (dia > 29 || (dia == 29 && ano % 4 != 0)))
                    return false;
                if (ano < 1900)
                    return false;
                return true;
            };
            /**
             * Valida PIS
             * @author Danilo Dorotheu
             * @since 4/1/2016
             * @returns {Boolean}
             */
            $.fn.validarPis = function () {
                var campo = $(this).val().replace(/[^\d]/g, "");
                var peso = [3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
                var soma = 0;
                var digito;

                for (var i = 0; i < (campo.length - 1); i++) {
                    soma += campo[i] * peso[i];

                }
                digito = 11 - (soma % 11);
                digito = (digito >= 10) ? 0 : digito;

                return campo[10] == digito;
            };
            /**
             * Transforma um form em objeto
             *
             * @param {boolean} short Quando TRUE, não é adicionado
             * propriedades com valor vazio no objeto, ou seja, objeto
             * será montado da forma mais reduzida possível
             * @author Danilo Dorotheu
             * @version 2.0
             * @todo Em análise de uso
             * @since 23/12/2015
             * @example http://jsfiddle.net/sxGtM/3/
             */
            $.fn.serializeObject = function (short) {
                var o = {};
                var a = this.serializeArray();

                $.each(a, function () {
                    if (this.value.length > 0 || !short) {
                        if (o[this.name] !== undefined) {
                            if (!o[this.name].push) {
                                o[this.name] = [o[this.name]];
                            }
                            o[this.name].push(this.value || '');
                        } else {
                            o[this.name] = this.value || '';
                        }
                    }
                });
                return o;
            };
            /**
             * Processa - Preparador de envio de dados para o servidor
             *
             * @param {Object} params Configuracoes de envio
             * @param {string} params.type Tipo da requisicao AJAX
             * @param {object} params.data Dados para envio ao servidor
             * @param {string} params.url URL da requisicao
             * @param {function} callback Funcao de execucao pós processamento
             * @param {boolean} params.showLoader Se TRUE, exibe loader na tela
             * @param {boolean} params.savePage Se TRUE, salva a pagina
             * @param {string} params.ret Local de retorno da resposta do AJAX
             * @param {boolean} params.last Permite refresh da pagina solicitada
             * @param {jQuery Object} params.form Formulario de envio
             * @version 3.1
             * @author Danilo Dorotheu
             */
            $.processa = function (params) {

                /**
                 * @private
                 */
                var formData;
                var defaults = {
                    type: "GET",
                    last: true,
                    showError: true, //Exibe o erro na tela
                    data: {},
                    url: false,
                    showMessage: true,
                    defaultFrm: "formSistema",
                    showLoader: true,
                    loader: true, // Modificar
                    savePage: true,
                    ret: "#inter",
                    canRet: true,
                    //PRECISA DOCUMENTAR
                    cache: false,
                    contentType: false,
                    processData: false,
                    callback: function (data, defaults) {
                        var $ret = $(defaults.ret);
                        if ($ret.length) {
                            $ret.html(data); //Exibe retorno na tela
                        } else {
                            console.log(data);
                            console.log('não Encontrou  o retorno para esse nome ' + defaults.ret);
                            console.log('não esqueça de utilizar o (#) para id e (.) para class');
                        }
                    },
                    /**
                     * @todo Este parametro é temporario, util pra mostra lista
                     * com as vars enviadas ao srv
                     */
                    showErrorArray: true,
                    clearData: {}
                };

                $.extend(defaults, params, defaults);

                if (defaults.savePage && defaults.url) {
                    module.Paginador.addHistorico({
                        "request": defaults
                    });
                }
                /**
                 * Caso o parametro for um formulário, o proximo bloco tem
                 * como objetivo personalizar o metodo processa, a fim de
                 * enviar corretamente os dados ao servidor
                 */
                if (defaults.frm) {
                    //Obriga requisicao ser POST
                    defaults.type = "POST";
                    //Caso o defaults.frm seja uma string, entao utiliza-la. Se nao, utilizar o defaults.defaultFrm
                    defaults.defaultFrm = (typeof defaults.frm == "string") ? defaults.frm : defaults.defaultFrm;
                    //Transforma formulario em objeto
                    defaults.clearData = $$(defaults.defaultFrm).serializeObject()
                    formData = new FormData($$(defaults.defaultFrm)[0]);
                } else {
                    formData = new FormData();
                }

                $.each(defaults.data, function (ind, ele) {
                    if (typeof ele === "object") {
                        if(!ele == null){
                            formData.append(ele.name, ele.value);
                            defaults.clearData[ele.name] = ele.value;
                        }else{
                            // ele quando null converto para vazio
                            formData.append(ind, '');
                            defaults.clearData[ind] = '';
                        }
                    } else {
                        formData.append(ind, ele);
                        defaults.clearData[ind] = ele;
                    }
                });
                defaults.data = formData;

                /**
                 * @description Nucleo de envio ao servidor
                 */
                action.requestServer(defaults);
            };

            /**
             * @description Simula o efeito radiobox em um campo checkbox
             * @author Danilo Dorotheu
             * @param {jQuery element | string} odd
             */
            $.fn.radio = function(odd){
                var $even = $(this);
                var $odd = $$(odd);

                $even.click(function () {
                    var isCheck = $(this).prop("checked");
                    $(this).prop("checked", isCheck);
                    $odd.prop("checked", false);
                });

                $odd.click(function () {
                    var isCheck = $(this).prop("checked");
                    $(this).prop("checked", isCheck);
                    $even.prop("checked", false);
                });
            };

            /**
             * Fixa todos os campos do array no formulario
             *
             * @returns {jQuery Element}
             */
            $.fn.fixPage = function () {
                //Transforma o container em objeto JQuery
                var $cont = $(this);

                //Pra cada input dentro do container...
                $cont.find("input").each(function () {
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
                $cont.find("select").each(function () {
                    //Remover :selected dos options nao selecionados
                    $(this).find("option").not($(this)
                            .find("option:selected")).removeAttr("selected");
                    //Adicionar :selected dos options selecionados
                    $(this).find("option:selected").attr("selected", "selected");
                });
                //Pra cada textarea dentro do container...
                $cont.find("textarea").each(function () {
                    //Definir texto utilizando o value
                    $(this).text($(this).val());
                });

                return $cont;
            }

            /**
             * Alias Processa (Quando chamado pelo proprio form)
             *
             * @author Danilo Dorotheu
             * @extends $.processa
             * @see $.processa
             * @since 23/12/2015
             * @param {type} params
             */
            $.fn.processa = function (params) {
                params["frm"] = $(this).attr("id");
                $.processa(params);
            };


            /**
             * Transforma um formulário em objeto
             * @version 1.5
             * @obsolete
             * @since 21/12/2015
             * @author Danilo Dorotheu
             * @returns Object
             */
            $.fn.frmToObj = function () {
                var frmSerialized = $(this).serializeArray();
                var aux = {};
                $.each(frmSerialized, function (key, value) {
                    aux[value["name"]] = value["value"] || '';
                });
                return aux;
            };

            /**
             * Armazena variáveis que podem ser utilizadas no sistema inteiro
             * @author Danilo Dorotheu
             * @param {string} prefix prefixo (alias) para guardar os objetos
             * @param {object} params Objeto que será armazenado
             * @returns {Object}
             */
            $.setPublics = function (prefix, params) {
                if (typeof prefix == "object") {
                    params = prefix;
                    prefix = "public";
                }
                if (options.publics[prefix || "public"]) {
                    $.extend(
                            options.publics[prefix || "public"],
                            params, options.publics[prefix || "public"]
                            );
                } else {
                    options.publics[prefix || "public"] = params || [];
                }

                return options.publics[prefix || "public"];
            };
            /**
             * Retorna variáveis publicas
             * @author Danilo Dorotheu
             * @param {string} prefix prefixo (alias) que contém os
             * objetos armazenados
             * @returns {Object}
             */
            $.getPublics = function (prefix) {
                return options.publics[prefix || "public"] || [];
            };

            /**
             * Simula sessao de armazenamento no front
             *
             * @author Danilo Dorotheu
             * @param  string key
             * @param  mixed  val
             * @return mixed 
             */
            $.jsSessao = function(key, val) {
                if(!options.sessao) {
                    options.sessao = {};
                }

                if(val) {
                    options.sessao[key] = val;
                }
                return options.sessao[key] || false;
            }

            /**
             * Notificador de mensagens do sistema
             * @version 1.0
             * @author Danilo Dorotheu
             * @see public/css/main.scss contem css (#notify) desta funcionalidade
             * @todo Precisa implementar um controle para quantidade de notifies na tela
             * @param {string} options.type Tipo do notificador (info|danger|warning|success)
             * @param {string} options.message Mensagem do notificador
             * @param {title} options.timelife Tempo de vida do notificador
             * @param {int} options.title Titulo da mensagem
             * @returns {jQuery} $notifyFirst Objeto criado pelo notify
             */
            $.notify = function (options) {
                var defaults = {
                    type: "info",
                    message: "Hey There!! I'm working",
                    timelife: 5000,
                    title: false
                };

                $.extend(defaults, options, defaults);

                var $notify = $("#notify");
                //mescla o titulo (se ouver) com o texto
                defaults.message = "<p>" + defaults.message + "</p>";
                if (defaults.title) {
                    defaults.message = "<p><strong>" + defaults.title + "</strong></p>" + defaults.message;
                }

                //Cria um notificador
                var close = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                $notify.prepend('<div class="hideOn alert alert-dismissible alert-' + defaults.type + '">' + close + defaults.message + '</div>');
                var $notifyFirst = $notify.find("div:first");
                $notifyFirst.show("slow");
                setTimeout(function () {
                    $notifyFirst.remove();
                }, defaults.timelife);

                return $notifyFirst;
            };

            /**
             * Exibe as mensagens na tela
             *
             * @param {Object} messages
             * @param {boolean} blockPrevent Quando TRUE, bloqueia mensagens repetidas
             * @returns {Boolean}
             * @autor Danilo Dorotheu
             * @since 07-04-2016
             */
            $.flashMessenger = function (messages, blockPrevent) {
                if (window.blockMessage) {
                    window.blockMessage = false;
                    return false;
                }

                if (blockPrevent) {
                    var local = JSON.stringify(messages);
                    var repos = JSON.stringify(window.messages);
                    if (local == repos) {
                        return false;
                    }
                }

                window.messages = messages;

                $.each(window.messages, function (type, messages) {
                    $.each(messages, function (_, message) {
                        $.notify({
                            "message": message,
                            "type": type
                        });
                    });
                });

                return true;
            }

            $.fn.autocomp = function (options) {
                console.warn("Metodo obsoleto. Utilize $$(element).auto(Object);");
                //action.auto($(this), options);
            };

            $.fn.removeAlert = function () {
                $(this).closest(".form-group").find('.alert').remove();
            };

            /**
             * Limpa os campos do formulario
             * @author Danilo Dorotheu
             * @param {Object} conditions Condicoes de exclusao
             * @param {array} conditions.ignore Lista com ID's a serem ignorados
             * @returns {jQuery Element} this
             */
            $.fn.clearForm = function (conditions) {
                var $form = $(this);
                var cond = {
                    ignore: [],
                };
                $.extend(cond, conditions, cond);

                //Limpa inputs que não estao na lista do ignore
                $form.find("input").filter(function () {
                    return $.inArray($(this).attr("id"), cond.ignore) < 0;
                }).val("");

                //Limpa textarea que não estao na lista do ignore
                $form.find("textarea").filter(function () {
                    return $.inArray($(this).attr("id"), cond.ignore) < 0;
                }).val("");

                //Limpa os Selects que não estão na lista do ignore
                $form.find('select').filter(function () {
                    return $.inArray($(this).attr("id"), cond.ignore) < 0;
                }).val($('options:first').val());

                return $form;
            };

            /**
             * Gera erro na tela caso um campo ou elemento
             * não esteja preenchido corretamente.
             * OBS: Alias para a funcao makeDivError
             * @extends addAlert
             * @author Danilo Dorotheu
             * @param {type} msg Mensagem do Erro
             * @param {string} id Adiciona ID na div do erro
             * @param {boolean} supress mostra ou omite natureza do erro
             */
            $.fn.addAlertByForm = function (msg, id, supress) {
                $(this).closest(".form-group").addAlert(msg, id, supress);
            };

            /**
             *
             * @param {type} msg
             * @param {type} id
             * @param {type} supress
             */
            $.fn.addAlert = function (msg, id, supress) {
                var temp = options["temp"] = options["temp"] || [];
                temp["errorCount"] = temp["errorCount"] || 0;
                var compl;
                var $el = $(this);

                if ($el.find('.alert').size() === 1)
                    return;

                msg = msg || '';// Arruma o paramentro de msg

                if (id === false) { // Redireciona paramentro id para supress
                    supress = false;
                    id = null;
                }

                if (id === null) { // Gera o id para a div alerta caso null sera acrecentado na variavel numerica global
                    temp["errorCount"]++;
                    id = 'erro' + temp["errorCount"];
                }
                if (supress === false) { // decide se coloca complemente padra de erro
                    compl = "</div>";
                } else {
                    compl = " Não pode estar em branco</div>";
                }
                $el.append('<div class="alert alert-danger" role="alert" id="' + id + '">' + msg + compl);
            };

            /**
             * SetValue 2.0
             *
             * Seta texto em qualquer tipo de campo
             * @version 2.0
             * @author Danilo Dorotheu
             * @param {string} value : Valor a ser setado
             * @returns {jQuery} this
             */
            $.fn.setValue = function (value) {
                var $el = $(this);
                switch ($el.prop("tagName")) {
                    case "INPUT":
                        $el.val(value);
                        //>
                        break;
                    case "SELECT":
                        $el.val(value);
                        if ($el.val() != value) {
                            var value = $.trim(value).toUpperCase();
                            $el.find("option").filter(function () {
                                return $.trim($(this).text()).toUpperCase() == value;
                            }).prop('selected', true);
                        }
                        break;
                        //>
                    case "TEXTAREA":
                        $el.text(value);
                        break;
                        //>
                    default:
                        break;
                }
            };

            $.fn.getValue = function () {
                var $el = $(this);
                switch ($el.prop("tagName")) {
                    case "INPUT":
                        return $el.val();
                        break;
                    default :
                        return "NOTHING";
                        break;
                }
            };

            /**
             * Gera modal na pagina
             * @version 1.1
             * @author Danilo Dorotheu
             * @see \Tcmed\Entity\FuncionariosController (getModalAction)
             * @see \view\tcmed\funcionarios\get-modal.phtml
             * @param {string} params.idModal : Id do modal a ser criado
             * @param {string} params.titleModal : Titulo do modal
             * @param {boolean} params.execModal : Pode ser usado pra cancelar
             * esta funcao, caso possua alguma condicao que impossibilite o
             * envio deste para o servidor
             * @param {string} params.bodyModal : id ou classe da div contendo o
             * corpo do modal
             * @param {array}  params.buttons : Botões do modal
             * @param {Object} params.buttons.itemObjeto : Objeto contendo
             * parametros do botao
             * @param {string} params.buttons.itemObjeto.type : Tipo do botão do
             *  modal
             * @param {string} params.buttons.itemObjeto.text : texto do botão
             * do modal
             * @param {string} params.buttons.itemObjeto.id : ID do botão do
             * modal
             * @param {string} params.buttons.itemObjeto.class : Classes do
             * botão do modal
             * @params {function} params.preModal : Funcao de execucao antes
             * load do modal
             * @params {function} params.callback : Funcao de execucao apos load
             *  do modal
             */
            $.callModal = function (params) {
                var options = {
                    execModal: true,
                    preModal: function () {
                    },
                    callback: function () {
                    },
                    url: "noUrlSetted",
                    idModal: "modalTeste",
                    titleModal: "modal",
                    bodyModal: false,
                    buttons: [],
                };
                $.extend(options, params, options);

                options.preModal(options);

                if ($("#modalContainer").length == 0) {
                    $("#inter").append('<div id="modalContainer">teste</div>');
                }
                var $modalContainer = $("#modalContainer");
                if (options.execModal && options.bodyModal) {
                    action.processa({
                        url: params.url,
                        type: "POST",
                        data: {
                            idModal: options.idModal,
                            titleModal: options.titleModal,
                            bodyModal: options.bodyModal,
                            buttons: options.buttons,
                        },
                        callback: function (data) {
                            $modalContainer.html(data);
                            $modalContainer.find(".modal-body").html($(options.bodyModal).html());
                            var $modal = $modalContainer.find(".modal");
                            $modal.modal("show");
                            params.callback($modal, options);
                        }
                    });
                }
            }

            /**
             * Fixa dados no html
             * (Trata erro de salvar pagina)
             *
             * @returns {undefined}
             */
            var fixDataForm = function () {
                //Transforma o container em objeto JQuery
                var cont = $(settings.pagin.cont);

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
             * Autocomplete 2.1
             *
             * Auto completa os campos com valores da base de dados
             * @author Danilo Dorotheu
             * @link https://www.devbridge.com/sourcery/components/jquery-autocomplete/
             * @version 2.0
             * 2.0:
             * - Independencia da funcao (que era de responsabilidade
             * do actions e jQuery. Com a versao 2.0, passa a ser de
             * responsabilidade apenas do jQuery;
             * - Remoção do meio de chamada (keypress) deste metodo;
             * - Adicao do 'deferRequestBy:2000' (Tempo de espera entre
             * as requisicoes AJAX;
             * - Adicao do 'autoSelectFirst', que preenche o campo
             * com o primeiro item da lista encontrado pelo AJAX;
             * - Abastração de trechos, visando ganho de desempenho;
             * 2.1:
             * -Adicao do metodo filter, para passar params dinamicos
             * @param {Object} options : Parametros externos
             * @return {jQuery} this
             */
            $.fn.auto = function (options) {
                //Default Parameters
                var defaults = {
                    serviceUrl: "/server.php",
                    type: "POST",
                    paramName: "data",
                    primary: "value",
                    orientation: "auto",
                    noCache: true,
                    responseTo: {},
                    callback: false,
                    params: {},
                    deferRequestBy: 500,
                    showCols: []
                };
                //Extende os parametros internos
                //com os externos
                $.extend(defaults, options, defaults);
                defaults["params"]["column"] = defaults["primary"];

                /**
                 * Caso exista um botão cujo qual a classe deste seja .list-all-autocomp,
                 * então implementar o gatilho de buscar todos os valores do servidor
                 */
                var elthis = $(this);
                var button = elthis.parent().find(".list-all-autocomp");
                if (button.length) {
                    button.click(function () {
                        elthis.focus().val("*").keyup();
                    });
                }

                /**
                 * Método responsável por converter o resultado obtido pelo AJAX
                 * OBS: E a porta de entrada do resultado vindo do servidor
                 * @param {Obtect} response
                 * @param {string} originalQuery Valor que contem no campo de consulta
                 * @returns {object}
                 */
                defaults["transformResult"] = function (response, originalQuery) {
                    return {
                        suggestions: $.map(JSON.parse(response), function (dataItem) {
                            return dataItem;
                        })
                    };
                };
                /**
                 * Alias para a funcao onSearchStart,
                 * que adiciona valores ao 'param' dinamicamente,
                 * a cada letra inserida no campo de consulta.
                 */
                if (defaults["filters"]) {
                    defaults["onSearchStart"] = defaults["filters"];
                }
                /**
                 * Método responsável por tratar dado selecionado
                 * na lista de resultados obtidos pela requisicao
                 * AJAX. Tambem e responsavel por tratar os campos
                 * de resposta
                 * @param {Object} suggestion
                 */
                defaults["onSelect"] = function (suggestion) {
                    $.each(defaults.responseTo, function (chaveDoAjax, campoRetorno) {
                        $.each(campoRetorno, function (_, idCampo) {
                            $$(idCampo).setValue(suggestion[chaveDoAjax]);
                        });
                    });
                    /**
                     * Executa funcao após setar campos
                     */
                    if (defaults.callback) {
                        if (defaults.callback.run) {
                            defaults.callback.run(suggestion, $(this));
                        } else {
                            defaults.callback(suggestion, $(this));
                        }
                    }
                };
                //Inicializa autocomplete
                $(this).autocomplete(defaults);
            };// FIM METODO

            $.notification = function (options) {
                if (window.Notification) {
                    if (!Notification.permission) {
                        Notification.requestPermission();
                    }

                    if (Notification.permission) {
                        var notification = new Notification(options.title, options);
                        return notification;
                    }
                }
            }

            /**
             * Executa ação quando o botão enter for clicado
             *
             * @author Danilo Dorotheu
             * @param {function} funcExec funcao para executar quando evento ocorrer
             */
            $.fn.clickEnter = function (funcExec) {
                $(this).keyup(function (e) {
                    if (e.which == 13 && funcExec) {
                        funcExec();
                    }
                });
            };

            /**
             *
             * Função padrão responsavel por ordenar as colunas do helper table do ZF2
             * @author Paulo Watakabe <watakabe05@gmail.com>
             * @version 1.0
             * @since 25-04-2016
             * @param {string} url
             * @param {boolean} frm
             * @returns {nothing}
             */
            $.orderCol = function (col, obj) {
                if (col == null || col == '') {
                    console.log('ordenador coluna invalido ' + col);
                    return;
                }
                $(obj).sendForm(null, "tableObs : 'Ordenador', addOrder: '" + col + '\'');
                preventDoubleClick = true;
            };

            //Inicializador do Messenger
            module.Messenger.init();

            defaults.events();
        },
        /**
         * @todo OBSOLETOs
         * @returns {undefined}
         */
        auto: function () {
            console.log("Obsoleto!!!!!!");
        },
        nextFocus: function (obj) {
            var inputs = $(obj).closest('form').find(':input:visible');
            var ind = inputs.index(obj);
            var i = 1;
            var flag = true;
            while (flag) {
                ele = inputs.eq(ind + i);
                tp = ele.prop('type');
                if (ele.prop('disabled')) {
                    i++;
                } else {
                    switch (tp) {
                        case 'button':
                            i++;
                            break;
                        default:
                            ele.focus();
                            flag = false;
                    }
                }
            }
            return;
        },
        /**
         * REMOVER (OU NAO)
         * @param {type} elem
         * @param {type} arr
         * @returns {unresolved}
         */
        removeElementArray: function (elem, arr) {
            var aux = arr.indexOf(elem);
            if (aux > -1) {
                arr.splice(aux, 1);
            }
            return arr;
        },
        /**
         * Retorna o texto do campo
         * TODO: remover
         * @param  {jQuery|String} el: Elemento a ser inspecionado
         * @return {String} text: Texto contido no elemento
         * @author Danilo Dorotheu
         */
        getText: function (el) {
            el = this.getId(el);
            el.find("*").remove();              //Remove todos os elementos filhos
            var text = el.val() || el.html();  //Recupera texto de DIV, A, INPUT...
            return $.trim(text);                 //Retorna texto sem espacos no comeco e fim
        },
        /**
         * Retorna a data formatada
         * @returns {String} data formatada
         */
        formatData: function () {
            var d = new Date();
            return d.getHours() + ":" + d.getMinutes();
        },
        /**
         *
         * @param {type} el
         * @returns {String}
         */
        getVal: function (el, text) {
            var $el = this.getEl(el);
            switch ($el.prop("tagName")) {
                case "INPUT":
                    var value = $el.val();
                    return value;
                    break;
                default :
                    return "NOTHING";
                    break;
            }
        },
        /**
         *
         * @see example: \Application\view\tcmed\funcionarios\form.phtml
         * @param {type} obj
         * @returns {undefined}
         */
        modalListener: function (obj) {
            action.processa({
                url: obj["url"],
                func: function (data) {
                    var $modalData = action.getEl(obj["cont"]);

                    $modalData.html(data);
                    $modalData.find(".modal").modal("show");

                    obj["callback"]($modalData);
                }
            });
        },
        /**
         *  ACTION
         *
         * @param  {jQuery} element: Elemento a ser observado
         * @param  {Object} params: Parametros
         * @return {[type]}         [description]
         */
        searchTable: function (element, params) {
            var requestServer = this.requestServer;
            //Variaveis locais
            defaults = {
                table: "#table-search",
                url: "",
                type: "POST",
                elementReturn: "",
            };
            //mescla os parametros
            $.extend(params, params, defaults);

            /**
             * [search description]
             * @return {[type]} [description]
             */
            function search() {
                //Faz request no servidor
                requestServer({
                    url: defaults.url,
                    type: defaults.type,
                    data: {
                        value: $(element).val()
                    }

                }).success(function (results) {
                    //Processa JSON
                    process(JSON.parse(results));
                });
            }
            /**
             * Controla os eventos
             * @return {[type]} [description]
             */
            function events() {
                event.keyup(element, function () {
                    search();
                });
            }
            /**
             * INITIALIZE SUPER ACTION
             */
            events();
        }

    }
})(jQuery, window.App.SETTINGS, actionEvents);
