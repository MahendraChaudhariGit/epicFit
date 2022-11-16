var div = 0;
var currentDate;

$('body').on('click', '.attendedclass', function(e){
	e.preventDefault();
	if(div.data('status') != 'attended'){
        var formData = {};
        formData['staffid'] = div.data('staff-id');
        formData['starttime'] = div.data('start-time');
        formData['endtime'] = div.data('end-time');
        formData['recordid'] = div.data('record-id');
        formData['case'] = 'attended';
 		//fetchDate = $('.fc-titleDatepicker-button').text();
 		//formData['attendencedate'] = moment(fetchDate).format('YYYY-MM-DD');
        $.ajax({
                url:public_url+'staff-attendence/mark-attendence/'+formData['staffid'],
                data: formData,
                type:'post',
                success:function(response){
                	var data = JSON.parse(response);
                	if(data.Status == "success"){
                		ajaxsuccess('attended');
                		//div.children().remove();
                		div.append('<span class="epic-tooltip tooltipclass m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Attended"><i class="fa fa-check"></i></span>');
                		$('.tooltipclass').tooltipster();
                    	//div.append('<i class="fa fa-check"></i>');
                    	//div.data('status','attended');
                    	div.data('edited-start-time',null);
                    	div.data('edited-end-time',null);
                    	div.data('disable-staff',1);
                    	//calPopupHelper.trigger("click");
                    }   
                }
        });
    }
});	


$('body').on('click', '.unattendedclass', function(e){
	e.preventDefault();
	if(div.data('status') != 'unattended'){
        var formData = {};
        formData['staffid'] = div.data('staff-id');
        formData['recordid'] = div.data('record-id');
        formData['case'] = 'unattended';
        $.ajax({
                url:public_url+'staff-attendence/mark-attendence/'+formData['staffid'],
                data: formData,
                type:'post',
                success:function(response){
                	var data = JSON.parse(response);
                	if(data.Status == "success"){
                		ajaxsuccess('unattended');
                    	//div.children().remove();
                    	div.append('<span class="epic-tooltip tooltipclass m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Not attended"><i class="fa fa-thumbs-down"></i></span>');
                    	$('.tooltipclass').tooltipster();
                    	div.data('disable-staff',1);
                    	//div.append('<i class="fa fa-thumbs-down"></i>');
                    	//div.data('status','unattended');
                    	//calPopupHelper.trigger("click");
                    } 
                }
        });
    }
});	

$("#submitBtn").on( "click", function(e) {
	e.preventDefault();
	    var form = $('#attendenceForm');
	    var isFormValid = form.valid();
		if(isFormValid){
			var formData = {};
		    formData['editedstarttime'] = timeStringToDbTime($('input[name=start_time]').val());
		    formData['editedendtime'] = timeStringToDbTime($('input[name=end_time]').val());
			formData['staffid'] = div.data('staff-id');
			formData['recordid'] = div.data('record-id');
        	formData['case'] = 'edited';
        	formData['notes'] = $('#classNote').val();
			//fetchDate = $('.fc-titleDatepicker-button').text();
			//formData['attendencedate'] = moment(fetchDate).format('YYYY-MM-DD');
			$.ajax({
	                url:public_url+'staff-attendence/mark-attendence/'+formData['staffid'],
	                data: formData,
	                type:'post',
	                success:function(response){
	                	var data = JSON.parse(response);
	                	if(data.Status == "success"){
	                    	//div.children().remove();
	                    	var starttime = div.data('start-time');
	                    	var endtime = div.data('end-time');
	                    	ajaxsuccess('edited');
                    		div.text(dbTimeToTimeString(formData['editedstarttime'])+' - '+dbTimeToTimeString(formData['editedendtime']));
                    		div.append('<span class="epic-tooltip tooltipclass m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="'+dbTimeToTimeString(starttime)+' - '+dbTimeToTimeString(endtime)+'"><i class="fa fa-edit"></i></span>');
	                    	//div.data('status','edited');
	                    	$('.tooltipclass').tooltipster();
	                    	div.data('edited-start-time',formData['editedstarttime']);
                    		div.data('edited-end-time',formData['editedendtime']);
                    		div.data('disable-staff',1);
                    		div.data('notes',formData['notes']);
                    		$('#editHours').modal('hide');

	                    }
	                   
	                }
	        });
		}

});

function ajaxsuccess(msg){
	div.children().remove();
	if(msg=='attended'){
		div.data('status','attended');
	}
	else if(msg=='unattended'){
        div.data('status','unattended');
	}
	else if(msg=='edited'){
		div.data('status','edited');
	}
	calPopupHelper.trigger("click");
}

$('#editHours').on('show.bs.modal', function(){
	calPopupHelper.trigger("click");
});


$(document).ready(function(){	
	initCustomValidator();

	calPopupHelper.click(function(){
		if(shownPopover.length !== 0){
			shownPopover[0].popover('destroy');
			shownPopover = [];
		}
		$(this).addClass('hidden').removeClass('superior');
	})

	calPopupHelper.height($(document).height());

	//display staff working hours
	var data = $('input[name=hoursdata]').val();
	if(data){
		var hoursdata = JSON.parse(data);
		var totalmin = 1440;
		var widthFix = (Main.isSmallDeviceFn()?0:59);
		var columnWidth = ($('#firstTimeSlot').width()-widthFix)*3;
	 	var permMinWidth = columnWidth/totalmin;
		$.each(hoursdata,function(key,value){
			var endtimes = value.endTime.split(',');
			var starttimes = value.startTime.split(',');
			var recordids = value.recordId.split(',');
			var status = value.status.split(',');
			
			if(value.editedStartTime)
			var editedstarttimes = value.editedStartTime.split(',');
			else 
				var editedstarttimes = '';
			if(value.editedEndTime)
			var editedendtimes = value.editedEndTime.split(',');
			else 
				var editedendtimes = '';
			var i = 0;
			var html ='';

			$.each(starttimes,function(key,val){

				  		var startTimeArray = val.split(':');
						var Hours = parseInt(startTimeArray[0],10);
						var Minutes = parseInt(startTimeArray[1],10);
						var totalStartMinutes = (Hours*60)+Minutes;
						var endTimeArray = endtimes[i].split(':');
						//$endTimeArray= explode(':', $record->hr_end_time);
						var Hours = parseInt(endTimeArray[0],10);
						var Minutes = parseInt(endTimeArray[1],10);
						var totalEndMinutes = (Hours*60)+Minutes;

						var width = (totalEndMinutes-totalStartMinutes)*permMinWidth;
						var leftMargin = totalStartMinutes*permMinWidth;
						var editedOrExistingStartTime = (editedstarttimes[i]==null?dbTimeToTimeString(val):dbTimeToTimeString(editedstarttimes[i]));
						var editedOrExistingEndTime = (editedendtimes[i]==null?dbTimeToTimeString(endtimes[i]):dbTimeToTimeString(editedendtimes[i]));
						
						html+='<div style="width:'+width+'px;margin-left:'+leftMargin+'px" class="h19 text-center font-11 m-b-2 time-slot " data-notes="'+value.notes+'" data-record-id='+recordids[i]+' data-start-time='+val+' data-end-time='+endtimes[i]+' data-staff-id='+value.staffId+' data-edited-start-time='+editedstarttimes[i]+' data-edited-end-time='+editedendtimes[i]+' data-status="'+status[i]+'" '+( (status[i]=='')?'data-disable-staff="0"':'data-disable-staff="1"')+' >'+editedOrExistingStartTime+' - '+editedOrExistingEndTime+' '+((status[i]=='edited')?'<span class="epic-tooltip tooltipclass m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="'+dbTimeToTimeString(val)+' - '+dbTimeToTimeString(endtimes[i])+'"><i class="fa fa-edit"></i></span>':(status[i]=='attended')?'<span class="epic-tooltip tooltipclass m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Attended"><i class="fa fa-check"></i></span>':(status[i]=='unattended')?'<span class="epic-tooltip tooltipclass m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Not attended"><i class="fa fa-thumbs-down"></i></span>':'')+'</div>' 
						i++;

			})
			var fetch=$('#'+value.staffId).append(html);

		})
	}

	$('.tooltipclass').tooltipster();
	
	var public_url = $('meta[name="public_url"]').attr('content');
	var hiddenDate = $('input[name="hiddendate"]').val();
	if(hiddenDate)
		currentDate=moment(hiddenDate).format('ddd, D MMM, YYYY');
	else
	currentDate = moment().format('ddd, D MMM, YYYY');
	displayDateOnButton(currentDate);


	$('.fc-titleDatepicker-button').on( "click", function(){
		$('#datepicker').datepicker("show");
	});


	$('#datepicker').datepicker({
		numberOfMonths: 1,
		 maxDate: 0,
		defaultDate:moment(currentDate).format('MM/DD/YYYY'),
		onSelect: function (dateText, inst){
			var fetchDay = moment(dateText).format('YYYY-MM-DD');
			window.location.href = public_url+'settings/business/staffs/attendences?date='+fetchDay;
		}
	});
	
	$('.fc-next-button').on( "click", function(){
		dateChange("next");
	});
	
	$('.fc-prev-button').on( "click", function(){
		dateChange("prev");
	});

	$('.fc-today-button').on("click", function(){
		var fetchDate = $('.fc-titleDatepicker-button').text();
		var todayDate = moment().format('ddd, D MMM, YYYY');
		if(todayDate!=fetchDate){
			window.location.href = public_url+'settings/business/staffs/attendences';
		}
	});

	$('.time-slot').on("click", function(){
		
		if( isUserType(['Admin']) || isUserType(['Staff']) ){ 
			//if( moment(fetchDate).isBefore(todayDate) ){
			var isValidEditPermission = $('input[name="hiddenpermission"]').val();
			if( isValidEditPermission !=0 ){
				//alert("edit permission allowed");
				div = $(this);
				if( isUserType(['Staff']) && (div.data('disable-staff')==1))
				{ 
					}
				else{
					//alert("ok");
					addEventPopoverOptt.title ="<strong>"+dbTimeToTimeString(div.data('start-time'))+' - '+dbTimeToTimeString(div.data('end-time'))+"</strong>";
					showPopoverOverModal($(this), addEventPopoverOptt);
						if(div.data('edited-start-time') == 'undefined' || div.data('edited-start-time') == null){
							$('input[name=start_time]').val(dbTimeToTimeString(div.data('start-time')));
			   				$('input[name=end_time]').val(dbTimeToTimeString(div.data('end-time')));
			   			}
			   			else{
			   				$('input[name=start_time]').val(dbTimeToTimeString(div.data('edited-start-time')));
			   				$('input[name=end_time]').val(dbTimeToTimeString(div.data('edited-end-time')));
			   			}

			   			$('#classNote').val(div.data('notes'));
			   			$('.attendedclass').parent().addClass(div.data('status'));
		   		
		   			}	
				}		
		}
		
	});

function showPopoverOverModal(elem, popoverOpt){
	elem.popover(popoverOpt).popover('show').data('bs.popover').tip().addClass('superior');
	calPopupHelper.removeClass('hidden').addClass('superior');
	shownPopover.push(elem)
}


function dateChange(param){
			/*var fetchDate = $('.fc-titleDatepicker-button').text();
			var fetchDateInFormat = moment(fetchDate).format('YYYY-MM-DD');
			if(param=="prev")
				var outputDate = moment(fetchDateInFormat).subtract(1,'d').format('ddd, D MMM, YYYY');
			else if(param=="next")
				var outputDate = moment(fetchDateInFormat).add(1,'d').format('ddd, D MMM, YYYY');
			var outputDateinCalFormat = moment(outputDate).format('MM/DD/YYYY');
			displayDateOnButton(outputDate);
			$( "#datepicker" ).datepicker( "setDate", outputDateinCalFormat );*/
			var fetchDate = $('.fc-titleDatepicker-button').text();
			//alert(fetchDate);
			var fetchDateInFormat = moment(fetchDate).format('YYYY-MM-DD');
			if(param=="prev")
				var fetchDay = moment(fetchDateInFormat).subtract(1,'d').format('YYYY-MM-DD');
			else if(param=="next")
				var fetchDay = moment(fetchDateInFormat).add(1,'d').format('YYYY-MM-DD');

			window.location.href = public_url+'settings/business/staffs/attendences?date='+fetchDay;

}	

function displayDateOnButton(givenDate){
		$('.fc-titleDatepicker-button').text(givenDate);
}

})