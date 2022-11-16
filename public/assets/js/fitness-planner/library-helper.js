var currentPhaseNo = 0;
var currentWeekNo = 0;
var currentDayNo = 0;
var currentSessionNo = 0;
var currentSessionElement = '';
var srcElement = '';
var cloneElement = '';
var clonePhaseData = '';
var isChooseDays = false;
$(document).ready(function (e) {
	/* Start: Save/Edit library plan */
	$('.savePlanBtn').click(function (e) {
		e.preventDefault();
		var $this = $(this),
			formData = {},
			form = $('#libraryPro-form'),
			isformValid = form.valid(),
			img = form.find('input[name="programImage"]'),
			preImg = form.find('input[name="prePhotoName"]').val(),
			gender = form.find('input[name="genderAdmin"]:checked'),
			programGenerateField = $('#programGenerateField');

		form.find('.sucMes').children().remove().addClass('hidden');

		if (img.val() == '' && preImg == '') {
			isformValid = false;
			setFieldInvalid(img.closest('.form-group'), 'Program image is required.');
		}

		if (gender.length <= 0) {
			isformValid = false;
			setFieldInvalid($('.gender-class'), 'This field is required.');
		}

		if (isformValid) {
			$.each($(form).find(':input').serializeArray(), function (i, field) {
				formData[field.name] = field.value
			});

			FX.setClientid(0);
			FX.setGender(formData.genderAdmin);
			FX.setPlanType(6);

			if (formData['libraryProgramId'] != '')
				var url = 'activity-builder/library-program/single-phase/update/' + formData['libraryProgramId'];
			else
				var url = 'activity-builder/library-program/single-phase/save';

			$.post(public_url + url, formData, function (response) {
				var data = JSON.parse(response);
				if (data.status == "added") {
					programGenerateField.addClass('hidden');
					resetTrainingSegments();
					openStep($this);
					FX.setClientPlanId(data.id);
				} else if (data.status == "updated") {
					programGenerateField.addClass('hidden');
					FX.setClientPlanId(data.id);
					FX.loadProgram(data.id, resetAndPopulateTrainingSegments);
					openStep($this);
					//$('[data-step="trainingSegment"]').find('.panel-heading .panel-collapse').trigger('click');
				}
				$('.filter-section').find('#ability').val(form.find('#habit').val());
				$('.filter-section').find('#equipment').val(form.find('#equipment').val());
				$('select').selectpicker('refresh');
				loadExerciseList();
			});
		}

	})
	/* End : Save/Edit library plan */

	$('.nextMultPhaseButton').click(function (e) {
		e.preventDefault();
		var $this = $(this),
			formData = {},
			form = $('#libraryPro-form'),
			isformValid = form.valid(),
			img = form.find('input[name="programImage"]'),
			preImg = form.find('input[name="prePhotoName"]').val(),
			gender = form.find('input[name="genderAdmin"]:checked'),
			programGenerateField = $('#programGenerateField');

		form.find('.sucMes').children().remove().addClass('hidden');

		if (img.val() == '' && preImg == '') {
			isformValid = false;
			setFieldInvalid(img.closest('.form-group'), 'Program image is required.');
		}

		if (gender.length <= 0) {
			isformValid = false;
			setFieldInvalid($('.gender-class'), 'This field is required.');
		}

		if (isformValid) {
			$.each($(form).find(':input').serializeArray(), function (i, field) {
				formData[field.name] = field.value
			});

			FX.setClientid(0);
			FX.setGender(formData.genderAdmin);
			FX.setPlanType(9);

			if (formData['libraryProgramId'] != '')
				var url = 'activity-builder/library-program/multi-phase/update/' + formData['libraryProgramId'];
			else
				var url = 'activity-builder/library-program/multi-phase/save';

			$.post(public_url + url, formData, function (response) {
				var data = JSON.parse(response);
				$('input[name="clientPlanId"]').val(data.id);
				if (data.status == "added") {
					$('#selectDaysModal').modal('show');
					FX.setClientPlanId(data.id);
				} else if (data.status == "updated") {
					programGenerateField.addClass('hidden');
					FX.setClientPlanId(data.id);
					FX.loadProgram(data.id, resetAndPopulateTrainingSegments);
					openStep($this);
					//$('[data-step="trainingSegment"]').find('.panel-heading .panel-collapse').trigger('click');
				}
			});
		}

		// 
	});

	$('body').on('click', '.addPhase', function () {
		var phases = $('#createProgramModal').find('.phaseDiv .column').length;
		let phase = phases + 1;
		phaseHtml = '<div class="column">\
          <div class="card">\
			<h3 class="phase-row"> <span class="phaseActions"><a class="nav-link copyPhase" href="javascript:void(0)"><i class="fa fa-files-o" aria-hidden="true"></i></a> &nbsp;</span> Phase <span class="phaseNo">' + (phases + 1) + '</span> <i class="fa fa-times removePhase pull-right"></i>\</h3>\
            <div class="weekSection">\
              <div class="row weekRow">\
                <div class="col-md-6">\
                  <p>Week <span class="weekNo">1</span></p>\
                </div>\
                <div class="col-md-6">\
                  <a class="addWeek nav-link" href="javascript:void(0)">+ Add Week</a>\
                </div>\
                <div class="col-md-12">\
                  <div class="daysSection">\
                    <div class="row dayRow">\
                      <div class="col-md-6">\
                        <p>Day <span class="dayNo">1</span></p>\
                      </div>\
                      <div class="col-md-6">\
                        <a class="addDay nav-link" href="javascript:void(0)">+ Add Day</a>\
					  </div>\
					  '+(isChooseDays == true ? getDaysHtml(phase,1,1) : '')+'\
                      <div class="col-md-12">\
                        <div class="sessionSection">\
                          <div class="row sessionRow">\
                            <div class="col-md-6">\
                              <p>Session <span class="sessionNo">1</span></p>\
                            </div>\
                            <div class="col-md-6">\
                              <a class="addSession nav-link" href="javascript:void(0)">+ Add Session</a>\
                            </div>\
                            <div class="col-md-12">\
                              <p draggable="true" ondragstart="dragnew(event, this)" class="session-row" ondrop="dropnew(event, this)" ondragover="allowDropnew(event, this)" data-id="" data-title=""><a class="addProgram nav-link" href="javascript:void(0)">+ Add Session Program</a></p>\
                            </div>\
                          </div>\
                        </div>\
                      </div>\
                    </div>\
                  </div>\
                </div>\
              </div>\
            </div>\
          </div>\
		</div>';
		$('#createProgramModal').find('.phaseDiv').append(phaseHtml);
	})

	$('body').on('click', '.addWeek', function () {
		var phases = parseInt($(this).closest('.column').find('.phaseNo').text());
		var weeks = $(this).closest('.weekSection').find('.weekRow').length;
		let week = weeks + 1;
		var weekHtml = '<div class="row weekRow">\
			<div class="col-md-6">\
			<p>Week <span class="weekNo">' + (weeks + 1) + '</span></p>\
		  </div>\
		  <div class="col-md-6">\
			<a class="removeWeek nav-link" href="javascript:void(0)">- Remove</a>\
		  </div>\
		  <div class="col-md-12">\
			<div class="daysSection">\
			  <div class="row dayRow">\
				<div class="col-md-6">\
				  <p>Day <span class="dayNo">1</span></p>\
				</div>\
				<div class="col-md-6">\
				  <a class="addDay nav-link" href="javascript:void(0)">+ Add Day</a>\
				</div>\
				'+(isChooseDays == true ? getDaysHtml(phases,week,1) : '')+'\
				<div class="col-md-12">\
				  <div class="sessionSection">\
					<div class="row sessionRow">\
					  <div class="col-md-6">\
						<p>Session <span class="sessionNo">1</span></p>\
					  </div>\
					  <div class="col-md-6">\
						<a class="addSession nav-link" href="javascript:void(0)">+ Add Session</a>\
					  </div>\
					  <div class="col-md-12">\
						<p draggable="true" ondragstart="dragnew(event, this)" class="session-row" ondrop="dropnew(event, this)" ondragover="allowDropnew(event, this)"  data-id="" data-title=""><a class="addProgram nav-link" href="javascript:void(0)">+ Add Session Program</a></p>\
					  </div>\
					</div>\
				  </div>\
				</div>\
			  </div>\
			</div>\
		  </div>\
		</div>';
		$(this).closest('.weekSection').append(weekHtml);
	});

	$('body').on('click', '.addDay', function () {
		var phases = parseInt($(this).closest('.column').find('.phaseNo').text());
		var weeks = parseInt($(this).closest('.weekRow').find('.weekNo').text());
		var days = $(this).closest('.daysSection').find('.dayRow').length;
		let day = days + 1;
		var dayHtml = '<div class="row dayRow">\
			<div class="col-md-6">\
				<p>Day <span class="dayNo">' + (days + 1) + '<span></p>\
			</div>\
			<div class="col-md-6">\
				<a class="removeDay nav-link" href="javascript:void(0)">- Remove</a>\
			</div>\
			'+(isChooseDays == true ? getDaysHtml(phases,weeks,day) : '')+'\
			<div class="col-md-12">\
				<div class="sessionSection">\
					<div class="row sessionRow">\
					<div class="col-md-6">\
						<p>Session <span class="sessionNo">1</span></p>\
					</div>\
					<div class="col-md-6">\
						<a class="addSession nav-link" href="javascript:void(0)">+ Add Session</a>\
					</div>\
					<div class="col-md-12">\
						<p draggable="true" ondragstart="dragnew(event, this)" class="session-row" ondrop="dropnew(event, this)" ondragover="allowDropnew(event, this)" data-id="" data-title=""><a class="addProgram nav-link" href="javascript:void(0)">+ Add Session Program</a></p>\
					</div>\
					</div>\
				</div>\
			</div>\
		</div>';
		$(this).closest('.daysSection').append(dayHtml);
	});

	$('body').on('click', '.addSession', function () {
		var sessions = $(this).closest('.sessionSection').find('.sessionRow').length;
		var sessionHtml = '<div class="row sessionRow">\
		<div class="col-md-6">\
		<p>Session <span class="sessionNo"> ' + (sessions + 1) + '</span></p>\
		</div>\
		<div class="col-md-6">\
		<a class="removeSession nav-link" href="javascript:void(0)">- Remove</a>\
		</div>\
		<div class="col-md-12">\
		<p draggable="true" ondragstart="dragnew(event, this)" class="session-row" ondrop="dropnew(event, this)" ondragover="allowDropnew(event, this)" data-id="" data-title=""><a class="addProgram nav-link" href="javascript:void(0)">+ Add Session Program</a></p>\
		</div>\
	</div>';
		$(this).closest('.sessionSection').append(sessionHtml);

	});

	$('body').on('click', '.removePhase', function () {
		$(this).closest('.column').remove();
		var count = 1;
		$('#createProgramModal').find('.phaseNo').each(function () {
			$(this).text(count);
			count = count + 1;
		});
	});

	$('body').on('click', '.removeWeek', function () {
		var $this = $(this);
		week = $this.closest('.weekSection');

		$(this).closest('.weekRow').remove();
		var count = 1;
		week.find('.weekNo').each(function () {
			$(this).text(count);
			count = count + 1;
		});
	});

	$('body').on('click', '.removeDay', function () {
		var $this = $(this);
		day = $this.closest('.daysSection');
		$(this).closest('.dayRow').remove();
		var count = 1;
		day.find('.dayNo').each(function () {
			$(this).text(count);
			count = count + 1;
		});
	});
	$('body').on('click', '.removeSession', function () {
		var $this = $(this);
		session = $this.closest('.sessionSection');
		$(this).closest('.sessionRow').remove();
		var count = 1;
		session.find('.sessionNo').each(function () {
			$(this).text(count);
			count = count + 1;
		});
	});

	$('body').on('click', '.removeProgram', function () {
		var $this = $(this);
		swal({
			type: 'warning',
			title: 'Are you sure to remove this program!',
			showCancelButton: true,
			allowOutsideClick: false,
			showConfirmButton: true,
		},function(isConfirm){
			if(isConfirm){
				session = $this.closest('p');
				session.empty().append('<a class="addProgram nav-link" href="javascript:void(0)">+ Add Session Program</a>');
			}
		});
	});

	$('body').on('click', '.addProgram', function () {
		currentSessionElement = $(this);
		var phaseDiv = $(this).closest('.column');
		currentPhaseNo = phaseDiv.find('.phaseNo').text();
		currentWeekNo = $(this).closest('.weekRow').find('.weekNo').text();
		currentDayNo = $(this).closest('.dayRow').find('.dayNo').text();
		currentSessionNo = $(this).closest('.sessionRow').find('.sessionNo').text();
		$('#chooseProgram').modal('show');
	});

	const getDaysHtml = (phase = 1,week = 1,day = 1,dayName='') => {
		var daysHtml = '<div class="col-md-12">\
		<div class="radio clip-radio radio-primary radio-inline m-b-0">\
			<input type="radio" name="phase' + phase + 'week'+ week +'day'+ day +'" id="phase' + phase + 'week'+ week +'day'+ day +'Sun" required value="sun" '+(dayName != '' && dayName == 'sun'? 'checked':'')+' class="onchange-set-neutral">\
			<label for="phase' + phase + 'week'+ week +'day'+ day +'Sun">Sun</label>\
		</div>\
		<div class="radio clip-radio radio-primary radio-inline m-b-0">\
			<input type="radio" name="phase' + phase + 'week'+ week +'day'+ day +'" id="phase' + phase + 'week'+ week +'day'+ day +'Mon" required value="mon" '+(dayName != '' && dayName == 'mon'? 'checked':'')+' class="onchange-set-neutral">\
			<label for="phase' + phase + 'week'+ week +'day'+ day +'Mon">Mon</label>\
		</div>\
		<div class="radio clip-radio radio-primary radio-inline m-b-0">\
			<input type="radio" name="phase' + phase + 'week'+ week +'day'+ day +'" id="phase' + phase + 'week'+ week +'day'+ day +'Tue" required value="tue" '+(dayName != '' && dayName == 'tue'? 'checked':'')+' class="onchange-set-neutral">\
			<label for="phase' + phase + 'week'+ week +'day'+ day +'Tue">Tue</label>\
		</div>\
		<div class="radio clip-radio radio-primary radio-inline m-b-0">\
			<input type="radio" name="phase' + phase + 'week'+ week +'day'+ day +'" id="phase' + phase + 'week'+ week +'day'+ day +'Wed" required value="wed" '+(dayName != '' && dayName == 'wed'? 'checked':'')+' class="onchange-set-neutral">\
			<label for="phase' + phase + 'week'+ week +'day'+ day +'Wed">Wed</label>\
		</div>\
		<div class="radio clip-radio radio-primary radio-inline m-b-0">\
			<input type="radio" name="phase' + phase + 'week'+ week +'day'+ day +'" id="phase' + phase + 'week'+ week +'day'+ day +'Thu" required value="thu" '+(dayName != '' && dayName == 'thu'? 'checked':'')+' class="onchange-set-neutral">\
			<label for="phase' + phase + 'week'+ week +'day'+ day +'Thu">Thu</label>\
		</div>\
		<div class="radio clip-radio radio-primary radio-inline m-b-0">\
			<input type="radio" name="phase' + phase + 'week'+ week +'day'+ day +'" id="phase' + phase + 'week'+ week +'day'+ day +'Fri" required value="fri" '+(dayName != '' && dayName == 'fri'? 'checked':'')+' class="onchange-set-neutral">\
			<label for="phase' + phase + 'week'+ week +'day'+ day +'Fri">Fri</label>\
		</div>\
		<div class="radio clip-radio radio-primary radio-inline m-b-0">\
			<input type="radio" name="phase' + phase + 'week'+ week +'day'+ day +'" id="phase' + phase + 'week'+ week +'day'+ day +'Sat" required value="sat" '+(dayName != '' && dayName == 'sat'? 'checked':'')+' class="onchange-set-neutral">\
			<label for="phase' + phase + 'week'+ week +'day'+ day +'Sat">Sat</label>\
		</div>\
	  </div>';
	  return daysHtml;
	}

	var selectDaysModalPhase = $('#selectDaysModal');
	var trainingStepModalPhase = $('#chooseProgram');
	var createProgramModalPhase = $('#createProgramModal');
	var libraryProgramPhase = $('#currentAbility');
	var designProgramPhase = $('#customPlanUpdateModal');
	var equipmentLibraryPhase = $('#equipmentLibraryModal');
	var programWantModalPhase = $('#libraryProgramWant');
	var createTrainingSegmants = $('#createTrainingSegmants');
	var planPreview = $('#planPreview');
	trainingStepModalPhase.find('.chooseProgram').click(function () {
		trainingStepModalPhase.find('.chooseProgram').removeClass('active');
		$(this).addClass('active');
	});
	libraryProgramPhase.find('.currentAbilityOptions').click(function () {
		libraryProgramPhase.find('.currentAbilityOptions').removeClass('active');
		$(this).addClass('active');
	});
	equipmentLibraryPhase.find('.equipmentOption').click(function () {
		equipmentLibraryPhase.find('.equipmentOption').removeClass('active');
		$(this).addClass('active');
	});
	$('body').on('click', '.nextStepPhaseButton', function () {
		var currentStep = $(this).data('current-step');
		if (currentStep == 'selectDays'){
			let dayOption = selectDaysModalPhase.find('input[name="dayOption"]:checked').val();
			if((dayOption == 1 && isChooseDays == false) || (dayOption == 2 && isChooseDays == true)){
				if(createProgramModalPhase.find('.phaseDiv .card').length){
					swal({
						type: 'warning',
						title: 'All program data will be cleared',
						showCancelButton: true,
						allowOutsideClick: false,
						showConfirmButton: true,
					},function(isConfirm){
						if(isConfirm){
							createProgramModalPhase.find('.phaseDiv').empty();
							if(dayOption == 1){
								isChooseDays = true;
							}else{
								isChooseDays = false;
							}
							let clientPlanId = $('#clientPlanId').val();
							toggleWaitShield('show');
							$.get(`${public_url}activity-builder/library-program/multi-phase/update-plan`,{id:clientPlanId,dayOption:dayOption},function(response){
								toggleWaitShield('hide');
								if(response.status == 'ok'){
									selectDaysModalPhase.modal('hide');
									createProgramModalPhase.find('.backStepButton').data('prev-step', 'selectDaysModal');
									createProgramModalPhase.modal('show');
								}else{
									swal({
										type: 'error',
										title: 'Something went wrong!',
										showCancelButton: false,
										allowOutsideClick: false,
										showConfirmButton: true,
									});
								}
							});
						}
					});
				}else{
					if(dayOption == 1){
						isChooseDays = true;
					}else{
						isChooseDays = false;
					}
					let clientPlanId = $('#clientPlanId').val();
					toggleWaitShield('show');
					$.get(`${public_url}activity-builder/library-program/multi-phase/update-plan`,{id:clientPlanId,dayOption:dayOption},function(response){
						toggleWaitShield('hide');
						if(response.status == 'ok'){
							selectDaysModalPhase.modal('hide');
							createProgramModalPhase.find('.backStepButton').data('prev-step', 'selectDaysModal');
							createProgramModalPhase.modal('show');
						}else{
							swal({
								type: 'error',
								title: 'Something went wrong!',
								showCancelButton: false,
								allowOutsideClick: false,
								showConfirmButton: true,
							});
						}
					});
				}
			}else{
				if(dayOption == 1){
					isChooseDays = true;
				}else{
					isChooseDays = false;
				}
				let clientPlanId = $('#clientPlanId').val();
				toggleWaitShield('show');
				$.get(`${public_url}activity-builder/library-program/multi-phase/update-plan`,{id:clientPlanId,dayOption:dayOption},function(response){
					toggleWaitShield('hide');
					if(response.status == 'ok'){
						selectDaysModalPhase.modal('hide');
						createProgramModalPhase.find('.backStepButton').data('prev-step', 'selectDaysModal');
						createProgramModalPhase.modal('show');
					}else{
						swal({
							type: 'error',
							title: 'Something went wrong!',
							showCancelButton: false,
							allowOutsideClick: false,
							showConfirmButton: true,
						});
					}
				});
			}
		} else if (currentStep == 'stepChooseProgram') {
			var activeElement = $(this).closest('.modal').find('.chooseProgram.active');
			if (activeElement.length > 0) {
				var targetStep = activeElement.find('a').data('target-step');
				if (targetStep == 'currentAbility') {
					libraryProgramPhase.find('.backStepButton').data('prev-step', 'chooseProgram');
					libraryProgramPhase.modal('show');
					trainingStepModalPhase.modal('hide');
				} else {
					designProgramPhase.find('.backStepButton').data('prev-step', 'chooseProgram');
					designProgramPhase.modal('show');
					trainingStepModalPhase.modal('hide');

				}
			} else {
				swalAlert('Please Choose any Training!');
			}
		} else if (currentStep == 'stepCurrentAbility') {
			var activeElement = $(this).closest('.modal').find('.currentAbilityOptions.active');
			if (activeElement.length > 0) {
				var targetStep = activeElement.find('a').data('target-step');
				if (targetStep == 'equipmentHave') {
					planFilter['habit'] = FX.numericStringToInt(activeElement.find('a input').val());
					equipmentLibraryPhase.find('.backStepButton').data('prev-step', 'currentAbility');
					equipmentLibraryPhase.modal('show');
					libraryProgramPhase.modal('hide');
				}
			} else {
				swalAlert('Please Choose any Program!');
			}
		} else if (currentStep == 'stepLibraryEquipmentModal') {
			var activeElement = $(this).closest('.modal').find('.equipmentOption.active');
			if (activeElement.length > 0) {
				var targetStep = activeElement.find('a').data('target-step');
				planFilter['equipment'] = FX.numericStringToInt(activeElement.find('a input').val());
				gender = $('input[name="genderAdmin"]').val();
				if (gender == 'Male') {
					planFilter['gender'] = 2;
				} else if (gender == 'Female') {
					planFilter['gender'] = 1;
				}
				var data = {};
				data['habit'] = planFilter['habit'];
				data['equipment'] = planFilter['equipment'],
					data['gender'] = planFilter['gender'],
					data['plan_type'] = 6,
					$.get(public_url + 'Planner/GetFilterPlan', data, function (response) {
						var data = JSON.parse(response);
						var imagesCount = 0;
						programWantModalPhase.find('.backStepButton').data('prev-step', 'equipmentLibraryModal');
						programWantModalPhase.modal('show');
						equipmentLibraryPhase.modal('hide');
						if (data.status == 'success') {
							var programs = data.plan,
								colCount = 0,
								html = '';
							$.each(programs, function (index, value) {
								if (colCount == 0)
									html += '<div class="row m-b-20">';

								html += '<div class="col-md-3 program-list-box"><a class="open-step nextStepPhaseButton inactive" data-target-step="addProgram" data-current-step="libraryProgramWant" href="#" data-weeks="' + value.DefaultWeeks + '" data-time="' + value.TimePerWeek + '" data-day-pattern="' + value.DayPattern + '" data-clientplan-id="' + value.FixedProgramId + '"><input type="image" value="' + value.FixedProgramId + '" class="image_class program_img" src="' + public_url + 'uploads/thumb_' + value.Image + '" alt="' + value.ProgramName + '"><h3>' + value.ProgramName + '</h3></a><div class="program-action"><a href="#" class="btn btn-xs btn-primary nextStepPhaseButton" data-target-step="addProgram" data-current-step="libraryProgramWant" data-clientplan-id="' + value.FixedProgramId + '"><i class="fa fa-share link-btn"></i></a><a href="#" class="btn btn-xs btn-primary tooltips open-step nextStepPhaseButton" data-current-step="libraryProgramWant" data-placement="top" data-original-title="View" data-target-step="createTrainingSegmants" data-clientplan-id="' + value.FixedProgramId + '"><i class="fa fa-pencil link-btn"></i></a></div></div>';

								colCount++;
								if (colCount >= 4 || imagesCount - 1 == index) {
									html += '</div>';
									colCount = 0;
								}
		
							});
						} else {
							html = '';
							html += '<div class="col-md-12"><div class="alert alert-warning">No any program yet.</div></div>';
						}
						$('#libraryProgramWant').find('.item_class').html(html);
					})
			} else {
				swalAlert('Please Choose any equipment!');
			}
		} else if (currentStep == 'libraryProgramWant') {
			var targetStep = $(this).data('target-step');
			if (targetStep == 'createTrainingSegmants') {
				var form_data = {};
				programId = $(this).data('clientplan-id');
				form_data.progrmId = $(this).data('clientplan-id');
				form_data.clientPlanId = FX.ClientPlanId;
				form_data.programType = 1;
				FX.loadProgram(programId, resetAndPopulateTrainingSegments);
				loadExerciseList();
				$.get(public_url + 'activity-builder/library-program/multi-phase/getProgramDetails', form_data, function (response) {
					if (response.status == 'error') {
						console.log(response.message);
						swal({
							type: 'error',
							title: 'Something went wrong!',
							showCancelButton: false,
							allowOutsideClick: false,
							showConfirmButton: true,
						});
					} else {
						$('input[name="clientPlanProgramId"]').val(response.clientProgramId);
					}
				}, 'json')
				FX.setPlanType(9);
				createTrainingSegmants.find('.backStepButton').data('prev-step', 'libraryProgramWant');
				createProgramModalPhase.modal('hide');
				createTrainingSegmants.modal('show');
				programWantModalPhase.modal('hide');
			} else if (targetStep == 'addProgram'){
				var form_data = {};
				programId = $(this).data('clientplan-id');
				form_data.progrmId = $(this).data('clientplan-id');
				form_data.clientPlanId = FX.ClientPlanId;
				form_data.programType = 1;
				toggleWaitShield('show');
				$.get(public_url + 'activity-builder/library-program/multi-phase/getProgramDetails', form_data, function (response) {
					toggleWaitShield('hide');
					if (response.status == 'error') {
						swal({
							type: 'error',
							title: 'Something went wrong!',
							showCancelButton: false,
							allowOutsideClick: false,
							showConfirmButton: true,
						});
					} else {
						$('#clientPlanProgramId').val('');
						currentSessionElement.parent('p').empty().append('<span class="addedSessionProgram" data-client-program-id="' + response.clientProgramId + '">' + response.title + '</span> &nbsp;&nbsp; <a class="editProgram nav-link" href="javascript:void(0)"> &nbsp;&nbsp; <a><i class="fa fa-pencil" aria-hidden="true"></i> </a> <a class="removeProgram nav-link" href="javascript:void(0)"> &nbsp; <i class="fa fa-trash-o" aria-hidden="true"></i></a><a class="copyProgram nav-link" href="javascript:void(0)"> &nbsp; <i class="fa fa-clone" aria-hidden="true"></i> </a> &nbsp;<i class="fa fa-bars nav-link" aria-hidden="true"></i>');
						programWantModalPhase.modal('hide');
						createProgramModalPhase.modal('show');
					}
				},'json')
				FX.setPlanType(9);
			}
		} else if (currentStep == 'designProgramMultiPhase') {
			var targetStep = $(this).data('target-step');
			if (targetStep == 'trainingSegment') {
				var formData = {};
				formData.title = designProgramPhase.find('input[name="progName"]').val();
				formData.description = designProgramPhase.find('#progDesc').val();
				if(formData.title == '' || formData.description == ''){
					if(formData.title == ''){
						$('#progName').rules('add', {
						required: true   
						});
					}
					if(formData.description == ''){
						$('#progDesc').rules('add', {
						required: true   
						});	
					}
				}
				else{
					formData.clientPlan = $('input[name="clientPlanId"]').val();
					formData.programType = 2;
					$.get(public_url + 'activity-builder/library-program/multi-phase/create-program', formData, function (response) {
						$('#clientPlanProgramId').val(response.clientPlanProgramId);
						resetTrainingSegments();
						loadExerciseList();
						createTrainingSegmants.find('.backStepButton').data('prev-step', 'customPlanUpdateModal');
						designProgramPhase.modal('hide');
						createTrainingSegmants.modal('show');
						createProgramModalPhase.modal('hide');

					});
				}
			}
		} else if (currentStep == 'createCustomProgram') {
			var isFormValid= true;
			var formData = {};
			var previewHtml = '';
			var phaseDiv = $('#createProgramModal').find('.phaseDiv');
			var noOfPhase = phaseDiv.find('.column').length;
			formData.noOfPhase = noOfPhase;
			formData.clientPlanId = $('#clientPlanId').val();
			var phaseData = [];
			var phaseHtml = '';
			phaseDiv.find('.column').each(function () {
				var $this = $(this);
				var phaseNo = $this.find('.phaseNo').text();
				var weekSection = $this.find('.weekSection');
				var noOfWeek = weekSection.find('.weekRow').length;
				var weekData = [];
				var weekHtml = '';
				weekSection.find('.weekRow').each(function () {
					var $thisWeek = $(this);
					var weekNo = $thisWeek.find('.weekNo').text();
					var daysSection = $thisWeek.find('.daysSection');
					var noOfDays = daysSection.find('.dayRow').length;
					var daysData = [];
					var dayHtml = '';
					daysSection.find('.dayRow').each(function () {
						var $thisDay = $(this);
						var dayNo = $thisDay.find('.dayNo').text();
						var day = '';
						if(isChooseDays){
							$thisDay.find('input[type="radio"]').each(function () {
								if ($(this).is(':checked')) {
									day = $(this).val();
								}
							});
							if(day === ''){
								isFormValid = false;
							}
						}
						var sessionSection = $thisDay.find('.sessionSection');
						var noOfSessions = sessionSection.find('.sessionRow').length;
						var sessionData = []
						var sessionHtml = '';
						sessionSection.find('.sessionRow').each(function () {
							var $thisSession = $(this);
							var sessionNo = $thisSession.find('.sessionNo').text();
							var sessionProgramId = $thisSession.find('.addedSessionProgram').data('client-program-id');
							sessionHtml += '<div class="col-md-12">\
												<span> <b> Session Program ' + sessionNo + ' </b>-' + $thisSession.find('.addedSessionProgram').text() + '</span> \
											</div>';
							sessionData.push({
								sessionNo: sessionNo,
								sessionProgramId: sessionProgramId
							});
							if(sessionProgramId == '' || sessionProgramId == undefined){
								isFormValid= false;
							}
						});
						dayHtml += '<div class="col-md-12">\
										<span> <b> Day ' + dayNo + ' </b>' + (isChooseDays?'- '+day:'') + '</span> \
									</div>';
						dayHtml += sessionHtml;
						daysData.push({
							dayNo: dayNo,
							day: day,
							noOfSessions: noOfSessions,
							sessionData: sessionData
						});
					});
					weekHtml += '<div class="col-md-12">\
									<b> Week ' + weekNo + '</b >\
								</div >';
					weekHtml += dayHtml;
					weekData.push({
						weekNo: weekNo,
						noOfDays: noOfDays,
						daysData: daysData
					});
				});
				phaseHtml += '<div class="column">\
					<b class="plan-day-style"> Phase ' + phaseNo + '</b>\
					' + weekHtml + '</div>';
				phaseData.push({
					phaseNo: phaseNo,
					noOfWeek: noOfWeek,
					weekData: weekData
				});
			});
			previewHtml += '<div class="row">' + phaseHtml + '</div>';
			if(!isFormValid){
			swalAlert('Please Fill Day & Session Data!');
			}else{
				formData['data'] = phaseData;
				toggleWaitShield('show');
				$.post(public_url + 'activity-builder/library-program/multi-phase/plan-preview', formData, function (response) {
					toggleWaitShield('hide');
					$('#plan-preview').empty().append(previewHtml);
					planPreview.find('.backStepButton').data('prev-step', 'createProgramModal');
					planPreview.modal('show');
					createProgramModalPhase.modal('hide');
				})
			}
		} else if (currentStep == 'trainingSegment') {
			trainingSegmentPanel = createTrainingSegmants.find('.modal-body');
			$('#progName').val('');
			$('#progDesc').val('');
			var response = validateTrainingSegment();
			if (response.setFormValid) {
				let programId = $('#clientPlanProgramId').val();
				var choosedTrainingSegment = [];
				$('.choosetrainingSegment').each(function () {
					if ($(this).is(':checked')) {
					choosedTrainingSegment.push(parseInt($(this).val()));
					}
				});
				var i = 1;
				var order = [];
				var orderExercise = [];
				$('.add-exercise-btn').each(function () {
					if (jQuery.inArray($(this).data('workout'), choosedTrainingSegment) != -1) {
						var workotId = $(this).data('workout');
						order.push({
							'workout_id': $(this).data('workout'),
							'order': i
						});
						var parentPanel = $(this).closest('.panel');
						var j = 1;
						parentPanel.find('.exeRow').each(function () {
							var planWorkoutExerciseId = $(this).data('plan-workout-exercise-id');
							orderExercise.push({
							'planWorkoutExerciseId': planWorkoutExerciseId,
							'order': j
							});
							j = j + 1;
						});
						i = i + 1;
					}
				});
				toggleWaitShield('show');
				
				$.get(public_url + 'activity-builder/library-program/multi-phase/update-program', {
					id: programId,order:order,orderExercise:orderExercise
				}, function (response) {
					toggleWaitShield('hide');
					if (response.status == 'ok') {
						$('#clientPlanProgramId').val('');
						currentSessionElement.parent('p').data('id',programId);
						currentSessionElement.parent('p').data('title',response.title);
						currentSessionElement.parent('p').empty().append('<span class="addedSessionProgram" data-client-program-id="' + programId + '">' + response.title + '</span> &nbsp;&nbsp; <a class="editProgram nav-link" href="javascript:void(0)"><i class="fa fa-pencil" aria-hidden="true"></i> </a> <a class="removeProgram nav-link" href="javascript:void(0)"> &nbsp; <i class="fa fa-trash-o" aria-hidden="true"></i></a><a class="copyProgram nav-link" href="javascript:void(0)"> &nbsp; <i class="fa fa-clone" aria-hidden="true"></i> </a> &nbsp;<i class="fa fa-bars nav-link" aria-hidden="true"></i>');
						createTrainingSegmants.modal('hide');
						createProgramModalPhase.modal('show');
					} else {
						swal({
							type: 'error',
							title: 'Something went wrong!',
							showCancelButton: false,
							allowOutsideClick: false,
							showConfirmButton: true,
						});
					}
				});
			}
			else{
				showNotific(FX.prepareNotific('error', response.message), trainingSegmentPanel);
			}
		}
	});

	$('body').on('click','#saveProgramData',function(){
		var clientPlanId = $('#clientPlanId').val();
		var pathname = window.location.pathname;
		let insertCalendar = false;
		let activityClient = false;
		let startDate = '';
		if (pathname.includes("actvity-plan")) {
			insertCalendar = true;
			activityClient = true;
			startDate = startData;
		}
		toggleWaitShield('show');
		$.get(public_url+'activity-builder/library-program/multi-phase/update-plan',{id:clientPlanId,insertCalendar:insertCalendar,status:'complete',startDate:startDate},function(response){
			toggleWaitShield('hide');
			if(response.status == 'ok'){
				swal({
					type: 'success',
					title: 'Plan created successfully',
					showCancelButton: false,
					allowOutsideClick: false,
					showConfirmButton: true,
				},function(isConfirm){
					if(isConfirm){
						if(activityClient){
							window.location.reload();
						}else{
							window.location.href = public_url+'activity-builder/library-program/multi-phase';
						}
					}
				});
			}else{
				swal({
					type: 'error',
					title: 'Something went wrong!',
					showCancelButton: false,
					allowOutsideClick: false,
					showConfirmButton: true,
				});
			}
		});
	})

	$('body').on('click','.nextMultPhaseEditButton',function(e){
		e.preventDefault();
		var $this = $(this),
			formData = {},
			form = $('#libraryPro-form'),
			isformValid = form.valid(),
			img = form.find('input[name="programImage"]'),
			preImg = form.find('input[name="prePhotoName"]').val(),
			gender = form.find('input[name="genderAdmin"]:checked'),
			programGenerateField = $('#programGenerateField');

		form.find('.sucMes').children().remove().addClass('hidden');

		if (img.val() == '' && preImg == '') {
			isformValid = false;
			setFieldInvalid(img.closest('.form-group'), 'Program image is required.');
		}

		if (gender.length <= 0) {
			isformValid = false;
			setFieldInvalid($('.gender-class'), 'This field is required.');
		}

		if (isformValid) {
			$.each($(form).find(':input').serializeArray(), function (i, field) {
				formData[field.name] = field.value
			});

			FX.setClientid(0);
			FX.setGender(formData.genderAdmin);
			FX.setPlanType(9);
			var url = 'activity-builder/library-program/multi-phase/update/' + formData['libraryProgramId'];
			$.post(public_url + url, formData, function (response) {
				var data = JSON.parse(response);
				$('input[name="clientPlanId"]').val(data.id);
				isChooseDays = data.clientPlan.dayOption == 1 ? true:false;
				FX.setClientPlanId(data.id);
				var html = "";
				var libraryProgramId = $('input[name="libraryProgramId"]').val();
				toggleWaitShield('show');
				$.get(public_url+'activity-builder/library-program/multi-phase/get-phase-data',{id:libraryProgramId},function(response){
					toggleWaitShield('hide');
					var phaseData = response.data;
					$.each(phaseData,function(key,phase){
						html += '<div class="column">\
						<div class="card">\
						<h3 class="phase-row"> <span class="phaseActions"><a class="nav-link copyPhase" href="javascript:void(0)"><i class="fa fa-files-o" aria-hidden="true"></i></a> &nbsp;</span> Phase <span class="phaseNo">' + key + '</span> <i class="fa fa-times removePhase pull-right"></i>\</h3>\
						<div class="weekSection">';
						$.each(phase,function(keyWeek,week){
							html += '<div class="row weekRow">\
							<div class="col-md-6">\
								<p>Week <span class="weekNo">'+keyWeek+'</span></p>\
							</div>\
							<div class="col-md-6">\
								'+(keyWeek == 1 ? '<a class="addWeek nav-link" href="javascript:void(0)">+ Add Week</a>':'<a class="removeWeek nav-link" href="javascript:void(0)">- Remove</a>')+'\
							</div>\
							<div class="col-md-12">\
								<div class="daysSection">';
								$.each(week,function(keyDay,day){
									html += '<div class="row dayRow">\
									<div class="col-md-6">\
									<p>Day <span class="dayNo">'+keyDay+'</span></p>\
									</div>\
									<div class="col-md-6">\
									'+(keyDay == 1 ? '<a class="addDay nav-link" href="javascript:void(0)">+ Add Day</a>':'<a class="removeDay nav-link" href="javascript:void(0)">- Remove</a>')+'\
									</div>\
									'+(data.clientPlan.dayOption == 1 ? getDaysHtml(key,keyWeek,keyDay,day.day):'')+'\
									<div class="col-md-12">\
									<div class="sessionSection">';
									$.each(day, function(keySession,session){
										if(session.is_session_program == 1){
											html += '<div class="row sessionRow">\
												<div class="col-md-6">\
												<p>Session <span class="sessionNo">'+keySession+'</span></p>\
												</div>\
												<div class="col-md-6">\
												'+(keySession == 1 ? '<a class="addSession nav-link" href="javascript:void(0)">+ Add Session</a>':'<a class="removeSession nav-link" href="javascript:void(0)">- Remove</a>')+'\
												</div>\
												<div class="col-md-12">\
												<p draggable="true" ondragstart="dragnew(event, this)" class="session-row" ondrop="dropnew(event, this)" ondragover="allowDropnew(event, this)" data-title="'+ session.title +'" data-id="' + session.programId + '" ><span class="addedSessionProgram" data-client-program-id="' + session.programId + '">' + session.title + '</span> &nbsp;&nbsp; <a class="editProgram nav-link" href="javascript:void(0)"> <i class="fa fa-pencil" aria-hidden="true"></i> </a> <a class="removeProgram nav-link" href="javascript:void(0)"> &nbsp; <i class="fa fa-trash-o" aria-hidden="true"></i></a> <a class="copyProgram nav-link" href="javascript:void(0)"> &nbsp; <i class="fa fa-clone" aria-hidden="true"></i> </a> &nbsp;<i class="fa fa-bars nav-link" aria-hidden="true"></i></p>\
												</div>\
											</div>';
										}
									});
									html += '</div>\
										</div>\
									</div>';
								});
								html += '</div>\
										</div>\
									</div>';
						});
						html += '</div>\
						</div>\
						</div>';
					});
					$('#createProgramModal').find('.phaseDiv').empty().append(html);
					$('#createProgramModal').modal('show');
				});
			});
		}
	});


	/**
	 * Copy Phase Data
	 */
	$('body').on('click','.copyPhase',function(){
		$this = $(this);
		var weekSection =  $this.closest('.card').find('.weekSection');
		$('.phaseDiv').find('.card').removeClass('copy-active');
		$(this).closest('.card').addClass('copy-active');
		$('.card').each(function(){
			if(!$(this).hasClass('copy-active')){
				$(this).find('.phase-row .phaseActions').empty().append('<a class="nav-link pastePhase" href="javascript:void(0)"><i class="fa fa-clipboard" aria-hidden="true"></i></a> &nbsp;');
			}else{
				$(this).find('.phase-row .phaseActions').empty().append('<a class="nav-link closePhase" href="javascript:void(0)"><i class="fa fa-times-circle" aria-hidden="true"></i></a> &nbsp;')
			}
		});
		var noOfWeek = weekSection.find('.weekRow').length;
		weekData = {};
		weekSection.find('.weekRow').each(function (i,e) {
			var $thisWeek = $(this);
			var weekNo = $thisWeek.find('.weekNo').text();
			var daysSection = $thisWeek.find('.daysSection');
			var daysData = {};
			var day = '';
			daysSection.find('.dayRow').each(function (i,e) {
				var $thisDay = $(this);
				var dayNo = $thisDay.find('.dayNo').text();
				$thisDay.find('input[type="radio"]').each(function () {
					if ($(this).is(':checked')) {
						day = $(this).val();
					}
				});
				var sessionSection =$thisDay.find('.sessionSection');
					noOfSessions = sessionSection.find('.sessionRow').length;
					sessionData = {};
				sessionSection.find('.sessionRow').each(function (i,e) {
					var $thisSession = $(this);
					var sessionProgramId = $thisSession.find('.addedSessionProgram').data('client-program-id');
					sessionNo = $thisSession.find('.sessionNo').text();
					sessionName = $thisSession.find('.addedSessionProgram').text();
					sessionData[i] = {
						sessionProgramId: sessionProgramId,
						sessionNo: sessionNo,
						sessionName: sessionName
					};
				});
				daysData[i] = {
					dayNo: dayNo,
					day: day,
					sessionData: sessionData
				};
			});
			weekData[i] = {
				weekNo: weekNo,
				daysData: daysData
			};
		});
		clonePhaseData = JSON.stringify(weekData);
	});

	/**
	 * Paste Phase Data
	 */
	$('body').on('click','.pastePhase',function(){
		if(clonePhaseData != '' ||clonePhaseData != undefined){
			let dropElement = $(this).closest('.card').find('.weekSection');
			let phaseData = JSON.parse(clonePhaseData);
			let key = parseInt($(this).closest('.phase-row').find('.phaseNo').text());
			var html="";
			$.each(phaseData,function(keyWeek,week){
				html += '<div class="row weekRow">\
				<div class="col-md-6">\
					<p>Week <span class="weekNo">'+week.weekNo+'</span></p>\
				</div>\
				<div class="col-md-6">\
					'+(week.weekNo == 1 ? '<a class="addWeek nav-link" href="javascript:void(0)">+ Add Week</a>':'<a class="removeWeek nav-link" href="javascript:void(0)">- Remove</a>')+'\
				</div>\
				<div class="col-md-12">\
					<div class="daysSection">';
					$.each(week.daysData,function(keyDay,day){
						html += '<div class="row dayRow">\
						<div class="col-md-6">\
						<p>Day <span class="dayNo">'+day.dayNo+'</span></p>\
						</div>\
						<div class="col-md-6">\
						'+(day.dayNo == 1 ? '<a class="addDay nav-link" href="javascript:void(0)">+ Add Day</a>':'<a class="removeDay nav-link" href="javascript:void(0)">- Remove</a>')+'\
						</div>\
						'+(isChooseDays == 1 ? getDaysHtml(key,keyWeek,keyDay,day.day):'')+'\
						<div class="col-md-12">\
						<div class="sessionSection">';
						$.each(day.sessionData, function(keySession,session){
							html += '<div class="row sessionRow">\
								<div class="col-md-6">\
								<p>Session <span class="sessionNo">'+session.sessionNo+'</span></p>\
								</div>\
								<div class="col-md-6">\
								'+(session.sessionNo == 1 ? '<a class="addSession nav-link" href="javascript:void(0)">+ Add Session</a>':'<a class="removeSession nav-link" href="javascript:void(0)">- Remove</a>')+'\
								</div>\
								<div class="col-md-12">\
								<p class="div1" draggable="true" ondragstart="dragnew(event, this)" class="session-row" ondrop="dropnew(event, this)" ondragover="allowDropnew(event, this)" data-title="'+ session.sessionName +'" data-id="' + session.sessionProgramId + '" >'+(session.sessionName == ''?'<a class="addProgram nav-link" href="javascript:void(0)">+ Add Session Program</a>':'<span class="addedSessionProgram" data-client-program-id="' + session.sessionProgramId + '">' + session.sessionName + '</span> &nbsp;&nbsp; <a class="editProgram nav-link" href="javascript:void(0)"> <i class="fa fa-pencil" aria-hidden="true"></i> </a> <a class="removeProgram nav-link" href="javascript:void(0)"> &nbsp; <i class="fa fa-trash-o" aria-hidden="true"></i></a> <a class="copyProgram nav-link" href="javascript:void(0)"> &nbsp; <i class="fa fa-clone" aria-hidden="true"></i> </a> &nbsp;<i class="fa fa-bars nav-link" aria-hidden="true"></i>')+'</p>\
								</div>\
							</div>';
						});
						html += '</div>\
							</div>\
						</div>';
					});
					html += '</div>\
							</div>\
						</div>';
			});
			dropElement.empty().append(html);
			$('.card').each(function(){
				$(this).find('.phase-row .phaseActions').empty().append('<a class="nav-link copyPhase" href="javascript:void(0)"><i class="fa fa-files-o" aria-hidden="true"></i></a> &nbsp;');
			});
			clonePhaseData = '';
		}
	});

	$('body').on('click','.closePhase',function(){
		$('.card').each(function(){
			$(this).find('.phase-row .phaseActions').empty().append('<a class="nav-link copyPhase" href="javascript:void(0)"><i class="fa fa-files-o" aria-hidden="true"></i></a> &nbsp;');
		})
	})

	/**
	 * Edit Session program data
	 */
	$('body').on('click','.editProgram',function(e){
		e.preventDefault();
		$this = $(this);
		currentSessionElement = $this;
		let formData = {};
		const programId = $this.closest('p').find('.addedSessionProgram').data('client-program-id');
		$('#clientPlanProgramId').val(programId);
		const isMultiPhaseProgram = true;
		formData = {fixedProgramId:programId,isMultiPhaseProgram:isMultiPhaseProgram};
		toggleWaitShield('show');
		$.get(`${public_url}CustomPlan/GetUsersPlanDetail`,formData,function(response){
			toggleWaitShield('hide');
			if(response.status == 'success'){
				resetAndPopulateTrainingSegments(response);
				loadExerciseList();
				var pathname = window.location.pathname;
				if (pathname.includes("actvity-plan")) {
					trainingSegmentModal.modal('show');
					multiPhaseProgramModal.modal('hide');
				}else{
					createTrainingSegmants.modal('show');
					createProgramModalPhase.modal('hide');
				}
			}else{
				swal({
					type: 'error',
					title: 'Something went wrong!',
					showCancelButton: false,
					allowOutsideClick: false,
					showConfirmButton: true,
				});
			}
		},'json');

	});

	/**
	 * Copy Session program data
	 */
	$('body').on('click','.copyProgram',function(){
		$('.sessionRow').find('.session-row').removeClass('copy-active');
		$('.copySpan').remove();
		$('.copyClose').remove();
		cloneElement = $(this).parent('p').clone();
		$(this).parent('p').addClass('copy-active');
		$('.sessionRow').find('.session-row').each(function(){
			if(!$(this).hasClass('copy-active')){
				$(this).append('<a class="copySpan nav-link" href="javascript:void(0)"> &nbsp;<i class="fa fa-clipboard" aria-hidden="true"></i></a>');
			}else{
				$(this).append('<a class="copyClose nav-link" href="javascript:void(0)"> &nbsp;<i class="fa fa-times" aria-hidden="true"></i></a>')
			}
		})
	});

	$('body').on('click','.copySpan',function(){
		$(this).closest('div').empty().append(cloneElement);
		$('.copySpan').remove();
		$('.copyClose').remove();
		cloneElement = '';
	});

	$('body').on('click','.copyClose',function(){
		$(this).closest('div').empty().append(cloneElement);
		$('.copySpan').remove();
		$(this).remove();
		cloneElement = '';
	})

	$('#progName').on('input',function(){
		$('#progName').rules('add', {
			required: false   
		});
	});

	$('#progDesc').on('input',function(){
		$('#progDesc').rules('add', {
			required: false   
		});
	});
});

function allowDropnew(ev, event) {
	ev.preventDefault();
}

function dragnew(ev,event) {
	if($(event).find('.addedSessionProgram').length){
		srcElement = $(event);
		var porgramId = srcElement.data('id');
		var title = srcElement.data('title');
		ev.dataTransfer.setData("porgramId", porgramId);
		ev.dataTransfer.setData("title", title);
	}
}

function dropnew(ev, event) {
	var programID = ev.dataTransfer.getData("porgramId");
	name = ev.dataTransfer.getData("title");
	if(programID != undefined && programID != '' && name != undefined && name != ''){
		srcElement.empty().append('<a class="addProgram nav-link" href="javascript:void(0)">+ Add Session Program</a>');
		html =  $(event).closest('p');
		html.find('.addedSessionProgram').text(name);
		html.attr('data-title', name);
		html.attr('data-id', programID);
		html.empty();
		html.append('<span class="addedSessionProgram" data-client-program-id="' + programID + '">' + name + '</span> &nbsp;&nbsp; <a class="editProgram nav-link" href="javascript:void(0)"> <i class="fa fa-pencil" aria-hidden="true"></i> </a> <a class="removeProgram nav-link" href="javascript:void(0)"> &nbsp; <i class="fa fa-trash-o" aria-hidden="true"></i></a><a class="copyProgram nav-link" href="javascript:void(0)"> &nbsp; <i class="fa fa-clone" aria-hidden="true"></i></a> &nbsp;<i class="fa fa-bars nav-link" aria-hidden="true"></i>');
	}
}
