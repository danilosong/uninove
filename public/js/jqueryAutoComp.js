/**
 *         +------------------------+
 *        / jqueryAutoComplete.js  /
 *       /          3.0.0         /
 *      +------------------------+
 *            
 * Upgrade da ferramenta 'ajaxAutoComp.js'
 * 
 * Versao 1.0.0: 06-05-2010
 * Versao 1.1.0: 25-05-2011
 * Versao 1.2.0: 25-07-2011   
 * Versao 3.0.0: 
 * 
 * @author Paulo Watakabe watakabe05@gmail.com
 * @author Danilo Dorotheu danilo.dorotheu@live.com
 * @version 3.0.0
 */
 var engine = (function($){
    /**
     * Variaveis default
     * @type {Object}
     */
     var options = {
        span: "compl",      //Container span
        typeAjax: "POST",   //Tipo de Request
        params:{},          //Parametros
        qtdItensTable: 0,
        show: "value",      //Coluna que será exibida no input após enter
        classRef: [         //Classes da Tabela
        'table-bordered', 
        'table-hover', 
        'table-striped', 
        'table-condensed'
        ],
        keys:{
            ESC: 27,
            TAB: 9,
            ENTER: 13,
            SPACE: 32,
            LEFT: 37,
            UP: 38,
            RIGHT: 39,
            DOWN: 40
        }
    }
    /**
     * Converte um Objeto em uma tabela
     * @param  {Object} obj Objeto a ser convertido
     * @param  {Array} clTab Classes da tabela
     * @return {DOM} table Estrutura da Tabela gerada
     * @author Danilo Dorotheu
     * @version  1.0
     */
     function objToTable(obj, clTab){
        var table;                 //Tabela
        var thead = "";            //Topo
        var tbody = "";            //Corpo
        var tfoot = "";            //Rodape
        var classTab =  "table";   //Classes da tabela
        var headers = [];          //Armazena os titulos da tabela
        
        /**
         * Incrementa as classes do array em variavel
         */
         for( var i = 0; i < clTab.length; i++ ){
            classTab += " " + clTab[i];
        }

        /**
         * Monta TBODY
         */
         var cont = 0;                                        //TODO: Modificado
         var aux = "<tbody class='suggest'>";
         $.each(obj, function(key, val){
            aux += "<tr data-pos='"+cont+"'>"; //Abre tr      //TODO: Modificado
            $.each(val, function(chave, valor){

                //Adiciona chave no vetor de TH's
                if($.inArray(chave, headers) < 0){
                    headers.push(chave);
                }
                aux += "<td>"+valor+"</td>"; //Adiciona td
            });
            aux += "</tr>"; //fecha tr
            cont++;                                          //TODO: Modificado
        });
         aux += "</tbody>"
         tbody = aux;

        /**
         * Monta THEAD
         */
         aux = "<thead><tr>"
         $.each(headers, function(key, val){
            aux += "<th>"+val+"</th>"    
        });
         aux += "</tr></thead>";
         thead = aux;

        /**
         * Monta o TFOOT
         */
         tfoot = "<tfoot></tfoot>";

        /**
         * Monta a TABLE
         */
         table = "<table class='"+ classTab +"'>" + thead + tbody + tfoot + "</table>";

         return table;
     }
    /**
     * Processador
     * @author Danilo Dorotheu
     * @version  1.0
     */
     function autoComp() {
        $span = $("#" + options.span);
        //Define o tamanho do span seguindo o tamanho do input
        $span.width($(options.element).width());
        
        if(!$span.is(":visible")){
            $span.show();
        }
        //Adiciona o valor do input em options.params
        options.params['data'] = $(options.element).val();

        //executa o ajax
        action.requestServer({
            url: options.url,          //URL do Servidor
            type: options.typeAjax,    //Tipo de request (default: POST)
            data: options.params       //Parametros
        }).success(function(ret){
            if(ret !== "")
            {   //Se retornar valor, processar gráficos
                ret = JSON.parse(ret);
                printPage(ret);
            }
            else
            {   //Se não retornar, fechar span
                closeSpan();
            }
        });        
    }
    /**
     * Processador de exibicao dos resultados
     * @param  {Object} results Objeto com os dados retornados do servidor
     * @author Danilo Dorotheu
     * @version 1.0
     */
     function printPage(results) {
        //Converte o span em jQuery
        $element = $(options.element);
        //Converte o Objeto em DOM
        var html = objToTable(results, options.classRef);

        if($element.val() == "")
        {   //Se o input estiver vazio, fechar span
            closeSpan();
        }
        else
        {
            //Adiciona o resultado no span
            $('#'+options.span).html(html);
            $('#'+options.span).show();
        }
    }

    function keyBoardListener(events){
        var keys = options.keys;

        switch(events.which){
            case keys.UP:

            
            break;
            case keys.DOWN:

            
            break;
            case keys.ENTER:

            
            break;
            case keys.ESC:
                closeSpan(); //Fecha o span
            
            break;
            default: //Another key
                autoComp(); //Executa query
            
            break;
        }
    }
    /**
     * Fecha span de resultados
     * @author Danilo Dorotheu
     * @version 1.0
     */
     function closeSpan(){
        $('#'+options.span).html("");
        $('#'+options.span).hide();
    }
    /**
     * Eventos do modulo
     * @author Danilo Dorotheu
     * @version 1.0
     */
     function events(){
        /**
         * @type {jQuery}
         */
         var $doc = $(document);
        /**
         * Elemento DOM
         * @type {jQuery}
         */
         var $element = $(options.element);
         /**
          * Alias options.keys
          * @type {Object}
          */
          var keys = options.keys;
        /**
         * Executa acoes do onClick
         * @param {jQuery|String} attr Elemento DOM que sera observado
         * @param {function} execMe Funcao de execucao quando ocorrencia ocorre
         * @author Danilo Dorotheu
         * @version 1.0
         */
         function $click(attr, execMe) {
            return $doc.on("click", attr, execMe);
        }
        /**
         * Executa acoes do onChange
         * @param {jQuery|String} attr Elemento DOM que sera observado
         * @param {function} execMe Funcao de execucao quando ocorrencia ocorre
         * @author Danilo Dorotheu
         * @version 1.0
         */
         function $change(attr, execMe){
            return $doc.on("change", attr, execMe);
        }
        /**
         * Executa acoes do onKeyUp
         * @param {jQuery|String} attr Elemento DOM que sera observado
         * @param {function} execMe Funcao de execucao quando ocorrencia ocorre
         * @author Danilo Dorotheu
         * @version 1.0
         */
         function $keyup(attr, execMe) {
            return $doc.on("keyup", attr, execMe);
        };
        /**
         * Executa acoes do onKeyDown
         * @param {jQuery|String} attr Elemento DOM que sera observado
         * @param {function} execMe Funcao de execucao quando ocorrencia ocorre
         * @author Danilo Dorotheu
         * @version 1.0
         */
         function $keyDown(attr, execMe) {
            return $doc.on("keydown", attr, execMe);
        };
        /**
         * Executa funcoes do onKeyPress
         * @param {jQuery|String} attr Elemento DOM que sera observado
         * @param {function} execMe Funcao de execucao quando ocorrencia ocorre
         * @author Danilo Dorotheu
         * @version 1.0
         */
         function $keyPress(attr, execMe){
            return $doc.on("keypress", attr, execMe);
        }
        /**
         * Executa acoes de clickOut do elemento
         * @param {jQuery|String} $container Elemento DOM que sera observado
         * @param {function} execMe Funcao de execucao quando ocorrencia ocorrer
         * @author Danilo Dorotheu
         * @version 1.0
         */
         function $clickOut($container, execMe) {
            $container = $($container);
            $doc.mouseup(function (e) {
                if (!$container.is(e.target) // if the target of the click isn't the container...
                        && $container.has(e.target).length === 0) // ... nor a descendant of the container
                {
                    execMe();
                }
            });
        };
        /**
         * Executa funcao quando tecla é solta
         * @author Danilo Dorotheu
         */
         $keyup($element, function(e){
            keyBoardListener(e);
        });
        /**
         * Fecha span se usuario clicar fora do input
         * @author Danilo Dorotheu
         */
         $clickOut($("#"+options.span),function(){
            closeSpan();
        });
         /**
          * Seleciona o campo através do click do mouse
          * @param  {type}[description]
          * @return {type}[description]
          */
          $click(".suggest tr", function(){
            console.log($(this).html());
        });
    }


    /**
     * PUBLIC METHODS
     */
     return {
        /**
         * Inicializa o modulo de auto complete
         * @param  {DOM|String} element Elemento a ser inspecionado
         * @param  {Object} params  Parametros:
         * {
         *     (Array)localRet: Locais que serao retornado os dados
         *     (Array)filters: Filtros
         *     (String)url: Url do Servidor
         *     (String)type: Tipo de chamada
         * }
         * @return {jQuery} element
         * @author Danilo Dorotheu
         * @version 3.0
         */
         autoComplete: function(element, params){
            //Trata elemento (concatena # no nome do elemento se não houver)
            if(element.indexOf(".") !== 0 || element.indexOf("#") !== 0){
                element = "#" + element;
                console.log(element);
            }
            //Insere os parametros publicos localmente
            $.extend(options, params, options);
            //Insere o element localmente
            options['element'] = element;
            
            //Monta o span para receber resposta
            $(options.element).parent().append("<div id='compl' class='dropdown-menu'>");

            //Ininicaliza eventos
            events();

            //Retorna o elemento
            return element;
        }
    }
})(jQuery);
