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
			saveBusinessStructure();
		}
		else if(stepNumb == 2){
			saveSetupExp();
		}
		else if(stepNumb == 3){
			saveOperationExp();
		}else if(stepNumb == 4){
			saveSaleProjection();
		}else if(stepNumb == 5){
			saveBusinessStructure();
			saveSetupExp();
			saveSaleProjection();
			saveOperationExp();
			location.reload()
		}

		formData['_token'] = jQuery(wizardForm).find('[name="_token"]').val();
		formData['stepNumb'] = stepNumb;
		
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
		else
		{
			ifSendAjax = false;
			if(fromStep && validateSteps(fromStep, fromStep)){
				submitStepData(obj, fromStep, '', true);
				ifSafeToSwitch = true;
			}
			else if(!fromStep && finalStep && validateSteps(5, 5))
			{
				submitStepData(obj, 5);	
				ifSafeToSwitch = true;
			}
			else if(!fromStep && !finalStep && validateSteps(context.fromStep, context.toStep))
			{
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

		if(stepnumber == 4)
		{
			$('#session_req_per_week , #clients_req_6_per_session,#consultations_req,#leads_req,#avg_lead_gen_req_per_week_fr_52,#avg_consult_week,#avg_singed_client_week').prop('readonly',false);
		}

		var isFormValid = wizardForm.valid(),
			$cont = $('#step-'+stepnumber);
                
		if(isFormValid == true){		
			if(stepnumber == 4)
			{
				$('#session_req_per_week , #clients_req_6_per_session,#consultations_req,#leads_req,#avg_lead_gen_req_per_week_fr_52,#avg_consult_week,#avg_singed_client_week').prop('readonly',true);
			}
			$('.anchor').children("li:nth-child(" + stepnumber + ")").children("a").removeClass('wait');
			animateBar(nextstep);
			return true;
		}else{
			if(stepnumber == 4)
			{
				$('#session_req_per_week , #clients_req_6_per_session,#consultations_req,#leads_req,#avg_lead_gen_req_per_week_fr_52,#avg_consult_week,#avg_singed_client_week').prop('readonly',true);
			}
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


	var saveBusinessStructure = function () {

                $('#expenses_save').addClass('inProgress').text('').attr('id','temp_expenses_save');
                var business_type = $('.business_type :selected').val();
                var is_gst_registered = $('.is_gst_registered :selected').val();
                var gst_no = $('.gst-no').val();
                var gst_percentage = $('.gst-percentage').val();

                $.ajax({
                    type: 'post',
                    url: public_url + 'new-financial-tool/save-business-structure',
                    data: {
                    	business_type:business_type,
                    	is_gst_registered: is_gst_registered, 
                    	gst_no : gst_no , 
                    	gst_percentage : gst_percentage ,
				    },
                    success: function (res) {
                        $('#temp_expenses_save').removeClass('inProgress').text('Save').attr('id','expenses_save');
                        var tax_category = $('.business_type :selected').val();
		                if(tax_category == 'partnership') // 
		                { 
		                    $('#no_of_partner').trigger('change');
		                }
		                $('#se_calculated').trigger('change');

		                if(business_type == "sole-trader")
		                {
		                    $('label.business_taxes_label').text('INCOME TAX');
		                } else {
		                    $('label.business_taxes_label').text('BUSINESS TAX');
		                }

                    }
                });
    }
	
	var saveSetupExp = function () {
    	
        $('#income_save').addClass('inProgress').text('').attr('id','temp_income_save');
     	  var setup_expenses = localStorage.getItem('setup_expenses'); //  For modal json data

                var se_calculated = $('#se_calculated').val(); // Incl
                var se_gst_excl = $('#se_gst_excl').val();
                var se_gst_paid = $('#se_gst_paid').val();
                var se_est_capital = $('#se_est_capital').val();
                var se_add_capital_req = $('#se_add_capital_req').val();
                var se_repayment_monthly_period_capital_req = $('#se_repayment_monthly_period_capital_req').val();
                var se_interest_rate_fr_loan = $('#se_interest_rate_fr_loan').val();
                var se_interest = $('#se_interest').val();
                var se_total = $('#se_total').val();
                var se_monthly_repayment = $('#se_monthly_repayment').val();
				
        $.ajax({
            type: 'post',
            url: public_url + '/new-financial-tool/save-setup-expenses/' + recordId,
            data: {id:recordId, 
            	        setup_expenses:setup_expenses,
                        se_calculated : se_calculated ,
						se_gst_excl : se_gst_excl ,
						se_gst_paid : se_gst_paid ,
						se_est_capital : se_est_capital ,
						se_add_capital_req : se_add_capital_req ,
						se_repayment_monthly_period_capital_req : se_repayment_monthly_period_capital_req ,
						se_interest_rate_fr_loan : se_interest_rate_fr_loan ,
						se_interest : se_interest ,
						se_total : se_total ,
						se_monthly_repayment : se_monthly_repayment ,
						
			},
            success: function (res) {
                $('#temp_income_save').removeClass('inProgress').text('Save').attr('id','income_save');

                // Logic To Display Sections
                var business_type = $('.business_type :selected').val();
                if(business_type == 'company')
                {
                    $('.partnership_div').css('display','none','important');
                    $('.living_div').css('display','block','important');
                    $('.business_div').addClass('f-r');
                    $('.living_div').addClass('f-l');
                }
                else if(business_type == 'sole-trader')
                {
                    $('.living_div').css('display','block','important');
                    $('.partnership_div').css('display','none','important');
                    $('.business_div').addClass('f-r');
                    $('.living_div').addClass('f-l');
                    
                }
                else if(business_type == 'partnership')
                {
                    $('.business_div').addClass('f-r');
                    $('.living_div').css('display','none','important');
                    $('.partnership_div').css('display','block','important');
                    $('.partnership_div').addClass('f-l');

                }
            }
        });
    }


    var saveOperationExp = function () {
    	
        $('#income_save').addClass('inProgress').text('').attr('id','temp_income_save');
     					// business_expenses ----------------------------------------------------
                var business_expenses = localStorage.getItem('business_expenses'); //  For modal json data
                var be_calculated = $('#be_calculated').val();
				var be_gst_excl = $('#be_gst_excl').val();
				var be_gst_paid = $('#be_gst_paid').val();
				var be_loan_repayment_amt = $('#be_loan_repayment_amt').val();
				var be_total_exp_per_mnth = $('#be_total_exp_per_mnth').val();
				var be_profit_req_after_tax = $('#be_profit_req_after_tax').val();
				var be_tax_payable = $('#be_tax_payable').val();
				var be_gst_paid_per_annum = $('#be_gst_paid_per_annum').val();
				var be_total_exp_per_annum = $('#be_total_exp_per_annum').val();
				var be_profit_after_tax = $('#be_profit_after_tax').val();

				// living_expenses ----------------------------------------------------
                var living_expenses = localStorage.getItem('living_expenses'); //  For modal json data
				var le_calculated   =	$('#le_calculated').val(); 
				var le_gst_excl     =	$('#le_gst_excl').val(); 
				var le_gst_paid     =	$('#le_gst_paid').val(); 

				// Partnership ----------------------------------------------------

                var partnership_expenses = localStorage.getItem('partnership_expenses'); //  For modal json data
                var equity_ratio = [];
				$("input[name='equity_ratio[]']").each(function() {
				    equity_ratio.push($(this).val());
				});

				var invested_amount = [];
				$("input[name='invested_amount[]']").each(function() {
				    invested_amount.push($(this).val());
				});

				var gst_excl = [];
				$("input[name='gst_excl[]']").each(function() {
				    gst_excl.push($(this).val());
				});

				var gst_paid = [];
				$("input[name='gst_paid[]']").each(function() {
				    gst_paid.push($(this).val());
				});
				
				var profit_percentage = equity_ratio;
				var invested_amount = invested_amount;
				var gst_excl = gst_excl;
				var gst_paid = gst_paid;
				var no_of_partner = $('#no_of_partner').val();

                var business_type = $('.business_type :selected').val();

        $.ajax({
            type: 'post',
            url: public_url + '/new-financial-tool/save-operation-expenses/' + recordId,
            data: {id:recordId,
                            business_expenses:business_expenses,
						be_calculated : be_calculated ,
						be_gst_excl : be_gst_excl ,
						be_gst_paid : be_gst_paid ,
						be_loan_repayment_amt : be_loan_repayment_amt ,
						be_total_exp_per_mnth : be_total_exp_per_mnth ,
						be_profit_req_after_tax : be_profit_req_after_tax ,
						be_tax_payable : be_tax_payable ,
						be_gst_paid_per_annum : be_gst_paid_per_annum ,
						be_total_exp_per_annum : be_total_exp_per_annum ,
						be_profit_after_tax : be_profit_after_tax ,

						// living_expenses ----------------------------------------------------
						living_expenses :	living_expenses ,
						le_calculated   :	le_calculated , 
						le_gst_excl     :	le_gst_excl , 
						le_gst_paid     :	le_gst_paid , 
						
						// partnership ----------------------------------------------------
						partnership_expenses : partnership_expenses ,
						profit_percentage : profit_percentage ,
						invested_amount : invested_amount ,
						gst_excl : gst_excl ,
						gst_paid : gst_paid,
						no_of_partner : no_of_partner,
						business_type : business_type,
 
			},
            success: function (res) {
                $('#temp_income_save').removeClass('inProgress').text('Save').attr('id','income_save');
            }
        });
    }
    
   	var saveSaleProjection = function () {
    	
        $('#income_save').addClass('inProgress').text('').attr('id','temp_income_save');
     	// Session Required
				var session_rate = $('#session_rate').val();
				var session_req = $('#session_req').val();
				var ann_working_weeks = $('#ann_working_weeks').val();
				var weekly_sess_req = $('#weekly_sess_req').val();
				var no_of_clients = $('#no_of_clients').val();
				var session_spots_req = $('#session_spots_req').val();
				var avg_sess_pweek_pclient = $('#avg_sess_pweek_pclient').val();
				var clients_req_to_break_even = $('#clients_req_to_break_even').val();
				var average_client_spend = $('#average_client_spend').val();
				var estimated_client_cap = $('#estimated_client_cap').val();
				
				// Client Rqeauired ----------------------------------------------------
				var client_active_current = $('#client_active_current').val();
				var session_req_per_week = $('#session_req_per_week').val();
				var clients_req_6_per_session = $('#clients_req_6_per_session').val();
				var timeframe_capicity = $('#timeframe_capicity').val();
				var projected_cons_conv_rate = $('#projected_cons_conv_rate').val();
				var consultations_req = $('#consultations_req').val();
				var contact_conv_rate = $('#contact_conv_rate').val();
				var leads_req = $('#leads_req').val();
				var avg_lead_gen_req_per_week_fr_52 = $('#avg_lead_gen_req_per_week_fr_52').val();
				var avg_consult_week = $('#avg_consult_week').val();
				var avg_singed_client_week = $('#avg_singed_client_week').val();
        $.ajax({
            type: 'post',
            url: public_url + '/new-financial-tool/save-sale-projection/' + recordId,
            data: {id:recordId, 
            		// Session Required ----------------------------------------------------
						session_rate : session_rate,
						session_req : session_req,
						ann_working_weeks : ann_working_weeks,
						weekly_sess_req : weekly_sess_req,
						no_of_clients : no_of_clients,
						session_spots_req : session_spots_req,
						avg_sess_pweek_pclient : avg_sess_pweek_pclient,
						clients_req_to_break_even : clients_req_to_break_even,
						average_client_spend : average_client_spend,
						estimated_client_cap : estimated_client_cap,

						// Client Required ----------------------------------------------------
						client_active_current : client_active_current ,
						session_req_per_week : session_req_per_week ,
						clients_req_6_per_session : clients_req_6_per_session ,
						timeframe_capicity : timeframe_capicity ,
						projected_cons_conv_rate : projected_cons_conv_rate ,
						consultations_req : consultations_req ,
						contact_conv_rate : contact_conv_rate ,
						leads_req : leads_req ,
						avg_lead_gen_req_per_week_fr_52 : avg_lead_gen_req_per_week_fr_52 ,
						avg_consult_week : avg_consult_week ,
						avg_singed_client_week : avg_singed_client_week ,
			},
            success: function (res) {
                $('#temp_income_save').removeClass('inProgress').text('Save').attr('id','income_save');
                $('#projected_avg_client_spend').val((average_client_spend / 12).toFixed(2));
                $('#projected_client_per_mnth').val((avg_singed_client_week * 52 / 12).toFixed(2));
                $('#sum_mnth1').trigger('blur');
            }
        });
    }

	return {
		init : function() {
			initWizard();
		}
	};
}();
