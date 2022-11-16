/** start: Angular.JS **/
var vpFormPQ = angular.module('vp-form-pq', ["angularjs-autogrow"]);

vpFormPQ.controller('PQController', ['$rootScope', '$scope', function ($rootScope, $scope) {
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

// controller goal buddy #1
vpFormPQ.controller('PQWidgetOne', ['$scope', '$rootScope', '$sce', function($scope, $rootScope, $sce) {

    // changes required for new step
    var container = $('.container-pq-step-1');
    // changes required for new step
    $scope.controllerStep = 1;


    $scope.activeIndex = 0;
    $scope.activeSubIndex = 0;
    $scope.indexType = 'radio';

    $scope.percentCompleted = 0;

    $scope.isKeyDitectionEnabled = true;

    $scope.disableKeyDetection = function () {
        $scope.isKeyDitectionEnabled = false;
    }

    $scope.enableKeyDetection = function () {
        $scope.isKeyDitectionEnabled = true;
    }

    //$scope.offsetTop = 150;

    if (screen.width < 768) {
        $scope.offsetTop = 70;
    } else {
        $scope.offsetTop = 150;
    }
    $( window ).resize(function() {
        if (screen.width < 768) {
            $scope.offsetTop = 70;
        } else {
            $scope.offsetTop = 150;
        }
    });

    window.stepOneScrollToItem = function(index) {
        $scope.scrollToItem(index);
    }


    /* start: Functions **/
    $scope.pressEnter = function (event) {
        if($scope.controllerStep === parseInt($('#m-selected-step').val()) && $rootScope.isFormStarted) {
            if(event.which === 13 && !event.shiftKey) {
                event.preventDefault();

                $scope.jumpToNextInput();
            }
        }
    }

    $scope.jumpToNextInput = function () {
        var cIndex = $scope.activeIndex,
            nIndex = cIndex + 1;

            value = $('#ecRelation').val();
            referralValue =$('#referrer').val();
            var relationHtml = $('.showNotes .form-group');
            
            if(cIndex == 11 && value == '' && relationHtml.length == '0' && referralValue != 'referral'){
                 $('#ecrelationModal').show();
            }else if(cIndex == 12 && value == '' && relationHtml.length == '0' && referralValue == 'referral'){
                 $('#ecrelationModal').show();
            }else{
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
        var elem = container.find(".vp-item").eq(index);
        $parentDiv = container.closest('.gray_box');
        if(elem) {
            // $parentDiv.scrollTop($parentDiv.scrollTop() - $parentDiv.offset().top + jQuery(elem).offset().top,'slow');
            $parentDiv.stop().animate({
                scrollTop: $parentDiv.scrollTop() - $parentDiv.offset().top + jQuery(elem).offset().top
            }, 'slow');
        }
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
            if((radioIndex === 0 && (optionIndex === 0 || optionIndex === 1 || optionIndex === 3)) || radioIndex === 1) {

            } else {
                // jump to next input
                // setTimeout(function () {
                //     $scope.jumpToNextInput();
                // }, 100);
            }

        }

        if(isDigestRequired) {
            if(!$scope.$$phase) {
                $scope.$digest();
            }
        }
    }

    $scope.updateRadioOptionValue = function ($event, gGadioIndex, gOptionIndex) {
        $event.stopPropagation();

        var cIndex = $scope.activeIndex,
            radioIndex = gGadioIndex,
            optionIndex = gOptionIndex;

        // var itemIndex = parseInt($scope.vpFormPQ.activeItem),
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
        if(!$scope.$$phase) {
            $scope.$digest();
        }
    }


    $scope.isControllerActive = function() {
        return $scope.controllerStep === parseInt($('#m-selected-step').val());
    }

    // take action on active item based on keypress
    $(document).bind('keypress', function(event) {

        if($scope.isKeyDitectionEnabled  && $rootScope.isFormStarted) {

            if($scope.isControllerActive()) {

                var cIndex = $scope.activeIndex,
                    cSubIndex = $scope.activeSubIndex,
                    indexType = $scope.indexType;


                if(indexType === 'rating' || indexType === 'rating-number') {
                    var key = parseInt(event.key);

                    // if(key > 0 && key <= item.itemCount ) {
                    //     $scope.vpFormPQ.setRatingValue(key);
                    // }
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

                                
                            }
                            // else if (item.type === 'checkbox' || item.type === 'checkbox-vertical') {
                            //     $scope.vpFormPQ.setCheckboxValue(optionIndex);
                            // }
                        }
                    } // end if - is key tracking
                }
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
            $('.gray_box').bind('scroll', function () {
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
        // changes required for new step
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
            // jQuery(currentItem).find('input, textarea, select').focus();

            if(iType === "radio") {
                $(document).find('input, textarea, select').blur();
            }

            //$scope.scrollToItem($scope.activeIndex);
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
                // jQuery(currentItem).find('input, textarea, select').focus();

                if(iType === "radio") {
                    $(document).find('input, textarea, select').blur();
                }
            }, 300)

            $scope.activateWindowAdventure();
        }
    });


    // changes required for new step
    window.getPqS1ActiveIndex = function () {
        return $scope.activeIndex;
    }

    // changes required for new step
    window.digestPqs1 = function () {
        if(!$scope.$$phase) {
            $scope.$digest();
        }
    }
    /* end: Functions **/



    /* start: DATA **/
    $scope.data = {
        radio: [
            {
                // option gaol model - index = 0
                activeOption: -1,
                value: "",
                keySelectionEnabled: true,
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: 'Online & Social Media', // required
                        value: 'onlinesocial' // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'B',  // optional
                        label: 'Media & Promotions',  // required
                        value: 'mediapromotions'  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'C',  // optional
                        label: 'Referral',  // required
                        value: 'referral'  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'D',  // optional
                        label: 'Other',  // required
                        value: 'socialmedia'  // required
                    }
                ]
            },
            {
                // option another - index = 1
                activeOption: -1,
                value: "",
                keySelectionEnabled: true,
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: 'Client', // required
                        value: 'Client' // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'B',  // optional
                        label: 'Staff',  // required
                        value: 'Staff'  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'C',  // optional
                        label: 'Professional network',  // required
                        value: 'Professional network'  // required
                    }
                ]
            },
            {
                // option another - index = 2
                activeOption: -1,
                value: "Male",
                keySelectionEnabled: true,
                options: [
                    {
                        icon: 'fa fa-male', // required
                        key: 'A', // optional
                        label: 'Male', // required
                        value: 'Male' // required
                    },
                    {
                        icon: 'fa fa-female',  // required
                        key: 'B',  // optional
                        label: 'Female',  // required
                        value: 'Female'  // required
                    }
                ]
            }
        ]
    }

    // changes required for new step
    window.pqs1Data = $scope.data;
    /* end: DATA **/
}]);




// controller parq #2
vpFormPQ.controller('PQWidgetTwo', ['$rootScope', '$scope', '$sce', function($rootScope, $scope, $sce) {

    // changes required for new step
    var container = $('.container-pq-step-2');
    // changes required for new step
    $scope.controllerStep = 2;
    $scope.activeIndex = 0;
    $scope.activeSubIndex = 0;
    $scope.indexType = 'radio';
    $scope.percentCompleted = 0;
    //$scope.offsetTop = 150;
    if (screen.width < 768) {
        $scope.offsetTop = 70;
    } else {
        $scope.offsetTop = 150;
    }
    $( window ).resize(function() {
        if (screen.width < 768) {
            $scope.offsetTop = 70;
        } else {
            $scope.offsetTop = 150;
        }
    });


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
        var elem = container.find(".vp-item").eq(index);
        $parentDiv = container.closest('.gray_box');
        if(elem) {
            // jQuery('html, body').stop().animate({
            //     scrollTop: jQuery(elem).offset().top - $scope.offsetTop
            // }, 'slow');
            $parentDiv.stop().animate({
                scrollTop: $parentDiv.scrollTop() - $parentDiv.offset().top + jQuery(elem).offset().top
            }, 'slow');
        }
    }

    /** start: RADIO OPTIONS **/
    $scope.setRadioValue = function (radioIndex, optionIndex, digestRequired) {
        var isDigestRequired = false;
        if(digestRequired != 'undefined') {
            isDigestRequired = digestRequired;
        }

        var cRadio = $scope.data.radio[radioIndex],
            cOption = cRadio.options[optionIndex];

        cRadio.activeOption = optionIndex;
        cRadio.value = cOption.value;

        setTimeout(function() {
            container.find('input[value="'+cRadio.value+'"]').trigger('change');
        }, 100);

        if(cOption.customValueEnabled) {
            // set data receive mode yes
            cOption.isDataReceiving = "yes";

            // set default custom value
            cOption.customValue = cOption.value;
        } else {
            // setTimeout(function() {
            //     // jump to next input
            //     $scope.jumpToNextInput();
            // }, 200);
        }
        if(isDigestRequired) {
            if(!$scope.$$phase) {
                $scope.$digest();
            }
        }


        // if(!$scope.$$phase) {
        //     $scope.$digest();
        // }

        // $rootScope.$broadcast('stop');
        // $scope.$broadcast('stop');
        // $scope.$apply();
        // $rootScope.$digest();
        // $scope.$broadcast('resume');
        // $rootScope.$broadcast('resume');
    }

    $scope.updateRadioOptionValue = function ($event, gGadioIndex, gOptionIndex) {
        $event.stopPropagation();

        var cIndex = $scope.activeIndex,
            radioIndex = gGadioIndex,
            optionIndex = gOptionIndex;

        // var itemIndex = parseInt($scope.vpFormPQ.activeItem),
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
    /** end: RADIO OPTIONS **/

    $scope.setCheckboxValueAll = function ($subIndex) {


        var itemIndex = parseInt($subIndex);
        var item = $scope.data.checkbox[itemIndex];


        item.isAllSelected = !item.isAllSelected;

        if(item.isAllSelected) {
            for(var i = 0; i < item.options.length; i++) {
                var option = item.options[i];

                item.activeOptions[item.activeOptions.length] = option.value;

                // item.value[item.value.length] = option.value;
            }

            var itemValue = "";
            if(item.activeOptions.length > 0) {
                for(var i = 0; i < item.activeOptions.length; i++) {
                    if(i !== item.activeOptions.length - 1) {
                        itemValue += item.activeOptions[i] + ',';
                    } else {
                        itemValue += item.activeOptions[i];
                    }
                }
            }
            item.value = itemValue;
        } else {
            item.activeOptions = [];
            item.value = "";
        }

    }

    /** start: CHECKBOX **/
    $scope.setCheckboxValue = function (checkboxIndex, optionIndex, digestRequired) {
        var isDigestRequired = false;
        if(digestRequired != 'undefined') {
            isDigestRequired = digestRequired;
        }

        var itemIndex = parseInt(checkboxIndex),
            optionIndex = parseInt(optionIndex);

        var item = $scope.data.checkbox[itemIndex],
            option = item.options[optionIndex];

        // add | remove to active options' array
        var valueIndexInActiveOptions = item.activeOptions.indexOf(option.value);
        if( valueIndexInActiveOptions > -1) {
            // value exist, so remove it
            item.activeOptions.splice(valueIndexInActiveOptions, 1);
        } else {
            // value not exist, so add it
            item.activeOptions[item.activeOptions.length] = option.value;
        }


        // update value in value array
        var itemValue = "";
        if(item.activeOptions.length > 0) {
            for(var i = 0; i < item.activeOptions.length; i++) {
                if(i !== item.activeOptions.length - 1) {
                    itemValue += item.activeOptions[i] + ',';
                } else {
                    itemValue += item.activeOptions[i];
                }
            }
        }
        item.value = itemValue;

        if(item.name === 'paIntensity') {
            $scope.paIntensity = item.value;
        }
        if(item.name === 'intensity') {
            $scope.intensity = item.value;
        }
       if(item.name ==='paPerWeek')
        {
            $scope.paPerWeek = item.value;
        }
        if(item.name ==='paSession')
        {
            $scope.paPerWeek = item.value;
        }




        // add | remove to value array
        // var valueIndexInValueArray = item.value.indexOf(option.value);
        //
        // if(valueIndexInValueArray > -1) {
        //     // value exist, so remove it
        //     item.value.splice(valueIndexInValueArray, 1);
        // } else {
        //     // value not exist, so add it
        //     item.value[item.value.length] = option.value;
        // }


        setTimeout(function() {
            // container.find('li[data-index="4"]').find('input').val(item.value).trigger('change');
        }, 100);


        if(option.customValueEnabled) {
            // set data receive mode yes
            option.isDataReceiving = "yes";

            // set default custom value
            option.customValue = option.value;
        }

        if(digestRequired) {
            $scope.$digest();
        }
    }

    $scope.updateCheckboxOptionValue = function($event, checkboxIndex, optionIndex, digestRequired) {
        $event.stopPropagation();

        var isDigestRequired = false;
        if(digestRequired != 'undefined') {
            isDigestRequired = digestRequired;
        }

        var itemIndex = parseInt(checkboxIndex),
            optionIndex = parseInt(optionIndex);

        var item = $scope.data.checkbox[itemIndex],
            option = item.options[optionIndex];

        if(option.customValue) {
            // update value in active options
            var valueIndexInActiveOptions = item.activeOptions.indexOf(option.value);
            item.activeOptions[valueIndexInActiveOptions] = option.customValue;

            // update value in value array
            // var valueIndexInValueArray = item.value.indexOf(option.value);
            // item.value[valueIndexInValueArray] = option.customValue;

            var itemValue = "";
            if(item.activeOptions.length > 0) {
                for(var i = 0; i < item.activeOptions.length; i++) {
                    if(i !== item.activeOptions.length - 1) {
                        itemValue += item.activeOptions[i] + ',';
                    } else {
                        itemValue += item.activeOptions[i];
                    }
                }
            }
            item.value = itemValue;

            if(item.name === 'paIntensity') {
                $scope.paIntensity = item.value;
            }
            if(item.name === 'intensity') {
                $scope.intensity = item.value;
            }
             if(item.name === 'paPerWeek') {
                $scope.paPerWeek = item.value;
            }
           if(item.name === 'paSession') {
                $scope.paSession = item.value;
            }

            // change option value and label
            option.value = option.customValue;
            option.label = option.customValue;

            // set data receive mode no
            option.isDataReceiving = "no";
        }

        if(isDigestRequired) {
            $scope.$digest();
        }

    }

    $scope.stopPropagation = function($event) {
        $event.stopPropagation();
    }
    /** end: CHECKBOX **/


    // calculate form percent completed
    $scope.calculatePercentCompleted = function() {
        var totalItem = container.find('li.vp-item').length,
            currentItem = $scope.activeIndex;

        if(currentItem === totalItem - 1) {
            $scope.percentCompleted = 100;
        } else {
            $scope.percentCompleted = Math.ceil((100 * currentItem) / totalItem);
        }
        if(!$scope.$$phase) {
            $scope.$digest();
        }
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

                // if(key > 0 && key <= item.itemCount ) {
                //     $scope.vpFormPQ.setRatingValue(key);
                // }
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
                        }
                        else if (indexType === 'checkbox' && optionIndex > -1) {
                            $scope.setCheckboxValue(cSubIndex, optionIndex, true);
                        }
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
            $('.gray_box').bind('scroll', function () {
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
        // changes required for new step
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
            // jQuery(currentItem).find('input, textarea, select').focus();

            if(iType === "radio") {
                $(document).find('input, textarea, select').blur();
            }

           // $scope.scrollToItem($scope.activeIndex);
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
                // jQuery(currentItem).find('input, textarea, select').focus();

                if(iType === "radio") {
                    $(document).find('input, textarea, select').blur();
                }
            }, 300)

            $scope.activateWindowAdventure();
        }
    });



    window.getPqS2CheckBoxOptions = function (checkboxIndex) {
        var options = $scope.data.checkbox[checkboxIndex]['options'];

        var values = [];
        for (var i = 0; i < options.length; i++) {
            values[values.length] = options[i]['value'];
        }

        return values;


    }


    window.setPqS2CheckboxValue = function (checkboxIndex, optionIndex) {
        $scope.setCheckboxValue(checkboxIndex,optionIndex)
    }

    

    // changes required for new step
    window.getPqS2ActiveIndex = function () {
        return $scope.activeIndex;
    }

    // changes required for new step
    window.digestPqs2 = function () {
        if(!$scope.$$phase) {
            $scope.$digest();
        }
    }
    /* end: Functions **/



    /* start: DATA **/
    $scope.data = {
        radio: [
            {
                // option gaol model - index = 0
                activeOption: -1,
                value: "",
                keySelectionEnabled: true,
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: 'None', // required
                        value: 'none' // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'B',  // optional
                        label: 'Cardio',  // required
                        value: 'cardio'  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'C',  // optional
                        label: 'Resistance',  // required
                        value: 'resistance'  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'D',  // optional
                        label: 'Cardio & Resistance',  // required
                        value: 'resistancecardio'  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'E',  // optional
                        label: 'Other',  // required
                        value: 'other'  // required
                    }
                ]
            },
            {
                // option another - index = 1
                activeOption: -1,
                value: "",
                keySelectionEnabled: true,
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: '1', // required
                        value: '1' // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'B',  // optional
                        label: '2',  // required
                        value: '2'  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'C',  // optional
                        label: '3-5',  // required
                        value: '3-5'  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'D',  // optional
                        label: '6-7',  // required
                        value: '6-7'  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'E',  // optional
                        label: 'Twice daily vigorous',  // required
                        value: 'Twice daily vigorous'  // required
                    }
                ]
            },
            {
                // option another - index = 2
                activeOption: -1,
                value: "",
                keySelectionEnabled: true,
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: '30 min', // required
                        value: '30 min' // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'B',  // optional
                        label: '60 min',  // required
                        value: '60 min'  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'C',  // optional
                        label: '90 min',  // required
                        value: '90 min'  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'D',  // optional
                        label: '120-150 min',  // required
                        value: '120-150 min'  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'E',  // optional
                        label: '150 min +',  // required
                        value: '150 min +'  // required
                    }
                ]
            },
            {
                // option another - index = 3
                activeOption: -1,
                value: "",
                keySelectionEnabled: true,
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: '30 min', // required
                        value: '30 min' // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'B',  // optional
                        label: '60 min',  // required
                        value: '60 min'  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'C',  // optional
                        label: '90 min',  // required
                        value: '90 min'  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'D',  // optional
                        label: '120-150 min',  // required
                        value: '120-150 min'  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'E',  // optional
                        label: '150 min +',  // required
                        value: '150 min +'  // required
                    }
                ]
            }
        ],
        checkbox: [
            {
                // input type checkbox | index = 0
                name: 'intensity',
                value: "", // default value | optional
                activeOptions: [], // index of option, if want to keep option unselected set the value to -1
                keySelectionEnabled: true, // Boolean | required to select option by key
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: 'Sedentary', // required
                        value: 'sedentary', // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'B',  // optional
                        label: 'Light',  // required
                        value: 'light',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'C',  // optional
                        label: 'Moderate',  // required
                        value: 'moderate',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'D',  // optional
                        label: 'Vigorous',  // required
                        value: 'vigorous',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'E',  // optional
                        label: 'Twice daily vigorous',  // required
                        value: 'high',  // required
                    }
                ]
            },
            {
                // input type checkbox | index = 1
                name: 'paIntensity',
                value: "", // default value | optional
                activeOptions: [], // index of option, if want to keep option unselected set the value to -1
                keySelectionEnabled: true, // Boolean | required to select option by key
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: 'Sedentary', // required
                        value: 'sedentary', // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'B',  // optional
                        label: 'Light',  // required
                        value: 'light',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'C',  // optional
                        label: 'Moderate',  // required
                        value: 'moderate',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'D',  // optional
                        label: 'Vigorous',  // required
                        value: 'vigorous',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'E',  // optional
                        label: 'Twice daily vigorous',  // required
                        value: 'high',  // required
                    }
                ]
            },
            {
                // input type checkbox | index = 1
                name: 'paPerWeek',
                value: "", // default value | optional
                activeOptions: [], // index of option, if want to keep option unselected set the value to -1
                keySelectionEnabled: true, // Boolean | required to select option by key
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: '30 min', // required
                        value: '30 min' // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'B',  // optional
                        label: '60 min',  // required
                        value: '60 min'  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'C',  // optional
                        label: '90 min',  // required
                        value: '90 min'  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'D',  // optional
                        label: '120-150 min',  // required
                        value: '120-150 min'  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'E',  // optional
                        label: '150 min +',  // required
                        value: '150 min +'  // required
                    }
                ]
            },
            {
                // input type checkbox | index = 1
                name: 'paSession',
                value: "", // default value | optional
                activeOptions: [], // index of option, if want to keep option unselected set the value to -1
                keySelectionEnabled: true, // Boolean | required to select option by key
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: '30 min', // required
                        value: '30 min' // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'B',  // optional
                        label: '60 min',  // required
                        value: '60 min'  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'C',  // optional
                        label: '90 min',  // required
                        value: '90 min'  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'D',  // optional
                        label: '120-150 min',  // required
                        value: '120-150 min'  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'E',  // optional
                        label: '150 min +',  // required
                        value: '150 min +'  // required
                    }
                ]
            }
        ]
    }

    $scope.intensity = [];
    window.pqs2DataSetIntensity = function (conditions) {
        $scope.intensity = conditions;
    }

    $scope.paIntensity = [];
    window.pqs2DataSetPaIntensity = function (conditions) {
        $scope.paIntensity = conditions;
    }
    $scope.paPerWeek = [];
    window.pqs2DataSetpaPerWeek = function (conditions) {
        $scope.paPerWeek = conditions;
    }

    $scope.paSession = [];
    window.pqs2DataSetPaSession = function (conditions) {
        $scope.paSession = conditions;
    }
    // changes required for new step
    window.pqs2Data = $scope.data;
    /* end: DATA **/
}]);


// controller parq #3
vpFormPQ.controller('PQWidgetThree', ['$scope', '$sce', function($scope, $sce) {

    // changes required for new step
    var container = $('.container-pq-step-3');
    // changes required for new step
    $scope.controllerStep = 3;

    $scope.activeIndex = 0;
    $scope.activeSubIndex = -1;
    $scope.indexType = "";

    $scope.percentCompleted = 0;

    $scope.isKeyDitectionEnabled = true;

    $scope.disableKeyDetection = function () {
        $scope.isKeyDitectionEnabled = false;
    }

    $scope.enableKeyDetection = function () {
        $scope.isKeyDitectionEnabled = true;
    }

    // $scope.offsetTop = 150;
    if (screen.width < 768) {
        $scope.offsetTop = 70;
    } else {
        $scope.offsetTop = 150;
    }
    $( window ).resize(function() {
        if (screen.width < 768) {
            $scope.offsetTop = 70;
        } else {
            $scope.offsetTop = 150;
        }
    });


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
        var elem = container.find(".vp-item").eq(index);
        $parentDiv = container.closest('.gray_box');
        if(elem) {
            // jQuery('html, body').stop().animate({
            //     scrollTop: jQuery(elem).offset().top - $scope.offsetTop
            // }, 'slow');
            $parentDiv.stop().animate({
                scrollTop: $parentDiv.scrollTop() - $parentDiv.offset().top + jQuery(elem).offset().top
            }, 'slow');
        }
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

        setTimeout(function() {
            container.find('input[value="'+cRadio.value+'"]').val(cRadio.value).trigger('change');

        }, 100);

        if(cOption.customValueEnabled) {
            // set data receive mode yes
            cOption.isDataReceiving = "yes";

            // set default custom value
            cOption.customValue = cOption.value;
        } else {
            if((radioIndex === 0 || radioIndex === 1 || radioIndex === 2) && optionIndex === 0) {

            } else {
                // jump to next input
                // setTimeout(function () {
                //     $scope.jumpToNextInput();
                // }, 200);
            }
        }
        if(isDigestRequired) {
            if(!$scope.$$phase) {
                $scope.$digest();
            }
        }
    }

    $scope.updateRadioOptionValue = function ($event, gGadioIndex, gOptionIndex) {
        $event.stopPropagation();

        var cIndex = $scope.activeIndex,
            radioIndex = gGadioIndex,
            optionIndex = gOptionIndex;

        // var itemIndex = parseInt($scope.vpFormPQ.activeItem),
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
        if(!$scope.$$phase) {
            $scope.$digest();
        }
    }


    $scope.isControllerActive = function() {
        return $scope.controllerStep === parseInt($('#m-selected-step').val());
    }

    // take action on active item based on keypress
    $(document).bind('keypress', function(event) {

        if($scope.isControllerActive() && $scope.isKeyDitectionEnabled) {

            var cIndex = $scope.activeIndex,
                cSubIndex = $scope.activeSubIndex,
                indexType = $scope.indexType;


            if(indexType === 'rating' || indexType === 'rating-number') {
                var key = parseInt(event.key);

                // if(key > 0 && key <= item.itemCount ) {
                //     $scope.vpFormPQ.setRatingValue(key);
                // }
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

                            
                        }
                        // else if (item.type === 'checkbox' || item.type === 'checkbox-vertical') {
                        //     $scope.vpFormPQ.setCheckboxValue(optionIndex);
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
            $('.gray_box').bind('scroll', function () {
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
        // changes required for new step
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
            // jQuery(currentItem).find('input, textarea, select').focus();

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
                // jQuery(currentItem).find('input, textarea, select').focus();

                if(iType === "radio") {
                    $(document).find('input, textarea, select').blur();
                }
            }, 300)

            $scope.activateWindowAdventure();
        }
    });


    // changes required for new step
    window.getPqS3ActiveIndex = function () {
        return $scope.activeIndex;
    }

    $scope.medicalCondition = [];

    window.pqs3DataSetMedicalCondition = function (conditions) {
        $scope.medicalCondition = conditions;
    }

    $scope.relMedicalCondition = [];

    window.pqs3DataSetRelMedicalCondition = function (conditions) {
        $scope.relMedicalCondition = conditions;
    }



    // changes required for new step
    window.digestPqs3 = function () {
        if(!$scope.$$phase) {
            $scope.$digest();
        }
    }
    /* end: Functions **/


    /* start: DATA **/
    $scope.data = {
        radio: [
            {
                // option gaol model - index = 0
                activeOption: -1,
                value: "",
                keySelectionEnabled: true,
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: 'Yes', // required
                        value: 'Yes' // required
                    },
                    {
                        icon: 'fa fa-times',  // required
                        key: 'B',  // optional
                        label: 'No',  // required
                        value: 'No'  // required
                    }
                ]
            },
            {
                // option another - index = 1
                activeOption: -1,
                value: "",
                keySelectionEnabled: true,
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: 'Yes', // required
                        value: 'Yes' // required
                    },
                    {
                        icon: 'fa fa-times',  // required
                        key: 'B',  // optional
                        label: 'No',  // required
                        value: 'No'  // required
                    }
                ]
            },
            {
                // option another - index = 2
                activeOption: -1,
                value: "",
                keySelectionEnabled: true,
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: 'Yes', // required
                        value: 'Yes' // required
                    },
                    {
                        icon: 'fa fa-times',  // required
                        key: 'B',  // optional
                        label: 'No',  // required
                        value: 'No'  // required
                    }
                ]
            },
            {
                // option another - index = 3
                activeOption: -1,
                value: "",
                keySelectionEnabled: true,
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: '1-9', // required
                        value: '1-9' // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'B',  // optional
                        label: '10-19',  // required
                        value: '10-19'  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'B',  // optional
                        label: '20+',  // required
                        value: '20+'  // required
                    }
                ]
            }
        ]
    }

    // changes required for new step
    window.pqs3Data = $scope.data;
    /* end: DATA **/
}]);


// controller parq #4
vpFormPQ.controller('PQWidgetFour', ['$scope', '$sce', function($scope, $sce) {

    // changes required for new step
    var container = $('.container-pq-step-4');
    // changes required for new step
    $scope.controllerStep = 4;

    $scope.activeIndex = 0;
    $scope.activeSubIndex = -1;
    $scope.indexType = "";

    $scope.percentCompleted = 0;

    // $scope.offsetTop = 150;
    if (screen.width < 768) {
        $scope.offsetTop = 70;
    } else {
        $scope.offsetTop = 150;
    }
    $( window ).resize(function() {
        if (screen.width < 768) {
            $scope.offsetTop = 70;
        } else {
            $scope.offsetTop = 150;
        }
    });


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
        var cIndex = $scope.activeIndex;
            // nIndex = cIndex + 1;

        var cElem = container.find('.vp-item').eq(cIndex),
            isValid = cElem.attr('data-valid');

        var nIndex = $(cElem).next('li').attr('data-index');
        nIndex = parseInt(nIndex);

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
        var cElem = container.find('.vp-item').eq($scope.activeIndex);

        var pIndex = $(cElem).prev('li').attr('data-index');
        pIndex = parseInt(pIndex);

        // var pIndex = parseInt($scope.activeIndex) - 1;

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
        var elem = container.find(".vp-item").eq(index);
        $parentDiv = container.closest('.gray_box');
        if(elem) {
            // jQuery('html, body').stop().animate({
            //     scrollTop: jQuery(elem).offset().top - $scope.offsetTop
            // }, 'slow');
            $parentDiv.stop().animate({
                scrollTop: $parentDiv.scrollTop() - $parentDiv.offset().top + jQuery(elem).offset().top
            }, 'slow');
        }
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

        $(container).find('li[data-index=0] .radio-inputs input').eq(optionIndex).prop('checked', true)
            .trigger('click');


        if(cOption.customValueEnabled) {
            // set data receive mode yes
            cOption.isDataReceiving = "yes";

            // set default custom value
            cOption.customValue = cOption.value;
        } else if(optionIndex !== 0) {
            // jump to next input
            // $scope.jumpToNextInput();
        }
        if(isDigestRequired) {
            if(!$scope.$$phase) {
                $scope.$digest();
            }
        }
    }

    $scope.updateRadioOptionValue = function ($event, gGadioIndex, gOptionIndex) {
        $event.stopPropagation();

        var cIndex = $scope.activeIndex,
            radioIndex = gGadioIndex,
            optionIndex = gOptionIndex;

        // var itemIndex = parseInt($scope.vpFormPQ.activeItem),
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
        if(!$scope.$$phase) {
            $scope.$digest();
        }
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

                // if(key > 0 && key <= item.itemCount ) {
                //     $scope.vpFormPQ.setRatingValue(key);
                // }
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

                           
                        }
                        // else if (item.type === 'checkbox' || item.type === 'checkbox-vertical') {
                        //     $scope.vpFormPQ.setCheckboxValue(optionIndex);
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
            $('.gray_box').bind('scroll', function () {
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
        // changes required for new step
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
            // jQuery(currentItem).find('input, textarea, select').focus();

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
                // jQuery(currentItem).find('input, textarea, select').focus();

                if(iType === "radio") {
                    $(document).find('input, textarea, select').blur();
                }
            }, 300)

            $scope.activateWindowAdventure();
        }
    });


    // changes required for new step
    window.getPqS4ActiveIndex = function () {
        return $scope.activeIndex;
    }

    // changes required for new step
    window.digestPqs4 = function () {
        if(!$scope.$$phase) {
            $scope.$digest();
        }
    }
    /* end: Functions **/



    /* start: DATA **/
    $scope.data = {
        radio: [
            {
                // option gaol model - index = 0
                activeOption: 1,
                value: "ansNo0",
                keySelectionEnabled: true,
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: 'Yes', // required
                        value: 'ansYes0' // required
                    },
                    {
                        icon: 'fa fa-times',  // required
                        key: 'B',  // optional
                        label: 'No',  // required
                        value: 'ansNo0'  // required
                    }
                ]
            },
            {
                // option gaol model - index = 1
                activeOption: 1,
                value: "ansNo1",
                keySelectionEnabled: true,
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: 'Yes', // required
                        value: 'ansYes1' // required
                    },
                    {
                        icon: 'fa fa-times',  // required
                        key: 'B',  // optional
                        label: 'No',  // required
                        value: 'ansNo1'  // required
                    }
                ]
            },
            {
                // option gaol model - index = 2
                activeOption: 1,
                value: "ansNo2",
                keySelectionEnabled: true,
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: 'Yes', // required
                        value: 'ansYes2' // required
                    },
                    {
                        icon: 'fa fa-times',  // required
                        key: 'B',  // optional
                        label: 'No',  // required
                        value: 'ansNo2'  // required
                    }
                ]
            },
            {
                // option gaol model - index = 3
                activeOption: 1,
                value: "ansNo3",
                keySelectionEnabled: true,
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: 'Yes', // required
                        value: 'ansYes3' // required
                    },
                    {
                        icon: 'fa fa-times',  // required
                        key: 'B',  // optional
                        label: 'No',  // required
                        value: 'ansNo3'  // required
                    }
                ]
            },
            {
                // option gaol model - index = 4
                activeOption: 1,
                value: "ansNo4",
                keySelectionEnabled: true,
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: 'Yes', // required
                        value: 'ansYes4' // required
                    },
                    {
                        icon: 'fa fa-times',  // required
                        key: 'B',  // optional
                        label: 'No',  // required
                        value: 'ansNo4'  // required
                    }
                ]
            },
            {
                // option gaol model - index = 5
                activeOption: 1,
                value: "ansNo5",
                keySelectionEnabled: true,
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: 'Yes', // required
                        value: 'ansYes5' // required
                    },
                    {
                        icon: 'fa fa-times',  // required
                        key: 'B',  // optional
                        label: 'No',  // required
                        value: 'ansNo5'  // required
                    }
                ]
            },
            {
                // option gaol model - index = 6
                activeOption: 1,
                value: "ansNo6",
                keySelectionEnabled: true,
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: 'Yes', // required
                        value: 'ansYes6' // required
                    },
                    {
                        icon: 'fa fa-times',  // required
                        key: 'B',  // optional
                        label: 'No',  // required
                        value: 'ansNo6'  // required
                    }
                ]
            },
            {
                // option gaol model - index = 7
                activeOption: 1,
                value: "ansNo7",
                keySelectionEnabled: true,
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: 'Yes', // required
                        value: 'ansYes7' // required
                    },
                    {
                        icon: 'fa fa-times',  // required
                        key: 'B',  // optional
                        label: 'No',  // required
                        value: 'ansNo7'  // required
                    }
                ]
            },
            {
                // option gaol model - index = 8
                activeOption: 1,
                value: "ansNo8",
                keySelectionEnabled: true,
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: 'Yes', // required
                        value: 'ansYes8' // required
                    },
                    {
                        icon: 'fa fa-times',  // required
                        key: 'B',  // optional
                        label: 'No',  // required
                        value: 'ansNo8'  // required
                    }
                ]
            },
            {
                // option gaol model - index = 9
                activeOption: 1,
                value: "ansNo9",
                keySelectionEnabled: true,
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: 'Yes', // required
                        value: 'ansYes9' // required
                    },
                    {
                        icon: 'fa fa-times',  // required
                        key: 'B',  // optional
                        label: 'No',  // required
                        value: 'ansNo9'  // required
                    }
                ]
            },
        ]
    }

    // changes required for new step
    window.pqs4Data = $scope.data;
    /* end: DATA **/
}]);



// controller parq #5
vpFormPQ.controller('PQWidgetFive', ['$scope', '$sce', function($scope, $sce) {

    // changes required for new step
    var container = $('.container-pq-step-5');
    // changes required for new step
    $scope.controllerStep = 5;

    $scope.activeIndex = 0;
    $scope.activeSubIndex = -1;
    $scope.indexType = "";

    $scope.percentCompleted = 0;

    // $scope.offsetTop = 150;
    if (screen.width < 768) {
        $scope.offsetTop = 70;
    } else {
        $scope.offsetTop = 150;
    }
    $( window ).resize(function() {
        if (screen.width < 768) {
            $scope.offsetTop = 70;
        } else {
            $scope.offsetTop = 150;
        }
    });


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
        $parentDiv = container.closest('.gray_box');
        if(elem) {
            // jQuery('html, body').stop().animate({
            //     scrollTop: jQuery(elem).offset().top - $scope.offsetTop
            // }, 'slow');
            $parentDiv.stop().animate({
                scrollTop: $parentDiv.scrollTop() - $parentDiv.offset().top + jQuery(elem).offset().top
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
            if(!$scope.$$phase) {
                $scope.$digest();
            }
        }

        setTimeout(function() {
            $scope.jumpToNextInput();
        }, 400);
    }

    /** start: SET RADIO OPTIONS **/
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
            // $scope.jumpToNextInput();
        }
        if(isDigestRequired) {
            if(!$scope.$$phase) {
                $scope.$digest();
            }
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
    /** end: SET RADIO OPTIONS **/


    /** start: CHECKBOX **/
    $scope.setCheckboxValue = function (checkboxIndex, optionIndex, digestRequired) {
        var isDigestRequired = false;
        if(digestRequired != 'undefined') {
            isDigestRequired = digestRequired;
        }

        var itemIndex = parseInt(checkboxIndex),
            optionIndex = parseInt(optionIndex);

        var item = $scope.data.checkbox[itemIndex],
            option = item.options[optionIndex];

        // deselect selectAll
        var itemElem = container.find('li.vp-item[data-type="checkbox"][data-sub-index='+parseInt(checkboxIndex)+']');

        if(itemElem.length > 0) {
            var checkElem = itemElem.find('input.selAllDd');

            if(checkElem.length > 0) {
                checkElem.prop('checked', false);
            }
        }
        if(item.hasOwnProperty('isAllSelected')) {
            item.isAllSelected = false;
        }

        // add | remove to active options' array
        var valueIndexInActiveOptions = item.activeOptions.indexOf(option.value);
        if( valueIndexInActiveOptions > -1) {
            // value exist, so remove it
            item.activeOptions.splice(valueIndexInActiveOptions, 1);
        } else {
            // value not exist, so add it
            item.activeOptions[item.activeOptions.length] = option.value;
        }

        // add | remove to value array
        // var valueIndexInValueArray = item.value.indexOf(option.value);
        //
        // if(valueIndexInValueArray > -1) {
        //     // value exist, so remove it
        //     item.value.splice(valueIndexInValueArray, 1);
        // } else {
        //     // value not exist, so add it
        //     item.value[item.value.length] = option.value;
        // }

        // update value in value array
        var itemValue = "";
        if(item.activeOptions.length > 0) {
            for(var i = 0; i < item.activeOptions.length; i++) {
                if(i !== item.activeOptions.length - 1) {
                    itemValue += item.activeOptions[i] + ',';
                } else {
                    itemValue += item.activeOptions[i];
                }
            }
        }
        item.value = itemValue;


        if(option.customValueEnabled) {
            // set data receive mode yes
            option.isDataReceiving = "yes";

            // set default custom value
            option.customValue = option.value;
        }

        if(digestRequired) {
            $scope.$digest();
        }
    }

    $scope.updateCheckboxOptionValue = function($event, checkboxIndex, optionIndex, digestRequired) {
        $event.stopPropagation();

        var isDigestRequired = false;
        if(digestRequired != 'undefined') {
            isDigestRequired = digestRequired;
        }

        var itemIndex = parseInt(checkboxIndex),
            optionIndex = parseInt(optionIndex);

        var item = $scope.data.checkbox[itemIndex],
            option = item.options[optionIndex];

        // deselect selectAll
        var itemElem = container.find('li.vp-item[data-type="checkbox"][data-sub-index='+parseInt(checkboxIndex)+']');

        if(itemElem.length > 0) {
            var checkElem = itemElem.find('input.selAllDd');

            if(checkElem.length > 0) {
                checkElem.prop('checked', false);
            }
        }

        if(item.hasOwnProperty('isAllSelected')) {
            item.isAllSelected = false;
        }

        if(option.customValue) {
            // update value in active options
            var valueIndexInActiveOptions = item.activeOptions.indexOf(option.value);
            item.activeOptions[valueIndexInActiveOptions] = option.customValue;

            // update value in value array
            // var valueIndexInValueArray = item.value.indexOf(option.value);
            // item.value[valueIndexInValueArray] = option.customValue;

            // update value in value array
            var itemValue = "";
            if(item.activeOptions.length > 0) {
                for(var i = 0; i < item.activeOptions.length; i++) {
                    if(i !== item.activeOptions.length - 1) {
                        itemValue += item.activeOptions[i] + ',';
                    } else {
                        itemValue += item.activeOptions[i];
                    }
                }
            }
            item.value = itemValue;

            // change option value and label
            option.value = option.customValue;
            option.label = option.customValue;

            // set data receive mode no
            option.isDataReceiving = "no";
        }

        if(isDigestRequired) {
            $scope.$digest();
        }

    }

    $scope.stopPropagation = function($event) {
        $event.stopPropagation();
    }

    $scope.setCheckboxValueAll = function ($subIndex) {


        var itemIndex = parseInt($subIndex);
        var item = $scope.data.checkbox[itemIndex];


        item.isAllSelected = !item.isAllSelected;

        if(item.isAllSelected) {
            for(var i = 0; i < item.options.length; i++) {
                var option = item.options[i];

                item.activeOptions[item.activeOptions.length] = option.value;

                // item.value[item.value.length] = option.value;
            }

            var itemValue = "";
            if(item.activeOptions.length > 0) {
                for(var i = 0; i < item.activeOptions.length; i++) {
                    if(i !== item.activeOptions.length - 1) {
                        itemValue += item.activeOptions[i] + ',';
                    } else {
                        itemValue += item.activeOptions[i];
                    }
                }
            }
            item.value = itemValue;
        } else {
            item.activeOptions = [];
            item.value = "";
        }

    }
    /** end: CHECKBOX **/


    // calculate form percent completed
    $scope.calculatePercentCompleted = function() {
        var totalItem = container.find('li.vp-item').length,
            currentItem = $scope.activeIndex;

        if(currentItem === totalItem - 1) {
            $scope.percentCompleted = 100;
        } else {
            $scope.percentCompleted = Math.ceil((100 * currentItem) / totalItem);
        }
        if(!$scope.$$phase) {
            $scope.$digest();
        }
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
                        }
                        else if (indexType === 'checkbox' && optionIndex > -1) {
                            $scope.setCheckboxValue(cSubIndex, optionIndex, true);
                        }
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
            $('.gray_box').bind('scroll', function () {
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
        $scope.windowAdventure('#viewport-5', '.vp-item', function (viewport, currentItem) {
            var activeIndex = parseInt(jQuery(currentItem).attr('data-index')),
                activeSubIndex = parseInt(jQuery(currentItem).attr('data-sub-index')),
                iType = jQuery(currentItem).attr('data-type');



            $(viewport).find('.vp-item').removeClass('vp-form-active');

            // add class active to current item
            $(currentItem).addClass('vp-form-active')
                .find('.input-header, .input-body').slideDown(400);

            $scope.setActiveIndexAndType(activeIndex, activeSubIndex, iType);

            // focus current input
            // jQuery(currentItem).find('input, textarea, select').focus();


            // init rating values
            // $scope.data.rating[0].value = $(viewport).find('.stress').val();
            // $scope.data.rating[1].value = $(viewport).find('.sleep').val();
            // $scope.data.rating[2].value = $(viewport).find('.nutrition').val();
            // $scope.data.rating[3].value = $(viewport).find('.hydration').val();
            // $scope.data.rating[4].value = $(viewport).find('.humidity').val();

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
                // jQuery(currentItem).find('input, textarea, select').focus();

                if(iType === "radio") {
                    $(document).find('input, textarea, select').blur();
                }
            }, 300)

            $scope.activateWindowAdventure();
        }
    });


    window.getPqS5CheckBoxOptions = function (checkboxIndex) {
        var options = $scope.data.checkbox[checkboxIndex]['options'];

        var values = [];
        for (var i = 0; i < options.length; i++) {
            values[values.length] = options[i]['value'];
        }

        return values;


    }

    window.setPqS5CheckboxValue = function (checkboxIndex, optionIndex) {
        $scope.setCheckboxValue(checkboxIndex,optionIndex)
    }

    // changes required for new step
    window.getPqS5ActiveIndex = function () {
        return $scope.activeIndex;
    }

    // changes required for new step
    window.digestPqs5 = function () {
        if(!$scope.$$phase) {
            $scope.$digest();
        }
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
        radio: [
            {
                // option gaol model - index = 0
                activeOption: -1,
                value: "",
                keySelectionEnabled: true,
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: 'Not', // required
                        value: 'Not' // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'B',  // optional
                        label: 'Somewhat',  // required
                        value: 'Somewhat'  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'C',  // optional
                        label: 'Very',  // required
                        value: 'Very'  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'D',  // optional
                        label: 'Extremely',  // required
                        value: 'Extremely'  // required
                    }
                ]
            },
            {
                // option gaol model - index = 1
                activeOption: -1,
                value: "",
                keySelectionEnabled: true,
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: 'None', // required
                        value: 'None' // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'B',  // optional
                        label: 'Moderate',  // required
                        value: 'Moderate'  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'C',  // optional
                        label: 'High',  // required
                        value: 'High'  // required
                    }
                ]
            },
            {
                // option gaol model - index = 2
                activeOption: -1,
                value: "",
                keySelectionEnabled: true,
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: 'None', // required
                        value: 'None' // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'B',  // optional
                        label: 'Moderate',  // required
                        value: 'Moderate'  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'C',  // optional
                        label: 'High',  // required
                        value: 'High'  // required
                    }
                ]
            },
            {
                // option gaol model - index = 3
                activeOption: -1,
                value: "",
                keySelectionEnabled: true,
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: 'None', // required
                        value: 'None' // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'B',  // optional
                        label: 'Moderate',  // required
                        value: 'Moderate'  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'C',  // optional
                        label: 'High',  // required
                        value: 'High'  // required
                    }
                ]
            }
        ],
        checkbox: [
            {
                // input type checkbox | index = 0
                value: "", // default value | optional
                activeOptions: [], // index of option, if want to keep option unselected set the value to -1
                keySelectionEnabled: true, // Boolean | required to select option by key
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: 'Health & Wellness', // required
                        value: 'Health & Wellness', // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'B',  // optional
                        label: 'Increased Energy',  // required
                        value: 'Increased Energy',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'C',  // optional
                        label: 'Tone',  // required
                        value: 'Tone',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'D',  // optional
                        label: 'Injury Recovery',  // required
                        value: 'Injury Recovery',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'E',  // optional
                        label: 'Improved Nutrition',  // required
                        value: 'Improved Nutrition',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'F',  // optional
                        label: 'Lose Weight',  // required
                        value: 'Lose Weight',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'G',  // optional
                        label: 'Improved Performance',  // required
                        value: 'Improved Performance',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'H',  // optional
                        label: 'Improved Endurance',  // required
                        value: 'Improved Endurance',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'I',  // optional
                        label: 'Improved Strength & Conditioning',  // required
                        value: 'Improved Strength & Conditioning',  // required
                    }
                ]
            },
            {
                // input type checkbox | index = 1
                value: "", // default value | optional
                activeOptions: [], // index of option, if want to keep option unselected set the value to -1
                keySelectionEnabled: true, // Boolean | required to select option by key
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: 'Physical activity', // required
                        value: 'Physical activity', // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'B',  // optional
                        label: 'Hydration',  // required
                        value: 'Hydration',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'C',  // optional
                        label: 'Nutrition',  // required
                        value: 'Nutrition',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'D',  // optional
                        label: 'Sleep',  // required
                        value: 'Sleep',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'E',  // optional
                        label: 'Occupation',  // required
                        value: 'Occupation',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'F',  // optional
                        label: 'Relationships',  // required
                        value: 'Relationships',  // required
                    }
                ]
            },
            {
                // input type checkbox | index = 2
                value: "", // default value | optional
                activeOptions: [], // index of option, if want to keep option unselected set the value to -1
                keySelectionEnabled: true, // Boolean | required to select option by key
                isAllSelected: false,
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'B', // optional
                        label: 'Toned', // required
                        value: 'Toned', // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'C',  // optional
                        label: 'Fitter',  // required
                        value: 'Fitter',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'D',  // optional
                        label: 'Stronger',  // required
                        value: 'Stronger',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'E',  // optional
                        label: 'Flexible',  // required
                        value: 'Flexible',  // required
                    }
                ]
            },
            {
                // input type checkbox | index = 3
                value: "", // default value | optional
                activeOptions: [], // index of option, if want to keep option unselected set the value to -1
                keySelectionEnabled: true, // Boolean | required to select option by key
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'B', // optional
                        label: 'Happier', // required
                        value: 'Happier', // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'C',  // optional
                        label: 'Energetic',  // required
                        value: 'Energetic',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'D',  // optional
                        label: 'Healthier',  // required
                        value: 'Healthier',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'E',  // optional
                        label: 'Relaxed',  // required
                        value: 'Relaxed',  // required
                    }
                ]
            },
            {
                // input type checkbox | index = 4
                value: "", // default value | optional
                activeOptions: [], // index of option, if want to keep option unselected set the value to -1
                keySelectionEnabled: true, // Boolean | required to select option by key
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'B', // optional
                        label: 'Less stress', // required
                        value: 'Less stress', // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'C',  // optional
                        label: 'More time',  // required
                        value: 'More time',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'D',  // optional
                        label: 'More fun',  // required
                        value: 'More fun',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'E',  // optional
                        label: 'More control',  // required
                        value: 'More control',  // required
                    }
                ]
            },
            {
                // input type checkbox | index = 1
                value: "", // default value | optional
                activeOptions: [], // index of option, if want to keep option unselected set the value to -1
                keySelectionEnabled: true, // Boolean | required to select option by key
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: 'No motivation', // required
                        value: 'No motivation', // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'B',  // optional
                        label: 'Low motivation',  // required
                        value: 'Low motivation',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'C',  // optional
                        label: 'TEAM motivated',  // required
                        value: 'TEAM motivated',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'D',  // optional
                        label: 'Self-motivated',  // required
                        value: 'Self-motivated',  // required
                    }
                ]
            }
        ]
    }


    $scope.goalHealthWellness = [];
    window.pqs5DataSetGoalHealthWellness = function (conditions) {
        $scope.goalHealthWellness = conditions;
    }

    $scope.lifestyleImprove = [];
    window.pqs5DataSetGoalLifestyleImprove = function (conditions) {
        $scope.lifestyleImprove = conditions;
    }

    $scope.goalWantTobe = [];
    window.pqs5DataSetGoalGoalWantTobe = function (conditions) {
        $scope.goalWantTobe = conditions;
    }

    $scope.goalWantfeel = [];
    window.pqs5DataSetGoalGoalWantTofeel = function (conditions) {
        $scope.goalWantfeel = conditions;
    }

    $scope.goalWantHave = [];
    window.pqs5DataSetGoalGoalWantToHave = function (conditions) {
        $scope.goalWantHave = conditions;
    }

    $scope.motivationImprove = [];
    window.pqs5DataSetMotivationImprove = function (conditions) {
        $scope.motivationImprove = conditions;
    }


    // changes required for new step
    window.pqs5Data = $scope.data;
    /* end: DATA **/
}]);



vpFormPQ.directive('contenteditable', ['$sce', function($sce) {
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

    // $('textarea').on('keypress',function() {
    //     $(this).css('width','0px');
    //     $(this).css('height','0px');
    //     $(this).css('width',Math.max(50,this.scrollWidth)+'px');
    //     $(this).css('height',Math.max(50,this.scrollHeight)+'px');
    // });
})