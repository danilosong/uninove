/**
 *  Ajax Autocomplete for jQuery, version %version%
 *  (c) 2015 Tomas Kirda
 *
 *  Ajax Autocomplete for jQuery is freely distributable under the terms of an MIT-style license.
 *  For details, see the web site: https://github.com/devbridge/jQuery-Autocomplete
 */

/*jslint  browser: true, white: true, plusplus: true, vars: true */
/*global define, window, document, jQuery, exports, require */

// Expose plugin as an AMD module if AMD loader is present:
(function (factory) {
    'use strict';
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module.
        define(['jquery'], factory);
    } else if (typeof exports === 'object' && typeof require === 'function') {
        // Browserify
        factory(require('jquery'));
    } else {
        // Browser globals
        factory(jQuery);
    }
}(function ($) {
    'use strict';

    var
            utils = (function () {
                return {
                    escapeRegExChars: function (value) {
                        return value.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
                    },
                    createNode: function (containerClass) {
                        var div = document.createElement('div');
                        div.className = containerClass;
                        div.style.position = 'absolute';
                        div.style.display = 'none';
                        return div;
                    }
                };
            }()),
            keys = {
                ESC: 27,
                TAB: 9,
                RETURN: 13,
                LEFT: 37,
                UP: 38,
                RIGHT: 39,
                DOWN: 40
            };

    function Autocomplete(el, options) {
        var noop = function () {
        },
                that = this,
                defaults = {
                    ajaxSettings: {},
                    primary: "value",
                    autoSelectFirst: false,
                    appendTo: document.body,
                    serviceUrl: null,
                    lookup: null,
                    onSelect: null,
                    width: 'auto',
                    minChars: 1,
                    maxHeight: 300,
                    deferRequestBy: 0,
                    params: {},
                    formatResult: Autocomplete.formatResult,
                    delimiter: null,
                    zIndex: 9999,
                    type: 'GET',
                    noCache: false,
                    onSearchStart: noop,
                    onSearchComplete: noop,
                    onSearchError: noop,
                    preserveInput: false,
                    containerClass: 'autocomplete-suggestions',
                    tabDisabled: false,
                    dataType: 'text',
                    currentRequest: null,
                    triggerSelectOnValidInput: true,
                    preventBadQueries: true,
                    lookupFilter: function (suggestion, originalQuery, queryLowerCase) {
                        return suggestion[options.primary].toLowerCase().indexOf(queryLowerCase) !== -1;
                    },
                    paramName: 'query',
                    transformResult: function (response) {
                        return typeof response === 'string' ? $.parseJSON(response) : response;
                    },
                    showNoSuggestionNotice: false,
                    noSuggestionNotice: 'No results',
                    orientation: 'bottom',
                    forceFixPosition: false
                };

        // Shared variables:
        that.element = el;
        that.el = $(el);
        that.suggestions = [];
        that.badQueries = [];
        //TODO
        that.selectedIndex = 0;
        that.currentValue = that.element.value;
        that.intervalId = 0;
        that.cachedResponse = {};
        that.onChangeInterval = null;
        that.onChange = null;
        that.isLocal = false;
        that.suggestionsContainer = null;
        that.noSuggestionsContainer = null;
        that.options = $.extend({}, defaults, options);
        that.classes = {
            selected: 'autocomplete-selected',
            suggestion: 'autocomplete-suggestion'
        };
        that.hint = null;
        that.hintValue = '';
        that.selection = null;

        // Initialize and set options:
        that.initialize();
        that.setOptions(options);
    }

    Autocomplete.utils = utils;

    $.Autocomplete = Autocomplete;

    Autocomplete.formatResult = function (suggestion, currentValue) {
        var pattern = '(' + utils.escapeRegExChars(currentValue) + ')';


        return suggestion["all"]
                .replace(new RegExp(pattern, 'gi'), '<strong>$1<\/strong>')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/&lt;(\/?strong)&gt;/g, '<$1>');
    };

    Autocomplete.prototype = {
        killerFn: null,
        initialize: function () {
            var that = this,
                    suggestionSelector = '.' + that.classes.suggestion,
                    selected = that.classes.selected,
                    options = that.options,
                    container;

            // Remove autocomplete attribute to prevent native suggestions:
            that.element.setAttribute('autocomplete', 'off');

            that.killerFn = function (e) {
                if ($(e.target).closest('.' + that.options.containerClass).length === 0) {
                    that.killSuggestions();
                    that.disableKillerFn();
                }
            };


            // html() deals with many types: htmlString or Element or Array or jQuery
            that.noSuggestionsContainer = $('<div class="autocomplete-no-suggestion"></div>')
                    .html(this.options.noSuggestionNotice).get(0);

            that.suggestionsContainer = Autocomplete.utils.createNode(options.containerClass);

            container = $(that.suggestionsContainer);

            container.appendTo(options.appendTo);

            // Only set width if it was provided:
            if (options.width !== 'auto') {
                container.width(options.width);
            }

            // Listen for mouse over event on suggestions list:
            container.on('mouseover.autocomplete', suggestionSelector, function () {
                that.activate($(this).data('index'));
            });

            // Deselect active element when mouse leaves suggestions container:
            container.on('mouseout.autocomplete', function () {
                //TODO
                that.selectedIndex = 0;
                container.children('.' + selected).removeClass(selected);
            });

            // Listen for click event on suggestions list:
            container.on('click.autocomplete', suggestionSelector, function () {
                that.select($(this).data('index'));
            });

            that.fixPositionCapture = function () {
                if (that.visible) {
                    that.fixPosition();
                }
            };

            $(window).on('resize.autocomplete', that.fixPositionCapture);

            that.el.on('keydown.autocomplete', function (e) {
                console.log("aqui keydown");
                that.onKeyPress(e);
            });
            that.el.on('keyup.autocomplete', function (e) {
                that.onKeyUp(e);
            });
            that.el.on('blur.autocomplete', function () {
                that.onBlur();
            });
            that.el.on('focus.autocomplete', function () {
                console.log("aqui focus");
                that.onFocus();
            });
            that.el.on('change.autocomplete', function (e) {
                that.onKeyUp(e);
            });
            that.el.on('input.autocomplete', function (e) {
                that.onKeyUp(e);
            });
        },
        onFocus: function () {
            var that = this;
            that.fixPosition();
            if (that.options.minChars === 0 && that.el.val().length === 0) {
                that.onValueChange();
            }
        },
        onBlur: function () {
            this.enableKillerFn();
        },
        abortAjax: function () {
            var that = this;
            if (that.currentRequest) {
                that.currentRequest.abort();
                that.currentRequest = null;
            }
        },
        setOptions: function (suppliedOptions) {
            var that = this,
                    options = that.options;

            $.extend(options, suppliedOptions);

            that.isLocal = $.isArray(options.lookup);

            if (that.isLocal) {
                options.lookup = that.verifySuggestionsFormat(options.lookup);
            }

            options.orientation = that.validateOrientation(options.orientation, 'bottom');

            // Adjust height, width and z-index:
            $(that.suggestionsContainer).css({
                'max-height': options.maxHeight + 'px',
                'width': options.width + 'px',
                'z-index': options.zIndex
            });
        },
        clearCache: function () {
            this.cachedResponse = {};
            this.badQueries = [];
        },
        clear: function () {
            this.clearCache();
            this.currentValue = '';
            this.suggestions = [];
        },
        disable: function () {
            var that = this;
            that.disabled = true;
            clearInterval(that.onChangeInterval);
            that.abortAjax();
        },
        enable: function () {
            this.disabled = false;
        },
        fixPosition: function () {
            // Use only when container has already its content

            var that = this,
                    $container = $(that.suggestionsContainer),
                    containerParent = $container.parent().get(0);
            // Fix position automatically when appended to body.
            // In other cases force parameter must be given.
            if (containerParent !== document.body && !that.options.forceFixPosition) {
                return;
            }

            // Choose orientation
            var orientation = that.options.orientation,
                    containerHeight = $container.outerHeight(),
                    height = that.el.outerHeight(),
                    offset = that.el.offset(),
                    styles = {'top': offset.top, 'left': offset.left};

            if (orientation === 'auto') {
                var viewPortHeight = $(window).height(),
                        scrollTop = $(window).scrollTop(),
                        topOverflow = -scrollTop + offset.top - containerHeight,
                        bottomOverflow = scrollTop + viewPortHeight - (offset.top + height + containerHeight);

                orientation = (Math.max(topOverflow, bottomOverflow) === topOverflow) ? 'top' : 'bottom';
            }

            if (orientation === 'top') {
                styles.top += -containerHeight;
            } else {
                styles.top += height;
            }

            // If container is not positioned to body,
            // correct its position using offset parent offset
            if (containerParent !== document.body) {
                var opacity = $container.css('opacity'),
                        parentOffsetDiff;

                if (!that.visible) {
                    $container.css('opacity', 0).show();
                }

                parentOffsetDiff = $container.offsetParent().offset();
                styles.top -= parentOffsetDiff.top;
                styles.left -= parentOffsetDiff.left;

                if (!that.visible) {
                    $container.css('opacity', opacity).hide();
                }
            }

            // -2px to account for suggestions border.
            if (that.options.width === 'auto') {
                styles.width = (that.el.outerWidth() - 2) + 'px';
            }

            $container.css(styles);
        },
        enableKillerFn: function () {
            var that = this;
            $(document).on('click.autocomplete', that.killerFn);
        },
        disableKillerFn: function () {
            var that = this;
            $(document).off('click.autocomplete', that.killerFn);
        },
        killSuggestions: function () {
            var that = this;
//            that.stopKillSuggestions();
//            that.intervalId = window.setInterval(function () {
//                that.el.val(that.currentValue);
            that.hide();
//                that.stopKillSuggestions();
//            }, 50);
        },
        stopKillSuggestions: function () {
            window.clearInterval(this.intervalId);
        },
        isCursorAtEnd: function () {
            var that = this,
                    valLength = that.el.val().length,
                    selectionStart = that.element.selectionStart,
                    range;

            if (typeof selectionStart === 'number') {
                return selectionStart === valLength;
            }
            if (document.selection) {
                range = document.selection.createRange();
                range.moveStart('character', -valLength);
                return valLength === range.text.length;
            }
            return true;
        },
        onKeyPress: function (e) {
            var that = this;

            // If suggestions are hidden and user presses arrow down, display suggestions:
            if (!that.disabled && !that.visible && e.which === keys.DOWN && that.currentValue) {
                that.suggest();
                return;
            }

            if (that.disabled || !that.visible) {
                return;
            }

            switch (e.which) {
                case keys.ESC:
                    that.el.val(that.currentValue);
                    that.hide();
                    break;
                case keys.RIGHT:
                    if (that.hint && that.options.onHint && that.isCursorAtEnd()) {
                        that.selectHint();
                        break;
                    }
                    return;
                case keys.TAB:
                    if (that.hint && that.options.onHint) {
                        that.selectHint();
                        return;
                    }
                    if (that.selectedIndex === -1) {
                        that.hide();
                        return;
                    }
                    that.select(that.selectedIndex);
                    if (that.options.tabDisabled === false) {
                        return;
                    }
                    break;
                case keys.RETURN:
                    if (that.selectedIndex === -1) {
                        that.hide();
                        return;
                    }
                    that.select(that.selectedIndex);
                    break;
                case keys.UP:
                    that.moveUp();
                    break;
                case keys.DOWN:
                    that.moveDown();
                    break;
                default:
                    return;
            }

            // Cancel event if function did not return:
            e.stopImmediatePropagation();
            e.preventDefault();
        },
        /**
         * @todo Foram executadas algumas mudanças para permitir listagem
         * @param {type} e
         * @returns {undefined}
         */
        onKeyUp: function (e) {
            var that = this;

            if (that.disabled) {
                return;
            }

            switch (e.which) {
                case keys.UP:
                case keys.DOWN:
                    return;
            }

            clearInterval(that.onChangeInterval);

            
            /**
             * @todo Inseri este verificador para trazer resultados quando for 
             * uma lista 
             * @author Danilo Dorotheu
             */
            if(that.el.val() == "*"){
                that.options.minChars = 1;
                that.onValueChange();
                return true;
            }

            // || that.el.val() == "" <- Coloquei a mais...retirar se der erro
            if (that.currentValue !== that.el.val()) {
                that.findBestHint();
                if (that.options.deferRequestBy > 0) {
                    // Defer lookup in case when value changes very quickly:
                    that.onChangeInterval = setInterval(function () {
                        that.onValueChange();
                    }, that.options.deferRequestBy);
                } else {
                    that.onValueChange();
                }
            }
        },
        /**
         * Trigger do autocomplete
         * @todo: Aqui é acionado o autocomplete
         * 
         * @returns {undefined}
         */
        onValueChange: function () {
            var that = this,
                    options = that.options,
                    value = that.el.val(),
                    query = that.getQuery(value);

            if (that.selection && that.currentValue !== query) {
                that.selection = null;
                (options.onInvalidateSelection || $.noop).call(that.element);
            }

            clearInterval(that.onChangeInterval);
            that.currentValue = value;
            //TODO
            that.selectedIndex = 0;

            // Check existing suggestion for the match before proceeding:
            if (options.triggerSelectOnValidInput && that.isExactMatch(query)) {
                that.select(0);
                return;
            }

            if (query.length < options.minChars) {
                that.hide();
            } else {
                that.getSuggestions(query);
            }
        },
        /**
         * @TODO
         * @param {type} query
         * @returns {Boolean}
         */
        isExactMatch: function (query) {
            var suggestions = this.suggestions;
            if (suggestions.lenght === 2) {
                return (suggestions[1][this.options.primary]).toLowerCase() === query.toLowerCase();
            }
            return false;
        },
        getQuery: function (value) {
            var delimiter = this.options.delimiter,
                    parts;

            if (!delimiter) {
                return value;
            }
            parts = value.split(delimiter);
            return $.trim(parts[parts.length - 1]);
        },
        getSuggestionsLocal: function (query) {
            var that = this,
                    options = that.options,
                    queryLowerCase = query.toLowerCase(),
                    filter = options.lookupFilter,
                    limit = parseInt(options.lookupLimit, 10),
                    data;

            data = {
                suggestions: $.grep(options.lookup, function (suggestion) {
                    return filter(suggestion, query, queryLowerCase);
                })
            };

            if (limit && data.suggestions.length > limit) {
                data.suggestions = data.suggestions.slice(0, limit);
            }

            return data;
        },
        /**
         * 
         * @todo Esta função PARECE ser o miolo do sistema de autocompleção. Aqui, 
         * o valor será buscado na base e retornado para os demais tratamentos
         * 
         * @param {string} q Texto inserido no input de busca 
         * @returns {undefined}
         */
        getSuggestions: function (q) {
            var response,
                    that = this,
                    options = that.options,
                    serviceUrl = options.serviceUrl,
                    params,
                    cacheKey,
                    ajaxSettings;

            options.params[options.paramName] = q;
            params = options.ignoreParams ? null : options.params;

            if (options.onSearchStart.call(that.element, options.params) === false) {
                return;
            }

            if ($.isFunction(options.lookup)) {
                options.lookup(q, function (data) {
                    that.suggestions = data.suggestions;
                    that.suggest();
                    options.onSearchComplete.call(that.element, q, data.suggestions);
                });
                return;
            }

            if (that.isLocal) {
                response = that.getSuggestionsLocal(q);
            } else {
                if ($.isFunction(serviceUrl)) {
                    serviceUrl = serviceUrl.call(that.element, q);
                }
                cacheKey = serviceUrl + '?' + $.param(params || {});
                response = that.cachedResponse[cacheKey];
            }

            if (response && $.isArray(response.suggestions)) {
                that.suggestions = response.suggestions;
                that.suggest();
                options.onSearchComplete.call(that.element, q, response.suggestions);
            } else if (!that.isBadQuery(q)) {
                that.abortAjax();

                ajaxSettings = {
                    url: serviceUrl,
                    data: params,
                    type: options.type,
                    dataType: options.dataType
                };

                $.extend(ajaxSettings, options.ajaxSettings);

                /**
                 * Aqui é onde será enviado as informações ao servidor
                 * @todo: REQUEST
                 */
                that.currentRequest = $.ajax(ajaxSettings).done(function (data) {
                    var result;
                    that.currentRequest = null;
                    result = options.transformResult(data, q);
                    that.processResponse(result, q, cacheKey);
                    options.onSearchComplete.call(that.element, q, result.suggestions);
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    options.onSearchError.call(that.element, q, jqXHR, textStatus, errorThrown);
                });
            } else {
                options.onSearchComplete.call(that.element, q, []);
            }
        },
        isBadQuery: function (q) {
            if (!this.options.preventBadQueries) {
                return false;
            }

            var badQueries = this.badQueries,
                    i = badQueries.length;

            while (i--) {
                if (q.indexOf(badQueries[i]) === 0) {
                    return true;
                }
            }

            return false;
        },
        hide: function () {
            var that = this,
                    container = $(that.suggestionsContainer);

            if ($.isFunction(that.options.onHide) && that.visible) {
                that.options.onHide.call(that.element, container);
            }

            that.visible = false;
            //TODO
            that.selectedIndex = 0;
            clearInterval(that.onChangeInterval);
            $(that.suggestionsContainer).hide();
            that.signalHint(null);
        },
        suggest: function () {
            if (this.suggestions.length === 0) {
                if (this.options.showNoSuggestionNotice) {
                    this.noSuggestions();
                } else {
                    this.hide();
                }
                return;
            }

            var that = this,
                    options = that.options,
                    groupBy = options.groupBy,
                    formatResult = options.formatResult,
                    value = that.getQuery(that.currentValue),
                    className = that.classes.suggestion,
                    classSelected = that.classes.selected,
                    container = $(that.suggestionsContainer),
                    noSuggestionsContainer = $(that.noSuggestionsContainer),
                    beforeRender = options.beforeRender,
                    html = '',
                    category,
                    formatGroup = function (suggestion, index) {
                        var currentCategory = suggestion.data[groupBy];

                        if (category === currentCategory) {
                            return '';
                        }

                        category = currentCategory;

                        return '<div class="autocomplete-group"><strong>' + category + '</strong></div>';
                    };

            if (options.triggerSelectOnValidInput && that.isExactMatch(value)) {
                that.select(0);
                return;
            }

            var response = {};

            /** == REMOÇÃO DE RESULTADOS INUTILIZAVEIS
             * 
             * Remove os dados que não serão exibidos no HTML do autocomplete
             * baseando-se nos dados pre-definidos em options.showCols
             ** ===============================================================*/

            if (that.options.showCols.length > 0) {
                var tempResp = {};
                var tempArray = {};
                $.each(that.suggestions, function (index, arrayIn) {
                    $.each(arrayIn, function (chave, value) {
                        $.each(that.options.showCols, function (k, val) {
                            //Inserir na tabela dados apenas com os valores 
                            //encontrados abaixo
                            var hotAccept = val;
                            var menuHotAccept = "menu_" + hotAccept;
                            var isConf = ("conf_" == chave.substring(0, 5));

                            if (chave == hotAccept || chave == menuHotAccept || isConf) {
                                tempArray[chave] = value;
                            }
                        });
                    });
                    tempResp[index] = tempArray;
                    tempArray = {};
                });
                response = tempResp;
            } else {
                response = that.suggestions;
            }

            /** == CONSTRUÇÃO DO HTML DO AUTOCOMPLETE
             * 
             * Constroi o HTML do autocomplete baseado nos resultados tratados
             * acima
             ** ===============================================================*/
            html += '<table class="table table-bordered table-condensed"><thead>';
            /**
             * Após eliminado toda a sujeira que o autocomp trouxe do servidor,
             * é hora de montar as linhas e as colunas com os resultados.
             */
            $.each(response, function (i, suggestion) {
                /**
                 * Quando um resultado possui a notação 'conf_', então este 
                 * resultado na verdade é um atributo da linha que irá comportar
                 * o resultado. Então, foi desenvolvido abaixo, a seguinte logica:
                 * 
                 * 1 - Define a posição da coluna em suggestion como false, pra 
                 * eliminar a configuração da construção dos resultados;
                 * 2 - Redefine a chave do atributo 'conf_' para construir string
                 * de atributos do 'tr';
                 * 3 - Atribui em attrs, a nova key juntamente com o seu valor;
                 */
                var attrs = {};
                $.each(suggestion, function (key, val) {
                    if ("conf_" == key.substring(0, 5)) {
                        suggestion[key] = false;
                        var key = key.substring(5);
                        attrs[key] = val;
                    }
                });
                /**
                 * Então, é adicionado os demais atributos que é comum em todos 
                 * os tr's, como o 'data-index' e o 'class' (E se houver algum 
                 * class previamente definido em attrs, então este atributo será
                 * mesclado);
                 */
                attrs["data-index"] = i;
                attrs["class"] = (attrs["class"]) ? attrs["class"] + " " + className : className;

                /**
                 * Por fim, a variavel attrs é transformada em string e então, 
                 * atribuida ao tr hospedeiro de todos estes atributos
                 */
                attrs = $.map(attrs, function (val, key) {
                    return key + '="' + val + '" ';
                });
                html += '<tr ' + attrs.join("") + '>';

                /**
                 * Quando na primeira linha, as colunas desta linha serão 
                 * construidas com o <th>
                 */
                if (i == 0) {
                    $.each(suggestion, function (k, column) {
                        html += "<th>" + column + "</th>";
                    });
                    html += "</thead><tbody>";
                }
                /**
                 * Nas demais colunas, é construida com o <td>
                 */
                else {
                    $.each(suggestion, function (k, column) {
                        if (column) {
                            html += "<td>" + column + "</td>";
                        }
                    });
                }

                html += "</tr>";
            });
            html += '</tbody></table>';
            /* ===============================================================*/

            // Build suggestions inner HTML:
//            $.each(that.suggestions, function (i, suggestion) {
//                if (groupBy) {
//                    html += formatGroup(suggestion, value, i);
//                }
//
//                html += '<div class="' + className + '" data-index="' + i + '">' + formatResult(suggestion, value) + '</div>';
//            });


            this.adjustContainerWidth();

            noSuggestionsContainer.detach();
            container.html(html);

            if ($.isFunction(beforeRender)) {
                beforeRender.call(that.element, container);
            }

            that.fixPosition();
            container.show();

            // Select first value by default:
            if (options.autoSelectFirst) {
                //TODO
                that.selectedIndex = 1;
                container.scrollTop(0);
                container.children('.' + className).first().addClass(classSelected);
            }

            that.visible = true;
            that.findBestHint();
        },
        noSuggestions: function () {
            var that = this,
                    container = $(that.suggestionsContainer),
                    noSuggestionsContainer = $(that.noSuggestionsContainer);

            this.adjustContainerWidth();

            // Some explicit steps. Be careful here as it easy to get
            // noSuggestionsContainer removed from DOM if not detached properly.
            noSuggestionsContainer.detach();
            container.empty(); // clean suggestions if any
            container.append(noSuggestionsContainer);

            that.fixPosition();

            container.show();
            that.visible = true;
        },
        adjustContainerWidth: function () {
            var that = this,
                    options = that.options,
                    width,
                    container = $(that.suggestionsContainer);

            // If width is auto, adjust width before displaying suggestions,
            // because if instance was created before input had width, it will be zero.
            // Also it adjusts if input width has changed.
            // -2px to account for suggestions border.
            if (options.width === 'auto') {
                width = that.el.outerWidth() - 2;
                container.width(width > 0 ? width : 300);
            }
        },
        findBestHint: function () {
            var that = this,
                    value = that.el.val().toLowerCase(),
                    bestMatch = null;

            if (!value) {
                return;
            }
            /**
             * @TODO: Precisa arrumar este erro!!!
             * @author Danilo Dorotheu
             */
            try {
                $.each(that.suggestions, function (i, suggestion) {
                    var foundMatch = suggestion[that.options.primary].toLowerCase().indexOf(value) === 0;
                    if (foundMatch) {
                        bestMatch = suggestion;
                    }
                    return !foundMatch;
                });
            } catch (e) {
            }

            that.signalHint(bestMatch);
        },
        signalHint: function (suggestion) {
            var hintValue = '',
                    that = this;
            if (suggestion) {
                hintValue = that.currentValue + suggestion[that.options.primary].substr(that.currentValue.length);
            }
            if (that.hintValue !== hintValue) {
                that.hintValue = hintValue;
                that.hint = suggestion;
                (this.options.onHint || $.noop)(hintValue);
            }
        },
        verifySuggestionsFormat: function (suggestions) {
            // If suggestions is string array, convert them to supported format:
            if (suggestions.length && typeof suggestions[0] === 'string') {
                return $.map(suggestions, function (value) {
                    return {value: value, data: null};
                });
            }

            return suggestions;
        },
        validateOrientation: function (orientation, fallback) {
            orientation = $.trim(orientation || '').toLowerCase();

            if ($.inArray(orientation, ['auto', 'bottom', 'top']) === -1) {
                orientation = fallback;
            }

            return orientation;
        },
        processResponse: function (result, originalQuery, cacheKey) {
            var that = this,
                    options = that.options;

            result.suggestions = that.verifySuggestionsFormat(result.suggestions);

            // Cache results if cache is not disabled:
            if (!options.noCache) {
                that.cachedResponse[cacheKey] = result;
                if (options.preventBadQueries && result.suggestions.length === 0) {
                    that.badQueries.push(originalQuery);
                }
            }

            // Return if originalQuery is not matching current query:
            if (originalQuery !== that.getQuery(that.currentValue)) {
                return;
            }

            that.suggestions = result.suggestions;
            that.suggest();
        },
        activate: function (index) {
            var that = this,
                    activeItem,
                    selected = that.classes.selected,
                    container = $(that.suggestionsContainer),
                    children = container.find('.' + that.classes.suggestion);

            container.find('.' + selected).removeClass(selected);

            that.selectedIndex = index;

            if (that.selectedIndex !== -1 && children.length > that.selectedIndex) {
                activeItem = children.get(that.selectedIndex);
                $(activeItem).addClass(selected);
                return activeItem;
            }

            return null;
        },
        selectHint: function () {
            var that = this,
                    i = $.inArray(that.hint, that.suggestions);

            that.select(i);
        },
        select: function (i) {
            var that = this;
            that.hide();
            that.onSelect(i);
        },
        moveUp: function () {
            var that = this;

            if (that.selectedIndex === -1) {
                return;
            }

            if (that.selectedIndex === 0) {
                $(that.suggestionsContainer).children().first().removeClass(that.classes.selected);
                //TODO
                that.selectedIndex = 0;
                that.el.val(that.currentValue);
                that.findBestHint();
                return;
            }

            that.adjustScroll(that.selectedIndex - 1);
        },
        moveDown: function () {
            var that = this;

            if (that.selectedIndex === (that.suggestions.length - 1)) {
                return;
            }

            that.adjustScroll(that.selectedIndex + 1);
        },
        adjustScroll: function (index) {
            var that = this,
                    activeItem = that.activate(index);

            if (!activeItem) {
                return;
            }

            var offsetTop,
                    upperBound,
                    lowerBound,
                    heightDelta = $(activeItem).outerHeight();

            offsetTop = activeItem.offsetTop;
            upperBound = $(that.suggestionsContainer).scrollTop();
            lowerBound = upperBound + that.options.maxHeight - heightDelta;

            if (offsetTop < upperBound) {
                $(that.suggestionsContainer).scrollTop(offsetTop);
            } else if (offsetTop > lowerBound) {
                $(that.suggestionsContainer).scrollTop(offsetTop - that.options.maxHeight + heightDelta);
            }

            if (!that.options.preserveInput) {
                that.el.val(that.getValue(that.suggestions[index][that.options.primary]));
            }
            that.signalHint(null);
        },
        onSelect: function (index) {
            var that = this,
                    onSelectCallback = that.options.onSelect,
                    suggestion = that.suggestions[index];

            that.currentValue = that.getValue(suggestion[that.options.primary]);

            if (that.currentValue !== that.el.val() && !that.options.preserveInput) {
                that.el.val(that.currentValue);
            }

            that.signalHint(null);
            that.suggestions = [];
            that.selection = suggestion;

            if ($.isFunction(onSelectCallback)) {
                onSelectCallback.call(that.element, suggestion);
            }
        },
        getValue: function (value) {
            var that = this,
                    delimiter = that.options.delimiter,
                    currentValue,
                    parts;

            if (!delimiter) {
                return value;
            }

            currentValue = that.currentValue;
            parts = currentValue.split(delimiter);

            if (parts.length === 1) {
                return value;
            }

            return currentValue.substr(0, currentValue.length - parts[parts.length - 1].length) + value;
        },
        dispose: function () {
            var that = this;
            that.el.off('.autocomplete').removeData('autocomplete');
            that.disableKillerFn();
            $(window).off('resize.autocomplete', that.fixPositionCapture);
            $(that.suggestionsContainer).remove();
        }
    };

    // Create chainable jQuery plugin:
    $.fn.autocomplete = $.fn.devbridgeAutocomplete = function (options, args) {
        var dataKey = 'autocomplete';
        // If function invoked without argument return
        // instance of the first matched element:
        if (arguments.length === 0) {
            return this.first().data(dataKey);
        }

        return this.each(function () {
            var inputElement = $(this),
                    instance = inputElement.data(dataKey);

            if (typeof options === 'string') {
                if (instance && typeof instance[options] === 'function') {
                    instance[options](args);
                }
            } else {
                // If instance already exists, destroy it:
                if (instance && instance.dispose) {
                    instance.dispose();
                }
                instance = new Autocomplete(this, options);
                inputElement.data(dataKey, instance);
            }
        });
    };
}));
