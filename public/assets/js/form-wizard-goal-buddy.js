var isJump ="true";
var i=j=k=b=c=d=e=a=f=g=h=m=p=n=1;
var isJumpMilestone = "true";
var isJumpTask = "true";
var ifSendAjax = false;
var optionValue, goalInfo, habitInfo, taskInfo;

var FormWizardGoalBuddy = function() {
	var wizardContent = $('#goalBuddyWizard');
	var wizardForm = $('#goal-buddy-form');
	//var ifSendAjax = false;
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
				/*username : {
					minlength : 2,
					required : true
				},
				email : {
					required : true,
					email : true
				},*/
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
		$(".goal-buddy-wizard-next-step").unbind("click").click(function(e) {
			ifSendAjax = true;
			c=2;
			e.preventDefault();
			wizardContent.smartWizard("goForward");
		});
		$(".goal-buddy-wizard-back-step").unbind("click").click(function(e) {
			if(context.fromStep == 1){
				b=2;
				c=1;
				$('#update-record').val('update-yes');
				$('.milestone-listing').hide();
			}
			if(context.fromStep == 2)
				$('.habit-listing').hide();

			if(context.fromStep == 3){
				b=2;
				c=2;
				h=2;
				$('.habit-listing').show();
				$('.task-listing').hide();
			}

			if(context.fromStep == 4)
				$('.task-listing').show();

			ifSendAjax = false;
			e.preventDefault();
			wizardContent.smartWizard("goBackward");
		});
		$(".goal-buddy-wizard-final-step").unbind("click").click(function(e) {
			e.preventDefault();
			
			/*var clientId=$('#goalClientId').val();
			window.location.hash = "#panel_assess_progress"; 
		    location.reload(true);*/
		});
	};

	$(".final-step-goalbuddy_edit").click(function(e) {
		e.preventDefault();
		$('#reqMsgSmart').addClass('hidden');
		var allVals = [];
		var formdata = {};
		$('.goalsmart:checked').each(function() {
			allVals.push($(this).val());
		});
		console.log(allVals);
		console.log(allVals.length);

		if(allVals.length == 5){
			formdata['review'] = allVals;
			formdata['form_no'] = 5;
			formdata['last_insert_id'] = $('#last-insert-id').val();
			savegoal(formdata);
			window.location.hash = "#panel_assess_progress"; 
	    	location.reload(true);;
			return false;
		}
		else{
			$('#reqMsgSmart').removeClass('hidden');
			return true;
		}
	});

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
	}

	var leaveAStepCallback = function(obj, context){
		console.log(obj)
		console.log(context)

			var formdata = {};
			//wizardForm.valid();
			formdata['ClientId'] = $('input[name="goalClientId"]').val();
			formdata['ClientName'] = $('input[name="goalClientName"]').val();

			if(context.fromStep == 1){
				formdata['name'] = $('#name_goal').val();
				formdata['describe_achieve'] = $("#describe_achieve").val();
				formdata['goal_year'] = $("input[name=goal_year]:checked").val();
	            formdata['change_life'] = prepareMultiSel($("select#change_life"));
				formdata['accomplish'] = $('#accomplish').val();
	            formdata['failDescription'] = $('#fail-description').val();
	            formdata['gb_relevant_goal'] = $('#gb_relevant_goal').val();
				formdata['gb_relevant_goal_event'] = $('#gb_relevant_goal_event').val();
				formdata['image'] = $('#goalBodyLogo').val();
				formdata['template'] = $('#goal-template').val();
				formdata['due_date'] =  dateStringToDbDate($('#datepicker_SYG').val());
				formdata['goal_seen'] = $("input[name=goal_seen]:checked").val();
				formdata['send_msg_type'] = $("input[name=send_msgss]:checked").val();
				formdata['form_no'] = context.fromStep;
				formdata['update_status'] = $('#update-record').val();

				if(formdata['update_status'] == 'update-yes')
					formdata['last_insert_id'] = $('#last-insert-id').val();

				savegoal(formdata);
			}
			else if(context.fromStep == 2 && m == 1){
			  	var allMilestonesNames = allMilestonesNamesId = allMilestonesDates = [];
	            $('.milestones-name').each(function() {
	                var milestonesNames= $(this).val() ;
	                var milestonesId = $(this).closest('.dd-item').data('milestones-id');
	                
	                if(milestonesNames != ''){
	                    if(milestonesId!= '')
	                        allMilestonesNamesId.push(milestonesId +':'+ milestonesNames);
	                    else
	                        allMilestonesNames.push(milestonesNames);
	                }
	            });

	            $('.milestones-date').each(function(){
	                if($(this).val() != '')
	                	allMilestonesDates.push($(this).val());
	            });
	            
	            if(allMilestonesNames.length > 0)
	                formdata['milestones-names'] = allMilestonesNames;
	            
	            if(allMilestonesNamesId.length > 0)
	            	formdata['milestones-names-id'] = allMilestonesNamesId;
	             
	            if(allMilestonesDates.length > 0)
	                formdata['milestones-dates'] = allMilestonesDates;
	            
	            var totalNameLength = parseInt(allMilestonesNames.length)+parseInt(allMilestonesNamesId.length);
	            var totalDateLength = allMilestonesDates.length;

			    formdata['gb_milestones_seen'] = $("input[name=gb_milestones_seen]:checked").val();
				formdata['gb_milestones_reminder'] = $("input[name=gb_milestones_reminder]:checked").val();
				formdata['form_no'] = context.fromStep;
				formdata['last_insert_id'] = $('#last-insert-id').val();
				formdata['goal_id_mile']=$('#goal_milestones_id').val();
				formdata['milestones_id'] = $('#milestones_id').val();

				if(formdata['milestones_id'] == '' || formdata['milestones_id'] == undefined){
					if(p == 1){
						if((allMilestonesNames.length) == totalDateLength){
							savegoal(formdata);
							n=1;
						} 
						else
							n=2;
					}
				} 
				else{
					savegoal(formdata);
				}
	            if(a == 1 ){
	                if(e == 1 && context.toStep == 3 && h == 2){
	                    $('.habit-listing').show();
	                    $('.habit-form').hide();

	                } 
	                else {
	                    $('.habit-listing').hide();
	                    $('.habit-form').show();
	                }
	            } 
	            else{
	                $('.habit-listing').show();
	                $('.habit-form').hide();
	                $('.cancel_habit_btn').addClass('hidden');
	                return false;
	            }
	            if($('#SYG_habits').val() != ''){
	                $('.habit-form').hide();
	                $('.habit-listing').show();
	                $('.cancel_habbit_btn').addClass('hidden');
	            }
			}
			else if(context.fromStep == 3 && c==2 && d !=2 && e==1){
				var habitId = $('#habit-id').val();
				formdata['habit_name'] = $('#SYG_habits').val();
				formdata['habit_recurrence'] = $("input[name=SYG_habit_recurrence]:checked").val();
				if(formdata['habit_recurrence'] == 'weekly'){
					var selectedWeeks = [];
					$('input[type="checkbox"]:checked.goalEventRepeatWeekdays').each(function() {
						selectedWeeks.push($(this).val());
		   			});
		   			formdata['habit_weeks'] = selectedWeeks;
	   			} 
	   			else if(formdata['habit_recurrence'] == 'monthly'){
					formdata['month'] = $(".month-date option:selected").text();
				}
				formdata['habit_notes'] = $('#SYG_notes').val();
				formdata['habit_milestone']=$('select[name="milestone_value"]').val();
				formdata['habit_seen'] = $("input[name=syg2_see_habit]:checked").val();
				formdata['habit_reminders'] = $("input[name=syg2_send_msg]:checked").val();
				formdata['form_no'] = context.fromStep;
				formdata['last_insert_id'] = $('#last-insert-id').val();
				f=2;
				if($('#SYG_habits').val() != ''){
					f=2;
					$('.habit-form').hide();
					$('.habit-listing').show();
					$('.cancel_habbit_btn').addClass('hidden');

				}
				if( habitId == '' || habitId == undefined){
					if(j == 1){
						if(formdata['habit_name'] != ''){
							savegoal(formdata);
						  	f=1;
						} 
						else
							f=2;
					}
				} 
				else {
					formdata['habit_id'] = habitId;
					savegoal(formdata);
					f=1;
				}
				if(isJump == 'false' && f == 1){
					j++;
					f=2;
					$('.habit-form').hide();
					$('.habit-listing').show();
					$('.cancel_habbit_btn').addClass('hidden');
					isJumpTask = false;
					return false;
				}
				if(a == 1 ){
					if(e == 1 && context.toStep == 4 && h == 2){
						$('.task-listing').show();
						$('.task-friends-section').hide();
						$('.task-form').hide();
						//isJumpTask = true;
					} 
					else {
						//console.log('taskform1');
						$('.task-listing').hide();
						$('.task-friends-section').hide();
						$('.task-form').show();
						//isJumpTask = true;
					}
				} 
				else {
					//console.log('taskform2');
					$('.task-listing').show();
					$('.task-friends-section').hide();
					$('.task-form').hide();
					$('.cancel_task_btn').addClass('hidden');
					//isJumpTask = false;
					return false;
				}
				if($('#SYG3_task').val() != ''){
					//console.log('listtask3');
					$('.task-form').hide();
					$('.task-listing').show();
				}
			} 
			else if(context.toStep == 4 && e==2){
				e=1;
				a++;
				isJump = "false";
				$('.task-listing').hide();
				$('.task-friends-section').hide();
				$('.task-form').show();
				if($('#SYG3_task').val() != ''){
					//console.log('listtask2');
					$('.task-form').hide();
					$('.task-listing').show();
				}

				formdata['habit_name'] = $('#SYG_habits').val();
				formdata['habit_recurrence'] = $("input[name=SYG_habit_recurrence]:checked").val();
				if(formdata['habit_recurrence'] == 'weekly'){
					var selectedWeeks = [];
					$('input[type="checkbox"]:checked.goalEventRepeatWeekdays').each(function() {
						selectedWeeks.push($(this).val());
		   			});
		   			formdata['habit_weeks'] = selectedWeeks;
	   			} 
	   			else if(formdata['habit_recurrence'] == 'monthly'){
					formdata['month'] = $(".month-date option:selected").text();
				}

				formdata['habit_notes'] = $('#SYG_notes').val();
				formdata['habit_milestone']=$('select[name="milestone_value"]').val();
				formdata['habit_seen'] = $("input[name=syg2_see_habit]:checked").val();
				formdata['habit_reminders'] = $("input[name=syg2_send_msg]:checked").val();
				formdata['form_no'] = context.fromStep;
				formdata['last_insert_id'] = $('#last-insert-id').val();
				var habitId = $('#habit-id').val();
				if($('#SYG_habits').val() != ''){
					$('.habit-form').hide();
					$('.habit-listing').show();
				}
				if( habitId == '' || habitId == undefined){
					if(j == 1){
						if(formdata['habit_name'] != ''){
							savegoal(formdata);
						  	f=1;
						} 
						else 
							f=2;
					}
				} 
				else {
					formdata['habit_id'] = habitId;
					savegoal(formdata);
				}
				if(isJump == 'false' && f==1){
					j++;
					$('.habit-form').hide();
					$('.habit-listing').show();
					$('.cancel_habbit_btn').addClass('hidden');
					f=2;
					isJumpTask = false;
					return false;
				}
			} 
			else if(context.fromStep == 4 && c==2 && d!=2){
				formdata['task_name'] = $('#SYG3_task').val();
				formdata['note'] = $('#note').val();
				formdata['task_priority'] = $("#SYG3_priority").val();
				formdata['task_habit_id'] = $('select[name="habit_value"]').val();
				formdata['task_seen'] = $("input[name=SYG3_see_task]:checked").val();
				formdata['task_reminders'] = $("input[name=SYG3_send_msg]:checked").val();

				formdata['task_recurrence'] = $("input[name=SYG_task_recurrence]:checked").val();
	            if(formdata['task_recurrence'] == 'weekly'){
	                var selectedWeeks = [];
	                $('input[type="checkbox"]:checked.taskEventRepeatWeekdays').each(function() {
	                    selectedWeeks.push($(this).val());
	                });
	                formdata['task_weeks'] = selectedWeeks;
	            }
	            else if(formdata['task_recurrence'] == 'monthly'){
	                formdata['month'] = $(".month-date-task option:selected").text();
	            }
				formdata['form_no'] = context.fromStep;
				formdata['last_insert_id'] = $('#last-insert-id').val();
				var taskId = $('#task-id').val();
				if( taskId == '' || taskId == undefined){
					if(k == 1){
						if(formdata['task_name'] != '' && formdata['task_priority'] != '' && formdata['task_date'] != ''){
							savegoal(formdata);
							g=1;
						} 
						else 
							g=2;
					}
				} 
				else {
					isJumpTask = "false";
					formdata['task_id'] = taskId;
					savegoal(formdata);
					b++;
				}

				if(b>2){
					isJumpTask = "true";
					b =1;
				}

				if(isJumpTask == 'false' && g==1){
					k++;
					$('.task-form').hide();
					$('.task-listing').show();
					$('.cancel_task_btn').addClass('hidden');
					e=1;
					isJumpTask = "true";
					return false;
				}
			} 
			else if(context.fromStep == 5 && d !=2){
				var allVals = [];
				$('input[type="checkbox"]:checked.goalsmart').each(function() {
					allVals.push($(this).val());
	   			});

	   			if(context.fromStep==5 && context.toStep==6){
					if(validateSteps(5, 6)){
						if(allVals.length > 0){
							$('#reqMsgSmart').addClass('hidden');
							formdata['review'] = allVals;
							formdata['form_no'] = context.fromStep;
							formdata['last_insert_id'] = $('#last-insert-id').val();
							savegoal(formdata);
							var clientId = formdata['ClientId'];
							window.location.hash = "#panel_assess_progress"; 
						    location.reload(true);
						}
						else{
							$('#reqMsgSmart').removeClass('.hidden');
							return false;
						}
					 } 
					 else {
	                    $('#reqMsgSmart').removeClass('.hidden');
						return false;
					}
				}
				else{
					$('#reqMsgSmart').removeClass('.hidden');
					return false;
				}
				
			}
			if(ifSendAjax){
				if(wizardForm.valid())
					var switchStep = true;
				else
					var switchStep = false;
			}
			else
				var switchStep = true;

			ifSendAjax = false;
			return switchStep;
	};

	var scrollToTop = function(id){
		$("body, html").animate({
			scrollTop:$(id).closest('.panel').offset().top
		}, 200);
	};

	var validateSteps = function(stepnumber, nextstep){
		var isFormValid = wizardForm.valid(),
			$cont = $('#step-'+stepnumber);

		if(stepnumber == 5){
            var specificField = $("#Specific").val();
            var measurableField = $("#Measurable").val();
            var attainableField = $("#Attainable").val();
            var relevantField = $("#Relevant").val();
            var timeBoundField = $("#Time-Bound").val();

            var is_specificField_checked = $("#Specific").attr("data-is-checked");
            var is_measurableField_checked = $("#Measurable").attr("data-is-checked");
            var is_attainableField_checked = $("#Attainable").attr("data-is-checked");
            var is_relevantField_checked = $("#Relevant").attr("data-is-checked");
            var is_timeBoundField_checked = $("#Time-Bound").attr("data-is-checked");

			if((specificField == undefined) || (measurableField == undefined) || (attainableField == undefined) || (relevantField == undefined) || (timeBoundField == undefined) ){
				$('#reqMsgSmart').removeClass('hidden');
				isFormValid = false;
		    }

            if((is_specificField_checked != 'yes') || (is_measurableField_checked != 'yes') || (is_attainableField_checked != 'yes') || (is_relevantField_checked != 'yes') || (is_timeBoundField_checked != 'yes') ){
                $('#reqMsgSmart').removeClass('hidden');
                isFormValid = false;
            }
			scrollToTop(wizardForm);
		}

		if(isFormValid){
			$('.anchor').children("li:nth-child(" + stepnumber + ")").children("a").removeClass('wait');
			animateBar(nextstep);
			return true;
		}
		return false;
	};

	var validateStepsEdit = function(stepnumber, nextstep){
		// var isFormValid = wizardForm.valid(),
		var isFormValid = true,
			$cont = $('#step-'+stepnumber);
		if(stepnumber == 5){
			var specificField = $("#Specific").val();
			var measurableField = $("#Measurable").val();
			var attainableField = $("#Attainable").val();
			var relevantField = $("#Relevant").val();
			var timeBoundField = $("#Time-Bound").val();

			var is_specificField_checked = $("#Specific").attr("data-is-checked");
			var is_measurableField_checked = $("#Measurable").attr("data-is-checked");
			var is_attainableField_checked = $("#Attainable").attr("data-is-checked");
			var is_relevantField_checked = $("#Relevant").attr("data-is-checked");
			var is_timeBoundField_checked = $("#Time-Bound").attr("data-is-checked");

			if((specificField == undefined) || (measurableField == undefined) || (attainableField == undefined) || (relevantField == undefined) || (timeBoundField == undefined) ){
				$('#reqMsgSmart').removeClass('hidden');
				isFormValid = false;
							   $('html, body').animate({
			        scrollTop: $("#reqMsgSmart").offset().top
			    }, 200);
		    }

			if((is_specificField_checked != 'yes') || (is_measurableField_checked != 'yes') || (is_attainableField_checked != 'yes') || (is_relevantField_checked != 'yes') || (is_timeBoundField_checked != 'yes') ){
                $('#reqMsgSmart').removeClass('hidden');
                isFormValid = false;
                $('html, body').animate({
                    scrollTop: $("#reqMsgSmart").offset().top
                }, 200);
			}
		}
		
		if(isFormValid){
			$('.anchor').children("li:nth-child(" + stepnumber + ")").children("a").removeClass('wait');
			animateBar(nextstep);
			return true;
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
	return {
		init : function() {
			initWizard();
		}
	};
}();
// form wizrd close.

/* Start: Document ready */
$(document).ready(function(){
	$('.stepNumber').click(function(){
        $('.task-listing').show();
        $('.cancel_task_btn').addClass('hidden');
    });

	/*$('.editable-pencil').click(function(){
		console.log($('.task-listing'));
		wizardContent.smartWizard("goBackward");
        $('.task-listing').show();
    });*/

    $('.submit-first-form').on('click',function(){
		return false;
   	});

 	$( document ).on( 'click', '.habit-edit', function() {
 		j=1;
		isJump = "false";
  	});

 	$( document ).on( 'click', '.add-habit', function() {
		j=1;
		isJump = "false";
		$('input[name="habit_id"]').val('');

		$('.habit-form input:text').each(function() {
		    $(this).val('');
		});

		$('.habit-form textarea').each(function() {
		    $(this).val('');
		});

		$('.habit-form input:radio').each(function() {
			$(this).prop('checked',false);
		});

		$('#SYG_habit_recurrence0').each(function() {
			$(this).prop('checked',true);
		});

		$('#syg2_see_habit0').each(function() {
			$(this).prop('checked',true);
		});

		$('#syg2_send_msg0').each(function() {
			$(this).prop('checked',true);
		});

		$('.habit-form input:checkbox').each(function() {
			$(this).prop('checked',false);
		});

		$('#milestone_div option').prop('selected', function() {
	        return this.defaultSelected;
	    });

	    $('.milestone_div_class').selectpicker('refresh');
		$('.show-weeks').hide();
		$('.month-count-div').hide();
		$('.habit-listing').hide();
		$('.cancel_habbit_btn').removeClass('hidden');
		$('.habit-form').show();
	});

	$( document ).on( 'click', '#cancel_habbit', function() {
	 	$('.habit-listing').show();
	    $('.habit-form').hide();
	    $('.cancel_habbit_btn').addClass('hidden');
	});

  	$( document ).on( 'click', '#cancel_task', function() {
	 	$('.task-listing').show();
	    $('.task-form').hide();
	    $('.cancel_task_btn').addClass('hidden');
 	});

 	$( document ).on( 'click', '.task-edit', function() {
	 	k=1;
	 	isJumpTask = "false";
 	});

 	$(document).on( 'click', '.add-task', function() {
	 	k=1;
	 	isJumpTask = "false";
	 	
	 	$('input[name="task_id"]').val('');

	 	$('.task-form input:text').each(function() {
		    $(this).val('');
		});

		$('.task-form input:radio').each(function() {
			$(this).prop('checked',false);
		});

		$('#SYG3_send_msg0').each(function() {
			$(this).prop('checked',true);
		});

		$('#SYG3_see_task2').each(function() {
			$(this).prop('checked',true);
		});

		$('.task-form select option:selected').each(function() {
			$(this).removeAttr('selected');
		});

		$('#milestone_div option').prop('selected', function() {
	        return this.defaultSelected;
	    });

	    $('.milestone_div_class').selectpicker('refresh');

	    $('#SYG3_priority option').prop('selected', function() {
	        return this.defaultSelected;
	    });

	    $('#SYG3_priority').selectpicker('refresh');
	    $('.cancel_task_btn').removeClass('hidden');
		$('.task-listing').hide();
	    $('.task-form').show();
	});

	$(document).on( 'click', '.habit-text', function() {
		e=2;
		d=1;
        c=2;
        var habitId =$(this).attr('data');
        $('#habit-id').val(habitId);
        $('.habit-listing').hide();
        $('.habit-form').show();
        $('#goalBuddyWizard').smartWizard('goToStep', 2);
        $('.habit-listing').hide();
        $('.habit-form').show();
        getHabit(habitId);
		return false;
    });
  
    $(document).on( 'click', '.task-text', function() {
   		var taskId =$(this).attr('data');
        $('#task-id').val(taskId);
        $('.task-listing').hide();
        $('.task-form').show();
        $('#goalBuddyWizard').smartWizard('goToStep', 3);
        getTask(taskId);
		return false;
    });

    $(document).on( 'click', '.listing-habit-name', function() {
    	var habitId =$(this).attr('data');
   		var goalName = $('#goal-name').val();
   		getHabitById(habitId,goalName);
		return false;
    });
});
/* End Document ready */

/* Start: Save step data */
function savegoal(formdata, callback){
	var status = 'error';
	if(ifSendAjax){
		$.ajax({
			url: public_url+'goal-buddy/savegoal',
			type: 'POST',
			data: {'formData':formdata},
			success: function(data){
				var data = JSON.parse(data);
				if(data.form == 'habit-list'){
					status = 'success';
					displayHabitList(data);
				}
				else if(data.form == 'task-list'){
					status = 'success';
					dispalyTaskList(data);
				}
				else if(data.form == 'milestones-list'){
					status = 'success';
					displayMilestone(data);
				}

				if(data.status == "success"){
					status = 'success';
					if(data.goalBuddy != ''){
						isJump = "false";
						var goalData = data.goalInfo;
						//console.log(goalData);
						if(goalData != undefined)
							showGoalInfo(goalData);

						$('.milestone-div-label').hide();
						$('.task-milestone-label').hide();
						$('#update-record').val('update-yes');
						$('#last-insert-id').val(data.goalBuddy);
					}
				}

				if(typeof callback != 'undefined')
					callback(status);
			}
		});
	}
 }
/* End: Save step data */

/* Strat: Diaplay habit in list table after save successful*/
var displayHabitList = function(data){
	var listValue = habitLabel = habitRecurrence = "";

	isJump = "true";
	isJumpTask = "false";

	$('#client-datatable tbody tr').remove();
	$.each(data.listData, function(key, value) {
		if(value.mile_stone_name != null)
			var milname=value.mile_stone_name;
		else
			var milname='';

		if(value.gb_habit_recurrence_type == 'weekly')
			habitRecurrence = 'Every '+value.gb_habit_recurrence_week;
		else if(value.gb_habit_recurrence_type == 'monthly')
			habitRecurrence = 'Day '+value.gb_habit_recurrence_month+' of every month';
		else 
			habitRecurrence = value.gb_habit_recurrence;

		listValue += '<tr><td>'+value.gb_habit_name+'</td><td>'+habitRecurrence+'</td><td class="hidden-xxs">'+milname+'</td><td class="hidden-xs">'+value.gb_habit_seen+'</td><td class="center"><a class="btn btn-xs btn-default tooltips habit-edit"  data-placement="top"data-original-title="Edit" data-habit-id = "'+value.id+'"><i class="fa fa-pencil" style="color:#ff4401;"></i></a>&nbsp;<a class="btn btn-xs btn-default tooltips delete-habit" data-placement="top"data-original-title="Delete" data-habit-id = "'+value.id+'"><i class="fa fa-times" style="color:#ff4401;"></i></a></td></tr>';
		habitLabel += '<a class="Step-your-goal3 habit-text" data ='+value.id+'><li>'+ value.gb_habit_name +'</li></a>';
		habitLabel += '<p>'+value.gb_habit_seen+'</p>';
	});
	//$('.habit-name').text('Your Habit has been saved.');
	$('.habit-label a').remove();
	$('.habit-label p').remove();
	$('.habit-label').append(habitLabel);
	$('#client-datatable tbody').append(listValue);
	listValue = "";
}
/* End: Diaplay habit in list table after save successful*/

/* Strat: Diaplay task in list table after save successful*/
var dispalyTaskList = function(data){
	isJumpTask = "true";
	$('#client-datatable-task tbody tr').remove();
	var taskListValue = taskLabel = taskDueDate  = "";

    $.each(data.listData, function(key, value) {
		taskListValue += '<tr><td>'+value.gb_task_name+'</td><td>'+value.gb_task_priority+'</td><td class="hidden-xs">'+value.gb_task_seen+'<br></td><td class="center"><a class="btn btn-xs btn-default tooltips task-edit" data-placement="top" data-original-title="Edit" data-task-id = "'+value.id+'"><i class="fa fa-pencil" style="color:#ff4401;"></i></a>&nbsp;<a class="btn btn-xs btn-default tooltips delete-task" data-placement="top" data-original-title="Delete" data-task-id = "'+value.id+'"><i class="fa fa-times" style="color:#ff4401;"></i></a></td>';
		
		taskLabel += '<a class="Step-your-goal2 task-text" data ='+value.id+'><li style="margin-left:18px;">'+ value.gb_task_name +'</li></a>';
		taskLabel += '<p>'+value.gb_task_seen+'</p>';
	});
	
	//$('.task-name').text('Your task '+value.gb_task_name+' has been saved.');
	$('.tasks-label a').remove();
	$('.tasks-label p').remove();
	$('.tasks-label').append(taskLabel);
	taskLabel = '';						
	$('#client-datatable-task tbody').append(taskListValue);
	listValue = '';
	i++;
}
/* End: Diaplay task in list table after save successful*/

/* Strat: Diaplay mileston after save successful */
var displayMilestone = function(data){
	milestonLabel = '';
	$('.milestone-div').show();
	var optionValue = '<select id="milestone_div" name="milestone_value" class="selectpicker form-control onchange-set-neutral milestone_div_class"><option value="">-- Select --</option>';
	$.each(data.mdata,function(key, value) {
		optionValue += '<option value="'+value.id+'">'+value.gb_milestones_name+'</option>';
		milestonLabel += '<p><a>'+value.gb_milestones_name+'</a></p>';
	});

	optionValue += '</select>';
	if('goalInfo' in data){
		var goalData = data.goalInfo;
		showGoalInfo(goalData);
	}

	//$('.milestone-dropdown').html($(optionValue));
	$('.milestone-label').append(milestonLabel);
	milestonLabel = '';

	//$('.task-milestone-dropdown').html($(optionValue));
	$('.milestone_div_class').selectpicker('refresh');
		optionValue = '';

	if('goalBuddy' in data && data.goalBuddy != ''){
		isJump = "false";
		$('#update-record').val('update-yes');
		$('#last-insert-id').val(data.goalBuddy);
	}
}
/* Strat: Diaplay mileston after save successful */

function showGoalInfo(goalData) {
 	$('.goal-name').text(goalData.gb_goal_name);
	if(goalData.gb_achieve_description == '')
		$('.achieve-description-label').hide(); 
	else 
		$('.achieve-description').text(goalData.gb_achieve_description);

	if(goalData.gb_change_life_reason == '')
		$('.change-life-label').hide(); 
	else 
		$('.change-life').text(goalData.gb_change_life_reason);
	
	if(goalData.gb_fail_description == '')
		$('.fail-description-label').hide();
	else 
		$('.fail-description').text(goalData.gb_fail_description);
	
	if(goalData.gb_goal_seen)
		$('.goal-seen').text('Shared:'+goalData.gb_goal_seen);
	
	if(goalData.gb_due_date != '1970-01-01')
		$('.goal-due-date').text('Due date:'+goalData.gb_due_date);
}

function getHabit(hid){
	$.ajax({
		url: 'get-habit',
		type: 'POST',
		data: {'habit_id':hid},
		success: function(data){
			var data = JSON.parse(data);
		if(data.status == 'true'){
			console.log(data.goalBuddy[0].id);
			$('#habit-id').val(data.goalBuddy[0].id);
			//$('#habit-id').val(data.goalBuddy[0].id);
			$('#SYG_habits').val(data.goalBuddy[0].gb_habit_name);
			$("input[name=SYG_habit_recurrence]:checked").val(data.goalBuddy[0].gb_habit_recurrence);
			$('#SYG_notes').val(data.goalBuddy[0].gb_habit_name);
			$(".milestone_div_class option:selected").val(data.goalBuddy[0].gb_milestones_name);
			$("input[name=syg2_see_habit]:checked").val(data.goalBuddy[0].gb_habit_name);
			$("input[name=syg2_send_msg]:checked").val(data.goalBuddy[0].gb_habit_name);
			$('#last-insert-id').val(data.goalBuddy[0].goal_id);
			$('#SYG_notes').val(data.goalBuddy[0].gb_habit_notes);
			}	
		}
		});
}

function getHabitById(hid,gName,gid){
	
			$.ajax({
		url: public_url+'goal-buddy/get-listing-habit',
		type: 'POST',
		data: {'habit_id':hid,'goal_name':gName,'goal_id':gid},
		success: function(data){
			
		var data = JSON.parse(data);
		if(data.status == 'true'){
			
			var daysHtml = '';
			$('.unchecked-days').html('');
			//$(".edit-habit").attr("edithabit",data.goalBuddy.id);
            $(".edit-habit").data('edithabit',data.goalBuddy.id);
			$(".delete-habit-list").data("deletehabitid",data.goalBuddy.id);
			$('.habit-name').text(data.goalBuddy.gb_habit_name);
			$('.frequency').text(data.goalBuddy.gb_habit_recurrence_type);
			$('.shared').text(data.goalBuddy.gb_habit_seen);
			if(data.goalBuddy.gb_milestones_name!='')
			$('.goal-with-milestone').text(gName+'->'+data.goalBuddy.gb_milestones_name);
			//console.log(data.goalBuddy.gb_habit_recurrence_type);
            


            if(data.uncompletedHabit.length >0){
            	$.each(data.uncompletedHabit, function (index, value) {
                  var dueaHabitDate = moment(value.due_date).format("ddd, D MMM YYYY");
                  daysHtml +=  '<div class="checkbox clip-check check-primary"><input type="checkbox" name="goal_hide" id="habit_'+value.id+'" value="1" class="habit-day-div" data ="'+value.id+'" data-type ="'+data.goalBuddy.gb_habit_recurrence_type+'"><label for="habit_'+value.id+'"><strong>'+dueaHabitDate+'</strong></label></div>';


            		});

            }

			
			$('.unchecked-days').html(daysHtml);
			
			}
		 }
		
		});
}

function getTask(tid){
$.ajax({
	url: 'get-task',
	type: 'POST',
	data: {'task_id':tid},
	success: function(data){
		var data = JSON.parse(data);
		if(data.status == 'true'){
			$('#SYG3_task').val(data.goalBuddy[0].gb_task_name);
			$("#SYG3_priority option:selected").val(data.goalBuddy[0].gb_task_priority);
			$('#datepicker_SYG3').val(data.goalBuddy[0].gb_task_due_date);
			$('#SYG3-time').val(data.goalBuddy[0].gb_task_time);
			var taskDropDown = $('.task-milestone-dropdown').find('.milestone_div_class option:selected');
			taskDropDown.val(data.goalBuddy[0].gb_milestones_name);
			$("input[name=SYG3_see_task]:checked").val(data.goalBuddy[0].gb_task_seen);
			$("input[name=SYG3_send_msg]:checked").val(data.goalBuddy[0].gb_task_reminder);
		}	
	}
});
}

var prepareMultiSel = function ($elem) {
	console.log($elem)
	var val = $elem.val();
	if(val != null)
		return val;
	return '';
}

var prepareCheckBoxSelect = function($elem){
	var value = [];
	if($elem.length){
		$.each($elem, function(i){
			value[i] = $elem.val();
		})
	}

	if(value.length)
		return value;
	else
		return '';
}
