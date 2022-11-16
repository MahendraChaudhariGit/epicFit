var prevoiusHeight = '',
    prevoiusWeight = '',
    isSetPreHight = false,
    isSetPreWeight = false;

$(document).ready(function () {
    var priceField = $('.price-field');
    if (priceField.length) {
        $(document).on("keydown", '.price-field', function (e) {
            if ($(this).val().indexOf('.') !== -1 && e.keyCode == 190)
                return false;

            if (!((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode == 8 || e.keyCode == 190))
                return false;
        });
    }
    $('.baccordion').click(function () {
        var $myGroup = $('.accordion-div');
        $myGroup.on('show.bs.collapse', '.collapse', function () {
            $myGroup.find('.collapse.in').collapse('hide');
        });
    });
});
var FormWizardBenchMark = function () {
    var wizardContent = "";
    var wizardForm = '#form';
    var numberOfSteps = $('.swMain > ul > li').length;
    var ifSendAjax = false;

    var initWizard = function () {
        // function to initiate Wizard Form
        wizardContent.smartWizard({
            //beginReset:true,
            keyNavigation: false,
            onLeaveStep: leaveAStepCallback,
            onShowStep: onShowStep//,
                    //selected: 0,
        });
        var numberOfSteps = 0;
        initValidator();

    }


    $('#prevHeight').change(function () {
        if(!isSetPreHight){
            var checked = $(this).is(":checked");
            var clientId = $('.client-id').val();
            var benchmarkId = $('input[name=benchmarkEditId]').val();
            if (checked) {
                if (prevoiusHeight == '' || prevoiusHeight == undefined) {
                    getClientInfo(clientId, 'height', benchmarkId);
                    var formGroup = $(this).closest('.form-group');
                    formGroup.removeClass('has-error');
                    formGroup.find('span.help-block').hide();
                } else {
                    $('#bm_height').val(prevoiusHeight);
                }
            } else {
                $('#bm_height').val('');
            }
        }

        if($(this).is(":checked"))
            isSetPreHight = true; 
        else
            isSetPreHight = false; 
    });


    /*$("#bm_time_day").datepicker({ dateFormat: 'dd M yy' }).bind("change",function(){
     $( "#bm_time_day" ).datepicker({ dateFormat: 'yy-mm-dd' });
     var minValue = $(this).val();
     minValue = $.datepicker.parseDate("dd M yy", minValue);
     minValue.setDate(minValue.getDate()+1);
     $("#bm_time_day").datepicker( "option", "minDate", minValue );
     })
     $('#bm_time_day').click(function(){
     var popup =$(this).offset();
     var popupTop = popup.top - 40;
     $('.ui-datepicker').css({
     'top' : popupTop
     });
     });*/

    $('#bm_time_day').change(function (e) {
        e.preventDefault();
        var formGroup = $(this).closest('.form-group');
        formGroup.removeClass('has-error');
        formGroup.find('span.error').hide();
    });

    $(".time_hour").change(function (e) {
        e.preventDefault();
        var formGroup = $(this).closest('.form-group');
        formGroup.removeClass('has-error');
        $(this).removeClass('has-error');
        //formGroup.find('span.help-block').hide();
        //$(this).addClass('has-success');

    });
    $(".time_min").change(function (e) {
        e.preventDefault();
        $(this).removeClass('has-error');
        //$(this).addClass('has-success');
    });

    $('#bm_pressups').keyup(function (e) {
        if ((validateValue($('#form-4').find(this))))
            $('#form-4').find(this).siblings('.error').text('');
    });
    $('#bm_plank').keyup(function (e) {
        if ((validateValue($('#form-4').find(this))))
            $('#form-4').find(this).siblings('.error').text('');
    });
    $('#bm_timetrial3k').keyup(function (e) {
        if ((validateValue($('#form-4').find(this))))
            $('#form-4').find(this).siblings('.error').text('');
    });
    $('#bm_bpm1').keyup(function (e) {
        if ((validateValue($('#form-4').find(this))))
            $('#form-4').find(this).siblings('.error').text('');
    });
    $('#bm_bpm2').keyup(function (e) {
        if ((validateValue($('#form-4').find(this))))
            $('#form-4').find(this).siblings('.error').text('');
    });
    $('#bm_bpm3').keyup(function (e) {
        if ((validateValue($('#form-4').find(this))))
            $('#form-4').find(this).siblings('.error').text('');
    });
    $('#bm_bpm4').keyup(function (e) {
        if ((validateValue($('#form-4').find(this))))
            $('#form-4').find(this).siblings('.error').text('');
    });
    $('#bm_bpm5').keyup(function (e) {
        if ((validateValue($('#form-4').find(this))))
            $('#form-4').find(this).siblings('.error').text('');
    });
    $('#bm_bpm6').keyup(function (e) {
        if ((validateValue($('#form-4').find(this))))
            $('#form-4').find(this).siblings('.error').text('');
    });

    $('#prevWeight').change(function () {
        if(!isSetPreWeight){
            var clientId = $('.client-id').val();
            var checked = $(this).is(":checked");
            var benchmarkId = $('input[name=benchmarkEditId]').val();
            if (checked) {
                if (prevoiusWeight == '' || prevoiusWeight == undefined) {
                    getClientInfo(clientId, 'weight', benchmarkId);
                    var formGroup = $(this).closest('.form-group');
                    formGroup.removeClass('has-error');
                    formGroup.find('span.help-block').hide();
                }
                $('#bm_weight').val(prevoiusWeight);
            } else {
                $('#bm_weight').val('');
            }
        }
        if($(this).is(":checked"))
            isSetPreWeight = true; 
        else
            isSetPreWeight = false; 

    });

    $('.stress').change(function () {
        var fg = $(this).parents('.form-group'), hb = fg.find('span.help-block');
        hb.html("");
        //hb.removeClass('help-block');
        fg.removeClass('has-error');
    });

    $('.sleep').change(function () {
        var fg = $(this).parents('.form-group'), hb = fg.find('span.help-block');
        hb.html("");
        //hb.removeClass('help-block');
        fg.removeClass('has-error');
    });
    $('.hydration').change(function () {
        var fg = $(this).parents('.form-group'), hb = fg.find('span.help-block');
        hb.html("");
        //hb.removeClass('help-block');
        fg.removeClass('has-error');
    });
    $('.nutrition').change(function () {
        var fg = $(this).parents('.form-group'), hb = fg.find('span.help-block');
        hb.html("");
        //hb.removeClass('help-block');
        fg.removeClass('has-error');
    });
    $('.humidity').change(function () {
        var fg = $(this).parents('.form-group'), hb = fg.find('span.help-block');
        hb.html("");
        //hb.removeClass('help-block');
        fg.removeClass('has-error');
    });

    var initValidator = function () {
        $.validator.setDefaults({
            errorElement: "span", // contain the error msg in a span tag
            errorClass: 'help-block',
            ignore: ':hidden',
            rules: {
                //firstName: {
                //    minlength: 2,
                //    required: true
                //},
                //lastName: {
                //    minlength: 2,
                //    required: true
                //},
                // email: {
                //    required: true,
                //    email: true
                //},
                //password: {
                //    minlength: 6,
                //    required: true
                //},
                //password2: {
                //    required: true,
                //    minlength: 5,
                //    equalTo: "#password"
                //}
            },
            messages: {
                firstName: "First Name is required",
                lastName: "Last Name is required"
            },
            highlight: function (element) {
                $(element).closest('.help-block').removeClass('valid');
                // display OK icon
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required');
                // add the Bootstrap error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error');
                // set error class to the control group
            },
            success: function (label, element) {
                label.addClass('help-block valid');
                // mark the current input as valid and display OK icon
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success').find('.symbol').removeClass('required').addClass('ok');
            }
        });
    };



    var displayConfirm = function () {
        $('.display-value', form).each(function () {
            var input = $('[name="' + $(this).attr("data-display") + '"]', form);
            if (input.attr("type") == "text" || input.attr("type") == "email" || input.is("textarea")) {
                $(this).html(input.val());
            } else if (input.is("select")) {
                $(this).html(input.find('option:selected').text());
            } else if (input.is(":radio") || input.is(":checkbox")) {

                $(this).html(input.filter(":checked").closest('label').text());
            } else if ($(this).attr("data-display") == 'card_expiry') {
                $(this).html($('[name="card_expiry_mm"]', form).val() + '/' + $('[name="card_expiry_yyyy"]', form).val());
            }
        });
    };
    var onShowStep = function (obj, context) {
        if (context.toStep == numberOfSteps) {
            $('.anchor').children("li:nth-child(" + context.toStep + ")").children("a").removeClass('wait');
            //displayConfirm();
        }
        $(".bm_next-step").unbind("click").click(function (e) {
            e.preventDefault();
            ifSendAjax = true;
            wizardContent.smartWizard("goForward");
        });
        $(".bm_back-step").unbind("click").click(function (e) {
            e.preventDefault();
            wizardContent.smartWizard("goBackward");
        });
        $("select[name='bm_time_hour']").change(function (e) {
            isValid = true;
            $('.time_hour').find('.hour-error').text('');

        });
        $("select[name='bm_time_min']").change(function (e) {
            isValid = true;
            $('.time_min').find('.min-error').text('');

        });
        $(".bm_finish-step").unbind("click").click(function (e) {
            var isValid = true;
            e.preventDefault();
            if (!(validateValue($('#form-4').find("input[name='bm_pressups']"))))
                isValid = false;
            if (!(validateValue($('#form-4').find("input[name='bm_plank']"))))
                isValid = false;
            if (!(validateValue($('#form-4').find("input[name='bm_timetrial3k']"))))
                isValid = false;
            if (!(validateValue($('#form-4').find("input[name='bm_bpm1']"))))
                isValid = false;
            if (!(validateValue($('#form-4').find("input[name='bm_bpm2']"))))
                isValid = false;
            if (!(validateValue($('#form-4').find("input[name='bm_bpm3']"))))
                isValid = false;
            if (!(validateValue($('#form-4').find("input[name='bm_bpm4']"))))
                isValid = false;
            if (!(validateValue($('#form-4').find("input[name='bm_bpm5']"))))
                isValid = false;
            if (!(validateValue($('#form-4').find("input[name='bm_bpm6']"))))
                isValid = false;
            if (isValid == false) {
                return true;
            } else if (isValid) {
                var requestUrl = $('#form-4').attr('action') + '/lastbenchmark';
                var data = $('#form-4').serialize();
                //alert(data);
                $.ajax({
                    url: requestUrl,
                    //method: "POST",
                    data: {'data': data},
                    success: function (response) {
                        var resp = JSON.parse(response);
                        if (resp.status == 'success') {
                            //window.location.href = window.location.origin + "/epicman/dashboard.php";
                            //location.reload();
                            //saveSuccess($('#benchmarks'), {action:'show', type:'store'});
                            reloadPageWithTab("#benchmarks");
                        }
                    }
                });
            }
        });
    };
    var leaveAStepCallback = function (obj, context, last) {
        console.log('ok');
        var formId = wizardForm + '-' + context.fromStep,
                fw = $(formId),
                msgElem = fw.find('.sucMes'),
                isNextStepDone = $('a[href="#step-' + context.toStep + '"]').hasClass('done');

        if (!msgElem.hasClass('hidden'))
            msgElem.addClass('hidden');
        if (last) {
            ifSendAjax = true;
        }
        if (ifSendAjax) {
            ifSendAjax = false;
            var isValid = validateSteps(context.fromStep, context.toStep, fw);
            var progression = fw.find(".ui-selected").text();
            if (context.fromStep == '1') {
                if (progression == 'Manual Time Entry') {
                    if (!(validateValue(fw.find("input[name='bm_time_day']"))))
                        isValid = false;
                    if (!(validateSelectValue(fw.find(".time_hour")))) {
                        isValid = false;
                        $('.time_hour').find('.hour-error').text('This field is required');
                        $('.time_hour').addClass('has-error');
                    }
                    if (!(validateSelectValue(fw.find(".time_min")))) {
                        isValid = false;
                        $('.time_min').find('.min-error').text('This field is required');
                        $('.time_min').addClass('has-error');
                    }
                }
            } else if (context.fromStep == '2') {
                if (!(validateStarValue(fw.find(".stress"))))
                    isValid = false;
                if (!(validateStarValue(fw.find(".sleep"))))
                    isValid = false;
                if (!(validateStarValue(fw.find(".nutrition"))))
                    isValid = false;
                if (!(validateStarValue(fw.find(".hydration"))))
                    isValid = false;
                if (!(validateStarValue(fw.find(".humidity"))))
                    isValid = false;
                if (!(validateStarValue(fw.find("select[name='bm_temp']"))))
                    isValid = false;


            } else if (context.fromStep == '3') {
                if (!(validateValue(fw.find("input[name='bm_waist']"))))
                    isValid = false;
                if (!(validateValue(fw.find("input[name='bm_hips']"))))
                    isValid = false;
                if (!(validateValue(fw.find("input[name='bm_height']"))))
                    isValid = false;
                if (!(validateValue(fw.find("input[name='bm_weight']"))))
                    isValid = false;
            } else if (context.fromStep == '4') {
                if (!(validateValue(fw.find("input[name='bm_pressups']"))))
                    isValid = false;
                if (!(validateValue(fw.find("input[name='bm_plank']"))))
                    isValid = false;
                if (!(validateValue(fw.find("input[name='bm_timetrial3']"))))
                    isValid = false;
                if (!(validateValue(fw.find("input[name='bm_bpm1']"))))
                    isValid = false;
                if (!(validateValue(fw.find("input[name='bm_bpm2']"))))
                    isValid = false;
                if (!(validateValue(fw.find("input[name='bm_bpm3']"))))
                    isValid = false;
                if (!(validateValue(fw.find("input[name='bm_bpm4']"))))
                    isValid = false;
                if (!(validateValue(fw.find("input[name='bm_bpm5']"))))
                    isValid = false;
                if (!(validateValue(fw.find("input[name='bm_bpm6']"))))
                    isValid = false;
            }

            if (isValid) {
                $('#hideBenchmarkForm').addClass('reloadBtn');
                var form = $(wizardContent).find(formId),
                        requestUrl = form.attr('action') + '/benchmarked',
                        allData = $(wizardContent).find(formId).serializeArray();
                var formatedData = {};
                $(allData).each(function (key, data) {
                    var name = data['name'];
                    formatedData[name] = data['value'];
                });
                formatedData['form_no'] = context.fromStep;
                formatedData['last_insert_id'] = $('#last-insert-id-bm').val();
                formatedData['client_id'] = $('.client-id').val();
                formatedData['stress'] = $('.stress').val();
                formatedData['sleep'] = $('.sleep').val();
                formatedData['nutrition'] = $('.nutrition').val();
                formatedData['hydration'] = $('.hydration').val();
                formatedData['humidity'] = $('.humidity ').val();
                formatedData['banchmarkId'] = $('input[name=benchmarkEditId]').val();
                //alert('abhi '+requestUrl);
                //console.log(formatedData);return false;
                $.ajax({
                    url: requestUrl,
                    method: "POST",
                    data: formatedData,
                    success: function (data) {
                        
                        var data = JSON.parse(data);
                        if (data.status == "success") {

                            if (data.benchmark != '') {

                                $('#last-insert-id-bm').val(data.benchmark);
                            }

                            if (context.fromStep == '1') {
                                $("input.businessId").val(data.insertId);

                                var step2Form = $('#form-2');
                                var addrOne = step2Form.find("input[name='address_line_one']");
                                var addrTwo = step2Form.find("input[name='address_line_two']");
                                var city = step2Form.find("input[name='city']");
                                var country = step2Form.find("select[name='country']");
                                var state = step2Form.find("select.states");
                                var postalCode = step2Form.find("input[name='postal_code']");

                                if (formatedData.venue_location && formatedData.venue_location == '1') {
                                    addrOne.val(formatedData.address_line_one);
                                    addrTwo.val(formatedData.address_line_two);
                                    city.val(formatedData.city);
                                    country.val(formatedData.country);
                                    state.data('selected', formatedData.state);
                                    postalCode.val(formatedData.postal_code);
                                } else {
                                    addrOne.val();
                                    addrTwo.val('');
                                    city.val('');
                                    country.val('');
                                    state.data('selected', '');
                                    postalCode.val('');
                                }
                                country.trigger("changed");
                            } else if (context.fromStep == '2') {
                                if (formatedData.venue == 'Location') {
                                    updateRelatedDd($('select.location'), data.insertId, formatedData.location_training_area);

                                    var step7Form = $('#form-7');
                                    $(step7Form).find("input[name='address_line_one']").val(formatedData.address_line_one);
                                    $(step7Form).find("input[name='address_line_two']").val(formatedData.address_line_two);
                                    $(step7Form).find("input[name='city']").val(formatedData.city);
                                    $(step7Form).find("select[name='country']").val(formatedData.country);
                                    $(step7Form).find("select[name='country']").trigger("changed");
                                    $(step7Form).find("select.states").data('selected', formatedData.state);
                                    $(step7Form).find("input[name='postal_code']").val(formatedData.postal_code);
                                }
                            } else if (context.fromStep == '3')
                                updateRelatedDd($('select.staff'), data.insertId, formatedData.first_name + ' ' + formatedData.last_name);

                            $('select').selectpicker('refresh');

                            jumpToNextStep(fw, wizardForm, context);

                        } else if (data.status == "emailExistError") {
                            var $emailField = fw.find("input[name='email']");
                            var $emailFormGroup = $emailField.closest('.form-group');
                            setFieldInvalid($emailFormGroup, $emailField.next("span"), data.message)

                            wizardContent.smartWizard("goBackward");

                            if (!isNextStepDone)
                                setTimeout(function () {
                                    wizardContent.smartWizard('disableStep', context.toStep)
                                }, 300);
                        }
                    }
                });
                return true
            } else
                return false;
        } else {
            jumpToNextStep(fw, wizardForm, context);
            return true;
        }
    };
    var onFinish = function (obj, context) {
        if (validateAllSteps()) {
            // alert('form submit function');
            $('.anchor').children("li").last().children("a").removeClass('wait').removeClass('selected').addClass('done').children('.stepNumber').addClass('animated tada');
            //wizardForm.submit();
        }
    };
    var prepareMultiSel = function ($elem) {
        var val = $elem.val();
        if (val != null)
            return val;
        return '';
    }
    var jumpToNextStep = function (fw, wizardForm, context) {
        //scrollToTop(fw);
        var epicToStep = wizardForm + '-' + context.toStep;
        $(epicToStep).closest('.panel').find('.panel-heading  .fa-chevron-down', '.panel-heading  .fa-chevron-up').trigger('click');
        $(wizardForm + '-' + context.fromStep).closest('.panel').find('.panel-heading  .icon-group-left i').attr('class', 'fa fa-check');
        saveSuccess($(wizardForm + '-' + context.fromStep), {action: 'show', type: 'store'});
        //console.log($(wizardForm + '-' +context.fromStep));
    }
    /*var scrollToTop = function ($elem) {
     $("body, html").animate({ 
     scrollTop:$elem.closest('.panel').offset().top 
     }, 200);
     }*/
    var updateRelatedDd = function ($dd, value, text) {
        $dd.append('<option value="' + value + '">' + text + '</option>');
        $dd.selectpicker('refresh');
    }
    var validateUpload = function ($uploadField) {
        var uploadVal = $uploadField.val();
        var formGroup = $uploadField.closest('.form-group');
        if (uploadVal == null || uploadVal == "") {
            setFieldInvalid(formGroup, formGroup.find("span.help-block"), 'Please upload a proper file.')
            return false;
        } else {
            setFieldValid(formGroup);
            return true;
        }
    }
    var validateUrl = function ($urlField) {
        var urlVal = $.trim($urlField.val());
        //if (urlVal != null && urlVal != "" && urlVal.indexOf("www.") < 0) {
        if (urlVal != null && urlVal != "" && !(/^[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9](?:\.[a-zA-Z]{2,})+$/.test(urlVal))) {
            setFieldInvalid($urlField.closest('.form-group'), $urlField.next("span"), 'Please enter a valid URL.')
            return false;
        }
        return true;
    }
    var validateStarValue = function ($valueField) {
        var fieldVal = $.trim($valueField.val());
        if (fieldVal == null || fieldVal == "") {
            setFieldInvalid($valueField.closest('.form-group'), $valueField.next("span").next("span"), 'This field is required.');
            return false;
        } else {
            $valueField.closest('.form-group').removeClass('has-error');
            //$valueField.removeClass('has-error');
            //setFieldInvalid($valueField.closest('.form-group'), $valueField.next("span"), '');
            return true;
        }

    }
    var validateValue = function ($valueField) {
        var fieldVal = $.trim($valueField.val());
        if (fieldVal == null || fieldVal == "") {
            setFieldInvalid($valueField.closest('.form-group'), $valueField.next("span"), 'This field is required.');
            return false;
        } else {
            $valueField.closest('.form-group').removeClass('has-error');
            //$valueField.removeClass('has-error');
            //setFieldInvalid($valueField.closest('.form-group'), $valueField.next("span"), '');
            return true;
        }

    }
    var validateSelectValue = function ($valueField) {
        var fieldVal = $.trim($valueField.find('.btn-default').attr('title'));
        if (fieldVal == 'HOUR' || fieldVal == "MINUTES") {
            //setFieldInvalid($valueField.closest('.form-group'), $valueField.next("span"), 'This field is required.');
            return false;
        } else {
            $valueField.closest('.form-group').removeClass('has-error');
            //$valueField.removeClass('has-error');
            //setFieldInvalid($valueField.closest('.form-group'), $valueField.next("span"), '');
            return true;
        }

    }
    var setFieldInvalid = function ($formGroup, $helpBlock, helpBlockMsg) {
        if ($formGroup.hasClass('has-success'))
            $formGroup.removeClass('has-success')
        $formGroup.addClass('has-error');
        $helpBlock.html(helpBlockMsg);
        $helpBlock.show();
    }
    var setFieldValid = function ($formGroup) {
        if ($formGroup.hasClass('has-error'))
            $formGroup.removeClass('has-error')
        $formGroup.addClass('has-success');
    }
    var validateSteps = function (stepnumber, nextstep, fw) {
        var isStepValid = false;
        var wizardForm = fw;
        wizardForm.validate().focusInvalid();

        if (numberOfSteps >= nextstep && nextstep > stepnumber) {

            // cache the form element selector
            if (wizardForm.valid()) { // validate the form
                for (var i = stepnumber; i <= nextstep; i++) {
                    $('.anchor').children("li:nth-child(" + i + ")").not("li:nth-child(" + nextstep + ")").children("a").removeClass('wait').addClass('done').children('.stepNumber').addClass('animated tada');
                }
                //focus the invalid fields
                isStepValid = true;
                return true;
            }
            ;
        } else if (nextstep < stepnumber) {
            for (i = nextstep; i <= stepnumber; i++) {
                $('.anchor').children("li:nth-child(" + i + ")").children("a").addClass('wait').children('.stepNumber').removeClass('animated tada');
            }

            return true;
        }
    };

    var leaveAStepCallbackLast = function (obj, context, finalStep, fromStep) {
        if (finalStep == undefined)
            finalStep = false;
        if (fromStep == undefined)
            fromStep = '';

        var ifSafeToSwitch = false;
        if (!ifSendAjax) {
            ifSafeToSwitch = true;
            scrollToTop(obj.attr('href'));
        } else {
            ifSendAjax = false;
            if (fromStep && validateSteps(fromStep, fromStep)) {
                submitStepData(obj, fromStep, '', true);
                ifSafeToSwitch = true;
            } else if (!fromStep && finalStep && validateSteps(5, 5)) {
                if ($("#finish-parq").data('submit') == true)
                    submitStepData(obj, 5);
                else
                    $("#waiverModal").modal();

                ifSafeToSwitch = true;
            } else if (!fromStep && !finalStep && validateSteps(context.fromStep, context.toStep)) {
                submitStepData(obj, context.fromStep, context.toStep);
                ifSafeToSwitch = true;
            }
        }

        return ifSafeToSwitch;
    };

    function getClientInfo(id, para, benchmarkId) {
        var requestUrl = $('#form-4').attr('action') + '/getClientInfo';
        $.ajax({
            url: requestUrl,
            method: "POST",
            data: {'id': id, 'benchmarkId': benchmarkId},
            success: function (response) {
                var resp = JSON.parse(response);
                if (resp.status == 'success') {
                    var height = resp.height.match(/\d+/);
                    var weight = resp.weight.match(/\d+/);
                    //var totalweight = parseFloat(.45 * weight);
                    //var totalheight = parseFloat(30.48 * height);
                    if (para == 'height') {
                        //prevoiusHeight = totalheight;
                        $('#bm_height').val(height);
                        prevoiusHeight = height;

                    } else {
                        prevoiusWeight = weight;
                        $('#bm_weight').val(weight);
                    }
                }
            }
        });
    }
    ;

    var validateAllSteps = function () {
        var isStepValid = true;
        // all step validation logic
        return isStepValid;
    };

    /* Reset field style 
     function setFieldNeutral($elem){
     var currentId = "$('"+$elem+"')";
     //console.log(currentId);
     var formGroup = currentId.closest('.form-group');
     formGroup.removeClass('has-error error hour-error');
     formGroup.find('span.help-block').hide();
     }*/

    //go to step 1


    return {
        init: function (wizard) {
            wizardContent = $(wizard);
            initWizard();
        }
    };


}();


