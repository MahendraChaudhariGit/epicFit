jQuery(document).ready(function () {
    //var selected_type = 'metric';
    var selected_type = jQuery('input[name=type]:checked').val();
    var metric = jQuery('#metric');
    var result = jQuery('#result');
    var imperial = jQuery('#imperial'); 
    jQuery('input[name=type]').on('change', function () { 
        var selected_type = jQuery('input[name=type]:checked').val();
        if (selected_type === 'metric') {
            imperial.hide()
            metric.show()
            //result.hide()
        } else {
            metric.hide()
            imperial.show()
            //result.hide()
        }
    });

    jQuery('.body-mass-index input[name=type]').on('change', function () { 
        var selected_type = jQuery('input[name=type]:checked').val();
        
        jQuery.ajax({
          url: public_url+'calculators/body-mass-index/'+selected_type,
          type: 'GET',
          data: 'selected_type='+selected_type,
          success: function(res) { 
          if (!$.trim(res)){  
                    jQuery('#record_id').val('');
                     jQuery('#weight_m').val('');
                     jQuery('#height_m').val('');
                     jQuery('#weight').val('');
                     jQuery('#height_ft').val('');
                     jQuery('#height_in').val('');
                     jQuery('#result .bmi').val('');
                     jQuery('#result .classification').val('');
                     jQuery('#result .weight-range').val('');
                }
                else{           
                     jQuery('#record_id').val(res.id);
                     jQuery('#weight_m').val(res.weight);
                     jQuery('#height_m').val(res.height_ft);
                     jQuery('#weight').val(res.weight);
                     jQuery('#height_ft').val(res.height_ft);
                     jQuery('#height_in').val(res.height_in);
                     jQuery('#result .bmi').val(res.bmi);
                     jQuery('#result .classification').val(res.clasification);
                     jQuery('#result .weight-range').val(res.weight_renge);
                  }
          }
        });
    });

    jQuery(".button").click(function() {
       var selected_type = jQuery('input[name=type]:checked').val();
       var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){
            var formdata = $('#form').serialize();
            jQuery.ajax({
                url: public_url+'calculators/body-mass-index',
                type: 'post',
                data: formdata,
                success: function (res) {
                    jQuery('#result').show();
                    jQuery('#result .bmi').val(res.bmi);
                    jQuery('#result .classification').val(res.classification);
                    jQuery('#result .weight-range').val(res.weight_range);
                }
            });
        }
    });

    jQuery(".button_edit").click(function() {
       var selected_type = jQuery('input[name=type]:checked').val();
       var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){
            var formdata = $('#form').serialize();
            jQuery.ajax({
                url: public_url+'calculators/body-mass-index-update',
                type: 'post',
                data: formdata,
                success: function (res) {
                    if(res.status == "updated"){
                        $( "div.alert-success" ).css('display','block');
                        $( "div.alert-success" ).text('Record has been successfully updated.');
                    }
                    jQuery('#result').show();
                    jQuery('#result .bmi').val(res.bmi);
                    jQuery('#result .classification').val(res.classification);
                    jQuery('#result .weight-range').val(res.weight_range);
                }
            });
        }
    });

    jQuery('.basal-metabolism-rate input[type=radio]').on('change', function () { 
        var selected_type = jQuery('input[name=type]:checked').val();
        var selected_age = jQuery('input[name=gender]:checked').val();
        var selected_equation = jQuery('input[name=equation]:checked').val();
        
        jQuery.ajax({
          url: public_url+'calculators/basal-metabolism-rate/'+selected_type+'/'+selected_age+'/'+selected_equation,
          type: 'GET',
          data: 'selected_type='+selected_type+'&selected_age='+selected_age+'&selected_equation='+selected_equation,
          success: function(res) {    
            if (!$.trim(res)){  
                    jQuery('#record_id').val();
                     jQuery('#age').val();
                     jQuery('#weight_m').val();
                     jQuery('#height_m').val();
                     jQuery('#weight').val();
                     jQuery('#height_ft').val();
                     jQuery('#height_in').val();
                     jQuery('#result .bmr').val();
                }
                else{       
                     jQuery('#record_id').val(res.id);
                     jQuery('#age').val(res.age);
                     jQuery('#weight_m').val(res.weight);
                     jQuery('#height_m').val(res.height_ft);
                     jQuery('#weight').val(res.weight);
                     jQuery('#height_ft').val(res.height_ft);
                     jQuery('#height_in').val(res.height_in);
                     jQuery('#result .bmr').val(res.brm);
         }
          }
        });
    });

    jQuery(".button_basal").click(function() {
       var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){
        var formdata = $('#form').serialize();

            jQuery.ajax({
                url: public_url+'calculators/basal-metabolism-rate',
                type: 'post',
                data: formdata,
                success: function (res) {
                    $('#result').show();
                    $('#result .bmr').val(res.brm);
                }
            });
        }
    });

    jQuery(".button_basal_edit").click(function() {
       var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){
        var formdata = $('#form').serialize();

            jQuery.ajax({
                url: public_url+'calculators/basal-metabolism-rate-update',
                type: 'post',
                data: formdata,
                success: function (res) {
                    if(res.status == "updated"){
                        $( "div.alert-success" ).css('display','block');
                        $( "div.alert-success" ).text('Record has been successfully updated.');
                    }
                    $('#result').show();
                    $('#result .bmr').val(res.brm);
                }
            });
        }
    });


    jQuery('.target-heart-rate input[name=goal]').on('change', function () { 
        var selected_type = jQuery('input[name=goal]:checked').val();
        
        jQuery.ajax({
          url: public_url+'calculators/target-heart-rate/'+selected_type,
          type: 'GET',
          data: 'selected_type='+selected_type,
          success: function(res) { 
            $('#result1').show();
            $('#result2').show();
          if (!$.trim(res)){  
                      $('#record_id').val();
                     $('#age').val();
                     $('#heart_rates').val();

                     $('#result1 .bpm').val();
                     $('#result1 .bpts').val();

                     $('#result2 .mhr').val();
                     $('#result2 .mhrits').val();
                }
                else{           
                     $('#record_id').val(res.id);
                     $('#age').val(res.age);
                     $('#heart_rates').val(res.rhra);

                    $('#result1 .bpm').val(res.bpml + ' - ' + res.bpmh + ' bpm');
                    $('#result1 .bpts').val(res.bptsl + ' - ' + res.bptsh);

                    $('#result2 .mhr').val(res.mhr);
                    $('#result2 .mhrits').val(res.mhrits);
        }
          }
        });
    });


    jQuery(".button_target").click(function() {
        
        var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){
            var formdata = $('#form').serialize();

            jQuery.ajax({
                url: public_url+'calculators/target-heart-rate',
                    type: 'post',
                    data: formdata,
                success: function (res) {
                    console.log(res);
                    $('#result1').show();
                    $('#result2').show();

                    $('#result1 .bpm').val(res.bpml + ' - ' + res.bpmh + ' bpm');
                    $('#result1 .bpts').val(res.bptsl + ' - ' + res.bptsh);

                    $('#result2 .mhr').val(res.mhr);
                    $('#result2 .mhrits').val(res.mhrits);
                }
            });
        }
    });

    jQuery(".button_target_edit").click(function() {
        
        var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){
            var formdata = $('#form').serialize();

            jQuery.ajax({
                url: public_url+'calculators/target-heart-rate-update',
                    type: 'post',
                    data: formdata,
                success: function (res) {
                    if(res.status == "updated"){
                        $( "div.alert-success" ).css('display','block');
                        $( "div.alert-success" ).text('Record has been successfully updated.');
                    }
                    console.log(res);
                    $('#result1').show();
                    $('#result2').show();

                    $('#result1 .bpm').val(res.bpml + ' - ' + res.bpmh + ' bpm');
                    $('#result1 .bpts').val(res.bptsl + ' - ' + res.bptsh);

                    $('#result2 .mhr').val(res.mhr);
                    $('#result2 .mhrits').val(res.mhrits);
                }
            });
        }
    });

    jQuery('.ideal-weight input[type=radio]').on('change', function () { 
        var selected_type = jQuery('input[name=type]:checked').val();
        var selected_gender = jQuery('input[name=gender]:checked').val();
        
        jQuery.ajax({
          url: public_url+'calculators/ideal-weight/'+selected_type+'/'+selected_gender,
          type: 'GET',
          data: 'selected_type='+selected_type+'&selected_gender='+selected_gender,
          success: function(res) {           
             var unit='';

            $('#result').show();

            if (selected_type === 'metric') {
                unit = 'kg';
            } else {
                unit = 'lbs';
            }
            $('#record_id').val(res.id);
            $('#height_m').val(res.height_ft);
            $('#height_ft').val(res.height_ft);
            $('#height_in').val(res.height_in);
            if (!$.trim(res)){   
               $('#result .ideal-weight').val();
            }
            else{                   
                $('#result .ideal-weight').val(res.ideal_weight + ' ' + unit);
            }
           
          }
        });
    });

    jQuery(".button_ideal").click(function() {
        var selected_type = jQuery('input[name=type]:checked').val();
       
       var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){
            var formdata = $('#form').serialize();
            $.ajax({
                url: public_url+'calculators/ideal-weight',
                type: 'post',
                data: formdata,
                success: function (res) {
                    var unit='';

                    $('#result').show();

                    if (selected_type === 'metric') {
                        unit = 'kg';
                    } else {
                        unit = 'lbs';
                    }

                    $('#result .ideal-weight').val(res.iw + ' ' + unit);
                }
            });
        }
    });

    jQuery(".button_ideal_edit").click(function() {
        var selected_type = jQuery('input[name=type]:checked').val();
       
       var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){
            var formdata = $('#form').serialize();
            $.ajax({
                url: public_url+'calculators/ideal-weight-update',
                type: 'post',
                data: formdata,
                success: function (res) {
                    if(res.status == "updated"){
                        $( "div.alert-success" ).css('display','block');
                        $( "div.alert-success" ).text('Record has been successfully updated.');
                    }
                    var unit='';

                    $('#result').show();

                    if (selected_type === 'metric') {
                        unit = 'kg';
                    } else {
                        unit = 'lbs';
                    }

                    $('#result .ideal-weight').val(res.iw + ' ' + unit);
                }
            });
        }
    });


    jQuery('.calorie-breakdown input[type=radio]').on('change', function () { 
        var selected_gender = jQuery('input[name=gender]:checked').val();
        
        jQuery.ajax({
          url: public_url+'calculators/calorie-breakdown/'+selected_gender,
          type: 'GET',
          data: 'selected_gender='+selected_gender,
          success: function(res) {  
              $('#result').show(); 
              $('#record_id').val(res.id);       
              if (!$.trim(res)){   
                   $('#age').val();
                    $('#calorie').val();
                    $('#result .fat span').text();
                    $('#result .protein span').text();
                    $('#result .carb span').text();
                    $('#result .fiber span').text();
                    $('#result .sugar span').text();
                }
                else{                   
                
                    $('#age').val(res.age);
                    $('#calorie').val(res.calorie);
                    $('#result .fat span').text('15% - 25% ' + res.fatl + ' - ' + res.fath + ' calories');
                    $('#result .protein span').text('15% - 25% ' + res.proteinl + ' - ' + res.proteinh + ' calories');
                    $('#result .carb span').text('50% - 70% ' + res.carbohydratel + ' - ' + res.carbohydrateh + ' calories');
                    $('#result .fiber span').text(res.fiber + ' grams');
                    $('#result .sugar span').text('<25% < ' + res.sugar + ' calories');
                } 

           
          }
        });
    });


   jQuery(".button_calorie").click(function() {
        
        var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){
        var formdata = $('#form').serialize();
        $.ajax({
            url: public_url+'calculators/calorie-breakdown',
            type: 'post',
            data: formdata,
            success: function (res) {
                $('#result').show();

                $('#result .fat span').text('15% - 25% ' + res.fatl + ' - ' + res.fath + ' calories');
                $('#result .protein span').text('15% - 25% ' + res.proteinl + ' - ' + res.proteinh + ' calories');
                $('#result .carb span').text('50% - 70% ' + res.carbohydratel + ' - ' + res.carbohydrateh + ' calories');
                $('#result .fiber span').text(res.fiber + ' grams');
                $('#result .sugar span').text('<25% < ' + res.sugar + ' calories');
            }
        });
    }
    });

   jQuery(".button_calorie_edit").click(function() {
        
        var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){
        var formdata = $('#form').serialize();
        $.ajax({
            url: public_url+'calculators/calorie-breakdown-update',
            type: 'post',
            data: formdata,
            success: function (res) {
                if(res.status == "updated"){
                        $( "div.alert-success" ).css('display','block');
                        $( "div.alert-success" ).text('Record has been successfully updated.');
                    }
                $('#result').show();

                $('#result .fat span').text('15% - 25% ' + res.fatl + ' - ' + res.fath + ' calories');
                $('#result .protein span').text('15% - 25% ' + res.proteinl + ' - ' + res.proteinh + ' calories');
                $('#result .carb span').text('50% - 70% ' + res.carbohydratel + ' - ' + res.carbohydrateh + ' calories');
                $('#result .fiber span').text(res.fiber + ' grams');
                $('#result .sugar span').text('<25% < ' + res.sugar + ' calories');
            }
        });
    }
    });


    jQuery('.resting-metabolism input[type=radio]').on('change', function () { 
            var selected_type = jQuery('input[name=type]:checked').val();
            if(selected_type == 'metric'){              
                    var selected_unittype = jQuery('input[name=unit-type-m]:checked').val();
            }
            else{
                var selected_unittype = jQuery('input[name=unit-type-i]:checked').val();
            }

        jQuery.ajax({
          url: public_url+'calculators/resting-metabolism/'+selected_type+'/'+selected_unittype,
          type: 'GET',
          data: 'selected_type='+selected_type+'&selected_unittype='+selected_unittype,
          success: function(res) {  
                var unit='';
              $('#result').show(); 
              $('#record_id').val(res.id);       
              if (!$.trim(res)){   
                    $('#weight_i').val('');
                    $('#weight_m').val('');
                    $('#mass_i').val('');
                    $('#mass_m').val('');
                    $('#result .rm').val('');
                    $('#result .lm').val('');
                    $('#result .fm').val('');
                }
                else{                   

                    if (selected_type === 'metric') {
                        unit = 'kg';
                    } else {
                        unit = 'lbs';
                    }
                    $('#weight_m').val(res.weight);
                    $('#weight_i').val(res.weight);
                    $('#mass_m').val(res.lmi);
                    $('#mass_i').val(res.lmi);
                    $('#result .rm').val(res.rm + ' calories per day');
                    $('#result .lm').val(res.lm + ' ' + unit + '    (' + res.lmp + '%)');
                    $('#result .fm').val(res.fm + ' ' + unit + '    (' + res.fmp + '%)');
                    } 

           
          }
        });
    });

   jQuery(".button_resting").click(function() {
    var selected_type = jQuery('input[name=type]:checked').val();
        
        var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){
        var formdata = $('#form').serialize();
        $.ajax({
            url: public_url+'calculators/resting-metabolism',
            type: 'post',
            data: formdata,
            success: function (res) {
                var unit='';

                $('#result').show();

                if (selected_type === 'metric') {
                    unit = 'kg';
                } else {
                    unit = 'lbs';
                }

                $('#result .rm').val(res.rm + ' calories per day');
                $('#result .lm').val(res.lm + ' ' + unit + '    (' + res.lmp + '%)');
                $('#result .fm').val(res.fm + ' ' + unit + '    (' + res.fmp + '%)');
            }
        });
    }
    });

   jQuery(".button_resting_edit").click(function() {
    var selected_type = jQuery('input[name=type]:checked').val();
        
        var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){
        var formdata = $('#form').serialize();
        $.ajax({
            url: public_url+'calculators/resting-metabolism-update',
            type: 'post',
            data: formdata,
            success: function (res) {
                if(res.status == "updated"){
                        $( "div.alert-success" ).css('display','block');
                        $( "div.alert-success" ).text('Record has been successfully updated.');
                    }
                var unit='';

                $('#result').show();

                if (selected_type === 'metric') {
                    unit = 'kg';
                } else {
                    unit = 'lbs';
                }

                $('#result .rm').val(res.rm + ' calories per day');
                $('#result .lm').val(res.lm + ' ' + unit + '    (' + res.lmp + '%)');
                $('#result .fm').val(res.fm + ' ' + unit + '    (' + res.fmp + '%)');
            }
        });
    }
    });


   jQuery('.advanced-resting-metabolism input[type=radio]').on('change', function () { 
        var selected_type = jQuery('input[name=type]:checked').val();
        var selected_gender = jQuery('input[name=gender]:checked').val();

        jQuery.ajax({
          url: public_url+'calculators/advanced-resting-metabolism/'+selected_type+'/'+selected_gender,
          type: 'GET',
          data: 'selected_type='+selected_type+'&selected_gender='+selected_gender,
          success: function(res) {  
                var unit='';
              $('#result').show(); 
              $('#record_id').val(res.id);       
              if (!$.trim(res)){  
                    $('#age').val(); 
                    $('#weight').val();
                    $('#weight_m').val();
                    $('#height_m').val();
                    $('#height_ft').val();
                    $('#height_in').val();
                    $('#result .arm').val();
                }
                else{                   
                        if (selected_type === 'metric') {
                            $('#age').val(res.age); 
                            $('#weight_m').val(res.weight);
                            $('#height_m').val(res.height_ft);
                            $('#result .arm').val(res.rm + ' calories per day');
                        }
                        else{
                            $('#age').val(res.age); 
                            $('#weight').val(res.weight);
                            $('#height_ft').val(res.height_ft);
                            $('#height_in').val(res.height_in);
                            $('#result .arm').val(res.rm + ' calories per day');
                        }
                    } 

           
          }
        });
    });

   $('.button_advance').click(function() {
    var selected_type = jQuery('input[name=type]:checked').val();
    var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){
        var formdata = $('#form').serialize();
        $.ajax({
            url: public_url+'calculators/advanced-resting-metabolism',
            type: 'post',
            data: formdata,
            success: function (res) {
                $('#result').show();
                if (selected_type === 'metric') {
                    $('#result .arm').val(res.arm + ' calories per day');
                }
                else{
                    $('#result .arm').val(res.arm + ' calories per day');
                }
                
            }
        });
    }
    });

   $('.button_advance_edit').click(function() {
    var selected_type = jQuery('input[name=type]:checked').val();
    var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){
        var formdata = $('#form').serialize();
        $.ajax({
            url: public_url+'calculators/advanced-resting-metabolism-update',
            type: 'post',
            data: formdata,
            success: function (res) {
                if(res.status == "updated"){
                        $( "div.alert-success" ).css('display','block');
                        $( "div.alert-success" ).text('Record has been successfully updated.');
                    }
                $('#result').show();
                if (selected_type === 'metric') {
                    $('#result .arm').val(res.arm + ' calories per day');
                }
                else{
                    $('#result .arm').val(res.arm + ' calories per day');
                }
                
            }
        });
    }
    });

   jQuery('.daily-metabolism input[type=radio]').on('change', function () { 
        var selected_type = jQuery('input[name=type]:checked').val();
        var selected_gender = jQuery('input[name=gender]:checked').val();
        var selected_activity = jQuery('input[name=activity]:checked').val();

        jQuery.ajax({
          url: public_url+'calculators/daily-metabolism/'+selected_type+'/'+selected_gender+'/'+selected_activity,
          type: 'GET',
          data: 'selected_type='+selected_type+'&selected_gender='+selected_gender+'&selected_activity='+selected_activity,
          success: function(res) {  
               // var unit='';
              $('#result').show(); 
              $('#record_id').val(res.id);       
              if (!$.trim(res)){  
                    $('#age').val(); 
                    $('#weight').val();
                    $('#weight_m').val();
                    $('#height_m').val();
                    $('#height_ft').val();
                    $('#height_in').val();
                    $('#result .aam').val();
                    $('#result .aamph').val();
                    $('#result .arm').val();
                }
                else{                   
                        if (selected_type === 'metric') {
                            $('#age').val(res.age); 
                            $('#weight_m').val(res.weight);
                            $('#height_m').val(res.height_ft);
                            $('#result .aam').val(res.aam + ' calories per day');
                            $('#result .aamph').val(res.aamph + ' calories per hour');
                            $('#result .arm').val(res.arm + ' calories per day');
                        }
                        else{
                            $('#age').val(res.age); 
                            $('#weight').val(res.weight);
                            $('#height_ft').val(res.height_ft);
                            $('#height_in').val(res.height_in);
                            $('#result .aam').val(res.aam + ' calories per day');
                            $('#result .aamph').val(res.aamph + ' calories per hour');
                            $('#result .arm').val(res.arm + ' calories per day');
                        }
                    } 

           
          }
        });
    });

   $('.button_daily').click(function() {
    var selected_type = jQuery('input[name=type]:checked').val();
    var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){
        var formdata = $('#form').serialize();
        $.ajax({
            url: public_url+'calculators/daily-metabolism',
            type: 'post',
            data: formdata,
            success: function (res) {
                $('#result').show();

                $('#result .aam').val(res.aam + ' calories per day');
                $('#result .aamph').val(res.aamph + ' calories per hour');
                $('#result .arm').val(res.arm + ' calories per day');
                
            }
        });
    }
    });

   $('.button_daily_edit').click(function() {
    var selected_type = jQuery('input[name=type]:checked').val();
    var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){
        var formdata = $('#form').serialize();
        $.ajax({
            url: public_url+'calculators/daily-metabolism-update',
            type: 'post',
            data: formdata,
            success: function (res) {
                if(res.status == "updated"){
                        $( "div.alert-success" ).css('display','block');
                        $( "div.alert-success" ).text('Record has been successfully updated.');
                    }
                $('#result').show();

                $('#result .aam').val(res.aam + ' calories per day');
                $('#result .aamph').val(res.aamph + ' calories per hour');
                $('#result .arm').val(res.arm + ' calories per day');
                
            }
        });
    }
    });


   /*************************************************************************/
   var selected_type = 'metric';

            var selected_gender = 'male';

            var metric = $('#metric');

            var result = $('#result');

            var imperial = $('#imperial');

            var hip = $('#hip');

            var unit_type = $('.unit-type');

            unit_type.text('(cm)');

            $('input[name=type]').on('change', function () {
                selected_type = $('input[name=type]:checked').val();

                if (selected_type === 'metric') {
                    imperial.hide();
                    metric.show();
                    //result.hide();
                    unit_type.text('(cm)');
                } else {
                    metric.hide();
                    imperial.show();
                    //result.hide();
                    unit_type.text('(in)');
                }
            });
            $('input[name=gender]').on('change', function () {
                selected_gender = $('input[name=gender]:checked').val()

                if (selected_gender === 'male') {
                    hip.hide();
                } else {
                    hip.show();
                }
            });

   /************************************************************************/
   jQuery('.body-fat-navy input[type=radio]').on('change', function () { 
        var selected_type = jQuery('input[name=type]:checked').val();
        var selected_gender = jQuery('input[name=gender]:checked').val();

        jQuery.ajax({
          url: public_url+'calculators/body-fat-navy/'+selected_type+'/'+selected_gender,
          type: 'GET',
          data: 'selected_type='+selected_type+'&selected_gender='+selected_gender,
          success: function(res) {  
               // var unit='';
              $('#result').show(); 
              $('#record_id').val(res.id);       
              if (!$.trim(res)){  
                    $('#age').val(); 
                    $('#weight').val();
                    $('#weight_m').val();
                    $('#height_m').val();
                    $('#height_ft').val();
                    $('#height_in').val();
                    $('#waist_m').val();
                    $('#neck_m').val();
                    $('#hip_m').val();
                    $('#result .bf').val();
                    $('#result .fm').val();
                    $('#result .lm').val();
                    $('#result .bfc').val();
                }
                else{                   
                        if (selected_type === 'metric') {
                            $('#age').val(res.age); 
                            $('#weight_m').val(res.weight);
                            $('#height_m').val(res.height_ft);
                        }
                        else{
                            $('#age').val(res.age); 
                            $('#weight').val(res.weight);
                            $('#height_ft').val(res.height_ft);
                            $('#height_in').val(res.height_in);                            
                        }
                        $('#waist_m').val(res.waist);
                        $('#neck_m').val(res.neck);
                        $('#hip_m').val(res.hip);
                        $('#result .bf').val(res.bf + '%');
                        $('#result .fm').val(res.fm + ' ' + unit);
                        $('#result .lm').val(res.lm + ' ' + unit);
                        $('#result .bfc').val(res.bfc);
                    } 

           
          }
        });
    });

   $('.button_navy').click(function() {
    var selected_type = jQuery('input[name=type]:checked').val();
    var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){

        var formdata = $('#form').serialize();
        $.ajax({
            url: public_url+'calculators/body-fat-navy',
            type: 'post',
            data: formdata,
            success: function (res) {
                var unit='';
                if (selected_type === 'metric') {
                    unit = 'kg';
                } else {
                    unit = 'lbs';
                }

                $('#result').show();

                $('#result .bf').val(res.bf + '%');
                $('#result .fm').val(res.fm + ' ' + unit);
                $('#result .lm').val(res.lm + ' ' + unit);
                $('#result .bfc').val(res.bfc);
            }
        });
    }
    });

   $('.button_navy_edit').click(function() {
    var selected_type = jQuery('input[name=type]:checked').val();
    var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){

        var formdata = $('#form').serialize();
        $.ajax({
            url: public_url+'calculators/body-fat-navy-update',
            type: 'post',
            data: formdata,
            success: function (res) {
                if(res.status == "updated"){
                        $( "div.alert-success" ).css('display','block');
                        $( "div.alert-success" ).text('Record has been successfully updated.');
                    }
                var unit='';
                if (selected_type === 'metric') {
                    unit = 'kg';
                } else {
                    unit = 'lbs';
                }

                $('#result').show();

                $('#result .bf').val(res.bf + '%');
                $('#result .fm').val(res.fm + ' ' + unit);
                $('#result .lm').val(res.lm + ' ' + unit);
                $('#result .bfc').val(res.bfc);
            }
        });
    }
    });
/******************************************************************************/

    jQuery('.body-fat-ymca input[type=radio]').on('change', function () { 
        var selected_type = jQuery('input[name=type]:checked').val();
        var selected_gender = jQuery('input[name=gender]:checked').val();

        jQuery.ajax({
          url: public_url+'calculators/body-fat-ymca/'+selected_type+'/'+selected_gender,
          type: 'GET',
          data: 'selected_type='+selected_type+'&selected_gender='+selected_gender,
          success: function(res) {  
               // var unit='';
              $('#result').show(); 
              $('#record_id').val(res.id);       
              if (!$.trim(res)){   
                    $('#weight').val();
                    $('#weight_m').val();
                    $('#waist_m').val();
                    $('#result .bf').val();
                    $('#result .fm').val();
                    $('#result .lm').val();
                    $('#result .bfc').val();
                }
                else{                   
                        if (selected_type === 'metric') {
                            $('#age').val(res.age); 
                            $('#weight_m').val(res.weight);
                        }
                        else{
                            $('#age').val(res.age); 
                            $('#weight').val(res.weight);                     
                        }
                        $('#waist_m').val(res.waist);
                        $('#result .bf').val(res.bf + '%');
                        $('#result .fm').val(res.fm + ' ' + unit);
                        $('#result .lm').val(res.lm + ' ' + unit);
                        $('#result .bfc').val(res.bfc);
                    } 

           
          }
        });
    });

   $('.button_ymca').click(function() {
    var selected_type = jQuery('input[name=type]:checked').val();
    var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){
        var formdata = $('#form').serialize();
        $.ajax({
            url: public_url+'calculators/body-fat-ymca',
            type: 'post',
            data: formdata,
            success: function (res) {
                var unit='';
                if (selected_type === 'metric') {
                    unit = 'kg';
                } else {
                    unit = 'lbs';
                }

                $('#result').show();

                $('#result .bf').val(res.bf + '%');
                $('#result .fm').val(res.fm + ' ' + unit);
                $('#result .lm').val(res.lm + ' ' + unit);
                $('#result .bfc').val(res.bfc);
            }
        });
    }
    });

   $('.button_ymca_edit').click(function() {
    var selected_type = jQuery('input[name=type]:checked').val();
    var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){
        var formdata = $('#form').serialize();
        $.ajax({
            url: public_url+'calculators/body-fat-ymca-update',
            type: 'post',
            data: formdata,
            success: function (res) {
                if(res.status == "updated"){
                        $( "div.alert-success" ).css('display','block');
                        $( "div.alert-success" ).text('Record has been successfully updated.');
                    }
                var unit='';
                if (selected_type === 'metric') {
                    unit = 'kg';
                } else {
                    unit = 'lbs';
                }

                $('#result').show();

                $('#result .bf').val(res.bf + '%');
                $('#result .fm').val(res.fm + ' ' + unit);
                $('#result .lm').val(res.lm + ' ' + unit);
                $('#result .bfc').val(res.bfc);
            }
        });
    }
    });

   /******************************************************************************************/
       jQuery('.lean-body-mass input[type=radio]').on('change', function () { 
        var selected_type = jQuery('input[name=type]:checked').val();
        var selected_gender = jQuery('input[name=gender]:checked').val();

        jQuery.ajax({
          url: public_url+'calculators/lean-body-mass/'+selected_type+'/'+selected_gender,
          type: 'GET',
          data: 'selected_type='+selected_type+'&selected_gender='+selected_gender,
          success: function(res) {  
               // var unit='';
              $('#result').show(); 
              $('#record_id').val(res.id);       
              if (!$.trim(res)){  
                    $('#weight').val();
                    $('#weight_m').val();
                    $('#height_m').val();
                    $('#height_ft').val();
                    $('#height_in').val();
                    $('#result .fm').val();
                    $('#result .lm').val();
                }
                else{                   
                        if (selected_type === 'metric') {
                             unit = 'kg';
                            $('#weight_m').val(res.weight);
                            $('#height_m').val(res.height_ft);
                        }
                        else{
                             unit = 'lbs';
                            $('#weight').val(res.weight);
                            $('#height_ft').val(res.height_ft);
                            $('#height_in').val(res.height_in);                            
                        }
                        $('#result .lm').val(res.lm + ' ' + unit + '   (' + res.lmp + '%)');
                        $('#result .fm').val(res.fm + ' ' + unit + '   (' + res.fmp + '%)');
                       
                    } 

           
          }
        });
    });

  $('.button_lean').click(function() {
    var selected_type = jQuery('input[name=type]:checked').val();
    var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){
    var formdata = $('#form').serialize();
        $.ajax({
            url: public_url+'calculators/lean-body-mass',
            type: 'post',
            data: formdata,
            success: function (res) {
                var unit='';

                $('#result').show();

                if (selected_type === 'metric') {
                    unit = 'kg';
                } else {
                    unit = 'lbs';
                }

                $('#result .lm').val(res.lm + ' ' + unit + '   (' + res.lmp + '%)');
                $('#result .fm').val(res.fm + ' ' + unit + '   (' + res.fmp + '%)');
            }
        });
    }
    });

  $('.button_lean_edit').click(function() {
    var selected_type = jQuery('input[name=type]:checked').val();
    var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){
    var formdata = $('#form').serialize();
        $.ajax({
            url: public_url+'calculators/lean-body-mass-update',
            type: 'post',
            data: formdata,
            success: function (res) {
                if(res.status == "updated"){
                        $( "div.alert-success" ).css('display','block');
                        $( "div.alert-success" ).text('Record has been successfully updated.');
                    }
                var unit='';

                $('#result').show();

                if (selected_type === 'metric') {
                    unit = 'kg';
                } else {
                    unit = 'lbs';
                }

                $('#result .lm').val(res.lm + ' ' + unit + '   (' + res.lmp + '%)');
                $('#result .fm').val(res.fm + ' ' + unit + '   (' + res.fmp + '%)');
            }
        });
    }
    });

  /*******************************************************************************************/
   jQuery('.waist-hip-ratio input[type=radio]').on('change', function () { 
        var selected_type = jQuery('input[name=type]:checked').val();
        var selected_gender = jQuery('input[name=gender]:checked').val();

        jQuery.ajax({
          url: public_url+'calculators/waist-hip-ratio/'+selected_type+'/'+selected_gender,
          type: 'GET',
          data: 'selected_type='+selected_type+'&selected_gender='+selected_gender,
          success: function(res) {  
               // var unit='';
              $('#result').show(); 
              $('#record_id').val(res.id);       
              if (!$.trim(res)){  
                    $('#waist_m').val();
                    $('#hip_m').val();
                    $('#result .ratio').val();
                    $('#result .bs').val();
                    $('#result .interpretation').text();
                }
                else{    

                        $('#waist_m').val(res.waist);
                        $('#hip_m').val(res.hip);    
                        $('#result .ratio').val(res.ratio + '%');
                        $('#result .bs').val(res.bs);
                        $('#result .interpretation').text(res.interpretation);
                       
                    } 

           
          }
        });
    });


  jQuery('.button_waist').click(function() {
    var selected_type = jQuery('input[name=type]:checked').val();
    var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){
     var formdata = $('#form').serialize();
        $.ajax({
            url: public_url+'calculators/waist-hip-ratio',
            type: 'post',
            data: formdata,
            success: function (res) {
                $('#result').show();

                $('#result .ratio').val(res.ratio + '%');
                $('#result .bs').val(res.bs);
                $('#result .interpretation').text(res.interpretation);
            }
        });
    }
    });

  jQuery('.button_waist_edit').click(function() {
    var selected_type = jQuery('input[name=type]:checked').val();
    var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){
     var formdata = $('#form').serialize();
        $.ajax({
            url: public_url+'calculators/waist-hip-ratio-update',
            type: 'post',
            data: formdata,
            success: function (res) {
                if(res.status == "updated"){
                        $( "div.alert-success" ).css('display','block');
                        $( "div.alert-success" ).text('Record has been successfully updated.');
                    }
                $('#result').show();

                $('#result .ratio').val(res.ratio + '%');
                $('#result .bs').val(res.bs);
                $('#result .interpretation').text(res.interpretation);
            }
        });
    }
    });

  /*************************************************************************************/

  jQuery('.full-body-analysis input[type=radio]').on('change', function () { 
        var selected_type = jQuery('input[name=type]:checked').val();
        var selected_gender = jQuery('input[name=gender]:checked').val();
        var activity = jQuery('input[name=activity]:checked').val();
        var goal = jQuery('input[name=goal]:checked').val();

        jQuery.ajax({
          url: public_url+'calculators/full-body-analysis/'+selected_type+'/'+selected_gender+'/'+activity+'/'+goal,
          type: 'GET',
          data: 'selected_type='+selected_type+'&selected_gender='+selected_gender+'&activity='+activity+'&goal='+goal,
          success: function(res) {  
               // var unit='';
              $('#result').show(); 
              $('#record_id').val(res.id);       
              if (!$.trim(res)){  
                    $('#weight').val();
                    $('#weight_m').val();
                    $('#height_m').val();
                    $('#height_ft').val();
                    $('#height_in').val();    
                    $('#waist_m').val();
                    $('#age').val();
                    $('#heart_rate').val();
                    $('#hip_m').val();
                    $('#elbow_m').val();
                    $('#result .bmi span').text();
                    $('#result .bmic span').text();
                    $('#result .whr span').text();
                    $('#result .bs span').text();
                    $('#result .interpretation span').text();
                    $('#result .iw span').text();
                    $('#result .bf span').text();
                    $('#result .lm span').text();
                    $('#result .rm span').text();
                    $('#result .aam span').text();
                    $('#result .kthr span').text();
                    $('#result .mhr span').text();
                }
                else{    
                    if (selected_type === 'metric') {
                             unit = 'kg';
                            $('#weight_m').val(res.weight);
                            $('#height_m').val(res.height_ft);
                        }
                        else{
                             unit = 'lbs';
                            $('#weight').val(res.weight);
                            $('#height_ft').val(res.height_ft);
                            $('#height_in').val(res.height_in);                            
                        }   
                        $('#age').val(res.age);
                        $('#heart_rate').val(res.rhra);
                        $('#elbow_m').val(res.elbow);               
                        $('#waist_m').val(res.waist);
                        $('#hip_m').val(res.hip);    
                        $('#result .bmi span').text(res.bmi);
                        $('#result .bmic span').text(res.classification + ' (' + res.weight_range + ')');
                        $('#result .whr span').text(res.ratio + '%');
                        $('#result .bs span').text(res.bs);
                        $('#result .interpretation span').text(res.interpretation);
                        $('#result .iw span').text(res.ideal_weight + ' ' + unit);
                        $('#result .bf span').text(res.fm + ' ' + unit + ' ' + res.fmp + '%');
                        $('#result .lm span').text(res.lm + ' ' + unit + ' ' + res.lmp + '%');
                        $('#result .rm span').text(res.arm + ' cal/day' + ' ' + Math.round(res.arm / 24) + ' cal/hour');
                        $('#result .aam span').text(res.aam + ' cal/day' + ' ' + res.aamph + ' cal/hour');
                        $('#result .kthr span').text(res.bpml + ' - ' + res.bpmh + ' bpm' + ' ' + res.bptsl + ' - ' + res.bptsh + ' b/10s');
                        $('#result .mhr span').text(res.mhr  + ' bpm' + ' ' + res.mhrits + ' b/10s');
                       
                    } 

           
          }
        });
    });

  jQuery('.button_full_body').click(function() {
    var selected_type = jQuery('input[name=type]:checked').val();
    var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){
    var formdata = $('#form').serialize();

        $.ajax({
            url: public_url+'calculators/full-body-analysis',
            type: 'post',
            data: formdata,
            success: function (res) {
                var unit='';
                if (selected_type === 'metric') {
                    unit = 'kg';
                } else {
                    unit = 'lbs';
                }

                $('#result').show();

//console.log(res);
                $('#result .bmi span').text(res.body_mass_index.bmi);
                $('#result .bmic span').text(res.body_mass_index.classification + ' (' + res.body_mass_index.weight_range + ')');
                $('#result .whr span').text(res.waist_hip_ratio.ratio + '%');
                $('#result .bs span').text(res.waist_hip_ratio.bs);
                $('#result .interpretation span').text(res.waist_hip_ratio.interpretation);
                $('#result .iw span').text(res.ideal_weight.iw + ' ' + unit);
                $('#result .bf span').text(res.lean_body_mass.fm + ' ' + unit + ' ' + res.lean_body_mass.fmp + '%');
                $('#result .lm span').text(res.lean_body_mass.lm + ' ' + unit + ' ' + res.lean_body_mass.lmp + '%');
                $('#result .rm span').text(res.daily_metabolism.arm + ' cal/day' + ' ' + Math.round(res.daily_metabolism.arm / 24) + ' cal/hour');
                $('#result .aam span').text(res.daily_metabolism.aam + ' cal/day' + ' ' + res.daily_metabolism.aamph + ' cal/hour');
                $('#result .kthr span').text(res.target_heart_rate.bpml + ' - ' + res.target_heart_rate.bpmh + ' bpm' + ' ' + res.target_heart_rate.bptsl + ' - ' + res.target_heart_rate.bptsh + ' b/10s');
                $('#result .mhr span').text(res.target_heart_rate.mhr  + ' bpm' + ' ' + res.target_heart_rate.mhrits + ' b/10s');
            }
        });
    }
    });

    jQuery('.button_full_body_edit').click(function() {
    var selected_type = jQuery('input[name=type]:checked').val();
    var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){
    var formdata = $('#form').serialize();

        $.ajax({
            url: public_url+'calculators/full-body-analysis-update',
            type: 'post',
            data: formdata,
            success: function (res) {
                if(res.status == "updated"){
                        $( "div.alert-success" ).css('display','block');
                        $( "div.alert-success" ).text('Record has been successfully updated.');
                    }
                var unit='';
                if (selected_type === 'metric') {
                    unit = 'kg';
                } else {
                    unit = 'lbs';
                }

                $('#result').show();

//console.log(res);
                $('#result .bmi span').text(res.body_mass_index.bmi);
                $('#result .bmic span').text(res.body_mass_index.classification + ' (' + res.body_mass_index.weight_range + ')');
                $('#result .whr span').text(res.waist_hip_ratio.ratio + '%');
                $('#result .bs span').text(res.waist_hip_ratio.bs);
                $('#result .interpretation span').text(res.waist_hip_ratio.interpretation);
                $('#result .iw span').text(res.ideal_weight.iw + ' ' + unit);
                $('#result .bf span').text(res.lean_body_mass.fm + ' ' + unit + ' ' + res.lean_body_mass.fmp + '%');
                $('#result .lm span').text(res.lean_body_mass.lm + ' ' + unit + ' ' + res.lean_body_mass.lmp + '%');
                $('#result .rm span').text(res.daily_metabolism.arm + ' cal/day' + ' ' + Math.round(res.daily_metabolism.arm / 24) + ' cal/hour');
                $('#result .aam span').text(res.daily_metabolism.aam + ' cal/day' + ' ' + res.daily_metabolism.aamph + ' cal/hour');
                $('#result .kthr span').text(res.target_heart_rate.bpml + ' - ' + res.target_heart_rate.bpmh + ' bpm' + ' ' + res.target_heart_rate.bptsl + ' - ' + res.target_heart_rate.bptsh + ' b/10s');
                $('#result .mhr span').text(res.target_heart_rate.mhr  + ' bpm' + ' ' + res.target_heart_rate.mhrits + ' b/10s');
            }
        });
    }
    });

  jQuery("#sendy_save").click(function() {
       
       var form = $('#sendy'),
            isFormValid = form.valid();
       
        if(isFormValid){
            var formdata = $('#sendy').serialize();
            jQuery.ajax({
                url: public_url+'save-email',
                type: 'post',
                data: formdata,
                success: function (res) {
                    if(res=='1'){
                        $('#suc_msg').html('Subscribed Successfully');
                        $('#suc_msg').show();
                    }
                    else{
                        $('#suc_msg').html(res);
                        $('#suc_msg').show();
                    }
                }
            });
        }
    });

  jQuery("#sendy_unsubscribe").click(function() {
       
       var form = $('#sendy_unsubscribe'),
            isFormValid = form.valid();
       
        if(isFormValid){
            var formdata = $('#sendy_unsubscribe').serialize();
            console.log(formdata);
            jQuery.ajax({
                url: public_url+'unsubscribe-email',
                type: 'post',
                data: formdata,
                success: function (res) {
                    if(res=='1'){
                        $('#suc_msg').html('Unsubscribed Successfully');
                        $('#suc_msg').show();
                    }
                    else{
                        $('#suc_msg').html(res);
                        $('#suc_msg').show();
                    }
                }
            });
        }
    });

  jQuery("#sendy_mail").click(function() {
       
       var form = $('#send_mail'),
            isFormValid = form.valid();
       
        if(isFormValid){
            var formdata = $('#send_mail').serialize();
            console.log(formdata);
            jQuery.ajax({
                url: public_url+'sending-email',
                type: 'post',
                data: formdata,
                success: function (res) {
                    if(res=='1'){
                        $('#suc_msg').html('Successfully');
                        $('#suc_msg').show();
                    }
                    else{
                        $('#suc_msg').html(res);
                        $('#suc_msg').show();
                    }
                }
            });
        }
    });
    
});
