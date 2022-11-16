

function displayAlert(alertMessage, type, location, pos) {
    //$('#alert_show').click(function(){

    Metronic.alert({
        container: location, //$('#alert_container').val(), // alerts parent container(by default placed after the page breadcrumbs)
        place: pos, //$('#alert_place').val(), // append or prepent in container 
        type: type, //$('#alert_type').val(),  // alert's type
        message: alertMessage, //$('#alert_message').val(),  // alert's message
        close: true, //$('#alert_close').is(":checked"), // make alert closable
        reset: false, //$('#alert_reset').is(":checked"), // close all previouse alerts first
        focus: true, //$('#alert_focus').is(":checked"), // auto scroll to the alert after shown
        closeInSeconds: 5, //$('#alert_close_in_seconds').val(), // auto close after defined seconds
        icon: ''//$('#alert_icon').val() // put icon before the message
    });

    //});
}
jQuery(document).ready(function () {
    
    //Metronic.init();
    //Main.init();
//    UINestable.init();

    jQuery(document).on('click', '.view-benchmark', function () {
        jQuery('.benchmark-details').css('display', 'none');
        jQuery('.close-benchmark-view').css('display', 'block');
        jQuery('.view-benchmark').css('display', 'none');
        var benchmarkId = jQuery(this).attr('benchmark-id');
        jQuery('#benchmark-details-' + benchmarkId).css('display', 'block');
    });
    jQuery(document).on('click', '.close-benchmark-view', function () {
        jQuery('.close-benchmark-view').css('display', 'none');
        jQuery('.view-benchmark').css('display', 'inline-block');
        jQuery('.benchmark-details').css('display', 'none');
    });
    jQuery('.benchmarkAutoTime').click(function () {
        jQuery('.benchmarkTimeManual').toggle();
        jQuery('.benchmarkAutoTime').hide();
        jQuery('.benchmarkManualTime').show();
        jQuery('.benchmarkDay').val("");
        jQuery('.benchmarkHour').val("");
        jQuery('.benchmarkMinute').val("");
        jQuery('#time_type').val("auto");
    });
    jQuery('.benchmarkManualTime').click(function () {
        jQuery('#time_type').val("manual");
        jQuery('.benchmarkTimeManual').toggle();
        jQuery('.benchmarkManualTime').hide();
        jQuery('.benchmarkAutoTime').show();
        jQuery('.benchmarkDay').datepicker();
        //jQuery(this).trigger("select");
    });
    jQuery('.extraBenchmarks').click(function () {
        jQuery('.extraMeasurements').toggle();
    });
    /*
     jQuery('#showBenchmarkBox').click(function(){
     clearForm($('#form-1'));
     clearForm($('#form-2'));
     clearForm($('#form-3'));
     clearForm($('#form-4'));
     jQuery('#benchmarkWizard').find('select').selectpicker('refresh');
     
     jQuery(this).hide();
     jQuery('#benchmarkWizard').smartWizard("goToStep", 1);
     toggleBmTimeFields($('.bm_time_selectable'));
     jQuery('.clear-rating').trigger('click');
     jQuery('#hideBenchmarkBox').show();
     jQuery('#createBenchmark').show();
     });
     jQuery('#hideBenchmarkBox').click(function(){
     //jQuery('#benchmarkWizard').find('.rating-symbol-background').removeClass('fa-star');
     jQuery(this).hide();
     jQuery('#showBenchmarkBox').show();
     jQuery('#createBenchmark').hide();
     });
     */
    jQuery('.add-benchmark-submit').click(function (form) {
        form.preventDefault();
        jQuery('.add-benchmark-submit i').show();
        jQuery(this).addClass('disabled');
        jQuery('.benchmarkError').hide();
        jQuery('.benchmarkDayError').hide();
        jQuery('.benchmarkHourError').hide();
        jQuery('.benchmarkMinError').hide();

        jQuery('.benchmarkDay').css('border', '1px solid #eee');
        jQuery('.benchmarkHour').css('border', '1px solid #eee');
        jQuery('.benchmarkMinute').css('border', '1px solid #eee');
        jQuery('.measurements_waist').css('border', '1px solid #eee');
        jQuery('.measurements_hips').css('border', '1px solid #eee');
        jQuery('.measurements_height').css('border', '1px solid #eee');
        jQuery('.measurements_weight').css('border', '1px solid #eee');

        //console.log('add-benchmark-submit clicked');
        var formData = {}
        jQuery.each(jQuery('.benchmark-save').serializeArray(), function (i, obj) {
            formData[obj.name] = obj.value;
        });

        var time_type = jQuery('#time_type').val();
        var time_error = false;
        var time_msg = {};

        if (time_type == 'manual') {
            if (jQuery('.benchmarkDay').val() == null || jQuery('.benchmarkDay').val() == "") {
                jQuery('.benchmarkError').show();
                jQuery('.benchmarkDayError').show();
                //console.log('day empty');
                time_error = true;
                jQuery('.benchmarkDay').css('border', '1px solid red');
                time_msg['error'] = time_msg['error'] + 'Check day value';
            }
            if (jQuery('.benchmarkHour').val() == null || jQuery('.benchmarkHour').val() == "") {
                //console.log('hour empty');
                jQuery('.benchmarkError').show();
                jQuery('.benchmarkHourError').show();
                time_error = true;
                jQuery('.benchmarkHour').css('border', '1px solid red');
                time_msg['error'] = time_msg['error'] + ' Check hour value';
            }
            if (jQuery('.benchmarkMinute').val() == null || jQuery('.benchmarkMinute').val() == "") {
                //console.log('min empty');
                jQuery('.benchmarkError').show();
                jQuery('.benchmarkMinError').show();
                time_error = true;
                jQuery('.benchmarkMinute').css('border', '1px solid red');
                time_msg['error'] = time_msg['error'] + 'Check min value';
            }
        }

        var waist = jQuery('.measurements_waist').val();
        var hips = jQuery('.measurements_hips').val();
        var height = jQuery('.measurements_height').val();
        var weight = jQuery('.measurements_weight').val();

        var stress = jQuery('.benchmark_stress').val();
        var sleep = jQuery('.benchmark_sleep').val();
        var nutrition = jQuery('.benchmark_nutrition').val();
        var hydration = jQuery('.benchmark_hydration').val();
        var humidity = jQuery('.benchmark_humidity').val();
        /********/
        if (stress == '') {
            //console.log('enter stress');
            time_error = true;
            jQuery('.benchmark_stress').css('border', '1px solid red');
            jQuery('.benchmarkError').show();
            jQuery('.benchmarkStressError').show();
        }
        if (sleep == '') {
            console.log('enter sleep');
            time_error = true;
            jQuery('.benchmark_sleep').css('border', '1px solid red');
            jQuery('.benchmarkError').show();
            jQuery('.benchmarkSleepError').show();
        }
        if (nutrition == '') {
            console.log('enter nutrition');
            time_error = true;
            jQuery('.benchmark_nutrition').css('border', '1px solid red');
            jQuery('.benchmarkError').show();
            jQuery('.benchmarkNutritionError').show();
        }
        if (hydration == '') {
            console.log('enter hydration');
            time_error = true;
            jQuery('.benchmark_hydration').css('border', '1px solid red');
            jQuery('.benchmarkError').show();
            jQuery('.benchmarkHydrationError').show();
        }
        if (humidity == '') {
            console.log('enter humidity');
            time_error = true;
            jQuery('.benchmark_humidity').css('border', '1px solid red');
            jQuery('.benchmarkError').show();
            jQuery('.benchmarkHumidityError').show();
        }

        /*******/

        if (waist == '') {
            console.log('enter waist');
            time_error = true;
            jQuery('.measurements_waist').css('border', '1px solid red');
            jQuery('.benchmarkError').show();
            jQuery('.benchmarkWaistError').show();
        }

        if (hips == '') {
            console.log('enter hips');
            time_error = true;
            jQuery('.measurements_hips').css('border', '1px solid red');
            jQuery('.benchmarkError').show();
            jQuery('.benchmarkHipsError').show();
        }

        if (height == '') {
            console.log('enter height');
            time_error = true;
            jQuery('.measurements_height').css('border', '1px solid red');
            jQuery('.benchmarkError').show();
            jQuery('.benchmarkHeightError').show();
        }

        if (weight == '') {
            console.log('enter weight');
            time_error = true;
            jQuery('.measurements_weight').css('border', '1px solid red');
            jQuery('.benchmarkError').show();
            jQuery('.benchmarkWeightError').show();
        }

        if (time_error) {
            jQuery('.add-benchmark-submit i').hide();
            jQuery('.add-benchmark-submit').removeClass('disabled');
            //jQuery('.benchmarkError').show().html(JSON.stringify(time_msg['error']));
            //console.log(JSON.stringify(time_msg));
        }

        if (!time_error) {
            //console.log('no error going go submit:');
            //console.log('Sending formData: '+formData);
            jQuery.ajax({
                url: 'http://192.168.0.50/crm/public/benchmarks',
                type: 'POST',
                data: {
                    'formData': formData,
                    '_token': jQuery('input[name=_token]').val()
                },
                success: function (data) {
                    var responseMsg = $.parseJSON(data);
                    if (responseMsg.status == 'success') {
                        displayAlert('Benchmark Saved', 'success', '#displayBenchmarks', 'prepend');//null
                        jQuery('.add-benchmark-submit i').hide();
                        jQuery('.add-benchmark-submit').removeClass('disabled');
                        jQuery('.benchmarkDay').val("");
                        jQuery('.benchmarkHour').val("");
                        jQuery('.benchmarkMinute').val("");
                        jQuery('.measurements_waist').val("");
                        jQuery('.measurements_hips').val("");
                        jQuery('.measurements_height').val("");
                        jQuery('.measurements_weight').val("");
                        jQuery('.benchmark_stress').removeAttr('checked');
                        jQuery('.benchmark_sleep').removeAttr('checked');
                        jQuery('.benchmark_nutrition').removeAttr('checked');
                        jQuery('.benchmark_hydration').removeAttr('checked');
                        jQuery('.benchmark_humidity').removeAttr('checked');
                        jQuery('.measurements_pressups').val("");
                        jQuery('.measurements_plank').val("");
                        jQuery('.measurements_timetrial3k').val("");
                        jQuery('.measurements_cardiobpm1').val("");
                        jQuery('.measurements_cardiobpm2').val("");
                        jQuery('.measurements_cardiobpm3').val("");
                        jQuery('.measurements_cardiobpm4').val("");
                        jQuery('.measurements_cardiobpm5').val("");
                        jQuery('.measurements_cardiobpm6').val("");
                        jQuery('#hideBenchmarkBox').hide();
                        jQuery('#showBenchmarkBox').show();
                        jQuery('#createBenchmark').hide();

                        var current_client = $('#client_id').val();
                        //$( "#showBenchmarks" ).load( "http://epictrainer.com/showbenchmarks/"+current_client);
                        /*** load benchmarks ***/

                        jQuery.ajax({
                            url: 'http://epictrainer.com/showbenchmark/' + current_client,
                            type: 'GET',
                            data: {
                                '_token': jQuery('input[name=_token]').val()
                            },
                            success: function (loadedBenchmarks) {

                                $('#displayBenchmarks').empty().append(loadedBenchmarks);
                                /*
                                 var responseMsg = $.parseJSON(data);
                                 if(responseMsg.status == 'success'){
                                 displayAlert('Benchmark Saved','success','#displayBenchmarks','prepend');//null
                                 
                                 $( "#showBenchmarks" ).load("http://epictrainer.com/showbenchmarks/"+current_client, function(loadData) {
                                 console.log('loaded benchmarks');
                                 console.log(loadData);
                                 });        		
                                 } else {
                                 displayAlert('Error saving benchmark, please try again or contact support','warning','#displayBenchmarks','prepend');
                                 jQuery('.add-benchmark-submit i').hide();
                                 jQuery('.add-benchmark-submit').removeClass('disabled');
                                 }
                                 
                                 */
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                console.log(textStatus);
                            }
                        });
                        /*** end load benchmarks ***/

                    } else {
                        displayAlert('Error saving benchmark, please try again or contact support', 'warning', '#displayBenchmarks', 'prepend');
                        jQuery('.add-benchmark-submit i').hide();
                        jQuery('.add-benchmark-submit').removeClass('disabled');
                    }

                    //jQuery('#addClient').modal('hide');
                    console.log(data);
                    //location.reload();
                },
                error: function (jqXHR, textStatus, errorThrown) {

                }
            });

        }

    });

    $(".panel-collapse.closed").trigger("click");

//    $('#client-datatable').dataTable({'bPaginate': false});

    /*$('#client-datatable').on('init', function(){
     if(typeof cookieSlug != 'undefined' && cookieSlug != null){
     var datatableRowsCountDd = $('#client-datatable_length select');
     setDatatableRowsAmount(datatableRowsCountDd, cookieSlug);
     datatableRowsCountDd.on("change", function(){
     var cookieName = calcTable_lengthCookieName(cookieSlug); 
     $.cookie(cookieName, datatableRowsCountDd.val())
     });   
     }
     }).dataTable(/*{
     "iDisplayStart":getRecord_indexCookieVal()
     }*);
     /*$('#client-datatable').on('draw.dt', function(){
     initSelectpicker($('#client-datatable select'));
     });*/

    FormWizard.init();
    FormWizardBenchMark.init('#benchmarkWizard');
//    FormWizardGoalBuddy.init('#goalBuddyWizard');
    //runFullCalendar();
    $('.client-delete').on('click', function (e) {
        var client_id = $(this).attr('data-client-delete');
        if (client_id.length > 0) {

        }
        ;
    });
    $('#calendarDatepicker').datepicker();
    $('#calendarDatepicker').on("changeDate", function () {
        $('#myCalendarDatepicker').val(
                $('#datepicker').datepicker('getFormattedDate')
                );
    });
    //Index.init();
    jQuery("#mcfTosmc").click(function (e) {
        e.preventDefault();
        jQuery("#smc").toggle();
        jQuery("#mcf").toggle();

    });
});
		