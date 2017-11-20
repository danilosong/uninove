var $play;

var _pagination = {
    back: "",
    next: "",
    container: "#inter",
    cursor: 0,
    initial: 0,
    final: 0,
    limit: 9,
    pages: {}
};

var _loader = {
    id: "#loader",
    img: "",
    css: {
        position: "absolute",
        width: "50px",
        heigth: "50px",
        top: "50%",
        right: "40%",
        display: "none"
    }
};

$(function () {
    $play = new play({
        loader: {
            img: "/img/loader.gif",
        },
        pagination: {
            back: ".godown",
            next: ".goup",
            container: "#inter",
        }
    });
});

function play(params) {

    this.registerParam = function (localvar, globalvar) {
        $.each(localvar, function (key, value) {
            globalvar[key] = value;
        });
    };

    if (params.loader) {
        this.registerParam(params.loader, _loader);

        $("body").prepend("<img id='" + _loader.id.replace("#", "") + "' src='" + _loader.img + "'>");
        $(_loader.id).css(_loader.css);
    }

    if (params.pagination) {
        this.registerParam(params.pagination, _pagination);
        managerPag();
    }
}
;

var setForm = function (cont) {
    $(cont).find("input").each(function () {
        /* radio e check */
        if ($(this).prop("checked")) {
            $(this).attr("checked", "checked");
        } else {
            $(this).removeAttr("checked");
        }
        /* text, pass... */
        $(this).attr("value", $(this).val());
    });

    $(cont).find("select").each(function () {
        $(this).find("option").not($(this).find("option:selected")).removeAttr("selected");
        $(this).find("option:selected").attr("selected", "selected");
    });

    $(cont).find("textarea").each(function () {
        $(this).text($(this).val());
    });
};

/**
 * Metodo de gerenciamento do log de paginas, que contem
 * os eventos responsaveis por voltar/avancar, alem de 
 * possuir a instrucao de adicionar a primeira pagina no log
 *  
 * @returns {undefined}
 */
function managerPag() {
    //Adiciona a primeira pagina no array (log)
    //_pagination.pages[0] = $(_pagination.container).html();

    //Desabilita os botoes para impedir que o usuario clique-os
    $(_pagination.back).attr("disabled", "true");
    $(_pagination.next).attr("disabled", "true");

    //Eventos de back (voltar pagina) e next (avancar pagina)
    $(_pagination.back).click(function () {
        $(_pagination.next).removeAttr("disabled");

        //decrementa cursor e chama pagina armazenada no log
        setForm(_pagination.container);
        _pagination.pages[_pagination.cursor] = $(_pagination.container).html();
        _pagination.cursor--;
        $(_pagination.container).html(_pagination.pages[_pagination.cursor]);

        //Se for a primeira pagina do log, entao deve ser 
        //desabilitado o botao de voltar
        if (_pagination.cursor == _pagination.initial) {
            $(_pagination.back).attr("disabled", "true");
        }
        ;
    });

    $(_pagination.next).click(function () {
        $(_pagination.back).removeAttr("disabled");

        //Incrementa cursor e chama pagina armazenada no log
        setForm(_pagination.container);
        _pagination.pages[_pagination.cursor] = $(_pagination.container).html();
        _pagination.cursor++;
        $(_pagination.container).html(_pagination.pages[_pagination.cursor]);

        //Se for a ultima pagina do log, entao deve ser
        //desabilitado o botao de voltar
        if (_pagination.cursor == _pagination.final) {
            $(_pagination.next).attr("disabled", "true");
        }
        ;
    });
}
;

/**
 * Método de adicao de paginas no array _pagination.pages
 * OBS: Faz controle do array
 * 
 * @param {string} page pagina 
 * @returns {undefined}
 */
play.prototype.addPage = function (pos) {
    // Se não for passada a pagina como parametro, 
    // pegar conteudo do container default para salvar
    setForm(_pagination.container);
    var page = $(_pagination.container).html();

    //habilita o botao back e desabilita o botao next
    $(_pagination.back).removeAttr("disabled");
    $(_pagination.next).attr("disabled", "true");

    //Incrementa cursor e armazena a pagina no array
    _pagination.pages[_pagination.cursor] = page;
    _pagination.final = _pagination.cursor;
    _pagination.cursor++;

    //Gerencia o estouro do array (Se o tamanho do array for maior que o 
    //limite estabelecido como default, entao deve ser excluido os primeiros 
    //dados para permitir a adicao de novos
    if (_pagination.final - _pagination.initial > _pagination.limit) {
        _pagination.pages[_pagination.initial] = "";
        _pagination.initial++;
    }
};

/**
 * 
 * @param {type} obj
 * @returns {String}
 */
play.prototype.getInputsForm = function (obj) {
    var o = {};

    var a = $("#" + obj).serializeArray();

    $.each(a, function () {
        o[this.name] = this.value || '';
    });
    return o;
};

/**
 * 
 * @param {type} obj
 * @returns {jqXHR}
 */
play.prototype.sendToServer = function (obj) {
    var params = {};

    if (obj.frm) { //Se houver dados para enviar
        params.data = $play.getInputsForm(obj.frm);
        params.type = "POST";
        params.url = GLOBALSIS.path + obj.url;
    } else {
        params.type = "GET";
        params.url = GLOBALSIS.path + obj.url + "?" + Math.ceil(Math.random() * 100000);
    }

    return $.ajax(params);
};

/**
 * 
 * @param {type} time
 * @param {type} funcao
 * @returns {initTimeOut.timeOut}
 */
play.prototype.initTimeOut = function (time, funcao) {
    var timeOut = setTimeout(funcao, time);
    return timeOut;
};

/**
 * Funcao de exibição do gif de carregamento
 * @param {type} visible
 * @returns {undefined}
 */
play.prototype.showLoader = function (visible) {
    if (visible) {
        $("#loader").fadeIn("fast");
        lockClick();
    } else {
        $("#loader").fadeOut("fast");
        unlockClick();
    }
};

