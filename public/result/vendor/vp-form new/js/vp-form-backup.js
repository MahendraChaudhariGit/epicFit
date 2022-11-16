/** start: Angular.JS **/
var vpForm = angular.module('vp-form', ["angularjs-autogrow"]);

vpForm.controller('vpFormController', ['$scope', '$sce', function($scope, $sce) {

    $scope.vpForm = {};
    $scope.vpForm.inputs = [{type:'text'}];
    $scope.vpForm.activeItem = 0;
    $scope.vpForm.percentCompleted = 0;

    /** start: AUTO-FILL MONTHS **/
    $scope.vpForm.months = [
        {key : 'January', value: 'January'},
        {key : 'February', value: 'February'},
        {key : 'March', value: 'March'},
        {key : 'April', value: 'April'},
        {key : 'May', value: 'May'},
        {key : 'June', value: 'June'},
        {key : 'July', value: 'July'},
        {key : 'August', value: 'August'},
        {key : 'September', value: 'September'},
        {key : 'October', value: 'October'},
        {key : 'November', value: 'November'},
        {key : 'December', value: 'December'}
    ];
    
    $scope.vpForm.selectMonth = function (inputIndex, month) {
        var inputIndex = parseInt(inputIndex);
        var selectedInput = $scope.vpForm.inputs[inputIndex];

        selectedInput.value = month;

        jQuery('.vp-form-input-list').find('.vp-input').eq(inputIndex).find('input').focus();

    }
    /** end: AUTO-FILL MONTHS **/


    /** start: RATING **/
    $scope.vpForm.setRatingValue = function(value) {
        var item = $scope.vpForm.inputs[parseInt($scope.vpForm.activeItem)];
        var value = parseInt(value);

        item.value = value;

        $scope.vpForm.jumpToNextInput();
    }
    /** end: RATING **/




    /** start: RADIO **/
    $scope.vpForm.setRadioValue = function(optionIndex) {
        var itemIndex = parseInt($scope.vpForm.activeItem),
            optionIndex = parseInt(optionIndex);

        var item = $scope.vpForm.inputs[itemIndex],
            option = item.options[optionIndex];

        // set item value
        item.value = option.value;
        // set active option
        item.activeOption = optionIndex;

        if(option.customValueEnabled) {
            // set data receive mode yes
            option.isDataReceiving = "yes";

            // set default custom value
            option.customValue = option.value;
        } else {
            // jump to next input
            jumpToNextInput();
        }
    }

    $scope.vpForm.updateRadioOptionValue = function($event, optionIndex) {
        $event.stopPropagation();

        var itemIndex = parseInt($scope.vpForm.activeItem),
            optionIndex = parseInt(optionIndex);

        var item = $scope.vpForm.inputs[itemIndex],
            option = item.options[optionIndex];

        if(option.customValue) {
            // set item value
            item.value = option.customValue;
            // update option value
            option.value = option.customValue;
            option.label = option.customValue;
            // set date receive mode no
            option.isDataReceiving = "no";

            $scope.vpForm.jumpToNextInput();
        }

    }
    /** end: RADIO **/



    /** start: CHECKBOX **/
    $scope.vpForm.setCheckboxValue = function (optionIndex) {
        var itemIndex = parseInt($scope.vpForm.activeItem),
            optionIndex = parseInt(optionIndex);

        var item = $scope.vpForm.inputs[itemIndex],
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
    }

    $scope.vpForm.updateCheckboxOptionValue = function($event, optionIndex) {
        $event.stopPropagation();

        var itemIndex = parseInt($scope.vpForm.activeItem),
            optionIndex = parseInt(optionIndex);

        var item = $scope.vpForm.inputs[itemIndex],
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


    }

    $scope.vpForm.stopPropagation = function($event) {
        $event.stopPropagation();
    }
    /** end: CHECKBOX **/




    /** start: HELPER FUNCTIONS **/
    // get number range
    $scope.vpForm.range = function(min, max, step) {
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

    // render html
    $scope.vpForm.renderHtml = function (htmlCode) {
        return $sce.trustAsHtml(htmlCode);
    };

    // on press enter jump to next input
    $scope.vpForm.pressEnter = function (event) {

        if(event.which === 13 && !event.shiftKey) {
            $scope.vpForm.jumpToNextInput();
        }
    }

    // scroll to provided item
    $scope.vpForm.scrollToItem = function (index) {
        var index = parseInt(index);

        var elem = jQuery(".vp-form-input-list").find(".vp-item").eq(index)

        jQuery('html, body').animate({
            scrollTop: jQuery(elem).offset().top - 20
        }, 'slow');
    }

    // validate input field
    $scope.vpForm.validateInput = function (index) {
        var index = parseInt(index);

        var item = $scope.vpForm.inputs[index];

        // reset item error
        $scope.vpForm.resetError(index);

        if(item.isRequired) {
            if( ! item.value ) {
                if(item.type === 'email') {
                    item.errors[item.errors.length] = {message: "A valid email address is required!"};
                } else if(item.type === 'number') {
                    item.errors[item.errors.length] = {message: "A valid number is required!"};
                } else if(item.type === 'url') {
                    item.errors[item.errors.length] = {message: "A valid url is required!"};
                } else {
                    item.errors[item.errors.length] = {message: "This filed can't be empty!"};
                }

            }
        }
    }

    // final validation before submitting form
    $scope.vpForm.finalValidation = function() {
        var items = $scope.vpForm.inputs;

        var isValid = true;

        for(var i = 0; i < items.length; i++) {
            var item = items[i];

            if(item.isRequired) {
                if( ! item.value ) {
                    item.errors = [];

                    if(item.type === 'email') {
                        item.errors[item.errors.length] = {message: "A valid email address is required!"};
                    } else if(item.type === 'number') {
                        item.errors[item.errors.length] = {message: "A valid number is required!"};
                    } else if(item.type === 'url') {
                        item.errors[item.errors.length] = {message: "A valid url is required!"};
                    } else {
                        item.errors[item.errors.length] = {message: "This filed can't be empty!"};
                    }

                    $scope.vpForm.scrollToItem(i);
                    isValid = false;
                    break;
                }
            }
        }

        return isValid;
    }

    // reset item error
    $scope.vpForm.resetError = function(index) {
        var index = parseInt(index);

        var item = $scope.vpForm.inputs[index];
        item.errors = [];
    }

    // jump to next input
    $scope.vpForm.jumpToNextInput = function () {
        var currentIndex = parseInt($scope.vpForm.activeItem),
            nextIndex = parseInt(currentIndex + 1);

        var currentItem = $scope.vpForm.inputs[currentIndex];
        var nextItem = $scope.vpForm.inputs[nextIndex];

        $scope.vpForm.validateInput($scope.vpForm.activeItem);
        if(currentItem.errors.length > 0) {
            return false;
        }

        if(! nextItem ) {
            if($scope.vpForm.inputs.length === (currentIndex + 1)) {
                if($scope.vpForm.finalValidation()) {
                    jQuery('#vp-form').submit();
                }
            }

            return false;
        }

        $scope.vpForm.scrollToItem(nextIndex);
    }

    // jump to prev input
    $scope.vpForm.jumpToPrevInput = function () {
        var prevIndex = parseInt($scope.vpForm.activeItem) - 1;

        var prevItem = $scope.vpForm.inputs[prevIndex];

        if(prevItem) {
            $scope.vpForm.scrollToItem(prevIndex);
        }
    }

    // calculate form percent completed
    $scope.vpForm.calculatePercentCompleted = function() {
        var totalItem = parseInt($scope.vpForm.inputs.length),
            currentItem = parseInt($scope.vpForm.activeItem);

        $scope.vpForm.percentCompleted = Math.ceil((100 * currentItem) / totalItem);
        if(currentItem === totalItem - 1) {
            $scope.vpForm.percentCompleted = 100;
        }
        $scope.$digest();
    }

    // take action on active item based on keypress
    jQuery(document).bind("keypress", function(event) {

        var item = $scope.vpForm.inputs[parseInt($scope.vpForm.activeItem)];
        if(item.type === 'rating' || item.type === 'rating-number') {
            var key = parseInt(event.key);

            if(key > 0 && key <= item.itemCount ) {
                $scope.vpForm.setRatingValue(key);
            }
        }

        if(item.type === 'radio' || item.type === 'radio-vertical' || item.type === 'checkbox' || item.type === 'checkbox-vertical') {


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

                    var optionIndex = "";

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


                    if(item.type === 'radio' || item.type === 'radio-vertical') {
                        $scope.vpForm.setRadioValue(optionIndex);
                    } else if (item.type === 'checkbox' || item.type === 'checkbox-vertical') {
                        $scope.vpForm.setCheckboxValue(optionIndex);
                    }
                }
            } // end if - is key tracking
        }
    });
    /** end: HELPER FUNCTIONS **/






    /** start: INITIALIZE THE FORM **/
    window.initVpForm = function(inputs) {
        inputs.forEach(function(input, index) {
            var mInput = input;
            mInput.label = $scope.vpForm.renderHtml(mInput.label);

            $scope.vpForm.inputs[$scope.vpForm.inputs.length] = mInput;
        });

        $scope.vpForm.inputs = inputs;
    }

    window.setVpFormActiveItem = function(index) {
        $scope.vpForm.activeItem = parseInt(index);

        $scope.vpForm.calculatePercentCompleted();
    }

    window.getVpFormActiveItem = function () {
        return $scope.vpForm.activeItem;
    }

    window.jumpToNextInput = function () {
        $scope.vpForm.jumpToNextInput();
    }
    /** end: INITIALIZE THE FORM **/

}]);


vpForm.directive("fileinput", [function() {
    return {
        scope: {
            fileinput: "=",
            filepreview: "="
        },
        link: function(scope, element, attributes) {
            element.bind("change", function(changeEvent) {
                scope.fileinput = changeEvent.target.files[0];
                var reader = new FileReader();
                reader.onload = function(loadEvent) {
                    scope.$apply(function() {
                        scope.filepreview = loadEvent.target.result;
                    });
                }
                reader.readAsDataURL(scope.fileinput);

                window.jumpToNextInput();
            });
        }
    }
}]);

vpForm.directive('contenteditable', ['$sce', function($sce) {
    return {
        restrict: 'A', // only activate on element attribute
        require: '?ngModel', // get a hold of NgModelController
        link: function(scope, element, attrs, ngModel) {
            if (!ngModel) return; // do nothing if no ng-model

            // Specify how UI should be updated
            // ngModel.$render = function() {
            //     element.html($sce.getTrustedHtml(ngModel.$viewValue || ''));
            //     read(); // initialize
            // };

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
/** end: ANGULAR JS **/





/** start: ACTIVATE WINDOWS.JS **/
jQuery(document).ready(function() {
    setTimeout( function(){
        jQuery('.vp-item').windows({
            snapping: false,
            snapSpeed: 500,
            snapInterval: 1100,
            onScroll: function(scrollPos){
                // scrollPos:Number
                var cwindow = jQuery('.vp-item').getCurrentWindow();

                // add class active to current item
                jQuery('.vp-item').removeClass('vp-form-active');
                jQuery(cwindow).addClass('vp-form-active');

                // focus active item
                jQuery(cwindow).find('input, textarea').focus();


                var activeIndex = parseInt(jQuery(cwindow).attr('data-index'));
                window.setVpFormActiveItem(activeIndex);


                console.log("Active Index: "+window.getVpFormActiveItem());
            },
            onSnapComplete: function($el){
                // after window ($el) snaps into place
            },
            onWindowEnter: function($el){
                // when new window ($el) enters viewport
            }
        });

        jQuery('.vp-form-input-list').find('.vp-item:first-child')
            .addClass('vp-form-active')
            .find('input, textarea').focus();



    }  , 1000 );




})
/** end: ACTIVATE WINDOWS.US **/




