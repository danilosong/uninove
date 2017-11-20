var $play;

//Método que irá substituir o GLOBALSIS
var settings = {
    ola: "mundo"
};

/**
 * Centralizador de erros 
 * 
 * @type type
 */
var warn = {
    serverNotFound: function (server) {
        var error = "Servidor '" + server + "' não pode ser localizado!:"
                + " Verifique o endereco do servidor";
        console.error(error);
    }
};

function events() {
    // Limpar campos da tela
//    $(document).on('click', '.clean', function () {
//        var obj = $(this).parent().parent();
//        obj.find('input[type=text]').val('').focus();
//        obj.find('textarea').val('').focus();
//        obj.find('input[type=checkbox]').removeAttr('checked').focus();
//        obj.find('input[type=radio]').removeAttr('checked').focus();
//        var select = obj.find('select');
//        if (select) {
//            select.val(jQuery('options:first', select).val()).focus();
//        }
//    });

    /*
    $(document).on("click", "#refresh", function () {
        if (options.lastRequest) {
            action.processa(options.lastRequest);
            alert(JSON.stringfy(obj));
        }

    });
*/
}

/**
 * Transforma os campos de um formulario em objeto
 * (para quem acessa de dentro da classe)
 * -
 * @param {type} form
 * @returns {play.transformFormToObject@pro;value|String}
 */
var transformFormToObject = function (form) {
    var aux = {};

    if($.type(form) === 'string'){
        var form = eval("document." + form);
//        var test = $(form1).serializeArray();
//        return test;
    }
    var formData = new FormData($(form)[0]);
    
//    var form = $(form).serializeArray();
//
//    $.each(form, function () {
//        aux[this.name] = this.value || '';
//    });

    console.log(formData);

    return formData;
};

/**
 * Método de envio/recebimento de dados para o servidor
 * 
 * @param {type} obj
 * @returns {undefined}
 */
var processa = function (obj) {
    console.warn("Metodo obsoleto utilizar action.processa para metodo atualizado.")
    if (obj.url === "" || obj.url === "#") {
        return;
    }

    //Verifica se há o param ret. Se não há, seta o default
    obj.ret = (obj.ret) ? obj.ret : settings.defReturn;

    action.loader(true); //liga o loader

    module.Pagination.savePage();

    var ret = action.requestServer({
        url: settings.path + obj.url,
        data: transformFormToObject($("#" + obj.frm)),
        type: (obj.frm) ? "POST" : "GET"
    }).done(function (data) {
        $(obj.ret).html(data);
    }).complete(function () {
        action.loader(false); //Desliga o loader
        module.Pagination.addPage();
    });
};

/**
 * Transform lower case to upper case
 * 
 * @returns {undefined}
 */
$.fn.toUp = function () {
    $(this).val($(this).val().toUpperCase());
};

// Evita que Acidentalmente a tecla backspace execute a função voltar do navegador
$(document).bind("keydown keypress", function (e) {
    var preventKeyPress;

    var rx = /INPUT|TEXTAREA/i;
    var rxT = /RADIO|CHECKBOX|SUBMIT/i;

    if (e.keyCode === 8) {
        var d = e.srcElement || e.target;
        if (rx.test(e.target.tagName)) {
            var preventPressBasedOnType = false;
            if (d.attributes["type"]) {
                preventPressBasedOnType = rxT.test(d.attributes["type"].value);
            }
            preventKeyPress = d.readOnly || d.disabled || preventPressBasedOnType;
        } else {
            preventKeyPress = true;
        }
    } else {
        preventKeyPress = false;
    }

    if (preventKeyPress)
        e.defaultPrevented;
});


/**
 * Passa o foco do campo para o proximo
 * -
 * @param {type} obj
 * @returns {undefined}
 */
function nextFocus(obj, key, jump) {
    if(key === 13 && obj.type === 'textarea'){
        return true;                        
    }
    var $inputs = $(obj).closest('form').find(':input:visible');
    var ind = $inputs.index(obj);
    var i = 1;
    var jumped = 0;
    var flag = true;
    while (flag) {
        ele = $inputs.eq(ind + i);
        tp = ele.prop('type');
        if (ele.prop('disabled')) {
            i++;
        } else {
            switch (tp) {
                case 'button':
                    i++;
                    break;
                default:
                if(jump !== null && jump !== 0 && jumped < jump){
                    jumped++;
                    i++;
                }else{
                    ele.focus();
                    flag = false;
                }
            }
        }
    }
    return false;
}

/**
 * Formata data no padrão DDMMAAAA
 * -
 * @param {type} campo
 * @returns {undefined}
 */
function formataData(campo) {
    campo.value = filtraCampo(campo);
    var vr = LimparMoeda(campo.value, "0123456789");
    tam = vr.length;
    if (tam <= 1)
        campo.value = vr;
    if (tam > 2 && tam < 5)
        campo.value = vr.substr(0, tam - 2) + '/' + vr.substr(tam - 2, tam);
    if (tam >= 5 && tam <= 10)
        campo.value = vr.substr(0, 2) + '/' + vr.substr(2, 2) + '/' + vr.substr(4, 4);
}

/**
 * limpa todos os caracteres especiais do campo solicitado
 * -
 * @param {type} campo
 * @returns {String}
 */
function filtraCampo(campo) {
    var s = "";
    var vr = campo;
    tam = vr.length;
    for (i = 0; i < tam; i++) {
        switch (vr[i]) {
            case "/":
            case "-":
            case ".":
            case ",":
                continue;
            default :
                s += vr[i];
                break;
        }
    }
    campo = s;
    return campo;
}

/**
 * retira caracteres invalidos da string
 * -
 * @param {type} valor
 * @param {type} validos
 * @returns {String}
 */
function LimparMoeda(valor, validos) {
    var result = "";
    var aux;
    for (var i = 0; i < valor.length; i++) {
        aux = validos.indexOf(valor.substring(i, i + 1));
        if (aux >= 0) {
            result += aux;
        }
    }
    return result;
}

/**
 * Desabilita o input
 * -
 * @param {type} name
 * @returns {undefined}
 */
function setInputDisabledMulti(name) {
    var inp = document.getElementsByName(name);
    for (i = 0; i < inp.length; i++) {
        inp[i].disabled = true;
    }
}

/**
 * retira os acentos da palavra
 * -
 * @param {type} palavra
 * @returns {String|nova}
 */
function retira_acentos(palavra) {
    com_acento = 'áàãâäéèêëíìîïóòõôöúùûüçÁÀÃÂÄÉÈÊËÍÌÎÏÓÒÕÖÔÚÙÛÜÇ';
    sem_acento = 'aaaaaeeeeiiiiooooouuuucAAAAAEEEEIIIIOOOOOUUUUC';
    nova = '';
    for (i = 0; i < palavra.length; i++) {
        if (com_acento.search(palavra.substr(i, 1)) >= 0) {
            nova += sem_acento.substr(com_acento.search(palavra.substr(i, 1)), 1);
        } else {
            nova += palavra.substr(i, 1);
        }
    }
    return nova;
}

/**
 * verifica se o valor esta no array
 * -
 * @param {type} array
 * @param {type} vlr
 * @returns {Boolean}
 */
function in_Array(array, vlr) {
    for (key in array) {
        if (array[key] == vlr) {
            return true;
        }
    }
    return false;
}

/**
 *
 *  
 * @param {type} options
 * @returns {Generator}
 */
var Generator = function (options) {
    //Configuracoes defaults
    var defaults = {
        defReturn: "#inter", //Div de retorno do ajax (processa)
        path: ""
    };

    settings = $.extend({}, options, defaults);
    settings = $.extend({}, options, settings);

    this.init();
};

/**
 * Public functions
 * 
 * @type type
 */
Generator.prototype = {
    init: function () {
    }
};


/**
 * Prepara o formulario para enviar ao servidor
 * -
 * @param {type} buttonEle
 * @returns {Boolean}
 */
function saveForm(buttonEle) {
    action.sendForm(buttonEle);
    
    return false;
    if (buttonEle) {
        var frm = getFormName(buttonEle);
        var act = getFormAction(buttonEle);
        if (isValid()) {
            eval(attachmentParamInAction(act, 'frm', frm));
        }
    } else {
        alert('Botão sem formulario.');
    }
    return false;
}

/**
 * 
 * 
 * @param {type} act
 * @param {type} key
 * @param {type} vlr
 * @returns {String}
 */
function attachmentParamInAction(act, key, vlr) {
    var ind = act.indexOf('})');
    return act.substr(0, ind) + "," + key + ":'" + vlr + "'" + act.substr(ind);
}
/* ... */
function getFormName(obj) {
    return getAtrrFromForm(obj, 'name');
}
/* ... */
function getFormAction(obj) {
    return getAtrrFromForm(obj, 'action');
}
/* ... */
function getAtrrFromForm(obj, atr) {
    return getAtrrFromParentTag(obj, 'form', atr);
}
/* ... */
function getAtrrFromParentTag(obj, tag, atr) {
    return $(obj).closest(tag).attr(atr);
}


/**
 * Transforma o campo parametrizado em toUpperCase
 * -
 * @param {type} o
 * @returns {undefined}
 */
function toUp(o) {
    console.warn("funçao toUp() deve ser modificada por $(elemento).toUp() ou $.toUp(elemento)");
    o.value = o.value.toUpperCase();
}

/**
 * Modificar a tecla enter para tab e
 * Verificar se tem função a ser executada
 * -
 * @param {type} obj
 * @param {type} e
 * @returns {Boolean}
 */
function changeEnterToTab(obj, e, jump) {
    var keycode;
    if (window.event) {
        keycode = window.event.keyCode;
    } else if (e) {
        keycode = e.keyCode || e.which;
    } else {
        return true;
    }
    if ((keycode == 13) || (keycode == 9)) {
        pressEnterOrTab(obj, e);
    }
    if (keycode == 9) {
        pressTab(obj, e);
        return nextFocus(obj, keycode, jump);
    }
    if (keycode == 13) {
        pressEnter(obj, e);
        return nextFocus(obj, keycode, jump);
    }
    return true;
}


//FUNCAO PARA SER SOBREESCRITA SE NECESSARIO
function pressEnterOrTab(obj, e) {
    return true;
}
//FUNCAO PARA SER SOBREESCRITA SE NECESSARIO
function pressEnter(obj, e) {
    return true;
}
//FUNCAO PARA SER SOBREESCRITA SE NECESSARIO
function pressTab(obj, e) {
    return true;
}


// -----------------------------------------------------------------------------
$(function () {
    //TODO: Precisa ser corrigido como action
    events();

    var gen = new Generator({
        pagination: true
    });
    if($("#inter").html() == ""){
        $(this).parent().parent().css("background-color", "#eee");
    }else{
        $(this).parent().parent().css("background-color", "#fff");
    }

});
