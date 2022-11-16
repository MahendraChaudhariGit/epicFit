var isJump ="true";
var i=j=k=b=c=d=e=a=f=g=h=m=p=n=1;
var isJumpMilestone ="true";
var isJumpTask ="true";
var optionValue;
var goalInfo,habitInfo,taskInfo;
$(document).ready(function(){
	$('.stepNumber').click(function(){
		$('.task-listing').show();
		$('.cancel_task_btn').addClass('hidden');
	});
	
	$('.editable-pencil').click(function(){
		$('.task-listing').show();
	});
	
	$('.submit-first-form').on('click',function(){
		return false;
	});
	
	$('.edit_goal_element').on('click',function(){
		var url1 = window.location.href;
		var result1= url1.split('/');
		var Param1 = result1[result1.length-3];
		var Param12 = result1[result1.length-2];
		
		if((Param1 == 'goal-buddy') && (Param12 == 'edit')){
			var id = $(this).attr('id');
			if(id == "Step-your-goal1")
			$("#set_your_goal").trigger("click");
			if(id == "Step-your-goal4")
			$("#Build_Milestone").trigger("click");
			if(id == "Step-your-goal3")
			$("#Build_Habits").trigger("click");
			if(id == "Step-your-goal2")
			$("#Manage_Tasks").trigger("click");
		}
	});
	
	$('#Manage_Tasks').on('click',function(){
		$(".task-form").hide();
		$('.task-listing').show();
	});
	
	$('#Build_Habits').on('click',function(){
		$(".habit-form").hide();
		$(".habit-listing").show();
	});
	
	$('#smart_review_a').on('click',function(){
		$('#reqMsgSmart').addClass('hidden');
		var imgName = $('input[name="prePhotoName"]').val();

		if(imgName && (imgName != 'undefined')){
			$("#smartReviewImg").removeClass("hidden");
			// $('#smartReviewImg').attr('src', public_url+'/uploads/thumb_'+imgName);
			$('#smartReviewImg').attr('src', public_url+'uploads/thumb_'+imgName);
		}
		else{
			$("#smartReviewImg").addClass("hidden");
		}
	});
	
	$('.editHbt').on('click',function(){
		var url1 = window.location.href;
		var result1= url1.split('/');
		var Param1 = result1[result1.length-3];
		var Param12 = result1[result1.length-2];
		
		if((Param1 == 'goals') && (Param12 == 'edit')){
			var id = $(this).attr('data');
			if(id){
				$("#Build_Habits").trigger("click");
				$("#edit_hab_"+id).trigger("click");
			}
		}
	});
	
	$('.editTsk').on('click',function(){
		var url1 = window.location.href;
		var result1= url1.split('/');
		var Param1 = result1[result1.length-3];
		var Param12 = result1[result1.length-2];
		if((Param1 == 'goals') && (Param12 == 'edit')){
			var id = $(this).attr('data');
			if(id){
				$("#Manage_Tasks").trigger("click");
				$("#edit_task_"+id).trigger("click");
			}
		}
	});
	
	/*});*/
	
	var url1 = window.location.href;
	var result1= url1.split('/');
	var Param1 = result1[result1.length-3];
	var Param12 = result1[result1.length-2];
	if((Param1 == 'goals') && (Param12 == 'edit')){
		var review_data = JSON.parse($('#review_data').val());
		if(review_data.milestones){
			var mile_id = [];
			var milestoneLabel="";
			$.each(review_data.milestones, function(k,obj) {
				mile_id.push(obj.milestones_id);
				milestoneLabel += '<a class="Step-your-goal4 milestone-text" data ='+obj.milestones_id+'><li>'+ obj.milestones_name +'</li></a>';
				milestoneLabel += '<p style="margin-left:18px;">'+obj.gb_milestones_seen+'</p>';
			});
			
			$('#milestones_id').val(mile_id);
			$('.milestone-label a').remove();
			$('.milestone-label p').remove();
			$('.milestone-label').append(milestoneLabel);
		}
		if(review_data.taskhabit){
			var listValue = "";
			var habitLabel = "";
			var habitRecurrence = "";
			$('#client-datatable tbody tr').remove();
			$('.task-habit-div').show();
			var taskoptionValue = '<select id="habit_div" name="habit_value" class="selectpicker form-control onchange-set-neutral taskhabit_div_class" required=""><option value="">-- Select --</option>';
			
			$.each(review_data.taskhabit, function(key, value) {
				if(value.gb_milestones_name!=null)
				var milname=value.gb_milestones_name;
				else
				var milname='';
				habitRecurrence = value.gb_habit_recurrence_type;
				
				listValue += '<tr><td >'+value.gb_habit_name+'</td><td>'+habitRecurrence+'</td><td >'+milname+'</td><td>'+value.gb_habit_seen+'</td><td class="center"><a class="btn btn-xs btn-default tooltips habit-edit"  data-placement="top"data-original-title="Edit" data-habit-id = "'+value.id+'" id="edit_hab_'+value.id+'"><i class="fa fa-pencil" style="color:#ff4401;"></i></a><a class="btn btn-xs btn-default tooltips delete-habit" data-placement="top"data-original-title="Delete" data-entity="habit" data-habit-id = "'+value.id+'"><i class="fa fa-times" style="color:#ff4401;"></i></a></td></tr>';
				habitLabel += '<a class="Step-your-goal3 habit-text editHbt" data ='+value.id+' ><li>'+ value.gb_habit_name +'</li></a>';
				habitLabel += '<p style="margin-left:18px;">'+value.gb_habit_seen+'</p>';
				
				taskoptionValue += '<option value="'+value.id+'">'+value.gb_habit_name+'</option>';
			});
			$('.habit-label a').remove();
			$('.habit-label p').remove();
			$('.habit-label').append(habitLabel);
			$('#client-datatable tbody').append(listValue);
			listValue = "";
			
			taskoptionValue += '</select>';
			$('.task-habit-dropdown').html($(taskoptionValue));
		}
		
		if(review_data.taskdata){
			isJumpTask = "true";
			$('#client-datatable-task tbody tr').remove();
			var taskListValue = "";
			var taskLabel = "";
			var taskDueDate = "";
			var milestonesValue = "";
			$.each(review_data.taskdata, function(key, value) {
				// console.log(value.gb_habit_name);
				if(value.gb_habit_name!=null)
				var habitname=value.gb_habit_name;
				else
				var habitname='';
				
				$('.task-name').text('Your task '+value.gb_task_name+' has been saved.');
				taskListValue += '<tr><td>'+value.gb_task_name+'</td><td>'+value.gb_task_priority+'</td><td>'+habitname+'</td><td>'+value.gb_task_seen+'<br></td><td class="center"><a class="btn btn-xs btn-default tooltips task-edit" data-placement="top" data-original-title="Edit" data-task-id = "'+value.id+'" id="edit_task_'+value.id+'"><i class="fa fa-pencil" style="color:#ff4401;"></i></a><a class="btn btn-xs btn-default tooltips delete-task" data-placement="top" data-original-title="Delete" data-entity="task" data-task-id = "'+value.id+'"><i class="fa fa-times" style="color:#ff4401;"></i></a></td>';
				taskLabel += '<a class="Step-your-goal2 task-text editTsk" data ='+value.id+'><li>'+ value.gb_task_name +'</li></a>';
				taskLabel += '<p style="margin-left:18px;">'+value.gb_task_seen+'</p>';
			});
			
			$('.tasks-label a').remove();
			$('.tasks-label p').remove();
			$('.tasks-label').append(taskLabel);
			taskLabel = '';
			$('#client-datatable-task tbody').append(taskListValue);
			listValue = '';
			i++;
		}
		showGoalInfo(review_data);
	}
	
	$( document ).on( 'click', '.habit-edit', function() {
		j=1;
		isJump = "false";
		var processbarDiv = $(this).closest('tr');
		$('.habit-form').show();
		$('.habit-listing').hide();
		var habitId = $(this).attr('data-habit-id');
		getHabit(habitId, processbarDiv);
	});
	
	$( document ).on( 'click', '.add-habit', function() {
		j=1;
		isJump = "false";
		$('input[name="goalHabitId"]').val('');
		
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
		var taskId = $(this).attr('data-task-id');

		$('.task-form').show();
		$('.task-listing').hide();
		var processbarDiv = $(this).closest('tr');
		getTask(taskId, processbarDiv);
	});
	
	$(document).on( 'click', '.add-task', function() {
		k=1;
		isJumpTask = "false";

		$('input[name="task_id"]').val('');
		$('.task-form #note').val('');
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
		$('.task-form input:checkbox').each(function() {
			$(this).prop('checked',false);
		});
		$('#habit_div option').prop('selected', function() {
			return this.defaultSelected;
		});
		$('.taskhabit_div_class').selectpicker('refresh');

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
		$('#wizard').smartWizard('goToStep', 3);
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
		$('#wizard').smartWizard('goToStep', 4);
		getTask(taskId);
		return false;
	});
	
	$(document).on( 'click', '.listing-habit-name', function() {
		var habitId =$(this).attr('data');
		// var goalName = $('#goal-name').val();
		var eventType = $(this).data('event-type');
		var goalId = $(this).data('parent-id');

		getHabitById(habitId, eventType, goalId);
		return false;
	});
});

/* Form wizard */
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
		
		/*var temp = wizardForm.find('input[name="step_status"]');
		if(temp.length){
			var step_status = temp.val().split(',');
			for(i=0; i<step_status.length; i++){
				wizardContent.smartWizard('enableStep', i+1)
				if($.trim(step_status[i]) != 'completed')
				break;
				stepCompleted++;
			}
		}*/
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
				Specific : {
					
					required : true
					
				},
				card_expiry_yyyy : "cardExpiry",
				payment : {
					required : true,
					minlength : 1
				},
				
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
			/*var formMode = wizardForm.data('form-mode');
			if(formMode == 'view')
			ifSendAjax = false;
			else{
				SYGFormValid();
				//alert('hi');
				ifSendAjax = true;
			}*/
			
			c=2;
			e.preventDefault();
			ifSendAjax = true;
			/*if(checkFormValidation(context.fromStep))*/
			wizardContent.smartWizard("goForward");
		});
		$(".back-step").unbind("click").click(function(e) {
			if(context.fromStep == 1){
				b=2;
				c=1;
				$('#update-record').val('update-yes');
				$('.milestone-listing').hide();
			}
			if(context.fromStep == 2){
				$('.habit-listing').hide();
			}
			if(context.fromStep == 3){
				b=2;
				c=2;
				h=2;
				$('.habit-listing').show();
				$('.task-listing').hide();
			}
			if(context.fromStep == 4){
				$('.task-listing').show();
			}
			e.preventDefault();
			ifSendAjax = false;
			wizardContent.smartWizard("goBackward");
			//return false;
		});
		$(".final-step-goalbuddy").unbind("click").click(function(e) {
			e.preventDefault();
			var allVals = [];
			var formdata = {};
			$('input[type="checkbox"]:checked.goalsmart').each(function() {
				allVals.push($(this).val());
			});
			
			if(validateSteps(5, 6)){
				if(allVals.length > 0){
					formdata['review'] = allVals;
				}
				// formdata['form_no'] = context.fromStep;
				formdata['form_no'] = 5;
				formdata['last_insert_id'] = $('#last-insert-id').val();
				savegoal(formdata);
				window.location.href = public_url+'goals';
			}
			else
			return false;
		});
	};
	
	
	$(".final-step-goalbuddy_edit").click(function(e) {
		// e.preventDefault();
		var allVals = [];
		var formdata = {};
		$('input[type="checkbox"]:checked.goalsmart').each(function() {
			allVals.push($(this).val());
		});
		
		if(validateStepsEdit(5, 6)){
			if(allVals.length > 0){
				formdata['review'] = allVals;
			}
			// formdata['form_no'] = context.fromStep;
			formdata['form_no'] = 5;
			formdata['goal_notes'] = $('#goal_notes').val();
			formdata['last_insert_id'] = $('#last-insert-id').val();
			savegoal(formdata);
			window.location.href = public_url+'goals';
			return false;
		}
		else
		return false;
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
		
		var formdata = {};
		//var allMilestonesNames = [];
		//var allMilestonesDates = [];
		formdata['goal_notes'] = $('#goal_notes').val();
		var viewStep = $('#viewport-'+context.fromStep);
		if(context.fromStep == 1){

			var goalDueDate = $('#datepicker_SYG').val();
			formdata['name'] = $('#name_goal').val();

			formdata['describe_achieve'] = $("#describe_achieve").val();
			formdata['goal_year'] = $("input[name=goal_year]").val();
			formdata['change_life'] = prepareCheckBoxSelect($('#form').find('input[name="change_life"]').val());
			$.inArray('Other',formdata['change_life'])
			{	 
				formdata['gb_change_life_reason_other'] = $('#form').find('textarea[name="gb_change_life_reason_other"]').val();
			}
			// formdata['change_life'] = prepareMultiSel($('#form').find('select[name="change_life"]'));
			formdata['accomplish'] = $('#accomplish').val();
			formdata['failDescription'] = $('#fail-description').val();
			formdata['gb_relevant_goal'] = $('#gb_relevant_goal').val();
			formdata['gb_relevant_goal_event'] = $('#gb_relevant_goal_event').val();
			formdata['image'] = $('input[name="prePhotoName"]').val();
			formdata['template'] = $('#goal-template').val();
			if(goalDueDate!=''){
				formdata['due_date'] =  moment(goalDueDate, 'ddd, D MMM YYYY').format("YYYY-MM-DD");}
				formdata['goal_seen'] = $("input[name=goal_seen]").val();
				formdata['send_msg_type'] = $("input[name=send_msgss]").val();
				formdata['form_no'] = context.fromStep;
				formdata['update_status'] = $('#update-record').val();
				
				if(formdata['update_status'] == 'update-yes') {
					formdata['last_insert_id'] = $('#last-insert-id').val();
					savegoal(formdata);
				}
				
				if(formdata['name'] != '' && formdata['change_life'].length > 0 && formdata['update_status'] != 'update-yes' && formdata['due_date'] != undefined ){
					savegoal(formdata);
				}
			} 
			else if(context.fromStep == 2 && m==1){
				var allMilestonesNames = [];
				var allMilestonesNamesId=[];
				var allMilestonesDates = [];
				wizardForm.valid() ;
				// var milestonesDueDate=$('input[name="gb_milestones_date"]').val();
				$('.milestones-name').each(function() {
					var milestonesNames=$(this).val() ;
					var milestonesId = $(this).data('milestones-id');
					if(milestonesNames != ''){
						if(milestonesId!= '')
						allMilestonesNamesId.push(milestonesId +':'+ milestonesNames);
						else
						allMilestonesNames.push(milestonesNames);
					}
				});
				
				$('.datepicker_SYG4').each(function() {
					if($(this).val() != ''){
						allMilestonesDates.push($(this).val());}
					});
					//console.log(allMilestonesNames);
					if(allMilestonesNames.length > 0){
						formdata['milestones-names'] = allMilestonesNames;
					}
					if(allMilestonesNamesId.length > 0){
						formdata['milestones-names-id'] = allMilestonesNamesId;
					}
					if(allMilestonesDates.length > 0){
						formdata['milestones-dates'] = allMilestonesDates;
					}
					var totalNameLength=parseInt(allMilestonesNames.length)+parseInt(allMilestonesNamesId.length);
					var totalDateLength=allMilestonesDates.length;
					
					formdata['gb_milestones_seen'] = $("input[name=gb_milestones_seen]").val();
					formdata['gb_milestones_reminder'] = $("input[name=gb_milestones_reminder]").val();
					formdata['form_no'] = context.fromStep;
					formdata['last_insert_id'] = $('#last-insert-id').val();
					formdata['goal_id_mile']=$('#goal_milestones_id').val();
					formdata['milestones_id'] = $('#milestones_id').val();
					
					if( formdata['milestones_id'] == '' || formdata['milestones_id'] == undefined){
						if(p == 1){
							if(totalNameLength == totalDateLength){
								savegoal(formdata);
								n=1;
							} 
							else {
								n=2;
							}
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
							$('.habit-listing').show();
							$('.habit-form').hide();
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
					// formdata['habit_recurrence'] = $("input[name=SYG_habit_recurrence]").val();
					$('.habitRecurrenceWrapper').each(function(){
						if($(this).hasClass('active'))
						{
							formdata['habit_recurrence'] = $(this).data('recurrence-type');
						}
					})
				
					if(formdata['habit_recurrence'] == 'weekly'){
						var selectedWeeks = [];
						viewStep.find('.goalEventRepeatWeekdays').each(function() {
							if($(this).attr('checked'))
							{
								selectedWeeks.push($(this).val());
							}
						});
						formdata['habit_weeks'] = selectedWeeks;
					} 
					else if(formdata['habit_recurrence'] == 'monthly'){
						formdata['month'] = $(".month-date option:selected").text();
					}
					formdata['habit_notes'] = $('#SYG_notes').val();
					formdata['habit_milestone']=$('select[name="milestone_value"]').val();
					// formdata['habit_seen'] = $("input[name=syg2_see_habit]").val();
					// formdata['habit_reminders'] = $("input[name=syg2_send_msg]").val();
					formdata['form_no'] = context.fromStep;
					formdata['last_insert_id'] = $('#last-insert-id').val();
					viewStep.find('.who-can-view').each(function(){
						if($(this).hasClass('active'))
						{
							formdata['habit_seen'] = $(this).data('value');
						}
					})
					viewStep.find('.send-reminders').each(function(){
						if($(this).hasClass('active'))
						{
							formdata['habit_reminders'] = $(this).data('value');
						}
					})
					
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
							else {
								f=2;
							}
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
						} 
						else {
							$('.task-listing').show();
							$('.task-friends-section').hide();
							$('.task-form').hide();
						}
					} 
					else {
						$('.task-listing').show();
						$('.task-friends-section').hide();
						$('.task-form').hide();
						$('.cancel_task_btn').addClass('hidden');
						return false;
					}
					if($('#SYG3_task').val() != ''){
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
						$('.task-form').hide();
						$('.task-listing').show();
					}
					formdata['habit_name'] = $('#SYG_habits').val();
					formdata['habit_recurrence'] = $("input[name=SYG_habit_recurrence]").val();
					if(formdata['habit_recurrence'] == 'weekly'){
						var selectedWeeks = [];
						viewStep.find('.goalEventRepeatWeekdays').each(function() {
							if($(this).attr('checked'))
							{
								selectedWeeks.push($(this).val());
							}
						});
						formdata['habit_weeks'] = selectedWeeks;
					} 
					else if(formdata['habit_recurrence'] == 'monthly'){
						formdata['month'] = $(".month-date option:selected").text();
					}
					formdata['habit_notes'] = $('#SYG_notes').val();
					formdata['habit_milestone']=$('select[name="milestone_value"]').val();
					formdata['habit_seen'] = $("input[name=syg2_see_habit]").val();
					formdata['habit_reminders'] = $("input[name=syg2_send_msg]").val();
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
							} else {
								f=2;
							}
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
					formdata['task_seen'] = $("input[name=SYG3_see_task]").val();
					formdata['task_reminders'] = $("input[name=SYG3_send_msg]").val();
					formdata['task_recurrence'] = $("input[name=SYG_task_recurrence]").val();
					
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
							else {
								g=2;
							}
						}
					} 
					else {
						isJumpTask = "false";
						formdata['task_id'] = taskId;
						savegoal(formdata);
						b++;
					}
					if(b>2){
						if( $(".task-listing").css('display') == 'block') {
							isJumpTask = "true";
							b =1;
						}
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
								formdata['review'] = allVals;
							}
							formdata['form_no'] = context.fromStep;
							formdata['last_insert_id'] = $('#last-insert-id').val();
							savegoal(formdata);
							//window.location.href = public_url+'goals/goal-listing';
						} 
						else {
							return false;
						}
					}
					else{
						$('#reqMsgSmart').addClass('hidden');
						return true;
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
					
					//alert(specificField);
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
		
		/* Save Goal form */
		function savegoal(formdata){
			$.ajax({
				url: 	public_url+'goal-buddy/savegoal',
				type: 	'POST',
				data: 	{'formData':formdata},
				success: function(data){
					var data = JSON.parse(data);
					var url = window.location.href;
					var result= url.split('/');
					var Param = result[result.length-3];
					var Param1 = result[result.length-2];

					if(data.form == 'milestones-list'){
						var milestoneLabel="";
						var milestones = $('.mile_section .dd-item');

						isJump = "false";
						
						$.each(milestones, function(k,obj) {
							milestoneLabel += '<a class="Step-your-goal4 milestone-text" data ='+$(obj).find('.save-milestone-info').data('milestones-id')+'><li>'+ $(obj).find('.milestones-name').val() +'</li></a>';
							milestoneLabel += '<p style="margin-left:18px;">'+$('.milestone-form input[name=gb_milestones_seen]').val()+'</p>';
						});

						
						$('#milestones_id').val(data.milestonesId);
						$('.milestone-label a').remove();
						$('.milestone-label p').remove();
						$('.milestone-label').append(milestoneLabel);
						
						if((Param == 'goals') && (Param1 == 'edit')){
							// alert('a');
							// $("#smart_review_a").trigger("click");
						}
					}
					
					if(data.form == 'habit-list' || (data.habit_list != null && data.habit_list != [])){
						isJump = "true";
						isJumpTask = "false";
						var listValue = "";
						var habitLabel = "";
						var habitRecurrence = "";
						$('#client-datatable tbody tr').remove();
						$('.task-habit-div').show();
						var taskoptionValue = '<select id="habit_div" name="habit_value" class="selectpicker form-control onchange-set-neutral taskhabit_div_class" required=""><option value="">-- Select --</option>';
						
						$.each(data.habit_list, function(key, value) {
							if(value.mile_stone_name != null)
							var milname = value.mile_stone_name;
							else
							var milname='';
							if(milname){
								$('.habit-name').html('<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Your Habit '+value.gb_habit_name+' has been saved.</div>');
							}
							if(value.gb_habit_recurrence_type == 'weekly'){
								habitRecurrence = 'Every '+value.gb_habit_recurrence_week;
							} 
							else if(value.gb_habit_recurrence_type == 'monthly'){
								habitRecurrence = 'Day '+value.gb_habit_recurrence_month+' of every month';
							} 
							else{
								habitRecurrence = value.gb_habit_recurrence_type;
							}
							
							listValue += '<tr><td >'+value.gb_habit_name+'</td><td>'+habitRecurrence+'</td><td >'+milname+'</td><td>'+value.gb_habit_seen+'</td><td class="center"><a class="btn btn-xs btn-default tooltips habit-edit"  data-placement="top"data-original-title="Edit" data-habit-id = "'+value.id+'"><i class="fa fa-pencil" style="color:#ff4401;"></i></a><a class="btn btn-xs btn-default tooltips delete-habit" data-placement="top"data-original-title="Delete" data-entity="habit" data-habit-id = "'+value.id+'"><i class="fa fa-times" style="color:#ff4401;"></i></a></td></tr>';
							habitLabel += '<a class="Step-your-goal3 habit-text" data ='+value.id+'><li>'+ value.gb_habit_name +'</li></a>';
							habitLabel += '<p style="margin-left:18px;">'+value.gb_habit_seen+'</p>';
							
							taskoptionValue += '<option value="'+value.id+'">'+value.gb_habit_name+'</option>';
						});
						
						$('.habit-label a').remove();
						$('.habit-label p').remove();
						$('.habit-label').append(habitLabel);
						$('#client-datatable tbody').append(listValue);
						listValue = "";
						
						taskoptionValue += '</select>';
						$('.task-habit-dropdown').html($(taskoptionValue));
						initSelectpicker($('.task-habit-dropdown select'));
						$('.taskhabit_div_class').selectpicker('refresh');
						
						if((Param == 'goals') && (Param1 == 'edit')){
							// alert('a');
							// $("#smart_review_a").trigger("click");
						}
						
						$('.habit-edit').click(function() {
							$('#m-selected-step').val(3).trigger('change');
						});
					} 
					
					if(data.form == 'task-list' || (data.task_list != null && data.tasl_list != [])){
						isJumpTask = "true";
						$('#client-datatable-task tbody tr').remove();
						var taskListValue = "";
						var taskLabel = "";
						var taskDueDate = "";
						var milestonesValue = "";
						$.each(data.task_list, function(key, value) {
							/*if(value.gb_task_due_date != '')
							taskDueDate  = moment(value.gb_task_due_date).format("ddd, D MMM YYYY");
							else
							taskDueDate="";*/
							if(value.task_habit_name != null)
							var habitname = value.task_habit_name;
							else
							var habitname='';
							
							$('.task-name').html('<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Your task '+value.gb_task_name+' has been saved.</div>');
							
							taskListValue += '<tr><td>'+value.gb_task_name+'</td><td>'+value.gb_task_priority+'</td><td>'+habitname+'</td><td>'+value.gb_task_seen+'<br></td><td class="center"><a class="btn btn-xs btn-default tooltips task-edit" data-placement="top" data-original-title="Edit" data-task-id = "'+value.id+'"><i class="fa fa-pencil" style="color:#ff4401;"></i></a><a class="btn btn-xs btn-default tooltips delete-task" data-placement="top" data-original-title="Delete" data-entity="task" data-task-id = "'+value.id+'"><i class="fa fa-times" style="color:#ff4401;"></i></a></td>';
							
							taskLabel += '<a class="Step-your-goal2 task-text" data ='+value.id+'><li>'+ value.gb_task_name +'</li></a>';
							taskLabel += '<p style="margin-left:18px;">'+value.gb_task_seen+'</p>';
						});
						
						$('.tasks-label a').remove();
						$('.tasks-label p').remove();
						$('.tasks-label').append(taskLabel);
						taskLabel = '';
						
						$('#client-datatable-task tbody').append(taskListValue);
						listValue = '';
						i++;
						
						if((Param == 'goals') && (Param1 == 'edit')){
							// alert('a');
							// $("#smart_review_a").trigger("click");
						}
						
						$('.task-edit').click(function() {
							$('#m-selected-step').val(4).trigger('change');
						});

						$('.gb_goal_notes').text(data.goalInfo.gb_goal_notes);
					} 
					
					if(data.status == "success"){
						if(data.goalBuddy != ''){
							/*isJump = "false";
							isJumpMilestone = "false";*/
							var goalData = data.goalInfo;
							
							if(goalData != undefined)
							showGoalInfo(goalData);
							
							// $('.milestone-div-label').hide();
							$('.task-milestone-label').hide();
							$('#update-record').val('update-yes');
							$('#last-insert-id').val(data.goalBuddy);

							if(data.milestone_list != null && data.milestone_list != undefined && data.milestone_list != '') {
								var milestones = data.milestone_list, 
								milestoneTooltip = '';

		                        milestones.forEach(function(value) { 
		                        	milestoneTooltip += value.gb_milestones_name + '<br>' ;
		                        });

		                        Tipped.create('#Milestones', milestoneTooltip);
		                        // $('#Milestones').attr('title', milestoneTooltip);
		                        var milestoneList = $('.dd-list'); 
				                    var count = 1;
				                    $.each( milestones, function( key, value ) {
				                      var html= '<li class="dd-item row" data-milestones-id="'+value.id+'" style="line-height: 20px; !important"><div class="milestones-form"><div class="col-md-4 col-sm-4 milestones-date-cls"><input data-toggle="tooltip" title="Milestone Name" name="milestones" class="form-control milestones-name" value="'+value.gb_milestones_name+'" data-milestones-id="'+value.id+'" type="text" disabled="disabled" /></div><div class="col-md-4 col-sm-4 milestones-date-cls"><input data-toggle="tooltip" title="Milestone Due Date" class="form-control milestones-date datepicker_SYG4" autocomplete="off" name="milestones-date" required="" value="" type="text" disabled="disabled" id="miledate"/></div><div class="col-md-2 col-sm-2 m-t-20 pencil_find_sibling"><a><i class="fa fa-times delete-milestone-info" style="margin-right: 5px" data-milestones-id="'+value.id+'"></i></a><a><i class="fa fa-pencil edit-milestone-info" style="display:inline; font-size: 16px"></i></a><a><i class="fa fa-save save-milestone-info" data-milestones-id="'+value.id+'" style="display:none"></i></a></div></div></li>';
				                      //milestoneList.append(html);
				                      if(count == 1)
				                      {
				                            $('.datepicker_SYG4 ').datepicker({

				                            todayHighlight: 'TRUE',
				                            startDate: '-0d',
				                            autoclose: true,
				                            minDate: moment(),
				                            format: 'D, d M yyyy'
				                        });
				                      }
				                      count = count + 1;
				                    });
	                    	}
							
							if((Param == 'goals') && (Param1 == 'edit')){
								// alert('a');
								// $("#smart_review_a").trigger("click");
							}
						}
					}
				}
			});
		}
		
		/* show Goal info */
		function showGoalInfo(goalData) {

			$('.goal-name').text(goalData.gb_goal_name);
			$('.gb_goal_notes').text(goalData.gb_goal_notes);
			if(goalData.gb_achieve_description == ''){

				$('.achieve-description-label').hide();
			} 
			else {
				$('.achieve-description').text(goalData.gb_achieve_description);
			}
			if(goalData.gb_change_life_reason == '' || goalData.gb_change_life_reason == undefined){
				$('.change-life-label').hide();
			} 
			else {
				var changeLifeReason =goalData.gb_change_life_reason;
				var intValArray=changeLifeReason.split(',');
				var lifeChangeArr = [];
				for(var i=0;i<intValArray.length;i++){
					//lifeChangeArr[]=push(intValArray[i]);
				}
				//alert(lifeChangeArr);
				//$('.change-life').text(goalData.gb_change_life_reason);
			}
			if(goalData.gb_fail_description == ''){
				$('.fail-description-label').hide();
			} 
			else {
				// $('.fail-description').text(goalData.gb_fail_description);
				$('.fail-description').text(goalData.gb_important_accomplish);
			}
			if(goalData.gb_goal_seen){
				$('.goal-seen').text('Shared:'+goalData.gb_goal_seen);
			}
			if(goalData.gb_due_date != '0000-00-00'){
				var goalsmartDuedate=moment(goalData.gb_due_date).format("ddd, D MMM YYYY");
				$('.goal-due-date').text('Due date:'+goalsmartDuedate);
			}
		}
		
		function getHabit(hid, processbarDiv){
			$.ajax({
				url: public_url+'goal-buddy/showhabit',
				type: 'POST',
				data: {'habitId':hid},
				async: false,
				success: function(data){
					var data = JSON.parse(data);
				
					if(data.status == 'true'){
						$('.cancel_habbit_btn').removeClass('hidden');

						// Set default all milestones selected unchecked
						$('#gb_habit_select_all_milestone').attr('checked', false);

						// Set default habit seen to everyone
						$('#viewport-3').find('#wrapper_syg2_see_habit0').find('li[data-value="everyone"]').trigger('click').trigger('change');
						$('input[name="syg2_see_habit"]').val('everyone');

						// Set default habit reminder
						$('#viewport-3').find('#wrapper_syg2_send_msg0').find('li[data-value="only_if_I_am_late"]').trigger('click').trigger('change');
						$('input[name="syg2_send_msg"]').val('only_if_I_am_late');

						// Set default habit recurrence to daily
						$("input[name=SYG_habit_recurrence]").val('daily');
						$('#viewport-3').find('.SYG_habit_recurrence_wrapper .click-box').find('li[data-recurrence-type="daily"]').trigger('click').trigger('change');
							
						// Set default habit notes
						$('#SYG_notes').val('');

						// Set default habit id
						// $('#habit-id').val('');
						$('#goal-habit-id').val(data.goalBuddy.id);

						setTimeout(function() {
							
							if(data.milestonesTask != '') {
								var milestonesIds = data.goalBuddy.gb_milestones_id.split(',')
								showMIlestoneDd(milestonesIds);

								if(milestonesIds.length === parseInt($('#milestone_div option').length)) {
									
									$('#gb_habit_select_all_milestone').attr('checked', true);
								}else{

									$('#gb_habit_select_all_milestone').attr('checked', false);
								}
							} else
								$('.milestone-dropdown').hide(); 
						
							$('#habit-id').val(data.goalBuddy.id);
							$('#goal-habit-id').val(data.goalBuddy.id);
							$('#SYG_habits').val(data.goalBuddy.gb_habit_name);
							$('#SYG_habits').trigger('change');
							
							var habit_recurrence_type = data.goalBuddy.gb_habit_recurrence_type;
							
							$("input[name=SYG_habit_recurrence]").val(habit_recurrence_type);
							$('#viewport-3').find('.SYG_habit_recurrence_wrapper .click-box').find('li[data-recurrence-type="'+habit_recurrence_type+'"]').trigger('click').trigger('change');

							if(habit_recurrence_type === 'weekly') {
								var checkBoxLi = $('#viewport-3').find('.SYG_habit_recurrence_wrapper .show-weeks');
								days = data.goalBuddy.gb_habit_recurrence_week;
								days = days.split(',');
								var dayDiv = '';
								
								checkBoxLi.find('.prefTrainSlot').each(function(index, value) {
									var day = $(this).data('day');
								
									if($.inArray(day, days) != -1) {
										$(this).addClass('active');
										$(this).removeClass('inactive');
										$(this).find('input.goalEventRepeatWeekdays').attr("checked", true);
									} else {
										$(this).addClass('inactive');
										$(this).removeClass('active');
										$(this).find('input.goalEventRepeatWeekdays').attr("checked", false);
									}
								});
							}
							
							if(habit_recurrence_type === 'monthly') {
								var month = data.goalBuddy.gb_habit_recurrence_month;
								$('#viewport-3').find('.SYG_habit_recurrence_wrapper .month-count').find('.month-date').val(month).trigger('change');
							}
							
							// who can see this habit
							var habit_seen = data.goalBuddy.gb_habit_seen;
							$('#viewport-3').find('#wrapper_syg2_see_habit0').find('li[data-value="'+habit_seen+'"]').trigger('click').trigger('change');
							$('input[name="syg2_see_habit"]').val(habit_seen);
							
							// habit reminder
							var habit_reminder = data.goalBuddy.gb_habit_reminder;
							$('#viewport-3').find('#wrapper_syg2_send_msg0').find('li[data-value="'+habit_reminder+'"]').trigger('click').trigger('change');
							$('input[name="syg2_send_msg"]').val(habit_reminder);
							
						}, 2000);
						
						$('input[name="SYG_habit_recurrence"]').val(data.goalBuddy.gb_habit_recurrence);
						$('#SYG_notes').val(data.goalBuddy.gb_habit_notes);
						$(".milestone_div_class option:selected").val(data.goalBuddy.gb_milestones_name);
						$("select#milestone_div").val(data.goalBuddy.gb_milestones_id).selectpicker("refresh");
						$('#goal-habit-id').val(data.goalBuddy.id);
			
						$('#last-insert-id').val(data.goalBuddy.goal_id);
						$('#SYG_notes').val(data.goalBuddy.gb_habit_notes);
					}
				}
			});
		}
		
		function getHabitById(hid, gName , gid){
			var toDay = moment().format("YYYY-MM-DD");
			$.ajax({
				url: public_url+'goal-buddy/get-listing-habit',
				type: 'POST',
				data: {'habit_id':hid},
				success: function(data){
					var data = JSON.parse(data);
					
					if(data.status == true){
						var daysHtml = '';
						var taskDaysHtml='';

						$('.unchecked-days').html('');
						$(".edit-habit").data('edithabit',data.habitDetails.id);
						$(".delete-habit-list").data("deletehabitid",data.habitDetails.id);
						$('.habit-name').text(data.habitDetails.gb_habit_name);
						$('.frequency').text(data.habitDetails.gb_habit_recurrence_type);
						$('.shared').text(data.habitDetails.gb_habit_seen);
						$('.goal-with-milestone').html(data.habitDetails.mile_stone_name.split(',').join('<br>'));
						
						// if(data.uncompletedHabit.length >0){
						// 	var lastdd='';
						// 	$.each(data.uncompletedHabit, function (index, value) {
						// 		if(value.eventType=='task'){
						// 			if(lastdd!=value.eventDueDate){
						// 				var dd = moment(value.eventDueDate).format("ddd, D MMM YYYY");
						// 				daysHtml +=  '<strong>'+dd+'</strong>';
						// 				lastdd=dd;
						// 			}
						// 			daysHtml +=  '<div class="checkbox clip-check check-primary"><input type="checkbox" name="goal_hide" id="habit_'+value.eventId+'" value="1" class="habit-day-div" data ="'+value.eventId+'" data-type ="'+value.recurrenceType+'"';
						// 			if(value.eventStatus==1){
						// 				daysHtml +='checked=""';
						// 			}
						// 			if((value.eventDueDate > toDay)){
						// 				daysHtml +='disabled="disabled"';
						// 			}
						// 			daysHtml +='><label for="habit_'+value.eventId+'" d>'+value.eventTitel+'</label></div>';
						// 			lastdd=value.eventDueDate;
						// 		}
						// 	});
						// }
						// $('.unchecked-days').html(daysHtml);
						// $('.unchecked-days-task').html(taskDaysHtml);
					}
				}
			});
		}
		
		function getTask(tid, processbarDiv){
			$.ajax({
				url: public_url+'goal-buddy/showtask',
				type: 'POST',
				data: {'taskId':tid},
				success: function(data){
					var data = JSON.parse(data);
		
					if(data.status == 'true'){

						var task_habit = [];
						$('#task-id').val(data.goalBuddy.id);
						$('#viewport-4').find('input[name="associatedHabitWithTask"]').val('');
						$('#viewport-4').find('input[name="goalTaskData"]').val(data.goalBuddy.id);
						$('.cancel_task_btn').removeClass('hidden');

						// Set default task priority
						$("#SYG3_priority").val('Low');
						$('#viewport-4').find('#gb_task_priority_wrapper').find('li[data-value="Low"]').trigger('click').trigger('change');

						// Set default task recurrence 
						$("input[name=SYG3_see_task]").val('daily');
						$('#viewport-4').find('ul#gb_task_recurrence_type_wrapper').find('li[data-value="daily"]').trigger('click').trigger('change');
                        $('#SYG_task_recurrence0').prop('checked', true);
						$('#viewport-4').find('ul#gb_task_recurrence_type_wrapper').children('li').addClass('disabled_task_recurrence');

						// Set default task seen
						$('#viewport-4').find('#gb_task_seen_wrapper').find('li[data-value="everyone"]').trigger('click').trigger('change');
						$("input[name=SYG3_see_task]").val('everyone');
						
						// Set default task reminder
						$('#viewport-4').find('#gb_task_reminder_wrapper').find('li[data-value="When_task_is_overdue"]').trigger('click').trigger('change');
						$("input[name=SYG3_send_msg]").val('When_task_is_overdue');

						// Set default task notes
						$('#note').val('');

						if(data.habitTask != ''){
							$('.task-habit-div').show();
							var optionValue = '<select id="habit_div" name="habit_value" class="form-control  taskhabit_div_class" required=""><option value="">-- Select --</option>';
					
							$.each(data.habitTask,function(key, value) {
								if(value.id==data.goalBuddy.gb_habit_id) {
									task_habit = value;
									let taskHabiStringify = JSON.stringify(value);
									$('#viewport-4').find('input[name="associatedHabitWithTask"]').val(taskHabiStringify);
									optionValue += '<option value="'+value.id+'" selected>'+value.gb_habit_name+'</option>';
								} else
								optionValue += '<option value="'+value.id+'">'+value.gb_habit_name+'</option>';
							});
							optionValue += '</select>';
							$('.task-habit-dropdown').html(optionValue);
							initSelectpicker($('.task-habit-dropdown select'));
							
							$('.taskhabit_div_class').selectpicker('refresh');
							optionValue = '';
						}
						else{ 
							$('.task-habit-div').hide();   
						}
	
						$('#task-id').val(data.goalBuddy.id);
						$('#SYG3_task').val(data.goalBuddy.gb_task_name);
						$('#SYG3-time').val(data.goalBuddy.gb_task_time);
						$("#SYG3_priority").val(data.goalBuddy.gb_task_priority);
						$("input[name=SYG3_see_task]").val(data.goalBuddy.gb_task_seen);
						$("input[name=SYG3_send_msg]").val(data.goalBuddy.gb_task_reminder);
						$('select#habit_div').val(data.goalBuddy.gb_habit_id).selectpicker("refresh");
						$('#note').val(data.goalBuddy.gb_task_note);
						
						var task_priority = data.goalBuddy.gb_task_priority;
						var task_recurrence_type = data.goalBuddy.gb_task_recurrence_type;
						var task_recurrence_week = data.goalBuddy.gb_task_recurrence_week;
						var task_recurrence_month = data.goalBuddy.gb_task_recurrence_month;
						var task_seen = data.goalBuddy.gb_task_seen;
						var task_reminder = data.goalBuddy.gb_task_reminder;
						
						task_recurrence_type = data.goalBuddy.gb_task_recurrence_type == '' ? task_habit.gb_habit_recurrence_type : data.goalBuddy.gb_task_recurrence_type;
						task_recurrence_week = data.goalBuddy.gb_task_recurrence_type == '' ? task_habit.gb_habit_recurrence_week : data.goalBuddy.gb_task_recurrence_week;
						task_recurrence_month = data.goalBuddy.gb_task_recurrence_type == '' ? task_habit.gb_habit_recurrence_month : data.goalBuddy.gb_task_recurrence_month;
		
						setTimeout(function() {
							$('#SYG3_task').trigger('change');
							
							// task_priority
							$('#viewport-4').find('#gb_task_priority_wrapper').find('li[data-value="'+task_priority+'"]').trigger('click').trigger('change');
							
							// start: task_recurrence_type
							if(task_recurrence_type === 'daily' || task_recurrence_type === 'weekly') {
								$('#viewport-4').find('ul#gb_task_recurrence_type_wrapper').find('li[data-value="weekly"]').trigger('click').trigger('change');
                                $('#SYG_task_recurrence1').prop('checked', true);
							} else {
								$('#viewport-4').find('ul#gb_task_recurrence_type_wrapper').find('li[data-value="'+task_recurrence_type+'"]').trigger('click').trigger('change');
                                $('#SYG_task_recurrence2').prop('checked', true);
							}
							
							$('#viewport-4').find('ul#gb_task_recurrence_type_wrapper').children('li').addClass('disabled_task_recurrence');

							if(task_recurrence_type === 'weekly' || task_recurrence_type === 'daily') {
								var checkBoxLi = $('#viewport-4').find('#gb_task_recurrence_weeks');
								
								var days = '';
								if(task_recurrence_type === 'weekly') {
									days = task_recurrence_week.split(',');
								} else if(task_recurrence_type === 'daily') {
									weekData = 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday';
                                    days = weekData.split(',');
								} 
								// console.log(days);
								checkBoxLi.find('div.prefTrainSlot').each(function(index, value) {
									let dayValue = $(this).find('input.taskEventRepeatWeekdays').val();
									if($.inArray(dayValue, days) == -1) {
										$(this).addClass('inactive');
										$(this).removeClass('active');
										$(this).find('input.taskEventRepeatWeekdays').attr("checked", false);
										$(this).addClass('taskEventRepeatWeekdayNotInHabit');						
									} else {
										$(this).addClass('active');
										$(this).removeClass('inactive');
										$(this).find('input.taskEventRepeatWeekdays').attr("checked", true);
										$(this).removeClass('taskEventRepeatWeekdayNotInHabit');
									}
								});
							}
							
							if(task_recurrence_type === 'monthly' || (task_recurrence_type == '' && task_habit.gb_habit_recurrence_type === 'monthly')) {
								$('#viewport-4').find('#gb_task_recurrence_month').val(task_recurrence_month).trigger('change');
							}
							// end: task_recurrence_type
							
							// task_seen
							$('#viewport-4').find('#gb_task_seen_wrapper').find('li[data-value="'+task_seen+'"]').trigger('click').trigger('change');
							
							// task_reminder
							$('#viewport-4').find('#gb_task_reminder_wrapper').find('li[data-value="'+task_reminder+'"]').trigger('click').trigger('change');
							
							jQuery('html, body').stop().animate({
								scrollTop: $('#viewport-4').find('li[data-index=0]').offset().top - 100
							}, 'slow');
							
						}, 2000);
						// Add this one for scroll issue
						jQuery('html, body').stop().animate({
						scrollTop: $('#viewport-4').find('li[data-index=0]').offset().top - 100
						}, 'slow');
						// end 
					}
				}
			});
		}
		
		/* Start: check each event from epic goal, habit, milestone, task */
		setTimeout(function() {
			$(document).ready(function() {
				// $('body').on('change', 'input.form-control, select.form-control, textarea.form-control', function() {
				// 	saveDataOnEveryInput();
				// });

				$('body').on('click', 'button', function() {
					saveDataOnEveryInput();
				});

				$('body').on('click', 'li.is-this-goal-imidiate-priority', function() {
					saveDataOnEveryInput();
				});

				/*$('body').on('click', 'li.send-reminders', function() {
					saveDataOnEveryInput();
				});

				$('body').on('click', 'li.who-can-view', function() {
					saveDataOnEveryInput();
				});*/

				/*$('body').on('click', 'li.task-prioities', function() {
					saveDataOnEveryInput();
				});*/
			});
		}, 1000);
		/* End: check each event from epic goal, habit, milestone, task */
	
		/* Start: save data on every input */
	  	function saveDataOnEveryInput() {
	  		var formdata = {};
	  		formdata['goal_notes'] = $('#goal_notes').val();
	  		if($('#viewport-1').is(':visible')) {
	  			// For Goal data
	  			var goalDueDate = $('#datepicker_SYG').val();
				formdata['name'] = $('#name_goal').val();
				formdata['describe_achieve'] = $("#describe_achieve").val();
				formdata['goal_year'] = $("input[name=goal_year]").val();
				formdata['change_life'] = prepareCheckBoxSelect($('#form').find('input[name="change_life"]').val());
				formdata['accomplish'] = $('#accomplish').val();
				formdata['failDescription'] = $('#fail-description').val();
				formdata['gb_relevant_goal'] = $('#gb_relevant_goal').val();
				formdata['gb_relevant_goal_event'] = $('#gb_relevant_goal_event').val();
				formdata['image'] = $('input[name="prePhotoName"]').val();
				formdata['template'] = $('#goal-template').val();
				formdata['due_date'] = '';

				if(goalDueDate!=''){
					formdata['due_date'] =  moment(goalDueDate, 'ddd, D MMM YYYY').format("YYYY-MM-DD");
				}

				formdata['goal_seen'] = $("input[name=goal_seen]").val();
				formdata['send_msg_type'] = $("input[name=send_msgss]").val();
				formdata['form_no'] = 1;
				formdata['update_status'] = $('#update-record').val();
					 
				if(formdata['change_life']) {
					formdata['change_life'] = '';
				}

				if(formdata['name']!=''){
					if(formdata['update_status'] == 'update-yes') {
						formdata['last_insert_id'] = $('#last-insert-id').val();
						saveGoalData(formdata);
					}
				}

				
	  		} else if($('#viewport-2').is(':visible')) {
	  			// For milestone data
	  			var allMilestonesNames = [];
				var allMilestonesNamesId=[];
				var allMilestonesDates = [];

				$('.milestones-name').each(function() {
					var milestonesNames=$(this).val() ;
					var milestonesId = $(this).data('milestones-id');
					if(milestonesNames != ''){
						if(milestonesId!= '')
						allMilestonesNamesId.push(milestonesId +':'+ milestonesNames);
						else
						allMilestonesNames.push(milestonesNames);
					}
				});
				
				$('.datepicker_SYG4').each(function() {
					if($(this).val() != ''){
						allMilestonesDates.push($(this).val());
					}
				});
					
				if(allMilestonesNames.length > 0){
					formdata['milestones-names'] = allMilestonesNames;
				}
				if(allMilestonesNamesId.length > 0){
					formdata['milestones-names-id'] = allMilestonesNamesId;
				}
				if(allMilestonesDates.length > 0){
					formdata['milestones-dates'] = allMilestonesDates;
				}
				var totalNameLength=parseInt(allMilestonesNames.length)+parseInt(allMilestonesNamesId.length);
				var totalDateLength=allMilestonesDates.length;
					
				formdata['gb_milestones_seen'] = $("input[name=gb_milestones_seen]").val();
				formdata['gb_milestones_reminder'] = $("input[name=gb_milestones_reminder]").val();
				formdata['form_no'] = 2;
				formdata['last_insert_id'] = $('#last-insert-id').val();
				formdata['goal_id_mile']=$('#goal_milestones_id').val();
				formdata['milestones_id'] = $('#milestones_id').val();

				if(allMilestonesNames.length == allMilestonesDates.length) {
					saveGoalData(formdata);
				}
				
	  			
	  		} else if($('#viewport-3').is(':visible')) {
	  			// For habit data
	  			// var habitId = $('#habit-id').val();
	  			var habitId = $('#goal-habit-id').val();
	  			if(habitId != null && habitId != undefined && habitId != '') {
	  				formdata['habit_id'] = habitId;
	  			}
	  			
				formdata['habit_name'] = $('#SYG_habits').val();
				$('.habitRecurrenceWrapper').each(function(){
					if($(this).hasClass('active'))
					{
						formdata['habit_recurrence'] = $(this).data('recurrence-type');
					}
				})
				// formdata['habit_recurrence'] = $("input[name=SYG_habit_recurrence]").val();
				if(formdata['habit_recurrence'] == 'weekly'){
					var selectedWeeks = [];
					$('.goalEventRepeatWeekdays').each(function() {
						if($(this).attr('checked'))
						{
							selectedWeeks.push($(this).val());
						}
					});
					formdata['habit_weeks'] = selectedWeeks;
				} else if(formdata['habit_recurrence'] == 'monthly'){
					formdata['month'] = $(".month-date option:selected").text();
				}

				formdata['habit_notes'] = $('#SYG_notes').val();
				formdata['habit_milestone']=$('select[name="milestone_value"]').val();
				if(formdata['habit_milestone'] == '' || formdata['habit_milestone'] == undefined || formdata['habit_milestone'] == null)
				{
					$('#goalHabitNextButton').addClass('disabled');
				}
				else
				{
					$('#goalHabitNextButton').removeClass('disabled');
				}
				$('.who-can-view').each(function(){
					if($(this).hasClass('active'))
					{
						formdata['habit_seen'] = $(this).data('value');
					}
				})
				$('.send-reminders').each(function(){
					if($(this).hasClass('active'))
					{
						formdata['habit_reminders'] = $(this).data('value');
					}
				})
				// formdata['habit_seen'] = $("input[name=syg2_see_habit]").val();
				// formdata['habit_reminders'] = $("input[name=syg2_send_msg]").val();
				formdata['form_no'] = 3;
				formdata['last_insert_id'] = $('#last-insert-id').val();

	  			saveGoalData(formdata);

	  		} else if($('#viewport-4').is(':visible')) {
	  			// For task data
	  			var selectedWeeks = [], 
	  			taskId = $('#task-id').val();

	  			if(taskId != null && taskId != undefined && taskId != '') {
					formdata['task_id'] = taskId;
	  			}

	  			formdata['task_name'] = $('#SYG3_task').val();
				formdata['note'] = $('#note').val();
					
				formdata['task_priority'] = $("#SYG3_priority").val();
				formdata['task_habit_id'] = $('select[name="habit_value"]').val();
				formdata['task_seen'] = $("input[name=SYG3_see_task]").val();
				formdata['task_reminders'] = $("input[name=SYG3_send_msg]").val();
				formdata['task_recurrence'] = $("input[name=SYG_task_recurrence]").val();
					
				if(formdata['task_recurrence'] == 'weekly'){
					// $('input[type="checkbox"]:checked.taskEventRepeatWeekdays').each(function() {
					$('input.taskEventRepeatWeekdays').each(function() {
						var attr = $(this).attr('checked');
						if (typeof attr !== typeof undefined && attr !== false) {
							selectedWeeks.push($(this).val());
						}
					});
					formdata['task_weeks'] = selectedWeeks;
				} else if(formdata['task_recurrence'] == 'monthly'){
					formdata['month'] = $(".month-date-task option:selected").text();
				}
				formdata['form_no'] = 4;
				formdata['last_insert_id'] = $('#last-insert-id').val();

	  			saveGoalData(formdata);
	  		}
	    }
	    /* End: save data on every input */

	    function saveGoalData(formdata){
			$.ajax({
				url: 	public_url+'goal-buddy/savegoal',
				type: 	'POST',
				data: 	{'formData':formdata},
				success: function(data){
					
					data = JSON.parse(data);
					if(data.form == 'milestones-list'){
						$('#viewport-2').find('#milestones_id').val(data.milestoneId);


					} else if(data.form == 'habit-list'){
						//console.log(data);
						$('#habit-id').val(data.habitId);
						$('#goal-habit-id').val(data.habitId);
						
						
					} else if(data.form == 'task-list'){
						$('#task-id').val(data.taskId);
						
					} else if(data.status == "success"){
						if(data.goalBuddy != ''){
							//console.log(data.goalBuddy);
							$('#update-record').val('update-yes');
							
							$('#last-insert-id').val(data.goalBuddy);
						}
					}
				}
			});
		}
		