var FormWizard = function() {
	var wizardContent = $('#wizard');
	var wizardForm = $('#form');
	var ifSendAjax = false;
	var stepCompleted = 0;
	var initWizard = function() {
		// function to initiate Wizard Form
		wizardContent.smartWizard({
			selected : 0,
			keyNavigation : false,
			onLeaveStep : leaveAStepCallback,
			onShowStep : onShowStep,
		});
		var numberOfSteps = 0;
		animateBar();
		initValidator();
		
	};

	var animateBar = function(val) {
		if (( typeof val == 'undefined') || val == "") {
			val = 1;
		};
		numberOfSteps = $('.swMain > ul > li').length;
		var valueNow = Math.floor(100 / numberOfSteps * val);
		$('.step-bar').css('width', valueNow + '%');
	};

	var initValidator = function() {
		$.validator.addMethod("cardExpiry", function() {
			//if all values are selected
			if ($("#card_expiry_mm").val() != "" && $("#card_expiry_yyyy").val() != "") {
				return true;
			} else {
				return false;
			}
		}, 'Please select a month and year');
		$.validator.setDefaults({
			errorElement : "span", // contain the error msg in a span tag
			errorClass : 'help-block',
			errorPlacement : function(error, element) {// render error placement for each input type
				if (element.attr("type") == "radio" || element.attr("type") == "checkbox") {// for chosen elements, need to insert the error after the chosen container
					error.insertAfter($(element).closest('.form-group').children('div').children().last());
				} else if (element.attr("name") == "card_expiry_mm" || element.attr("name") == "card_expiry_yyyy") {
					error.appendTo($(element).closest('.form-group').children('div'));
				} else {
					error.insertAfter(element);
					// for other inputs, just perform default behavior
				}
			},
			ignore : ':hidden',
			rules : {
				username : {
					minlength : 2,
					required : true
				},
				email : {
					required : true,
					email : true
				},
				password : {
					minlength : 6,
					required : true
				},
				password_again : {
					required : true,
					minlength : 5,
					equalTo : "#password"
				},
				full_name : {
					required : true,
					minlength : 2,
				},
				phone : {
					required : true
				},
				gender : {
					required : true
				},
				address : {
					required : true
				},
				city : {
					required : true
				},
				country : {
					required : true
				},
				card_name : {
					required : true
				},
				card_number : {
					minlength : 16,
					maxlength : 16,
					required : true
				},
				card_cvc : {
					digits : true,
					required : true,
					minlength : 3,
					maxlength : 4
				},
				card_expiry_yyyy : "cardExpiry",
				payment : {
					required : true,
					minlength : 1
				}
			},
			messages : {
				firstname : "Please specify your first name"
			},
			highlight : function(element) {
				$(element).closest('.help-block').removeClass('valid');
				// display OK icon
				$(element).closest('.form-group').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required');
				// add the Bootstrap error class to the control group
			},
			unhighlight : function(element) {// revert the change done by hightlight
				$(element).closest('.form-group').removeClass('has-error');
				// set error class to the control group
			},
			success : function(label, element) {
				label.addClass('help-block valid');
				// mark the current input as valid and display OK icon
				$(element).closest('.form-group').removeClass('has-error').addClass('has-success').find('.symbol').removeClass('required').addClass('ok');
			}
		});
	};
	
	var onShowStep = function(obj, context) {

		$(".next-step").unbind("click").click(function(e) {
			ifSendAjax = true;
			e.preventDefault();
			var returnStatus = leaveAStepCallback(obj, context, false, $(this).data("step"));
			if(returnStatus === true){
				ifSendAjax = false;
				wizardContent.smartWizard("goForward");
			}

			// var formMode = wizardForm.data('form-mode');
			// if(formMode == 'view')
			// 	ifSendAjax = false;
			// else
			// 	ifSendAjax = true;
			// e.preventDefault();
		});

		$(".back-step").unbind("click").click(function(e) {
			e.preventDefault();
			wizardContent.smartWizard("goBackward");
		});

		$("#finish-parq").unbind("click").click(function(e) {
			ifSendAjax = true;
			e.preventDefault();
			leaveAStepCallback(obj, context, true);
		});

		$(".submit-step").unbind("click").click(function(e) {
			ifSendAjax = true;
			e.preventDefault();
			leaveAStepCallback(obj, context, false, $(this).data("step"));
		});
	};

	var submitStepData = function(obj, stepNumb, toStep, showSuccess){

		if(showSuccess == undefined)
			showSuccess = false;
			
		var stepId = obj.attr('href'),
			formData = {};

			if(toStep != null && toStep != '')
				isNextStepDone = $('a[href="#step-'+toStep+'"]').hasClass('done');
			else
				isNextStepDone = true;
			
		jQuery.each(jQuery(wizardForm).find(stepId).find(':input').serializeArray(), function(i, obj){
			formData[obj.name] = obj.value
		});

		if(stepNumb == 1){
			saveExpenses();
		}
		else if(stepNumb == 2){
			saveIncome();
		}
		else if(stepNumb == 3){
			saveCashflow();
		}else if(stepNumb == 4){
			saveLeadGen();
		}
		
		formData['_token'] = jQuery(wizardForm).find('[name="_token"]').val();
		formData['stepNumb'] = stepNumb;
		
	
		// jQuery.ajax({
		// 	url: public_url+'saveparq',
		// 	type: 'POST',
		// 	data: {
		// 		'formData':formData,
		// 		'_token':formData['_token']
		// 	},
		// 	success: function(data){
		// 		var data = JSON.parse(data);
		// 		if(data.status == "updated"){

		// 			if(stepNumb == 1){
		// 				if(formData['referrer'] != 'onlinesocial' && formData['referrer'] != 'mediapromotions')
		// 					wizardForm.find('input[name="referencewhere"]').val('')
						
		// 			}

		// 			if(stepNumb > stepCompleted){

		// 				stepCompleted = stepNumb;
		// 			}

		// 			var $sucMesElem = $(stepId).find('.sucMes');
		// 			if(showSuccess){
		// 				$sucMesElem.html(data.message);
		// 				$sucMesElem.removeClass('hidden');
		// 			}
		// 			else{
		// 				$sucMesElem.html('');
		// 				$sucMesElem.addClass('hidden');
		// 			}
					
		// 			// if(stepNumb == 5)
		// 			// 	window.location.reload();
		// 		}
		// 		else if(data.status == "error"){
		// 			$.each(data.errorData, function(key, val){
		// 				$.each(this, function(errorType, message){
		// 					if(errorType == "emailExist" )
		// 						var field = $('input[name="primEm"]');	

		// 					setFieldInvalid(field.closest('.form-group'), field.next("span"), message)
		// 				});							
		// 			});
		// 			wizardContent.smartWizard("goBackward");

		// 			if(!isNextStepDone)
		// 				setTimeout(function(){wizardContent.smartWizard('disableStep', toStep)}, 300);
		// 		}
		// 		scrollToTop(stepId);
		// 	}
		// });


	}

	var leaveAStepCallback = function(obj, context, finalStep, fromStep) {

		if(finalStep == undefined)
			finalStep = false;
		if(fromStep == undefined)
			fromStep = '';
			
		var ifSafeToSwitch = false;
		if(!ifSendAjax){
			ifSafeToSwitch = true;
			scrollToTop(obj.attr('href'));
		}
		else{
			ifSendAjax = false;
			if(fromStep && validateSteps(fromStep, fromStep)){
				submitStepData(obj, fromStep, '', true);
				ifSafeToSwitch = true;
			}
			else if(!fromStep && finalStep && validateSteps(4, 4)){

				/*if($("#finish-parq").data('submit') == true)
					submitStepData(obj, 5);
				else
					$("#waiverModal").modal();*/
				submitStepData(obj, 4);
				$("#waiverModal").modal();
				
				ifSafeToSwitch = true;
			}
			// else if(!fromStep && !finalStep && validateSteps(fromStep, fromStep)){

			// 	submitStepData(obj, fromStep, fromStep);
			// 	ifSafeToSwitch = true;
			// }
			else if(!fromStep && !finalStep && validateSteps(context.fromStep, context.toStep)){
				submitStepData(obj, context.fromStep, context.toStep);
				ifSafeToSwitch = true;
			}
		}

		return ifSafeToSwitch;
	};

	var scrollToTop = function(id){
		$("body, html").animate({ 
			scrollTop:$(id).closest('.panel').offset().top 
		}, 200);
	};
	var validateSteps = function(stepnumber, nextstep){

		var isFormValid = wizardForm.valid(),
			$cont = $('#step-'+stepnumber);
                
		if(isFormValid == true){
			$('.anchor').children("li:nth-child(" + stepnumber + ")").children("a").removeClass('wait');
			animateBar(nextstep);
			return true;
		}else{
			return false;
		}

		return false;

	};

	var setFieldInvalid = function ($formGroup, $helpBlock, helpBlockMsg) {
		if($formGroup.hasClass('has-success'))
			$formGroup.removeClass('has-success')
		$formGroup.addClass('has-error');
		$helpBlock.html(helpBlockMsg);
		$helpBlock.show();
	};

	var setFieldValid = function ($formGroup) {
		if($formGroup.hasClass('has-error'))
			$formGroup.removeClass('has-error')
		$formGroup.addClass('has-success');
	};



	var saveExpenses = function () {

                $('#expenses_save').addClass('inProgress').text('').attr('id','temp_expenses_save');
                var tax_type = $('input[name="tax_type"]:checked').val();
                var is_gst_registered = $('.is_gst_registered:checked').val();
                var gst_no = $('.gst-no').val();
                var gst_percentage = $('.gst-percentage').val();

                var setup_expenses= localStorage.getItem('setup_expenses');
                var setup_exp_calculated = $('#setup_expense_total').val();
                var setup_exp_est = $('#setup_exp_est').val();
                var setup_exp_gst_incl = $('#setup_exp_gst_incl').val();
                var business_expenses = localStorage.getItem('business_expenses');
                var business_exp_calculated = $('#business_exp_calculated').val();
                var business_exp_est = $('#business_exp_est').val();
                var business_exp_gst_incl = $('#business_exp_gst_incl').val();
                var living_expenses = localStorage.getItem('living_expenses');
                var living_exp_calculated = $('#living_exp_calculated').val();
                var living_exp_est = $('#living_exp_est').val();
                var living_exp_gst_incl = $('#living_exp_gst_incl').val();
                var turnover_req = parseFloat(setup_exp_gst_incl) + parseFloat(business_exp_gst_incl);
                localStorage.setItem('turnover_req' , turnover_req);
                $.ajax({
                    type: 'post',
                    url: public_url + 'financial-tool/save-expenses',
                    data: {
                    	tax_type:tax_type,
                    	is_gst_registered: is_gst_registered, 
                    	gst_no : gst_no , 
                    	gst_percentage : gst_percentage ,
                        setup_expenses:setup_expenses,
                        setup_exp_calculated:setup_exp_calculated,
                        setup_exp_est:setup_exp_est,
                        setup_exp_gst_incl:setup_exp_gst_incl,
                        business_expenses:business_expenses,
                        business_exp_calculated:business_exp_calculated,
                        business_exp_est:business_exp_est,
                        business_exp_gst_incl:business_exp_gst_incl,
                        living_expenses:living_expenses,
                        living_exp_calculated:living_exp_calculated,
                        living_exp_est:living_exp_est,
                        living_exp_gst_incl:living_exp_gst_incl,
                    	},
                    success: function (res) {
                        $('#temp_expenses_save').removeClass('inProgress').text('Save').attr('id','expenses_save');
                		var turnover_req = localStorage.getItem('turnover_req');
                		$('#turnover_req').val(turnover_req);
                		$('#profit_req').val(living_exp_est);
                		
                		var setup_exp_gst_incl_tax = localStorage.getItem('setup_exp_gst_incl_tax');
		                var business_exp_gst_incl_tax = localStorage.getItem('business_exp_gst_incl_tax');
                		var provisional_tax_gst = parseFloat(setup_exp_gst_incl_tax) + parseFloat(business_exp_gst_incl_tax);
                		// var company_tax_gst = (parseFloat(living_exp_est) / 100) * 30;
                		var company_tax_gst = $('#company_tax').val();
                		var personal_tax = $('#personal_tax').val();
                		// Set tax
                		$('#gst').val(provisional_tax_gst.toFixed(2));
                		var total_income = parseFloat(turnover_req) + 
                							parseFloat(living_exp_est) + 
                								parseFloat(provisional_tax_gst) + 
                									parseFloat(company_tax_gst) + 
                										parseFloat(personal_tax);	
                		if(!total_income) {
                			total_income = 0;
                		}
                		$('#turnover_and_taxes').val(total_income.toFixed(2));
                		$('#ann_working_weeks').val(52);

                		// manully trigger
                		$('#living_exp_est').trigger('change');

                    }
                });
    }

    var saveIncome = function () {
    	
        $('#income_save').addClass('inProgress').text('').attr('id','temp_income_save');
        var turnover_req = $('#turnover_req').val();
        var profit_req = $('#profit_req').val();
        var gst = $('#gst').val();
        var company_tax = $('#company_tax').val();
        var personal_tax = $('#personal_tax').val();
        var partnership_tax = $('#partnership_tax').val();
        var turnover_and_taxes = $('#turnover_and_taxes').val();
        var eqv_full_sess_cor = $('#eqv_full_sess_cor').val();
        var eqv_full_sess_req_for_tot_inc = $('#eqv_full_sess_req_for_tot_inc').val();
        var ann_working_weeks = $('#ann_working_weeks').val();
        var weekly_sess_req = $('#weekly_sess_req').val();
        var avg_sess_pweek_pclient = $('#avg_sess_pweek_pclient').val();
        var no_of_clients = $('#no_of_clients').val();
        var clients_req_eqv_full_sess_cor = $('#clients_req_eqv_full_sess_cor').val();
        var clients_req_eqv_full_sess_req_for_tot_inc = $('#clients_req_eqv_full_sess_req_for_tot_inc').val();
        var clients_req_ann_working_weeks = $('#clients_req_ann_working_weeks').val();
        var clients_req_weekly_sess_req = $('#clients_req_weekly_sess_req').val();
        var clients_req_no_of_clients = $('#clients_req_no_of_clients').val();
        var clients_req_avg_sess_pweek_pclient = $('#clients_req_avg_sess_pweek_pclient').val();
        var session_spots_req = $('#session_spots_req').val();
		var clients_req_to_break_even = $('#clients_req_to_break_even').val();
		var average_client_spend = $('#average_client_spend').val();
		var client_session_spots_req = $('#client_session_spots_req').val();
		var client_clients_req_to_break_even = $('#client_clients_req_to_break_even').val();
		var client_average_client_spend = $('#client_average_client_spend').val();
        $.ajax({
            type: 'post',
            url: public_url + '/financial-tool/save-income/' + recordId,
            data: {id:recordId, turnover_req: turnover_req, profit_req: profit_req, gst: gst,
                company_tax: company_tax, personal_tax: personal_tax,partnership_tax : partnership_tax , turnover_and_taxes: turnover_and_taxes,
                eqv_full_sess_cor: eqv_full_sess_cor, ann_working_weeks: ann_working_weeks,
                eqv_full_sess_req_for_tot_inc:eqv_full_sess_req_for_tot_inc,
                weekly_sess_req:weekly_sess_req, avg_sess_pweek_pclient:avg_sess_pweek_pclient,
                no_of_clients: no_of_clients, clients_req_eqv_full_sess_cor : clients_req_eqv_full_sess_cor,
				clients_req_eqv_full_sess_req_for_tot_inc : clients_req_eqv_full_sess_req_for_tot_inc,
				clients_req_ann_working_weeks : clients_req_ann_working_weeks,
				clients_req_weekly_sess_req : clients_req_weekly_sess_req,
				clients_req_no_of_clients : clients_req_no_of_clients,
				clients_req_avg_sess_pweek_pclient : clients_req_avg_sess_pweek_pclient,
				session_spots_req : session_spots_req , 
				clients_req_to_break_even : clients_req_to_break_even , 
				average_client_spend : average_client_spend , 
				client_session_spots_req : client_session_spots_req , 
				client_clients_req_to_break_even : client_clients_req_to_break_even , 
				client_average_client_spend : client_average_client_spend 
			},
            success: function (res) {
                $('#temp_income_save').removeClass('inProgress').text('Save').attr('id','income_save');
                // var total_monthly_expense = $('#total_monthly_expense').val();
                // var cashflow_forecast_time = $('#cashflow_forecast_time').val();
                // var setup_loan_repayment_per_month_amt = $('#setup_loan_repayment_per_month_amt').val();
                // var cap_req_first_three_months =
                // 	(parseFloat(total_monthly_expense) * cashflow_forecast_time) + (parseFloat(setup_loan_repayment_per_month_amt) * cashflow_forecast_time) ;
                // $('#cap_req_first_three_months').val(cap_req_first_three_months.toFixed(2));
            }
        });
    }

    var saveCashflow = function () {
        $('#cashflow_save').addClass('inProgress').text('').attr('id','temp_cashflow_save');
        var cap_req_first_three_months = $('#cap_req_first_three_months').val();
        var presonal_capital_available = $('#presonal_capital_available').val();

        var cashflow_forecast_time = $('#cashflow_forecast_time').val();
		var monthly_business_cost = $('#monthly_business_cost').val();
		var monthly_personal_cost = $('#monthly_personal_cost').val();
		var total_monthly_expense = $('#total_monthly_expense').val();
		var cashflow_setup_expense = $('#cashflow_setup_expense').val();
		var setup_loan_repayment_period_month_time = $('#setup_loan_repayment_period_month_time').val();
		var setup_loan_repayment_per_month_amt = $('#setup_loan_repayment_per_month_amt').val();
		var loan_req = $('#loan_req').val();
		var monthly_req = $('#monthly_req').val();
        $.ajax({
            type: 'post',
            url: public_url + '/financial-tool/save-cashflow/'+recordId,
            data: {id:recordId, cap_req_first_three_months:cap_req_first_three_months,
                presonal_capital_available:presonal_capital_available,
                cashflow_forecast_time : cashflow_forecast_time ,
				monthly_business_cost : monthly_business_cost ,
				monthly_personal_cost : monthly_personal_cost ,
				total_monthly_expense : total_monthly_expense ,
				cashflow_setup_expense : cashflow_setup_expense ,
				setup_loan_repayment_period_month_time : setup_loan_repayment_period_month_time ,
				setup_loan_repayment_per_month_amt : setup_loan_repayment_per_month_amt ,
				loan_req : loan_req ,
				monthly_req : monthly_req ,
            },
            success: function (res) {
                $('#temp_cashflow_save').removeClass('inProgress').text('Save').attr('id','cashflow_save');
            }
        });
    }



    var saveLeadGen = function (){
        $('#lead_gen_save').addClass('inProgress').text('').attr('id','temp_lead_gen_save');
        var total_client_req = $('#total_client_req').val();
        var projected_conv_rate = $('#projected_conv_rate').val();
        var consultations_req = $('#consultations_req').val();
        var leads_req = $('#leads_req').val();
        var avg_lead_gen_req_per_week_twlv = $('#avg_lead_gen_req_per_week_twlv').val();
        var lead_gen_session_req_per_week = $('#lead_gen_session_req_per_week').val();
		var lead_gen_timeframe_capicity = $('#lead_gen_timeframe_capicity').val();
		var lead_gen_contact_conv_rate = $('#lead_gen_contact_conv_rate').val();
		var lead_gen_avg_consult_week = $('#lead_gen_avg_consult_week').val();
		var lead_gen_avg_singed_client_week = $('#lead_gen_avg_singed_client_week').val();
        $.ajax({
            type: 'post',
            url: public_url + '/financial-tool/save-lead-generation/'+recordId,
            data: {id:recordId, total_client_req: total_client_req,projected_conv_rate: projected_conv_rate,
                consultations_req:consultations_req,leads_req: leads_req,
                avg_lead_gen_req_per_week_twlv:avg_lead_gen_req_per_week_twlv,
				lead_gen_session_req_per_week : lead_gen_session_req_per_week,
				lead_gen_timeframe_capicity : lead_gen_timeframe_capicity,
				lead_gen_contact_conv_rate : lead_gen_contact_conv_rate,
				lead_gen_avg_consult_week : lead_gen_avg_consult_week,
				lead_gen_avg_singed_client_week : lead_gen_avg_singed_client_week},
            success: function (res) {
                $('#temp_lead_gen_save').removeClass('inProgress').text('Save').attr('id','lead_gen_save');
            }
        });
    }

	return {
		init : function() {
			initWizard();
		}
	};
}();