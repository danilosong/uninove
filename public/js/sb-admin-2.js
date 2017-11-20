$(function () {
    $('#side-menu').metisMenu();
    sideMenu.init();
});

var sideMenu = (function ($) {

    /**
     * Parametros default do menu
     * @Object
     */
    var options = {
    };

    function loadapi() {
        $(window).bind("load resize", function () {
            topOffset = 50;
            width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
            if (width < 768) {
                $('div.navbar-collapse').addClass('collapse');
                topOffset = 100; // 2-row-menu
            } else {
                $('div.navbar-collapse').removeClass('collapse');
            }

            height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
            height = height - topOffset;
            if (height < 1)
                height = 1;
            if (height > topOffset) {
                $("#page-wrapper").css("min-height", (height) + "px");
            }
        });

        var url = window.location;
        var element = $('ul.nav a').filter(function () {
            return this.href == url || url.href.indexOf(this.href) == 0;
        }).addClass('active').parent().parent().addClass('in').parent();
        if (element.is('li')) {
            element.addClass('active');
        }

        console.log("Configuracoes do menu ativadas");
    }

    function alignDefinitions() {
        /**
         * Definicoes gerais
         */
        options.topHeight = $(".navbar").height();
        options.sidebarWid = $(".sidebar").width();
        options.navDocHeight = $(document).height() - options.topHeight;
        options.navWinHeight = $(window).height() - options.topHeight;
        options.sideMenu = $("#side-menu").height();
        var $sidebarNav = $(".sidebar-nav");

        /**
         * Alinha a página (#page-wrapper), inserindo uma margem
         * entre ela e o menu, com o objetivo de comportar a 
         * funcao toggle
         */
        $("#page-wrapper").css({
            "margin-left": options.sidebarWid,
            "margin-top": options.topHeight
        });

        /**
         * Ajusta o sidebar para ter tamanho 100%
         */
        $sidebarNav.css({
            "max-height": "none",
            "height": options.navDocHeight
        });

        /**
         * Ativa scroll quando o mouse passa
         * por cima do menu
         */
        $sidebarNav.mouseenter(function () {
            $(this).css({
                "overflow-y": "scroll"
            });
        });

        /**
         * Desativa o scroll quando o mouse 
         * passa por cima do menu
         */
        $sidebarNav.mouseleave(function () {
            $(this).css({
                "overflow-y": "hidden",
            });
        });

    }

    function managerClick() {
        var sideMenu = $("#side-menu");
        var linkMenu = sideMenu.find("a");
        var $manag = $(document);

        /**
         * Gerencia a classe 'active' 
         * da tag <a> quando clicada
         */
        $manag.on("click", "#side-menu a", function () {
            var isGroup = $(this).find("span").hasClass("arrow");

            if (isGroup) {
                if($(this).parent().hasClass("active")){
                    //Abrindo
                    if($(this).hasClass("active")){
                        $(this).removeClass("active");
                    }
                }else{
                    //fechando
                    if($(this).parent().find("ul").find("a").hasClass('active')){
                        $(this).addClass("active");
                    }
                }
            }
            else {
                linkMenu.each(function () {
                    $(this).removeClass("active");
                });
                $(this).addClass("active");
            }
        });
    }
    ;


    /**
     * Variaveis Publicas
     * @returns function init
     */
    return {
        /**
         * Metodo de inicializacao do menu
         * @param {type} params
         * @returns {undefined}
         */
        init: function (params) {
            $.extend(options, params, options);

            loadapi();
            alignDefinitions();
            managerClick();
        }
    };

})(jQuery);
