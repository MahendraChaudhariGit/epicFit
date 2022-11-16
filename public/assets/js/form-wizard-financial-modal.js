$(document).ready(function () {
 

    /**************~ Common - For -  all ~**********************/
    function renderDefaultDataToModal(LocalKey,DefaultObject) {
        if (JSON.parse(localStorage.getItem(LocalKey))){
            var OBJECTDATA = JSON.parse(localStorage.getItem(LocalKey));
            var ObLength = 0;
            for (var ixz in OBJECTDATA){
                ObLength +=1;
            }
           if(ObLength == 0){
               localStorage.setItem(LocalKey,JSON.stringify(DefaultObject));
           }
        }else{
            localStorage.setItem(LocalKey,JSON.stringify(DefaultObject));
        }
        if (LocalKey == 'setup_expenses'){
            rendorSetupJSONObejct(JSON.parse(localStorage.getItem(LocalKey)));
        }
        if (LocalKey == 'business_expenses'){
            rendorBusinessJSONObejct(JSON.parse(localStorage.getItem(LocalKey)));
        }
        if (LocalKey == 'living_expenses'){
            rendorLivingJSONObejct(JSON.parse(localStorage.getItem(LocalKey)));
        }
    }
    /**************~ End  Common - For -  all ~**********************/


/* * * * * * * * * * * * * start setup expenses  * * * * * * * * * * * * */
    // var setupObject = {"Premises":{"Purchase of studio": null}};
      // var setupObject = {"Premises":{"Purchase of studio":0},"Registration / Training":{"Required training / Registration":0},"Communication":{"Phone":0,"Computer":0,"Other":0},"Vehicle / Travel":{"Vehicle purchase":0,"Signage on car":0},"Clothing":{"Uniform":0,"Shoes":0},"Equipment":{"Startup Requirements*":0},"Office & Stationary":{"Desk & furniture":0},"Promotion & Marketing":{" Logo design costs":0,"Business cards":0,"Letterheads":0,"Web design costs":0,"Brochures":0,"Other":0}};
      var setupObject = {"Premises":[{"Purchase of studio":0,"Incl. Tax":0 , "timeframe" : "oneoff" }],"Registration / Training":[{"Required training / Registration":0,"Incl. Tax":0 , "timeframe" : "oneoff" }],"Communication":[{"Phone":0,"Incl. Tax":0 , "timeframe" : "oneoff" },{"Computer":0,"Incl. Tax":0 , "timeframe" : "oneoff" },{"Other":0,"Incl. Tax":0 , "timeframe" : "oneoff" }],"Vehicle / Travel":[{"Vehicle purchase":0,"Incl. Tax":0 , "timeframe" : "oneoff" },{"Signage on car":0,"Incl. Tax":0 , "timeframe" : "oneoff" }],"Clothing":[{"Uniform":0,"Incl. Tax":0 , "timeframe" : "oneoff" },{"Shoes":0,"Incl. Tax":0 , "timeframe" : "oneoff" }],"Equipment":[{"Startup Requirements*":0,"Incl. Tax":0 , "timeframe" : "oneoff" }],"Office & Stationary":[{"Desk & furniture":0,"Incl. Tax":0 , "timeframe" : "oneoff" }],"Promotion & Marketing":[{" Logo design costs":0,"Incl. Tax":0 , "timeframe" : "oneoff" },{"Business cards":0,"Incl. Tax":0 , "timeframe" : "oneoff" },{"Letterheads":0,"Incl. Tax":0 , "timeframe" : "oneoff" },{"Web design costs":0,"Incl. Tax":0 , "timeframe" : "oneoff" },{"Brochures":0,"Incl. Tax":0 , "timeframe" : "oneoff" },{"Other":0,"Incl. Tax":0 , "timeframe" : "oneoff" }]};
      renderDefaultDataToModal('setup_expenses',setupObject);

       $(document).on('click','.setup_expense_submit',function(){

           $('.chk').each(function(){
             var setupPID = $(this).attr('data-setup-parent-id');
             var SetupStorage = JSON.parse(localStorage.getItem('setup_expenses'));
             var setupINPUTID = "Incl. Tax";
             var setupCHKID = $(this).attr('data-setup-check-id');
             var setupINTPUTVAL = $(this).val();

               if (SetupStorage.hasOwnProperty(setupPID)){
                   // console.log('setupPID', setupPID,setupINPUTID)
                   for (i = 0; i < SetupStorage[setupPID].length; i++) {
                       if (SetupStorage[setupPID] && SetupStorage[setupPID][i].hasOwnProperty(setupCHKID)) {
                           console.log(SetupStorage[setupPID][i],setupINPUTID, setupINTPUTVAL)
                           SetupStorage[setupPID][i][setupINPUTID] = setupINTPUTVAL;
                       }
                   }
               }
             // if (SetupStorage.hasOwnProperty(setupPID)) {
             //    SetupStorage[setupPID][setupINPUTID] = setupINTPUTVAL;
             // }
             localStorage.setItem('setup_expenses', JSON.stringify(SetupStorage));
               // console.log(localStorage.getItem('setup_expenses'))
           });
            
          // Process for timeframe dropdown
           $('.timeframe').each(function(){
             var setupPID = $(this).attr('data-setup-parent-id');
             var SetupStorage = JSON.parse(localStorage.getItem('setup_expenses'));
             var setupINPUTID = "timeframe";
             var setupCHKID = $(this).attr('data-setup-timeframe-id');
             var setupINTPUTVAL = $(this).val();

               if (SetupStorage.hasOwnProperty(setupPID)){
                   // console.log('setupPID', setupPID,setupINPUTID)
                   for (i = 0; i < SetupStorage[setupPID].length; i++) {
                       if (SetupStorage[setupPID] && SetupStorage[setupPID][i].hasOwnProperty(setupCHKID)) {
                           console.log(SetupStorage[setupPID][i],setupINPUTID, setupINTPUTVAL)
                           SetupStorage[setupPID][i][setupINPUTID] = setupINTPUTVAL;
                       }
                   }
               }
             // if (SetupStorage.hasOwnProperty(setupPID)) {
             //    SetupStorage[setupPID][setupINPUTID] = setupINTPUTVAL;
             // }
             localStorage.setItem('setup_expenses', JSON.stringify(SetupStorage));
               // console.log(localStorage.getItem('setup_expenses'))
           });


           var setup_amount_sum = 0;
           var setup_exp_gst_incl = 0;
           $('.setup_fill_data').each(function(){
           if(!$(this).val()){
              $(this).val() = 0;
           } console.log("$(this).attr('gst-incl')", $(this).attr('gst-incl'))
            if($(this).attr('gst-incl') == 'true'){
              setup_exp_gst_incl += parseFloat($(this).val());
            }
            setup_amount_sum += parseFloat($(this).val());
           });
           $('.setup_expense_total').val(0);
           $('#setup_exp_est').val(setup_amount_sum);
              localStorage.setItem('setup_exp_gst_incl',setup_exp_gst_incl);
           if(setup_exp_gst_incl != null && setup_exp_gst_incl != 0) {
              localStorage.setItem('setup_exp_gst_incl_tax',(parseFloat(setup_exp_gst_incl) / 100) * $('.gst-percentage').val());
              setup_exp_gst_incl = parseFloat(setup_exp_gst_incl) + (parseFloat(setup_exp_gst_incl) / 100) * $('.gst-percentage').val();

              $('#setup_exp_gst_incl').val(setup_exp_gst_incl);
           } else {
              localStorage.setItem('setup_exp_gst_incl_tax',0);
              $('#setup_exp_gst_incl').val(setup_exp_gst_incl);
           }
       });

       //  CRUD
       //  on change event input fill
       $(document).on('keyup change','.setup_fill_data',function(){
           var SetupStorage = JSON.parse(localStorage.getItem('setup_expenses'));
           var setupPID = $(this).attr('data-setup-parent-id');
           var setupINPUTID = $(this).attr('data-setup-input-id');
           var setupINTPUTVAL = $(this).val();
           var newKey = $(this).closest('.dynamic_input_box').find('.input_name').text();
           // if (!setupINTPUTVAL){
           //     setupINTPUTVAL = 0;
           // }
           $(this).val(setupINTPUTVAL);
           if (SetupStorage.hasOwnProperty(setupPID)) {
               if (SetupStorage[setupPID]){
                   for (i = 0; i < SetupStorage[setupPID].length; i++) { console.log(SetupStorage[setupPID][i].hasOwnProperty(setupINPUTID))
                       if (SetupStorage[setupPID] && SetupStorage[setupPID][i].hasOwnProperty(setupINPUTID)) {
                           SetupStorage[setupPID][i][setupINPUTID] = setupINTPUTVAL;
                       }
                   }
               }
           }
           localStorage.setItem('setup_expenses', JSON.stringify(SetupStorage));
           // console.log("localStorage.getItem('setup_expenses')", localStorage.getItem('setup_expenses'))
       });

       // add section
       var setupJSONObejct = {};
       $(document).on('click','#add-setup-section',function(){
           if (localStorage.getItem('setup_expenses')){
               setupJSONObejct = JSON.parse(localStorage.getItem('setup_expenses')); // add previous data as it is
               setupJSONObejct[$('.setup_section_name').val()] = []; // add new section
           }
           setupJSONObejct[$('.setup_section_name').val()] = []; // add new section
           rendorSetupJSONObejct(setupJSONObejct);
           console.log(JSON.stringify(setupJSONObejct));
           localStorage.setItem('setup_expenses',JSON.stringify(setupJSONObejct));
       });
       $(document).on('click','.add_new_setup_input_btn',function(){
           $('.add_new_setup_field_btn').removeProp('data-section-setup-id');
           $('.add_new_setup_field_btn').attr('data-section-setup-id',$(this).attr('data-section-setup-id'));
       });

       // delete section
       $(document).on('click', '.delete_setup_section_btn', function () {
           var localData = JSON.parse(localStorage.getItem('setup_expenses'));
           var dId= $(this).attr('data-setup-section-id');

           if (localData.hasOwnProperty(dId)){
               delete localData[dId];
               console.log('deleted successfuly!');
           }
           localStorage.setItem('setup_expenses', JSON.stringify(localData));
           $(this).parent().parent().remove();
       });

       // add field
       $(document).on('click', '.add_new_setup_field_btn', function () {
           var ContentsArray = JSON.parse(localStorage.getItem('setup_expenses'));
           var inputLable = $('.setup_field_name').val();
           var selectedSection = $(this).attr('data-section-setup-id');
           ContentsArray[selectedSection].push({ [inputLable] : 0 , "Incl. Tax" : 0, "timeframe" : "oneoff" });
           localStorage.setItem('setup_expenses', JSON.stringify(ContentsArray));
           var tempArray = JSON.parse(localStorage.getItem('setup_expenses'));
           rendorSetupJSONObejct(tempArray);
           $('.timeframe').selectpicker('refresh');
       });

       // delete field
       $(document).on('click', '.remove_setup_section_field_btn', function () {
           var LocalStorageData = JSON.parse(localStorage.getItem('setup_expenses'));
           var selectForDelete = $(this).attr('data-setup-field-id');
           var selectedParentId = $(this).attr('data-setup-parent-id');
           if (LocalStorageData.hasOwnProperty(selectedParentId)) {
               if (LocalStorageData[selectedParentId]){

                   for (i = 0; i < LocalStorageData[selectedParentId].length; i++) {
                       if (LocalStorageData[selectedParentId] && LocalStorageData[selectedParentId][i].hasOwnProperty(selectForDelete)) {
                           var removeIndex = LocalStorageData[selectedParentId].map(function(item) { return item.id; }).indexOf(LocalStorageData[selectedParentId][i]);
                           console.log(removeIndex,LocalStorageData[selectedParentId][i])
                           delete LocalStorageData[selectedParentId].splice(removeIndex, 1);
                       }
                   }
               }
           }
           // if (LocalStorageData.hasOwnProperty(selectedParentId)) {
           //     if (LocalStorageData[selectedParentId] && LocalStorageData[selectedParentId].hasOwnProperty(selectForDelete)) {
           //         delete LocalStorageData[selectedParentId][selectForDelete];
           //     }
           // }
           localStorage.setItem('setup_expenses', JSON.stringify(LocalStorageData));
           $(this).parent().parent().remove();
       });

       // render json object to html
       function rendorSetupJSONObejct(JsonObj) {

           var setup_contents = '';
           setup_contents +='<div class="setup-section">';

           var $keys = Object.keys(JsonObj).sort(function (a, b) { return a.toLowerCase().localeCompare(b.toLowerCase()); });
           for (var i=0;i<$keys.length ; i++) {

           var $FieldKeys = Object.keys(JsonObj[$keys[i]]);

            var fields = '';
               fields += '<tr>';
               for (var j=0;j<$FieldKeys.length ; j++) {
                   var fieldVal = 0;
                   var checkboxVal = 0 , timeframeBoxVal = 'oneoff';
                   var gstIncl = 'false';
                   var $inputFieldKeys = Object.keys(JsonObj[$keys[i]][$FieldKeys[j]]);
                    for (var k = 0; k < $inputFieldKeys.length; k++) {
                      if (JsonObj[$keys[i]][$FieldKeys[j]][$inputFieldKeys[0]]){
                        fieldVal = JsonObj[$keys[i]][$FieldKeys[j]][$inputFieldKeys[0]];
                      } else {
                        fieldVal = 0;
                      }
                      if (JsonObj[$keys[i]][$FieldKeys[j]][$inputFieldKeys[1]]){
                        checkboxVal = JsonObj[$keys[i]][$FieldKeys[j]][$inputFieldKeys[1]];
                      } else {
                        checkboxVal = 0;
                      }
                      if (JsonObj[$keys[i]][$FieldKeys[j]][$inputFieldKeys[2]]){
                        timeframeBoxVal = JsonObj[$keys[i]][$FieldKeys[j]][$inputFieldKeys[2]];
                      }
                    }
                    if(checkboxVal == 1) {
                      var is_check = 'checked';
                      gstIncl = 'true';
                    } else {
                      var is_check = '';
                      gstIncl = 'false';
                    }

                   var chkBox = '<div class="checkbox clip-check check-primary checkbox-inline m-b-0"><input data-setup-parent-id="'+$keys[i]+
                       '"'+is_check+' data-setup-check-id="'+$inputFieldKeys[0]+'" id="checkbox" type="checkbox" value="'+ checkboxVal +'" class="chk" name="Incl. Tax"><label for="checkbox'+$FieldKeys[j]+'"></label></div>';
                   var timeframeBox = '<select data-setup-parent-id="'+$keys[i]+'" data-setup-timeframe-id="'+$inputFieldKeys[0]+'" name="timeframe" class="timeframe form-control" disabled><option value="" disabled>Select Timeframe</option><option value="oneoff" selected>Oneoff</option></select>';   
                   fields += '<tr class="dynamic_input_box" style="width: 100%;">' +
                       '<td class="w-20 input_name text-left" data-id="'+j+'" data-setup-parent-id="'+$keys[i]+'" data-setup-input-id="'+$inputFieldKeys[0]+'">' +
                       $inputFieldKeys[0]+'</td><td class="w-20 input_value"><input gst-incl="' + gstIncl + '" class="form-control input_field_value allowNumericWithDecimalOnly' +
                       ' setup_fill_data" data-setup-parent-id="'+$keys[i]+'" data-setup-input-id="'+$inputFieldKeys[0]+'"' +
                       ' placeholder="$11,200.00" type="text" value="'+fieldVal+'"></td>' +
                       '<td class="w-20">' + 'Inc. GST? &nbsp;' + 
                       ''+chkBox+'</td>' + '<td class="w-20 timeframe-td"> '+timeframeBox+' </td>' +
                       '<td class="w-10"><a style="margin-right: 1px;margin-bottom: 3px;" class="btn btn-xs btn-default tooltips pull-right remove_setup_section_field_btn"' +
                       'role="button" value="Small Default" data-setup-parent-id="'+$keys[i]+
                       '"title="Remove field" data-setup-field-id="'+$inputFieldKeys[0]+'">' + 
                       '<i class="fa fa-trash" style="color:#ff4401;"></i></a></strong>' +
                       '<a style="margin-right: 1px;margin-bottom: 3px;" class="btn btn-xs btn-default tooltips pull-right edit_setup_section_field_btn"' +
                       'role="button" value="Small Default" data-setup-parent-id="'+$keys[i]+
                       '"title="Remove field" data-setup-field-id="'+$inputFieldKeys[0]+'">' + 
                       '<i class="fa fa-pencil" style="color:#ff4401;"></i></a></strong>' +
                       '</td></tr>';
               }
               fields += '</tbody></table>';

               setup_contents +=  '<div class="setup-dynamic-section"><strong class="dynamic_title" data-title="'+$keys[i]+'">' + $keys[i] +
                   '<a style="margin-right: 1px;margin-bottom: 3px;" class="btn btn-xs btn-default tooltips p-y-0 pull-right delete_setup_section_btn"' +
                   'role="button" value="Small Default" title="Remove section" data-setup-section-id="'+$keys[i]+'">' +
                   '<i class="fa fa-trash" style="color:#ff4401;"></i></a>' +
                   '<a style="margin-right: 1px;margin-bottom: 3px;" class="btn btn-xs btn-default tooltips p-y-0 pull-right setup edit_setup_section_btn"' +
                   'role="button" value="Small Default" title="Remove section" data-setup-section-id="'+$keys[i]+'">' +
                   '<i class="fa fa-pencil" style="color:#ff4401;"></i></a>' +
                   '<a style="margin-right: 1px;margin-bottom: 3px;" class="btn btn-xs btn-default tooltips p-y-0 pull-right add_new_setup_input_btn"' +
                    'data-toggle="modal" href="#setup-field-modal" data-section-setup-id="'+$keys[i]+
                    '"role="button" value="Small Default" title="Add new field">' +
                    '<i class="fa fa-plus-circle" style="color:#ff4401;"></i></a></strong>' +
                   '<div class="responsive-table"><div class="scrollable-area">' +
                   '<table class="table data-table table table-bordered table-striped"><tbody>' +fields+
                   '</div></div></div></tbody></table>';
               setup_contents +='<br>';
           }
           setup_contents +='</div>';
           // add to html
           $('.setup-expense-content').html(setup_contents);
           enableCheckbox();
       }

       // previous code...
       $('.overflow').click(function(){
                   $('#setup-modal').css('overflow-y','auto','important');
               });
/* * * * * * * * * * * * * end setup expenses  * * * * * * * * * * * * */


/* * * * * * * * * * * * * start business expenses  * * * * * * * * * * * * */
    // var businesObject = {"Premises":{"Rent":0,"Mortgage / Insurance":0,"Power":0,"Rates":0},"Communication":{"Landline":0,"Mobile":0,"Internet":0},"Vehicle / Travel":{"Car repayments":0,"Petrol":0,"Mileage":0,"WOF":0,"Rego":0,"Car service / Repairs":0,"Parking":0,"Bus / Train fares":0},"Office & Stationary":{"Furniture":0,"Insurance":0,"Maintenance":0,"Postage & PO Box rental":0,"Printing":0,"Staff salaries":0},"Marketing":{"Website hosting":0,"Advertising":0,"Entertainment":0},"Training":{"Workshops / Courses":0,"Professional Memberships":0,"Conferences":0},"Legal / Accounting":{"Accountant fees":0,"Other professionals":0,"Taxes":0},"Misc Costs":{"Debt":0,"Bank fees":0,"Depreciation":0,"Drawings":0}};
    var businesObject = {
                        "Premises":[{"Rent":0,"Incl. Tax":0, "timeframe" : "weekly" },{"Mortgage / Insurance":0,"Incl. Tax":0, "timeframe" : "weekly"},{"Power":0,"Incl. Tax":0, "timeframe" : "weekly"},{"Rates":0,"Incl. Tax":0, "timeframe" : "weekly"}],
                        "Communication":[{"Landline":0,"Incl. Tax":0, "timeframe" : "weekly"},{"Mobile":0,"Incl. Tax":0, "timeframe" : "weekly"},{"Internet":0,"Incl. Tax":0, "timeframe" : "weekly"}],
                        "Vehicle / Travel":[{"Car repayments":0,"Incl. Tax":0, "timeframe" : "weekly"},{"Petrol":0,"Incl. Tax":0, "timeframe" : "weekly"},{"Mileage":0,"Incl. Tax":0, "timeframe" : "weekly"},{"WOF":0,"Incl. Tax":0, "timeframe" : "weekly"},{"Rego":0,"Incl. Tax":0, "timeframe" : "weekly"},{"Car service / Repairs":0,"Incl. Tax":0, "timeframe" : "weekly"},{"Parking":0,"Incl. Tax":0, "timeframe" : "weekly"},{"Bus / Train fares":0,"Incl. Tax":0, "timeframe" : "weekly"}],
                        "Office & Stationary":[{"Furniture":0,"Incl. Tax":0, "timeframe" : "weekly"},{"Insurance":0,"Incl. Tax":0, "timeframe" : "weekly"},{"Maintenance":0,"Incl. Tax":0, "timeframe" : "weekly"},{"Postage & PO Box rental":0,"Incl. Tax":0, "timeframe" : "weekly"},{"Printing":0,"Incl. Tax":0, "timeframe" : "weekly"},{"Staff salaries":0,"Incl. Tax":0, "timeframe" : "weekly"}],
                        "Marketing":[{"Website hosting":0,"Incl. Tax":0, "timeframe" : "weekly"},{"Advertising":0,"Incl. Tax":0, "timeframe" : "weekly"},{"Entertainment":0,"Incl. Tax":0, "timeframe" : "weekly"}],
                        "Training":[{"Workshops / Courses":0,"Incl. Tax":0, "timeframe" : "weekly"},{"Professional Memberships":0,"Incl. Tax":0, "timeframe" : "weekly"},{"Conferences":0,"Incl. Tax":0, "timeframe" : "weekly"}],
                        "Legal / Accounting":[{"Accountant fees":0,"Incl. Tax":0, "timeframe" : "weekly"},{"Other professionals":0,"Incl. Tax":0, "timeframe" : "weekly"},{"Taxes":0,"Incl. Tax":0, "timeframe" : "weekly"}],
                        "Misc Costs":[{"Debt":0,"Incl. Tax":0, "timeframe" : "weekly"},{"Bank fees":0,"Incl. Tax":0, "timeframe" : "weekly"},{"Depreciation":0,"Incl. Tax":0, "timeframe" : "weekly"},{"Drawings":0,"Incl. Tax":0, "timeframe" : "weekly"}]};
    renderDefaultDataToModal('business_expenses',businesObject);

        $(document).on('click','.business_expense_submit',function(){

            timeframeCalculation();

        
           $('.chk').each(function(){
              var BusinessStorage = JSON.parse(localStorage.getItem('business_expenses'));
              var businessPID = $(this).attr('data-business-parent-id');
               var businessCHKID = $(this).attr('data-business-check-id');
              var businessINPUTID = "Incl. Tax";
              var businessINTPUTVAL = $(this).val();

               if (BusinessStorage.hasOwnProperty(businessPID)){
                   // console.log('setupPID', setupPID,setupINPUTID)
                   for (i = 0; i < BusinessStorage[businessPID].length; i++) {
                       if (BusinessStorage[businessPID] && BusinessStorage[businessPID][i].hasOwnProperty(businessCHKID)) {
                           console.log(BusinessStorage[businessPID][i],businessINPUTID, businessINTPUTVAL)
                           BusinessStorage[businessPID][i][businessINPUTID] = businessINTPUTVAL;
                       }
                   }
               }
             //  if (BusinessStorage.hasOwnProperty(businessPID)) {
             //     if (BusinessStorage[businessPID]){
             //      var keys = Object.keys(BusinessStorage[businessPID]);
             //      console.log(keys);
             //      for(var i=0;i<keys.length;i++) {
             //         BusinessStorage[businessPID][keys[i]][businessINPUTID] = businessINTPUTVAL;
             //      }
             //     }
             // }
             localStorage.setItem('business_expenses', JSON.stringify(BusinessStorage));
           });
          var finalVal = 0;
           $('.timeframe').each(function(){
              var BusinessStorage = JSON.parse(localStorage.getItem('business_expenses'));
              var businessPID = $(this).attr('data-business-parent-id');
               var businessCHKID = $(this).attr('data-business-timeframe-id');
              var businessINPUTID = "timeframe";
              var businessINTPUTVAL = $(this).val();

               if (BusinessStorage.hasOwnProperty(businessPID)){
                   // console.log('setupPID', setupPID,setupINPUTID)
                   for (i = 0; i < BusinessStorage[businessPID].length; i++) {
                       if (BusinessStorage[businessPID] && BusinessStorage[businessPID][i].hasOwnProperty(businessCHKID)) {
                           BusinessStorage[businessPID][i][businessINPUTID] = businessINTPUTVAL;
                           var temp = timeframeCalculation(BusinessStorage[businessPID][i][businessCHKID] , BusinessStorage[businessPID][i][businessINPUTID] , 'business');
                           finalVal += parseFloat(temp);
                       }
                   }
               }
             //  if (BusinessStorage.hasOwnProperty(businessPID)) {
             //     if (BusinessStorage[businessPID]){
             //      var keys = Object.keys(BusinessStorage[businessPID]);
             //      console.log(keys);
             //      for(var i=0;i<keys.length;i++) {
             //         BusinessStorage[businessPID][keys[i]][businessINPUTID] = businessINTPUTVAL;
             //      }
             //     }
             // }
             localStorage.setItem('business_expenses', JSON.stringify(BusinessStorage));
           });

            console.log(finalVal , 'finalVal');
            
           var business_amount_sum = finalVal;
           var business_exp_gst_incl = 0;
           $('.business_fill_data').each(function(){
           if(!$(this).val()){
              $(this).val() = 0;
           }
            if($(this).attr('gst-incl') == 'true'){
             business_exp_gst_incl += parseFloat($(this).val());
            }
            // business_amount_sum += parseFloat($(this).val());
           });

            $('.business_expense_total').val(0);
            $('#business_exp_est').val((business_amount_sum).toFixed(2));
              localStorage.setItem('business_exp_gst_incl',business_exp_gst_incl);
            if(business_exp_gst_incl != null && business_exp_gst_incl != 0) {
              localStorage.setItem('business_exp_gst_incl_tax',(parseFloat(business_exp_gst_incl) / 100) * $('.gst-percentage').val());
              business_exp_gst_incl = parseFloat(business_exp_gst_incl) + (parseFloat(business_exp_gst_incl) / 100) * $('.gst-percentage').val();
              $('#business_exp_gst_incl').val(business_exp_gst_incl);
              
            }else {
              localStorage.setItem('business_exp_gst_incl_tax',0);
              $('#business_exp_gst_incl').val(business_exp_gst_incl);
            }
        });

        //  CRUD ~~~~~
        //  on change event input fill
        $(document).on('keyup change','.business_fill_data',function(){
            var BusinessStorage = JSON.parse(localStorage.getItem('business_expenses'));
            var businessPID = $(this).attr('data-business-parent-id');
            var businessINPUTID = $(this).attr('data-business-input-id');
            var businessINTPUTVAL = $(this).val();
            $(this).val(businessINTPUTVAL);
            if (BusinessStorage.hasOwnProperty(businessPID)) {
                if (BusinessStorage[businessPID]){
                    for (i = 0; i < BusinessStorage[businessPID].length; i++) { console.log(BusinessStorage[businessPID][i].hasOwnProperty(businessINPUTID))
                        if (BusinessStorage[businessPID] && BusinessStorage[businessPID][i].hasOwnProperty(businessINPUTID)) {
                            BusinessStorage[businessPID][i][businessINPUTID] = businessINTPUTVAL;
                        }
                    }
                }
                // if (BusinessStorage[businessPID] && BusinessStorage[businessPID].hasOwnProperty(businessINPUTID)){
                //     BusinessStorage[businessPID][businessINPUTID] = businessINTPUTVAL;
                // }
            }
            localStorage.setItem('business_expenses', JSON.stringify(BusinessStorage));
        });

        // add section
        var businessJSONObejct = {};
        $(document).on('click','#add-business-section',function(){
            if (localStorage.getItem('business_expenses')){
                businessJSONObejct = JSON.parse(localStorage.getItem('business_expenses')); // add previous data as it is
                businessJSONObejct[$('.business_section_name').val()] = []; // add new section
            }
            businessJSONObejct[$('.business_section_name').val()] = []; // add new section
            rendorBusinessJSONObejct(businessJSONObejct);
            localStorage.setItem('business_expenses',JSON.stringify(businessJSONObejct));
        });
        $(document).on('click','.add_new_business_input_btn',function(){
            $('.add_new_business_field_btn').removeProp('data-section-business-id');
            $('.add_new_business_field_btn').attr('data-section-business-id',$(this).attr('data-section-business-id'));
        });

        // delete section
        $(document).on('click', '.delete_business_section_btn', function () {
            var localData = JSON.parse(localStorage.getItem('business_expenses'));
            var dId= $(this).attr('data-business-section-id');

            if (localData.hasOwnProperty(dId)){
                delete localData[dId];
                console.log('deleted successfuly!');
            }
            localStorage.setItem('business_expenses', JSON.stringify(localData));
            $(this).parent().parent().remove();
        });

        // add field
        $(document).on('click', '.add_new_business_field_btn', function () {
            var ContentsArray = JSON.parse(localStorage.getItem('business_expenses'));
            var inputLable = $('.business_field_name').val();
            var selectedSection = $(this).attr('data-section-business-id');
            ContentsArray[selectedSection].push({ [inputLable] : 0 , "Incl. Tax" : 0, "timeframe" : "weekly" });
            // if (ContentsArray.hasOwnProperty(selectedSection)) {
            //     ContentsArray[selectedSection][inputLable] = 0;
            // }
            localStorage.setItem('business_expenses', JSON.stringify(ContentsArray));
            var tempArray = JSON.parse(localStorage.getItem('business_expenses'));
            rendorBusinessJSONObejct(tempArray);
            $('.timeframe').selectpicker('refresh');
        });

        // delete field
        $(document).on('click', '.remove_business_section_field_btn', function () {
            var LocalStorageData = JSON.parse(localStorage.getItem('business_expenses'));
            var selectForDelete = $(this).attr('data-business-field-id');
            var selectedParentId = $(this).attr('data-business-parent-id');
            if (LocalStorageData.hasOwnProperty(selectedParentId)) {
                if (LocalStorageData[selectedParentId]){
                    for (i = 0; i < LocalStorageData[selectedParentId].length; i++) {
                        if (LocalStorageData[selectedParentId] && LocalStorageData[selectedParentId][i].hasOwnProperty(selectForDelete)) {
                            var removeIndex = LocalStorageData[selectedParentId].indexOf(LocalStorageData[selectedParentId][i]);
                            console.log(removeIndex,LocalStorageData[selectedParentId][i])
                            delete LocalStorageData[selectedParentId].splice(removeIndex, 1);
                        }
                    }
                }
                // if (LocalStorageData[selectedParentId] && LocalStorageData[selectedParentId].hasOwnProperty(selectForDelete)) {
                //     delete LocalStorageData[selectedParentId][selectForDelete];
                // }
            }
            localStorage.setItem('business_expenses', JSON.stringify(LocalStorageData));
            $(this).parent().parent().remove();
        });

        // render json object to html
        function rendorBusinessJSONObejct(JsonObj) {
            var business_contents = '';
            business_contents +='<div class="business-section">';

            var $keys = Object.keys(JsonObj).sort(function (a, b) { return a.toLowerCase().localeCompare(b.toLowerCase()); });
            for (var i=0;i<$keys.length ; i++) {

            var $FieldKeys = Object.keys(JsonObj[$keys[i]]);
            var fields = '';
                fields += '<tr>';

                for (var j=0;j<$FieldKeys.length ; j++) {
                   var fieldVal = 0;
                   var checkboxVal = 0;
                   var gstIncl = 'false' , timeframeBoxVal = "weekly";
                   var $inputFieldKeys = Object.keys(JsonObj[$keys[i]][$FieldKeys[j]]);
                    for (var k = 0; k < $inputFieldKeys.length; k++) {
                      if (JsonObj[$keys[i]][$FieldKeys[j]][$inputFieldKeys[0]]){
                        fieldVal = JsonObj[$keys[i]][$FieldKeys[j]][$inputFieldKeys[0]];
                      } else {
                        fieldVal = 0;
                      }
                      if (JsonObj[$keys[i]][$FieldKeys[j]][$inputFieldKeys[1]]){
                        checkboxVal = JsonObj[$keys[i]][$FieldKeys[j]][$inputFieldKeys[1]];
                      } else {
                        checkboxVal = 0;
                      }
                       if (JsonObj[$keys[i]][$FieldKeys[j]][$inputFieldKeys[2]]){
                        timeframeBoxVal = JsonObj[$keys[i]][$FieldKeys[j]][$inputFieldKeys[2]];
                      }
                    }
                    if(checkboxVal == 1) {
                      var is_check = 'checked';
                      gstIncl = 'true';
                    } else {
                      var is_check = '';
                      gstIncl = 'false';
                    }

                    var weekly_selected = '' ,fortnightly_selected = '' , monthly_selected = '', annually_selected = '';
                 
                    if(timeframeBoxVal == 'weekly') 
                    {
                      weekly_selected = 'selected';
                    } 
                    else if(timeframeBoxVal == 'fortnightly') 
                    {
                      fortnightly_selected = 'selected';
                    } 
                    else if (timeframeBoxVal == 'monthly') 
                    {
                      monthly_selected = 'selected';
                    } 
                    else if (timeframeBoxVal == 'yearly') 
                    {
                      annually_selected = 'selected';
                    }

                    var chkBox = '<div class="checkbox clip-check check-primary checkbox-inline m-b-0"><input data-business-parent-id="'+$keys[i]+
                       '"'+is_check+' data-business-check-id="'+$inputFieldKeys[0]+'" id="checkbox" type="checkbox" value="'+ checkboxVal +'" class="chk" name="Incl. Tax"><label for="checkbox'+$FieldKeys[j]+'"></label></div>';

                    var timeframeBox = '<select data-business-parent-id="'+$keys[i]+'" data-business-timeframe-id="'+$inputFieldKeys[0]+'" name="timeframe" class="timeframe form-control">' +
                                      '<option value="" disabled>Select Timeframe</option>' +
                                      '<option value="weekly" '+weekly_selected+'>Weekly</option>' +
                                      '<option value="fortnightly" '+fortnightly_selected+'>Fortnightly</option>' +
                                      '<option value="monthly" '+monthly_selected+'>Monthly</option>' +
                                      '<option value="annually" '+annually_selected+'>Annually</option></select>';   
                     fields +='<tr class="dynamic_input_box" style="width: 100%;">' +
                         '<td class="business input_name w-20 text-left" data-id="'+j+'" data-business-parent-id="'+$keys[i]+'" data-business-input-id="'+$inputFieldKeys[0]+'">'+
                        $inputFieldKeys[0]+'</td><td class="w-20 input_value"><input gst-incl="' + gstIncl +'" class="form-control input_field_value allowNumericWithDecimalOnly' +
                        ' business_fill_data" data-business-parent-id="'+$keys[i]+'" data-business-input-id="'+$inputFieldKeys[0]+'"' +
                        ' placeholder="$11,200.00" type="text" value="'+fieldVal+'"></td>' +
                        '<td class="w-20">' + 'Inc. GST? &nbsp;' +
                       ''+chkBox+'</td>' + '<td class="w-20 timeframe-td"> '+timeframeBox+' </td>' +
                        '<td class="w-10"><a style="margin-right: 1px;margin-bottom: 3px;" class="btn btn-xs btn-default tooltips pull-right remove_business_section_field_btn"' +
                        'role="button" value="Small Default" data-business-parent-id="'+$keys[i]+
                        '"title="Remove field" data-business-field-id="'+$inputFieldKeys[0]+'">' +
                        '<i class="fa fa-trash" style="color:#ff4401;"></i></a></strong>' +
                        '<a style="margin-right: 1px;margin-bottom: 3px;" class="btn btn-xs btn-default tooltips pull-right business edit_business_section_field_btn"' +
                        'role="button" value="Small Default" data-business-parent-id="'+$keys[i]+
                        '"title="Remove field" data-business-field-id="'+$inputFieldKeys[0]+'">' + 
                        '<i class="fa fa-pencil" style="color:#ff4401;"></i></a></strong>' +
                        '</td></tr>';
                }
                fields += '</tbody></table>';

                business_contents +=  '<div class="business-dynamic-section"><strong class="dynamic_title" data-title="'+$keys[i]+'">' + $keys[i] +
                    '<a style="margin-right: 1px;margin-bottom: 3px;" class="btn btn-xs btn-default tooltips p-y-0 pull-right delete_business_section_btn"' +
                    'role="button" value="Small Default" title="Remove section" data-business-section-id="'+$keys[i]+'">' +
                    '<i class="fa fa-trash" style="color:#ff4401;"></i></a>' +
                    '<a style="margin-right: 1px;margin-bottom: 3px;" class="btn btn-xs btn-default tooltips p-y-0 pull-right business edit_business_section_btn"' +
                   'role="button" value="Small Default" title="Edit section" data-business-section-id="'+$keys[i]+'">' +
                   '<i class="fa fa-pencil" style="color:#ff4401;"></i></a>' +
                    '<a style="margin-right: 1px;margin-bottom: 3px;" class="btn btn-xs btn-default tooltips p-y-0 pull-right add_new_business_input_btn"' +
                     'data-toggle="modal" href="#business-field-modal" data-section-business-id="'+$keys[i]+
                     '"role="button" value="Small Default" title="Add new field">' +
                     '<i class="fa fa-plus-circle" style="color:#ff4401;"></i></a></strong>' +
                    '<div class="responsive-table"><div class="scrollable-area">' +
                    '<table class="table data-table table table-bordered table-striped"><tbody>' +fields+
                    '</div></div></div></tbody></table>';
                business_contents +='<br>';
            }
            business_contents +='</div>';
            // add to html
            $('.business-expense-content').html(business_contents);
            enableCheckbox();
        }

        // previous code...
        $('.overflow').click(function(){
            $('#business-modal').css('overflow-y','auto','important');
        });

/* * * * * * * * * * * * * end business expenses  * * * * * * * * * * * * */

/* * * * * * * * * * * * * start living expenses  * * * * * * * * * * * * */
    // var livinObject = {"Premises":{"Rent":0,"Mortgage / Insurance":0,"Power":0,"Rates":0},"Communication":{"Landline":0,"Mobile":0,"Internet":0},"Vehicle / Travel":{"Car repayments":0,"Petrol":0,"Mileage":0,"WOF":0,"Rego":0,"Car service / Repairs":0,"Parking":0,"Bus / Train fares":0},"Office & Stationary":{"Furniture":0,"Insurance":0,"Maintenance":0,"Postage & PO Box rental":0,"Printing":0,"Staff salaries":0},"Marketing":{"Website hosting":0,"Advertising":0,"Entertainment":0},"Training":{"Workshops / Courses":0,"Professional Memberships":0,"Conferences":0},"Legal / Accounting":{"Accountant fees":0,"Other professionals":0,"Taxes":0},"Misc Costs":{"Debt":0,"Bank fees":0,"Depreciation":0,"Drawings":0}};
    var livinObject = {"Premises":[{"Rent":0,"Incl. Tax":1, "timeframe" : "weekly"},{"Mortgage / Insurance":0,"Incl. Tax":1, "timeframe" : "weekly"},{"Power":0,"Incl. Tax":1, "timeframe" : "weekly"},{"Rates":0,"Incl. Tax":1, "timeframe" : "weekly"}],"Communication":[{"Landline":0,"Incl. Tax":1, "timeframe" : "weekly"},{"Mobile":0,"Incl. Tax":1, "timeframe" : "weekly"},{"Internet":0,"Incl. Tax":1, "timeframe" : "weekly"}],"Vehicle / Travel":[{"Car repayments":0,"Incl. Tax":1, "timeframe" : "weekly"},{"Petrol":0,"Incl. Tax":1, "timeframe" : "weekly"},{"Mileage":0,"Incl. Tax":1, "timeframe" : "weekly"},{"WOF":0,"Incl. Tax":1, "timeframe" : "weekly"},{"Rego":0,"Incl. Tax":1, "timeframe" : "weekly"},{"Car service / Repairs":0,"Incl. Tax":1, "timeframe" : "weekly"},{"Parking":0,"Incl. Tax":1, "timeframe" : "weekly"},{"Bus / Train fares":0,"Incl. Tax":1, "timeframe" : "weekly"}],"Office & Stationary":[{"Furniture":0,"Incl. Tax":1, "timeframe" : "weekly"},{"Insurance":0,"Incl. Tax":1, "timeframe" : "weekly"},{"Maintenance":0,"Incl. Tax":1, "timeframe" : "weekly"},{"Postage & PO Box rental":0,"Incl. Tax":1, "timeframe" : "weekly"},{"Printing":0,"Incl. Tax":1, "timeframe" : "weekly"},{"Staff salaries":0,"Incl. Tax":1, "timeframe" : "weekly"}],"Marketing":[{"Website hosting":0,"Incl. Tax":1, "timeframe" : "weekly"},{"Advertising":0,"Incl. Tax":1, "timeframe" : "weekly"},{"Entertainment":0,"Incl. Tax":1, "timeframe" : "weekly"}],"Training":[{"Workshops / Courses":0,"Incl. Tax":1, "timeframe" : "weekly"},{"Professional Memberships":0,"Incl. Tax":1, "timeframe" : "weekly"},{"Conferences":0,"Incl. Tax":1, "timeframe" : "weekly"}],"Legal / Accounting":[{"Accountant fees":0,"Incl. Tax":1, "timeframe" : "weekly"},{"Other professionals":0,"Incl. Tax":1, "timeframe" : "weekly"},{"Taxes":0,"Incl. Tax":1, "timeframe" : "weekly"}],"Misc Costs":[{"Debt":0,"Incl. Tax":1, "timeframe" : "weekly"},{"Bank fees":0,"Incl. Tax":1, "timeframe" : "weekly"},{"Depreciation":0,"Incl. Tax":1, "timeframe" : "weekly"},{"Drawings":0,"Incl. Tax":1, "timeframe" : "weekly"}]};
    renderDefaultDataToModal('living_expenses',livinObject);

        $(document).on('click','.living_expense_submit',function(){
          
          $('.chk').each(function(){
              var LivingStorage = JSON.parse(localStorage.getItem('living_expenses'));
              var livingPID = $(this).attr('data-living-parent-id');
              var livingCHKID = $(this).attr('data-living-check-id');
              var livingINPUTID = "Incl. Tax";
              var livingINTPUTVAL = $(this).val();
              if (LivingStorage.hasOwnProperty(livingPID)){
                  // console.log('setupPID', setupPID,setupINPUTID)
                  for (i = 0; i < LivingStorage[livingPID].length; i++) {
                      if (LivingStorage[livingPID] && LivingStorage[livingPID][i].hasOwnProperty(livingCHKID)) {
                          console.log(LivingStorage[livingPID][i],livingINPUTID, livingINTPUTVAL)
                          LivingStorage[livingPID][i][livingINPUTID] = livingINTPUTVAL;
                      }
                  }
              }
             //   if (LivingStorage.hasOwnProperty(livingINPUTID)) {
             //     if (LivingStorage[livingINPUTID]){
             //      for(var i=0;i<LivingStorage[livingINPUTID].length;i++) {
             //         LivingStorage[livingINPUTID][i][livingINPUTID] = livingINTPUTVAL;
             //      }
             //     }
             // }
             localStorage.setItem('living_expenses', JSON.stringify(LivingStorage));
          });

          var finalVal = 0;

          $('.timeframe').each(function(){
              var LivingStorage = JSON.parse(localStorage.getItem('living_expenses'));
              var livingPID = $(this).attr('data-living-parent-id');
              var livingCHKID = $(this).attr('data-living-timeframe-id');
              var livingINPUTID = "timeframe";
              var livingINTPUTVAL = $(this).val();
              if (LivingStorage.hasOwnProperty(livingPID)){
                  // console.log('setupPID', setupPID,setupINPUTID)
                  for (i = 0; i < LivingStorage[livingPID].length; i++) {
                      if (LivingStorage[livingPID] && LivingStorage[livingPID][i].hasOwnProperty(livingCHKID)) {
                          console.log(LivingStorage[livingPID][i],livingINPUTID, livingINTPUTVAL)
                          LivingStorage[livingPID][i][livingINPUTID] = livingINTPUTVAL;
                           var temp = timeframeCalculation(LivingStorage[livingPID][i][livingCHKID] , LivingStorage[livingPID][i][livingINPUTID] , null);
                           finalVal += parseFloat(temp);
                      }
                  }
              }
             //   if (LivingStorage.hasOwnProperty(livingINPUTID)) {
             //     if (LivingStorage[livingINPUTID]){
             //      for(var i=0;i<LivingStorage[livingINPUTID].length;i++) {
             //         LivingStorage[livingINPUTID][i][livingINPUTID] = livingINTPUTVAL;
             //      }
             //     }
             // }
             localStorage.setItem('living_expenses', JSON.stringify(LivingStorage));
          });

           var living_amount_sum = finalVal;
           var living_exp_gst_incl = 0;
           $('.living_fill_data').each(function(){
           if(!$(this).val()){
              $(this).val() = 0;
           }
            if($(this).attr('gst-incl') == 'true'){
             living_exp_gst_incl += parseFloat($(this).val());
            }
            // living_amount_sum += parseFloat($(this).val());
           });

            localStorage.setItem('living_exp_gst_incl',living_exp_gst_incl);
            $('.living_expense_total').val(0);
            $('#living_exp_est').val((living_amount_sum).toFixed(2));
            $('#living_exp_est').trigger('change');
        });


        //  CRUD ~~~~
        //  on change event input fill
        $(document).on('keyup change','.living_fill_data',function(){
            var LivingStorage = JSON.parse(localStorage.getItem('living_expenses'));
            var livingPID = $(this).attr('data-living-parent-id');
            var livingINPUTID = $(this).attr('data-living-input-id');
            var livingINTPUTVAL = $(this).val();
            $(this).val(livingINTPUTVAL);
            if (LivingStorage.hasOwnProperty(livingPID)) {
                if (LivingStorage[livingPID]){
                    for (i = 0; i < LivingStorage[livingPID].length; i++) {
                        if (LivingStorage[livingPID] && LivingStorage[livingPID][i].hasOwnProperty(livingINPUTID)) {
                            LivingStorage[livingPID][i][livingINPUTID] = livingINTPUTVAL;
                        }
                    }
                }
                // if (LivingStorage[livingPID] && LivingStorage[livingPID].hasOwnProperty(livingINPUTID)){
                //     LivingStorage[livingPID][livingINPUTID] = livingINTPUTVAL;
                // }
            }
            localStorage.setItem('living_expenses', JSON.stringify(LivingStorage));
        });

        // add section
        var livingJSONObejct = {};
        $(document).on('click','#add-living-section',function(){
            if (localStorage.getItem('living_expenses')){
                livingJSONObejct = JSON.parse(localStorage.getItem('living_expenses')); // add previous data as it is
                livingJSONObejct[$('.living_section_name').val()] = []; // add new section
            }
            livingJSONObejct[$('.living_section_name').val()] = []; // add new section
            rendorLivingJSONObejct(livingJSONObejct);
            localStorage.setItem('living_expenses',JSON.stringify(livingJSONObejct));
        });
        $(document).on('click','.add_new_living_input_btn',function(){
            $('.add_new_living_field_btn').removeProp('data-section-living-id');
            $('.add_new_living_field_btn').attr('data-section-living-id',$(this).attr('data-section-living-id'));
        });

        // delete section
        $(document).on('click', '.delete_living_section_btn', function () {
            var localData = JSON.parse(localStorage.getItem('living_expenses'));
            var dId= $(this).attr('data-living-section-id');

            if (localData.hasOwnProperty(dId)){
                delete localData[dId];
                console.log('deleted successfuly!');
            }
            localStorage.setItem('living_expenses', JSON.stringify(localData));
            $(this).parent().parent().remove();
        });

        // add field
        $(document).on('click', '.add_new_living_field_btn', function () {
            var ContentsArray = JSON.parse(localStorage.getItem('living_expenses'));
            var inputLable = $('.living_field_name').val();
            var selectedSection = $(this).attr('data-section-living-id');
            ContentsArray[selectedSection].push({ [inputLable] : 0 , "Incl. Tax" : 1 ,"timeframe":"weekly"});
            // if (ContentsArray.hasOwnProperty(selectedSection)) {
            //     ContentsArray[selectedSection][inputLable] = 0;
            // }
            localStorage.setItem('living_expenses', JSON.stringify(ContentsArray));
            var tempArray = JSON.parse(localStorage.getItem('living_expenses'));
            rendorLivingJSONObejct(tempArray);
            $('.timeframe').selectpicker('refresh');
        });

        // delete field
        $(document).on('click', '.remove_living_section_field_btn', function () {
            var LocalStorageData = JSON.parse(localStorage.getItem('living_expenses'));
            var selectForDelete = $(this).attr('data-living-field-id');
            var selectedParentId = $(this).attr('data-living-parent-id');
            if (LocalStorageData.hasOwnProperty(selectedParentId)) {
                if (LocalStorageData[selectedParentId]){
                    for (i = 0; i < LocalStorageData[selectedParentId].length; i++) {
                        if (LocalStorageData[selectedParentId] && LocalStorageData[selectedParentId][i].hasOwnProperty(selectForDelete)) {
                            var removeIndex = LocalStorageData[selectedParentId].indexOf(LocalStorageData[selectedParentId][i]);
                            console.log(removeIndex,LocalStorageData[selectedParentId][i])
                            delete LocalStorageData[selectedParentId].splice(removeIndex, 1);
                        }
                    }
                }
                // if (LocalStorageData[selectedParentId] && LocalStorageData[selectedParentId].hasOwnProperty(selectForDelete)) {
                //     delete LocalStorageData[selectedParentId][selectForDelete];
                // }
            }
            localStorage.setItem('living_expenses', JSON.stringify(LocalStorageData));
            $(this).parent().parent().remove();
        });

        // render json object to html
        function rendorLivingJSONObejct(JsonObj) {
            var living_contents = '';
            living_contents +='<div class="living-section">';

            var $keys = Object.keys(JsonObj).sort(function (a, b) { return a.toLowerCase().localeCompare(b.toLowerCase()); });

            for (var i=0;i<$keys.length ; i++) {

            var $FieldKeys = Object.keys(JsonObj[$keys[i]]);
            var fields = '';
                fields += '<tr>';

                for (var j=0;j<$FieldKeys.length ; j++) {
                   var fieldVal = 0;
                   var checkboxVal = 0;
                   var is_check = 'checked' , timeframeBoxVal = 'weekly';
                   var $inputFieldKeys = Object.keys(JsonObj[$keys[i]][$FieldKeys[j]]);
                    for (var k = 0; k < $inputFieldKeys.length; k++) {
                      if (JsonObj[$keys[i]][$FieldKeys[j]][$inputFieldKeys[0]]){
                        fieldVal = JsonObj[$keys[i]][$FieldKeys[j]][$inputFieldKeys[0]];
                      } else {
                        fieldVal = 0;
                      }
                      if (JsonObj[$keys[i]][$FieldKeys[j]][$inputFieldKeys[1]]){
                        checkboxVal = JsonObj[$keys[i]][$FieldKeys[j]][$inputFieldKeys[1]];
                      } else {
                        checkboxVal = 0;
                      }
                      if (JsonObj[$keys[i]][$FieldKeys[j]][$inputFieldKeys[2]]){
                        timeframeBoxVal = JsonObj[$keys[i]][$FieldKeys[j]][$inputFieldKeys[2]];
                      }
                    }

                    var weekly_selected = '' ,fortnightly_selected = '' , monthly_selected = '', annually_selected = '';
                    
                    if(timeframeBoxVal == 'weekly') 
                    {
                      weekly_selected = 'selected';
                    } 
                    else if(timeframeBoxVal == 'fortnightly') 
                    {
                      fortnightly_selected = 'selected';
                    } 
                    else if (timeframeBoxVal == 'monthly') 
                    {
                      monthly_selected = 'selected';
                    } 
                    else if (timeframeBoxVal == 'yearly') 
                    {
                      annually_selected = 'selected';
                    }

                    var chkBox = '<div class="checkbox clip-check check-primary checkbox-inline m-b-0 businesschk"><input data-living-parent-id="'+$keys[i]+
                       '"'+is_check+' data-living-check-id="'+$inputFieldKeys[0]+'" id="checkbox" type="checkbox" value="1" class="chk" name="Incl. Tax" disabled><label for="checkbox'+$FieldKeys[j]+'"></label></div>';

                   var timeframeBox = '<select data-living-parent-id="'+$keys[i]+'" data-living-timeframe-id="'+$inputFieldKeys[0]+'" name="timeframe" class="timeframe form-control">' +
                                      '<option value="" disabled>Select Timeframe</option>' +
                                      '<option value="weekly" '+weekly_selected+'>Weekly</option>' +
                                      '<option value="fortnightly" '+fortnightly_selected+'>Fortnightly</option>' +
                                      '<option value="monthly" '+monthly_selected+'>Monthly</option>' +
                                      '<option value="annually" '+annually_selected+'>Annually</option></select>';   
                    fields +='<tr class="dynamic_input_box" style="width: 100%;">' +
                        '<td class="living input_name w-20 text-left" data-id="'+j+'" data-living-parent-id="'+$keys[i]+'" data-living-input-id="'+$inputFieldKeys[0]+'">' +
                        $inputFieldKeys[0]+'</td><td class="w-20 input_value"><input gst-incl="true" class="form-control input_field_value allowNumericWithDecimalOnly' +
                        ' living_fill_data" data-living-parent-id="'+$keys[i]+'" data-living-input-id="'+$inputFieldKeys[0]+'"' +
                        ' placeholder="$11,200.00" type="text" value="'+fieldVal+'"></td>' +
                        '<td class="w-20">' + 'Inc. GST? &nbsp;' + 
                       ''+chkBox+'</td>' + '<td class="w-20 timeframe-td"> '+timeframeBox+' </td>' +
                        '<td class="w-10"><a style="margin-right: 1px;margin-bottom: 3px;" class="btn btn-xs btn-default tooltips pull-right remove_living_section_field_btn"' +
                        'role="button" value="Small Default" data-living-parent-id="'+$keys[i]+
                        '"title="Remove field" data-living-field-id="'+$inputFieldKeys[0]+'">' +
                        '<i class="fa fa-trash" style="color:#ff4401;"></i></a></strong>' +
                        '<a style="margin-right: 1px;margin-bottom: 3px;" class="btn btn-xs btn-default tooltips pull-right living edit_living_section_field_btn"' +
                        'role="button" value="Small Default" data-living-parent-id="'+$keys[i]+
                        '"title="Remove field" data-living-field-id="'+$inputFieldKeys[0]+'">' +
                        '<i class="fa fa-pencil" style="color:#ff4401;"></i></a></strong>' +
                        '</td></tr>';
                }
                fields += '</tbody></table>';
                living_contents +=  '<div class="living-dynamic-section"><strong class="dynamic_title" data-title="'+$keys[i]+'">' + $keys[i] +
                    '<a style="margin-right: 1px;margin-bottom: 3px;" class="btn btn-xs btn-default tooltips p-y-0 pull-right delete_living_section_btn"' +
                    'role="button" value="Small Default" title="Remove section" data-living-section-id="'+$keys[i]+'">' +
                    '<i class="fa fa-trash" style="color:#ff4401;"></i></a>' +
                    '<a style="margin-right: 1px;margin-bottom: 3px;" class="btn btn-xs btn-default tooltips p-y-0 pull-right living edit_living_section_btn"' +
                   'role="button" value="Small Default" title="Edit section" data-living-section-id="'+$keys[i]+'">' +
                   '<i class="fa fa-pencil" style="color:#ff4401;"></i></a>' +
                    '<a style="margin-right: 1px;margin-bottom: 3px;" class="btn btn-xs btn-default tooltips p-y-0 pull-right add_new_living_input_btn"' +
                     'data-toggle="modal" href="#living-field-modal" data-section-living-id="'+$keys[i]+
                     '"role="button" value="Small Default" title="Add new field">' +
                     '<i class="fa fa-plus-circle" style="color:#ff4401;"></i></a></strong>' +
                    '<div class="responsive-table"><div class="scrollable-area">' +
                    '<table class="table data-table table table-bordered table-striped"><tbody>' +fields+
                    '</div></div></div></tbody></table>';
                living_contents +='<br>';
            }
            living_contents +='</div>';
            // add to html
            $('.living-expense-content').html(living_contents);
            enableCheckbox();
        }

        // previous code...
        $('.overflow').click(function(){
            $('#living-modal').css('overflow-y','auto','important');
        });

/* * * * * * * * * * * * * end living expenses  * * * * * * * * * * * * */


/* * * * * * * * * * * * * Editable Labels  * * * * * * * * * * * * */

 $(document).on("click", ".edit_setup_section_field_btn , .edit_business_section_field_btn, .edit_living_section_field_btn", function (evt) {

     if ($(this).hasClass('living')){
         console.log('living');
         var $targetLabel = $(this).closest('.dynamic_input_box').find('.input_name');
         var txt = $targetLabel.text();
         var parentId = $targetLabel.attr('data-living-parent-id');
         var inputId = $targetLabel.attr('data-living-input-id');
         var index = $targetLabel.attr('data-id');
         console.log(parentId, index)
         $targetLabel.replaceWith("<input type='text' class='input_name living' data-id='"+index+"' data-living-input-id='"+inputId+"' data-living-parent-id='"+parentId+"'>");
     }
     else if ($(this).hasClass('business')){
         console.log('business');
         var $targetLabel = $(this).closest('.dynamic_input_box').find('.input_name');
         var txt = $targetLabel.text();
         var parentId = $targetLabel.attr('data-business-parent-id');
         var inputId = $targetLabel.attr('data-business-input-id');
         var index = $targetLabel.attr('data-id');
         console.log(parentId, index)
         $targetLabel.replaceWith("<input type='text' class='input_name business' data-id='"+index+"' data-business-input-id='"+inputId+"' data-business-parent-id='"+parentId+"'>");
     }else{
         evt.preventDefault();
         var $targetLabel = $(this).closest('.dynamic_input_box').find('.input_name');
         var txt = $targetLabel.text();
         var parentId = $targetLabel.attr('data-setup-parent-id');
         var inputId = $targetLabel.attr('data-setup-input-id');
         var index = $targetLabel.attr('data-id');
         console.log(parentId, index)
         $targetLabel.replaceWith("<input type='text' class='input_name' data-id='"+index+"' data-setup-input-id='"+inputId+"' data-setup-parent-id='"+parentId+"'>");
     }
      $('input.input_name').val(txt);
      $('input.input_name').focus();
  });

  $(document).on("blur", "input.input_name", function (evt) {

      if ($(this).hasClass('living')){
          console.log('living');
          var setupPID = $(this).attr('data-living-parent-id');
          var setupINPUTID = $(this).attr('data-living-input-id');
          var setupINTPUTVAL = $(this).val();
          $(this).val(setupINTPUTVAL);
          $(this).closest('.dynamic_input_box').find('.input_value input').attr('data-living-input-id', setupINTPUTVAL);
          $(this).closest('.dynamic_input_box').find('a.remove_setup_section_field_btn').attr('data-living-input-id', setupINTPUTVAL);
          $(this).closest('.dynamic_input_box').find('.checkbox .chk').attr('data-living-input-id', setupINTPUTVAL);

          // evt.preventDefault();
          var $targetInput = $(evt.target).closest('input.input_name');
          var txt = $targetInput.val();console.log('txt',txt)
          var parentId = $targetInput.attr('data-living-parent-id');
          var inputId = $targetInput.attr('data-living-input-id');
          var index = $targetInput.attr('data-id');
          $targetInput.replaceWith("<td class='input_name w-35 text-left' data-id='"+index+"' data-living-input-id='"+inputId+"' data-living-parent-id='"+parentId+"'> " + txt +"  </td>");
          var SetupStorage = JSON.parse(localStorage.getItem('living_expenses'));
          var localstorageKey = 'living_expenses';

      }
      else if ($(this).hasClass('business')){
          console.log('business');
          var setupPID = $(this).attr('data-business-parent-id');
          var setupINPUTID = $(this).attr('data-business-input-id');
          var setupINTPUTVAL = $(this).val();
          $(this).val(setupINTPUTVAL);
          $(this).closest('.dynamic_input_box').find('.input_value input').attr('data-business-input-id', setupINTPUTVAL);
          $(this).closest('.dynamic_input_box').find('a.remove_setup_section_field_btn').attr('data-business-input-id', setupINTPUTVAL);
          $(this).closest('.dynamic_input_box').find('.checkbox .chk').attr('data-business-input-id', setupINTPUTVAL);

          // evt.preventDefault();
          var $targetInput = $(evt.target).closest('input.input_name');
          var txt = $targetInput.val();console.log('txt',txt)
          var parentId = $targetInput.attr('data-business-parent-id');
          var inputId = $targetInput.attr('data-business-input-id');
          var index = $targetInput.attr('data-id');
          $targetInput.replaceWith("<td class='input_name w-35 text-left' data-id='"+index+"' data-business-input-id='"+inputId+"' data-business-parent-id='"+parentId+"'> " + txt +"  </td>");
          var SetupStorage = JSON.parse(localStorage.getItem('business_expenses'));
          var localstorageKey = 'business_expenses';

      }else{
          var setupPID = $(this).attr('data-setup-parent-id');
          var setupINPUTID = $(this).attr('data-setup-input-id');
          var setupINTPUTVAL = $(this).val();
          $(this).val(setupINTPUTVAL);
          $(this).closest('.dynamic_input_box').find('.input_value input').attr('data-setup-input-id', setupINTPUTVAL);
          $(this).closest('.dynamic_input_box').find('a.remove_setup_section_field_btn').attr('data-setup-input-id', setupINTPUTVAL);
          $(this).closest('.dynamic_input_box').find('.checkbox .chk').attr('data-setup-input-id', setupINTPUTVAL);

          evt.preventDefault();
          var $targetInput = $(evt.target).closest('input.input_name');
          var txt = $targetInput.val();
          var parentId = $targetInput.attr('data-setup-parent-id');
          var inputId = $targetInput.attr('data-setup-input-id');
          var index = $targetInput.attr('data-id');
          $targetInput.replaceWith("<td class='input_name w-35 text-left' data-id='"+index+"' data-setup-input-id='"+inputId+"' data-setup-parent-id='"+parentId+"'> " + txt +"  </td>");
          var SetupStorage = JSON.parse(localStorage.getItem('setup_expenses'));

          var localstorageKey = 'setup_expenses';
      }

      /* Update local storage */
      if (SetupStorage.hasOwnProperty(setupPID)) {
          if (SetupStorage[setupPID]){

              for (i = 0; i < SetupStorage[setupPID].length; i++) {
                  // console.log('SetupStorage[setupPID]',SetupStorage[setupPID],setupINPUTID)
                  if (SetupStorage[setupPID] && SetupStorage[setupPID][i].hasOwnProperty(setupINPUTID)) {
                      // console.log('SetupStorage[setupPID]',Object.keys(SetupStorage[setupPID][i][setupINPUTID]));
                      var temp = {};

                      temp[setupINTPUTVAL] = SetupStorage[setupPID][i][setupINPUTID];
                      temp['Incl. Tax'] = SetupStorage[setupPID][i]['Incl. Tax'];
                      SetupStorage[setupPID].push(temp);

                      var removeIndex = SetupStorage[setupPID].indexOf(SetupStorage[setupPID][i]);
                      console.log('SetupStorage[setupPID] udpate',SetupStorage[setupPID],removeIndex,SetupStorage[setupPID][i])
                      delete SetupStorage[setupPID].splice(removeIndex, 1);
                  }
              }
              console.log('SetupStorage[setupPID]', SetupStorage[setupPID])
          }
      }
      localStorage.setItem(localstorageKey, JSON.stringify(SetupStorage));
  });

 $(document).on("click", ".edit_setup_section_btn , .edit_business_section_btn, .edit_living_section_btn", function (evt) {
      if ($(this).hasClass('living')){
        var sectionClassName = 'living';
      } else if($(this).hasClass('business')){
        var sectionClassName = 'business';
      } else {
        var sectionClassName = 'setup';
      }
      evt.preventDefault();

      var $targetTitle = $(evt.target).closest('strong.dynamic_title');
      var txt = $targetTitle.text();
      var oldVal = $targetTitle.attr('data-title');
      $targetTitle.replaceWith("<input type='text' class='input_title "+ sectionClassName +"' data-title='"+oldVal+"'>");
      $('input.input_title').val(txt);
      $('input.input_title').focus();
  });

  $(document).on("blur", "input.input_title", function (evt) {

     if ($(this).hasClass('living')){
        evt.preventDefault();
        var sectionClassName = 'living';
        var setupINTPUTVAL = $(this).val();
        var oldVal = $(this).attr('data-title');
        $(this).closest('.living-dynamic-section').find('.dynamic_input_box').each(function () {
            $(this).find('.input_name').attr('data-living-parent-id', setupINTPUTVAL);
            $(this).find('.input_value .living_fill_data').attr('data-living-parent-id', setupINTPUTVAL);
            $(this).find('a.remove_living_section_field_btn').attr('data-living-parent-id', setupINTPUTVAL);
            $(this).find('.checkbox .chk').attr('data-living-parent-id', setupINTPUTVAL);
        });
        var $targetInput = $(evt.target).closest('input.input_title');
        var txt = $targetInput.val();
        var btns =  '<a style="margin-right: 1px;margin-bottom: 3px;" class="btn btn-xs btn-default tooltips p-y-0 pull-right delete_living_section_btn" role="button" value="Small Default" title="Remove section" data-living-section-id="Premises"><i class="fa fa-trash" style="color:#ff4401;"></i></a>' +
                    '<a style="margin-right: 1px;margin-bottom: 3px;" class="btn btn-xs btn-default tooltips p-y-0 pull-right living edit_living_section_btn"' +'role="button" value="Small Default" title="Remove section" data-living-section-id="'+setupINTPUTVAL+'">' + '<i class="fa fa-pencil" style="color:#ff4401;"></i></a>' + 
                    '<a style="margin-right: 1px;margin-bottom: 3px;" class="btn btn-xs btn-default tooltips p-y-0 pull-right add_new_living_input_btn" data-toggle="modal" href="#living-field-modal" data-section-living-id="Premises" role="button" value="Small Default" title="Add new field"><i class="fa fa-plus-circle" style="color:#ff4401;"></i></a>';

        $targetInput.replaceWith("<strong class='dynamic_title' data-title='"+txt+"'> " + txt +" " + btns +"  </strong>");

        var SetupStorage = JSON.parse(localStorage.getItem('living_expenses'));
        var localstorageKey = 'living_expenses';

      } else if($(this).hasClass('business')){
        evt.preventDefault();
        var setupINTPUTVAL = $(this).val();
        var oldVal = $(this).attr('data-title');
        $(this).closest('.business-dynamic-section').find('.dynamic_input_box').each(function () {
            $(this).find('.input_name').attr('data-business-parent-id', setupINTPUTVAL);
            $(this).find('.input_value .business_fill_data').attr('data-business-parent-id', setupINTPUTVAL);
            $(this).find('a.remove_business_section_field_btn').attr('data-business-parent-id', setupINTPUTVAL);
            $(this).find('.checkbox .chk').attr('data-business-parent-id', setupINTPUTVAL);
        });
        var $targetInput = $(evt.target).closest('input.input_title');
        var txt = $targetInput.val();
        var btns =  '<a style="margin-right: 1px;margin-bottom: 3px;" class="btn btn-xs btn-default tooltips p-y-0 pull-right delete_business_section_btn" role="button" value="Small Default" title="Remove section" data-business-section-id="Premises"><i class="fa fa-trash" style="color:#ff4401;"></i></a>' +
                    '<a style="margin-right: 1px;margin-bottom: 3px;" class="btn btn-xs btn-default tooltips p-y-0 pull-right business edit_business_section_btn"' +'role="button" value="Small Default" title="Remove section" data-business-section-id="'+setupINTPUTVAL+'">' + '<i class="fa fa-pencil" style="color:#ff4401;"></i></a>' + 
                    '<a style="margin-right: 1px;margin-bottom: 3px;" class="btn btn-xs btn-default tooltips p-y-0 pull-right add_new_business_input_btn" data-toggle="modal" href="#business-field-modal" data-section-business-id="Premises" role="button" value="Small Default" title="Add new field"><i class="fa fa-plus-circle" style="color:#ff4401;"></i></a>';

        $targetInput.replaceWith("<strong class='dynamic_title' data-title='"+txt+"'> " + txt +" " + btns +"  </strong>");

        var SetupStorage = JSON.parse(localStorage.getItem('business_expenses'));
        var localstorageKey = 'business_expenses';

      } else {
        evt.preventDefault();
        var sectionClassName = 'setup';
        var setupINTPUTVAL = $(this).val();
        var oldVal = $(this).attr('data-title');
        $(this).closest('.setup-dynamic-section').find('.dynamic_input_box').each(function () {
            $(this).find('.input_name').attr('data-setup-parent-id', setupINTPUTVAL);
            $(this).find('.input_value .setup_fill_data').attr('data-setup-parent-id', setupINTPUTVAL);
            $(this).find('a.remove_setup_section_field_btn').attr('data-setup-parent-id', setupINTPUTVAL);
            $(this).find('.checkbox .chk').attr('data-setup-parent-id', setupINTPUTVAL);
        });
        var $targetInput = $(evt.target).closest('input.input_title');
        var txt = $targetInput.val();
        var btns = '<a style="margin-right: 1px;margin-bottom: 3px;" class="btn btn-xs btn-default tooltips p-y-0 pull-right delete_setup_section_btn" role="button" value="Small Default" title="Remove section" data-setup-section-id="Premises"><i class="fa fa-trash" style="color:#ff4401;"></i></a>' +
                    '<a style="margin-right: 1px;margin-bottom: 3px;" class="btn btn-xs btn-default tooltips p-y-0 pull-right edit_setup_section_btn"' +
                     'role="button" value="Small Default" title="Remove section" data-setup-section-id="'+setupINTPUTVAL+'">' +
                     '<i class="fa fa-pencil" style="color:#ff4401;"></i></a>' +
                   '<a style="margin-right: 1px;margin-bottom: 3px;" class="btn btn-xs btn-default tooltips p-y-0 pull-right add_new_setup_input_btn" data-toggle="modal" href="#setup-field-modal" data-section-setup-id="Premises" role="button" value="Small Default" title="Add new field"><i class="fa fa-plus-circle" style="color:#ff4401;"></i></a>';
        $targetInput.replaceWith("<strong class='dynamic_title' data-title='"+txt+"'> " + txt +" " + btns +"  </strong>");
        var SetupStorage = JSON.parse(localStorage.getItem('setup_expenses'));
        var localstorageKey = 'setup_expenses';
      }
      /* Update local storage */
      if (SetupStorage.hasOwnProperty(oldVal)) {
          var temp = SetupStorage[oldVal];

          SetupStorage[txt] = temp;
          delete SetupStorage[oldVal];
      }
      localStorage.setItem(localstorageKey, JSON.stringify(SetupStorage));
  });
/* * * * * * * * * * * * * end Editable Labels  * * * * * * * * * * * * */

/* * * * * * * * * * * * * Custom Checkbox for table  * * * * * * * * * * * * */
$('#business-modal').on('shown.bs.modal' , function(){
  enableCheckbox();
});

function enableCheckbox() {
  $('.checkbox').click(function (event) {
    if (!$(event.target).is('input')) {
       $('input:checkbox', this).prop('checked', function (i, value) {
        if(value == false) {
          $(this).val(1);
          $(this).closest('tr').find('td.input_value input').attr('gst-incl','true');
        } else {
          $(this).val(0);
          $(this).closest('tr').find('td.input_value input').attr('gst-incl','false');
        }
        return !value;
       });
    }
  });
}

/* * * * * * * * * * * * * End : Custom Checkbox for table  * * * * * * * * * * * * */

/* * * * * * * * * * * * * Start : Time Frame Calculations  * * * * * * * * * * * * */

/*
  This Function is to calculate Time frame wise expenses

*/

function timeframeCalculation(fieldVal , timeframeVal , condtion) {

    if(condtion == 'business')
    {
      setting_pref_timeframe = 'monthly';
    }

    //  WEEKLY
    if(setting_pref_timeframe == 'weekly') // if setting and preference timeframe is weekly
    { 
      var temp , weeklyVal = 0 , fortnightlyVal = 2 , monthlyVal = 4.34524 , annuallyVal = 52.1429;

      if(timeframeVal == 'weekly') // check modal time frame
      {
        temp = fieldVal;
      }
      else if (timeframeVal == 'fortnightly')
      {
        temp = fieldVal / fortnightlyVal;
        
      }
      else if (timeframeVal == 'monthly')
      {
        temp = fieldVal / monthlyVal;
      }
      else if (timeframeVal == 'annually')
      {
        temp = fieldVal / annuallyVal;
      }
      console.log(temp , 'temp');
      return temp;
    } 

    //  FORTNIGHTLY
    else if (setting_pref_timeframe == 'fortnightly') // if setting and preference timeframe is fortnightly
    {

      var temp , weeklyVal = 0.5 , fortnightlyVal = 0 , monthlyVal = 2.17262 , annuallyVal = 26.0714;

      if(timeframeVal == 'weekly') // check modal time frame
      {
        temp = fieldVal * weeklyVal;
      }
      else if (timeframeVal == 'fortnightly')
      {
        temp = fieldVal;
        
      }
      else if (timeframeVal == 'monthly')
      {
        temp = fieldVal / monthlyVal;
      }
      else if (timeframeVal == 'annually')
      {
        temp = fieldVal / annuallyVal;
      }
      console.log(temp , 'temp');
      return temp;  
    }

    //  MONTHLY
    else if (setting_pref_timeframe == 'monthly') // if setting and preference timeframe is monthly
    {

      var temp , weeklyVal = 0.230137 , fortnightlyVal = 0.460273 , monthlyVal = 0 , annuallyVal = 12;

      if(timeframeVal == 'weekly') // check modal time frame
      {
        temp = fieldVal * weeklyVal;

      }
      else if (timeframeVal == 'fortnightly')
      {
        temp = fieldVal * fortnightlyVal;
        
      }
      else if (timeframeVal == 'monthly')
      {
        temp = fieldVal;
      }
      else if (timeframeVal == 'annually')
      {
        temp = fieldVal / annuallyVal;
      }
      console.log(temp , 'temp');
      return temp;

    }
    //  ANNUALLY
    else if (setting_pref_timeframe == 'annually') // if setting and preference timeframe is annually
    {

      var temp , weeklyVal = 0.0192 , fortnightlyVal = 0.0383562 , monthlyVal = 0.0821 , annuallyVal = 0;

      if(timeframeVal == 'weekly') // check modal time frame
      {
        temp = fieldVal * weeklyVal;
      }
      else if (timeframeVal == 'fortnightly')
      {
        temp = fieldVal * fortnightlyVal;
        
      }
      else if (timeframeVal == 'monthly')
      {
        temp = fieldVal * monthlyVal;
      }
      else if (timeframeVal == 'annually')
      {
        temp = fieldVal;
      }
      console.log(temp , 'temp');
      return temp;
    }

}

/* * * * * * * * * * * * * End : Time Frame Calculations  * * * * * * * * * * * * */


}); // end of document
