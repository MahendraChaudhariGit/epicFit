/** start: Angular.JS **/
var vfBusinessPlan = angular.module('vf-business-plan', ["angularjs-autogrow"]);

vfBusinessPlan.controller('BPController', ['$rootScope', '$scope', function ($rootScope, $scope) {
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

        $('#m-selected-step').trigger('change');

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
vfBusinessPlan.controller('BPWidgetOne', ['$scope', '$rootScope', '$sce', function($scope, $rootScope, $sce) {

    // changes required for new step
    var container = $('.container-bp-step-1');
    // changes required for new step
    $scope.controllerStep = 1;

    $scope.activeIndex = 0;
    $scope.maxIndex = 0;
    $scope.activeSubIndex = -1;
    $scope.indexType = null;

    $scope.percentCompleted = 0;

    $scope.isKeyDitectionEnabled = true;

    $scope.disableKeyDetection = function () {
        $scope.isKeyDitectionEnabled = false;
    }

    $scope.enableKeyDetection = function () {
        $scope.isKeyDitectionEnabled = true;
    }

    $scope.offsetTop = 120;

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
            return false;
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

            return false;
        }
    }

    $scope.scrollToItem = function (index) {
        if(index > -1) {
            var elem = container.find(".vp-item").eq(index);

            if(elem) {
                jQuery('html, body').stop().animate({
                    scrollTop: jQuery(elem).offset().top - $scope.offsetTop
                }, 'slow');
            }
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
                    //     $scope.vfBusinessPlan.setRatingValue(key);
                    // }
                }
            }
        }
    });

    $scope.setActiveIndexAndType = function (index, subIndex, itype) {
        $scope.activeIndex = parseInt(index);
        $scope.activeSubIndex = parseInt(subIndex);
        $scope.indexType = itype;

        $scope.calculatePercentCompleted();

        if($scope.activeIndex > $scope.maxIndex) {
            $scope.maxIndex = $scope.activeIndex;
        }
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
            }, 300);

            $scope.activateWindowAdventure();
        }
    });


    // changes required for new step
    window.getBPS1ActiveIndex = function () {
        return $scope.activeIndex;
    }

    // changes required for new step
    window.digestBPs1 = function () {
        if(!$scope.$$phase) {
            $scope.$digest();
        }
    }
    /* end: Functions **/



    /* start: DATA **/
    $scope.data = {

    }

    // changes required for new step
    window.BPs1Data = $scope.data;
    /* end: DATA **/
}]);





vfBusinessPlan.directive('contenteditable', ['$sce', function($sce) {
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

    // save business plan data
    $('.bp-step-finish').click(function() {
        var $this = $(this),
            $form = $this.closest('form'),
            formData = $form.serialize();

        console.log(public_url+'/business-plan', formData);

        $.ajax({
            url: public_url+'/business-plan',
            method: "POST",
            data: formData,
            success: function(data) {
                var myObj=JSON.parse(data);
                if(myObj.status=='succsess'){

                    $('#msg').html('<p class="alert alert-success">Data has been saved successfully!</p>');
                    $('html, body').animate({scrollTop: 0}, 400);

                    // if(group_name=='ex_summary')
                    //     idField.val(myObj.insertedId);
                    // if(group_name=='financial_plan_group')
                    //     location.reload();
                }
            }
        });
    });
});