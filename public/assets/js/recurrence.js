$(document).ready(function(){
	calPopupHelper.click(function(){
		if(shownPopover.length !== 0){
			shownPopover[0].popover('destroy');
			shownPopover = [];
		}
		$(this).addClass('hidden').removeClass('superior');
	})

	setTimeout(function(){ 
		calPopupHelper.height($(document).height());
	}, 500);
	
		


});


/* start: Toggle event repeat fields */
	$('select[name="eventRepeat"]').change(function(){
		var $this = $(this),
			selVal = $this.val(),
			eventRepeatFields = $this.closest('.event-reccur').find('.eventRepeatFields'),
			eventRepeatIntervalDd = eventRepeatFields.find('select[name="eventRepeatInterval"]'),
			eventRepeatIntervalUnit = eventRepeatFields.find('.eventRepeatIntervalUnit'),
			eventRepeatWeekdays = eventRepeatFields.find('.eventRepeatWeekdays');

		eventRepeatWeekdays.hide();
		setFieldNeutral(eventRepeatWeekdays)
		if(selVal == 'Daily' || selVal == 'Weekly' || selVal == 'Monthly'){
			eventRepeatFields.show();
			eventRepeatIntervalDd.prop('required', true)

			if(selVal == 'Daily')
				eventRepeatIntervalUnit.text('days')
			else if(selVal == 'Weekly'){
				eventRepeatWeekdays.show();
				eventRepeatIntervalUnit.text('weeks');
			}
			else
				eventRepeatIntervalUnit.text('months')
		}
		else{
			eventRepeatFields.hide();
			eventRepeatFields.find(':input').prop('required', false)
		}
	});
	/* end: Toggle event repeat fields */

	/* start: Neutral event repeat weekdays checkbox */
	$('.eventRepeatWeekdays input[type="checkbox"]').change(function(){
		setFieldNeutral($(this))
	});
	/* end: Neutral event repeat weekdays checkbox */

	/* start: Toggle event repeat end fields */
	$('input[name="eventRepeatEnd"]').change(function(){
		var $this = $(this),
			selVal = $this.val(),
			module = $this.closest('.event-reccur'),
			eventRepeatEndAfterOccurDd = module.find('select[name="eventRepeatEndAfterOccur"]'),
			eventRepeatEndOnDate = module.find('input[name="eventRepeatEndOnDate"]');

		if(selVal == 'After'){
			eventRepeatEndAfterOccurDd.prop({'disabled':false, 'required':true})
			eventRepeatEndOnDate.prop('disabled', true);
			setFieldNeutral(eventRepeatEndOnDate)
		}
		else if(selVal == 'On'){
			eventRepeatEndAfterOccurDd.prop('disabled', true);
			setFieldNeutral(eventRepeatEndAfterOccurDd)
			eventRepeatEndOnDate.prop({'disabled':false, 'required':true})
		}
		else{
			eventRepeatEndAfterOccurDd.prop('disabled', true);
			eventRepeatEndOnDate.prop('disabled', true);
			setFieldNeutral(eventRepeatEndAfterOccurDd)
			setFieldNeutral(eventRepeatEndOnDate)
		}
		eventRepeatEndAfterOccurDd.selectpicker('refresh')
	});
	/* end: Toggle event repeat end fields */

	/* start: Reset event recurrence data */
	function resetEventReccur(modal){
		var module = modal.find('.event-reccur');

		module.find('select[name="eventRepeat"]').change();

		///module.find('input[name="eventRepeatEndOnDate"]').val(moment().format('D MMM YYYY'))
		setRepeatEndDate(modal)

		module.find('input[name="eventRepeatEnd"][value="On"]').prop('checked', true).trigger('change');

		setEventdayAsRepeatWeekDay(modal);
	}
	/* end: Reset event recurrence data */

	function setRepeatEndDate(modal, date){
		var field = modal.find('input[name="eventRepeatEndOnDate"]'),
			eventDate = setEventDate(modal, 'submit'),
			minDateMoment = moment(eventDate.date),
			minDate = minDateMoment.format('D MMM YYYY');

		field.datepicker("option", "minDate", minDate);

		if(typeof date == 'undefined'){
			var prevVal = field.val();
			if(!prevVal || moment(prevVal).isBefore(minDateMoment)){
				date = minDate;
				field.val(date);
			}
		}
		else{
			date = moment(date).format('D MMM YYYY');
			field.val(date)	
		}
	}

	function getCalendEndDate(){
		if(bladeType =="Dashboard"){
			var taskFilterDate = $('.ui-datepicker-trigger').text();//$('#taskFilterSection img').attr('alt');
			var taskFilterDateFormat = moment(taskFilterDate).format('YYYY-MM-DD');
			var addmonth = moment(taskFilterDateFormat).add(1,'M') .toDate();
			var subtractday = moment(addmonth).subtract(1, 'days');
			var nextMonthDate = moment(subtractday).format('YYYY-MM-DD');
			return nextMonthDate; //moment().endOf('month').format('YYYY-MM-DD');
		}
		else if(bladeType =="DashboardCalendar"){
			
			var datee = calendar.fullCalendar('getView').end;
			return datee.format('YYYY-MM-DD');
		}
	}


	/* start: Populate event recurrence data */
	function populateEventReccur(modal,repeatedData){
	    resetEventReccur(modal);

	    var module = modal.find('.event-reccur'),
	        savedEventRepeat = repeatedData[0].tr_repeat;
	        savedEventRepeatEnd = repeatedData[0].tr_repeat_end;


	    if(savedEventRepeat != null){
	        module.find('select[name="eventRepeat"]').val(savedEventRepeat).change();

	        if(savedEventRepeat == 'Daily' || savedEventRepeat == 'Weekly' || savedEventRepeat == 'Monthly'){
	          module.find('select[name="eventRepeatInterval"]').val(repeatedData[0].tr_repeat_interval); // module.find('select[name="eventRepeatInterval"]').val(eventObj.find('input[name="eventRepeatInterval"]').val())
	           //console.log(module.find('select[name="eventRepeatInterval"]').val());
	            
	            module.find('input[name="eventRepeatEnd"][value="'+savedEventRepeatEnd+'"]').prop('checked', true).trigger('change');
	            if(savedEventRepeatEnd == 'After'){
	          	  module.find('select[name="eventRepeatEndAfterOccur"]').val(repeatedData[0].tr_repeat_end_after_occur);
	          	  
				}
	            else if(savedEventRepeatEnd == 'On'){
	            	setRepeatEndDate(modal,repeatedData[0].tr_repeat_end_on_date );//setRepeatEndDate(modal, eventObj.find('input[name="eventRepeatEndOnDate"]').val())
	            }

	            if(savedEventRepeat == 'Weekly'){
	                eventRepeatWeekdays = module.find('.eventRepeatWeekdays input[type="checkbox"]');
	                eventRepeatWeekdays.prop('checked', false);
	               	$.each(JSON.parse(repeatedData[0].tr_repeat_week_days), function(key,val){
	                    eventRepeatWeekdays.filter('[value="'+val+'"]').prop('checked', true)
	                });
	            }
	        }
	    }
	   
	}
/* end: Populate event recurrence data */

/* start: show popover over modal */
	function showPopoverOverModal(elem, popoverOpt){
		elem.popover(popoverOpt).popover('show').data('bs.popover').tip().addClass('superior');
		calPopupHelper.removeClass('hidden').addClass('superior');
		shownPopover.push(elem)
	}
/* end: show popover over modal */

