
/*
 * Project: Modules.js
 * Description: Centralizador de Módulos do sistema
 * Date: 24_06_2015
 */

/**
 * Initialize Actions
 * 
 */
if (!window.App) {
    window.App = {};
    window.App.SETTINGS = {};
}

var module = {};

/**
 * Paginador 2.0
 * Controla a paginação do sistema
 * 
 * @author Danilo Dorotheu <danilo.dorotheu@live.com>
 * @since 09/03/2016
 * @type function
 */
module.Paginador = (function (window, document, $, settings) {

    /**@private*/
    var _def = {
        /**@type int Cursor (ponteiro) do array para add ou excluir paginas*/
        cursor: 0,
        /**@type int Final do array*/
        end: 0,
        /**@type int Inicio do array*/
        ini: 0,
        /**@type int Tamanho maximo do array*/
        max: 9,
        /**@type string Botao Anterior*/
        back: "#goback",
        /**@type string Botao Proximo*/
        next: "#goup",
        /**@type string Botao Atualizar*/
        refresh: "#refresh",
        /**@type string Container cujo qual possui o form na view*/
        container: "body",
        /**@type object Gaveta de armazenamento das paginas e requests salvos*/
        gaveta: {},
    };

    /**
     * Fixa os dados preenchidos no formulario e retorna html
     * 
     * @private
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @returns {string}
     */
    function getHtmlPagina() {
        var $container = $(_def.container);

        //Pra cada input dentro do $container...
        $container.find("input").each(function () {
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

        //Pra casa select dentro do $containerainerainer...
        $container.find("select").each(function () {
            //Remover :selected dos options nao selecionados
            $(this).find("option").not($(this)
                    .find("option:selected")).removeAttr("selected");
            //Adicionar :selected dos options selecionados
            $(this).find("option:selected").attr("selected", "selected");
        });

        //Pra cada textarea dentro do $containerainer...
        $container.find("textarea").each(function () {
            //Definir texto utilizando o value
            $(this).text($(this).val());
        });

        return $container.html();
    }
    /**
     * Retorna a pagina salva baseada no index
     *
     * @param {int} index
     * @returns {_def.gaveta.paginaSalva}
     */
    function getPaginaSalva(index) {
        return _def.gaveta[index].paginaSalva;
    }
    /**
     * Retorna o request salvo baseado no index
     * 
     * @param {int} index
     * @returns {_def.gaveta.requestSalvo}
     */
    function getRequestSalvo(index) {
        return _def.gaveta[index].requestSalvo;
    }
    /**
     * Salva a página no histórico
     * 
     * Se não houver um obj como parametro, então utilizar o request já salvo na
     * posicao do cursor. Caso contrario, usar o request contido no obj.
     * Então, salvar dados na gaveta
     * 
     * @private
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @param {object} obj
     * @returns {void}
     */
    function salvarHistorico(obj) {
        var request = false;

        if (!obj) {
            //console.log("salvarHistorico > Nenhum objeto definido")
            request = getRequestSalvo(_def.cursor);
        } else {
            //console.log("salvarHistorico > Objeto definido")
            request = obj['request'];
        }

        _def.gaveta[_def.cursor] = {
            requestSalvo: request,
            paginaSalva: getHtmlPagina()
        };

        //console.log("salvarHistorico > Salvando " + request.url + " na posicao " + _def.cursor + " do cursor");
    }
    /**
     * Adiciona um novo histórico no Paginador
     * Quando a gaveta está vazia, então deve-se apenas salvar esta pagina.
     * Caso a gaveta já contenha dados, então salvar a pagina atual, incrementar
     * cursor, salvar proxima pagina, habilitar o botao back e desabilitar o botao
     * next, além de inspecionar se o limite do histórico foi alcançado e definir
     * o final do array (_def.end) com o mesmo valor contido no cursor (_def.cursor)
     * 
     * @private
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @see Paginador::salvarHistorico para entender a logica de salvamento da pagina
     * @param {object} obj
     * @returns {void}
     */
    function addHistorico(obj) {
        if (gavetaVazia()) {
            //console.log("addHistorico > Primeira inclusao na gaveta");

            salvarHistorico(obj);

        } else {
            //console.log("addHistorico > Mais uma inclusão na gaveta");

            salvarHistorico();

            _def.cursor++;

            //Se estourar o tamanho do array, então remover o 1º elemento do array
            if (_def.end - _def.ini > _def.max) {
                _def.gaveta[_def.ini] = false;
                _def.ini++;
            }

            $(_def.back).attr("disabled", false);
            $(_def.next).attr("disabled", true);

            _def.end = _def.cursor;

            salvarHistorico(obj);
        }
    }
    /**
     * Exibe a pagina salva na exata posição do cursor
     * 
     * @private
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @returns {void}
     */
    function exibirPagina() {
        //console.log("exibirPagina > Exibindo " + _def.cursor)
        window.blockMessage = true;
        $(_def.container).html(getPaginaSalva(_def.cursor));
    }
    /**
     * Avança para a pagina contida na gaveta cujo qual possui um cursor posterior
     * ao atual, ou seja, a pagina salva posteriormente
     * 
     * OBS: A pagina atual também é salvada. Desta forma, qualquer valor inserido
     * na pagina atual ficará salvo caso a pagina seja retornada. Além disso, caso
     * o cursor esteja na primeira posição, então deve-se inviabilizar o uso do
     * botão back, dado ao fato que não há mais páginas para serem retornadas
     * no sistema. E por ultimo, independente da situação, o botão next é habilitado
     * para permitir o seu uso no sistema.
     * 
     * Então, a proxima pagina é exibida no sistema
     * 
     * @private
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @returns {void}
     */
    function voltarPagina() {
        salvarHistorico();

        _def.cursor--;
        //console.log("voltarPagina > Voltando para " + _def.cursor);

        $(_def.next).attr("disabled", false);

        if (_def.cursor === _def.ini) {
            $(_def.back).attr("disabled", true);
        }

        exibirPagina();
    }
    /**
     * Volta para a pagina contida na gaveta cujo qual possui um cursor anterior
     * ao atual, ou seja, a pagina salva anteriormente
     * 
     * OBS: A pagina atual também é salvada. Desta forma, qualquer valor inserido
     * na pagina atual ficará salvo caso a pagina seja avançada. Além disso, caso
     * o cursor esteja na ultima posição, então deve-se inviabilizar o uso do
     * botão next, dado ao fato que não há mais páginas para serem avançadas 
     * no sistema. E por ultimo, independente da situação, o botão back é habilitado
     * para permitir o seu uso no sistema.
     * 
     * Então, a proxima pagina é exibida no sistema
     *  
     * @private
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @returns {void}
     */
    function avancarPagina() {
        salvarHistorico();

        _def.cursor++;
        //console.log("avancarPagina > Indo para " + _def.cursor);

        $(_def.back).attr("disabled", false);

        if (_def.cursor === _def.end) {
            $(_def.next).attr("disabled", true);
        }

        exibirPagina();
    }
    /**
     * Recarrega a pagina atual contida na gaveta, a partir da posicao atual 
     * do cursor.
     * Foi definido também o atributo "savePage" do requestSalvo como FALSE, para
     * impossibilitar um novo salvamento de pagina. Desta forma, apenas a pagina 
     * é recarregada, sem qualquer salvamento adicional 
     * 
     * @private
     * @see Actions::$.processa()
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @returns {void}
     */
    function recarregarPagina() {
        getRequestSalvo(_def.cursor)["savePage"] = false;
        //console.log("recarregar pagina > Na posicao " + _def.cursor + ", recarregar URL " + getRequestSalvo(_def.cursor).url);
        $.processa(getRequestSalvo(_def.cursor));
    }
    /**
     * Verifica se a gaveta encontra-se vazia e retorna resultado
     * 
     * @private
     * @returns {Boolean}
     */
    function gavetaVazia() {
        return Object.keys(_def.gaveta).length < 1;
    }
    /**
     * Compara o request atual com o ultimo salvo na gaveta
     * 
     * @private
     * @param {string} url2 
     * @param {string} url1 
     * @returns {Boolean}
     */
    function urlEquals(url1, url2) {
        //Se houver parametros no auxNovo após '?', remover esta substring
        url1 = url1.split("?")[0];
        url1 = url1.toUpperCase();

        //Se houver parametros no auxGaveta após '?', remover esta substring
        url2 = url2.split("?")[0];
        url2 = url2.toUpperCase();

        //console.log("urlEquals > " + url1);
        //console.log("urlEquals > " + url2);

        return url1 === url2;
    }
    /**
     * Responsável por gerir todos os eventos jQuery referente ao Paginador, 
     * como por exemplo os botões de voltar, de avançar e de atualizar.
     * A partir destes eventos, são chamadas as funcoes privadas cujo quais devem
     * executar a operacao solicitada pelo evento
     * 
     * @private
     * @event
     * @author Danilo Dorotheu <danilo.dorotheu@live.com>
     * @returns {void}
     */
    function eventManager() {
        /**
         * Avança o historico de paginação
         * 
         * @private
         * @see Paginador::avancarPagina()
         * @event
         */
        $(_def.next).click(function () {
            avancarPagina();
        });

        /**
         * Volta o historico de paginação
         * 
         * @private
         * @see Paginador::voltarPagina()
         * @event
         */
        $(_def.back).click(function () {
            voltarPagina();
        });

        /**
         * Atualiza a pagina
         * 
         * @private
         * @see Paginador::recarregarPagina()
         * @event
         */
        $(_def.refresh).click(function () {
            recarregarPagina();
        });
    }
    /**
     * Funções publicas do Paginador
     * 
     * @public functions
     * @return {object} Funcoes publicas
     */
    return {
        /**
         * Inicializador
         * 
         * Seta as opções defaults do Paginador e inicializa o eventManager
         * OBS: Os botões back e next são desabilitados inicialmente, até 
         * que uma nova página seja inserida (pelo usuário)
         * 
         * @public 
         * @param {object} params Objeto contendo os parametros utilizados pelo paginador
         * @returns {void}
         */
        init: function (params) {
            this.setOptions(params);

            eventManager();

            $(_def.back).attr("disabled", true);
            $(_def.next).attr("disabled", true);

            return this;
        },
        /**
         * Salva uma nova página
         * OBS: Se a gaveta não for mais vazia e o request for o mesmo comparado
         * ao anterior, então não salvar a pagina
         * 
         * @public
         * @see Paginador::salvarHistorico()
         * @param {object} obj Objeto contendo os dados a serem salvos na gaveta
         * @returns {void}
         */
        addHistorico: function (obj) {
            //console.log("(public) addHistorico > Iniciando inclusao da nova URL " + obj["request"].url);

//            if (!gavetaVazia() && urlEquals(obj["request"].url, getRequestSalvo(_def.cursor).url)) {
//                console.log("urlEquals > Atenção, você ta salvando uma pagina com a mesma URL anterior. Pagina não será salva!");
//                return false;
//            }

            addHistorico(obj);
        },
        /**
         * Seta os parametros defaults do sistema
         * 
         * @param {object} params Objeto contendo os parametros utilizados pelo paginador
         * @returns {object} _def Objeto contendo os parametros já setados no Paginador
         */
        setOptions: function (params) {
            $.extend(_def, params, _def);
            return _def;
        }
    };
})(window, document, jQuery, App.SETTINGS);

/**
 * Messenger
 * Controla o sistema de mensagens lateral do sistema
 * 
 * @author Danilo Dorotheu <danilo.dorotheu@live.com>
 * @type function
 */
module.Messenger = (function (window, document, $, options, event) {
    /**
     * 
     * @private
     * @type Object
     */
    var _els = {
        /**@property {string} container ID do container geral cujo qual hospeda o messenger*/
        container: "#messenger-container",
        /**@property {string} index ID da janela index*/
        index: "#window-index",
        /**@property {string} salaChat ID da janela salaChat*/
        salaChat: "#window-chat",
        /**@property {string} listaView ID da janela listaView*/
        listaView: "#window-lista",
        /**@property {string} listaContatosGrupo ID da area cujo qual hospeda a lista de contatos do grupo*/
        listaContatosGrupo: "#lista-contatos-grupo",
        /**@property {string} listaContatos ID da area cujo qual hospeda a lista de contatos do meu usuário*/
        listaContatos: "#lista-contatos",
        /**@property {string} listaContatos ID da area de mensagens da salaChat*/
        areaMensagem: "#area-mensagem",
        /**@property {string} listaContatos ID da area cujo qual informa o nome do contato da salaChat*/
        nomeTopo: ".nome-topo",
        /**@property {string} listaContatos ID da area cujo qual contém a classe do bootstrap para definir status do contato*/
        statusTopo: ".status-topo",
        infoContato: "#info-contato",
        btnExibeTodos: ".view-all",
        btnExibeOnline: ".view-online",
        btnExibeGrupos: ".view-groups",
    };
    /**
     * 
     * @private
     * @type Object
     */
    var _def = {
        /**@property {int} server rota do servidor que irá receber as requisições*/
        server: "/app/messenger/",
        /**@property {int} intervaloMensagens Intervalo entre as requisições de atualização da lista de mensagens*/
        intervaloMensagens:       10000,
        /**@property {int} intervaloListaContatos Intervalo entre as requisições de atualização da lista de contatos*/
        intervaloListaContatos:   60001,
        /**@property {int} intervaloAtualizaStatus Intervalo entre as requisições de atualização do status*/
        intervaloAtualizaStatus: 100003,
        /**@property {Object} status Tabela de conversão de status para as tags utilizadas no bootstrap*/
        /**@property {Object} tipo Tipos de usuários*/
        tipo: {
            "usuario": "user",
            "grupo": "group",
            "inexist": "user-plus"
        },
        status: {
            "online": "success",
            "busy": "danger",
            "offline": "default",
            "grupo": "primary",
            "inexist": "default",
            "alerta": "warning"
        },
        flag: true,
        /**@property {boolean} open Quando true, faz com que o chat seja aberto sempre que o sistema for aberto*/
        open: true,
        /**@property {boolean} requested Não enviar outra requisição de mensagem até a ultima ser respondida ou dar time out */
        requested : false,
        jobTimeOut : false
    };

    /**
     * @private
     * @var {Object} _agenda Lista de contatos contendo todos os usuários/grupos cujo quais são meus contatos
     */
    var _agenda = [];
    /**
     * @private
     * @var {Object} _info Local de armazenamento de dados utilizados exclusivamente pelo sistema
     */
    var _info = {};

    /**
     * Retorna todos os usuários da agenda ou um usuário específico quando o ID
     * deste usuário for definido no parametro
     * - É retornado o primeiro item do array resultante da pesquisa. Se este
     * não existir, então retorna false
     * 
     * @private
     * @param {int|null} id
     * @returns {Object|boolean}
     */
    function getUsuario(id) {
        var usuario = $.grep(_agenda, function (data) {
            var verificaId = (id) ? data.id == id : true;
            return (verificaId && data.tipo == "usuario");
        });

        return (usuario[0]) ? usuario[0] : false;
    }
    /**
     * Retorna todos os grupos da agenda ou um grupo específico quando o ID
     * deste grupo for definido no parametro
     * - É retornado o primeiro item do array resultante da pesquisa. Se este
     * não existir, então retorna false
     * 
     * @private
     * @param {int|null} id
     * @returns {Object|boolean}
     */
    function getGrupo(id) {
        var grupo = $.grep(_agenda, function (element) {
            var verificaId = (id) ? element.id == id : true;
            return (verificaId && element.tipo == "grupo");
        });

        return (grupo[0]) ? grupo[0] : false;
    }
    /**
     * Retorna o contato baseado no ID e no tipo
     * - Se não houver os parametros ID e tipo, então retornar lista de contatos
     *  completa;
     * - Se não houver tipo, então assumir valor padrão (usuario);
     * - Se não houver ID, então retornar todos os contatos do mesmo tipo definido
     * - Se ainda assim, nenhum for localizado, então retornar false
     * 
     * @private
     * @param {int} id
     * @param {int} tipo
     * @returns {Array|Boolean}
     */
    function getContatos(id, tipo) {
        if (!id && !tipo) {
            return _agenda;
        }

        tipo = (tipo) ? tipo : "usuario";

        var contato = $.grep(_agenda, function (element) {
            var verificaId = (id) ? element.id == id : true;
            var verificaTipo = (tipo) ? element.tipo == tipo : true;

            return (verificaId && verificaTipo);
        });

        return (contato[0]) ? contato[0] : false;
    }
    /**
     * Retorna o usuário contido no grupo definido no parametro
     * 
     * @private
     * @param {int} idGrupo ID do grupo
     * @param {int} idUsuario ID do usuário do grupo
     * @returns {Object|boolean}
     */
    function getUsuarioInGrupo(idGrupo, idUsuario) {
        var grupo = getGrupo(idGrupo);

        var usuario = $.grep(grupo.usuarios, function (data) {
            return (idUsuario) ? data.id == idUsuario : true;
        });
        return (usuario[0]) ? usuario[0] : false;
    }
    /**
     * Retorna o contato (usuario ou grupo) aberto na sala de chat
     * 
     * @private
     * @returns {Object}
     */
    function getContatoAberto() {
        if (!_info.contatoAberto) {
            return false;
        }

        return getContatos(_info.contatoAberto.id, _info.contatoAberto.tipo);
    }
    /**
     * Seta o contato aberto na sala de chat
     * 
     * @private
     * @param {Object} contato
     * @returns {void}
     */
    function setContatoAberto(contato) {
        _info.contatoAberto = contato;
    }
    /**
     * Remove o alerta do contato
     * 
     * @param {type} contato
     * @returns {undefined}
     */
    function removeAlerta(contato) {
        contato.alerta = false;

        var $btnWarning = $(_els.listaContatos).find(".btn-warning");
        if ($btnWarning.length == 1) {
            redefineStatus("#messenger", "btn", _def.status[contato.status]);
        }
    }
    /**
     * Gera a notificação para o usuário
     * 
     * @param {Object} contato
     * @param {Object} msg
     * @param {boolean} param
     * @returns {void}
     */
    function addAlerta(contato, msg) {
        contato.alerta = true;

        redefineStatus("#messenger", "btn", "warning");

        _els.notification = $.notification({
            title: contato.nome,
            body: msg.mensagem
        });
        /**
         * Adiciona o evento click no notificador. 
         * 
         * @event
         */
        _els.notification.onclick = function () {
            var idBotao = "#" + contato.tipo + "-" + contato.id;
            $(idBotao).click();
        }

//        if (msg && param) {
//        }


        //Definir avisos de chegada de mensagem
    }
    /**
     * Entrega a mensagem para o contato
     * - Se não for definido o parametro contato, então ler contato aberto
     * - Se o contato nunca recebeu mensagens, então criar a area de mensagens dele
     * - O mensageiro alerta o contato de que recebeu uma nova mensagem
     * 
     * @private
     * @param {Object} mensagem do usuário
     * @param {Object} contato que irá receber a mensagem
     * @returns {void}
     */
    function carteiro(mensagem, contato) {
        if (!contato) {
            contato = getContatoAberto();
        }

        var meuUsuario = getMeuUsuario();

        if (!contato.mensagens) {
            contato.mensagens = [];
        }
        //Entrega a mensagem
        contato.mensagens.push(mensagem);

        //Alerta que foi entregue uma nova mensagem
        if (getContatoAberto().id != contato.id) {
            addAlerta(contato, mensagem);
        }
    }
    /**
     * Retorna o HTML do item da lista de contatos baseado nos parametros definidos
     * neste contato (tais como o tipo, o status, se há alertas...)
     * 
     * @private
     * @param {Object} contato que irá ocupar o item da lista
     * @returns {string} html do item da lista de contatos
     */
    function getItemListaContatos(contato) {
        var iconeAlerta = (contato.alerta) ? '<i class="fa fa-exclamation"></i> ' : '';
        var iconeTipoUsuario = ("usuario" == contato.tipo) ? '<i class="fa fa-user"></i>' : '<i class="fa fa-group"></i>';
        var idContato = contato.tipo + '-' + contato.id;
        var status = (contato.alerta) ? _def.status['alerta'] : _def.status[contato.status];

        return '<button id="' + idContato + '" data-id="' + contato.id + '" class="' + contato.tipo + ' btn btn-block btn-' + status + '">' + iconeAlerta + iconeTipoUsuario + ' ' + contato.nome + '</button>';
    }
    /**
     * Gera o HTML dos botões de todos os contatos (usuários e grupos) e atualiza
     * o campo de lista de contatos
     * - OBS: Se o botão de pesquisar contatos estiver com focus, então não atualizar
     * lista
     * 
     * @private
     * @returns {void|false}
     */
    function atualizaLista() {
        if ($("#text-search").is(":focus")) {
            return false;
        }

        $("#text-search").val("");

        var lista = $.map(getContatos(), function (contato) {
            return getItemListaContatos(contato);
        });
        //console.log(lista.join(""));
        $(_els.listaContatos).html(lista.join(""));
    }
    /**
     * Retorna o HTML da mensagem
     * - Se não houver um contato definido no parametro, entao deduz-se que esta
     * mensagem é minha
     * 
     * @param {Object} msg Mensagem
     * @returns {string}
     */
    function getItemMensagem(msg, contato) {
        var color = "alert-success";
        var autor = contato.nome;
        var user = "0"

        if (contato.nome == getMeuUsuario().nome) {
            color = "alert-info";
            autor = "eu";
            user = "1";
        }

        return '<div class="alert ' + color + ' alert-' + user + '"><strong>' + autor + ' (' + msg.horaEnviado + '):</strong><br>' + msg.mensagem + '</div>';
    }
    /**
     * Sempre que uma nova mensagem é adicionada, o scroll será acionado. Então,
     * as ultimas mensagens poderão ser exibidas
     * 
     * @event
     * @returns {void}
     */
    function scrollAreaMensagens() {
        var contador = $(_els.areaMensagem).scrollTop();

        $(_els.areaMensagem).find("div").each(function () {
            contador += $(this).height();
        });

        $(_els.areaMensagem).animate({
            scrollTop: contador
        }, 500);
    }
    /**
     * Atualiza a area de mensagens da sala de chat. 
     * - Se não houver mensagens no contato, então nada mais será executado;
     * - Define como padrão, o propietario do log de mensagens como sendo o 
     * contato aberto;
     * - Se for uma mensagem de grupo, então localizar o autor dessa mensagem;
     * - Se o ID do autor da mensagem for igual ao meu ID, então o dono da mensagem
     * sou eu. Sendo assim, não enviar um contato para gerar o HTML da mensagem
     * 
     * @private
     * @returns {void}
     */
    function atualizarMensagens() {
        $(_els.areaMensagem).html("");

        var contato = getContatoAberto();
        var meuUsuario = getMeuUsuario();

        if (contato && janelaAbertaIs("salaChat")) {
            if (!contato.mensagens) {
                return false;
            }
            var mensagens = $.map(contato.mensagens, function (mensagem) {

                if (mensagem.toGrupo) {
                    contato = getUsuarioInGrupo(mensagem.toGrupo, mensagem.fromId);
                    return getItemMensagem(mensagem, contato);
                } else {
                    contato = (mensagem.fromId == meuUsuario.idUser) ? meuUsuario : getUsuario(mensagem.fromId);
                    return getItemMensagem(mensagem, contato);
                }
            });
            $(_els.areaMensagem).html(mensagens.join(""));

            scrollAreaMensagens();
        }
    }
    /**
     * Atualiza todos os contatos cadastrados no sistema
     * - Se a agenda for vazia, então definir a agenda com todos os contatos
     * da lista de contatos;
     * - Se um novo contato for inserido na base, então ele será inserido
     * aqui também;
     * - Se um contato for atualizado na base, então ele será atualizado aqui
     * também, preservando os valores previamente definidos;
     * - Se um contato for deletado da base, então ele será deletado daqui 
     * também;
     * 
     * @private
     * @param {array} listaContatos Lista contendo todos os contatos a serem atualizados
     * @see this::getListaContatos()
     * @returns {array}
     */
    function atualizarAgendaContatos(listaContatos) {
        if (_agenda.length < 1) {
            _agenda = listaContatos;
            return _agenda;
        }

        listaContatos = $.map(listaContatos, function (newContato) {
            var oldContato = getContatos(newContato.id, newContato.tipo);

            //Garante que não irá utilizar o mesmo endereco de memoria do contato já definido
            oldContato = JSON.parse(JSON.stringify(oldContato));

            var auxContato;

            if (!oldContato) {
                return newContato;
            }

            return $.extend(oldContato, newContato);
        });

        _agenda = listaContatos;
        return _agenda;
    }
    /**
     * Atualiza o status dos elementos
     * 
     * @param {String} element Elemento contendo o status a ser alterado
     * @param {String} prefix Prefixo da classe ou ID a ser alterada 
     * @param {String} novoStatus Novo Status [online|offline|ocupado|nenhum]
     * @returns {undefined}
     */
    function redefineStatus(element, prefix, novoStatus) {
        $(element).removeClass(prefix + "-danger");
        $(element).removeClass(prefix + "-success");
        $(element).removeClass(prefix + "-primary");
        $(element).removeClass(prefix + "-warning");
        $(element).removeClass(prefix + "-default");

        $(element).addClass(prefix + "-" + novoStatus);
    }
    /**
     * Atualiza todos os parametros da sala de chat
     * 
     * @private
     * @returns {void}
     */
    function atualizarSalaChat() {
        var contato = getContatoAberto();

        //Atualiza a cor do status do topo
        redefineStatus(_els.statusTopo, "panel", _def.status[contato.status]);

        //Atualiza o nome do contato no topo
        $(_els.nomeTopo).html(contato.nome);

        //Atualiza a area de mensagens
        atualizarMensagens();
    }
    /**
     * Abre a sala de chat para iniciar conversa entre os usuários
     * 
     * @private
     * @param {Object} contato
     * @returns {void|boolean}
     */
    function abrirSalaChat(contato) {
        if (!contato) {
            return false;
        }
        removeAlerta(contato);

        setContatoAberto(contato);

        abrirJanela("salaChat");
        atualizarSalaChat();
    }
    /**
     * Busca no servidor e atualiza a lista de contatos
     * 
     * @private
     * @returns {void}
     */
    function getListaContatos() {
        $.processa({
            url: _def.server + "listaContatosGrupos",
            loader: false,
            savePage: false,
            showError: false,
            callback: function (contatos) {
                contatos = JSON.parse(contatos);
                atualizarAgendaContatos(contatos);
                atualizaLista();
            }
        });
    }
    /**
     * Retorna os dados do meu usuário
     * OBS: Quando o parametro fromServer for true, os dados serão buscados 
     * diretamente do servidos. Então, estes dados serão retornados
     * 
     * @private
     * @param {boolean} fromServer
     * @returns {Object}
     */
    function getMeuUsuario(fromServer) {
        if (fromServer || !_info.meuUsuario) {
            $.processa({
                url: _def.server + "getMeuUsuario",
                loader: false,
                savePage: false,
                callback: function (meuUsuario) {
                    meuUsuario = JSON.parse(meuUsuario);
                    _info.meuUsuario = meuUsuario;
                }
            });
        }

        return _info.meuUsuario;
    }
    /**
     * Busca no servidor e atualiza a lista de mensagens
     * OBS: Quando a mensagem é de grupo e a mensagem recebida foi enviada por
     * este usuário, então este sistema não irá entregar a mansagem.
     * 
     * Adicionado logica para não gerar pedidos de mensagens sem que o ultimo tenha sido respondido.
     * Adicionado logica para que um pedido tem um tempo de vida util não maior que 60s.
     * @author Paulo Watakabe <watakabe05@gmail.com>
     * @since 18-12-2016
     * 
     * @private
     * @returns {void}
     */
    function getListaMensagens() {
        if(_def.requested){
            return;
        }
        _def.requested = true;
        _def.jobTimeOut = setTimeout(function(){
            _def.requested = false;            
        }, 60000);
        $.processa({
            url: _def.server + "getMensagens",
            loader: false,
            savePage: false,
            showError: false,
            callback: function (mensagens) {
                mensagens = (mensagens) ? JSON.parse(mensagens) : {};

                $.each(mensagens, function (aux, mensagem) {
                    var contato;

                    if (mensagem.toGrupo) {
                        if (mensagem.fromId !== getMeuUsuario().idUser) {
                            contato = getGrupo(mensagem.toGrupo);
                            carteiro(mensagem, contato);
                        }
                    } else {
                        contato = getUsuario(mensagem.fromId);
                        carteiro(mensagem, contato);
                    }
                });
                _def.requested = false;
                if(_def.jobTimeOut){
                    clearTimeout(_def.jobTimeOut);
                    _def.jobTimeOut = false;
                }
            }
        });
    }
    /**
     * Busca o HTML do chat no servidor e define o container com este HTML
     * 
     * @returns {void}
     */
    function montarHtmlChat() {
        $.processa({
            url: _def.server + "getHtml",
            loader: false,
            savePage: false,
            async: false,
            callback: function (data) {
                $(_els.container).html(data);
//                $(_els.container).html(data);
                abrirJanela("index");
            }
        });
    }
    /**
     * Abre a janela do parametro e define qual janela foi aberta em _def.janelaAberta
     * 
     * @private
     * @param {string} janela a ser aberta
     * @returns {void}
     */
    function abrirJanela(janela) {
        $(_els.index).hide();
        $(_els.salaChat).hide();
        $(_els.listaView).hide();

        $(_els[janela]).show();
        adjusts(janela);
        _def.janelaAberta = janela;
    }
    /**
     * Testa se a janela passada no parametro é a mesma definida em janelaAberta
     * 
     * @private
     * @param {string} janela
     * @returns {boolean}
     */
    function janelaAbertaIs(janela) {
        return _def.janelaAberta == janela;
    }
    /**
     * Exibe todos contatos do usuario
     * 
     * @private
     * @returns {void}
     */
    function exibirTodos() {
        $(_els.listaContatos).find(".btn").show();
    }
    /**
     * Exibe apenas os contatos online do usuario
     * 
     * @private
     * @returns {void}
     */
    function exibirOnline() {
        $(_els.listaContatos).find(".btn").hide();
        $(_els.listaContatos).find(".btn-success").show();
        $(_els.listaContatos).find(".btn-danger").show();
    }
    /**
     * Exibe todos os grupos do usuario
     * 
     * @private
     * @returns {void}
     */
    function exibirGrupos() {
        $(_els.listaContatos).find(".btn").hide();
        $(_els.listaContatos).find(".btn-primary").show();
    }
    /**
     * Redefine o estado da flag
     * 
     * OBS: Quando false, rejeita qualquer tentativa de request ao servidor. Útil
     * para evitar envios multiplos de request (que normalmente ocorrem quando
     * o servidor trava e a fila de requests aguarda, e então, quando o servidor
     * volta ao estado normal, esta fila dispara várias chamadas, sendo cada 
     * uma delas referente a requisição que permaneceu aguardando)
     * 
     * @param {boolean} flag Valor [true|false] para definir a flag
     * @returns {void}
     */
    function setFlag(flag) {
        _def.flag = flag;
    }
    /**
     * Retorna o estado da flag
     * 
     * OBS: Quando false, rejeita qualquer tentativa de request ao servidor. Útil
     * para evitar envios multiplos de request (que normalmente ocorrem quando
     * o servidor trava e a fila de requests aguarda, e então, quando o servidor
     * volta ao estado normal, esta fila dispara várias chamadas, sendo cada 
     * uma delas referente a requisição que permaneceu aguardando)
     * 
     * @returns {boolean}
     */
    function getFlag() {
        return _def.flag;
    }
    /**
     * Envia a mensagem para o contato
     * OBS: Quando a flag encontra-se indisponível (false), e requisição não será
     * enviada ao servidor.
     * 
     * @see this::setFlag()
     * @see this::getFlag()
     * @private
     * @param {int|String} id do contato
     * @param {String} tipo de contato [user|group]
     * @param {String} mensagem que será enviada
     * @returns {void}
     */
    function enviarMensagem(mensagem) {
        var contato = getContatoAberto();

        if (!getFlag()) {
            return false;
        }

        setFlag(false);

        $.processa({
            url: _def.server + "enviarMensagem",
            type: "POST",
            loader: false,
            savePage: false,
            data: {
                toUser: contato.id,
                tipo: contato.tipo,
                mensagem: mensagem,
            },
            callback: function (mensagem) {
                mensagem = JSON.parse(mensagem);
                carteiro(mensagem);
                $(_els.areaMensagem).append(getItemMensagem(mensagem, getMeuUsuario()));
                scrollAreaMensagens();

                setFlag(true);
            }
        });
    }
    /**
     * 
     * @private
     * @returns {undefined}
     */
    function getHistorico(periodo) {
        var contato = getContatoAberto();
        $.processa({
            url: _def.server + "getHistory",
            type: "POST",
            loader: false,
            savePage: false,
            data: {
                idContato: contato.id,
                tipoContato: contato.tipo,
                periodo: periodo,
            },
            callback: function (mensagens) {
                mensagens = JSON.parse(mensagens);
                contato.mensagens = mensagens;
                setContatoAberto(contato);
                atualizarSalaChat();
            }
        });
    }
    /**
     * Função de apoio para abrir a janela index do messenger
     * 
     * @private
     * @returns {void}
     */
    function abrirIndex() {
        setContatoAberto(false);
        atualizaLista();
        abrirJanela("index");
    }
    /**
     * Seta o status do usuário cujo qual encontra-se logado no sistema
     * 
     * @param {string} newStatus
     * @returns {void}
     */
    function setStatus(newStatus) {
        _info.meuUsuario['status'] = newStatus;
    }
    /**
     * retorna o status do usuário cujo qual encontra-se logado no sistema
     * 
     * @returns {string}
     */
    function getStatus() {
        return _info.meuUsuario['status'];
    }
    /**
     * Envia o status atual do usuário cujo qual encontra-se logado no sistema
     * 
     * @returns {void}
     */
    function sendStatus() {
        $.processa({
            url: _def.server + "sendStatus",
            type: "POST",
            loader: false,
            savePage: false,
            showError: false,
            data: {
                status: getStatus()
            },
            ret: false,
            callback: function (data) {
                // do nothing
            }
        });
    }
    /**
     * Gerencia todos os eventos do chat
     * 
     * @public
     * @returns {void}
     */
    function eventManager() {
        /**
         * Marca o botão online e exibe todos contatos do usuário
         * 
         * @event
         */
        $(".view-all").click(function () {
            //Remove a marcacao do botao marcado atualmente
            $(this).parent().siblings().find(".active").removeClass("active");
            //Adiciona a marcacao no botao exibeOnline
            $(this).addClass("active");

            exibirTodos();
        });
        /**
         * Marca o botão online e exibe todos os grupos do usuário
         * 
         * @event
         */
        $(".view-groups").click(function () {
            //Remove a marcacao do botao marcado atualmente
            $(this).parent().siblings().find(".active").removeClass("active");
            //Adiciona a marcacao no botao exibeOnline
            $(this).addClass("active");

            exibirGrupos();
        });
        /**
         * Marca o botão online e exibe apenas os contatos ativos do usuário
         * 
         * @event
         */
        $(".view-online").click(function () {
            //Remove a marcacao do botao marcado atualmente
            $(this).parent().siblings().find(".active").removeClass("active");
            //Adiciona a marcacao no botao exibeOnline
            $(this).addClass("active");

            exibirOnline();
        });
        /**
         * Fecha a tela da sala de chat e abre index
         * 
         * @event
         */
        $("#fechar-chat").click(function () {
            abrirIndex();
        });
        /**
         * Fecha a tela da sala de chat e abre index
         * 
         * @event
         */
        $("#enviar").click(function () {
            var mensagem = $("#msg-text").val();
            mensagem = $.trim(mensagem);
            if (mensagem.length > 0) {
                enviarMensagem(mensagem);
                $("#msg-text").val("");
            }
            $("#msg-text").focus();
        });
        /**
         * Se a tecla enter for acionada dentro do campo de mensagens, então 
         * acionar o botão enter
         * 
         * @event
         */
        $("#msg-text").clickEnter(function () {
            $("#enviar").click();
        });

        /**
         * Abre a sala de chat para o usuário
         * 
         * @event
         */
        $(document).on("click", ".usuario", function () {
            var idUsuario = $(this).attr("data-id");
            var usuario = getUsuario(idUsuario);
            abrirSalaChat(usuario);
        });
        /**
         * Abre a sala de chat para o grupo
         * 
         * @event
         */
        $(document).on("click", ".grupo", function () {
            var idGrupo = $(this).attr("data-id");
            var grupo = getGrupo(idGrupo);
            abrirSalaChat(grupo);
        });
        /**
         * Pesquisa de contatos
         * OBS: Ao inserir letra no campo text-search, este evento dispara uma busca 
         * em todos os botões da lista localizando o text que estes contém e os 
         * compara com o texto inserido no campo. Caso este texto seja diferente
         * com o já preenchido no campo, então este botão será ocultado.
         * 
         * Caso o campo esteja vazio, exibir todos os botões novamente
         * 
         * @event
         */
        $("#text-search").keyup(function () {
            var $buttons = $("#lista-contatos").find("button");
            var textoChave = $(this).val();
            textoChave = $.trim(textoChave).toUpperCase();

            if (textoChave == "") {
                $buttons.show();
            }

            $buttons.filter(function () {
                var textoBotao = $(this).text();
                textoBotao = $.trim(textoBotao).toUpperCase();
                return textoBotao.indexOf(textoChave) < 0;
            }).hide();
        });
        /**
         * Retorna o histórico de mensagens do dia
         * 
         * @event
         */
        $("#view-dia").click(function () {
            getHistorico("dia");
        });
        /**
         * Retorna o histórico de mensagens da semana
         * 
         * @event
         */
        $("#view-semana").click(function () {
            getHistorico("semana");
        });
        /**
         * Retorna o histórico de mensagens do mês
         * 
         * @event
         */
        $("#view-mes").click(function () {
            getHistorico("mes");
        });
        /**
         * Abre/Fecha o container
         * 
         * @event
         */
        $("#messenger").click(function () {
            if ($(_els.container).is(":visible")) {
                $(_els.container).hide("slow");
                abrirIndex();
            } else {
                $(_els.container).show("slow");
            }
        });
        /**
         * Ao clicar fora do container, fechar o container
         * 
         * @event
         */
        $(_els.container).clickOut(function () {
            if ($(_els.container).is(":visible")) {
                $(_els.container).hide("slow");
                $("#fechar-chat").click();
            }
        });
        /**
         * Seta o status deste usuário para online
         * 
         * @event
         */
        $(".setOnline").click(function () {
            setStatus("online");
            redefineStatus("#messenger", "btn", "success");
            sendStatus();
        });
        /**
         * Seta o status deste usuário para ocupado
         * 
         * @event
         */
        $(".setBusy").click(function () {
            setStatus("busy");
            redefineStatus("#messenger", "btn", "danger");
            sendStatus();
        });
        /**
         * Seta o status deste usuário para offline
         * 
         * @event
         */
        $(".setOffline").click(function () {
            setStatus("offline");
            redefineStatus("#messenger", "btn", "default");
            sendStatus();
        });
    }
    /**
     * Inicializa as funções de timer
     * 
     * @returns {void}
     */
    function timerEvents() {
        /**
         * Cria um intervalo de atualização automático da lista de mensagens
         * 
         * @private
         * @temporalEvent
         * @returns {void}
         */
        setInterval(function () {
            getListaMensagens();
            atualizarSalaChat();

            if (janelaAbertaIs("index")) {
                atualizaLista();
            }
        }, _def.intervaloMensagens);
        /**
         * Cria um intervalo de atualização automático da lista de contatos
         * 
         * @temporalEvent
         * @returns {void}
         */
        setInterval(function () {
            getListaContatos();
        }, _def.intervaloListaContatos);
        /**
         * Cria um intervalo de atualização automático do status do usuário
         * 
         * @temporalEvent
         * @returns {void}
         */
        setInterval(function () {
            sendStatus();
        }, _def.intervaloListaContatos);

    }

    function adjusts(window) {
        switch (window) {
            case "salaChat":
                var $panel = $("#window-chat").find(".panel");
                /**
                 * LEIA: 
                 * Define através do jQuery, as regras de CSS pós load da página
                 * para ajustar a janela de chat. Abaixo, é possível encontrar
                 * o tamanho de diversos elementos, e entre eles, o panelTotal.
                 * Então, o calculo é:
                 * 
                 * panelTotal - (medidaOutrosElementos + espacos restantes)
                 */
                var panelTotal = $panel.outerHeight();
                var panelHeading = $panel.find(".panel-heading").outerHeight();
                var options = $panel.find("#options-area").outerHeight();
                var messages = $panel.find("#msg-area").outerHeight();
                var text = $panel.find("#text-area").outerHeight();
                var button = $panel.find("#button-area").outerHeight();
                var space = 105;

                var novoTamanho = panelTotal - (panelHeading + options + text + button + space);

                $panel.find("#area-mensagem").css({
                    "max-height": novoTamanho,
                    "height": novoTamanho,
                    "margin-bottom": 20
                });
                break;

            default : // Ajusta a lista de contatos na tela
                var messengerTotal = $("#messenger-container").height();
                var secaoTopo = $("#section-topo").outerHeight();
                var space = 20;

                var novoTamanho = messengerTotal - secaoTopo - space;

                $("#lista-contatos").css({
                    "max-height": novoTamanho,
                    "height": novoTamanho,
                    "margin-bottom": 20
                });
                break;
        }
    }
    /**
     * Metodos publicos
     * @param {Object} options
     * @returns {undefined}
     */
    return {
        /**
         * 
         * @public
         * @author Danilo Dorotheu <danilo.dorotheu@live.com>
         * @param {Object} definicoes Objeto contendo as definicoes do chat
         * @returns {this}
         */
        init: function (definicoes) {
            $.extend(_def, definicoes, _def);

            getMeuUsuario();
            montarHtmlChat();
            getListaContatos();

            eventManager();
            timerEvents();

            return this;
        },
        /**
         * Seta os elementos utilizados pelo chat
         * <br>
         * <b>Elementos do Chat:</b>
         * <li>container</li>
         * 
         * @public
         * @author Danilo Dorotheu <danilo.dorotheu@live.com>
         * @param {Object} elementos Objeto contendo os elementos utilizados no chat
         * @returns {this}
         */
        setElementos: function (elementos) {
            $.extend(_els, elementos, _els);

            return this;
        },
        /**
         * Seta o container principal do chat
         * 
         * @public
         * @author Danilo Dorotheu <danilo.dorotheu@live.com>
         * @param {String} container Container principal usado pelo chat
         * @returns {this}
         */
        setContainer: function (container) {
            _els.container = container;

            return this;
        }
    };

})(window, document, jQuery, App.SETTINGS);

/**
 * Cookie Manager
 * Controla o sistema de cookies do sistema
 * 
 * @author Danilo Dorotheu <danilo.dorotheu@live.com>
 * @type function
 */
module.Cookie = (function (window, document, $, settings) {

    var defaults = {
        expires: 1
    };
    /**
     * Retorna a data de expiracao do cookie
     * @param {type} val
     * @returns {String}
     */
    var getExpires = function (val) {
        var d = new Date();
        d.setTime(d.getTime() + (val * 24 * 60 * 60 * 1000));
        return d.toGMTString();
    };
    /**
     * Seta o cookie (privado)
     * @param {Object} cookie
     * @private
     */
    var setCookie = function (cookie) {
        var d = new Date();
        cookie.expires = (cookie.expires) ? cookie.expires : defaults.expires;
        if (cookie.expires != '0') {
            d.setTime(d.getTime() + (cookie.expires * 24 * 60 * 60 * 1000));
            cookie.expires = d.toUTCString();
        }
        cookie.path = (cookie.path) ? cookie.path : '; path=/';
        document.cookie =
                cookie.name
                + "="
                + cookie.value
                + "; expires="
                + cookie.expires
                + cookie.path;
    };


    /**
     * Métodos Públicos
     * @param {type} obj
     * @returns {undefined}
     */
    return {
        /**
         * Salva o cookie
         * @param {type} obj
         * @returns {undefined}
         */
        save: function (obj) {
            if (settings.cookies == undefined) {
                settings.cookies = {}
            }
            ;

            settings.cookies[obj.key] = obj.value;
        },
        /**
         * Denine um novo cookie
         * @param {type} cookie
         * @returns {undefined}
         */
        set: function (cookie) {
            setCookie(cookie);
        },
        erase: function (name) {
            document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;path=/';
        },
        /**
         * Retorna o cookie
         * @param {type} cname
         * @returns {String}
         */
        get: function (cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ')
                    c = c.substring(1);
                if (c.indexOf(name) == 0)
                    return c.substring(name.length, c.length);
            }
            return "";
        }
    }

})(window, document, jQuery, window.App.SETTINGS);

/**
 * SideMenu
 * Controla o menu lateral do sistema
 * 
 * @author Danilo Dorotheu <danilo.dorotheu@live.com>
 * @type 
 */
module.Sidemenu = (function (window, document, $, settings, event) {

    event.click("#toggle", function (e) {
        e.preventDefault();

        if ($(".sidebar").is(':visible')) {
            $('.sidebar').animate({'width': '0px'}, 'slow', function () {
                $('.sidebar').hide();
            });
            $('#page-wrapper').animate({
                'margin-left': '0px'
            }, 'slow');
        } else {
            $('.sidebar').show();
            $('.sidebar').animate({'width': '250px'}, 'slow');
            $('#page-wrapper').animate({
                'margin-left': '250px'
            }, 'slow');
        }
    });

    /**
     * Métodos Públicos
     * @param {type} obj
     * @returns {undefined}
     */
    return {
        /**
         * Esconde o menu da tela
         * @returns {undefined}
         */
        hideSideBar: function () {
            if ($(".sidebar").is(':visible')) {
                $('.sidebar').animate({'width': '0px'}, 'slow', function () {
                    $('.sidebar').hide();
                });
                $('#page-wrapper').animate({
                    'margin-left': '0px'
                }, 'slow');
            }
        }
    };
})(window, document, jQuery, window.App.SETTINGS, actionEvents);

