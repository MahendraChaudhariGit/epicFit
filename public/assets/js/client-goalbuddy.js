$(document).ready(function(){
    $('.goal-name').click(function() {
        var currentTr = $(this).closest('tr');
        var currentMilestonesId = currentTr.find('.milestones').attr('id');
        var currentHabitsId = currentTr.find('.habits').attr('id');
        $('#'+currentMilestonesId).toggle();
        $('#'+currentMilestonesId).removeClass('hide');
        $('#'+currentHabitsId).toggle();
        $('#'+currentHabitsId).removeClass('hide');
    });

    $('.delete-goal').click(function() {
        var processbarDiv = $(this).closest('tr');
        var goalId = processbarDiv.find('#goal-id').val();
        deleteGoal(goalId);
        processbarDiv.remove();
    });
    $(document).on( 'click', '.create-goal', function(e) {
    	//console.log('ok');
    	$('.client-goalbuddylist').hide();
    	$('#goalbuddy-crate-section').removeClass('hidden');

    });
    $('.Btn_milestone').click(function() {
        var i =1;        
        if ($('#Milestones').val().length) {
    		var milestoneValue = $('#Milestones').val();
           
    		$('.mile_section ul').append('<li class="dd-item row" style="line-height: 20px; !important"><div class=" col-md-5"><div class="show_edit texts"><p class="milestones-text">' + milestoneValue +'</p><form class="milestones-form"><input type="text" name ="milestones" style ="display:none;" class = "milestones-name" value="'+milestoneValue+'"> </form></div></div><div class="col-md-4 milestones-date-cls" ><input type="text" class="form-control milestones-date datepicker_SYG4" autocomplete="off" name ="milestones-date" readonly></div><div class="col-md-2 m-t-20 pencil_find_sibling" ><a> <i class="fa fa-times delete-user-info pull-right" data-milestones-id=" " ></i></a><a><i class="fa fa-pencil edit-user-info pull-right"  ></i></a><a><i class="fa fa-save save-user-info pull-right"  data-milestones-id=" "></i></a></div></li>');
                getCalender();
            $('#Milestones').val('');
            return false;
    	}
            i++;
            return false;
    });

    $( document ).on( 'click', '#SYG_habit_recurrence0', function() {
	      $('.show-weeks').hide();
	      $('.month-count').hide();
    });
    $( document ).on( 'click', '#SYG_habit_recurrence1', function() {
	      $('.show-weeks').show();
	      $('.month-count').hide();
    });
    $( document ).on( 'click', '#SYG_habit_recurrence2', function() {
            $('.month-count').show();
            $('.show-weeks').hide();
            var totalDayInMonth=getDaysInMonth();
            var selector = "";
            $('.month-count .month-count-div').remove();
            selector += "<div class ='month-count-div'>Day <select class='month-date'>";
            for (var i = 1; i <= totalDayInMonth; i++) {
                selector += "<option value ="+i+">"+i+"</option>"; 
            }
            selector +="</select> of every month</div>";
            $('.month-count').append(selector);
            selector = '';
    });

    $('.countries').on('changed.bs.select', function(e){
				updateSearchState($(this));
	});

    updateSearchState($('#search_form').find('select.countries'));

    $('#client-datatable').dataTable();

});

    function getCalender(){
		    $('.datepicker_SYG4').datepicker({
		        //todayHighlight: 'TRUE',
		        //startDate: '-0d',
		        autoclose: true,
		        //format:"D, d M yyyy",
                dateFormat:"D, d M yy"
		    });
    }

    $('#datepicker_manage, #datepicker_SYG, #datepicker_SYG3, #datepicker_SYG00, .datepicker_SYG4' ).datepicker({
		    //todayHighlight: 'TRUE',
		    //startDate: '-0d',
		    autoclose: true,
		    //format:'D, d M yyyy'
            dateFormat:"D, d M yy"
	
    });

    function deleteGoal(id){
    $.ajax({
            url: 'deletegoal',
            type: 'POST',
            data: {'goal_id':id},
            success: function(response) {
              var data = JSON.parse(response);
                if(data.status == 'true'){
                   // location.reload();
              }  
            }
        });
    }


    $(document).on( 'click', '.save-user-info', function() {
        var mValue = $(this).closest('.dd-item').find('.milestones-name').val();
        var mDate = $(this).closest('.dd-item').find('.milestones-date').val();
        var mDateValue=moment(mDate, 'ddd, D MMM YYYY').format("YYYY-MM-DD");
        var milestonesId = $(this).data('milestones-id');
         
        $(this).closest('.dd-item').find('.milestones-text').show();
        $(this).closest('.dd-item').find('.milestones-text').text(mValue);
        $(this).closest('.dd-item').find('input[name="milestones"]').val('');
        $(this).closest('.dd-item').find('.milestones-name').hide();
        $(this).closest('.dd-item').find('.milestones-date').attr('disabled');
       // $(this).closest('.dd-item').find('.milestones-name').text();
        $(this).hide();
        $(this).closest('.dd-item').find('.dd-handle').css("padding","10px 10px");
        $(this).closest('.dd-item').find('.edit-user-info').show();
          //alert(milestonesId);
          if(milestonesId!=''){
            $.ajax({
            url: public_url+'goal-buddy/updatemilestones',
            type: 'POST',
            data: {'milestonesId':milestonesId,'mValue':mValue,'mDateValue':mDateValue},
            success: function(response) {
              //var data = JSON.parse(response);
              //console.log(data);
                 
            }
         });
        }

    }); 

    $( document ).on( 'click', '.delete-user-info', function() {
        var currentRow = $(this).closest('.dd-item');
        var milestonesId = $(this).data('milestones-id');
              $(currentRow).remove();
		      if(milestonesId!=''){
		           $.ajax({
		            url: public_url+'goal-buddy/deletemilestones',
		            type: 'POST',
		            data: {'milestonesId':milestonesId},
		            success: function(response) {
		              var data = JSON.parse(response);
		              //console.log(data);
		                if(data.status == 'true'){
		                    //location.reload();
		              }  
		            }
		        });   
		    }
    });

    $(document).on( 'click', '.edit-user-info', function() {
        $(this).closest('.dd-item').find('.milestones-text').hide();
        $(this).closest('.dd-item').find('.milestones-name').show();
        $(this).closest('.dd-item').find('input[name="milestones"]').val($(this).closest('.dd-item').find('.milestones-text').html());
        $(this).closest('.dd-item').find('.milestones-name').focus();
        $(this).closest('.dd-item').find('.milestones-date').removeAttr('disabled');
        $(this).closest('.dd-item').find('.dd-handle').css("padding","0px");
        $(this).hide();
        $(this).closest('.dd-item').find('.save-user-info').show();
    });

    	function updateSearchState(contryDd){
    		
				if(contryDd.length){
					
					var country_code = contryDd.val(),
					selectedStates = contryDd.closest('form').find('select.states');
					
					if(country_code == "" || country_code == "undefined" || country_code == null){
					selectedStates.html('<option value="">-- Select --</option>');
					selectedStates.selectpicker('refresh');
				}
				else{	
					$.ajax({
					url: public_url+'countries/'+country_code,
					method: "get",
					data: {},
					success: function(data) {
					var defaultState = selectedStates.data('selected'),
					formGroup = selectedStates.closest('.form-group');
					
					selectedStates.html("");
					$.each(data, function(val, text){
					var option = '<option value="">Select State</option><option value="' + val + '"';
					if(defaultState != '' && defaultState != null && val == defaultState)
					option += ' selected';
					option += '>' + text + '</option>';
					selectedStates.append(option);
					});
					
					contryDd.selectpicker('refresh');
					selectedStates.selectpicker('refresh');
					//setFieldValid(formGroup, formGroup.find('span.help-block'))
					}
					});
				}
				}
			} 