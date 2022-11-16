/** start: Angular.JS **/
var vpForm = angular.module('vp-form', ["angularjs-autogrow"]);

vpForm.controller('GBController', ['$rootScope', '$scope', function ($rootScope, $scope) {
    $rootScope.isFormStarted = false;

    var interval = setInterval(function () {
        $('#input-starting-screen').focus();
    }, 100);

    $scope.startFormInput = function () {
        $('.showdiv').css('display','block');

        $('.starting-screen').removeClass('fade-in');
        $('.panel-body').removeClass('invisible');

        $rootScope.isFormStarted = true;
        clearInterval(interval);
        $('body').css('overflow', 'auto');

        window.stepOneScrollToItem(0);
    };

    $scope.pressEnter = function (event) {
        if(event.which === 13 && !event.shiftKey) {
            event.preventDefault();

            $scope.startFormInput();
        }
    };
}]);

// controller goal buddy #1
vpForm.controller('GBWidgetOne', ['$scope', '$sce', function($scope, $sce) {

    // changes required for new step
    var container = $('.container-gb');
    // changes required for new step
    var stepId = $('#step-1');
    // changes required for new step
    $scope.controllerStep = 1;

    $scope.activeIndex = 0;
    $scope.activeSubIndex = 0;
    $scope.indexType = "radio";

    $scope.percentCompleted = 0;

    window.stepOneScrollToItem = function(index) {
        $scope.scrollToItem(index);
    }
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

    // required for checkbox
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
        } else {
            $scope.validateWidgetInputs(false);
        }

        $scope.scrollToItem(nIndex);
    }

    // required for checkbox
    $scope.jumpToCreateGoal = function () {
        var cIndex = $scope.activeIndex,
            nIndex = cIndex + 2;

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
        } else {
            $scope.validateWidgetInputs(false);
        }

        $scope.scrollToItem(nIndex);
    }

    // jump to prev input
    $scope.jumpToPrevInput = function () {
        var pIndex = parseInt($scope.activeIndex) - 1;

        $scope.scrollToItem(pIndex);
    }

    // required for checkbox
    $scope.validateWidgetInputs = function(scrollToInvalidItem) {
        var scrollToInItem = true;
        if(scrollToInvalidItem !== undefined) {
            scrollToInItem = scrollToInvalidItem;
        }

        var invalidInputs = container.find('.vp-item[data-valid="false"]');

        if(invalidInputs.length > 0) {
            var invalidInput = invalidInputs.eq(0),
                invalidIndex = parseInt($(invalidInput).attr('data-index'));

            if(scrollToInItem) {
                $scope.scrollToItem(invalidIndex);
            }

            stepId.find('.btn.btn-step').attr('disabled', 'disabled');
            return false
        } else {
            stepId.find('.btn.btn-step').removeAttr('disabled');
        }
    }

    $scope.scrollToItem = function (index) {
        var elem = container.find(".vp-item").eq(index)

        if(typeof elem != undefined && elem != '') {
            jQuery('html, body').stop().animate({
                scrollTop: jQuery(elem).offset().top - $scope.offsetTop
            }, 'slow');
        }
    }

    /** start: SET RADIO VALUE **/
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

        // var itemIndex = parseInt($scope.vpForm.activeItem),
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
    /** end: SET RADIO VALUE **/

    /** start: CHECKBOX **/
    // required for checkbox
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

        // add | remove to value array
        var valueIndexInValueArray = item.value.indexOf(option.value);

        if(valueIndexInValueArray > -1) {
            // value exist, so remove it
            item.value.splice(valueIndexInValueArray, 1);
        } else {
            // value not exist, so add it
            item.value[item.value.length] = option.value;
        }


        if(option.customValueEnabled) {
            // set data receive mode yes
            option.isDataReceiving = "yes";

            // set default custom value
            option.customValue = option.value;
        }

        if(item.value.length > 0) {
            $(container).find('.vp-item[data-sub-index="'+checkboxIndex+'"][data-type="checkbox"]').attr('data-valid', true)
                .find('input[type="hidden"]')
                    .removeClass('ng-empty ng-invalid ng-invalid-required')
                    .addClass('ng-valid');

        } else {
            $(container).find('.vp-item[data-sub-index="'+checkboxIndex+'"][data-type="checkbox"]').attr('data-valid', false);
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
            item.activeOptions[valueIndexInActiveOptions] = option.customValue

            // update value in value array
            var valueIndexInValueArray = item.value.indexOf(option.value);
            item.value[valueIndexInValueArray] = option.customValue;

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
                item.value[item.value.length] = option.value;
            }
        } else {
            item.activeOptions = [];
            item.value = [];
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
                        }
                        else if (indexType === 'checkbox' && optionIndex > -1) {
                            $scope.setCheckboxValue(cSubIndex, optionIndex, true);
                        }
                    }
                } // end if - is key tracking
            }
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


    window.getCheckBoxOptions = function (checkboxIndex) {
        var options = $scope.data.checkbox[checkboxIndex]['options'];

        var values = [];
        for (var i = 0; i < options.length; i++) {
            values[values.length] = options[i]['value'];
        }

        return values;


    }

    window.setCheckboxValue = function (checkboxIndex, optionIndex) {
        $scope.setCheckboxValue(checkboxIndex,optionIndex)
    }

    // changes required for new step
    window.getGBActiveIndex = function () {
        return $scope.activeIndex;
    }

    // changes required for new step
    window.digestGb = function () {
        $scope.$digest();
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
                        label: 'Weight Management', // required
                        value: 'Weight Management', // required
                        goal_template_id: 1,
                        image_url: 'result/images/weightmanagement.jpg',
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'B',  // optional
                        label: 'Drop a size',  // required
                        value: 'Drop a size',  // required
                        goal_template_id: 2,
                        image_url: 'result/images/drop_a_size.jpg',
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'C',  // optional
                        label: 'Improve nutrition',  // required
                        value: 'Improve nutrition',  // required
                        goal_template_id: 3,
                        image_url: 'result/images/eat.jpg',
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'D',  // optional
                        label: 'Improve hydration',  // required
                        value: 'Improve hydration',  // required
                        goal_template_id: 4,
                        image_url: 'result/images/improve_h.jpg',
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'F',  // optional
                        label: 'Limit stress',  // required
                        value: 'Limit stress',  // required
                        goal_template_id: 5,
                        image_url: 'result/images/reduce_stress.jpg',
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'E',  // optional
                        label: 'Improve sleep',  // required
                        value: 'Improve sleep',  // required
                        goal_template_id: 6,
                        image_url: 'result/images/improve_my_sleep.jpg',
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'H',  // optional
                        label: 'Improve health',  // required
                        value: 'Improve health',  // required
                        goal_template_id: 7,
                        image_url: 'result/images/improve_health.jpg',
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'I',  // optional
                        label: 'Injury recovery',  // required
                        value: 'Injury recovery',  // required
                        goal_template_id: 8,
                        image_url: 'result/images/injury.jpg',
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'J',  // optional
                        label: 'Increase activity',  // required
                        value: 'Increase activity',  // required
                        goal_template_id: 9,
                        image_url: 'result/images/increase_activity.jpg',
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'K',  // optional
                        label: 'Balance lifestyle',  // required
                        value: 'Balance lifestyle',  // required
                        goal_template_id: 10,
                        image_url: 'result/images/balance.jpg',
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'L',  // optional
                        label: 'Healthy Happy living',  // required
                        value: 'Healthy Happy living',  // required
                        goal_template_id: 11,
                        image_url: 'result/images/health.jpg',
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'M',  // optional
                        label: 'Improve posture',  // required
                        value: 'Improve posture',  // required
                        goal_template_id: 12,
                        image_url: 'result/images/improve_posture.jpg',
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'N',  // optional
                        label: 'Improve Time Management',  // required
                        value: 'Improve Time Management',  // required
                        goal_template_id: 13,
                        image_url: 'result/images/time_man.jpg',
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'O',  // optional
                        label: 'Improve Perfomance',  // required
                        value: 'Improve Perfomance',  // required
                        goal_template_id: 14,
                        image_url: 'result/images/improve_per.jpg',
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'P',  // optional
                        label: 'Improve Career',  // required
                        value: 'Improve Career',  // required
                        goal_template_id: 15,
                        image_url: 'result/images/improve_c.jpg',
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'Q',  // optional
                        label: 'Be Proactive',  // required
                        value: 'Be Proactive',  // required
                        goal_template_id: 16,
                        image_url: 'result/images/become_proactive.jpg',
                    }, 
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
                        value: 'yes', // required
                    },
                    {
                        icon: 'fa fa-times',  // required
                        key: 'B',  // optional
                        label: 'No',  // required
                        value: 'no',  // required
                    }
                ]
            },
            {
                // option another - index = 2
                activeOption: 0,
                value: "Everyone",
                keySelectionEnabled: true,
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: 'Everyone', // required
                        value: 'Everyone', // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'B',  // optional
                        label: 'Just Me',  // required
                        value: 'Just Me',  // required
                    }
                ]
            },
            {
                // option another - index = 2
                activeOption: 0,
                value: "when_ overdue",
                keySelectionEnabled: true,
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: 'When Overdue', // required
                        value: 'when_ overdue', // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'B',  // optional
                        label: 'Daily',  // required
                        value: 'daily',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'C',  // optional
                        label: 'Weekly',  // required
                        value: 'weekly',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'D',  // optional
                        label: 'Monthly',  // required
                        value: 'monthly',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'E',  // optional
                        label: 'None',  // required
                        value: 'none',  // required
                    }
                ]
            }
        ], // radio
        checkbox: [
            {
                // input type checkbox | index = 0
                value: [], // default value | optional
                activeOptions: [], // index of option, if want to keep option unselected set the value to -1
                keySelectionEnabled: true, // Boolean | required to select option by key
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: 'Improve health', // required
                        value: 'Improve_health', // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'B',  // optional
                        label: 'Improve mental and emotional wellness',  // required
                        value: 'Improve_mental_and_emotional_wellness',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'C',  // optional
                        label: 'Improve lifestyle',  // required
                        value: 'Improve_lifestyle',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'D',  // optional
                        label: 'Improve self image',  // required
                        value: 'Improve_self_image',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'E',  // optional
                        label: 'Improve family/home environment',  // required
                        value: 'Improve_family_home_environment',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'E',  // optional
                        label: 'Improve personal relationships',  // required
                        value: 'Improve_personal_relationships',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'E',  // optional
                        label: 'Improve career satisfaction',  // required
                        value: 'improve_career_satisfaction',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'E',  // optional
                        label: 'Improve financial situation',  // required
                        value: 'improve_financial_situation',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'E',  // optional
                        label: 'Other',  // required
                        value: 'Other',  // required
                        customValueEnabled: true
                    }
                ]
            }
        ] // check box
    }

    // changes required for new step
    window.gbData = $scope.data;
    /* end: DATA **/
}]);



// controller goal buddy #2
vpForm.controller('GBWidgetTwo', ['$scope', '$sce', function($scope, $sce) {

    // changes required for new step
    var container = $('.container-gb-step-2');
    // changes required for new step
    $scope.controllerStep = 2;

    $scope.activeIndex = 0;
    $scope.activeSubIndex = -1;
    $scope.indexType = "";

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

       
    }


    // Check milestone validation
    $scope.isMilestoneInvalid = function () {
        var milestoneNames = [],
        milestoneDates = [];

        $('input[name="milestones"]').each(function(){
            var data = $(this).val();
            if(typeof data != undefined && data != '')
                milestoneNames.push(data);
        });

        $('input[name="milestones-date"]').each(function(){
            var data = $(this).val();
            if(typeof data != undefined && data != '')
                milestoneDates.push(data);
        });

        if(milestoneNames.length == 0 || milestoneDates.length == 0 || (milestoneNames.length != milestoneDates.length))
            return true;

        return false;
    }

    // Check if habit recurrence is invalid
    $scope.validateGoalHabit = function () {
        var habitRecurrenceType = $('#SYG_habit_recurrence_wrapper ul li:active').data('recurrence-type');
        console.log(habitRecurrenceType);
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

        // var itemIndex = parseInt($scope.vpForm.activeItem),
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

                // if(key > 0 && key <= item.itemCount ) {
                //     $scope.vpForm.setRatingValue(key);
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

                            console.log(optionIndex, cIndex, cSubIndex, indexType);
                        }
                        // else if (item.type === 'checkbox' || item.type === 'checkbox-vertical') {
                        //     $scope.vpForm.setCheckboxValue(optionIndex);
                        // }
                    }
                } // end if - is key tracking
            }
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

            $(window).bind('scroll', function() {
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
        $scope.windowAdventure('#viewport-2', '.vp-item', function (viewport, currentItem) {
            var activeIndex = parseInt(jQuery(currentItem).attr('data-index')),
                activeSubIndex = parseInt(jQuery(currentItem).attr('data-sub-index')),
                iType = jQuery(currentItem).attr('data-type');


            // add class active to current item
            $(viewport).find('.vp-item').removeClass('vp-form-active');

            $(currentItem).addClass('vp-form-active')
                .find('.input-header, .input-body').slideDown(400);

            $scope.setActiveIndexAndType(activeIndex, activeSubIndex, iType);

            // focus current input
            jQuery(currentItem).find('input, textarea, select').focus();

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
    window.getGBS2ActiveIndex = function () {
        return $scope.activeIndex;
    }

    // changes required for new step
    window.digestGbs2 = function () {
        $scope.$digest();
    }

    /* end: Functions **/



    /* start: DATA **/
    $scope.data = {
        radio: [
            {
                // who can view your milestone - index = 0
                activeOption: 0,
                value: "everyone",
                keySelectionEnabled: true,
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: 'Everyone', // required
                        value: 'everyone', // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'B',  // optional
                        label: 'Just Me',  // required
                        value: 'Just_Me',  // required
                    }
                ]
            },
            {
                // option gb_milestones_reminder - index = 1
                activeOption: 0,
                value: "when_overdue",
                keySelectionEnabled: true,
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: 'When Overdue', // required
                        value: 'when_overdue', // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'B',  // optional
                        label: 'Daily',  // required
                        value: 'daily',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'C',  // optional
                        label: 'Weekly',  // required
                        value: 'weekly',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'D',  // optional
                        label: 'Monthly',  // required
                        value: 'monthly',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'E',  // optional
                        label: 'None',  // required
                        value: 'none',  // required
                    }
                ]
            }
        ]
    }

    // changes required for new step
    window.gbs2Data = $scope.data;
    /* end: DATA **/
}]);





// controller goal buddy #3
vpForm.controller('GBWidgetThree', ['$scope', '$sce', function($scope, $sce) {

    // changes required for new step
    var container = $('.container-gb-step-3');
    // changes required for new step
    $scope.controllerStep = 3;

    $scope.activeIndex = 0;
    $scope.activeSubIndex = -1;
    $scope.indexType = null;

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

        var jumpToNext = $(cElem).attr('data-jump-next');
        if(jumpToNext !== undefined && jumpToNext === "no") {
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
            if((radioIndex === 0) && (optionIndex === 1 || optionIndex === 2) ) {

            } else {
                // jump to next input
                $scope.jumpToNextInput();
            }
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

        // var itemIndex = parseInt($scope.vpForm.activeItem),
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

                // if(key > 0 && key <= item.itemCount ) {
                //     $scope.vpForm.setRatingValue(key);
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

                            console.log(optionIndex, cIndex, cSubIndex, indexType);
                        }
                        // else if (item.type === 'checkbox' || item.type === 'checkbox-vertical') {
                        //     $scope.vpForm.setCheckboxValue(optionIndex);
                        // }
                    }
                } // end if - is key tracking
            }
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

            $(window).bind('scroll', function() {
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


            // add class active to current item
            $(viewport).find('.vp-item').removeClass('vp-form-active');

            $(currentItem).addClass('vp-form-active')
                .find('.input-header, .input-body').slideDown(400);

            $scope.setActiveIndexAndType(activeIndex, activeSubIndex, iType);

            // focus current input
            jQuery(currentItem).find('input, textarea, select').focus();

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
    window.getGBS3ActiveIndex = function () {
        return $scope.activeIndex;
    }

    // changes required for new step
    window.digestGbs3 = function () {
        $scope.$digest();
    }

    /* end: Functions **/



    /* start: DATA **/
    $scope.data = {
        radio: [
            {
                // SYG_habit_recurrence - index = 0
                activeOption: 0,
                value: "daily",
                keySelectionEnabled: true,
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: 'Daily', // required
                        value: 'daily', // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'B',  // optional
                        label: 'Weekly',  // required
                        value: 'weekly',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'C',  // optional
                        label: 'Monthly',  // required
                        value: 'monthly',  // required
                    }
                ]
            },
            {
                // syg2_see_habit - index = 1
                activeOption: 0,
                value: "everyone",
                keySelectionEnabled: true,
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: 'Everyone', // required
                        value: 'everyone', // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'B',  // optional
                        label: 'Just Me',  // required
                        value: 'Just Me',  // required
                    }
                ]
            },
            {
                // syg2_send_msg - index = 2
                activeOption: 0,
                value: "only_if_I_am_late",
                keySelectionEnabled: true,
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: 'If I\'m late', // required
                        value: 'only_if_I_am_late', // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'B',  // optional
                        label: 'Every occurrence',  // required
                        value: 'Every_occurrence',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'C',  // optional
                        label: 'None',  // required
                        value: 'none',  // required
                    }
                ]
            }
        ]
    }

    // changes required for new step
    window.gbs3Data = $scope.data;
    /* end: DATA **/

    // changes required for new step
    // reset form data on add new habit
    window.gbs3ResetForm = function() {
        // reset data
        $scope.data = {
            radio: [
                {
                    // SYG_habit_recurrence - index = 0
                    activeOption: 0,
                    value: "daily",
                    keySelectionEnabled: true,
                    options: [
                        {
                            icon: 'fa fa-check', // required
                            key: 'A', // optional
                            label: 'Daily', // required
                            value: 'daily', // required
                        },
                        {
                            icon: 'fa fa-check',  // required
                            key: 'B',  // optional
                            label: 'Weekly',  // required
                            value: 'weekly',  // required
                        },
                        {
                            icon: 'fa fa-check',  // required
                            key: 'C',  // optional
                            label: 'Monthly',  // required
                            value: 'monthly',  // required
                        }
                    ]
                },
                {
                    // syg2_see_habit - index = 1
                    activeOption: 0,
                    value: "everyone",
                    keySelectionEnabled: true,
                    options: [
                        {
                            icon: 'fa fa-check', // required
                            key: 'A', // optional
                            label: 'Everyone', // required
                            value: 'everyone', // required
                        },
                        {
                            icon: 'fa fa-check',  // required
                            key: 'B',  // optional
                            label: 'Just Me',  // required
                            value: 'Just Me',  // required
                        }
                    ]
                },
                {
                    // syg2_send_msg - index = 2
                    activeOption: 0,
                    value: "only_if_I_am_late",
                    keySelectionEnabled: true,
                    options: [
                        {
                            icon: 'fa fa-check', // required
                            key: 'A', // optional
                            label: 'If I\'m late', // required
                            value: 'only_if_I_am_late', // required
                        },
                        {
                            icon: 'fa fa-check',  // required
                            key: 'B',  // optional
                            label: 'Every occurrence',  // required
                            value: 'Every_occurrence',  // required
                        },
                        {
                            icon: 'fa fa-check',  // required
                            key: 'C',  // optional
                            label: 'None',  // required
                            value: 'none',  // required
                        }
                    ]
                }
            ]
        } // end reset data

        // start: reset form
        $scope.SYG_habits = '';
        $scope.milestone_value = '';

        $('#milestone_div').val('');

        $scope.$digest();
        // end reset habit
    }
}]);



// controller goal buddy #4
vpForm.controller('GBWidgetFour', ['$scope', '$sce', function($scope, $sce) {

    // changes required for new step
    var container = $('.container-gb-step-4');
    // changes required for new step
    $scope.controllerStep = 4;

    $scope.activeIndex = 0;
    $scope.activeSubIndex = -1;
    $scope.indexType = null;

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

    $scope.jumpToNextInput = function (forceToMove) {
        var cIndex = $scope.activeIndex,
            nIndex = cIndex + 1;

        var cElem = container.find('.vp-item').eq(cIndex),
            isValid = cElem.attr('data-valid');

        if(isValid === "false") {
            return false;
        }

        var jumpToNext = $(cElem).attr('data-jump-next');
        if(jumpToNext !== undefined && jumpToNext === "no" && forceToMove === undefined) {
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

        // var itemIndex = parseInt($scope.vpForm.activeItem),
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

                // if(key > 0 && key <= item.itemCount ) {
                //     $scope.vpForm.setRatingValue(key);
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

                            console.log(optionIndex, cIndex, cSubIndex, indexType);
                        }
                        // else if (item.type === 'checkbox' || item.type === 'checkbox-vertical') {
                        //     $scope.vpForm.setCheckboxValue(optionIndex);
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

            $(window).bind('scroll', function() {
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


            // add class active to current item
            $(viewport).find('.vp-item').removeClass('vp-form-active');

            $(currentItem).addClass('vp-form-active')
                .find('.input-header, .input-body').slideDown(400);

            $scope.setActiveIndexAndType(activeIndex, activeSubIndex, iType);

            // focus current input
            jQuery(currentItem).find('input, textarea, select').focus();

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

            $scope.$digest();
        }
    });


    // changes required for new step
    window.getGBS4ActiveIndex = function () {
        return $scope.activeIndex;
    }

    // changes required for new step
    window.digestGbs4 = function () {
        $scope.$digest();
    }

    /* end: Functions **/



    /* start: DATA **/
    $scope.data = {
        radio: [
            {
                // SYG_habit_recurrence - index = 0
                activeOption: 0,
                value: "daily",
                keySelectionEnabled: true,
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: 'Daily', // required
                        value: 'daily', // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'B',  // optional
                        label: 'Weekly',  // required
                        value: 'weekly',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'C',  // optional
                        label: 'Monthly',  // required
                        value: 'monthly',  // required
                    }
                ]
            },
            {
                // syg2_see_habit - index = 1
                activeOption: 0,
                value: "everyone",
                keySelectionEnabled: true,
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: 'Everyone', // required
                        value: 'everyone', // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'B',  // optional
                        label: 'Just Me',  // required
                        value: 'Just Me',  // required
                    }
                ]
            },
            {
                // SYG3_send_msg - index = 2
                activeOption: 0,
                value: "When_task_is_overdue",
                keySelectionEnabled: true,
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: 'Overdue', // required
                        value: 'When_task_is_overdue', // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'B',  // optional
                        label: 'Due',  // required
                        value: 'When_task_is_due',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'C',  // optional
                        label: 'None',  // required
                        value: 'none',  // required
                    }
                ]
            },
            {
                // SYG3_send_msg - index = 3
                activeOption: -1,
                value: "",
                keySelectionEnabled: true,
                options: [
                    {
                        icon: 'fa fa-check', // required
                        key: 'A', // optional
                        label: 'Low', // required
                        value: 'Low', // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'B',  // optional
                        label: 'Normal',  // required
                        value: 'Normal',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'C',  // optional
                        label: 'High',  // required
                        value: 'High',  // required
                    },
                    {
                        icon: 'fa fa-check',  // required
                        key: 'D',  // optional
                        label: 'Urgent',  // required
                        value: 'Urgent',  // required
                    }
                ]
            }
        ]
    }

    // changes required for new step
    window.gbs4Data = $scope.data;
    /* end: DATA **/


    // changes required for new step
    // reset form data on add new habit
    window.gbs4ResetForm = function() {
        // reset data
        $scope.data = {
            radio: [
                {
                    // SYG_habit_recurrence - index = 0
                    activeOption: 0,
                    value: "daily",
                    keySelectionEnabled: true,
                    options: [
                        {
                            icon: 'fa fa-check', // required
                            key: 'A', // optional
                            label: 'Daily', // required
                            value: 'daily', // required
                        },
                        {
                            icon: 'fa fa-check',  // required
                            key: 'B',  // optional
                            label: 'Weekly',  // required
                            value: 'weekly',  // required
                        },
                        {
                            icon: 'fa fa-check',  // required
                            key: 'C',  // optional
                            label: 'Monthly',  // required
                            value: 'monthly',  // required
                        }
                    ]
                },
                {
                    // syg2_see_habit - index = 1
                    activeOption: 0,
                    value: "everyone",
                    keySelectionEnabled: true,
                    options: [
                        {
                            icon: 'fa fa-check', // required
                            key: 'A', // optional
                            label: 'Everyone', // required
                            value: 'everyone', // required
                        },
                        {
                            icon: 'fa fa-check',  // required
                            key: 'B',  // optional
                            label: 'Just Me',  // required
                            value: 'Just Me',  // required
                        }
                    ]
                },
                {
                    // SYG3_send_msg - index = 2
                    activeOption: 0,
                    value: "When_task_is_overdue",
                    keySelectionEnabled: true,
                    options: [
                        {
                            icon: 'fa fa-check', // required
                            key: 'A', // optional
                            label: 'Overdue', // required
                            value: 'When_task_is_overdue', // required
                        },
                        {
                            icon: 'fa fa-check',  // required
                            key: 'B',  // optional
                            label: 'Due',  // required
                            value: 'When_task_is_due',  // required
                        },
                        {
                            icon: 'fa fa-check',  // required
                            key: 'C',  // optional
                            label: 'None',  // required
                            value: 'none',  // required
                        }
                    ]
                },
                {
                    // SYG3_send_msg - index = 3
                    activeOption: -1,
                    value: "",
                    keySelectionEnabled: true,
                    options: [
                        {
                            icon: 'fa fa-check', // required
                            key: 'A', // optional
                            label: 'Low', // required
                            value: 'Low', // required
                        },
                        {
                            icon: 'fa fa-check',  // required
                            key: 'B',  // optional
                            label: 'Normal',  // required
                            value: 'Normal',  // required
                        },
                        {
                            icon: 'fa fa-check',  // required
                            key: 'C',  // optional
                            label: 'High',  // required
                            value: 'High',  // required
                        },
                        {
                            icon: 'fa fa-check',  // required
                            key: 'D',  // optional
                            label: 'Urgent',  // required
                            value: 'Urgent',  // required
                        }
                    ]
                }
            ]
        } // end reset data

        $scope.$digest();
    }
    // end: reset tasks

}]);



vpForm.directive('contenteditable', ['$sce', function($sce) {
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

    // $('input, textarea').focus(function(){
    $('input[type=text], input[type=password], input[type=tel], input[type=number], textarea').focus(function(){
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