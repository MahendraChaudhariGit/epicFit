/** start: Angular.JS **/
var vpFormBM = angular.module('vpFormBM', ["angularjs-autogrow"]);

vpFormBM.controller('BMController', ['$rootScope', '$scope', function ($rootScope, $scope) {
    $rootScope.isFormStarted = false;

    var interval = setInterval(function () {
        $('#input-starting-screen').focus();
    }, 100);

    $scope.startFormInput = function () {
        $('.starting-screen').removeClass('fade-in');
        $('.panel-body').removeClass('invisible');

        $rootScope.isFormStarted = true;
        clearInterval(interval);
        $('body').css('overflow', 'auto');

        window.stepOneScrollToItem(0);
    }

    $scope.pressEnter = function (event) {
        if(event.which === 13 && !event.shiftKey) {
            event.preventDefault();

            $scope.startFormInput();
        }
    }
}]);

// controller benchmark #1
vpFormBM.controller('BMWidgetOne', ['$scope', '$sce', function($scope, $sce) {

    // changes required for new step
    var container = $('.container-gb');
    // changes required for new step
    $scope.controllerStep = 1;

    $scope.activeIndex = 0;
    $scope.activeSubIndex = null;
    $scope.indexType = "select";

    $scope.percentCompleted = 0;

    $scope.bm_time_opt = "Manual Time Entry";

    $scope.updateBmTimeOpt = function (value) {
        $scope.bm_time_opt = value;
    }


    $scope.offsetTop = 150;

    window.stepOneScrollToItem = function(index) {
        $scope.scrollToItem(index);
    }

    /* start: Functions **/
    $scope.pressEnter = function (event) {
        if($scope.controllerStep === parseInt($('#m-selected-step').val())) {
            if(event.which === 13 && !event.shiftKey) {
                event.preventDefault();
                $scope.jumpToNextInput();
            }
        }
    }

    $scope.jumpToNextInput = function () {
        var cIndex = $scope.activeIndex,
            nIndex = cIndex + 1;

        var cElem = container.find('.vp-item').eq(cIndex),
            isValid = cElem.attr('data-valid');

        if(isValid === "false") {
            return false;
        }

        var nElem = container.find('.vp-item').eq(nIndex);
        if(nElem.length < 1) {
            // validate form
            $scope.validateWidgetInputs();
            // submit the form
            return false
        }

        $scope.scrollToItem(nIndex);
    }

    // jump to prev input
    $scope.jumpToPrevInput = function () {
        var pIndex = parseInt($scope.activeIndex) - 1;

        $scope.scrollToItem(pIndex);
    }

    $scope.validateWidgetInputs = function() {
        var invalidInputs = container.find('.vp-item[data-valid="false"]');

        if(invalidInputs.length > 0) {
            var invalidInput = invalidInputs.eq(0),
                invalidIndex = parseInt($(invalidInput).attr('data-index'));

            $scope.scrollToItem(invalidIndex);

            return false
        }
    }

    $scope.scrollToItem = function (index) {
        var elem = container.find(".vp-item").eq(index)

        if(elem) {
            jQuery('html, body').stop().animate({
                scrollTop: jQuery(elem).offset().top - $scope.offsetTop
            }, 'slow');
        }
    }


    // calculate form percent completed
    $scope.calculatePercentCompleted = function() {
        var totalItem = container.find('li.vp-item').length,
            currentItem = $scope.activeIndex;

        if(currentItem === totalItem - 1) {
            $scope.percentCompleted = 100;
        } else {
            $scope.percentCompleted = Math.ceil((100 * currentItem) / totalItem);
        }
        $scope.$digest();
    }


    $scope.isControllerActive = function() {
        return $scope.controllerStep === parseInt($('#m-selected-step').val());
    }

    // take action on active item based on keypress
    $(document).bind('keypress', function(event) {

        if($scope.isControllerActive()) {

        }
    });

    // changes required for new step
    $scope.setActiveIndexAndType = function (index, subIndex, itype) {
        $scope.activeIndex = parseInt(index);
        $scope.activeSubIndex = parseInt(subIndex);
        $scope.indexType = itype;

        $scope.calculatePercentCompleted();
    }

    $scope.windowAdventure = function(viewport, item, callback) {
        var wa = {};
        wa.waViewPort = viewport;
        wa.item = item;
        wa.callback = callback;
        wa.ci = 0;
        wa.cItem = null;

        if($scope.isControllerActive()) {
            $(window).bind('scroll', function () {
                clearTimeout($.data(this, 'scrollTimer'));

                $.data(this, 'scrollTimer', setTimeout(function () {
                    var viewPort = $(wa.waViewPort),
                        items = viewPort.find(wa.item);

                    var itemsInWindow = wa._itemsInWindow(items),
                        currentIndex = itemsInWindow[0];

                    if (currentIndex !== wa.ci && currentIndex > -1) {

                        // return with viewport and current item
                        wa.cItem = $(items.eq(currentIndex));
                        wa.ci = currentIndex;

                        return wa.callback(wa.waViewPort, wa.cItem);
                    }

                }, 30));

            });
        }


        wa._itemsInWindow = function(items) {
            var vpTop = $(window).scrollTop(),
                wHeight = $(window).height()
            vpBottom =  vpTop + wHeight;

            var vpItems = [];

            for(var i = 0; i < items.length; i++) {
                var item = $(items.eq(i));

                var itemTop = item.offset().top,
                    itemBottom = itemTop + item.outerHeight();

                if(itemBottom > vpTop + (wHeight / 2.5) && itemTop < vpBottom) {
                    vpItems.push(i);
                }
            }

            return vpItems;
        }
    }


    $scope.activateWindowAdventure = function() {
        $scope.windowAdventure('#viewport-1', '.vp-item', function (viewport, currentItem) {
            var activeIndex = parseInt(jQuery(currentItem).attr('data-index')),
                activeSubIndex = parseInt(jQuery(currentItem).attr('data-sub-index')),
                iType = jQuery(currentItem).attr('data-type');



            $(viewport).find('.vp-item').removeClass('vp-form-active');

            // add class active to current item
            $(currentItem).addClass('vp-form-active')
                .find('.input-header, .input-body').slideDown(400);

            $scope.setActiveIndexAndType(activeIndex, activeSubIndex, iType);

            // focus current input
            jQuery(currentItem).find('input, textarea, select').focus();

            // trigger change
            $(viewport).find('input, textarea, select').trigger('change');

            if(iType === "radio") {
                $(document).find('input, textarea, select').blur();
            }

            $scope.scrollToItem($scope.activeIndex);
        });
    }

    $('#m-selected-step').on('change', function() {
        if($scope.isControllerActive()) {
            setTimeout(function() {

                $(window).scrollTop(0);
            }, 200);

            setTimeout(function() {
                $(container).find('.vp-item').removeClass('vp-form-active');

                // add class active to current item
                var currentItem = $(container).find('.vp-item').eq('0');

                $(currentItem).addClass('vp-form-active')
                    .find('.input-header, .input-body').slideDown(400);

                var activeIndex = parseInt(jQuery(currentItem).attr('data-index')),
                    activeSubIndex = parseInt(jQuery(currentItem).attr('data-sub-index')),
                    iType = jQuery(currentItem).attr('data-type');

                $scope.setActiveIndexAndType(activeIndex, activeSubIndex, iType);

                // focus current input
                jQuery(currentItem).find('input, textarea, select').focus();

                if(iType === "radio") {
                    $(document).find('input, textarea, select').blur();
                }
            }, 300)

            $scope.activateWindowAdventure();
        }
    });


    // changes required for new step
    window.getBMS1ActiveIndex = function () {
        return $scope.activeIndex;
    }

    // changes required for new step
    window.digestBMS1 = function () {
        $scope.$digest();
    }
    /* end: Functions **/
}]);



// controller benchmark #2
vpFormBM.controller('BMWidgetTwo', ['$scope', '$sce', function($scope, $sce) {

    // changes required for new step
    var container = $('.container-bm-step-2');
    // changes required for new step
    $scope.controllerStep = 2;

    $scope.activeIndex = 0;
    $scope.activeSubIndex = 0;
    $scope.indexType = "rating";

    $scope.percentCompleted = 0;

    $scope.offsetTop = 150;

    /* start: Functions **/
    $scope.pressEnter = function (event) {
        if($scope.controllerStep === parseInt($('#m-selected-step').val())) {
            if(event.which === 13 && !event.shiftKey) {
                event.preventDefault();
                $scope.jumpToNextInput();
            }
        }
    }

    $scope.jumpToNextInput = function () {
        var cIndex = $scope.activeIndex,
            nIndex = cIndex + 1;

        var cElem = container.find('.vp-item').eq(cIndex),
            isValid = cElem.attr('data-valid');

        if(isValid === "false") {
            return false;
        }

        var nElem = container.find('.vp-item').eq(nIndex);
        if(nElem.length < 1) {
            // validate form
            $scope.validateWidgetInputs();
            // submit the form
            return false
        }

        $scope.scrollToItem(nIndex);
    }

    // jump to prev input
    $scope.jumpToPrevInput = function () {
        var pIndex = parseInt($scope.activeIndex) - 1;

        $scope.scrollToItem(pIndex);
    }

    $scope.validateWidgetInputs = function() {
        var invalidInputs = container.find('.vp-item[data-valid="false"]');

        if(invalidInputs.length > 0) {
            var invalidInput = invalidInputs.eq(0),
                invalidIndex = parseInt($(invalidInput).attr('data-index'));

            $scope.scrollToItem(invalidIndex);

            return false
        }
    }

    $scope.scrollToItem = function (index) {
        var elem = container.find(".vp-item").eq(index)

        if(elem) {
            jQuery('html, body').stop().animate({
                scrollTop: jQuery(elem).offset().top - $scope.offsetTop
            }, 'slow');
        }
    }

    $scope.setRatingValue = function(ratingIndex, value, digestRequired) {
        var isDigestRequired = false;
        if(digestRequired !== 'undefined' && digestRequired !== undefined) {
            isDigestRequired = digestRequired;
        }

        var item = $scope.data.rating[parseInt(ratingIndex)];

        item.value = parseInt(value);

        if(isDigestRequired) {
            $scope.$digest();
        }

        setTimeout(function() {
            $scope.jumpToNextInput();
        }, 400);
    }

    $scope.setRadioValue = function (radioIndex, optionIndex, digestRequired) {
        var isDigestRequired = false;
        if(digestRequired != 'undefined') {
            isDigestRequired = digestRequired;
        }

        var cRadio = $scope.data.radio[radioIndex],
            cOption = cRadio.options[optionIndex];

        cRadio.activeOption = optionIndex;
        cRadio.value = cOption.value;

        if(cOption.customValueEnabled) {
            // set data receive mode yes
            cOption.isDataReceiving = "yes";

            // set default custom value
            cOption.customValue = cOption.value;
        } else {
            // jump to next input
            $scope.jumpToNextInput();
        }
        if(isDigestRequired) {
            $scope.$digest();
        }
    }

    $scope.updateRadioOptionValue = function ($event, gGadioIndex, gOptionIndex) {
        $event.stopPropagation();

        var cIndex = $scope.activeIndex,
            radioIndex = gGadioIndex,
            optionIndex = gOptionIndex;

        // var itemIndex = parseInt($scope.vpFormBM.activeItem),
        //     optionIndex = parseInt(optionIndex);

        var item = $scope.data.radio[radioIndex],
            option = item.options[optionIndex];

        if(option.customValue) {
            // set item value
            item.value = option.customValue;
            // update option value
            option.value = option.customValue;
            option.label = option.customValue;
            // set date receive mode no
            option.isDataReceiving = "no";

            $scope.jumpToNextInput();
        }
    }


    // calculate form percent completed
    $scope.calculatePercentCompleted = function() {
        var totalItem = container.find('li.vp-item').length,
            currentItem = $scope.activeIndex;

        if(currentItem === totalItem - 1) {
            $scope.percentCompleted = 100;
        } else {
            $scope.percentCompleted = Math.ceil((100 * currentItem) / totalItem);
        }
        $scope.$digest();
    }


    $scope.isControllerActive = function() {
        return $scope.controllerStep === parseInt($('#m-selected-step').val());
    }

    // take action on active item based on keypress
    $(document).bind('keypress', function(event) {

        if($scope.isControllerActive()) {

            var cIndex = $scope.activeIndex,
                cSubIndex = $scope.activeSubIndex,
                indexType = $scope.indexType;


            if(indexType === 'rating' || indexType === 'rating-number') {
                var key = parseInt(event.key);

                var item = $scope.data.rating[cSubIndex];
                if(key > 0 && key <= item.itemCount ) {
                    $scope.setRatingValue(cSubIndex, key, true);
                }
            }

            if(indexType === 'radio' || indexType === 'checkbox') {
                // get the active item
                var item = undefined;
                if(indexType === 'radio') {
                    item = $scope.data.radio[cSubIndex];
                } else if(indexType === 'checkbox') {
                    item = $scope.data.checkbox[cSubIndex];
                }

                var isKeyTracking = true;

                for(var j = 0; j < item.options.length; j++) {
                    var tOption = item.options[j];
                    if(tOption.isDataReceiving === "yes") {
                        isKeyTracking = false;
                        break;
                    }
                }

                if( isKeyTracking ) {

                    if(item.keySelectionEnabled) {
                        var key = event.key;
                        key = key.toLowerCase();

                        var optionIndex = -1;

                        for(var i = 0; i < item.options.length; i++) {
                            var tmpOption = item.options[i];

                            var tmpKey = tmpOption.key;
                            if(! tmpKey) {
                                continue;
                            }

                            tmpKey = tmpKey.toLowerCase();

                            if(tmpKey === key) {
                                optionIndex = i;
                                break;
                            }
                        } // end for


                        if(indexType === 'radio' && optionIndex > -1) {
                            $scope.setRadioValue(cSubIndex, optionIndex, true);

                            console.log(optionIndex, cIndex, cSubIndex, indexType);
                        }
                        // else if (item.type === 'checkbox' || item.type === 'checkbox-vertical') {
                        //     $scope.vpFormBM.setCheckboxValue(optionIndex);
                        // }
                    }
                } // end if - is key tracking
            }
        }
    });

    $scope.setActiveIndexAndType = function (index, subIndex, itype) {
        $scope.activeIndex = parseInt(index);
        $scope.activeSubIndex = parseInt(subIndex);
        $scope.indexType = itype;

        $scope.calculatePercentCompleted();
    }

    $scope.windowAdventure = function(viewport, item, callback) {
        var wa = {};
        wa.waViewPort = viewport;
        wa.item = item;
        wa.callback = callback;
        wa.ci = 0;
        wa.cItem = null;

        if($scope.isControllerActive()) {
            $(window).bind('scroll', function () {
                clearTimeout($.data(this, 'scrollTimer'));

                $.data(this, 'scrollTimer', setTimeout(function () {
                    var viewPort = $(wa.waViewPort),
                        items = viewPort.find(wa.item);

                    var itemsInWindow = wa._itemsInWindow(items),
                        currentIndex = itemsInWindow[0];

                    if (currentIndex !== wa.ci && currentIndex > -1) {

                        // return with viewport and current item
                        wa.cItem = $(items.eq(currentIndex));
                        wa.ci = currentIndex;

                        return wa.callback(wa.waViewPort, wa.cItem);
                    }

                }, 30));

            });
        }


        wa._itemsInWindow = function(items) {
            var vpTop = $(window).scrollTop(),
                wHeight = $(window).height()
            vpBottom =  vpTop + wHeight;

            var vpItems = [];

            for(var i = 0; i < items.length; i++) {
                var item = $(items.eq(i));

                var itemTop = item.offset().top,
                    itemBottom = itemTop + item.outerHeight();

                if(itemBottom > vpTop + (wHeight / 2.5) && itemTop < vpBottom) {
                    vpItems.push(i);
                }
            }

            return vpItems;
        }
    }


    // changes required for new step
    $scope.activateWindowAdventure = function() {
        $scope.windowAdventure('#viewport-2', '.vp-item', function (viewport, currentItem) {
            var activeIndex = parseInt(jQuery(currentItem).attr('data-index')),
                activeSubIndex = parseInt(jQuery(currentItem).attr('data-sub-index')),
                iType = jQuery(currentItem).attr('data-type');



            $(viewport).find('.vp-item').removeClass('vp-form-active');

            // add class active to current item
            $(currentItem).addClass('vp-form-active')
                .find('.input-header, .input-body').slideDown(400);

            $scope.setActiveIndexAndType(activeIndex, activeSubIndex, iType);

            // focus current input
            jQuery(currentItem).find('input, textarea, select').focus();


            // init rating values
            $scope.data.rating[0].value = $(viewport).find('.stress').val();
            $scope.data.rating[1].value = $(viewport).find('.sleep').val();
            $scope.data.rating[2].value = $(viewport).find('.nutrition').val();
            $scope.data.rating[3].value = $(viewport).find('.hydration').val();
            $scope.data.rating[4].value = $(viewport).find('.humidity').val();

            // trigger change
            $(viewport).find('input, textarea, select').trigger('change');

            if(iType === "radio") {
                $(document).find('input, textarea, select').blur();
            }

            $scope.scrollToItem($scope.activeIndex);
        });
    }

    $('#m-selected-step').on('change', function() {
        if($scope.isControllerActive()) {
            setTimeout(function() {

                $(window).scrollTop(0);
            }, 200);

            setTimeout(function() {
                $(container).find('.vp-item').removeClass('vp-form-active');

                // add class active to current item
                var currentItem = $(container).find('.vp-item').eq('0');

                $(currentItem).addClass('vp-form-active')
                    .find('.input-header, .input-body').slideDown(400);

                var activeIndex = parseInt(jQuery(currentItem).attr('data-index')),
                    activeSubIndex = parseInt(jQuery(currentItem).attr('data-sub-index')),
                    iType = jQuery(currentItem).attr('data-type');

                $scope.setActiveIndexAndType(activeIndex, activeSubIndex, iType);

                // focus current input
                jQuery(currentItem).find('input, textarea, select').focus();

                if(iType === "radio") {
                    $(document).find('input, textarea, select').blur();
                }
            }, 300)

            $scope.activateWindowAdventure();
        }
    });


    // changes required for new step
    window.getBMS2ActiveIndex = function () {
        return $scope.activeIndex;
    }

    // changes required for new step
    window.digestBMS2 = function () {
        $scope.$digest();
    }
    /* end: Functions **/

    // render html
    $scope.renderHtml = function (htmlCode) {
        return $sce.trustAsHtml(htmlCode);
    };

    // get number range
    $scope.range = function(min, max, step) {
        // parameters validation for method overloading
        if (max == undefined) {
            max = min;
            min = 0;
        }
        step = Math.abs(step) || 1;
        if (min > max) {
            step = -step;
        }
        // building the array
        var output = [];
        for (var value=min; value<max; value+=step) {
            output.push(value);
        }
        // returning the generated array
        return output;
    };



    /* start: DATA **/
    $scope.data = {
        rating: [
            {
                // input type rating | 0
                type: 'rating', // required
                label: $scope.renderHtml('<b>Stress</b> (Rate from 1 high to 10 low)'),  // required
                description: '', // optional
                name: 'stress', // required | must be unique
                value: '', // default value | optional
                icon: 'fa fa-star-o fa-4x', // required
                iconFill: 'fa fa-star fa-4x', // required
                itemCount: 10, // required | as much star you want
                isRequired: true, // Boolean | required
            },
            {
                // input type rating  | 1
                type: 'rating', // required
                label: $scope.renderHtml('<b>Sleep</b> (Rate from 1 bad to 10 good)'),  // required
                description: '', // optional
                name: 'sleep', // required | must be unique
                value: '', // default value | optional
                icon: 'fa fa-star-o fa-4x', // required
                iconFill: 'fa fa-star fa-4x', // required
                itemCount: 10, // required | as much star you want
                isRequired: true, // Boolean | required
            },
            {
                // input type rating  | 2
                type: 'rating', // required
                label: $scope.renderHtml('<b>Nutrition</b> (Rate from 1 bad to 10 good)'),  // required
                description: '', // optional
                name: 'nutrition', // required | must be unique
                value: '', // default value | optional
                icon: 'fa fa-star-o fa-4x', // required
                iconFill: 'fa fa-star fa-4x', // required
                itemCount: 10, // required | as much star you want
                isRequired: true, // Boolean | required
            },
            {
                // input type rating  | 3
                type: 'rating', // required
                label: $scope.renderHtml('<b>Hydration</b> (Rate from 1 bad to 10 good)'),  // required
                description: '', // optional
                name: 'hydration', // required | must be unique
                value: '', // default value | optional
                icon: 'fa fa-star-o fa-4x', // required
                iconFill: 'fa fa-star fa-4x', // required
                itemCount: 10, // required | as much star you want
                isRequired: true, // Boolean | required
            },
            {
                // input type rating  | 4
                type: 'rating', // required
                label: $scope.renderHtml('<b>Humidity</b> (Rate from 1 low to 10 high)'),  // required
                description: '', // optional
                name: 'humidity', // required | must be unique
                value: '', // default value | optional
                icon: 'fa fa-star-o fa-4x', // required
                iconFill: 'fa fa-star fa-4x', // required
                itemCount: 10, // required | as much star you want
                isRequired: true, // Boolean | required
            }
        ]
    }

    // changes required for new step
    window.bmDataTwo = $scope.data;
    /* end: DATA **/
}]);




// controller benchmark #3
vpFormBM.controller('BMWidgetThree', ['$scope', '$sce', function($scope, $sce) {

    // changes required for new step
    var container = $('.container-bm-step-3');
    // changes required for new step
    $scope.controllerStep = 3;

    $scope.activeIndex = 0;
    $scope.activeSubIndex = null;
    $scope.indexType = "number";

    $scope.percentCompleted = 0;

    $scope.offsetTop = 150;

    /* start: Functions **/
    $scope.pressEnter = function (event) {
        if($scope.controllerStep === parseInt($('#m-selected-step').val())) {
            if(event.which === 13 && !event.shiftKey) {
                event.preventDefault();
                $scope.jumpToNextInput();
            }
        }
    }

    $scope.jumpToNextInput = function () {
        var cIndex = $scope.activeIndex,
            nIndex = cIndex + 1;

        var cElem = container.find('.vp-item').eq(cIndex),
            isValid = cElem.attr('data-valid');

        if(isValid === "false") {
            return false;
        }

        var nElem = container.find('.vp-item').eq(nIndex);
        if(nElem.length < 1) {
            // validate form
            $scope.validateWidgetInputs();
            // submit the form
            return false
        }

        $scope.scrollToItem(nIndex);
    }

    // jump to prev input
    $scope.jumpToPrevInput = function () {
        var pIndex = parseInt($scope.activeIndex) - 1;

        $scope.scrollToItem(pIndex);
    }

    $scope.validateWidgetInputs = function() {
        var invalidInputs = container.find('.vp-item[data-valid="false"]');

        if(invalidInputs.length > 0) {
            var invalidInput = invalidInputs.eq(0),
                invalidIndex = parseInt($(invalidInput).attr('data-index'));

            $scope.scrollToItem(invalidIndex);

            return false
        }
    }

    $scope.scrollToItem = function (index) {
        var elem = container.find(".vp-item").eq(index)

        if(elem) {
            jQuery('html, body').stop().animate({
                scrollTop: jQuery(elem).offset().top - $scope.offsetTop
            }, 'slow');
        }
    }

    $scope.setRatingValue = function(ratingIndex, value, digestRequired) {
        var isDigestRequired = false;
        if(digestRequired !== 'undefined' && digestRequired !== undefined) {
            isDigestRequired = digestRequired;
        }

        var item = $scope.data.rating[parseInt(ratingIndex)];

        item.value = parseInt(value);

        if(isDigestRequired) {
            $scope.$digest();
        }

        setTimeout(function() {
            $scope.jumpToNextInput();
        }, 400);
    }

    $scope.setRadioValue = function (radioIndex, optionIndex, digestRequired) {
        var isDigestRequired = false;
        if(digestRequired != 'undefined') {
            isDigestRequired = digestRequired;
        }

        var cRadio = $scope.data.radio[radioIndex],
            cOption = cRadio.options[optionIndex];

        cRadio.activeOption = optionIndex;
        cRadio.value = cOption.value;

        if(cOption.customValueEnabled) {
            // set data receive mode yes
            cOption.isDataReceiving = "yes";

            // set default custom value
            cOption.customValue = cOption.value;
        } else {
            // jump to next input
            $scope.jumpToNextInput();
        }
        if(isDigestRequired) {
            $scope.$digest();
        }
    }

    $scope.updateRadioOptionValue = function ($event, gGadioIndex, gOptionIndex) {
        $event.stopPropagation();

        var cIndex = $scope.activeIndex,
            radioIndex = gGadioIndex,
            optionIndex = gOptionIndex;

        // var itemIndex = parseInt($scope.vpFormBM.activeItem),
        //     optionIndex = parseInt(optionIndex);

        var item = $scope.data.radio[radioIndex],
            option = item.options[optionIndex];

        if(option.customValue) {
            // set item value
            item.value = option.customValue;
            // update option value
            option.value = option.customValue;
            option.label = option.customValue;
            // set date receive mode no
            option.isDataReceiving = "no";

            $scope.jumpToNextInput();
        }
    }


    // calculate form percent completed
    $scope.calculatePercentCompleted = function() {
        var totalItem = container.find('li.vp-item').length,
            currentItem = $scope.activeIndex;

        if(currentItem === totalItem - 1) {
            $scope.percentCompleted = 100;
        } else {
            $scope.percentCompleted = Math.ceil((100 * currentItem) / totalItem);
        }
        $scope.$digest();
    }


    $scope.isControllerActive = function() {
        return $scope.controllerStep === parseInt($('#m-selected-step').val());
    }

    // take action on active item based on keypress
    $(document).bind('keypress', function(event) {

        if($scope.isControllerActive()) {

            var cIndex = $scope.activeIndex,
                cSubIndex = $scope.activeSubIndex,
                indexType = $scope.indexType;


            if(indexType === 'rating' || indexType === 'rating-number') {
                var key = parseInt(event.key);

                var item = $scope.data.rating[cSubIndex];
                if(key > 0 && key <= item.itemCount ) {
                    $scope.setRatingValue(cSubIndex, key, true);
                }
            }

            if(indexType === 'radio' || indexType === 'checkbox') {
                // get the active item
                var item = undefined;
                if(indexType === 'radio') {
                    item = $scope.data.radio[cSubIndex];
                } else if(indexType === 'checkbox') {
                    item = $scope.data.checkbox[cSubIndex];
                }

                var isKeyTracking = true;

                for(var j = 0; j < item.options.length; j++) {
                    var tOption = item.options[j];
                    if(tOption.isDataReceiving === "yes") {
                        isKeyTracking = false;
                        break;
                    }
                }

                if( isKeyTracking ) {

                    if(item.keySelectionEnabled) {
                        var key = event.key;
                        key = key.toLowerCase();

                        var optionIndex = -1;

                        for(var i = 0; i < item.options.length; i++) {
                            var tmpOption = item.options[i];

                            var tmpKey = tmpOption.key;
                            if(! tmpKey) {
                                continue;
                            }

                            tmpKey = tmpKey.toLowerCase();

                            if(tmpKey === key) {
                                optionIndex = i;
                                break;
                            }
                        } // end for


                        if(indexType === 'radio' && optionIndex > -1) {
                            $scope.setRadioValue(cSubIndex, optionIndex, true);

                            console.log(optionIndex, cIndex, cSubIndex, indexType);
                        }
                        // else if (item.type === 'checkbox' || item.type === 'checkbox-vertical') {
                        //     $scope.vpFormBM.setCheckboxValue(optionIndex);
                        // }
                    }
                } // end if - is key tracking
            }
        }
    });

    $scope.setActiveIndexAndType = function (index, subIndex, itype) {
        $scope.activeIndex = parseInt(index);
        $scope.activeSubIndex = parseInt(subIndex);
        $scope.indexType = itype;

        $scope.calculatePercentCompleted();
    }

    $scope.windowAdventure = function(viewport, item, callback) {
        var wa = {};
        wa.waViewPort = viewport;
        wa.item = item;
        wa.callback = callback;
        wa.ci = 0;
        wa.cItem = null;

        if($scope.isControllerActive()) {
            $(window).bind('scroll', function () {
                clearTimeout($.data(this, 'scrollTimer'));

                $.data(this, 'scrollTimer', setTimeout(function () {
                    var viewPort = $(wa.waViewPort),
                        items = viewPort.find(wa.item);

                    var itemsInWindow = wa._itemsInWindow(items),
                        currentIndex = itemsInWindow[0];

                    if (currentIndex !== wa.ci && currentIndex > -1) {

                        // return with viewport and current item
                        wa.cItem = $(items.eq(currentIndex));
                        wa.ci = currentIndex;

                        return wa.callback(wa.waViewPort, wa.cItem);
                    }

                }, 30));

            });
        }


        wa._itemsInWindow = function(items) {
            var vpTop = $(window).scrollTop(),
                wHeight = $(window).height()
            vpBottom =  vpTop + wHeight;

            var vpItems = [];

            for(var i = 0; i < items.length; i++) {
                var item = $(items.eq(i));

                var itemTop = item.offset().top,
                    itemBottom = itemTop + item.outerHeight();

                if(itemBottom > vpTop + (wHeight / 2.5) && itemTop < vpBottom) {
                    vpItems.push(i);
                }
            }

            return vpItems;
        }
    }


    // changes required for new step
    $scope.activateWindowAdventure = function() {
        $scope.windowAdventure('#viewport-3', '.vp-item', function (viewport, currentItem) {
            var activeIndex = parseInt(jQuery(currentItem).attr('data-index')),
                activeSubIndex = parseInt(jQuery(currentItem).attr('data-sub-index')),
                iType = jQuery(currentItem).attr('data-type');



            $(viewport).find('.vp-item').removeClass('vp-form-active');

            // add class active to current item
            $(currentItem).addClass('vp-form-active')
                .find('.input-header, .input-body').slideDown(400);

            $scope.setActiveIndexAndType(activeIndex, activeSubIndex, iType);

            // focus current input
            jQuery(currentItem).find('input, textarea, select').focus();

            // trigger change
            $(viewport).find('input, textarea, select').trigger('change');

            if(iType === "radio") {
                $(document).find('input, textarea, select').blur();
            }

            $scope.scrollToItem($scope.activeIndex);
        });
    }

    $('#m-selected-step').on('change', function() {
        if($scope.isControllerActive()) {
            setTimeout(function() {

                $(window).scrollTop(0);
            }, 200);

            setTimeout(function() {
                $(container).find('.vp-item').removeClass('vp-form-active');

                // add class active to current item
                var currentItem = $(container).find('.vp-item').eq('0');

                $(currentItem).addClass('vp-form-active')
                    .find('.input-header, .input-body').slideDown(400);

                var activeIndex = parseInt(jQuery(currentItem).attr('data-index')),
                    activeSubIndex = parseInt(jQuery(currentItem).attr('data-sub-index')),
                    iType = jQuery(currentItem).attr('data-type');

                $scope.setActiveIndexAndType(activeIndex, activeSubIndex, iType);

                // focus current input
                jQuery(currentItem).find('input, textarea, select').focus();

                if(iType === "radio") {
                    $(document).find('input, textarea, select').blur();
                }
            }, 300)

            $scope.activateWindowAdventure();
        }
    });


    // changes required for new step
    window.getBMS3ActiveIndex = function () {
        return $scope.activeIndex;
    }

    // changes required for new step
    window.digestBMS3 = function () {
        $scope.$digest();
    }
    /* end: Functions **/

    // render html
    $scope.renderHtml = function (htmlCode) {
        return $sce.trustAsHtml(htmlCode);
    };

    // get number range
    $scope.range = function(min, max, step) {
        // parameters validation for method overloading
        if (max == undefined) {
            max = min;
            min = 0;
        }
        step = Math.abs(step) || 1;
        if (min > max) {
            step = -step;
        }
        // building the array
        var output = [];
        for (var value=min; value<max; value+=step) {
            output.push(value);
        }
        // returning the generated array
        return output;
    };



    /* start: DATA **/
    $scope.data = {

    }

    // changes required for new step
    window.bmDataThree = $scope.data;
    /* end: DATA **/
}]);




// controller benchmark #4
vpFormBM.controller('BMWidgetFour', ['$scope', '$sce', function($scope, $sce) {

    // changes required for new step
    var container = $('.container-bm-step-4');
    // changes required for new step
    $scope.controllerStep = 4;

    $scope.activeIndex = 0;
    $scope.activeSubIndex = null;
    $scope.indexType = "text";

    $scope.percentCompleted = 0;

    $scope.offsetTop = 150;

    /* start: Functions **/
    $scope.pressEnter = function (event) {
        if($scope.controllerStep === parseInt($('#m-selected-step').val())) {
            if(event.which === 13 && !event.shiftKey) {
                event.preventDefault();
                $scope.jumpToNextInput();
            }
        }
    }

    $scope.jumpToNextInput = function () {
        var cIndex = $scope.activeIndex,
            nIndex = cIndex + 1;

        var cElem = container.find('.vp-item').eq(cIndex),
            isValid = cElem.attr('data-valid');

        if(isValid === "false") {
            return false;
        }

        var nElem = container.find('.vp-item').eq(nIndex);
        if(nElem.length < 1) {
            // validate form
            $scope.validateWidgetInputs();
            // submit the form
            return false
        }

        $scope.scrollToItem(nIndex);
    }

    // jump to prev input
    $scope.jumpToPrevInput = function () {
        var pIndex = parseInt($scope.activeIndex) - 1;

        $scope.scrollToItem(pIndex);
    }

    $scope.validateWidgetInputs = function() {
        var invalidInputs = container.find('.vp-item[data-valid="false"]');

        if(invalidInputs.length > 0) {
            var invalidInput = invalidInputs.eq(0),
                invalidIndex = parseInt($(invalidInput).attr('data-index'));

            $scope.scrollToItem(invalidIndex);

            return false
        }
    }

    $scope.scrollToItem = function (index) {
        var elem = container.find(".vp-item").eq(index)

        if(elem) {
            jQuery('html, body').stop().animate({
                scrollTop: jQuery(elem).offset().top - $scope.offsetTop
            }, 'slow');
        }
    }

    $scope.setRatingValue = function(ratingIndex, value, digestRequired) {
        var isDigestRequired = false;
        if(digestRequired !== 'undefined' && digestRequired !== undefined) {
            isDigestRequired = digestRequired;
        }

        var item = $scope.data.rating[parseInt(ratingIndex)];

        item.value = parseInt(value);

        if(isDigestRequired) {
            $scope.$digest();
        }

        setTimeout(function() {
            $scope.jumpToNextInput();
        }, 400);
    }

    $scope.setRadioValue = function (radioIndex, optionIndex, digestRequired) {
        var isDigestRequired = false;
        if(digestRequired != 'undefined') {
            isDigestRequired = digestRequired;
        }

        var cRadio = $scope.data.radio[radioIndex],
            cOption = cRadio.options[optionIndex];

        cRadio.activeOption = optionIndex;
        cRadio.value = cOption.value;

        if(cOption.customValueEnabled) {
            // set data receive mode yes
            cOption.isDataReceiving = "yes";

            // set default custom value
            cOption.customValue = cOption.value;
        } else {
            // jump to next input
            $scope.jumpToNextInput();
        }
        if(isDigestRequired) {
            $scope.$digest();
        }
    }

    $scope.updateRadioOptionValue = function ($event, gGadioIndex, gOptionIndex) {
        $event.stopPropagation();

        var cIndex = $scope.activeIndex,
            radioIndex = gGadioIndex,
            optionIndex = gOptionIndex;

        // var itemIndex = parseInt($scope.vpFormBM.activeItem),
        //     optionIndex = parseInt(optionIndex);

        var item = $scope.data.radio[radioIndex],
            option = item.options[optionIndex];

        if(option.customValue) {
            // set item value
            item.value = option.customValue;
            // update option value
            option.value = option.customValue;
            option.label = option.customValue;
            // set date receive mode no
            option.isDataReceiving = "no";

            $scope.jumpToNextInput();
        }
    }


    // calculate form percent completed
    $scope.calculatePercentCompleted = function() {
        var totalItem = container.find('li.vp-item').length,
            currentItem = $scope.activeIndex;

        if(currentItem === totalItem - 1) {
            $scope.percentCompleted = 100;
        } else {
            $scope.percentCompleted = Math.ceil((100 * currentItem) / totalItem);
        }
        $scope.$digest();
    }


    $scope.isControllerActive = function() {
        return $scope.controllerStep === parseInt($('#m-selected-step').val());
    }

    // take action on active item based on keypress
    $(document).bind('keypress', function(event) {

        if($scope.isControllerActive()) {

            var cIndex = $scope.activeIndex,
                cSubIndex = $scope.activeSubIndex,
                indexType = $scope.indexType;


            if(indexType === 'rating' || indexType === 'rating-number') {
                var key = parseInt(event.key);

                var item = $scope.data.rating[cSubIndex];
                if(key > 0 && key <= item.itemCount ) {
                    $scope.setRatingValue(cSubIndex, key, true);
                }
            }

            if(indexType === 'radio' || indexType === 'checkbox') {
                // get the active item
                var item = undefined;
                if(indexType === 'radio') {
                    item = $scope.data.radio[cSubIndex];
                } else if(indexType === 'checkbox') {
                    item = $scope.data.checkbox[cSubIndex];
                }

                var isKeyTracking = true;

                for(var j = 0; j < item.options.length; j++) {
                    var tOption = item.options[j];
                    if(tOption.isDataReceiving === "yes") {
                        isKeyTracking = false;
                        break;
                    }
                }

                if( isKeyTracking ) {

                    if(item.keySelectionEnabled) {
                        var key = event.key;
                        key = key.toLowerCase();

                        var optionIndex = -1;

                        for(var i = 0; i < item.options.length; i++) {
                            var tmpOption = item.options[i];

                            var tmpKey = tmpOption.key;
                            if(! tmpKey) {
                                continue;
                            }

                            tmpKey = tmpKey.toLowerCase();

                            if(tmpKey === key) {
                                optionIndex = i;
                                break;
                            }
                        } // end for


                        if(indexType === 'radio' && optionIndex > -1) {
                            $scope.setRadioValue(cSubIndex, optionIndex, true);

                            console.log(optionIndex, cIndex, cSubIndex, indexType);
                        }
                        // else if (item.type === 'checkbox' || item.type === 'checkbox-vertical') {
                        //     $scope.vpFormBM.setCheckboxValue(optionIndex);
                        // }
                    }
                } // end if - is key tracking
            }
        }
    });

    $scope.setActiveIndexAndType = function (index, subIndex, itype) {
        $scope.activeIndex = parseInt(index);
        $scope.activeSubIndex = parseInt(subIndex);
        $scope.indexType = itype;

        $scope.calculatePercentCompleted();
    }

    $scope.windowAdventure = function(viewport, item, callback) {
        var wa = {};
        wa.waViewPort = viewport;
        wa.item = item;
        wa.callback = callback;
        wa.ci = 0;
        wa.cItem = null;

        if($scope.isControllerActive()) {
            $(window).bind('scroll', function () {
                clearTimeout($.data(this, 'scrollTimer'));

                $.data(this, 'scrollTimer', setTimeout(function () {
                    var viewPort = $(wa.waViewPort),
                        items = viewPort.find(wa.item);

                    var itemsInWindow = wa._itemsInWindow(items),
                        currentIndex = itemsInWindow[0];

                    if (currentIndex !== wa.ci && currentIndex > -1) {

                        // return with viewport and current item
                        wa.cItem = $(items.eq(currentIndex));
                        wa.ci = currentIndex;

                        return wa.callback(wa.waViewPort, wa.cItem);
                    }

                }, 30));

            });
        }


        wa._itemsInWindow = function(items) {
            var vpTop = $(window).scrollTop(),
                wHeight = $(window).height()
            vpBottom =  vpTop + wHeight;

            var vpItems = [];

            for(var i = 0; i < items.length; i++) {
                var item = $(items.eq(i));

                var itemTop = item.offset().top,
                    itemBottom = itemTop + item.outerHeight();

                if(itemBottom > vpTop + (wHeight / 2.5) && itemTop < vpBottom) {
                    vpItems.push(i);
                }
            }

            return vpItems;
        }
    }


    // changes required for new step
    $scope.activateWindowAdventure = function() {
        $scope.windowAdventure('#viewport-4', '.vp-item', function (viewport, currentItem) {
            var activeIndex = parseInt(jQuery(currentItem).attr('data-index')),
                activeSubIndex = parseInt(jQuery(currentItem).attr('data-sub-index')),
                iType = jQuery(currentItem).attr('data-type');



            $(viewport).find('.vp-item').removeClass('vp-form-active');

            // add class active to current item
            $(currentItem).addClass('vp-form-active')
                .find('.input-header, .input-body').slideDown(400);

            $scope.setActiveIndexAndType(activeIndex, activeSubIndex, iType);

            // focus current input
            jQuery(currentItem).find('input, textarea, select').focus();

            // trigger change
            $(viewport).find('input, textarea, select').trigger('change');

            if(iType === "radio") {
                $(document).find('input, textarea, select').blur();
            }

            $scope.scrollToItem($scope.activeIndex);
        });
    }

    $('#m-selected-step').on('change', function() {
        if($scope.isControllerActive()) {
            setTimeout(function() {

                $(window).scrollTop(0);
            }, 200);

            setTimeout(function() {
                $(container).find('.vp-item').removeClass('vp-form-active');

                // add class active to current item
                var currentItem = $(container).find('.vp-item').eq('0');

                $(currentItem).addClass('vp-form-active')
                    .find('.input-header, .input-body').slideDown(400);

                var activeIndex = parseInt(jQuery(currentItem).attr('data-index')),
                    activeSubIndex = parseInt(jQuery(currentItem).attr('data-sub-index')),
                    iType = jQuery(currentItem).attr('data-type');

                $scope.setActiveIndexAndType(activeIndex, activeSubIndex, iType);

                // focus current input
                jQuery(currentItem).find('input, textarea, select').focus();

                if(iType === "radio") {
                    $(document).find('input, textarea, select').blur();
                }
            }, 300)

            $scope.activateWindowAdventure();
        }
    });


    // changes required for new step
    window.getBMS4ActiveIndex = function () {
        return $scope.activeIndex;
    }

    // changes required for new step
    window.digestBMS4 = function () {
        $scope.$digest();
    }
    /* end: Functions **/

    // render html
    $scope.renderHtml = function (htmlCode) {
        return $sce.trustAsHtml(htmlCode);
    };

    // get number range
    $scope.range = function(min, max, step) {
        // parameters validation for method overloading
        if (max == undefined) {
            max = min;
            min = 0;
        }
        step = Math.abs(step) || 1;
        if (min > max) {
            step = -step;
        }
        // building the array
        var output = [];
        for (var value=min; value<max; value+=step) {
            output.push(value);
        }
        // returning the generated array
        return output;
    };



    /* start: DATA **/
    $scope.data = {

    }

    // changes required for new step
    window.bmDataFour = $scope.data;
    /* end: DATA **/
}]);




vpFormBM.directive('contenteditable', ['$sce', function($sce) {
    return {
        restrict: 'A', // only activate on element attribute
        require: '?ngModel', // get a hold of NgModelController
        link: function(scope, element, attrs, ngModel) {
            if (!ngModel) return;

            ngModel.$render = function() {
                element.html(ngModel.$viewValue || '');
                read(); // initialize
            };

            // Listen for change events to enable binding
            element.on('blur keyup change', function() {
                scope.$evalAsync(read);
            });

            // Write data to the model
            function read() {
                var html = element.html();
                // When we clear the content editable the browser leaves a <br> behind
                // If strip-br attribute is provided then we strip this out
                if ( attrs.stripBr && html == '<br>' ) {
                    html = '';
                }
                ngModel.$setViewValue(html);
            }
        }
    };
}]);


$(document).ready(function() {
    $(document).trigger('click');

    $('input, textarea').focus(function(){
        var that = this;
        setTimeout(function(){ that.selectionStart = that.selectionEnd = 1000; }, 0);
    });

    $('.task-edit .fa-pencil, .habit-edit .fa-pencil').click(function(){
        $('#m-selected-step').trigger('change');
    })
})