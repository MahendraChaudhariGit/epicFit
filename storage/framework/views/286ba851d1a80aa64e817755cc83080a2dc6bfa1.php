<!DOCTYPE html>
<html>
<head>
    <title>EPIC Result</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="_token" content="<?php echo e(csrf_token()); ?>" />
    <meta name="public_url" content="<?php echo e(asset('')); ?>">
    <meta name="description" content="<?php echo $__env->yieldContent('meta_description', ''); ?>">
    <meta name="author" content="<?php echo $__env->yieldContent('meta_author', ''); ?>">
    <?php echo $__env->yieldContent('meta'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('result/parq-theme/style.css?v='.time())); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('result/plugins/fontawesome/css/font-awesome.min.css')); ?>">
    
    <?php echo Html::style('result/plugins/sweetalert/sweet-alert.css'); ?>

    <?php echo Html::style('result/plugins/bootstrap/css/bootstrap.min.css'); ?>

    <?php echo Html::style('result/plugins/tooltipster-master/tooltipster.css'); ?>


    <!-- start: Summernote -->
    <?php echo Html::style('result/plugins/summernote/dist/summernote.css'); ?>

    <!-- end: Summernote -->
    <?php echo Html::style('result/plugins/bootstrap-select-master/css/bootstrap-select.min.css'); ?>

    <?php echo Html::style('result/plugins/intl-tel-input-master/build/css/intlTelInput.css'); ?>

    <?php echo Html::style('result/plugins/bootstrap-datepicker/css/datepicker.css'); ?>

    <?php echo Html::style('result/plugins/nestable-cliptwo/jquery.nestable.css'); ?>

    <?php echo Html::script('result/plugins/jquery/jquery.min.js'); ?>

    <?php echo Html::script('result/plugins/jquery/jquery-ui.min.js'); ?>

    
    <!-- Start: NEW datetimepicker css -->
    <?php echo Html::style('assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css'); ?>

    <?php echo Html::style('assets/plugins/bootstrap-material-datetimepicker/css/custom-css-style.css'); ?>


    <?php echo Html::style('result/plugins/sweetalert/sweet-alert.css'); ?>

    <?php echo Html::script('result/js/jquery.tooltipster.min.js'); ?>


    <?php echo Html::style('result/plugins/Jcrop/css/jquery.Jcrop.min.css'); ?>

    <?php echo Html::style('result/plugins/dropzone/dropzone.css'); ?>

    <?php echo Html::style('result/plugins/dropzone/cropper.css'); ?>


    <?php echo Html::style('result/css/custom.css?v='.time()); ?>

    
    <?php echo Html::style('result/css/autocomplete.css'); ?>

    <?php echo $__env->yieldContent('required-styles'); ?>

    </head>
    <body>
        <div id="waitingShield" class="text-center">
    <div>
        <i class="fa fa-circle-o-notch"></i>
    </div>
</div>
<script type="text/javascript">
    var BASE_URL = "<?php echo e(url('/')); ?>";
</script>
    <?php echo $__env->yieldContent('content'); ?>
    
    <!-- <div class="back_to_goal_list backto_dashboard">
        <button type="button" name="" class="btn">Back to Goal List</button>
    </div> -->
    <?php echo Html::script('result/plugins/moment/moment.min.js'); ?>


    <?php echo Html::script('result/plugins/bootstrap/js/tethr.js'); ?>

    <?php echo Html::script('result/plugins/bootstrap/js/bootstrap.js'); ?>

    <script src="<?php echo e(asset('result/js/jquery-3.2.1.min.js')); ?>"></script>
    <script src="<?php echo e(asset('result/parq-theme/common_scripts.min.js')); ?>"></script>
    <!-- <script src="<?php echo e(asset('result/parq-theme/goal_wizard.js?v='.time())); ?>"></script> -->
    <?php echo Html::script('result/js/jquery-ui.min.js'); ?>

    <?php echo Html::script('result/plugins/jquery-cookie/jquery.cookie.js'); ?>

    <?php echo Html::script('assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js'); ?>

    <!-- End: NEW datetimepicker js --> 
    <?php echo Html::script('result/js/webcam.js'); ?>

    <!-- start: Moment Library -->
    <?php echo Html::script('result/plugins/moment/moment.min.js'); ?>

    <!-- end: Moment Library -->

    <!-- start: Summernote -->
    <?php echo Html::script('result/plugins/summernote/dist/summernote.min.js'); ?>

    <!-- end: Summernote -->
    <!-- start: Rating -->
    <?php echo Html::script('result/plugins/bootstrap-rating/bootstrap-rating.min.js'); ?>

    <!-- end: Rating -->
    <!-- start: Bootstrap Typeahead -->
    <?php echo Html::script('result/plugins/bootstrap3-typeahead/js/bootstrap3-typeahead.min.js'); ?>  
    <!-- end: Bootstrap Typeahead --> 
    <!-- start: Bootstrap Typeahead -->
    <?php echo Html::script('result/plugins/jQuery-Smart-Wizard/js/jquery.smartWizard.js'); ?>  

    <?php echo Html::script('result/plugins/Jcrop/js/jquery.Jcrop.min.js'); ?>  
    <?php echo Html::script('result/plugins/Jcrop/js/script.js'); ?>  

    <!-- end: Bootstrap Typeahead --> 
    <!-- start: Bootstrap timepicker -->

    <?php echo Html::script('result/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js'); ?>

    <!-- end: Bootstrap timepicker -->
    <?php echo Html::script('result/js/autocomplete.js?v='.time()); ?>

    <?php echo Html::script('result/plugins/tooltipster-master/jquery.tooltipster.min.js'); ?>

    <?php echo Html::script('result/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>

    <?php echo Html::script('result/plugins/nestable-cliptwo/jquery.nestable.js'); ?>

    <?php echo Html::script('result/plugins/nestable-cliptwo/nestable.js'); ?>

    <!-- <?php echo Html::script('result/js/form-wizard-goal-buddy.js?v='.time()); ?> -->
    <!-- <?php echo Html::script('result/js/goal-buddy.js?v='.time()); ?> -->
    <link rel="stylesheet" type="text/css" 
     href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <?php echo Html::script('result/js/goal-process/manage-steps.js?v='.time()); ?>

    <?php echo Html::script('result/plugins/bootstrap-select-master/js/bootstrap-select.min.js'); ?>

    <?php echo Html::script('result/plugins/intl-tel-input-master/build/js/utils.js'); ?>

    <?php echo Html::script('result/plugins/intl-tel-input-master/build/js/intlTelInput.js'); ?>

    <?php echo Html::script('result/plugins/jquery-validation/jquery.validate.min.js'); ?>

    <?php echo Html::script('result/plugins/sweetalert/sweet-alert.min.js'); ?>

    <?php echo Html::script('result/plugins/dropzone/dropzone.js'); ?>


    <?php echo Html::script('result/plugins/dropzone/cropper.js'); ?>

    <?php echo Html::script('result/js/helper.js?v='.time()); ?>

    <?php echo Html::script('result/js/edit-field-realtime.js?v='.time()); ?>

    <?php echo Html::script('result/js/main.js?v='.time()); ?>


     <?php echo Html::script('result/plugins/tipped-tooltip/js/tipped/tipped.js'); ?>



    <script type="text/javascript">
        $.each(jQuery('textarea[data-autoresize]'), function() {
            var offset = this.offsetHeight - this.clientHeight;
             
            var resizeTextarea = function(el) {
                $(el).css('height', 'auto').css('height', el.scrollHeight + offset);
            };
            jQuery(this).on('keyup input', function() { resizeTextarea(this); }).removeAttr('data-autoresize');
        });

        $(document).ready(function() {
            Tipped.create('[data-toggle="tooltip"]', {
                skin: 'light', 
                radius: true, 
                size:'large',
            });

            function isMilestoneInvalid() {
                console.log('valid');
            }
        });
        
        // $(window).bind('beforeunload', function(){
        //     if($('#m-selected-step').val() != 5){
        //         return 'Are you sure you want to leave?';
        //     }
        // });
        
    </script>
    <script>
        /**
         * 01.01 : Initial configuration for sending ajax
         */
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
    </script>
 <script>
    var url = "<?php echo e(url('photo/save')); ?>";
    Dropzone.autoDiscover = false;
    var dropzone = $("div#userPic").dropzone({ 
        url: url,
        paramName: "newFileToUpload",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        maxFiles: 1,
        success: function(file, response) {

            // console.log(response);
            $('#imageCrop').attr('src',"<?php echo e(url('/')); ?>"+'/uploads/'+response);
            $('#cropperModal').find('input[name="photoName"]').val(response);
            $('#cropperModal').modal('show');

        },
         init: function() {
            this.on("maxfilesexceeded", function(file){
                swal("You can only upload one file!");
            });
        }
    });
    // console.log(dropzone);
    var loggedInUser = {
            type: '<?php echo e(Auth::user()->account_type); ?>',
            id: '<?php echo e(Auth::user()->account_id); ?>',
            name: '<?php echo e(Auth::user()->name); ?>'
        },
     popoverContainer = $('#container');    
</script>
<script>
    window.addEventListener('DOMContentLoaded', function () {
      var image = document.getElementById('imageCrop');
      var cropBoxData;
      var canvasData;
      var cropper;

      $('#cropperModal').on('shown.bs.modal', function () {
        cropper = new Cropper(image, {
          autoCropArea: 0.5,
          ready: function () {
            //Should set crop box data first here
            cropper.setCropBoxData(cropBoxData).setCanvasData(canvasData);
          },
          viewMode: 2,
          autoCropArea: 1,
          aspectRatio: 1 / 1
        });
       $('.saveImg').click(function(){
            var cropData = cropper.getData();
            var form_data = new FormData();                  
            form_data.append('photoName', $('#cropperModal').find('input[name="photoName"]').val());
            form_data.append('widthScale', cropData.scaleX);
            form_data.append('x1', cropData.x);
            form_data.append('w', cropData.width);
            form_data.append('heightScale', cropData.scaleY);
            form_data.append('y1', cropData.y);
            form_data.append('h', cropData.height);
            $.ajax({
                url: public_url+'photo/save',
                dataType: 'text',  
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,                         
                type: 'post',
                success: function(response){
                    $('#cropperModal').modal('hide');
                    $('.clientpicPreviewPics').prop('src', "<?php echo e(url('/')); ?>"+'/uploads/thumb_'+response);
                    if($('.clientpicPreviewPics').hasClass('hidden'))
                    $('.clientpicPreviewPics').removeClass('hidden');
                    $('#userPic').addClass('hide');
                    $('.openCamera').addClass('hide');
                    $('.picRemove').removeClass('hide');
                    $('.prePhotoNameClient').val(response);
                    $('#check-img').val(response);
                    var myDropzone = Dropzone.forElement("div#userPic");
                    myDropzone.removeAllFiles();
                }
            });
        })
        $('.cropImg').click(function(){
            var cropData = cropper.getData();
            var form_data = new FormData();                  
			form_data.append('photoName', $('#cropperModal').find('input[name="photoName"]').val());
			form_data.append('widthScale', cropData.scaleX);
			form_data.append('x1', cropData.x);
			form_data.append('w', cropData.width);
			form_data.append('heightScale', cropData.scaleY);
			form_data.append('y1', cropData.y);
			form_data.append('h', cropData.height);
			$.ajax({
				url: public_url+'photo/save',
				dataType: 'text',  
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,                         
				type: 'post',
				success: function(response){
					$('#cropperModal').modal('hide');
					$('.clientpicPreviewPics').prop('src', "<?php echo e(url('/')); ?>"+'/uploads/thumb_'+response);
					if($('.clientpicPreviewPics').hasClass('hidden'))
                    $('.clientpicPreviewPics').removeClass('hidden');
                    $('#userPic').addClass('hide');
                    $('.openCamera').addClass('hide');
                    $('.picRemove').removeClass('hide');
                    $('.prePhotoNameClient').val(response);
                    $('#check-img').val(response);
                    var myDropzone = Dropzone.forElement("div#userPic");
                    myDropzone.removeAllFiles();
				}
			});
        })
      }).on('hidden.bs.modal', function () {
        cropBoxData = cropper.getCropBoxData();
        canvasData = cropper.getCanvasData();
        cropper.destroy();
      });
    });
  </script>
  <!-- Dropzone And Cropper Js End -->

<!--  <?php echo Html::script('result/js/clients.js?v='.time()); ?> -->


<script type="text/javascript">
    $(document).ready(function(){
        setTimeout(function(){
            $('#ecNumber').intlTelInput({
                defaultCountry: "nz",
                preferredCountries: ['nz', 'au', 'za']
            }); 
        }, 500);
    })
</script>

<!-- Google Places Api -->
<script>
    var placeSearch, autocomplete;
    var componentForm = {
      street_number: 'short_name',
      route: 'long_name',
      sublocality_level_1: 'long_name',
      locality: 'long_name',
      administrative_area_level_1: 'short_name',
      country: 'short_name',
      postal_code: 'short_name'
    };
    function initAutocomplete() {
        if($("#autocomplete").length){
            autocomplete = new google.maps.places.Autocomplete(
            document.getElementById('autocomplete'), {types: ['geocode']});
          autocomplete.setFields(['address_component','geometry']);
          autocomplete.addListener('place_changed', fillInAddress);
        }
      
    }
    function fillInAddress() {
        console.log('hicd');
      var place = autocomplete.getPlace();
      var latitude = place.geometry.location.lat();
      var longitude = place.geometry.location.lng();
      getTimeZone(latitude,longitude);
      $('input[name="addressline1"]').val('');
      $('input[name="addressline2"]').val('');
      $('input[name="city"]').val('');
      $('input[name="postal_code"]').val(val);
      $('#country').selectpicker('refresh');
      $('select#addrState').selectpicker('refresh');
      var streetNumber = route = sublocality_level_1 = city = stateCode = countryCode = postalCode = '';
      for (var i = 0; i < place.address_components.length; i++) {
        var addressType = place.address_components[i].types[0];
        var val = place.address_components[i][componentForm[addressType]];
        if (addressType == 'street_number') {
            streetNumber = val;
        }else if(addressType == 'route'){
            route = val;
        }else if(addressType == 'sublocality_level_1'){
            sublocality_level_1 = val;
        }else if(addressType == 'locality'){
            city = val;
        }else if(addressType == 'administrative_area_level_1'){
            stateCode = val;
        }else if(addressType == 'country'){
            countryCode = val;
        }else if(addressType == 'postal_code'){
            postalCode = val;
        }
        getCurrencyCode(countryCode);
      }
        $('input[name="addressline1"]').val(streetNumber+' '+route);
        $('input[name="addressline2"]').val(sublocality_level_1);
        $('input[name="city"]').val(city);
        $('#country option').each(function(){
            if($(this).val() == countryCode){
                $(this).attr('selected','selected');
                $('#country').trigger('change');
                var country_code = countryCode,
                    selectedStates = $('select#addrState');
                    
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
                            var defaultState = stateCode,
                                formGroup = selectedStates.closest('.form-group');

                            selectedStates.html("");
                            $.each(data, function(val, text){
                                var option = '<option value="' + val + '"';
                                if(defaultState != '' && defaultState != null && val == defaultState)
                                    option += ' selected';
                                option += '>' + text + '</option>';
                                selectedStates.append(option);
                            });

                            $('#country').selectpicker('refresh');
                            selectedStates.selectpicker('refresh');
                            setFieldValid(formGroup, formGroup.find('span.help-block'))
                        }
                    });
                }
            }
        });
        $('input[name="postal_code"]').val(postalCode);
    }
    function geolocate() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
          var geolocation = {
            lat: position.coords.latitude,
            lng: position.coords.longitude
          };
          var circle = new google.maps.Circle(
              {center: geolocation, radius: position.coords.accuracy});
          autocomplete.setBounds(circle.getBounds());
        });
      }

    }
    function getTimeZone(lat, long){
        delete $.ajaxSettings.headers["X-CSRF-TOKEN"];
        $.get('https://maps.googleapis.com/maps/api/timezone/json?location=' + lat + ',' + long + '&timestamp=1331161200&sensor=false&key=AIzaSyCI9fgvBgIW52M1jvW5rWQ9LOSdweGy8kg',function(response){
            if(response != undefined){
                var timeZone = response.timeZoneId;
                if(timeZone !=undefined){
                    if(timeZone == 'Asia/Calcutta'){
                        timeZone = 'Asia/Kolkata';
                    }
                    $('#timezone').val(timeZone);
                    $('#timezone').selectpicker('refresh');
                }
            }
        });
        $.ajaxSettings.headers["X-CSRF-TOKEN"] = $('meta[name="_token"]').attr('content');
    }
    function getCurrencyCode(countryCode){
        delete $.ajaxSettings.headers["X-CSRF-TOKEN"];
        jQuery.getJSON(
        "https://restcountries.eu/rest/v1/alpha/"+countryCode,
        function (data) {
                if(data.currencies[0] != undefined){
                    $('#currency').val(data.currencies[0]);
                    $('#currency').selectpicker('refresh');
                }
            }
        );
        $.ajaxSettings.headers["X-CSRF-TOKEN"] = $('meta[name="_token"]').attr('content');
    }
    $('body').on('change','#country',function(){
        var countryCode = $(this).val();
        getCurrencyCode(countryCode);
    });
</script>
<script type="text/javascript">
    $('document').ready(function(){
        $('#waitingShield').addClass('hidden');
    })

    $(document).ready(function () {
        console.log("main_goal.blade.php");
    });



    $(window).scroll(function() {    
        var scroll = $(window).scrollTop();

        if (scroll >= 300) {
            $(".custom-img").addClass("stickyimg");
        } else {
            $(".custom-img").removeClass("stickyimg");
        }

        if (scroll >= 180) {
            $(".stepsection").addClass("stickystep");
        } else {
            $(".stepsection").removeClass("stickystep");
        }
    });

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.address/1.6/jquery.address.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCI9fgvBgIW52M1jvW5rWQ9LOSdweGy8kg&libraries=places&callback=initAutocomplete"
    async defer></script>
<?php echo $__env->yieldContent('required-scrips'); ?>

</body>
</html><?php /**PATH /Users/mahendra/Documents/projects/epicFit/epicfitlaravelv6/resources/views/Result/goal-buddy/main_goal.blade.php ENDPATH**/ ?>