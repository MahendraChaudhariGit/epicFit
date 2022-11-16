var isJump ="true";
var i=j=k=b=c=d=e=a=f=g=h=m=p=n=1;
var isJumpMilestone ="true";
var isJumpTask ="true";
var optionValue;
var goalInfo,habitInfo,taskInfo;


    
$(document).ready(function(){

	$('.milestones-date').datepicker({
			todayHighlight: 'TRUE',
			startDate: '-0d',
			autoclose: true,
			minDate: moment(),
			format: 'D, d M yyyy',
			// endDate: '+'+days+'d',
		});

	// $("input[name=goal-Send-mail]:radio").click(function() {	 
	//  var value = $("input[name='goal-Send-mail']:checked").val(); 
	// 	if(value == 1){
	// 		$("#goal_notification_option").val("when_overdue");
	// 	}else if(value == 2){
	// 		$("#goal_notification_option").val("daily");
	// 	}else if(value == 3){
	// 		$("#goal_notification_option").val("weekly");
	// 	}else if(value == 4){
	// 		$("#goal_notification_option").val("monthly");
	//  	}else if(value == 5){
	//  		$("#goal_notification_option").val("none");
	// 	}
	// });
	
	$('body').on('click','.goal-reminders',function(){		
		var input = $('input[name="goal-Send-mail"]');
		input.each(function(){
			if($(this).is(':checked'))
			{
				$(this).attr('checked',true);
			}
			else
			{
				$(this).attr('checked',false);
			}
		})
	});

	$('body').on('click','.milestones-reminders',function(){
		var input = $('input[name="milestones-Send-mail"]');
		input.each(function(){
			if($(this).is(':checked'))
			{
				$(this).attr('checked',true);
			}
			else
			{
				$(this).attr('checked',false);
			}
		})
	});


	$('body').on('click','.habits-reminders',function(){		
		var input = $('input[name="habits-send-mail"]');
		input.each(function(){
			if($(this).is(':checked'))
			{
				$(this).attr('checked',true);
			}
			else
			{
				$(this).attr('checked',false);
			}
		})
	});

	$('body').on('click','.task-reminders',function(){			 
		var input = $('input[name="creattask-send-mail"]');
		input.each(function(){
			if($(this).is(':checked'))
			{
				$(this).attr('checked',true);
			}
			else
			{
				$(this).attr('checked',false);
			}
		})
	});
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
        
		if($(this).hasClass('Step-your-goal4')){
			$('#wizard-ul').find('.formStepThird').removeClass('activeFormStep');
			$('#wizard-ul').find('.formStepSecond').addClass('activeFormStep');
			$('#wizard-ul').find('.formStepfirst').removeClass('activeFormStep');
			$('#wizard-ul').find('.formStepFourth').removeClass('activeFormStep');
			$('#wizard-ul').find('.formStepFive').removeClass('activeFormStep');
			$('.backward').trigger('click');
			
			$('.step').each(function(){
			    if($('.milestoneData').data('value') == 0){
					$('.backward').trigger('click');
				}else{
					return false;
				}

			});
			

		}
		if($(this).hasClass('Step-your-goal1')){
			$('#wizard-ul').find('.formStepThird').removeClass('activeFormStep');
			$('#wizard-ul').find('.formStepSecond').removeClass('activeFormStep');
			$('#wizard-ul').find('.formStepfirst').addClass('activeFormStep');
			$('#wizard-ul').find('.formStepFourth').removeClass('activeFormStep');
			$('#wizard-ul').find('.formStepFive').removeClass('activeFormStep');
			$('.backward').trigger('click');
			console.log('step 1');
			$('.step').each(function(){
			if($('.goalName').data('value') == 0){
				$('.backward').trigger('click');
			}else{
				return false;
			}

			});
			

		}
		if($(this).hasClass('Step-your-goal3')){
			$('#wizard-ul').find('.formStepThird').addClass('activeFormStep');
			$('#wizard-ul').find('.formStepSecond').removeClass('activeFormStep');
			$('#wizard-ul').find('.formStepfirst').removeClass('activeFormStep');
			$('#wizard-ul').find('.formStepFourth').removeClass('activeFormStep');
			$('#wizard-ul').find('.formStepFive').removeClass('activeFormStep');
			$('.backward').trigger('click');
			
			$('.step').each(function(){
			// if($('.newHabitForm').data('value') == 0){
			if($('.newTask').data('value') == 0){
				$('.backward').trigger('click');
			}else{
				return false;
			}

			});

		}
		if($(this).hasClass('Step-your-goal2')){
			$('#wizard-ul').find('.formStepThird').removeClass('activeFormStep');
			$('#wizard-ul').find('.formStepSecond').removeClass('activeFormStep');
			$('#wizard-ul').find('.formStepfirst').removeClass('activeFormStep');
			$('#wizard-ul').find('.formStepFourth').addClass('activeFormStep');
			$('#wizard-ul').find('.formStepFive').removeClass('activeFormStep');
			$('.backward').trigger('click');
			
			$('.step').each(function(){
			// if($('.newTask').data('value') == 0){
			if($('.showNext').data('value') == 0){
				$('.backward').trigger('click');
			}else{
				return false;
			}

			});

		}
		
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
	
// $( document ).on( 'click', '.next-step', function() {
			
	
// 		// var processbarDiv = $(this).closest('tr');
// 		// console.log(processbarDiv);

// 		$('.habit-form').show();
// 		var habitId = 922;
// 		getHabit(habitId);
// 	});

	$( document ).on( 'click', '.habit-edit', function() {
		j=1;
		isJump = "false";
		var processbarDiv = $(this).closest('tr');

		//add readonly if template
		var goal_type;
		goal_type = $('input[name="chooseGoal"]:checked').val();
		
		if(goal_type == 'create_new_goal'){
            $('#SYG_habits').attr('readonly', false);
        }else if(goal_type == 'choose_form_template'){
            $('#SYG_habits').attr('readonly', true);
        }

		// console.log(processbarDiv);
		$('.habit-form').show();
		$('.habit-listing').hide();
		var habitId = $(this).attr('data-habit-id');
		$('.backward').trigger('click');
		getHabit(habitId, processbarDiv);
	});

	$( document ).on( 'click', '.task-edit', function() {
		j=1;
		isJump = "false";
		var processbarDiv = $(this).closest('tr');
		// console.log(processbarDiv);
		// $('.habit-form').show();
		// $('.habit-listing').hide();
		var taskId = $(this).attr('data-task-id');
		$('.backward').trigger('click');
		$("#SYG3_selective_friends").attr('value','');
		console.log(taskId,1);
		getTask(taskId, processbarDiv);
	});
	
	$( document ).on( 'click', '.add-habit', function() {
		j=1;
		isJump = "false";
		$('#SYG_habits').val('');
		$('#SYG_habits').removeAttr('readonly');
		$('input[name="SYG_habit_recurrence"]').prop('checked', false);
		$('#SYG_notes').val('');
		$('input[name="SYG_notes"]').prop('checked', false);
		$('input[name="habit"]').prop('checked', false);
		$('input[name="habits-send-mail"]').prop('checked', false);
		
		
	
		$('#gb_habit_select_all_milestone').prop('checked', false);
		$('input[name="syg2_see_habit"]').prop('checked', false);
		$("#syg2_selective_friends").attr('value','')
		if($("#all-my-friends").val() != undefined && $("#all-my-friends").val() != ''){
            $("#syg2_selective_friends").amsifySuggestags("refresh");
            var my_friends = JSON.parse($("#all-my-friends").val());
            var options = [];
                
            for(var aaa =0; aaa < my_friends.length; aaa++ ){
                options[aaa] = {'tag':my_friends[aaa].name,'value':my_friends[aaa].id}
            }
        
            $('.autocomplete').amsifySuggestags({
                type :'bootstrap',
                suggestions: options,
                whiteList:true,
            });
        }
		$("#syg2_selective_friends").parent().addClass('hidden');
		$('#newHabitForm input[type="radio"]').each(function(){
			$(this).prop('checked',false);
		})
		$('.goalEventRepeatWeekdays').each(function(){
			$(this).prop('checked',false);
		})
		$('#newHabitForm .showTimeBox').hide(); 
		$('#newHabitForm .showDayBox').hide(); 
		$('#newHabitForm .showMonthBox').hide();     

		
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
		// $('#syg2_see_habit0').each(function() {
		// 	$(this).prop('checked',true);
		// });
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
		$('.backward').trigger('click');
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
	
	// $( document ).on( 'click', '.task-edit', function() {
	// 	k=1;
	// 	isJumpTask = "false";
	// 	var taskId = $(this).attr('data-task-id');

	// 	$('.task-form').show();
	// 	$('.task-listing').hide();
	// 	var processbarDiv = $(this).closest('tr');
	// 	getTask(taskId, processbarDiv);
	// });
	
	$(document).on( 'click', '.add-task', function() {
		k=1;
		isJumpTask = "false";

		$('#SYG_habits').val('');
		$('#SYG3_task').removeAttr('readonly');
	     $('#SYG3_task').val(''); // 02-06-2021

		$('input[name="SYG_task_recurrence"]').prop('checked', false);
		$('#note').val('');
		// $('#gb_habit_select_all_milestone').trigger('click');
		$('input[name="Priority"]').prop('checked', false);
		$('input[name="creattask-send-mail"]').prop('checked', false);
		$('input[name="SYG3_see_task2"]').prop('checked', false);
		$("#SYG3_selective_friends").attr('value','')
		if($("#all-my-friends").val() != undefined && $("#all-my-friends").val() != ''){
            $("#SYG3_selective_friends").amsifySuggestags("refresh");
            var my_friends = JSON.parse($("#all-my-friends").val());
            var options = [];
                
            for(var aaa =0; aaa < my_friends.length; aaa++ ){
                options[aaa] = {'tag':my_friends[aaa].name,'value':my_friends[aaa].id}
            }
        
            $('.autocomplete').amsifySuggestags({
                type :'bootstrap',
                suggestions: options,
                whiteList:true,
            });
        }
		$("#SYG3_selective_friends").parent().addClass('hidden');
		// $('#task-id').val(data.goalBuddy.id);
		$('input[name="associatedHabitWithTask"]').val('');
		// $('input[name="goalTaskData"]').val(data.goalBuddy.id);
		$('.cancel_task_btn').removeClass('hidden');
		$('.taskNext input[type="radio"]').each(function(){
			$(this).prop('checked',false);
		})

		$('.taskEventRepeatWeekdays').each(function(){
			$(this).prop('checked',false);
			

		})
		$('.taskNext .showTimeBox').hide(); 
		$('.taskNext .showDayBox').hide(); 
		$('.taskNext .showMonthBox').hide(); 

		$('input[name="task_id"]').val('');
		$('input[name="task_id_new"]').val('');
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
		// $('#SYG3_see_task2').each(function() {
		// 	$(this).prop('checked',true);
		// });
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
		$('.backward').trigger('click');
		$('.task-form').show();

		getAllHabit();
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
		console.log(taskId,2);
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
			var formData = {};
			formData['goalId'] = $('#last-insert-id').val();
			$('.finalSubmitLoader').show();
			$('.final-step-goalbuddy_edit').addClass('disabled');
			$.post(public_url+'goal-buddy/checkgoalform',formData,function(response){
				$('.finalSubmitLoader').hide();
				$('.final-step-goalbuddy_edit').removeClass('disabled');
				if(response.canFinalSubmit){
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
				}else{
					var errorElement = $('#formFinalSubmitError');
					errorElement.empty();
					var errorHtml = '<div class="alert alert-danger alert-dismissible">\
							<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
							<strong>Error!</strong> '+response.incompletedStep+'.\
						</div>';
					errorElement.append(errorHtml);
					$('html, body').animate({
						scrollTop: $("#formFinalSubmitError").offset().top
					}, 2000);
					return false;
				}
			},'json');
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

			formdata['describe_achieve'] = $("#description").val();
			formdata['goal_year'] = $("input[name=goal_year]").val();
			formdata['change_life'] = prepareCheckBoxSelect($('#form').find('input[name="change_life"]').val());
			$.inArray('Other',formdata['change_life'])
			{	 
				formdata['gb_change_life_reason_other'] = $('#form').find('textarea[name="gb_change_life_reason_other"]').val();
			}
			// formdata['change_life'] = prepareMultiSel($('#form').find('select[name="change_life"]'));
			formdata['accomplish'] = $('#accomplish').val();
			formdata['failDescription'] = $('#fail-description').val();
			formdata['gb_relevant_goal'] = $('#relevant_goal').val();
			formdata['gb_relevant_goal_event'] = $('#gb_relevant_goal_event').val();
			formdata['image'] = $('input[name="prePhotoName"]').val();
			formdata['template'] = $('#goal-template').val();
			if(goalDueDate!=''){
				formdata['due_date'] =  moment(goalDueDate, 'ddd, D MMM YYYY').format("YYYY-MM-DD");}
				formdata['goal_seen'] = $("input[name=goal_seen]").val();
				formdata['send_msg_type'] = $("input[name='goal-Send-mail']:checked").val(); 
				if(formdata['send_msg_type'] == "daily"){
					formdata['Send_mail_time'] = $('#daily_time_goal').val();
				}else if(formdata['send_msg_type'] == "weekly"){
					formdata['Send_mail_time'] = $('#weekly_day_goal').val();
				}else if(formdata['send_msg_type'] == "monthly"){
					formdata['Send_mail_time'] = $('#month_date_goal').val();
				}
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

					formdata['gb_milestones_reminder'] = $("input[name='milestones-Send-mail']:checked").val(); 
					if(formdata['gb_milestones_reminder'] == "daily"){
						formdata['Send_mail_milestones_time'] = $('#daily_time_milestones').val();
					}else if(formdata['gb_milestones_reminder'] == "weekly"){
						formdata['Send_mail_milestones_time'] = $('#weekly_day_milestones').val();
					}else if(formdata['gb_milestones_reminder'] == "monthly"){
						formdata['Send_mail_milestones_time'] = $('#month_date_milestones').val();
					}
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
							$('#habitlist tr').find('.habit-edit').each(function(key,obj) {
								var noOfHabits = $('#habitlist').find('tr').length;
								$("input[name=no_of_habit]").val(noOfHabits);
								if(key == 0){
									$("input[name=habit_index]").val(key);
									var habit_id = $(this).data('habit-id');
									$('.habit-listing').hide();
									$('.habit-form').show();
									$('#SYG_habits').attr('readonly', true);
									getHabit(habit_id);
								}				
							});
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
					// formdata['habit_notes'] = $('#SYG_notes').val();
					formdata['habit_notes'] = [];
					$('input[name="SYG_notes"]:checked').each(function(){
						formdata['habit_notes'].push($(this).val());
						if($(this).val() == 'Other'){
							formdata['gb_habit_note_other'] = $('#habit_note_other').val();
						}
					});
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
					// viewStep.find('.send-reminders').each(function(){
					// 	if($(this).hasClass('active'))
					// 	{
					// 		formdata['habit_reminders'] = $(this).data('value');
					// 	}
					// })

					formdata['habit_reminders'] = $("input[name='habits-send-mail']:checked").val(); 
					if(formdata['habit_reminders'] == "daily"){
						formdata['Send_mail_habits_time'] = $('#daily_time_habits').val();
					}else if(formdata['habit_reminders'] == "weekly"){
						formdata['Send_mail_habits_time'] = $('#weekly_day_habits').val();
					}else if(formdata['habit_reminders'] == "monthly"){
						formdata['Send_mail_habits_time'] = $('#month_date_habits').val();
					}
					
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
						var habit_index = $('#habit-index').val();
						var new_habit_index = parseInt(habit_index) + 1;
						var no_of_index = $('#habitlist').find('tr').length;
						$('#habitlist tr').find('.habit-edit').each(function(key,obj) {
						    if(new_habit_index == key){
								if(no_of_index  == new_habit_index){
									$('.habit-form').hide();
									$('.habit-listing').show();
									$('.cancel_habbit_btn').addClass('hidden');
								}else{
									$('#habit-index').val(new_habit_index);
									$(this).trigger('click');
								}
						    }
						});
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
							if($('a[href="#step-3"]').hasClass('done')){
								$('.task-listing').hide();
								$('.task-friends-section').hide();
								// $('.task-form').show();
	                    		$('#tasklist tr').find('.task-edit').each(function(key,obj) {
								var noOfTask = $('#tasklist').find('tr').length;
								$("input[name=no_of_task]").val(noOfTask);
								if(key == 0){
									$("input[name=task_index]").val(key);
									var task_id = $(this).data('task-id');
									$('.task-listing').hide();
									$('.task-form').show();
								    $('#SYG3_task').attr('readonly', true);
									console.log(task_id,3);
									getTask(task_id);
								}				
							});

							}else{
								var habit_index = $('#habit-index').val();
								var new_habit_index = parseInt(habit_index) + 1;
								var no_of_index = $('#habitlist').find('tr').length;
							
								if(habit_index != "0"){
									$('#habitlist tr').find('.habit-edit').each(function(key,obj) {
										if(new_habit_index == key){
											if(no_of_index  == new_habit_index){
												if($('a[href="#step-3"]').hasClass('done')){
													$('.task-listing').show();
													$('.task-friends-section').hide();
													$('.task-form').hide();
												}else{
													$('.habit-form').hide();
													$('.habit-listing').show();
													$('.cancel_habbit_btn').addClass('hidden');
													$('a[href="#step-3"]').removeClass('selected');
													$('a[href="#step-3"]').addClass('done');
												}
											}else{
												$('#habit-index').val(new_habit_index);
												$(this).trigger('click');
											}
										}else{
											if(no_of_index  == new_habit_index){
												if($('a[href="#step-3"]').hasClass('done')){
													$('.task-listing').hide();
													$('.task-friends-section').hide();
													$('.task-form').show();
												}else{
													$('.habit-form').hide();
													$('.habit-listing').show();
													$('.cancel_habbit_btn').addClass('hidden');
													$('a[href="#step-3"]').removeClass('selected');
													$('a[href="#step-3"]').addClass('done');
												}
											}
										}
									});
									isJumpTask = false;
									return false;
								}else{
									$('.task-listing').hide();
									$('.task-friends-section').hide();
									$('.task-form').show();
								}
							}
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
					// formdata['habit_notes'] = $('#SYG_notes').val();
					formdata['habit_notes'] = [];
					$('input[name="SYG_notes"]:checked').each(function(){
						formdata['habit_notes'].push($(this).val());
						if($(this).val() == 'Other'){
							formdata['gb_habit_note_other'] = $('#habit_note_other').val();
						}
					});
					formdata['habit_milestone']=$('select[name="milestone_value"]').val();
					formdata['habit_seen'] = $("input[name=syg2_see_habit]").val();
					formdata['habit_reminders'] = $("input[name='habits-send-mail']:checked").val(); 
					if(formdata['habit_reminders'] == "daily"){
						formdata['Send_mail_habits_time'] = $('#daily_time_habits').val();
					}else if(formdata['habit_reminders'] == "weekly"){
						formdata['Send_mail_habits_time'] = $('#weekly_day_habits').val();
					}else if(formdata['habit_reminders'] == "monthly"){
						formdata['Send_mail_habits_time'] = $('#month_date_habits').val();
					}
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

					formdata['task_reminders'] = $("input[name='creattask-send-mail']:checked").val(); 
					if(formdata['task_reminders'] == "daily"){
						formdata['Send_mail_task_time'] = $('#daily_time_task').val();
					} 
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
						var task_index = $('#task-index').val();
						var new_task_index = parseInt(task_index) + 1;
						var no_of_index = $('#tasklist').find('tr').length;
						if(task_index != "na"){
							$('#tasklist tr').find('.task-edit').each(function(key,obj) {
								if(new_task_index == key)
								{
									if(no_of_index  == new_task_index){
										$('.task-form').hide();
										$('.task-listing').show();
										$('.cancel_htask_btn').addClass('hidden');
									}else{
										$('#task-index').val(new_task_index);
										$(this).trigger('click');
									}
								}else{
									if(no_of_index  == new_task_index)
									{
										if($('a[href="#step-4"]').hasClass('done'))
										{
										}
										else{
											$('.task-listing').show();
											$('.task-friends-section').hide();
											$('.task-form').hide();
											$('.cancel_task_btn').addClass('hidden');
											return false;
										}
									}
								}
							});
							e=1;
							isJumpTask = "true";
							return false;
						}else{
							$('.task-listing').show();
							$('.task-friends-section').hide();
							$('.task-form').hide();
							$('.cancel_task_btn').addClass('hidden');
							isJumpTask = "true";
							return false;
						}
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
		
		/**
		 * Save Step Form Data
		 * @param {*} formdata 
		 */
		$('body').on('click','.saveStep',function(){
			var formStep = $(this).data('step');
			var formdata = {};
			var viewStep = $('#viewport-'+formStep);
			if(formStep == '1'){
				formdata['goal_notes'] = $('#goal_notes').val();
				var goalDueDate = $('#datepicker_SYG').val();
				formdata['name'] = $('#name_goal').val();

				formdata['describe_achieve'] = $("#description").val();
				formdata['goal_year'] = $("input[name=goal_year]").val();
				formdata['change_life'] = prepareCheckBoxSelect($('#form').find('input[name="change_life"]').val());
				$.inArray('Other',formdata['change_life'])
				{	 
					formdata['gb_change_life_reason_other'] = $('#form').find('textarea[name="gb_change_life_reason_other"]').val();
				}
				formdata['accomplish'] = $('#accomplish').val();
				formdata['failDescription'] = $('#fail-description').val();
				formdata['gb_relevant_goal'] = $('#relevant_goal').val();
				formdata['gb_relevant_goal_event'] = $('#gb_relevant_goal_event').val();
				formdata['image'] = $('input[name="prePhotoName"]').val();
				formdata['template'] = $('#goal-template').val();
				if(goalDueDate!=''){
					formdata['due_date'] =  moment(goalDueDate, 'ddd, D MMM YYYY').format("YYYY-MM-DD");
				}
				formdata['goal_seen'] = $("input[name=goal_seen]").val();
				formdata['send_msg_type'] = $("input[name='goal-Send-mail']:checked").val(); 
				if(formdata['send_msg_type'] == "daily"){
					formdata['Send_mail_time'] = $('#daily_time_goal').val();
				}else if(formdata['send_msg_type'] == "weekly"){
					formdata['Send_mail_time'] = $('#weekly_day_goal').val();
				}else if(formdata['send_msg_type'] == "monthly"){
					formdata['Send_mail_time'] = $('#month_date_goal').val();
				}
				formdata['form_no'] = formStep;
				formdata['update_status'] = $('#update-record').val();
				formdata['save_as_draft'] = true;
				if(formdata['update_status'] == 'update-yes') {
					formdata['last_insert_id'] = $('#last-insert-id').val();
					savegoal(formdata);
				}
				
				if(formdata['name'] != '' && formdata['change_life'].length > 0 && formdata['update_status'] != 'update-yes' && formdata['due_date'] != undefined ){
					savegoal(formdata);
				}
			}else if(formStep == '2'){
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

				formdata['gb_milestones_reminder'] = $("input[name='milestones-Send-mail']:checked").val(); 
				if(formdata['gb_milestones_reminder'] == "daily"){
					formdata['Send_mail_milestones_time'] = $('#daily_time_milestones').val();
				}else if(formdata['gb_milestones_reminder'] == "weekly"){
					formdata['Send_mail_milestones_time'] = $('#weekly_day_milestones').val();
				}else if(formdata['gb_milestones_reminder'] == "monthly"){
					formdata['Send_mail_milestones_time'] = $('#month_date_milestones').val();
				}
				formdata['form_no'] = formStep;
				formdata['last_insert_id'] = $('#last-insert-id').val();
				formdata['goal_id_mile']=$('#goal_milestones_id').val();
				formdata['milestones_id'] = $('#milestones_id').val();
				formdata['save_as_draft'] = true;
				if( formdata['milestones_id'] == '' || formdata['milestones_id'] == undefined){
					if(totalNameLength == totalDateLength){
						savegoal(formdata);
					} 
					else {
						console.log('error');
					}
				}else{
					savegoal(formdata);
				}
			}else if(formStep == '3'){
				var habitId = $('#habit-id').val();
				formdata['habit_name'] = $('#SYG_habits').val();
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
				// formdata['habit_notes'] = $('#SYG_notes').val();
				formdata['habit_notes'] = [];
				$('input[name="SYG_notes"]:checked').each(function(){
					formdata['habit_notes'].push($(this).val());
					if($(this).val() == 'Other'){
						formdata['gb_habit_note_other'] = $('#habit_note_other').val();
					}
				});
				formdata['habit_milestone']=$('select[name="milestone_value"]').val();
				formdata['form_no'] = formStep;
				formdata['last_insert_id'] = $('#last-insert-id').val();
				viewStep.find('.who-can-view').each(function(){
					if($(this).hasClass('active'))
					{
						formdata['habit_seen'] = $(this).data('value');
					}
				})
				formdata['habit_reminders'] = $("input[name='habits-send-mail']:checked").val(); 
				if(formdata['habit_reminders'] == "daily"){
					formdata['Send_mail_habits_time'] = $('#daily_time_habits').val();
				}else if(formdata['habit_reminders'] == "weekly"){
					formdata['Send_mail_habits_time'] = $('#weekly_day_habits').val();
				}else if(formdata['habit_reminders'] == "monthly"){
					formdata['Send_mail_habits_time'] = $('#month_date_habits').val();
				}
				formdata['save_as_draft'] = true;
				if( habitId == '' || habitId == undefined){
					if(formdata['habit_name'] != ''){
						savegoal(formdata);
					}
				} 
				else {
					formdata['habit_id'] = habitId;
					savegoal(formdata);
				}
			}else if(formStep == '4'){
				formdata['task_name'] = $('#SYG3_task').val();
				formdata['note'] = $('#note').val();
				
				formdata['task_priority'] = $("#SYG3_priority").val();
				formdata['task_habit_id'] = $('select[name="habit_value"]').val();
				formdata['task_seen'] = $("input[name=SYG3_see_task]").val();

				formdata['task_reminders'] = $("input[name='creattask-send-mail']:checked").val(); 
				if(formdata['task_reminders'] == "daily"){
					formdata['Send_mail_task_time'] = $('#daily_time_task').val();
				} 
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
				formdata['form_no'] = formStep;
				formdata['last_insert_id'] = $('#last-insert-id').val();
				var taskId = $('#task-id').val();
				formdata['save_as_draft'] = true;
				if( taskId == '' || taskId == undefined){
					if(formdata['task_name'] != '' && formdata['task_priority'] != '' && formdata['task_date'] != ''){
						savegoal(formdata);
					}
				} 
				else {
					formdata['task_id'] = taskId;
					savegoal(formdata);
				}
			}
		});

		$(document).on('keyup','#goal_notes',function(){
			$(".show_task-section").children('p').html($(this).val());
		})


		/* Save Goal form */
		function savegoal(formdata){
			$.ajax({
				url: 	public_url+'goal-buddy/savegoal',
				type: 	'POST',
				data: 	{'formData':formdata},
				success: function(data){
					var data = JSON.parse(data);
					if(data.saveAsDraft == "true"){
						if(data.goalBuddy != undefined){
							$('#update-record').val('update-yes');
							$('#last-insert-id').val(data.goalBuddy);
						}
						if(data.form == 'milestones-list'){
							$('#milestones_id').val(data.milestonesId);
						}
						if(data.form == 'habit-list' || (data.habit_list != null && data.habit_list != [])){
							$('#habit-id').val(data.habitId);
						}
						if(data.form == 'task-list' || (data.task_list != null && data.tasl_list != [])){
							$('#task-id').val(data.taskId);
						}
						// swal('Data has been saved successfully');
					}else{
						var url = window.location.href;
						var result= url.split('/');
						var Param = result[result.length-3];
						var Param1 = result[result.length-2];
	
						if(data.form == 'milestones-list'){
							var milestoneLabel="";
							var milestones = $('.mile_section .dd-item');
	
							isJump = "false";
							$.each(milestones, function(k,obj) {
								milestoneLabel += '<a class="Step-your-goal4 milestone-text" data ='+$(obj).find('.edit-milestone-info').data('milestones-id')+'><li>'+ $(obj).find('.milestones-name').val() +'</li></a>';
								milestoneLabel += '<p style="margin-left:18px;">'+$('input[name=gb_milestones_seen]:checked').val()+'</p>';
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
							$('#habit-id').val(data.habitId);
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
							$('#task-id').val(data.taskId);
							
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
								if(data.form == 'preview'){
									$('#formSubmitted').val('1');
								}
								/*isJump = "false";
								isJumpMilestone = "false";*/
								var goalData = data.goalInfo;
								
								if(goalData != undefined)
								showGoalInfo(goalData);
								
								// $('.milestone-div-label').hide();
								$('.task-milestone-label').hide();
								$('#update-record').val('update-yes');
								$('#last-insert-id').val(data.goalBuddy);
								$('#goalDueDate').val(goalData.gb_due_date);
                            
								initFirstMilestoneDatePicker(goalData.gb_due_date);

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
		
		function getHabit(hid){
			$('#waitingShield').removeClass('hidden');
			$.ajax({
				url: public_url+'goal-buddy/showhabit',
				type: 'POST',
				data: {'habitId':hid},
				async: false,
				success: function(data){
					var data = JSON.parse(data);
					if(data.status == 'true'){
						$('input[name="SYG_habit_recurrence"]').prop('checked', false);
						$('#SYG_notes').val('');
						$('#gb_habit_select_all_milestone').prop('checked', false);
						$('input[name="habit"]').prop('checked', false);
						$('input[name="syg2_see_habit"]').prop('checked', false);
						if(data.goalBuddy.gb_habit_seen == 'Selected friends'){
							console.log(data.goalBuddy.gb_habit_selective_friends)
							$("#syg2_selective_friends").val(data.goalBuddy.gb_habit_selective_friends);
							$("#syg2_selective_friends").parent().removeClass('hidden');
							if($("#all-my-friends").val() != undefined && $("#all-my-friends").val() != ''){
								$("#syg2_selective_friends").amsifySuggestags("refresh");
								var my_friends = JSON.parse($("#all-my-friends").val());
								var options = [];
									
								for(var aaa =0; aaa < my_friends.length; aaa++ ){
									options[aaa] = {'tag':my_friends[aaa].name,'value':my_friends[aaa].id}
								}
							
								$('.autocomplete').amsifySuggestags({
									type :'bootstrap',
									suggestions: options,
									whiteList:true,
								});
							}
							
						}else{
							$("#syg2_selective_friends").attr('value','');
							$("#syg2_selective_friends").parent().addClass('hidden');
						}
						$('#newHabitForm input[type="radio"]').each(function(){
							$(this).prop('checked',false);
						})
						$('.goalEventRepeatWeekdays').each(function(){
							$(this).prop('checked',false);
						})
						$('#newHabitForm .showTimeBox').hide(); 
						$('#newHabitForm .showDayBox').hide(); 
						$('#newHabitForm .showMonthBox').hide();     
						
						$('input[name="habits-send-mail"]').prop('checked', false);
						$('.cancel_habbit_btn').removeClass('hidden');

						// Set default all milestones selected unchecked
						$('#gb_habit_select_all_milestone').attr('checked', false);

						// Set default habit seen to everyone
						$('#viewport-3').find('#wrapper_syg2_see_habit0').find('li[data-value="everyone"]').trigger('click').trigger('change');
						// $('input[name="syg2_see_habit"]').val('everyone');

						// Set default habit reminder
						$('#viewport-3').find('#wrapper_syg2_send_msg0').find('li[data-value="only_if_I_am_late"]').trigger('click').trigger('change');
						$('input[name="syg2_send_msg"]').val('only_if_I_am_late');

						// Set default habit recurrence to daily
						// $("input[name=SYG_habit_recurrence]").val('daily');
						$('#viewport-3').find('.SYG_habit_recurrence_wrapper .click-box').find('li[data-recurrence-type="daily"]').trigger('click').trigger('change');
							
						// Set default habit notes
						$('#SYG_notes').val('');

						// Set default habit id
						// $('#habit-id').val('');
						$('#goal-habit-id').val(data.goalBuddy.id);
						$('input[name="SYG_habit_recurrence"]').each(function(){
							if($(this).val() == data.goalBuddy.gb_habit_recurrence_type){
								$(this).prop('checked', true);
							}
							if(data.goalBuddy.gb_habit_recurrence_type == 'weekly'){
								$('.showDayBox').show();
								var recWeek = data.goalBuddy.gb_habit_recurrence_week.split(',');
								$('input[name="habitRecWeek"]').each(function(){
									if(jQuery.inArray($(this).val(), recWeek) !== -1){
										$(this).prop('checked',true);
									}
									

								})
							}else if(data.goalBuddy.gb_habit_recurrence_type == 'monthly'){
								$('.showMonthBox').show();   
								$('.month-date').val(data.goalBuddy.gb_habit_recurrence_month).selectpicker('refresh');
								
							}
							
						});
						$('input[name="habits-send-mail"]').each(function(){
							if($(this).val() == data.goalBuddy.gb_habit_reminder){
								$(this).prop('checked', true);

								
							}
							if($(this).val() == 'weekly'){
								$('#weekly_day_habits').val(data.goalBuddy.gb_habit_reminder_time).selectpicker('refresh');
							}else if($(this).val() == 'daily'){
								$('#daily_time_habits').val(data.goalBuddy.gb_habit_reminder_time).selectpicker('refresh');
							}else if($(this).val() == 'monthly'){
								$('#month_date_habits').val(data.goalBuddy.gb_habit_reminder_time).selectpicker('refresh');
							}
							
						});

						$('input[name="habits-send-epichq"]').each(function(){
							if($(this).val() == data.goalBuddy.gb_habit_reminder_epichq){
								$(this).prop('checked', true);
							}
						});

						$('input[name="syg2_see_habit"]').each(function(){
							if($(this).val() == data.goalBuddy.gb_habit_seen){
								$(this).prop('checked', true);
							}
							
						});
						if(data.goalBuddy.gb_habit_seen == 'Selected friends'){
							// console.log(data.goalBuddy.gb_habit_selective_friends)
							$("#syg2_selective_friends").attr('value',data.goalBuddy.gb_habit_selective_friends);
							if($("#all-my-friends").val() != undefined && $("#all-my-friends").val() != ''){
								$("#syg2_selective_friends").amsifySuggestags("refresh");
								var my_friends = JSON.parse($("#all-my-friends").val());
								var options = [];
									
								for(var aaa =0; aaa < my_friends.length; aaa++ ){
									options[aaa] = {'tag':my_friends[aaa].name,'value':my_friends[aaa].id}
								}
							
								$('.autocomplete').amsifySuggestags({
									type :'bootstrap',
									suggestions: options,
									whiteList:true,
								});
							}
							$("#syg2_selective_friends").parent().removeClass('hidden');
						}else{
							$("#syg2_selective_friends").attr('value','');
							$("#syg2_selective_friends").parent().addClass('hidden');
						}
						$('#SYG_notes').val('');
						
						
						
						}

						setTimeout(function() {
						
							
							if(data.milestonesTask != '') {
								var milestonesIds = data.goalBuddy.gb_milestones_id.split(',');
								showMIlestoneDd(milestonesIds);
								if(milestonesIds.length === parseInt($('#milestone_div option').length)) {
									
									$('#gb_habit_select_all_milestone').prop('checked', true);
								}else{

									$('#gb_habit_select_all_milestone').prop('checked', false);
								}

								if(milestonesIds == ""){
									$('#gb_habit_select_all_milestone').prop('checked', false);
								}
							} else
								$('.milestone-dropdown').hide(); 

					
							$('#habit-id').val(data.goalBuddy.id);
							$('#goal-habit-id').val(data.goalBuddy.id);
							$('#SYG_habits').val(data.goalBuddy.gb_habit_name);
							$('#SYG_habits').trigger('change');
							
							var habit_recurrence_type = data.goalBuddy.gb_habit_recurrence_type;
							
							// $("input[name=SYG_habit_recurrence]").val(habit_recurrence_type);
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
							// $('input[name="syg2_see_habit"]').val(habit_seen);
							
							// habit reminder
							var habit_reminder = data.goalBuddy.gb_habit_reminder;
							$('#viewport-3').find('#wrapper_syg2_send_msg0').find('li[data-value="'+habit_reminder+'"]').trigger('click').trigger('change');
							$('input[name="syg2_send_msg"]').val(habit_reminder);
							
						}, 2000);
						// $("#habit_form_validation").val(1);
						// $('input[name="SYG_habit_recurrence"]').val(data.goalBuddy.gb_habit_recurrence);
						console.log('hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh');
						 $('#SYG_notes').val(data.goalBuddy.gb_habit_notes);
						   /*  */
						//  console.log(data);
						 var habit_list = data.habitNoteList;
					    //  console.log(habit_list);
           /* outer for loop */
						for(var i = 0; i < habit_list.length; i++) { 
						
                            var hibit_name = data.goalBuddy.gb_habit_name;
							if(hibit_name == habit_list[i].gb_habit_name){
							    var habit_notes = habit_list[i].gb_habit_notes.split(/\r\n|\n|\r/);	
						        // var checked_habit_notes = data.goalBuddy.gb_habit_notes.split(',');
								var checked_habit_notes = data.goalBuddy.gb_habit_notes.split(/,|\r\n|\n|\r/);
								// console.log('habit_notes=== ', habit_notes,'checked_habit_notes==', checked_habit_notes );
						        html ='';
								$(".habit_notes").html('');
								$(".habit_notes").removeClass('outborder');
								var radio_val = '';
								var habit_note_other = null;
						/* for loop */  
						
						   for(var k = 0; k < habit_notes.length; k++) {
							   if(checked_habit_notes.length > 0){
								   for(var j = 0; j < checked_habit_notes.length; j++) {
									   radio_val = (checked_habit_notes[j] == habit_notes[k]) ? 'checked' : '';
									   if(radio_val == 'checked'){
											break;
										}
									 }
									 habit_note_other = data.goalBuddy.gb_habit_note_other;
								}
							   var habit_other = '';
							   if(habit_notes[k] == 'Other'){
								habit_other = 'habit_note_other';
								   if(habit_note_other != null){
									   html = '<textarea rows="7" class="form-control" id="habit_note_other" name="gb_habit_note_other">'+ habit_note_other +'</textarea>';
								   } else {
									   html = '<textarea rows="7" class="form-control hidden" id="habit_note_other" name="gb_habit_note_other"></textarea>';
								   }
							   }
							   
							   $(".habit_notes").append(
								   '<div class="form-group">\
								   <label class="container_check version_2">'+habit_notes[k]+'\
								   <input type="checkbox" class="'+habit_other+'" name="SYG_notes" required value="'+habit_notes[k]+'" '+radio_val+'>\
								   <span class="checkmark"></span>\
								   '+html+'\
								   </label>\
								</div>'
							   )
						   }
						   /* for loop */  
						}
					}
             /* outer for loop */
						//    $("#syg2_selective_friends").val('');
	   
						   /*  */


						$(".milestone_div_class option:selected").val(data.goalBuddy.gb_milestones_name);
						$("select#milestone_div").val(data.goalBuddy.gb_milestones_id).selectpicker("refresh");
						$('#goal-habit-id').val(data.goalBuddy.id);
			
						$('#last-insert-id').val(data.goalBuddy.goal_id);
						// $('#SYG_notes').val(data.goalBuddy.gb_habit_notes);
						setTimeout(function() {
							$('#waitingShield').addClass('hidden');
						},2000);
						
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
						$('.goal-with-habit').text(data.habitDetails.gb_habit_notes);
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
			$('#waitingShield').removeClass('hidden');
			$.ajax({
				url: public_url+'goal-buddy/showtask',
				type: 'POST',
				data: {'taskId':tid},
				success: function(data){
					var data = JSON.parse(data);
					console.log(data);
					if(data.status == 'true'){
						$('#task-id').val('');
						$('input[name="task_id"]').val(data.goalBuddy.id);
						// $('#task-id').val(data.goalBuddy.id);
						$('#task_id_new').val(data.goalBuddy.id);

						var task_habit = [];
						$('input[name="SYG_task_recurrence"]').prop('checked', false);
						$('#note').val('');
						// $('#gb_habit_select_all_milestone').trigger('click');
						$('input[name="Priority"]').prop('checked', false);
						$('input[name="creattask-send-mail"]').prop('checked', false);
						$('input[name="SYG3_see_task"]').prop('checked', false);
					
						$('input[name="associatedHabitWithTask"]').val('');
						$('input[name="goalTaskData"]').val(data.goalBuddy.id);
						$('.cancel_task_btn').removeClass('hidden');
						$('.taskNext input[type="radio"]').each(function(){
							$(this).prop('checked',false);
						})

						$('.taskEventRepeatWeekdays').each(function(){
							$(this).prop('checked',false);
							

						})
						$('.taskNext .showTimeBox').hide(); 
						$('.taskNext .showDayBox').hide(); 
						$('.taskNext .showMonthBox').hide(); 
						// Set default task priority
						$("#SYG3_priority").val('Low');
						$('#viewport-4').find('#gb_task_priority_wrapper').find('li[data-value="Low"]').trigger('click').trigger('change');

						// Set default task recurrence 
						// $("input[name=SYG3_see_task]").val('daily');
						$('#viewport-4').find('ul#gb_task_recurrence_type_wrapper').find('li[data-value="daily"]').trigger('click').trigger('change');
						$('#viewport-4').find('ul#gb_task_recurrence_type_wrapper').children('li').addClass('disabled_task_recurrence');

						// Set default task seen
						$('#viewport-4').find('#gb_task_seen_wrapper').find('li[data-value="everyone"]').trigger('click').trigger('change');
						// $("input[name=SYG3_see_task]").val('everyone');
						
						// Set default task reminder
						$('#viewport-4').find('#gb_task_reminder_wrapper').find('li[data-value="When_task_is_overdue"]').trigger('click').trigger('change');
						$("input[name=SYG3_send_msg]").val('When_task_is_overdue');

						// Set default task notes
						$('#note').val('');
						$('input[name="Priority"]').each(function(){
							if($(this).val() == data.goalBuddy.gb_task_priority){
								$(this).prop('checked',true);
							}
						})
						$('input[name="SYG3_see_task"]').each(function(){
							if($(this).val() == data.goalBuddy.gb_task_seen){
								$(this).prop('checked',true);
							}
						})
						if(data.goalBuddy.gb_task_seen == 'Selected friends'){
							// console.log(data.goalBuddy.gb_habit_selective_friends)
							$("#SYG3_selective_friends").attr('value',data.goalBuddy.gb_task_selective_friends);
							if($("#all-my-friends").val() != undefined && $("#all-my-friends").val() != ''){
								$("#SYG3_selective_friends").amsifySuggestags("refresh");
								var my_friends = JSON.parse($("#all-my-friends").val());
								var options = [];
									
								for(var aaa =0; aaa < my_friends.length; aaa++ ){
									options[aaa] = {'tag':my_friends[aaa].name,'value':my_friends[aaa].id}
								}
							
								$('.autocomplete').amsifySuggestags({
									type :'bootstrap',
									suggestions: options,
									whiteList:true,
								});
							}
							$("#SYG3_selective_friends").parent().removeClass('hidden');
						}else{
							$("#SYG3_selective_friends").attr('value','');
							$("#SYG3_selective_friends").parent().addClass('hidden');
						}
						$('input[name="SYG_task_recurrence"]').each(function(){
							if($(this).val() == data.goalBuddy.gb_task_recurrence_type){
								$(this).prop('checked',true);
							}
							if(data.goalBuddy.gb_task_recurrence_type == 'weekly'){
								var recWeek = data.goalBuddy.gb_task_recurrence_week.split(',');
								$('.taskEventRepeatWeekdays').each(function(){
									if(jQuery.inArray($(this).val(), recWeek) !== -1){
										$(this).prop('checked',true);
									}
									

								})

								$('.taskNext .showDayBox').show();
								$('.taskNext .showMonthBox').hide();
							}else if(data.goalBuddy.gb_task_recurrence_type == 'monthly'){
								$('.taskNext .showMonthBox').show();
								$('.taskNext .showDayBox').hide();
										$('#gb_task_recurrence_month').val(data.goalBuddy.gb_task_recurrence_month).selectpicker('refresh');
							}
						})

						$('input[name="creattask-send-mail"]').each(function(){
							if($(this).val() == data.goalBuddy.gb_task_reminder){
								$(this).prop('checked',true);
								
								
							}
							if($(this).val() == 'daily')
							{
								$('#daily_time_task').val(data.goalBuddy.gb_task_reminder_time).selectpicker('refresh');
								$('.taskNext .showTimeBox').show();
							}else{
								$('.taskNext .showTimeBox').hide(); 
							}
						})

						$('input[name="creattask-send-epichq"]').each(function(){
							if($(this).val() == data.goalBuddy.gb_task_reminder_epichq){
								$(this).prop('checked',true);								
							}
						})

					var goalTempleteId = $('input[name="template"]:checked').data('id');
					console.log('goalTempleteId', goalTempleteId);	
                     if(goalTempleteId == undefined){

						if(data.habitTask != ''){
							$('.task-habit-div').show();
							var optionValue = '<select id="habit_div" name="habit_value" class="form-control  taskhabit_div_class" required=""><option value="">-- Select --</option>';
							
							$.each(data.habitTask,function(key, value) {
								if(value.id==data.goalBuddy.gb_habit_id) {
									task_habit = value;
									let taskHabiStringify = JSON.stringify(value);
									$('#viewport-4').find('input[name="associatedHabitWithTask"]').val(taskHabiStringify);
									 optionValue += '<option value="'+value.id+'" >'+value.gb_habit_name+'</option>';
									//  optionValue += '<option value="'+value.id+'" selected>'+value.gb_habit_name+'</option>';
							    } else{
									optionValue += '<option value="'+value.id+'">'+value.gb_habit_name+'</option>';
								}
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

					 } else {
						$('.task-habit-div').show();
						var optionValue = '';
						//'<select id="habit_div" name="habit_value" class="form-control  taskhabit_div_class" required=""><option value="">-- Select --</option>';
						
						$.each(data.habitTask,function(key, value) {
							if(value.id==data.goalBuddy.gb_habit_id) {

								optionValue = '<input data-toggle="tooltip" title="" type="text" class="form-control taskhabit_div_class" id="habit_div" value="'+value.gb_habit_name+'" name="habit_value" disabled>';
								//'<textarea data-toggle="tooltip" title="" data-autoresize rows="3" id="SYG_notes" name="SYG_notes" ng-model="SYG_notes" placeholder="" class="form-control" disabled>'+value.gb_habit_name+'</textarea>';
								
								/*task_habit = value;
								let taskHabiStringify = JSON.stringify(value);
								$('#viewport-4').find('input[name="associatedHabitWithTask"]').val(taskHabiStringify);
								optionValue += '<option value="'+value.id+'" selected>'+value.gb_habit_name+'</option>';*/
							} /*else
							optionValue += '<option value="'+value.id+'">'+value.gb_habit_name+'</option>';*/
						});
						//optionValue = '<textarea data-toggle="tooltip" title="" data-autoresize rows="3" id="SYG_notes" name="SYG_notes" ng-model="SYG_notes" placeholder="" class="form-control" disabled>Physical Activity, Healthy Balanced Nutrition, Stress Management</textarea>';
						//'<label><a href="javascript:void(0)" class=""data-message="<b>Physical Activity</b><br/><b>Healthy Balanced Nutrition</b><br/><b>Stress Management</b>" data-message1="Tooltip not provided on the documents"><i class="fa fa-question-circle question-mark"></i></a></label>';
						//+= '</select>';
						$('.task-habit-dropdown').html(optionValue);
						//initSelectpicker($('.task-habit-dropdown select'));
						
						//$('.taskhabit_div_class').selectpicker('refresh');
						optionValue = '';
						 
					 }
						

						// var goalTempleteId = $('input[name="template"]:checked').data('id');
				       
	        	  /* code */
						// if(data.habitTask != ''){
						// 	$('.task-habit-div').show();
						// 	var optionValue = '';
						// 	//'<select id="habit_div" name="habit_value" class="form-control  taskhabit_div_class" required=""><option value="">-- Select --</option>';
							
						// 	$.each(data.habitTask,function(key, value) {
						// 		if(value.id==data.goalBuddy.gb_habit_id) {

						// 			optionValue = '<input data-toggle="tooltip" title="" type="text" class="form-control taskhabit_div_class" id="habit_div" value="'+value.gb_habit_name+'" name="habit_value" disabled>';
						// 			//'<textarea data-toggle="tooltip" title="" data-autoresize rows="3" id="SYG_notes" name="SYG_notes" ng-model="SYG_notes" placeholder="" class="form-control" disabled>'+value.gb_habit_name+'</textarea>';
									
						// 			/*task_habit = value;
						// 			let taskHabiStringify = JSON.stringify(value);
						// 			$('#viewport-4').find('input[name="associatedHabitWithTask"]').val(taskHabiStringify);
						// 			optionValue += '<option value="'+value.id+'" selected>'+value.gb_habit_name+'</option>';*/
						// 		} /*else
						// 		optionValue += '<option value="'+value.id+'">'+value.gb_habit_name+'</option>';*/
						// 	});
						// 	//optionValue = '<textarea data-toggle="tooltip" title="" data-autoresize rows="3" id="SYG_notes" name="SYG_notes" ng-model="SYG_notes" placeholder="" class="form-control" disabled>Physical Activity, Healthy Balanced Nutrition, Stress Management</textarea>';
						// 	//'<label><a href="javascript:void(0)" class=""data-message="<b>Physical Activity</b><br/><b>Healthy Balanced Nutrition</b><br/><b>Stress Management</b>" data-message1="Tooltip not provided on the documents"><i class="fa fa-question-circle question-mark"></i></a></label>';
						// 	//+= '</select>';
						// 	$('.task-habit-dropdown').html(optionValue);
						// 	//initSelectpicker($('.task-habit-dropdown select'));
							
						// 	//$('.taskhabit_div_class').selectpicker('refresh');
						// 	optionValue = '';
						// }
						// else{ 
						// 	$('.task-habit-div').hide();   
						// }
						/* code */
                        if(data.goalBuddy.gb_task_recurrence_type == "" || data.goalBuddy.gb_task_recurrence_type == undefined){
							if(data.goalBuddy.gb_task_name == "Resistance Training" || data.goalBuddy.gb_task_name == "Recovery Routine" || data.goalBuddy.gb_task_name == "Cardiovascular Training" || data.goalBuddy.gb_task_name == "Recovery Routines"){
								var habitData = data.habitTask;
								$.each(habitData,function(key,value){

									//remove old selected data
		                            /*$('.taskEventRepeatWeekdays').each(function(){
		                                $(this).prop('checked',false);
		                            });
		                            $('input[name="SYG_task_recurrence"][value="weekly"]').prop('checked', false);
		                            $("#hbt_rec_tsk").val('none');*/
		                            //remove old selected data - end

									var recWeek = value.gb_habit_recurrence_week.split(',');
									if(key == 0 && value.gb_habit_recurrence_type == 'weekly'){
										$("#hbt_rec_tsk").val('none');
										$('.taskEventRepeatWeekdays').each(function(){
											if(jQuery.inArray($(this).val(), recWeek) !== -1){
												$(this).prop('checked',true);
											}	
										})
										$('.taskNext .showDayBox').show(); 
										$('.taskNext .showMonthBox').hide();
										
										$('input[name="SYG_task_recurrence"][value="weekly"]').prop('checked', true);
										$('#habitRecValue').val(recWeek);
										$('input[name="SYG_task_recurrence"][value="daily"]').prop('disabled',true);
										//$('input[name="SYG_task_recurrence"][value="monthly"]').prop('disabled',true);
									}else if(key == 0 && value.gb_habit_recurrence_type == 'daily'){
										$("#hbt_rec_tsk").val('daily');
		                                $('input[name="SYG_task_recurrence"][value="weekly"]').trigger('click');
		                                //prop('checked', true);
		                                $('.taskEventRepeatWeekdays').each(function(){
		                                        $(this).prop('checked',true);
		                                });
		                                $('.taskNext .showDayBox').show(); 
										/*$('input[name="SYG_task_recurrence"][value="daily"]').prop('checked', true);
										$('input[name="SYG_task_recurrence"][value="weekly"]').prop('disabled',true);
										$('input[name="SYG_task_recurrence"][value="monthly"]').prop('disabled',true);
										$('.taskNext .showDayBox').hide(); 
										$('.taskNext .showMonthBox').hide();*/
									}else if(key == 0 && value.gb_habit_recurrence_type == 'monthly'){
										$('input[name="SYG_task_recurrence"][value="monthly"]').prop('checked', true);
										$('.taskNext .showMonthBox').show();
										$('#gb_task_recurrence_month').val(value.gb_habit_recurrence_month).selectpicker('refresh');
										$('input[name="SYG_task_recurrence"][value="daily"]').prop('disabled',true);
										$('input[name="SYG_task_recurrence"][value="weekly"]').prop('disabled',true);
										$('.taskNext .showDayBox').hide(); 
									}
								});
								
							}else if(data.goalBuddy.gb_task_name == "Portion Distortion" || data.goalBuddy.gb_task_name == "Food preparation" || data.goalBuddy.gb_task_name == "Trace and Replace"){
								var habitData = data.habitTask;
								$.each(habitData,function(key,value){
									var recWeek = value.gb_habit_recurrence_week.split(',');
									if(key == 1 && value.gb_habit_recurrence_type == 'weekly'){
										$("#hbt_rec_tsk").val('none');
										$('.taskEventRepeatWeekdays').each(function(){
											if(jQuery.inArray($(this).val(), recWeek) !== -1){
												$(this).prop('checked',true);
											}	
										})
										$('.taskNext .showMonthBox').hide();
										$('.taskNext .showDayBox').show(); 
										$('input[name="SYG_task_recurrence"][value="weekly"]').prop('checked', true);
										$('#habitRecValue').val(recWeek);
										$('input[name="SYG_task_recurrence"][value="daily"]').prop('disabled',true);
										//$('input[name="SYG_task_recurrence"][value="monthly"]').prop('disabled',true);
									}else if(key == 1 && value.gb_habit_recurrence_type == 'daily'){
										$("#hbt_rec_tsk").val('daily');
		                                $('input[name="SYG_task_recurrence"][value="weekly"]').trigger('click');
		                                //prop('checked', true);
		                                $('.taskEventRepeatWeekdays').each(function(){
		                                        $(this).prop('checked',true);
		                                });
		                                $('.taskNext .showDayBox').show();
										/*$('input[name="SYG_task_recurrence"][value="daily"]').prop('checked', true);
										$('input[name="SYG_task_recurrence"][value="monthly"]').prop('disabled',true);
										$('input[name="SYG_task_recurrence"][value="weekly"]').prop('disabled',true);
										$('.taskNext .showDayBox').hide(); 
										$('.taskNext .showMonthBox').hide();*/
									}else if(key == 1 && value.gb_habit_recurrence_type == 'monthly'){
										$('input[name="SYG_task_recurrence"][value="monthly"]').prop('checked', true);
										$('.taskNext .showMonthBox').show();
										$('input[name="SYG_task_recurrence"][value="daily"]').prop('disabled',true);
										$('input[name="SYG_task_recurrence"][value="weekly"]').prop('disabled',true);
										$('#gb_task_recurrence_month').val(value.gb_habit_recurrence_month).selectpicker('refresh');
										$('.taskNext .showDayBox').hide(); 
									}
								});
							}else if(data.goalBuddy.gb_task_name == "Limit blue light" || data.goalBuddy.gb_task_name == "Sleep" || data.goalBuddy.gb_task_name == "Time Management"){
								var habitData = data.habitTask;
								$('.taskEventRepeatWeekdays').each(function(){
									$(this).prop('checked',false);
								})
								$.each(habitData,function(key,value){
									var recWeek = value.gb_habit_recurrence_week.split(',');
									if(key == 2 && value.gb_habit_recurrence_type == 'weekly'){
										$("#hbt_rec_tsk").val('none');
										$('.taskEventRepeatWeekdays').each(function(){
											if(jQuery.inArray($(this).val(), recWeek) !== -1){
												$(this).prop('checked',true);
											}	
										})
										$('.taskNext .showMonthBox').hide();
										$('.taskNext .showDayBox').show(); 
										$('input[name="SYG_task_recurrence"][value="weekly"]').prop('checked', true);
										$('#habitRecValue').val(recWeek);
										$('input[name="SYG_task_recurrence"][value="daily"]').prop('disabled',true);
										//$('input[name="SYG_task_recurrence"][value="monthly"]').prop('disabled',true);
									}else if(key == 2 && value.gb_habit_recurrence_type == 'daily'){
										
										$("#hbt_rec_tsk").val('daily');
		                                $('input[name="SYG_task_recurrence"][value="weekly"]').trigger('click');
		                                //prop('checked', true);
		                                $('.taskEventRepeatWeekdays').each(function(){
		                                        $(this).prop('checked',true);
		                                });
		                                $('.taskNext .showDayBox').show();

										/*$('input[name="SYG_task_recurrence"][value="daily"]').prop('checked', true);
										$('input[name="SYG_task_recurrence"][value="monthly"]').prop('disabled',true);
										$('input[name="SYG_task_recurrence"][value="weekly"]').prop('disabled',true);
										$('.taskNext .showDayBox').hide(); 
										$('.taskNext .showMonthBox').hide();*/
										
									}else if(key == 2 && value.gb_habit_recurrence_type == 'monthly'){
										$('input[name="SYG_task_recurrence"][value="monthly"]').prop('checked', true);
										$('.taskNext .showMonthBox').show();
										$('input[name="SYG_task_recurrence"][value="daily"]').prop('disabled',true);
										$('input[name="SYG_task_recurrence"][value="weekly"]').prop('disabled',true);
										$('#gb_task_recurrence_month').val(value.gb_habit_recurrence_month).selectpicker('refresh');
										$('.taskNext .showDayBox').hide(); 
									}
								});
							}
						}
	
						// $('#task-id').val(data.goalBuddy.id);
						$('#SYG3_task').val(data.goalBuddy.gb_task_name);
						$('#SYG3-time').val(data.goalBuddy.gb_task_time);
						$("#SYG3_priority").val(data.goalBuddy.gb_task_priority);
						// $("input[name=SYG3_see_task]").val(data.goalBuddy.gb_task_seen);
						$("input[name=SYG3_send_msg]").val(data.goalBuddy.gb_task_reminder);
						// $('select#habit_div').attr('disabled', true);
						$('select#habit_div').val(data.goalBuddy.gb_habit_id).selectpicker("refresh");
						$('#note').val(data.goalBuddy.gb_task_note);
						// alert('hiii');
						console.log('pinki', data.goalBuddy.gb_task_note.length);
						$('#note').on( 'keydown', function (e){
							$(this).css('height', 'auto' );
							$(this).height( this.scrollHeight );
						});
						$('#note').keydown();
						//    var note_length  = data.goalBuddy.gb_task_not.split(/\r\n|\n|\r/);
						//    console.log('hiihihj=====',note_length.length);
                        // $("#note").animate({ scrollTop: $('#note').prop("scrollHeight")}, 1000);
						
						var task_priority = data.goalBuddy.gb_task_priority;
						var task_recurrence_type = data.goalBuddy.gb_task_recurrence_type;
						var task_recurrence_week = data.goalBuddy.gb_task_recurrence_week;
						var task_recurrence_month = data.goalBuddy.gb_task_recurrence_month;
						var task_seen = data.goalBuddy.gb_task_seen;
						var task_reminder = data.goalBuddy.gb_task_reminder;
						
						task_recurrence_type = data.goalBuddy.gb_task_recurrence_type == '' ? task_habit.gb_habit_recurrence_type : data.goalBuddy.gb_task_recurrence_type;
						task_recurrence_week = data.goalBuddy.gb_task_recurrence_type == '' ? task_habit.gb_habit_recurrence_week : data.goalBuddy.gb_task_recurrence_week;
						task_recurrence_month = data.goalBuddy.gb_task_recurrence_type == '' ? task_habit.gb_habit_recurrence_month : data.goalBuddy.gb_task_recurrence_month;
		
						// setTimeout(function() {
						// 	$('#SYG3_task').trigger('change');
							
						// 	// task_priority
						// 	$('#viewport-4').find('#gb_task_priority_wrapper').find('li[data-value="'+task_priority+'"]').trigger('click').trigger('change');
							
						// 	// start: task_recurrence_type
						// 	if(task_recurrence_type === 'daily' || task_recurrence_type === 'weekly') {
						// 		$('#viewport-4').find('ul#gb_task_recurrence_type_wrapper').find('li[data-value="weekly"]').trigger('click').trigger('change');
                        //         $('#SYG_task_recurrence1').prop('checked', true);
						// 	} else {
						// 		$('#viewport-4').find('ul#gb_task_recurrence_type_wrapper').find('li[data-value="'+task_recurrence_type+'"]').trigger('click').trigger('change');
                        //         $('#SYG_task_recurrence2').prop('checked', true);
						// 	}
							
						// 	$('#viewport-4').find('ul#gb_task_recurrence_type_wrapper').children('li').addClass('disabled_task_recurrence');

						// 	if(task_recurrence_type === 'weekly' || task_recurrence_type === 'daily') {
						// 		var checkBoxLi = $('#viewport-4').find('#gb_task_recurrence_weeks');
								
						// 		var days = '';
						// 		if(task_recurrence_type === 'weekly') {
						// 			days = task_recurrence_week.split(',');
						// 		} else if(task_recurrence_type === 'daily') {
						// 			weekData = 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday';
                        //             days = weekData.split(',');
						// 		} 
						// 		// console.log(days);
						// 		checkBoxLi.find('div.prefTrainSlot').each(function(index, value) {
						// 			let dayValue = $(this).find('input.taskEventRepeatWeekdays').val();
						// 			if($.inArray(dayValue, days) == -1) {
						// 				$(this).addClass('inactive');
						// 				$(this).removeClass('active');
						// 				$(this).find('input.taskEventRepeatWeekdays').attr("checked", false);
						// 				$(this).addClass('taskEventRepeatWeekdayNotInHabit');						
						// 			} else {
						// 				$(this).addClass('active');
						// 				$(this).removeClass('inactive');
						// 				$(this).find('input.taskEventRepeatWeekdays').attr("checked", true);
						// 				$(this).removeClass('taskEventRepeatWeekdayNotInHabit');
						// 			}
						// 		});
						// 	}
							
						// 	if(task_recurrence_type === 'monthly' || (task_recurrence_type == '' && task_habit.gb_habit_recurrence_type === 'monthly')) {
						// 		$('#viewport-4').find('#gb_task_recurrence_month').val(task_recurrence_month).trigger('change');
						// 	}
						// 	// end: task_recurrence_type
							
						// 	// task_seen
						// 	$('#viewport-4').find('#gb_task_seen_wrapper').find('li[data-value="'+task_seen+'"]').trigger('click').trigger('change');
							
						// 	// task_reminder
						// 	$('#viewport-4').find('#gb_task_reminder_wrapper').find('li[data-value="'+task_reminder+'"]').trigger('click').trigger('change');
							
						// 	// jQuery('html, body').stop().animate({
						// 	// 	scrollTop: $('#viewport-4').find('li[data-index=0]').offset().top - 100
						// 	// }, 'slow');
						// 		$('select#habit_div').attr('disabled', true);
						// $('select#habit_div').val(data.goalBuddy.gb_habit_id).selectpicker("refresh");
							
						// }, 2000);
						// Add this one for scroll issue
						// jQuery('html, body').stop().animate({
						// scrollTop: $('#viewport-4').find('li[data-index=0]').offset().top - 100
						// }, 'slow');
						// end 
					}
					setTimeout(function() {
						$('#waitingShield').addClass('hidden');
					},2000);
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
				formdata['send_msg_type'] = $("input[name='goal-Send-mail']:checked").val(); 
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
				formdata['gb_milestones_reminder'] = $("input[name='milestones-Send-mail']:checked").val(); 
				if(formdata['gb_milestones_reminder'] == "daily"){
					formdata['Send_mail_milestones_time'] = $('#daily_time_milestones').val();
				}else if(formdata['gb_milestones_reminder'] == "weekly"){
					formdata['Send_mail_milestones_time'] = $('#weekly_day_milestones').val();
				}else if(formdata['gb_milestones_reminder'] == "monthly"){
					formdata['Send_mail_milestones_time'] = $('#month_date_milestones').val();
				}
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

				// formdata['habit_notes'] = $('#SYG_notes').val();
				formdata['habit_notes'] = [];
				$('input[name="SYG_notes"]:checked').each(function(){
					formdata['habit_notes'].push($(this).val());
					if($(this).val() == 'Other'){
						formdata['gb_habit_note_other'] = $('#habit_note_other').val();
					}
				});
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
				// $('.send-reminders').each(function(){
				// 	if($(this).hasClass('active'))
				// 	{
				// 		formdata['habit_reminders'] = $(this).data('value');
				// 	}
				// })
				// formdata['habit_seen'] = $("input[name=syg2_see_habit]").val();
				// formdata['habit_reminders'] = $("input[name=syg2_send_msg]").val();

				formdata['habit_reminders'] = $("input[name='habits-send-mail']:checked").val(); 
					if(formdata['habit_reminders'] == "daily"){
						formdata['Send_mail_habits_time'] = $('#daily_time_habits').val();
					}else if(formdata['habit_reminders'] == "weekly"){
						formdata['Send_mail_habits_time'] = $('#weekly_day_habits').val();
					}else if(formdata['habit_reminders'] == "monthly"){
						formdata['Send_mail_habits_time'] = $('#month_date_habits').val();
				}
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
				formdata['task_reminders'] = $("input[name='creattask-send-mail']:checked").val(); 
					if(formdata['task_reminders'] == "daily"){
						formdata['Send_mail_task_time'] = $('#daily_time_task').val();
					} 
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
			// $.ajax({
			// 	url: 	public_url+'goal-buddy/savegoal',
			// 	type: 	'POST',
			// 	data: 	{'formData':formdata},
			// 	success: function(data){
					
			// 		data = JSON.parse(data);
			// 		if(data.form == 'milestones-list'){
			// 			$('#viewport-2').find('#milestones_id').val(data.milestoneId);


			// 		} else if(data.form == 'habit-list'){
			// 			//console.log(data);
			// 			$('#habit-id').val(data.habitId);
			// 			$('#goal-habit-id').val(data.habitId);
						
						
			// 		} else if(data.form == 'task-list'){
			// 			// $('#task-id').val(data.taskId);
			// 			$('#viewport-4').find('input[name="goalTaskData"]').val(data.taskId);
						
			// 		} else if(data.status == "success"){
			// 			if(data.goalBuddy != ''){
			// 				//console.log(data.goalBuddy);
			// 				$('#update-record').val('update-yes');
							
			// 				$('#last-insert-id').val(data.goalBuddy);
			// 			}
			// 		}
			// 	}
			// });
		}
		
		$('.editForm').on('click',function(){
			 var currentStep = $('#wizard-ul .activeFormStep').data('value');
			 stepValue = $(this).data('value');
			 $('#editFormWizard').val(1);
			if(currentStep > stepValue){
				if($(this).hasClass('formStepSecond')){
					$('.taskNext').data('value',0);
					$('.backward').trigger('click');
					$('#wizard-ul').find('.formStepThird').removeClass('activeFormStep');
					$('#wizard-ul').find('.formStepSecond').addClass('activeFormStep');
					$('#wizard-ul').find('.formStepfirst').removeClass('activeFormStep');
					$('#wizard-ul').find('.formStepFourth').removeClass('activeFormStep');
					$('#wizard-ul').find('.formStepFive').removeClass('activeFormStep');
					
					$('.step').each(function(){
					if($('.milestoneData').data('value') == 0){
						$('.backward').trigger('click');
					}else{
						$('#editFormWizard').val(0);
						return false;
					}
		
					});
					
		
				}
				if($(this).hasClass('formStepfirst')){
					$('.taskNext').data('value',0);
					$('#wizard-ul').find('.formStepThird').removeClass('activeFormStep');
					$('#wizard-ul').find('.formStepSecond').removeClass('activeFormStep');
					$('#wizard-ul').find('.formStepfirst').addClass('activeFormStep');
					$('#wizard-ul').find('.formStepFourth').removeClass('activeFormStep');
					$('#wizard-ul').find('.formStepFive').removeClass('activeFormStep');
					$('.backward').trigger('click');
					
					$('.step').each(function(){
					if($('.newGoalStep').data('value') == 0){
						$('.backward').trigger('click');
					}else{
						$('#editFormWizard').val(0);
						return false;
					}
		
					});
					
		
				}
				if($(this).hasClass('formStepThird')){
					var habitId = $('.habit-edit').attr('data-habit-id');
		           $('.taskNext').data('value',1);
					getHabit(habitId);
					$('#wizard-ul').find('.formStepThird').addClass('activeFormStep');
					$('#wizard-ul').find('.formStepSecond').removeClass('activeFormStep');
					$('#wizard-ul').find('.formStepfirst').removeClass('activeFormStep');
					$('#wizard-ul').find('.formStepFourth').removeClass('activeFormStep');
					$('#wizard-ul').find('.formStepFive').removeClass('activeFormStep');
					$('.backward').trigger('click');
					
					$('.step').each(function(){
					if($('.newTask').data('value') == 0){
						$('.backward').trigger('click');
					}else{
						$('#editFormWizard').val(0);
						return false;
					}
		
					});
		
				}
				if($(this).hasClass('formStepFourth')){
					$('.taskNext').data('value',0);
					var taskId = $('.task-edit').attr('data-task-id');
					console.log(taskId,4);
						getTask(taskId);
					$('#wizard-ul').find('.formStepThird').removeClass('activeFormStep');
					$('#wizard-ul').find('.formStepSecond').removeClass('activeFormStep');
					$('#wizard-ul').find('.formStepfirst').removeClass('activeFormStep');
					$('#wizard-ul').find('.formStepFourth').addClass('activeFormStep');
					$('#wizard-ul').find('.formStepFive').removeClass('activeFormStep');
					$('.backward').trigger('click');
					
					$('.step').each(function(){
					if($('.showNext').data('value') == 0){
						$('.backward').trigger('click');
					}else{
						$('#editFormWizard').val(0);
						return false;
					}
		
					});
		
				}
			}else if(currentStep < stepValue){
				if($(this).attr('isdone') == 1){
					if($(this).hasClass('formStepSecond')){
						$('.taskNext').data('value',0);
						$('#wizard-ul').find('.formStepThird').removeClass('activeFormStep');
						$('#wizard-ul').find('.formStepSecond').addClass('activeFormStep');
						$('#wizard-ul').find('.formStepfirst').removeClass('activeFormStep');
						$('#wizard-ul').find('.formStepFourth').removeClass('activeFormStep');
						$('#wizard-ul').find('.formStepFive').removeClass('activeFormStep');
						// $('.forward').trigger('click');
						
						$('.step').each(function(){
						if($('.milestoneData').data('value') == 0){
							$('.forward').trigger('click');
						}else{
							$('#editFormWizard').val(0);
							return false;
						}
						});
					}
					if($(this).hasClass('formStepThird')){
						$('.taskNext').data('value',1);
						var habitId = $('.habit-edit').attr('data-habit-id');
		
						getHabit(habitId);
						$('#wizard-ul').find('.formStepThird').addClass('activeFormStep');
						$('#wizard-ul').find('.formStepSecond').removeClass('activeFormStep');
						$('#wizard-ul').find('.formStepfirst').removeClass('activeFormStep');
						$('#wizard-ul').find('.formStepFourth').removeClass('activeFormStep');
						$('#wizard-ul').find('.formStepFive').removeClass('activeFormStep');
						$('.forward').trigger('click');
						
						$('.step').each(function(){
						if($('.newTask').data('value') == 0){
							$('.forward').trigger('click');
						}else{
							$('#editFormWizard').val(0);
							return false;
						}
			
						});
			
					}
					if($(this).hasClass('formStepFourth')){
						$('.taskNext').data('value',0);
						var taskId = $('.task-edit').attr('data-task-id');
						console.log(taskId,5);
						getTask(taskId);
						$('#wizard-ul').find('.formStepThird').removeClass('activeFormStep');
						$('#wizard-ul').find('.formStepSecond').removeClass('activeFormStep');
						$('#wizard-ul').find('.formStepfirst').removeClass('activeFormStep');
						$('#wizard-ul').find('.formStepFourth').addClass('activeFormStep');
						$('#wizard-ul').find('.formStepFive').removeClass('activeFormStep');
						$('.forward').trigger('click');
						
						$('.step').each(function(){
						if($('.showNext').data('value') == 0){
							$('.forward').trigger('click');
						}else{
							$('#editFormWizard').val(0);
							return false;
						}
			
						});
			
					}
					if($(this).hasClass('formStepFive')){
						$('.taskNext').data('value',0);
						$('#wizard-ul').find('.formStepThird').removeClass('activeFormStep');
						$('#wizard-ul').find('.formStepSecond').removeClass('activeFormStep');
						$('#wizard-ul').find('.formStepfirst').removeClass('activeFormStep');
						$('#wizard-ul').find('.formStepFourth').removeClass('activeFormStep');
						$('#wizard-ul').find('.formStepFive').addClass('activeFormStep');
						$('.forward').trigger('click');
						
						$('.step').each(function(){
						if($('.reviewGoal').data('value') == 0){
							$('.forward').trigger('click');
						}else{
							$('#editFormWizard').val(0);
							return false;
						}
			
						});
			
					}
					
				}
			}
		});

		$('.backto_dashboard').click(function(){
		swal({
				title: 'Warning',
				text:'Are you sure you want to leave the page? Any unsaved changes will be Lost.',
				allowOutsideClick: false,
				showCancelButton: true,
				confirmButtonText: 'ok',
				confirmButtonColor: '#ff4401',
				cancelButtonText: 'Cancel',
				animation: false
			}, 
			function(isConfirm){
				if(isConfirm){
					window.location =public_url+'new-dashboard';
					}
				
			});
		});
				
		$('.back_to_goal_list').click(function(){
			swal({
					title: 'Warning',
					text:'Are you sure you want to leave the page? Any unsaved changes will be Lost.',
					allowOutsideClick: false,
					showCancelButton: true,
					confirmButtonText: 'ok',
					confirmButtonColor: '#ff4401',
					cancelButtonText: 'Cancel',
					animation: false
				}, 
				function(isConfirm){
					if(isConfirm){
						window.location =public_url+'goals';
						}
					
				});
			});	
		
		$('.taskEventRepeatWeekdays').on('click',function(){
			// var habitValue = [];
			var allChecked = $("#hbt_rec_tsk").val() == 'daily' ? true : false;
			//$('.taskEventRepeatWeekdays:checked').length == $('.taskEventRepeatWeekdays').length;

			var taskRec =$(this);
			if($(this).is(':checked')){
				$(this).prop('checked',false);
				var habitValue = $('#habitRecValue').val();
				var recWeek = habitValue.split(',');

				if(jQuery.inArray($(this).val(), recWeek) == -1){

					var value =$(this).val();
					if(!allChecked){
						swal({
							title: 'Do you want to add it?',
							text: value+' is not included in habit associated',
							type: "warning",
							allowOutsideClick: false,
							showCancelButton: true,
							cancelButtonText:"Don't add",
							confirmButtonText: 'Yes, add',
							confirmButtonColor: '#ff4401',
							animation: false
						}, 
						function(isConfirm){
							if(isConfirm){
								taskRec.prop('checked',true);
								}
							
						});	
					}else{
						taskRec.prop('checked',true);
					}
					
				}else{
					taskRec.prop('checked',true);
				}	
			}

		});

function initFirstMilestoneDatePicker(dueDate){
    // var start = new Date(),
	//   end   = new Date(dueDate),
    //   diff  = new Date(end - start),
    //   days  = diff/1000/60/60/24;
    // //   days = Math.round(days);
	//   days = Math.ceil(days);
	//   const day_array = [];
	//   if(days < 7){
	// 	var current_day_number = new Date().getDay();
	// 	var j = 1;
	// 	for(let i = current_day_number; i <= 6 ; i++){
	// 		if(j <= days){
	// 			switch (i) {
	// 				case 0:
	// 					day = "Sunday";
	// 					break;
	// 				case 1:
	// 					day = "Monday";
	// 					break;
	// 				case 2:
	// 					day = "Tuesday";
	// 					break;
	// 				case 3:
	// 					day = "Wednesday";
	// 				   break;
	// 				case 4:
	// 					day = "Thursday";
	// 					break;
	// 				case 5:
	// 					day = "Friday";
	// 					break;
	// 				case 6:
	// 					day = "Saturday";
	// 					break;
	// 			}
	// 			day_array.push(day);
	// 	    	j++;
	// 		}	
	//  	 }
	// 	  console.log('day', day_array);
	// 	  const myArray = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
	// 	   const toRemove = new Set(day_array);
	// 	  const difference = myArray.filter( x => !toRemove.has(x));
	// 	   $.each(difference, function(index,val){
	// 		 	$("input[name='habitRecWeek'][value="+ val +"]").prop('disabled', true);				
	
	// 	   });
	// 	  console.log('difference', difference);
	//   }
	
	// var url = window.location.href;
    // var result = url.split('/');
    // var Param = result[result.length - 3];
    // var Param1 = result[result.length - 2];
    // if ((Param == 'goal-buddy') && (Param1 == 'edit')) {
	//    var li_length = $('li.dd-item').find('.edit-milestones-date').length;
	// 	if(li_length >= 1){
	// 		var last_date = $('li.dd-item').find('.edit-milestones-date').val();
	// 		var start = new Date(last_date);
	// 	}

    //  } else {
    //     var start = new Date();

    //  }

    // var start = new Date(),
	//  end   = new Date(dueDate),
    // diff  = new Date(end - start),
    // days  = diff/1000/60/60/24;
    // days = Math.round(days);
    // $('.milestones-date').datepicker({
    //     todayHighlight: 'TRUE',
    //     startDate: '-0d',
    //     autoclose: true,
    //     minDate: moment(),
    //     format: 'D, d M yyyy',
    //     endDate: '+'+days+'d',
    // });
}

$("#datepicker_SYG").datepicker({
	todayHighlight: 'TRUE',
	startDate: '-0d',
	autoclose: true,
	minDate: moment(),
	format: 'D, d M yyyy'
  }).on('changeDate', function (selected) {
		var minDate = new Date(selected.date.valueOf());
		minDate.setDate(minDate.getDate() - 1);
		$('.milestones-date').datepicker({
			todayHighlight: 'TRUE',
			startDate: '-0d',
			autoclose: true,
			minDate: moment(),
			format: 'D, d M yyyy',
			// endDate: '+'+days+'d',
		});
		$('.create-milestones-date').datepicker('setEndDate', minDate);
		var due_date =  moment(minDate, 'ddd, D MMM YYYY').format("YYYY-MM-DD");
		 $('#goalDueDate').val(due_date);
});


function getAllHabit(){
	var goalId = $('#last-insert-id').val();
	var templateCheck = $("input[name='chooseGoal']:checked").val() == 'choose_form_template' ? true : false;
	
	$.ajax({
		url: public_url+'goal-buddy/getAllHabit',
		type: 'POST',
		data: {'goal_id':goalId},
		success: function(data){
			var data = JSON.parse(data);
			
			if(data.allHabit != ''){
				$('.task-habit-div').show();
				var optionValue = '';

				var optionValue = '<select id="habit_div" name="habit_value" class="form-control  taskhabit_div_class" required=""><option value="">-- Select --</option>';
				
				$.each(data.allHabit,function(key, value) {
					optionValue += '<option value="'+value.id+'">'+value.gb_habit_name+'</option>';
				});
				optionValue += '</select>';
				$('.task-habit-dropdown').html(optionValue);
				initSelectpicker($('.task-habit-dropdown select'));
				$('.taskhabit_div_class').selectpicker('refresh');
				optionValue = '';
			}
		}
	});
}