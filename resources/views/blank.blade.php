<!DOCTYPE html>
<!-- Template Name: Clip-One - Responsive Admin Template build with Twitter Bootstrap 3.x Version: 1.4 Author: ClipTheme -->
<!--[if IE 8]><html class="ie8 no-js" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9 no-js" lang="en"><![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
	<!--<![endif]-->
	<head>
		<title>@yield('title', app_name())</title>
		<!-- start: META -->
		<meta charset="utf-8" />
		<meta name="_token" content="{{ csrf_token() }}" />
        <meta name="public_url" content="{{ asset('') }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
    
		<!-- end: META -->

		<!-- start: GOOGLE FONTS -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
        <meta name="apple-mobile-web-app-capable" content="yes">

        <meta name="twitter:card" content="summary_large_image">
        <meta property="og:site_name" content="Scotch">
        <meta property="og:url" content="https://epic.testingserver.in">  
        <meta property="og:type" content="website"> 
        <meta property="og:type" content="article">
        <meta property="og:title" content="Scotch Web Development">
        <meta property="og:description" content="Scotch is a web development blog discussing all things programming, development, web, and life.">
        <meta property="og:image" content="http://epic.testingserver.in/category-images/01611093793.png">
        <meta property="fb:app_id" content="1307117479669004">
        <meta name="twitter:site" content="https://epic.testingserver.in">

        {{-- <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:site" content="@nytimes">
        <meta name="twitter:creator" content="@SarahMaslinNir">
        <meta name="twitter:title" content="Parade of Fans for Houston’s Funeral">
        <meta name="twitter:description" content="NEWARK - The guest list and parade of limousines with celebrities emerging from them seemed more suited to a red carpet event in Hollywood or New York than than a gritty stretch of Sussex Avenue near the former site of the James M. Baxter Terrace public housing project here.">
        <meta name="twitter:image" content="http://graphics8.nytimes.com/images/2012/02/19/us/19whitney-span/19whitney-span-articleLarge.jpg"> --}}
	    <!-- end: GOOGLE FONTS -->
        
        <!-- start: FONTS AWSOME-->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
        <!-- start: FONTS AWSOME-->

		<!-- start: MAIN CSS -->
		{!! Html::style('vendor/bootstrap/css/bootstrap.min.css?v='.time()) !!}
		{!! Html::style('vendor/fontawesome/css/font-awesome.min.css?v='.time()) !!}
        <!--[if IE 7]>
		<link rel="stylesheet" href="{{ asset('assets/plugins/font-awesome/css/font-awesome-ie7.min.css') }}">
		<![endif]-->
		{!! Html::style('assets/fonts/style.css?v='.time()) !!}

         @yield('invoice')
         
		<!-- {!! HTML::style('assets/css/invoice.css?v='.time()) !!}  -->
		{!! Html::style('vendor/themify-icons/themify-icons.min.css?v='.time()) !!}
		{!! Html::style('vendor/animate.css/animate.min.css?v='.time(), ['media' => 'screen']) !!}
		{!! Html::style('vendor/perfect-scrollbar/perfect-scrollbar.min.css?v='.time(), ['media' => 'screen']) !!}
		{!! Html::style('vendor/switchery/switchery.min.css?v='.time(), ['media' => 'screen']) !!}
		{!! Html::style('vendor/tooltipster-master/tooltipster.css?v='.time()) !!}
		{!! Html::style('vendor/jquery-ui/jquery-ui-1.10.1.custom.css?v='.time()) !!}
        {!! Html::style('vendor/bootstrap-select-master/css/bootstrap-select.min.css') !!}
		<!-- end: MAIN CSS -->

		<!-- start: CLIP-TWO CSS -->
		{!! Html::style('assets/css/styles-orange.css?v='.time()) !!}
		{!! Html::style('assets/css/custom-style.css?v='.time()) !!}
		{!! Html::style('assets/css/clip-two/main-navigation.css?v='.time()) !!}
		{!! Html::style('assets/css/plugins.css?v='.time()) !!}

		@yield('plugin-css')

		{!! Html::style('assets/css/themes/theme-orange.css?v='.time()) !!}
		<!-- end: CLIP-TWO CSS -->
        {!! Html::style('assets/css/theme_light.css?v='.time()) !!}
        {!! Html::style('assets/css/print.css?v='.time(), ['media' => 'print']) !!}
        {!! Html::style('assets/plugins/fullcalendar/fullcalendar/fullcalendar.css') !!}
        {!! Html::style('vendor/fullcalendar/fullcalendar.min.css?v='.time()) !!}
        <!--{!! Html::style('assets/plugins/datepicker/css/datepicker.css?v='.time()) !!}-->
        {!! Html::style('assets/plugins/select2/select2.css?v='.time()) !!}
        {!! Html::style('assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css') !!}
        {!! Html::style('assets/plugins/intl-tel-input-master/build/css/intlTelInput.css') !!}
        {!! Html::style('assets/plugins/nestable-cliptwo/jquery.nestable.css?v='.time()) !!}
        {!! Html::style('assets/plugins/Jcrop/css/jquery.Jcrop.min.css?v='.time()) !!}
		{!! Html::style('vendor/sweetalert/sweet-alert.css') !!}
		{!! Html::style('assets/plugins/lightbox/lightbox.css') !!}
        
		<!-- Start: NEW timepicker css -->  
    	{!! Html::style('assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') !!}
    	<!-- End: NEW timepicker css -->
        
        <!-- start - FITNESS PLANNER CSS -->
        {!! HTML::style('fitness-planner/css/pt-planner.css?v='.time()) !!}
        {!! HTML::style('fitness-planner/css/jquery.ui.labeledslider.css?v='.time()) !!}
        {!! HTML::style('fitness-planner/custom/style.css?v='.time()) !!}
		<link rel='stylesheet' href='http://api.onesportevent.com/devapi/css/v1/core_t5.css?ver=4.3.5' type='text/css' />
		<!-- end - FITNESS PLANNER CSS -->

		<!-- start: Rating -->
		{!! Html::style('assets/plugins/bootstrap-rating/bootstrap-rating.css') !!}
		<!-- end: Rating -->
        
        <!-- start - SUBVIEW CSS -->
        {!! Html::style('assets/plugins/summernote/dist/summernote.css?v='.time()) !!}
        <!-- end - SUBVIEW CSS -->
        
        {!! Html::style('assets/css/custom.css?v='.time()) !!}

        <!-- start: Jquery File Upload -->
		{!! Html::style('assets/plugins/jquery-file-upload2/css/jquery.fileupload-ui.css') !!}
		<!-- end: Jquery File Upload -->
		
		<link rel="shortcut icon" href="favicon.ico" />
        {!! Html::script('vendor/jquery/jquery.min.js') !!}
        {!! Html::script('assets/js/jquery-ui.min.js') !!}
        {!! Html::script('assets/plugins/jquery-migrate/jquery-migrate-1.2.1.min.js') !!}
        {!! Html::script('vendor/sweetalert/sweet-alert.min.js') !!}

		@yield('required-styles')
		<style>
		.highlight{
			background: #14ff3045 !important;
			color:white !important;
		}</style>
		{{-- <script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=60056b094c4dcc0018f14681&product=inline-share-buttons' async='async'></script> --}}
	</head>
	<!-- start: BODY -->
	<body onload="getNotify()">
		<!-- Start: Lock screen alert -->
		@include('includes.partials.lock_screen_alert')
		<!-- End: Lock screen alert -->
		
		<!-- Start: Lock screen html --> 
		<div id="lock-screen-div" class="{{ (Session::has('lockstatus'))?'':'hidden'}} lock-screen-main">   
		   @include('lock_screen',['user'=>Auth::user()])
		</div>
		<!-- End: Lock screen html -->

		<!-- Start: This hidden field for only resedule event action -->
	    {!! Form::hidden('resheduleAppoint') !!}
	    <!-- Start: This hidden field for only resedule event action -->
    
		<div id="calPopupHelper" class="hidden"></div>
		@include('includes.partials.waiting_shield')
		{!! Form::hidden('upcomingTasks', null) !!}
		
		<div id="app" class="app-sidebar-closed a-s-c {{ Route::getCurrentRoute()->uri() == 'clients/{filter?}'?'overflowFix':'' }}">
         @show
			<!-- side bar -->
            @include( session()->get('adminData')->account_type == 'Super Admin' ? 'super-admin.layout.nav' : 'layouts.includes.sidebar')

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

			<!--Start: Modal for upcoming task reminder -->
	        @include('includes.partials.reminder_modal')
	        <!--End: Modal for upcoming task reminder -->

	        <!-- Start: Modal for expried service -->
           	@include('includes.partials.expired_service_modal')
        	<!-- Start: Modal for expried service -->    
		</div>

		<!-- Start: Script -->
		{!! Html::script('vendor/bootstrap/js/bootstrap.js?v='.time()) !!}
		{!! Html::script('vendor/jquery-cookie/jquery.cookie.js?v='.time()) !!}
		{!! Html::script('vendor/perfect-scrollbar/perfect-scrollbar.min.js?v='.time()) !!}
		{!! Html::script('vendor/switchery/switchery.min.js?v='.time()) !!}
		{!! Html::script('vendor/moment/moment.min.js?v='.time()) !!}
        {!! Html::script('vendor/moment/moment-timezone-with-data.js?v='.time()) !!}
        {!! Html::script('https://cdnjs.cloudflare.com/ajax/libs/moment-range/2.2.0/moment-range.min.js') !!}
		{!! Html::script('vendor/jquery-validation/jquery.validate.min.js?v='.time()) !!}
		{!! Html::script('vendor/bootstrap-select-master/js/bootstrap-select.min.js?v='.time()) !!}
        {!! Html::script('vendor/tooltipster-master/jquery.tooltipster.min.js?v='.time()) !!}

		{!! Html::script('assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js?v='.time()) !!}
		{!! Html::script('assets/plugins/blockUI/jquery.blockUI.js?v='.time()) !!}
		{!! Html::script('assets/plugins/iCheck/jquery.icheck.min.js?v='.time()) !!}
		{!! Html::script('assets/plugins/perfect-scrollbar/src/jquery.mousewheel.js?v='.time()) !!}
		{!! Html::script('assets/plugins/bootstrap-colorpalette/js/bootstrap-colorpalette.js?v='.time()) !!}
		{!! Html::script('assets/plugins/jQuery-Smart-Wizard/js/jquery.smartWizard.js?v='.time()) !!}
		{!! Html::script('assets/plugins/fullcalendar/fullcalendar/fullcalendar.js?v='.time()) !!}
		{!! Html::script('assets/plugins/select2/select2.min.js?v='.time()) !!}
		{!! Html::script('assets/plugins/DataTables/media/js/jquery.dataTables.min.js?v='.time()) !!}
		{!! Html::script('assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js?v='.time()) !!}
		{!! Html::script('assets/plugins/DataTables/media/js/dataTableDateSort.js?v='.time()) !!}
		{!! Html::script('assets/plugins/intl-tel-input-master/build/js/utils.js?v='.time()) !!}
		{!! Html::script('assets/plugins/intl-tel-input-master/build/js/intlTelInput.js?v='.time()) !!}
		{!! Html::script('assets/plugins/nestable-cliptwo/jquery.nestable.js?v='.time()) !!}
		{!! Html::script('assets/plugins/nestable-cliptwo/nestable.js?v='.time()) !!}
		{!! Html::script('assets/plugins/Jcrop/js/jquery.Jcrop.min.js?v='.time()) !!}
		{!! Html::script('assets/plugins/Jcrop/js/script.js?v='.time()) !!}
		{!! Html::script('assets/plugins/Jcrop/js/posture-script.js?v='.time()) !!}

		{!! Html::script('assets/js/main.js?v='.time()) !!}
		{!! Html::script('assets/js/metronic.js?v='.time()) !!}
		{!! Html::script('assets/js/clients-notification.js?v='.time()) !!}
		{!! Html::script('assets/plugins/lightbox/lightbox.js') !!}
        {!! Html::script('result/js/webcam.js?v='.time()) !!}
        
		<!-- <script src="https://webrtc.github.io/adapter/adapter-latest.js"></script> -->
		<!-- {!! Html::script('result/js/webrtc.js') !!} -->

		<script>
			var currTimeZone= '<?php echo (Session::has('timeZone'))?Session::get('timeZone'):''; ?>';
			$.ajaxSetup({
		        headers: {
		            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
		        }
		    });
			
			function displayAlert(alertMessage,type,location,pos){
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
			}

			/* Auth user information */
			var loggedInUser = {
		        type: '{{ Auth::user()->account_type }}',
		        id: '{{ Auth::user()->account_id }}',
		        userId: '{{ Auth::id() }}',
		        name: '{{ Auth::user()->fullName }}'
	    	};

			jQuery(document).ready(function() {
				Metronic.init();
				Main.init();
				UINestable.init();

				@yield('script-handler-for-this-page')
	
				$( ".panel-collapse.closed" ).trigger( "click" );

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

				/* if business then get upcoming task */
				var businessExist= '<?php echo Session::has('businessId'); ?>';
                if(businessExist){
                    getUpcomingTasks();
                    getExpriedService();
                }
			});
		</script>

<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> -->
<script src="https://parsleyjs.org/dist/parsley.js"></script>
<script>
    var constraints = '';
    var modal_type = '';
    var modal_id = '';
    var url = window.location.href;
    var index = url.indexOf("#");
    if (index !== -1) 
    {
    var hash = url.substring(index + 1);
    }

    if(hash == 'Before')
    {         
        $("#Before").addClass('active');
        $("#Gallery").removeClass('active');
        $('#before-after-tab').trigger('click');
    }
    else if(hash == 'Gallery')
    {
        $("#Before").removeClass('active');
        $("#Gallery").addClass('active');
        $('#gallery-tab').trigger('click');
    }

    $("#gallery-tab").click(function() 
    {
        // window.location.hash = 'Gallery';    
    }); 
    $("#before-after-tab").click(function() 
    {
        // window.location.hash = 'Before';    
    });

    $('input').parsley();
    // $(window).load(function() {
    // $(".waitingShield").show();
    // setTimeout(function () 
    // {
    //     $(".waitingShield").hide();   
    // },3000);
    // });
    setTimeout(function() {
    $('#success_message').fadeOut("slow");
    }, 3000 );
    var validImageTypes = ["image/jpeg","image/png","image/jpg"];
    
    $("#upload-gallery-images").change(function(){
    $("#valid_file_msg").hide();
    $('#preview-gallery-images').html("");

    var total_file=document.getElementById("upload-gallery-images").files.length;
    var valid_file = true;
    for(var i=0;i<total_file;i++)
    {
        var file = event.target.files[i];
        var fileType = file['type'];
        if($.inArray(fileType, validImageTypes) < 0)
        {
            valid_file = false;
        }
        else
        {
            $('#preview-gallery-images').append("<li><div class='row'><div class='col-md-3'><img src='"+URL.createObjectURL(event.target.files[i])+"' class='img-fluid'></div><div class='col-md-8'><input type='text' name='images_name[]' class='form-control' required  data-parsley-trigger='focusout' data-parsley-required-message='Image name is required' placeholder='Enter image name'> </div></div></li>");
        }
    }
        if(valid_file === true)
        {
            $("#gallery-images-modal").modal();      
        }
        else
        {
            $('#preview-gallery-images').html("");
            $("#valid_file_msg").show();
        }
    });
    $('#upload-images-btn').click(function () 
    {
        var isValid = true;
        $('#gallery-images-modal input').each( function() 
        {
            if ($(this).parsley().validate() !== true)
            {
                isValid = false;
            }
        });
        if(isValid)
        {
            $("#images_name").val($("input[name='images_name[]']").map(function(){return $(this).val();}).get());
            $('#add-gallery-image-form').submit();
            $(".waitingShield").show();
        }
    });

 /* Take photo from camera js */
    Webcam.set({
        width: 320,
        height: 240,
        image_format: 'jpeg',
        jpeg_quality: 90,
        constraints: constraints,
    });
    $('.openWebcam').on('click',function(e) {
        capture_image_type = $(this).attr('data-item');
        if(capture_image_type === 'before' || capture_image_type === 'after' )
        {
            $('#'+capture_image_type+'-msg').empty();
            $('#'+capture_image_type+'-image-upload').val('');
        }
    $('#webcam-modal').modal('show');
    Webcam.attach('#camera');
    });
    $('.snap').on('click',function()
    {
        Webcam.snap(function(data_uri) 
        {
            $('#'+capture_image_type+'_success_message').empty();
            $('#'+capture_image_type+'-image-capture').val(data_uri);
            Webcam.reset();
            $('#webcam-modal').modal('hide');
            $('<span>'+'Photo uploaded successfully'+'</span>').appendTo("#"+capture_image_type+"_success_message").css('color','green');
        });
    });
    $('.close-webcam').click(function(){
        Webcam.reset();
        $('#webcam-modal').modal('hide');
    });

    /* Manage image uload after and before */
    $('.add-before-after-btn').on('click',function(){
        modal_type = $(this).attr('data-type');
        title = $(this).attr('data-item');
        var before_after_id = $(this).attr('data-id');
        if(modal_type == 'edit')
        {
            $("#before-after-id").val(before_after_id);
            $("input[name='title']").val(title);
        }
        else
        {
            $("#before-after-id").val('');
            $("input[name='title']").val('');
        }
        $('#add-before-after-modal').modal();
    });
    var upload_image_type = ''
    $("input[type='file']").on('click',function()
    {
        upload_image_type = $(this).attr('data-item');
        upload_image_medium = $(this).attr('data-type');
        if(upload_image_type === 'before' || upload_image_type === 'after' )
        {
            $('#'+upload_image_type+'-image-'+upload_image_medium).change(function()
            {
                var validImageTypes = ["image/jpeg", "image/png","image/jpg"];
                var file = this.files[0]
                var fileType = file['type'];
                if($.inArray(fileType, validImageTypes) < 0)
                {
                    $('#'+upload_image_type+'_success_message').empty();
                   $('#'+upload_image_type+'-msg').empty();
                    $('#'+upload_image_type+'-image-upload').val('');
                    $('#'+upload_image_type+'-image-capture').val('');
                   $('<p>' + 'Image must be png,jpg,jpge type' + '</p>').appendTo("#"+upload_image_type+"-msg").css('color','red');
                }
                else
                {
                    $('#'+upload_image_type+'_success_message').empty();
                    $('<span>'+'Photo uploaded successfully'+'</span>').appendTo("#"+upload_image_type+"_success_message").css('color','green');
                    $('#'+upload_image_type+'-msg').empty();
                    if(upload_image_medium == 'upload')
                    {
                        $('#'+upload_image_type+'-image-capture').val(null);   
                    }
                    else
                    {
                        $('#'+upload_image_type+'-image-upload').val(null);   

                    }
                }

            });
        }
    });

    $('.before-after-save-btn').click(function () 
    {
        $("#before-msg").empty();
        $("#after-msg").empty();
        var  before_available = true;
        var  after_available = true;
        var  before_image_upload = $("#before-image-upload").val();
        var  before_image_capture = $("#before-image-capture").val();
        var  after_image_upload = $("#after-image-upload").val();
        var  after_image_capture = $("#after-image-capture").val();
        if(modal_type == 'add')
        {
            if(before_image_upload == '' && before_image_capture == '')
            {
                // before_available = false;
                // $('<p>' + 'Before image is required' + '</p>').appendTo("#before-msg").css('color','red');
                
            }
            if(after_image_upload == '' && after_image_capture == '')
            {
                // after_available = false;
                // $('<p>' + 'After image is required' + '</p>').appendTo("#after-msg").css('color','red');

            }   
        }
        var isValid = true;
        $('#add-before-after-modal input').each( function() 
        {
            if ($(this).parsley().validate() !== true)
            {
                isValid = false;
            }
        });
        if(isValid && before_available && after_available)
        {
            $('#add-before-after-form').submit();
            $(".waitingShield").show();
        }
    });

    $('#add-before-after-modal').on('hidden.bs.modal', function () 
    {
        $('#after-msg').empty();
        $('#before-msg').empty();
        $('.parsley-required').empty();
        $('#before_success_message').empty();
        $('#after_success_message').empty();
        $('#before-image-upload').val('');
        $('#after-image-upload').val('');
        $('#before-image-capture').val('');
        $('#after-image-capture').val('');
    });
    
    /* View afer-before uploaded image*/
    $('.view-after-before').on('click',function(){
        var modal_id = $(this).attr('data-id');
        $('#view-after-before-modal'+modal_id).modal();
    });


    /* --------- delete before-after --------------------*/
    $(document).on('click','.delete-before-after-btn', function ()
    {
        var before_after_id = $(this).attr('data-id');
        swal({
        title: "Do you want to delete?",
					type: 'warning',
					allowEscapeKey: false,
					showCancelButton: true,
					confirmButtonText: 'Yes',
					cancelButtonText:'No',
					confirmButtonColor: '#ff4401'
        },
        function(isConfirm) {
        if(isConfirm) 
        { 
            $.ajax({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            method:"POST",
            url:"{{url('client/delete-before-after')}}",
            data : 
            { 
                before_after_id : before_after_id
            },
            success: function(data) {
                if(data.status == true)
                {
                    swal("Deleted!", "Your imaginary file has been deleted.", "success");
                    location.reload();
                }
            }
            });   
        } 
        else{
       		swal("Cancelled", "Your file is safe", "error");
        }
        });
    });	

    // setTimeout(function(){
    //         $('.alert-success').html('');
    //    }, 2000);

</script>

		{!! Html::script('assets/js/set-moment-timezone.js') !!} 
		{!! Html::script('assets/js/lock-screen.js') !!}

		<!-- Script for this page -->
		@yield('script')
	<!-- 	<script type="text/javascript">

		 <script type="text/javascript">

			$(document).ready(function(){


			var d = new Date();
			var n = d.getTime();
			$('script').each(function() {
				var attrSrc = $(this).attr("src");
				if(attrSrc != undefined && attrSrc != ''){
					var src = $.trim($(this).attr('src'));
					$(this).attr('src', src+'?v='+n);
				}
			});
				});

		</script>
 -->

		</script> 

	</body>
	<!-- end: BODY -->
</html>