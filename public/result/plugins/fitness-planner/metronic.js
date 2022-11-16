/**
Core script to handle the entire theme and core functions
**/
var Metronic = function() {

    // IE mode
    var isRTL = false;
    var isIE8 = false;
    var isIE9 = false;
    var isIE10 = false;

    var resizeHandlers = [];

    var assetsPath = '../../assets/';

    var globalImgPath = 'global/img/';

    var globalPluginsPath = 'global/plugins/';

    var globalCssPath = 'global/css/';

    // theme layout color set

    var brandColors = {
        'blue': '#89C4F4',
        'red': '#F3565D',
        'green': '#1bbc9b',
        'purple': '#9b59b6',
        'grey': '#95a5a6',
        'yellow': '#F8CB00'
    };

    // initializes main settings
    var handleInit = function() {
        /*
        if (jQuery('body').css('direction') === 'rtl') {
            isRTL = true;
        }*/

        isIE8 = !!navigator.userAgent.match(/MSIE 8.0/);
        isIE9 = !!navigator.userAgent.match(/MSIE 9.0/);
        isIE10 = !!navigator.userAgent.match(/MSIE 10.0/);

        if (isIE10) {
            jQuery('html').addClass('ie10'); // detect IE10 version
        }

        if (isIE10 || isIE9 || isIE8) {
            jQuery('html').addClass('ie'); // detect IE10 version
        }
    };

    // runs callback functions set by Metronic.addResponsiveHandler().
    var _runResizeHandlers = function() {
        // reinitialize other subscribed elements
        for (var i = 0; i < resizeHandlers.length; i++) {
            var each = resizeHandlers[i];
            each.call();
        }
    };

    // handle the layout reinitialization on window resize
    var handleOnResize = function() {
        var resize;
        if (isIE8) {
            var currheight;
            jQuery(window).resize(function() {
                if (currheight == document.documentElement.clientHeight) {
                    return; //quite event since only body resized not window.
                }
                if (resize) {
                    clearTimeout(resize);
                }
                resize = setTimeout(function() {
                    _runResizeHandlers();
                }, 50); // wait 50ms until window resize finishes.                
                currheight = document.documentElement.clientHeight; // store last body client height
            });
        } else {
            jQuery(window).resize(function() {
                if (resize) {
                    clearTimeout(resize);
                }
                resize = setTimeout(function() {
                    _runResizeHandlers();
                }, 50); // wait 50ms until window resize finishes.
            });
        }
    };

    // Handles portlet tools & actions
    var handlePortletTools = function() {
        // handle portlet remove
        jQuery('body').on('click', '.portlet > .portlet-title > .tools > a.remove', function(e) {
            e.preventDefault();
            var portlet = jQuery(this).closest(".portlet");

            if (jQuery('body').hasClass('page-portlet-fullscreen')) {
                jQuery('body').removeClass('page-portlet-fullscreen');
            }

            portlet.find('.portlet-title .fullscreen').tooltip('destroy');
            portlet.find('.portlet-title > .tools > .reload').tooltip('destroy');
            portlet.find('.portlet-title > .tools > .remove').tooltip('destroy');
            portlet.find('.portlet-title > .tools > .config').tooltip('destroy');
            portlet.find('.portlet-title > .tools > .collapse, .portlet > .portlet-title > .tools > .expand').tooltip('destroy');

            portlet.remove();
        });

        // handle portlet fullscreen
        jQuery('body').on('click', '.portlet > .portlet-title .fullscreen', function(e) {
            e.preventDefault();
            var portlet = jQuery(this).closest(".portlet");
            if (portlet.hasClass('portlet-fullscreen')) {
                jQuery(this).removeClass('on');
                portlet.removeClass('portlet-fullscreen');
                jQuery('body').removeClass('page-portlet-fullscreen');
                portlet.children('.portlet-body').css('height', 'auto');
            } else {
                var height = Metronic.getViewPort().height -
                    portlet.children('.portlet-title').outerHeight() -
                    parseInt(portlet.children('.portlet-body').css('padding-top')) -
                    parseInt(portlet.children('.portlet-body').css('padding-bottom'));

                jQuery(this).addClass('on');
                portlet.addClass('portlet-fullscreen');
                jQuery('body').addClass('page-portlet-fullscreen');
                portlet.children('.portlet-body').css('height', height);
            }
        });

        jQuery('body').on('click', '.portlet > .portlet-title > .tools > a.reload', function(e) {
            e.preventDefault();
            var el = jQuery(this).closest(".portlet").children(".portlet-body");
            var url = jQuery(this).attr("data-url");
            var error = jQuery(this).attr("data-error-display");
            if (url) {
                Metronic.blockUI({
                    target: el,
                    animate: true,
                    overlayColor: 'none'
                });
                jQuery.ajax({
                    type: "GET",
                    cache: false,
                    url: url,
                    dataType: "html",
                    success: function(res) {
                        Metronic.unblockUI(el);
                        el.html(res);
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        Metronic.unblockUI(el);
                        var msg = 'Error on reloading the content. Please check your connection and try again.';
                        if (error == "toastr" && toastr) {
                            toastr.error(msg);
                        } else if (error == "notific8" && jQuery.notific8) {
                            jQuery.notific8('zindex', 11500);
                            jQuery.notific8(msg, {
                                theme: 'ruby',
                                life: 3000
                            });
                        } else {
                            alert(msg);
                        }
                    }
                });
            } else {
                // for demo purpose
                Metronic.blockUI({
                    target: el,
                    animate: true,
                    overlayColor: 'none'
                });
                window.setTimeout(function() {
                    Metronic.unblockUI(el);
                }, 1000);
            }
        });

        // load ajax data on page init
        jQuery('.portlet .portlet-title a.reload[data-load="true"]').click();

        jQuery('body').on('click', '.portlet > .portlet-title > .tools > .collapse, .portlet .portlet-title > .tools > .expand', function(e) {
            e.preventDefault();
            var el = jQuery(this).closest(".portlet").children(".portlet-body");
            if (jQuery(this).hasClass("collapse")) {
                jQuery(this).removeClass("collapse").addClass("expand");
                el.slideUp(200);
            } else {
                jQuery(this).removeClass("expand").addClass("collapse");
                el.slideDown(200);
            }
        });
    };

    // Handles custom checkboxes & radios using jQuery Uniform plugin
    var handleUniform = function() {
        if (!jQuery().uniform) {
            return;
        }
        var test = jQuery("input[type=checkbox]:not(.toggle, .make-switch, .icheck), input[type=radio]:not(.toggle, .star, .make-switch, .icheck)");
        if (test.size() > 0) {
            test.each(function() {
                if (jQuery(this).parents(".checker").size() === 0) {
                    jQuery(this).show();
                    jQuery(this).uniform();
                }
            });
        }
    };

    // Handles custom checkboxes & radios using jQuery iCheck plugin
    var handleiCheck = function() {
        if (!jQuery().iCheck) {
            return;
        }

        jQuery('.icheck').each(function() {
            var checkboxClass = jQuery(this).attr('data-checkbox') ? jQuery(this).attr('data-checkbox') : 'icheckbox_minimal-grey';
            var radioClass = jQuery(this).attr('data-radio') ? jQuery(this).attr('data-radio') : 'iradio_minimal-grey';

            if (checkboxClass.indexOf('_line') > -1 || radioClass.indexOf('_line') > -1) {
                jQuery(this).iCheck({
                    checkboxClass: checkboxClass,
                    radioClass: radioClass,
                    insert: '<div class="icheck_line-icon"></div>' + jQuery(this).attr("data-label")
                });
            } else {
                jQuery(this).iCheck({
                    checkboxClass: checkboxClass,
                    radioClass: radioClass
                });
            }
        });
    };

    // Handles Bootstrap switches
    var handleBootstrapSwitch = function() {
        if (!jQuery().bootstrapSwitch) {
            return;
        }
        jQuery('.make-switch').bootstrapSwitch();
    };

    // Handles Bootstrap confirmations
    var handleBootstrapConfirmation = function() {
        if (!jQuery().confirmation) {
            return;
        }
        jQuery('[data-toggle=confirmation]').confirmation({ container: 'body', btnOkClass: 'btn-xs btn-success', btnCancelClass: 'btn-xs btn-danger'});
    }
    
    // Handles Bootstrap Accordions.
    var handleAccordions = function() {
        jQuery('body').on('shown.bs.collapse', '.accordion.scrollable', function(e) {
            Metronic.scrollTo(jQuery(e.target));
        });
    };

    // Handles Bootstrap Tabs.
    var handleTabs = function() {
        //activate tab if tab id provided in the URL
        if (location.hash) {
            var tabid = location.hash.substr(1);
            jQuery('a[href="#' + tabid + '"]').parents('.tab-pane:hidden').each(function() {
                var tabid = jQuery(this).attr("id");
                jQuery('a[href="#' + tabid + '"]').click();
            });
            jQuery('a[href="#' + tabid + '"]').click();
        }
    };

    // Handles Bootstrap Modals.
    var handleModals = function() {        
        // fix stackable modal issue: when 2 or more modals opened, closing one of modal will remove .modal-open class. 
        jQuery('body').on('hide.bs.modal', function() {
            if (jQuery('.modal:visible').size() > 1 && jQuery('html').hasClass('modal-open') === false) {
                jQuery('html').addClass('modal-open');
            } else if (jQuery('.modal:visible').size() <= 1) {
                jQuery('html').removeClass('modal-open');
            }
        });

        // fix page scrollbars issue
        jQuery('body').on('show.bs.modal', '.modal', function() {
            if (jQuery(this).hasClass("modal-scroll")) {
                jQuery('body').addClass("modal-open-noscroll");
            }
        });

        // fix page scrollbars issue
        jQuery('body').on('hide.bs.modal', '.modal', function() {
            jQuery('body').removeClass("modal-open-noscroll");
        });

        // remove ajax content and remove cache on modal closed 
        jQuery('body').on('hidden.bs.modal', '.modal:not(.modal-cached)', function () {
            jQuery(this).removeData('bs.modal');
        });
    };

    // Handles Bootstrap Tooltips.
    var handleTooltips = function() {
        // global tooltips
        jQuery('.tooltips').tooltip();

        // portlet tooltips
        jQuery('.portlet > .portlet-title .fullscreen').tooltip({
            container: 'body',
            title: 'Fullscreen'
        });
        jQuery('.portlet > .portlet-title > .tools > .reload').tooltip({
            container: 'body',
            title: 'Reload'
        });
        jQuery('.portlet > .portlet-title > .tools > .remove').tooltip({
            container: 'body',
            title: 'Remove'
        });
        jQuery('.portlet > .portlet-title > .tools > .config').tooltip({
            container: 'body',
            title: 'Settings'
        });
        jQuery('.portlet > .portlet-title > .tools > .collapse, .portlet > .portlet-title > .tools > .expand').tooltip({
            container: 'body',
            title: 'Collapse/Expand'
        });
    };

    // Handles Bootstrap Dropdowns
    var handleDropdowns = function() {
        /*
          Hold dropdown on click  
        */
        jQuery('body').on('click', '.dropdown-menu.hold-on-click', function(e) {
            e.stopPropagation();
        });
    };

    var handleAlerts = function() {
        jQuery('body').on('click', '[data-close="alert"]', function(e) {
            jQuery(this).parent('.alert').hide();
            jQuery(this).closest('.note').hide();
            e.preventDefault();
        });

        jQuery('body').on('click', '[data-close="note"]', function(e) {
            jQuery(this).closest('.note').hide();
            e.preventDefault();
        });

        jQuery('body').on('click', '[data-remove="note"]', function(e) {
            jQuery(this).closest('.note').remove();
            e.preventDefault();
        });
    };

    // Handle Hower Dropdowns
    var handleDropdownHover = function() {
        jQuery('[data-hover="dropdown"]').not('.hover-initialized').each(function() {
            jQuery(this).dropdownHover();
            jQuery(this).addClass('hover-initialized');
        });
    };

    // Handles Bootstrap Popovers

    // last popep popover
    var lastPopedPopover;

    var handlePopovers = function() {
        jQuery('.popovers').popover();

        // close last displayed popover

        jQuery(document).on('click.bs.popover.data-api', function(e) {
            if (lastPopedPopover) {
                lastPopedPopover.popover('hide');
            }
        });
    };

    // Handles scrollable contents using jQuery SlimScroll plugin.
    var handleScrollers = function() {
        Metronic.initSlimScroll('.scroller');
    };

    // Handles Image Preview using jQuery Fancybox plugin
    var handleFancybox = function() {
        if (!jQuery.fancybox) {
            return;
        }

        if (jQuery(".fancybox-button").size() > 0) {
            jQuery(".fancybox-button").fancybox({
                groupAttr: 'data-rel',
                prevEffect: 'none',
                nextEffect: 'none',
                closeBtn: true,
                helpers: {
                    title: {
                        type: 'inside'
                    }
                }
            });
        }
    };

    // Fix input placeholder issue for IE8 and IE9
    var handleFixInputPlaceholderForIE = function() {
        //fix html5 placeholder attribute for ie7 & ie8
        if (isIE8 || isIE9) { // ie8 & ie9
            // this is html5 placeholder fix for inputs, inputs with placeholder-no-fix class will be skipped(e.g: we need this for password fields)
            jQuery('input[placeholder]:not(.placeholder-no-fix), textarea[placeholder]:not(.placeholder-no-fix)').each(function() {
                var input = jQuery(this);

                if (input.val() === '' && input.attr("placeholder") !== '') {
                    input.addClass("placeholder").val(input.attr('placeholder'));
                }

                input.focus(function() {
                    if (input.val() == input.attr('placeholder')) {
                        input.val('');
                    }
                });

                input.blur(function() {
                    if (input.val() === '' || input.val() == input.attr('placeholder')) {
                        input.val(input.attr('placeholder'));
                    }
                });
            });
        }
    };

    // Handle Select2 Dropdowns
    var handleSelect2 = function() {
        if (jQuery().select2) {
            jQuery('.select2me').select2({
                placeholder: "Select",
                allowClear: true
            });
        }
    };

    //* END:CORE HANDLERS *//

    return {

        //main function to initiate the theme
        init: function() {
            //IMPORTANT!!!: Do not modify the core handlers call order.

            //Core handlers
            handleInit(); // initialize core variables
            handleOnResize(); // set and handle responsive    

            //UI Component handlers            
            handleUniform(); // hanfle custom radio & checkboxes
            handleiCheck(); // handles custom icheck radio and checkboxes
            handleBootstrapSwitch(); // handle bootstrap switch plugin
            handleScrollers(); // handles slim scrolling contents 
            handleFancybox(); // handle fancy box
            handleSelect2(); // handle custom Select2 dropdowns
            handlePortletTools(); // handles portlet action bar functionality(refresh, configure, toggle, remove)
            handleAlerts(); //handle closabled alerts
            handleDropdowns(); // handle dropdowns
            handleTabs(); // handle tabs
            handleTooltips(); // handle bootstrap tooltips
            handlePopovers(); // handles bootstrap popovers
            handleAccordions(); //handles accordions 
            handleModals(); // handle modals
            handleBootstrapConfirmation(); // handle bootstrap confirmations

            // Hacks
            handleFixInputPlaceholderForIE(); //IE8 & IE9 input placeholder issue fix
        },

        //main function to initiate core javascript after ajax complete
        initAjax: function() {
            handleUniform(); // handles custom radio & checkboxes     
            handleiCheck(); // handles custom icheck radio and checkboxes
            handleBootstrapSwitch(); // handle bootstrap switch plugin
            handleDropdownHover(); // handles dropdown hover       
            handleScrollers(); // handles slim scrolling contents 
            handleSelect2(); // handle custom Select2 dropdowns
            handleFancybox(); // handle fancy box
            handleDropdowns(); // handle dropdowns
            handleTooltips(); // handle bootstrap tooltips
            handlePopovers(); // handles bootstrap popovers
            handleAccordions(); //handles accordions 
            handleBootstrapConfirmation(); // handle bootstrap confirmations
        },

        //init main components 
        initComponents: function() {
            this.initAjax();
        },

        //public function to remember last opened popover that needs to be closed on click
        setLastPopedPopover: function(el) {
            lastPopedPopover = el;
        },

        //public function to add callback a function which will be called on window resize
        addResizeHandler: function(func) {
            resizeHandlers.push(func);
        },

        //public functon to call _runresizeHandlers
        runResizeHandlers: function() {
            _runResizeHandlers();
        },

        // wrMetronicer function to scroll(focus) to an element
        scrollTo: function(el, offeset) {
            var pos = (el && el.size() > 0) ? el.offset().top : 0;

            if (el) {
                if (jQuery('body').hasClass('page-header-fixed')) {
                    pos = pos - jQuery('.page-header').height();
                }
                pos = pos + (offeset ? offeset : -1 * el.height());
            }

            jQuery('html,body').animate({
                scrollTop: pos
            }, 'slow');
        },

        initSlimScroll: function(el) {
            jQuery(el).each(function() {
                if (jQuery(this).attr("data-initialized")) {
                    return; // exit
                }

                var height;

                if (jQuery(this).attr("data-height")) {
                    height = jQuery(this).attr("data-height");
                } else {
                    height = jQuery(this).css('height');
                }

                jQuery(this).slimScroll({
                    allowPageScroll: true, // allow page scroll when the element scroll is ended
                    size: '7px',
                    color: (jQuery(this).attr("data-handle-color") ? jQuery(this).attr("data-handle-color") : '#bbb'),
                    wrapperClass: (jQuery(this).attr("data-wrapper-class") ? jQuery(this).attr("data-wrapper-class") : 'slimScrollDiv'),
                    railColor: (jQuery(this).attr("data-rail-color") ? jQuery(this).attr("data-rail-color") : '#eaeaea'),
                    position: isRTL ? 'left' : 'right',
                    height: height,
                    alwaysVisible: (jQuery(this).attr("data-always-visible") == "1" ? true : false),
                    railVisible: (jQuery(this).attr("data-rail-visible") == "1" ? true : false),
                    disableFadeOut: true
                });

                jQuery(this).attr("data-initialized", "1");
            });
        },

        destroySlimScroll: function(el) {
            jQuery(el).each(function() {
                if (jQuery(this).attr("data-initialized") === "1") { // destroy existing instance before updating the height
                    jQuery(this).removeAttr("data-initialized");
                    jQuery(this).removeAttr("style");

                    var attrList = {};

                    // store the custom attribures so later we will reassign.
                    if (jQuery(this).attr("data-handle-color")) {
                        attrList["data-handle-color"] = jQuery(this).attr("data-handle-color");
                    }
                    if (jQuery(this).attr("data-wrapper-class")) {
                        attrList["data-wrapper-class"] = jQuery(this).attr("data-wrapper-class");
                    }
                    if (jQuery(this).attr("data-rail-color")) {
                        attrList["data-rail-color"] = jQuery(this).attr("data-rail-color");
                    }
                    if (jQuery(this).attr("data-always-visible")) {
                        attrList["data-always-visible"] = jQuery(this).attr("data-always-visible");
                    }
                    if (jQuery(this).attr("data-rail-visible")) {
                        attrList["data-rail-visible"] = jQuery(this).attr("data-rail-visible");
                    }

                    jQuery(this).slimScroll({
                        wrapperClass: (jQuery(this).attr("data-wrapper-class") ? jQuery(this).attr("data-wrapper-class") : 'slimScrollDiv'),
                        destroy: true
                    });

                    var the = jQuery(this);

                    // reassign custom attributes
                    jQuery.each(attrList, function(key, value) {
                        the.attr(key, value);
                    });

                }
            });
        },

        // function to scroll to the top
        scrollTop: function() {
            Metronic.scrollTo();
        },

        // wrMetronicer function to  block element(indicate loading)
        blockUI: function(options) {
            options = jQuery.extend(true, {}, options);
            var html = '';
            if (options.animate) {
                html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '">' + '<div class="block-spinner-bar"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>' + '</div>';
            } else if (options.iconOnly) {
                html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><img src="' + this.getGlobalImgPath() + 'loading-spinner-grey.gif" align=""></div>';
            } else if (options.textOnly) {
                html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><span>&nbsp;&nbsp;' + (options.message ? options.message : 'LOADING...') + '</span></div>';
            } else {
                html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><img src="' + this.getGlobalImgPath() + 'loading-spinner-grey.gif" align=""><span>&nbsp;&nbsp;' + (options.message ? options.message : 'LOADING...') + '</span></div>';
            }

            if (options.target) { // element blocking
                var el = jQuery(options.target);
                if (el.height() <= (jQuery(window).height())) {
                    options.cenrerY = true;
                }
                el.block({
                    message: html,
                    baseZ: options.zIndex ? options.zIndex : 1000,
                    centerY: options.cenrerY !== undefined ? options.cenrerY : false,
                    css: {
                        top: '10%',
                        border: '0',
                        padding: '0',
                        backgroundColor: 'none'
                    },
                    overlayCSS: {
                        backgroundColor: options.overlayColor ? options.overlayColor : '#555',
                        opacity: options.boxed ? 0.05 : 0.1,
                        cursor: 'wait'
                    }
                });
            } else { // page blocking
                jQuery.blockUI({
                    message: html,
                    baseZ: options.zIndex ? options.zIndex : 1000,
                    css: {
                        border: '0',
                        padding: '0',
                        backgroundColor: 'none'
                    },
                    overlayCSS: {
                        backgroundColor: options.overlayColor ? options.overlayColor : '#555',
                        opacity: options.boxed ? 0.05 : 0.1,
                        cursor: 'wait'
                    }
                });
            }
        },

        // wrMetronicer function to  un-block element(finish loading)
        unblockUI: function(target) {
            if (target) {
                jQuery(target).unblock({
                    onUnblock: function() {
                        jQuery(target).css('position', '');
                        jQuery(target).css('zoom', '');
                    }
                });
            } else {
                jQuery.unblockUI();
            }
        },

        startPageLoading: function(options) {
            if (options && options.animate) {
                jQuery('.page-spinner-bar').remove();
                jQuery('body').append('<div class="page-spinner-bar"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>');
            } else {
                jQuery('.page-loading').remove();
                jQuery('body').append('<div class="page-loading"><img src="' + this.getGlobalImgPath() + 'loading-spinner-grey.gif"/>&nbsp;&nbsp;<span>' + (options && options.message ? options.message : 'Loading...') + '</span></div>');
            }
        },

        stopPageLoading: function() {
            jQuery('.page-loading, .page-spinner-bar').remove();
        },

        alert: function(options) {

            options = jQuery.extend(true, {
                container: "", // alerts parent container(by default placed after the page breadcrumbs)
                place: "append", // "append" or "prepend" in container 
                type: 'success', // alert's type
                message: "", // alert's message
                close: true, // make alert closable
                reset: true, // close all previouse alerts first
                focus: true, // auto scroll to the alert after shown
                closeInSeconds: 0, // auto close after defined seconds
                icon: "" // put icon before the message
            }, options);

            var id = Metronic.getUniqueID("Metronic_alert");

            var html = '<div id="' + id + '" class="Metronic-alerts alert alert-' + options.type + ' fade in">' + (options.close ? '<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>' : '') + (options.icon !== "" ? '<i class="fa-lg fa fa-' + options.icon + '"></i>  ' : '') + options.message + '</div>';

            if (options.reset) {
                jQuery('.Metronic-alerts').remove();
            }

            if (!options.container) {
                if (jQuery('body').hasClass("page-container-bg-solid")) {
                    jQuery('.page-title').after(html);
                } else {
                    if (jQuery('.page-bar').size() > 0) {
                        jQuery('.page-bar').after(html);
                    } else {
                        jQuery('.page-breadcrumb').after(html);
                    }
                }
            } else {
                if (options.place == "append") {
                    jQuery(options.container).append(html);
                } else {
                    jQuery(options.container).prepend(html);
                }
            }

            if (options.focus) {
                Metronic.scrollTo(jQuery('#' + id));
            }

            if (options.closeInSeconds > 0) {
                setTimeout(function() {
                    jQuery('#' + id).remove();
                }, options.closeInSeconds * 1000);
            }

            return id;
        },

        // initializes uniform elements
        initUniform: function(els) {
            if (els) {
                jQuery(els).each(function() {
                    if (jQuery(this).parents(".checker").size() === 0) {
                        jQuery(this).show();
                        jQuery(this).uniform();
                    }
                });
            } else {
                handleUniform();
            }
        },

        //wrMetronicer function to update/sync jquery uniform checkbox & radios
        updateUniform: function(els) {
            jQuery.uniform.update(els); // update the uniform checkbox & radios UI after the actual input control state changed
        },

        //public function to initialize the fancybox plugin
        initFancybox: function() {
            handleFancybox();
        },

        //public helper function to get actual input value(used in IE9 and IE8 due to placeholder attribute not supported)
        getActualVal: function(el) {
            el = jQuery(el);
            if (el.val() === el.attr("placeholder")) {
                return "";
            }
            return el.val();
        },

        //public function to get a paremeter by name from URL
        getURLParameter: function(paramName) {
            var searchString = window.location.search.substring(1),
                i, val, params = searchString.split("&");

            for (i = 0; i < params.length; i++) {
                val = params[i].split("=");
                if (val[0] == paramName) {
                    return unescape(val[1]);
                }
            }
            return null;
        },

        // check for device touch support
        isTouchDevice: function() {
            try {
                document.createEvent("TouchEvent");
                return true;
            } catch (e) {
                return false;
            }
        },

        // To get the correct viewport width based on  http://andylangton.co.uk/articles/javascript/get-viewport-size-javascript/
        getViewPort: function() {
            var e = window,
                a = 'inner';
            if (!('innerWidth' in window)) {
                a = 'client';
                e = document.documentElement || document.body;
            }

            return {
                width: e[a + 'Width'],
                height: e[a + 'Height']
            };
        },

        getUniqueID: function(prefix) {
            return 'prefix_' + Math.floor(Math.random() * (new Date()).getTime());
        },

        // check IE8 mode
        isIE8: function() {
            return isIE8;
        },

        // check IE9 mode
        isIE9: function() {
            return isIE9;
        },

        //check RTL mode
        isRTL: function() {
            return isRTL;
        },

        // check IE8 mode
        isAngularJsApp: function() {
            return (typeof angular == 'undefined') ? false : true;
        },

        getAssetsPath: function() {
            return assetsPath;
        },

        setAssetsPath: function(path) {
            assetsPath = path;
        },

        setGlobalImgPath: function(path) {
            globalImgPath = path;
        },

        getGlobalImgPath: function() {
            return assetsPath + globalImgPath;
        },

        setGlobalPluginsPath: function(path) {
            globalPluginsPath = path;
        },

        getGlobalPluginsPath: function() {
            return assetsPath + globalPluginsPath;
        },

        getGlobalCssPath: function() {
            return assetsPath + globalCssPath;
        },

        // get layout color code by color name
        getBrandColor: function(name) {
            if (brandColors[name]) {
                return brandColors[name];
            } else {
                return '';
            }
        },

        getResponsiveBreakpoint: function(size) {
            // bootstrap responsive breakpoints
            var sizes = {
                'xs' : 480,     // extra small
                'sm' : 768,     // small
                'md' : 992,     // medium
                'lg' : 1200     // large
            };

            return sizes[size] ? sizes[size] : 0; 
        }
    };

}();