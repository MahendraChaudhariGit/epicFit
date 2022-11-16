/*
 * multi-typeahead.js v0.1.1
 *
 * jQuery Multi Typeahead Plugin v0.1.1
 * https://github.com/kendrikat/jquery-multi-typeahead.js
 *
 * Copyright 2013 Klaas Endrikat
 *
 * Released under the MIT license
 */

!function ($) {

    "use strict";
    if (!$) {
        // TODO:
        return false;
    }

    var typeaheadInput = {};
    var typeaheadTokens = {};
    var typeaheadContainer = {};
    var hiddenField = {};

    var multiTypeaheadOptions = {
        minLength: 1,
        items: 8,
        valueKey: 'value',
        tokenizer: false,
        local: "",
        prefill: "",
        fixed: false,
        delimiter: [",", " "]
    }

    $.addTag = function (value) {
        if (!multiTypeaheadOptions.fixed) {
            typeaheadTokens.append('<span class="tag" value="' + value + '">' + value + '<a class="tagsinput-remove-link"></a></span>');
        } else {
            typeaheadTokens.append('<span class="tag" value="' + value + '">' + value + '<a class=""></a></span>');
        }
        $.refresh();
    }

    $.refresh = function () {
        typeaheadInput.typeahead('setQuery', '');
        typeaheadInput.width(100);

        var padding = parseInt(typeaheadContainer.css('padding-left'));
        var cw = typeaheadContainer.width();
        var ip = typeaheadInput.offset().left;
        var iw = typeaheadInput.width();
        var inputNewWidth = cw - ip + iw + padding;
        var inputValues = new Array();

        typeaheadInput.width(inputNewWidth);

        $.each($(typeaheadContainer.find('span.tag')), function (key, value) {
            inputValues.push($(this).attr('value'));
        });
        if (!multiTypeaheadOptions.fixed) {
            hiddenField.val(inputValues.join(","));
        }
    }

    $.prepare = function () {

        $(window).resize(function () {
            $.refresh();
        });

        $(window).load(function () {
            $.refresh();
        });

        typeaheadContainer.click(function (e) {
            typeaheadInput.focus();
        });

        typeaheadInput.bind('blur', function (e) {
            typeaheadContainer.removeClass('focused');
        });

        typeaheadInput.bind('focus', function (e) {
            typeaheadContainer.addClass('focused');
        });

        $('body').on('click', '#typeahead-container a.tagsinput-remove-link', function (e) {
            $(this).parents('span').remove();
            typeaheadInput.focus();
            $.refresh();
        });

        typeaheadInput.keypress(function (e) {
            var consume = true;
            var code = (e.charCode ? e.charCode : e.which);
            if (code == 8 && typeaheadInput.val().length == 0) {
                typeaheadContainer.find('span.tag').last().remove();
                $.refresh();
            }
            if (!multiTypeaheadOptions.tokenizer && typeaheadInput.val().length > 0 && !typeaheadContainer.find('.tt-dropdown-menu').is(':visible')) {
                $.each(multiTypeaheadOptions.delimiter, function () {
                    if (code == this.charCodeAt(0) || code == 13) {
                        $.addTag(typeaheadInput.val().replace(this, '').trim());
                        consume = false;
                    }
                });
            }
            return consume;
        });

        if (!multiTypeaheadOptions.tokenizer) {
            $('.tagsinput-add').bind('click', function (e) {
                if (typeaheadInput.val().length > 0 && !typeaheadContainer.find('.tt-dropdown-menu').is(':visible')) {
                    $.addTag(typeaheadInput.val());
                }
            });
        }

        if (multiTypeaheadOptions.prefill.length > 0) {
            $.each(multiTypeaheadOptions.prefill, function (key, value) {
                $.addTag(value);
            });
            $.refresh();
        }

        if (multiTypeaheadOptions.fixed) {
            typeaheadContainer.find("input").prop('disabled', true);
        }
    }

    $.fn.multiTypeahead = function (options) {

        $.extend(multiTypeaheadOptions, options);

        typeaheadInput = $(this);

        typeaheadInput.wrap('<div id="typeahead-container" class="tagsinput tagsinput-primary"></div>');
        typeaheadContainer = $('#typeahead-container');
        typeaheadTokens = $('<div id="typeahead-tokens"></div>');

        if (!multiTypeaheadOptions.fixed) {
            var hiddenID = "hidden-" + typeaheadInput.attr('id');

            typeaheadContainer.prepend('<div class="tagsinput-add-container"></div>');
            typeaheadContainer.prepend(typeaheadTokens);
            typeaheadContainer.append('<input id="' + hiddenID + '" type="hidden" name="' + hiddenID + '"></div>');

            $('.tagsinput-add-container').prepend('<div class="tagsinput-add"></div>');
            hiddenField = $('#' + hiddenID);
        }

        this.typeahead(multiTypeaheadOptions)
            .bind('typeahead:selected', function (event, item) {
                $.addTag(item[multiTypeaheadOptions.valueKey]);
            });

        $.prepare();

        return this;
    }

}(window.jQuery);
