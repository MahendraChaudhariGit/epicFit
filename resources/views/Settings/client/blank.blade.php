<!DOCTYPE html>
<!-- Template Name: Clip-One - Responsive Admin Template build with Twitter Bootstrap 3.x Version: 1.4 Author: ClipTheme -->
<!--[if IE 8]><html class="ie8 no-js" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9 no-js" lang="en"><![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
	<!--<![endif]--><head>
		<title>@yield('title', app_name())</title>
		<!-- start: META -->
		<meta charset="utf-8" />
		<meta name="_token" content="{{ csrf_token() }}" />
        <meta name="public_url" content="{{ asset('') }}">
		<!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<!-- end: META -->

		<!-- start: GOOGLE FONTS -->
	    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
	    <!-- end: GOOGLE FONTS -->
        
		<!-- start: MAIN CSS -->
		{!! Html::style('vendor/bootstrap/css/bootstrap.min.css') !!}
		{!! Html::style('vendor/fontawesome/css/font-awesome.min.css') !!}
        <!--[if IE 7]>
		<link rel="stylesheet" href="{{ asset('assets/plugins/font-awesome/css/font-awesome-ie7.min.css') }}">
		<![endif]-->
		{!! Html::style('assets/fonts/style.css') !!}
         @yield('invoice');
		<!-- {!! HTML::style('assets/css/invoice.css') !!}  -->
		{!! Html::style('vendor/themify-icons/themify-icons.min.css') !!}
		{!! Html::style('vendor/animate.css/animate.min.css', ['media' => 'screen']) !!}
		{!! Html::style('vendor/perfect-scrollbar/perfect-scrollbar.min.css', ['media' => 'screen']) !!}
		{!! Html::style('vendor/switchery/switchery.min.css', ['media' => 'screen']) !!}
		{!! Html::style('vendor/tooltipster-master/tooltipster.css') !!}
		{!! Html::style('vendor/jquery-ui/jquery-ui-1.10.1.custom.css') !!}
        {!! Html::style('vendor/bootstrap-select-master/css/bootstrap-select.min.css') !!}
		<!-- end: MAIN CSS -->

		<!-- start: CLIP-TWO CSS -->
		{!! Html::style('assets/css/styles-orange.css') !!}
		{!! Html::style('assets/css/clip-two/main-navigation.css') !!}
		@yield('plugin-css')
		{!! Html::style('assets/css/themes/theme-orange.css') !!}
		<!-- end: CLIP-TWO CSS -->
        
        {!! Html::style('assets/css/theme_light.css') !!}
        {!! Html::style('assets/css/print.css', ['media' => 'print']) !!}
        {!! Html::style('assets/plugins/fullcalendar/fullcalendar/fullcalendar.css') !!}
        {!! Html::style('assets/plugins/datepicker/css/datepicker.css') !!}
        {!! Html::style('assets/plugins/select2/select2.css') !!}
        {!! Html::style('assets/plugins/DataTables/media/css/DT_bootstrap.css') !!}
        {!! Html::style('assets/plugins/intl-tel-input-master/build/css/intlTelInput.css') !!}
        {!! Html::style('assets/plugins/nestable-cliptwo/jquery.nestable.css') !!}
        {!! Html::style('assets/plugins/Jcrop/css/jquery.Jcrop.min.css') !!}
        {!! Html::style('vendor/sweetalert/sweet-alert.css') !!}
        <!-- start: Bootstrap timepicker -->
		{!! Html::style('vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') !!}
		<!-- end: Bootstrap timepicker -->
        
        <!-- start - FITNESS PLANNER CSS -->
        {!! HTML::style('fitness-planner/css/pt-planner.css') !!}
        {!! HTML::style('fitness-planner/css/jquery.ui.labeledslider.css') !!}
        {!! HTML::style('fitness-planner/custom/style.css') !!}
		<link rel='stylesheet' href='http://api.onesportevent.com/devapi/css/v1/core_t5.css?ver=4.3.5' type='text/css' />
		<!-- end - FITNESS PLANNER CSS -->

		<!-- start: Rating -->
		{!! Html::style('assets/plugins/bootstrap-rating/bootstrap-rating.css') !!}
		<!-- end: Rating -->
        
        <!-- start - SUBVIEW CSS -->
        {!! Html::style('assets/plugins/summernote/dist/summernote.css') !!}
        <!-- end - SUBVIEW CSS -->
        
        {!! Html::style('assets/css/custom.css') !!}

        <!-- start: Jquery File Upload -->
		{!! Html::style('assets/plugins/jquery-file-upload2/css/jquery.fileupload-ui.css') !!}
		<!-- end: Jquery File Upload -->
        
		<link rel="shortcut icon" href="favicon.ico" />
        
        {!! Html::script('vendor/jquery/jquery.min.js') !!}
        {!! Html::script('assets/js/jquery-ui.min.js') !!}
        {!! Html::script('assets/plugins/jquery-migrate/jquery-migrate-1.2.1.min.js') !!}
        {!! Html::script('vendor/sweetalert/sweet-alert.min.js') !!}
		
        <!-- start - FITNESS PLANNER JS -->
        <!--{!! Html::script('assets/plugins/jquery-json-master/src/jquery.json.js') !!}
        <script type='text/javascript' src='http://api.onesportevent.com/devapi/js/v1/core_t32.js'></script>
    	<script type='text/javascript' src='http://api.onesportevent.com/devapi/js/v1/core_non10.js'></script>
        {!! Html::script('fitness-planner/custom/js/popup.js') !!}
        {!! Html::script('fitness-planner/custom/js/jquery.placeholder.js') !!}
        {!! Html::script('fitness-planner/custom/js/jquery.ui.touch-punch.min.js') !!}
        {!! Html::script('fitness-planner/custom/jwplayer/jwplayer.js') !!}
        {!! Html::script('fitness-planner/js/jquery.ui.labeledslider.js') !!}-->
        <!-- end - FITNESS PLANNER JS -->
	</head>
	<!-- start: BODY -->
	<body>
		<div id="calPopupHelper" class="hidden"></div>
		<div id="app" class="app-sidebar-closed a-s-c {{ Route::getCurrentRoute()->getPath() == 'clients/{filter?}'?'overflowFix':'' }}">
         @show
			<!-- side bar -->
			@include('layouts.includes.sidebar')

			<!-- start: APP CONTENT -->
			<div class="app-content">

				<!-- top nav bar -->
				@include('layouts.includes.navbar')

				<!-- start: MAIN CONTENT -->
				<div class="main-content" >
					<div class="wrap-content container" id="container">
						@include('layouts.includes.page_title')

						@include('includes.partials.messages')

						@yield('content')
					</div>
				</div>
				<!-- end: MAIN CONTENT -->

			</div>
			<!-- end: APP CONTENT -->

			@include('layouts.includes.footer')
		</div>
		{!! Html::script('vendor/bootstrap/js/bootstrap.js') !!}
		{!! Html::script('vendor/jquery-cookie/jquery.cookie.js') !!}
		{!! Html::script('vendor/perfect-scrollbar/perfect-scrollbar.min.js') !!}
		{!! Html::script('vendor/switchery/switchery.min.js') !!}
		{!! Html::script('assets/js/main.js') !!}

		<!--{!! Html::script('assets/js/clients-before-all.js') !!}-->
		<script src="{{ asset('assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js') }}"></script>
		<script src="{{ asset('assets/plugins/blockUI/jquery.blockUI.js') }}"></script>
		<script src="{{ asset('assets/plugins/iCheck/jquery.icheck.min.js') }}"></script>
		<script src="{{ asset('assets/plugins/perfect-scrollbar/src/jquery.mousewheel.js') }}"></script>
		<script src="{{ asset('assets/plugins/bootstrap-colorpalette/js/bootstrap-colorpalette.js') }}"></script>
		{!! Html::script('vendor/jquery-validation/jquery.validate.min.js') !!}
		<!--<script src="{{ asset('assets/plugins/jquery-validation/dist/jquery.validate.min.js') }}"></script>-->
		<script src="{{ asset('assets/plugins/jQuery-Smart-Wizard/js/jquery.smartWizard.js') }}"></script>
		<script src="{{ asset('assets/js/form-wizard-clients.js') }}"></script>
		<script src="{{ asset('assets/js/form-wizard-benchmark.js') }}"></script>
		<script src="{{ asset('assets/plugins/fullcalendar/fullcalendar/fullcalendar.js') }}"></script>
		<script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
		<script src="{{ asset('assets/js/metronic.js') }}"></script>
		<script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
		<script src="{{ asset('assets/plugins/DataTables/media/js/jquery.dataTables.min.js') }}"></script>
		<script src="{{ asset('assets/plugins/DataTables/media/js/DT_bootstrap.js') }}"></script>
		<script src="{{ asset('assets/plugins/intl-tel-input-master/build/js/utils.js') }}" ></script>
		<script src="{{ asset('assets/plugins/intl-tel-input-master/build/js/intlTelInput.js') }}" ></script>
		<script src="{{ asset('assets/plugins/nestable-cliptwo/jquery.nestable.js') }}" ></script>
		<script src="{{ asset('assets/plugins/nestable-cliptwo/nestable.js') }}" ></script>
		<script src="{{ asset('assets/plugins/Jcrop/js/jquery.Jcrop.min.js') }}" ></script>
		<script src="{{ asset('assets/plugins/Jcrop/js/script.js') }}" ></script>
        {!! Html::script('vendor/bootstrap-select-master/js/bootstrap-select.min.js') !!}
        {!! Html::script('vendor/tooltipster-master/jquery.tooltipster.min.js') !!}
		<script>
			$.ajaxSetup({
		        headers: {
		            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
		        }
		    });
			/*function listOfpart(valBody){
				if(valBody){
					$('#listBodyPart').val(valBody);
					$('#allbodyPart').removeClass('hidden');
					for(i=0;i<11;i++){
						if(valBody == i)
							$('#bodyPart'+i).removeClass('hidden');
						else
							$('#bodyPart'+i).addClass('hidden');
					}
				}
				else{
					$('#allbodyPart').addClass('hidden');
					for(i=0;i<11;i++)
						$('#bodyPart'+i).addClass('hidden');
				}
			}
			function listOfarea(valBody){
				if(valBody){
					$('#listBodyArea').val(valBody);
					$('#allbodyArea').removeClass('hidden');
					for(i=0;i<9;i++){
						if(valBody == i)
							$('#bodyArea'+i).removeClass('hidden');
						else
							$('#bodyArea'+i).addClass('hidden');
					}
				}
				else{
					$('#allbodyArea').addClass('hidden');
					for(i=0;i<9;i++)
						$('#bodyArea'+i).addClass('hidden');
				}
			}*/
			function displayAlert(alertMessage,type,location,pos){
				//$('#alert_show').click(function(){
	
					Metronic.alert({
						container: location,//$('#alert_container').val(), // alerts parent container(by default placed after the page breadcrumbs)
						place: pos,//$('#alert_place').val(), // append or prepent in container 
						type: type,//$('#alert_type').val(),  // alert's type
						message: alertMessage,//$('#alert_message').val(),  // alert's message
						close: true,//$('#alert_close').is(":checked"), // make alert closable
						reset: false,//$('#alert_reset').is(":checked"), // close all previouse alerts first
						focus: true,//$('#alert_focus').is(":checked"), // auto scroll to the alert after shown
						closeInSeconds:5, //$('#alert_close_in_seconds').val(), // auto close after defined seconds
						icon: ''//$('#alert_icon').val() // put icon before the message
					});
	
				//});
			}
			jQuery(document).ready(function() {
				Metronic.init();
				Main.init();
				UINestable.init();
				
				jQuery(document).on('click','.view-benchmark',function(){
					jQuery('.benchmark-details').css('display','none');
					jQuery('.close-benchmark-view').css('display','block');
					jQuery('.view-benchmark').css('display','none');
					var benchmarkId = jQuery(this).attr('benchmark-id');
					jQuery('#benchmark-details-'+benchmarkId).css('display','block');
				});
				jQuery(document).on('click','.close-benchmark-view',function(){
					jQuery('.close-benchmark-view').css('display','none');
					jQuery('.view-benchmark').css('display','inline-block');
					jQuery('.benchmark-details').css('display','none');
				});
				jQuery('.benchmarkAutoTime').click(function(){
					jQuery('.benchmarkTimeManual').toggle();
					jQuery('.benchmarkAutoTime').hide();
					jQuery('.benchmarkManualTime').show();
					jQuery('.benchmarkDay').val("");
					jQuery('.benchmarkHour').val("");
					jQuery('.benchmarkMinute').val("");
					jQuery('#time_type').val("auto");
				});
				jQuery('.benchmarkManualTime').click(function(){
					jQuery('#time_type').val("manual");
					jQuery('.benchmarkTimeManual').toggle();
					jQuery('.benchmarkManualTime').hide();
					jQuery('.benchmarkAutoTime').show();
					jQuery('.benchmarkDay').datepicker();
				});
				jQuery('.extraBenchmarks').click(function(){
					jQuery('.extraMeasurements').toggle();
				});
				jQuery('#showBenchmarkBox').click(function(){
					jQuery(this).hide();
					jQuery('#hideBenchmarkBox').show();
					jQuery('#createBenchmark').show();
				});
				jQuery('#hideBenchmarkBox').click(function(){
					jQuery(this).hide();
					jQuery('#showBenchmarkBox').show();
					jQuery('#createBenchmark').hide();
				});

				jQuery('.add-benchmark-submit').click(function(form){
					form.preventDefault();
					jQuery('.add-benchmark-submit i').show();
					jQuery(this).addClass('disabled');
					jQuery('.benchmarkError').hide();
					jQuery('.benchmarkDayError').hide();
					jQuery('.benchmarkHourError').hide();
					jQuery('.benchmarkMinError').hide();
					
					jQuery('.benchmarkDay').css('border','1px solid #eee');
					jQuery('.benchmarkHour').css('border','1px solid #eee');
					jQuery('.benchmarkMinute').css('border','1px solid #eee');
					jQuery('.measurements_waist').css('border','1px solid #eee');
					jQuery('.measurements_hips').css('border','1px solid #eee');
					jQuery('.measurements_height').css('border','1px solid #eee');
					jQuery('.measurements_weight').css('border','1px solid #eee');
		
					console.log('add-benchmark-submit clicked');
					var formData = {}
					jQuery.each(jQuery('.benchmark-save').serializeArray(), function(i, obj) { 
						formData[obj.name] = obj.value; 
					});
		
					var time_type = jQuery('#time_type').val();
					var time_error = false;
					var time_msg = {};
					
					if(time_type == 'manual'){
						if(jQuery('.benchmarkDay').val() == null || jQuery('.benchmarkDay').val() == ""){
							jQuery('.benchmarkError').show();
							jQuery('.benchmarkDayError').show();
							//console.log('day empty');
							time_error = true;
							jQuery('.benchmarkDay').css('border','1px solid red');
							time_msg['error'] = time_msg['error']+'Check day value';
						}
						if(jQuery('.benchmarkHour').val() == null || jQuery('.benchmarkHour').val() == ""){
							//console.log('hour empty');
							jQuery('.benchmarkError').show();
							jQuery('.benchmarkHourError').show();
							time_error = true;
							jQuery('.benchmarkHour').css('border','1px solid red');
							time_msg['error'] = time_msg['error']+' Check hour value';
						}
						if(jQuery('.benchmarkMinute').val() == null || jQuery('.benchmarkMinute').val() == ""){
							//console.log('min empty');
							jQuery('.benchmarkError').show();
							jQuery('.benchmarkMinError').show();
							time_error = true;
							jQuery('.benchmarkMinute').css('border','1px solid red');
							time_msg['error'] = time_msg['error']+'Check min value';
						}	
					}
		
					var waist = jQuery('.measurements_waist').val();
					var hips = jQuery('.measurements_hips').val();
					var height = jQuery('.measurements_height').val();
					var weight = jQuery('.measurements_weight').val();
		
					var stress = jQuery('.benchmark_stress').val();
					var sleep = jQuery('.benchmark_sleep').val();
					var nutrition = jQuery('.benchmark_nutrition').val();
					var hydration = jQuery('.benchmark_hydration').val();
					var humidity = jQuery('.benchmark_humidity').val();
					/********/
					if (stress =='') {
						//console.log('enter stress');
						time_error = true;
						jQuery('.benchmark_stress').css('border','1px solid red');
						jQuery('.benchmarkError').show();
						jQuery('.benchmarkStressError').show();
					}
					if (sleep =='') {
						console.log('enter sleep');
						time_error = true;
						jQuery('.benchmark_sleep').css('border','1px solid red');
						jQuery('.benchmarkError').show();
						jQuery('.benchmarkSleepError').show();
					}
					if (nutrition =='') {
						console.log('enter nutrition');
						time_error = true;
						jQuery('.benchmark_nutrition').css('border','1px solid red');
						jQuery('.benchmarkError').show();
						jQuery('.benchmarkNutritionError').show();
					}
					if (hydration =='') {
						console.log('enter hydration');
						time_error = true;
						jQuery('.benchmark_hydration').css('border','1px solid red');
						jQuery('.benchmarkError').show();
						jQuery('.benchmarkHydrationError').show();
					}
					if (humidity =='') {
						console.log('enter humidity');
						time_error = true;
						jQuery('.benchmark_humidity').css('border','1px solid red');
						jQuery('.benchmarkError').show();
						jQuery('.benchmarkHumidityError').show();
					}
		
					/*******/
		
					if (waist =='') {
						console.log('enter waist');
						time_error = true;
						jQuery('.measurements_waist').css('border','1px solid red');
						jQuery('.benchmarkError').show();
						jQuery('.benchmarkWaistError').show();
					}
		
					if (hips =='') {
						console.log('enter hips');
						time_error = true;
						jQuery('.measurements_hips').css('border','1px solid red');
						jQuery('.benchmarkError').show();
						jQuery('.benchmarkHipsError').show();
					}
		
					if (height =='') {
						console.log('enter height');
						time_error = true;
						jQuery('.measurements_height').css('border','1px solid red');
						jQuery('.benchmarkError').show();
						jQuery('.benchmarkHeightError').show();
					}
		
					if (weight =='') {
						console.log('enter weight');
						time_error = true;
						jQuery('.measurements_weight').css('border','1px solid red');
						jQuery('.benchmarkError').show();
						jQuery('.benchmarkWeightError').show();
					}
		
					if(time_error){
						jQuery('.add-benchmark-submit i').hide();
						jQuery('.add-benchmark-submit').removeClass('disabled');
						//jQuery('.benchmarkError').show().html(JSON.stringify(time_msg['error']));
						//console.log(JSON.stringify(time_msg));
					}
		
					if(!time_error){
						console.log('no error going go submit:');
						console.log('Sending formData: '+formData);
						jQuery.ajax({
							url: '{{ url("/") }}/benchmarks',
							type: 'POST',
							data: {
								'formData':formData,
								'_token':jQuery('input[name=_token]').val()
							},
							success: function(data) {
								var responseMsg = $.parseJSON(data);
								if(responseMsg.status == 'success'){
									displayAlert('Benchmark Saved','success','#displayBenchmarks','prepend');//null
									jQuery('.add-benchmark-submit i').hide();
									jQuery('.add-benchmark-submit').removeClass('disabled');
									jQuery('.benchmarkDay').val("");
									jQuery('.benchmarkHour').val("");
									jQuery('.benchmarkMinute').val("");
									jQuery('.measurements_waist').val("");
									jQuery('.measurements_hips').val("");
									jQuery('.measurements_height').val("");
									jQuery('.measurements_weight').val("");
									jQuery('.benchmark_stress').removeAttr('checked');
									jQuery('.benchmark_sleep').removeAttr('checked');
									jQuery('.benchmark_nutrition').removeAttr('checked');
									jQuery('.benchmark_hydration').removeAttr('checked');
									jQuery('.benchmark_humidity').removeAttr('checked');
									jQuery('.measurements_pressups').val("");
									jQuery('.measurements_plank').val("");
									jQuery('.measurements_timetrial3k').val("");
									jQuery('.measurements_cardiobpm1').val("");
									jQuery('.measurements_cardiobpm2').val("");
									jQuery('.measurements_cardiobpm3').val("");
									jQuery('.measurements_cardiobpm4').val("");
									jQuery('.measurements_cardiobpm5').val("");
									jQuery('.measurements_cardiobpm6').val("");
									jQuery('#hideBenchmarkBox').hide();
									jQuery('#showBenchmarkBox').show();
									jQuery('#createBenchmark').hide();
								
									var current_client = $('#client_id').val();
									//$( "#showBenchmarks" ).load( "http://epictrainer.com/showbenchmarks/"+current_client);
									/*** load benchmarks ***/
		
									jQuery.ajax({
										url: 'http://epictrainer.com/showbenchmark/'+current_client,
										type: 'GET',
										data: {
											'_token':jQuery('input[name=_token]').val()
										},
										success: function(loadedBenchmarks) {
		
										$('#displayBenchmarks').empty().append(loadedBenchmarks);
										/*
											var responseMsg = $.parseJSON(data);
											if(responseMsg.status == 'success'){
												displayAlert('Benchmark Saved','success','#displayBenchmarks','prepend');//null
		
												$( "#showBenchmarks" ).load("http://epictrainer.com/showbenchmarks/"+current_client, function(loadData) {
												   console.log('loaded benchmarks');
												   console.log(loadData);
												});        		
											} else {
												displayAlert('Error saving benchmark, please try again or contact support','warning','#displayBenchmarks','prepend');
												jQuery('.add-benchmark-submit i').hide();
												jQuery('.add-benchmark-submit').removeClass('disabled');
											}
										
										*/
										},
										error: function(jqXHR, textStatus, errorThrown){
											console.log(textStatus);
										}
									});
									/*** end load benchmarks ***/	
					
								} else {
									displayAlert('Error saving benchmark, please try again or contact support','warning','#displayBenchmarks','prepend');
									jQuery('.add-benchmark-submit i').hide();
									jQuery('.add-benchmark-submit').removeClass('disabled');
								}
							
							//jQuery('#addClient').modal('hide');
							console.log(data);
							//location.reload();
							},
							error: function(jqXHR, textStatus, errorThrown){
		
							}
						});
						
					}
					
				});
				
				$( ".panel-collapse.closed" ).trigger( "click" );

				$('#client-datatable').on('init', function(){
				    if(typeof cookieSlug != 'undefined' && cookieSlug != null){
						var datatableRowsCountDd = $('#client-datatable_length select');
						setDatatableRowsAmount(datatableRowsCountDd, cookieSlug);
						datatableRowsCountDd.on("change", function(){
							var cookieName = calcTable_lengthCookieName(cookieSlug); 
							$.cookie(cookieName, datatableRowsCountDd.val())
						});   
					}
				}).dataTable({
					"iDisplayStart":getRecord_indexCookieVal()
				});
				$('#client-datatable').on('draw.dt', function(){
					initSelectpicker($('#client-datatable select'));
				});
				
                FormWizard.init();
				FormWizardBenchMark.init('#benchmarkWizard');
				//runFullCalendar();
				$('.client-delete').on('click', function(e) {
					var client_id = $(this).attr('data-client-delete');
					if (client_id.length > 0) {

					};
				});
				$('#calendarDatepicker').datepicker();
				$('#calendarDatepicker').on("changeDate", function() {
				    $('#myCalendarDatepicker').val(
				        $('#datepicker').datepicker('getFormattedDate')
				    );
				});
				//Index.init();
				jQuery("#mcfTosmc").click(function (e) {
					e.preventDefault();
				    jQuery("#smc").toggle();
				    jQuery("#mcf").toggle();
				     
				});
			});
		</script>
        @yield('script')
</body>
	<!-- end: BODY -->
</html>