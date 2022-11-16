$(document).ready(function(){
	/* Appointment Goal and activity section DD */
	$('.app-section').click(function(e){
		e.preventDefault();
		var $this = $(this),
			dateString = $this.closest('#app-section-row').find('.ui-datepicker-trigger').text();

		setDropdownTextInHeader($this);
		getAppSectionData(dateString);
	})

	/* Health Section DD */
	$('.health-section').click(function(e){
		e.preventDefault();
		var $this = $(this),
			section = $this.data('text');
		setDropdownTextInHeader($this);
		toggelHealthSection(section);
	})

	/* Benchmark view */
	$('.benchmark-view-btn').click(function(e){
		e.preventDefault();
		toggleWaitShield('show');
		var $this = $(this),
			id = $this.data('id');

		$.ajax({
			url:  public_url+'benchmark/'+id,
			type: 'GET',
			data: {},
			success: function(response){
				var data = JSON.parse(response);
				displayBenchmarkData(data);
				$('#benchmark-table').hide();
				$('#benchmark-div').show();
				toggleWaitShield('hide');
			}
		});	
	})

	/* Benchmark view close */
	$('#close-benchmark-btn').click(function(e){
		e.preventDefault();
		$('#benchmark-table').show();
		$('#benchmark-div').hide();
	})
});

/* Start: Set dropdown text in header text */
function setDropdownTextInHeader(elem){
	var liText = elem.data('text'),
		liHeader = elem.closest('.rapidoDD').find('.rapidlitext');

	liHeader.html(liText);
}
/* End: Set dropdown text in header text */

/* Start: Toggel hide/show app section according to DD */
function toggelAppSection(section_name){
	var section_row = $('#app-section-row'),
		section = section_row.find('#'+section_name+'-section');

	if(section_name == 'all'){
		section_row.find('.app-section-area').show();
	}
	else{
		section_row.find('.app-section-area').hide();
		section.show();
	}

	if((section_name == 'appointments' || section_name == 'all') && $('#appointments-section').find('.accordion-toggle').hasClass('collapsed'))
		$('#appointments-section').find('.accordion-toggle').trigger('click');
	else if((section_name == 'activities' || section_name == 'goals') && section.find('.accordion-toggle').hasClass('collapsed'))
		section.find('.accordion-toggle').trigger('click');
}
/* End: Toggel hide/show app section according to DD */

/* Start: Toggel hide/show health section according to DD */
function toggelHealthSection(section_name){
	var section_row = $('#health-section-row'),
		section = section_row.find('#'+section_name+'-section');

		section_row.find('.health-section-area').hide();
		section.show();
}
/* End: Toggel hide/show health section according to DD */

/* Start: get data according to date and dd value */
function getAppSectionData(dateString){
	toggleWaitShield('show');
	var formData = {},
		date = moment(dateString).format("YYYY-MM-DD"),
		app_section = $.trim($('#app-section-text').text());

		formData['section_name'] = app_section.toLowerCase();
		formData['date'] = date;

	$.ajax({
		url:  public_url+'new-dashboard/app-section/data',
		type: 'GET',
		data: formData,
		success: function(response){
			var data = JSON.parse(response);
			displayDataInAppSection(data, formData['section_name']);
			toggleWaitShield('hide');
		}
	});	

}
/* End: get data according to date and dd value */

/* Start: Populate data in app section */
function displayDataInAppSection(data, section){
	var colspanNo = 0;
	if('appointments' in data){
		$('#appointments-field').empty();
		var html  = '';

		if((data.appointments).length > 0){
			$.each(data.appointments, function(i, value){
				if(value.type == 'class')
					var icon = '<i class="fa fa-bullhorn" style="color:#ff0000"></i> ';
				else
					var icon = '<i class="fa fa-cog" style="color:#ff0000"></i> ';

				html += '<tr><td>\
							<div class="deshboard-date">'+value.date+'</div>\
							<div class="fourth-staff">'+ icon + value.desc + '</div>\
						</td></tr>';
			});
		}
		else{
			html += '<tr><td>No record found.</td></tr>';
		}
		$('#appointments-field').append(html);
	}

	if('activities' in data){
		$('#activities-field').empty();
		var html  = '';
		
		if((data.activities).length > 0){
			$.each(data.activities, function(i, value){
				html += '<tr><td>\
							<div class="deshboard-date">'+value.date+'</div>\
							<div class="fourth-staff"><i class="fa fa-heartbeat" style="color:#ff0000"></i> ' + value.desc + '</div>\
						</td></tr>';
			});
		}
		else{
			html += '<tr><td>No record found.</td></tr>';
		}
		$('#activities-field').append(html);
	}

	if('goals' in data){
		$('#goals-field').empty();
		var html  = '';
		
		if((data.goals).length > 0){
			$.each(data.goals, function(i, value){
				html += '<tr>\
							<td class="center hidden-xs">'+(i+1)+'</td>\
							<td>'+value.name+'</td>\
							<td>'+value.due_date+'</td>\
							<td>\
								<div class="progress progress-striped progress-xs"style="margin-bottom:10px">\
		                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="'+value.per+'" style="width:'+value.per+'% ">\
		                            </div>\
		                        </div>\
								<p class="progress-percentage"><strong>Milestones:</strong>'+value.per+'%</p>\
							</td>\
						</tr>';
			});
		}
		else{
			html += '<tr><td colspan="4">No record found.</td></tr>';
		}
		$('#goals-field').append(html);
	}
	
	toggelAppSection(section);
}
/* End: Populate data in app section */



