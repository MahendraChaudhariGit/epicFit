var div = '',
	current_mode = '';
var exWidth=0;
function setRowHeight(view){
	if(view == 'month'){
		$.each($('.tr-body'), function(){
			var $this = $(this),
				height = $this.find('.tr-month').height();
				$this.find('.headcol').css('height',height+1);
		})
	}
	else{
		$.each($('.tr-body'), function(){
			var $this = $(this);
				$this.find('.headcol').css('height',$this.height());
		})
	}
}

function attendenceDelConfirm(callback){
	swal({
        title: "Are you sure to delete this attendance?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d43f3a",
        confirmButtonText: "Yes, delete it!",
        allowOutsideClick: true,
        customClass: 'delete-alert'
    }, 
    function(isConfirm){
		if(isConfirm)
			callback();
	});
}

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

function changeViewType(view){
	$('.fc-agendaDay-button').removeClass('fc-state-active');
	$('.fc-agendaWeek-button').removeClass('fc-state-active');
	$('.fc-agendaMonth-button').removeClass('fc-state-active');
	$('.fc-agenda'+view+'-button').addClass('fc-state-active');
}

function changeStructure(header,column){
	$('.dayheader').addClass('hidden');
	$('.weekheader').addClass('hidden');
	$('.monthheader').addClass('hidden');
	$('.daycolumn').addClass('hidden');
	$('.weekcolumn').addClass('hidden');
	$('.monthcolumn').addClass('hidden');
	if(header!="monthheader"){
		$('.'+header).removeClass('hidden');
		$('.'+column).removeClass('hidden');
	}
}

function showPopoverOverModal(elem, popoverOpt){
	elem.popover(popoverOpt).popover('show').data('bs.popover').tip().addClass('superior');
	calPopupHelper.removeClass('hidden').addClass('superior');
	shownPopover.push(elem)
}

function dateChange(param){
	var fetchDate = $('.fc-titleDatepicker-button').text();
	var fetchDateInFormat = moment(fetchDate).format('YYYY-MM-DD');
	if(param=="prev"){
		var fetchDay = moment(fetchDateInFormat).subtract(1,'d').format('YYYY-MM-DD');
	}
	else if(param=="next"){
		var fetchDay = moment(fetchDateInFormat).add(1,'d').format('YYYY-MM-DD');
	}
	$('#calendar').find('input[name="dateHolder"]').val(fetchDay);
	window.location.href = public_url+'settings/business/staffs/new-roster?date='+fetchDay;//+'&date2='+fetchDay;
}

function getData(viewType,exWidth){
	if(viewType=='Day'){
		$('.daycolumn').empty();
		var data = $('input[name=hoursdata]').val();
		if(data){
			//var columnWidth=0;
			var hoursdata = JSON.parse(data);
			var totalmin = 1440;
			var widthFix = (Main.isSmallDeviceFn()?0:59);
			var firstSlot = $('#firstTimeSlot').width();
			if(exWidth != undefined && widthFix != 0)
				 firstSlot+=exWidth;
			var columnWidth = (firstSlot-widthFix)*3;
		 	var permMinWidth = columnWidth/totalmin;

			$.each(hoursdata,function(key,value){
				if(value.startTime != null){
					var recordids = value.recordId.split(',');
					var status = value.status.split(',');
					var starttimes = value.startTime.split(',');
					var endtimes = value.endTime.split(',');

					if(value.editedStartTime)
						var editedstarttimes = value.editedStartTime.split(',');
					else 
						var editedstarttimes = '';
					if(value.editedEndTime)
						var editedendtimes = value.editedEndTime.split(',');
					else 
						var editedendtimes = '';
					if(value.staffDate)
						var staffDate = value.staffDate.split(',');
					var i = 0,
						html ='';
					$.each(starttimes,function(key,val){
						if(typeof editedstarttimes[i] != 'undefined'){
							var editedOrExistingStartTime = (editedstarttimes[i] != "" ? editedstarttimes[i]:val);
							var editedOrExistingEndTime = (editedendtimes[i] != "" ? editedendtimes[i]:endtimes[i]);
						}
						else{
							var editedOrExistingStartTime = val;
							var editedOrExistingEndTime = endtimes[i];
						}

						if(editedOrExistingStartTime != null && editedOrExistingStartTime != editedOrExistingEndTime){
							var startTimeArray = editedOrExistingStartTime.split(':');
							var Hours = parseInt(startTimeArray[0],10);
							var Minutes = parseInt(startTimeArray[1],10);
							var totalStartMinutes = (Hours*60)+Minutes;
							var endTimeArray = editedOrExistingEndTime.split(':');
							var Hours = parseInt(endTimeArray[0],10);
							var Minutes = parseInt(endTimeArray[1],10);
							var totalEndMinutes = (Hours*60)+Minutes;
							var width = (totalEndMinutes-totalStartMinutes)*permMinWidth;
							var leftMargin = totalStartMinutes*permMinWidth;

							html+='<div style="width:'+width+'px;margin-left:'+leftMargin+'px" class="h19 text-center font-11 m-b-2 time-slot " data-date="'+staffDate[i]+'" data-notes="'+value.notes+'" data-record-id="'+recordids[i]+'" data-start-time="'+val+'" data-end-time="'+endtimes[i]+'" data-staff-id="'+value.staffId+'" data-edited-start-time="'+editedstarttimes[i]+'" data-edited-end-time="'+editedendtimes[i]+'" data-status="'+status[i]+'" '+( (status[i]=='')?'data-disable-staff="0"':'data-disable-staff="1"')+'" >'+dbTimeToTimeString(editedOrExistingStartTime)+' - '+dbTimeToTimeString(editedOrExistingEndTime)+' '+((status[i]=='edited')?'<span class="epic-tooltip tooltipclass m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="'+dbTimeToTimeString(editedOrExistingStartTime)+' - '+dbTimeToTimeString(editedOrExistingEndTime)+'"><i class="fa fa-edit"></i></span>':(status[i]=='attended')?'<span class="epic-tooltip tooltipclass m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Attended"><i class="fa fa-check"></i></span>':(status[i]=='unattended')?'<span class="epic-tooltip tooltipclass m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Not attended"><i class="fa fa-thumbs-down"></i></span>':'')+'</div>' 
							i++;
						}
					})
					var fetch = $('#'+value.staffId).append(html);
					if(i < 1)
						$('#'+value.staffId).closest('.tr-body').css('height','34px');
					$('.tooltipclass').tooltipster();
					setTimeout(setRowHeight('day'),20);
				}
			})
		}
	}
	else if(viewType=="Week"){
		var titleDate = $('.fc-titleDatepicker-button').text();
		var titleDateInFormat = moment(titleDate).format('YYYY-MM-DD');
		var titledayIndex = moment(titleDateInFormat).day();
		var endOfWeek = moment(titleDateInFormat).add(6-titledayIndex,'d').format('YYYY-MM-DD');
		var startOfWeek = moment(endOfWeek).subtract(6,'d').format('YYYY-MM-DD');
		var formData = {};
		var html = '';
		formData['type']="ajax";
		$.get(public_url+'settings/business/staffs/new-roster?date1='+startOfWeek+'&date2='+endOfWeek,formData, function(response){ 
        	var data = JSON.parse(response);
        	$.each(data,function(key,value){
				var recordids = value.recordId.split(',');
				var status = value.status.split(',');
				var staffDates = value.staffDate.split(',');
        		var endtimes = value.endTime.split(',');
				var starttimes = value.startTime.split(',');

				if(value.editedStartTime)
					var editedstarttimes = value.editedStartTime.split(',');
				else 
					var editedstarttimes = '';
				if(value.editedEndTime)
					var editedendtimes = value.editedEndTime.split(',');
				else 
					var editedendtimes = '';

				if(value.staffDate)
					var staffDate = value.staffDate.split(',');

					console.log(staffDate);

				$('#'+value.staffId).parent().find('.weektask').empty();
        		for(i=0;i<staffDates.length;i++){
        			/*var editedOrExistingStartTime = (editedstarttimes[i]==null?dbTimeToTimeString(starttimes[i]):dbTimeToTimeString(editedstarttimes[i]));
					var editedOrExistingEndTime = (editedendtimes[i]==null?dbTimeToTimeString(endtimes[i]):dbTimeToTimeString(editedendtimes[i]));*/
        			if(typeof editedstarttimes[i] != 'undefined'){
						var editedOrExistingStartTime = (editedstarttimes[i] != "" ? editedstarttimes[i]:starttimes[i]);
						var editedOrExistingEndTime = (editedendtimes[i] != "" ? editedendtimes[i]:endtimes[i]);
					}
					else{
						var editedOrExistingStartTime = starttimes[i];
						var editedOrExistingEndTime = endtimes[i];
					}

					if(editedOrExistingStartTime != null && editedOrExistingStartTime != editedOrExistingEndTime){
	        			var Staffday = moment(staffDates[i], "YYYY-MM-DD HH:mm:ss").format('dddd');
	        			var time = dbTimeToTimeString(editedOrExistingStartTime)+'-'+dbTimeToTimeString(editedOrExistingEndTime);
	        			
	        			html='<div class="h19 text-center font-11 m-b-2 m-l-5 time-slot w110" data-notes="'+value.notes+'" data-date="'+staffDate[i]+'" data-record-id="'+recordids[i]+'" data-start-time="'+starttimes[i]+'" data-end-time="'+endtimes[i]+'" data-staff-id="'+value.staffId+'" data-edited-start-time="'+editedstarttimes[i]+'" data-edited-end-time="'+editedendtimes[i]+'" data-status="'+status[i]+'" '+( (status[i]=='')?'data-disable-staff="0"':'data-disable-staff="1"')+'" >'+dbTimeToTimeString(editedOrExistingStartTime)+' - '+dbTimeToTimeString(editedOrExistingEndTime)+' '+((status[i]=='edited')?'<span class="epic-tooltip tooltipclass m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="'+dbTimeToTimeString(editedOrExistingStartTime)+' - '+dbTimeToTimeString(editedOrExistingEndTime)+'"><i class="fa fa-edit"></i></span>':(status[i]=='attended')?'<span class="epic-tooltip tooltipclass m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Attended"><i class="fa fa-check"></i></span>':(status[i]=='unattended')?'<span class="epic-tooltip tooltipclass m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Not attended"><i class="fa fa-thumbs-down"></i></span>':'')+'</div>';
	        			//html='<div class="h19 text-center font-11 m-b-2 m-l-10 time-slot w110" data-notes="'+value.recordId+'" data-record-id="11" data-start-time="12" data-end-time='+endtimes[i]+' data-staff-id="'+value.staffId+'">'+time+'</div>'; 
	        			$('#'+value.staffId).parent().find('.'+Staffday).append(html);
	        			
	        			$('.tooltipclass').tooltipster();
	        		}
        		}
        	});
        	setTimeout(setRowHeight('week'),20);
        });
	}
	else if(viewType=="Month"){
		var monthAndYear = moment(currentDate).format('YYYY-MM');
		var daysInGivenMonth = moment(monthAndYear,"YYYY-MM").daysInMonth();
		var startOfMonth = monthAndYear+"-01";
		var endOfMonth = monthAndYear+"-"+daysInGivenMonth;
		var html ='';
		var formData = {};
		formData['type']="ajax";
		$.get(public_url+'settings/business/staffs/new-roster?date1='+startOfMonth+'&date2='+endOfMonth,formData, function(response){ 
        	var data = JSON.parse(response);
        	$.each(data,function(key,value){
        		var monthIds = value.staffDate.split(',');
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
				if(value.staffDate)
					var staffDate = value.staffDate.split(',');

					console.log(staffDate);

				$('#'+value.staffId).parent().find('.monthtask').empty();

        		for(i=0;i<monthIds.length;i++){
        			/*var editedOrExistingStartTime = (editedstarttimes[i]==null?dbTimeToTimeString(starttimes[i]):dbTimeToTimeString(editedstarttimes[i]));
					var editedOrExistingEndTime = (editedendtimes[i]==null?dbTimeToTimeString(endtimes[i]):dbTimeToTimeString(editedendtimes[i]));*/
        			if(typeof editedstarttimes[i] != 'undefined'){
						var editedOrExistingStartTime = (editedstarttimes[i] != "" ? editedstarttimes[i]:starttimes[i]);
						var editedOrExistingEndTime = (editedendtimes[i] != "" ? editedendtimes[i]:endtimes[i]);
					}
					else{
						var editedOrExistingStartTime = starttimes[i];
						var editedOrExistingEndTime = endtimes[i];
					}

					if(editedOrExistingStartTime != null && editedOrExistingStartTime != editedOrExistingEndTime){
	        			monthId = moment(monthIds[i]).format('D');
						var time = dbTimeToTimeString(editedOrExistingStartTime)+'-'+dbTimeToTimeString(editedOrExistingEndTime);
	        			html='<div class="h19 text-center font-11 time-slot" data-notes="'+value.notes+'" data-record-id="'+recordids[i]+'" data-date="'+staffDate[i]+'" data-start-time="'+starttimes[i]+'" data-end-time="'+endtimes[i]+'" data-staff-id="'+value.staffId+'" data-edited-start-time="'+editedstarttimes[i]+'" data-edited-end-time="'+editedendtimes[i]+'" data-status="'+status[i]+'" '+( (status[i]=='')?'data-disable-staff="0"':'data-disable-staff="1"')+' > '+((status[i]=='edited')?'<span class="epic-tooltip tooltipclass m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="'+dbTimeToTimeString(editedOrExistingStartTime)+' - '+dbTimeToTimeString(editedOrExistingEndTime)+'"><i class="fa fa-edit"></i></span>':(status[i]=='attended')?'<span class="epic-tooltip tooltipclass m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Attended"><i class="fa fa-check"></i></span>':(status[i]=='unattended')?'<span class="epic-tooltip tooltipclass m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Not attended"><i class="fa fa-thumbs-down"></i></span>':'')+'</div>';
	        			 //<div class="hbw100"></div>
	        			$('#'+value.staffId).parent().find('.time-slot').css({"margin-bottom":"2px"});
	        			$('#'+value.staffId).parent().find('.monthcolumn').css({"padding-top":"0px", "padding-bottom":"0px"});
	        			$('#'+value.staffId).parent().find('.monthtask').closest('.table').css("margin","0px");
	        			$('#'+value.staffId).parent().find('.'+monthId).css({"padding":"0px"});
	        			$('#'+value.staffId).parent().find('.monthcolumn').css("width","32px");
	        			//console.log("date"+monthId);
	        			//console.log($('#'+value.staffId).parent().find('.'+monthId));
	        			$('#'+value.staffId).parent().find('.'+monthId).append(html);
	        			$('.tooltipclass').tooltipster();
	        		}
        		}
        		
        	});
        	setTimeout(setRowHeight('month'),20);
        });	
	}
	var cdate = $('.fc-titleDatepicker-button').text();
	$('#addAttendence').find('.rosterDatepicker').val(moment(cdate).format("ddd, D MMM YYYY"));
}

function displayDateOnButton(givenDate){
		$('.fc-titleDatepicker-button').text(givenDate);
}

$(document).ready(function(){

	$('body').on('click', '.attendedclass', function(e){
		e.preventDefault();
		if(div.data('status') != 'attended'){
	        var formData = {};
	        formData['staffid'] = div.data('staff-id');
	        formData['starttime'] = div.data('start-time');
	        formData['endtime'] = div.data('end-time');
	        formData['recordid'] = div.data('record-id');
	        formData['case'] = 'attended';
	        $.ajax({
	                url:public_url+'staff-attendence/mark-attendence/'+formData['staffid'],
	                data: formData,
	                type:'post',
	                success:function(response){
	                	var data = JSON.parse(response);
	                	if(data.Status == "success"){
	                		ajaxsuccess('attended');
	                		div.append('<span class="epic-tooltip tooltipclass m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Attended"><i class="fa fa-check"></i></span>');
	                		$('.tooltipclass').tooltipster();
	                    	div.data('edited-start-time',null);
	                    	div.data('edited-end-time',null);
	                    	div.data('disable-staff',1);
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
	                    	div.append('<span class="epic-tooltip tooltipclass m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Not attended"><i class="fa fa-thumbs-down"></i></span>');
	                    	$('.tooltipclass').tooltipster();
	                    	div.data('disable-staff',1);
	                    } 
	                }
	        });
	    }
	});

	$('body').on('click', '.editAttendence', function(e){
		e.preventDefault();
        var modal = $('#editHours'),
        	attendenceId = div.data('record-id');
        $.ajax({
            url:public_url+'staff-attendence/edit/'+attendenceId,
            data: '',
            type:'get',
            success:function(response){
            	var data = JSON.parse(response);
            	if(data.status == "success"){
            		modal.find('input[name=start_time]').val(dbTimeToTimeString(data.start_time));
			   		modal.find('input[name=end_time]').val(dbTimeToTimeString(data.end_time));
			   		modal.find('#classNote').val(data.notes);
			   		calPopupHelper.trigger("click");
			   		modal.modal('show');
                	div.data('disable-staff',1);
                } 
            }
        });

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
				$.ajax({
	                url:public_url+'staff-attendence/mark-attendence/'+formData['staffid'],
	                data: formData,
	                type:'post',
	                success:function(response){
	                	var data = JSON.parse(response);
	                	if(data.Status == "success"){
	                		current_mode = 'reload';
	                		var calendorView = $('#calendar .fc-button-group').find('.fc-state-active').text().toLowerCase();
	                		if(calendorView == 'day'){
	                			location.reload(true);
	                		}
	                		else{
		                    	var starttime = div.data('start-time');
		                    	var endtime = div.data('end-time');
		                    	ajaxsuccess('edited');

	                    		if(calendorView == 'week'){
	                    			div.text(dbTimeToTimeString(formData['editedstarttime'])+' - '+dbTimeToTimeString(formData['editedendtime']));
	                    		}
	                    		div.append('<span class="epic-tooltip tooltipclass m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="'+dbTimeToTimeString(starttime)+' - '+dbTimeToTimeString(endtime)+'"><i class="fa fa-edit"></i></span>');
		                    	$('.tooltipclass').tooltipster();
		                    	div.data('edited-start-time',formData['editedstarttime']);
	                    		div.data('edited-end-time',formData['editedendtime']);
	                    		div.data('disable-staff',1);
	                    		div.data('notes',formData['notes']);
	                    	}
                    		$('#editHours').modal('hide');
	                    }
	                }
		        });
			}
	});

	/*$('#editHours').on('show.bs.modal', function(){
		calPopupHelper.trigger("click");
	});*/

	initCustomValidator();
	changeViewType('Day');

	var public_url = $('meta[name="public_url"]').attr('content');
	var hiddenDate = $('input[name="hiddendate"]').val();
	if(hiddenDate)
		currentDate=moment(hiddenDate).format('ddd, D MMM, YYYY');
	else
		currentDate = moment().format('ddd, D MMM, YYYY');

	displayDateOnButton(currentDate);
	var givenMonth = moment(currentDate).format('YYYY-MM');
	var daysInGivenMonth = moment(givenMonth,"YYYY-MM").daysInMonth();

	changeStructure('dayheader','daycolumn');
	getData('Day');

	calPopupHelper.click(function(){
		if(shownPopover.length != 0){
			shownPopover[0].popover('hide');
			shownPopover = [];
		}
		$(this).addClass('hidden').removeClass('superior');
	})

	calPopupHelper.height($(document).height());

	//On click datepicker
	$('.fc-titleDatepicker-button').on( "click", function(){
		$('#datepicker').datepicker("show");
	});

	//On click next button
	$('.fc-next-button').on( "click", function(){
		dateChange("next");
	});

	//On click previous buttton
	$('.fc-prev-button').on( "click", function(){
		dateChange("prev");
	});

	//On click Day view
	$('.fc-agendaDay-button').on('click',function(){
		if(current_mode == 'reload'){
			current_mode = '';
			location.reload(true);
			return;
		}
		else{
			changeViewType('Day');
			changeStructure('dayheader','daycolumn');
			exWidth=52;
			getData('Day',exWidth);
			/*$('.fix-table-style').addClass('day-margin-left');*/
			//setRowHeight('day');
		}
	});

	//On click Week view
	$('.fc-agendaWeek-button').on('click',function(){
		changeViewType('Week');
		changeStructure('weekheader','weekcolumn');
		getData('Week',exWidth);
		//setRowHeight('week');
	});

	//On click Month view
	$('.fc-agendaMonth-button').on('click',function(){
		changeViewType('Month');
		changeStructure('monthheader','monthcolumn');
		$('.monthheader:lt('+daysInGivenMonth+')').removeClass('hidden');
		//$('.monthcolumn:lt('+daysInGivenMonth+')').removeClass('hidden');
		$('.monthcolumn').removeClass('hidden');
		/*$('.fix-table-style').removeClass('day-margin-left');*/
		//var headerWidth = ($('#firstTimeSlot').width())*3;
		//console.log($('#roaastertable').width());
		//zz
		/*var tableWidth = $('#monthtable').width();
		//console.log(tableWidth);
		if(daysInGivenMonth==31){
			var headerWidth = tableWidth/31;
			$('.monthheader').css({"width":+headerWidth +"px","max-width":+headerWidth+"px"});
		}
		else if(daysInGivenMonth==30){
			var headerWidth = tableWidth/30;
			$('.monthheader').css({"width":+headerWidth +"px","max-width":+headerWidth+"px"});
			//$('.monthheader').css({"width":"2.8%","max-width":"2.8%"});
		}
		else if(daysInGivenMonth==29){
			var headerWidth = tableWidth/29;
			$('.monthheader').css({"width":+headerWidth +"px","max-width":+headerWidth+"px"});
			//$('.monthheader').css("width","2.89%");
		}
		else if(daysInGivenMonth==28){
			var headerWidth = tableWidth/28;
			$('.monthheader').css({"width":+headerWidth +"px","max-width":+headerWidth+"px"});
			//$('.monthheader').css("width","3%");
		}*/
		getData('Month',exWidth);

	});

	//On click Today button
	$('.fc-today-button').on("click", function(){
		var fetchDate = $('.fc-titleDatepicker-button').text();
		var todayDate = moment().format('ddd, D MMM, YYYY');
		if(todayDate!=fetchDate){
			window.location.href = public_url+'settings/business/staffs/new-roster';
		}
	});

	$('.tooltipclass').tooltipster();

	$('#datepicker').datepicker({
		numberOfMonths: 1,
		defaultDate:moment(currentDate).format('MM/DD/YYYY'),
		onSelect: function (dateText, inst){
			var fetchDay = moment(dateText).format('YYYY-MM-DD');
			window.location.href = public_url+'settings/business/staffs/new-roster?date='+fetchDay;//+'&date2='+fetchDay;
		}
	});	
	
	$('body').on('click', '.time-slot', function(){
		$('.popover').popover('hide');
		if( isUserType(['Admin']) || isUserType(['Staff']) ){ 
			var isValidEditPermission = $('input[name="hiddenpermission"]').val();
			if( isValidEditPermission !=0 ){
				div = $(this);
				var startTitle = '',
					endTitle = '';

				if(div.data('edited-start-time') != 'undefined' && div.data('edited-start-time') != ""){
					startTitle = dbTimeToTimeString(div.data('edited-start-time'));
					endTitle = dbTimeToTimeString(div.data('edited-end-time'));
				}
				else{
					startTitle = dbTimeToTimeString(div.data('start-time'));
					endTitle = dbTimeToTimeString(div.data('end-time'));
				}
				bookDate = $('input[name="hiddendate"]').val();
				var momentt = moment(),
				todayDate = momentt.format('YYYY')+'-'+momentt.format('MM')+'-'+momentt.format('DD');
				addEventPopoverOptt.title ="<strong>"+startTitle+' - '+endTitle+"</strong>";
				
				$('#calendar').find('.popover').popover('hide');
				if(bookDate <= todayDate){
					showPopoverOverModal($(this), addEventPopoverOptt);
				}else{
					swal({
						title: "Action can't be performed in future dates",
						type: "warning",
						showCancelButton: false,
						confirmButtonColor: "#d43f3a",
						confirmButtonText: "Done",
						allowOutsideClick: true,
					});
				}
		   		$('.attendedclass').parent().addClass(div.data('status'));	
			}		
		}
		
	});

	/* Start: Add new staff attendence modal submit*/
	$('#submitNewAttend').click(function(e){
		e.preventDefault();
		var $this = $(this),
			modal = $this.closest('.modal')
			form = modal.find('form'),
			isFormValid = form.valid(),
			dateField = form.find('input[name="date"]');
			formData = {};

			if(dateField.val() == ""){
				isFormValid = false;
				setFieldInvalid(dateField.closest('.form-group'),'This field is required.');
			}

			if(isFormValid){
				formData['notes'] = form.find('#attendNote').val();
				$.each(form.find(':input').serializeArray(), function(i, field){
					if(field.name == 'start_time' || field.name == 'end_time')
						formData[field.name] = timeStringToDbTime(field.value);
					else if(field.name == 'date')
						formData[field.name] = dateStringToDbDate(field.value);
					else
						formData[field.name] = field.value;
				})
				$('.time-slot').each(function(){
				  var staffId =$(this).data('staff-id');
				  if(staffId == formData['staff']){
					editedStartTime = $(this).data('edited-start-time');
					  editedEndTime = $(this).data('edited-end-time');
					  startTime = $(this).data('start-time');
					  endTime = $(this).data('start-time');
					  staffDate = $(this).data('date');
					  	if(staffDate == formData['date']){
							oldeditedStartTime = moment(editedStartTime,"HH:mm:ss");
							oldeditedEndTime = moment(editedEndTime,"HH:mm:ss");
							oldStartTime = moment(startTime,"HH:mm:ss");
							oldEndTime = moment(endTime,"HH:mm:ss");
							newStartTime = moment(formData['start_time'],"HH:mm:ss");
							newEndTime = moment(formData['end_time'],"HH:mm:ss");
							var range  = moment.range(oldStartTime,oldEndTime);
							var range2  = moment.range(newStartTime,newEndTime);
							var range3 = moment.range(oldeditedStartTime,oldeditedEndTime);
						
							
							if((range2.overlaps(range)) || (range2.overlaps(range3))){
								isFormValid = false;
								
							}
						}
				  }
				})
				if(isFormValid){
					$.ajax({
						url:public_url+'staff-attendence/add-attendence',
						type:'POST',
						data: formData,
						success: function(reponse){
							var data = JSON.parse(reponse);
							if(data.status = 'added'){
								modal.modal('hide');
								location.reload(true);
							}
						}
					})
				}else{
					swal({
						title: "Action can't be performed in same Times",
						type: "warning",
						showCancelButton: false,
						confirmButtonColor: "#d43f3a",
						confirmButtonText: "Done",
						allowOutsideClick: true,
					});
				}
			}
	})
	/* End: Add new staff attendence modal submit*/

	/* Start: Add new staff attendence modal close */
	$('#addAttendence').on('hide.bs.modal', function(){
		var modal = $(this),
			form = modal.find('form');

		$.each(form.find(':input'), function(){
			setFieldNeutral($(this));
		})

		form.find('#attendNote').val('');
		//clearForm(form);
	})
	/* End: Add new staff attendence modal close */

	/* Start: Delete attendence */
	$('body').on('click', '.delAttendence', function(e){
		e.preventDefault();
		calPopupHelper.trigger("click");
		//console.log(div.data('status'));
		var attenId = div.data('record-id');
		attendenceDelConfirm(function(){
	        $.ajax({
	            url:public_url+'staff-attendence/delete-attendence/'+attenId,
	            data: '',
	            type:'delete',
	            success:function(response){
	            	var data = JSON.parse(response);
	            	if(data.status == "deleted"){
	            		//var calendorView = $('#calendar .fc-button-group').find('.fc-state-active').text().toLowerCase();
	            		div.remove();
	            		location.reload(true);
	            		//setRowHeight(calendorView);
	                	//div.data('disable-staff',1);
	                } 
	            }
	        });
	    });
	});	
	/* End: Delete attendence */

});

