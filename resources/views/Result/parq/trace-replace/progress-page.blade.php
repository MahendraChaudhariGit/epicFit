@extends('Result.masters.app')

@section('page-title')
    <span >Nutritional Journal</span> 
@stop
@section('required-styles')
{!! Html::style('result/plugins/tooltipster-master/tooltipster.css') !!}

<!-- start: Summernote -->
{!! Html::style('result/plugins/summernote/dist/summernote.css') !!}
<!-- end: Summernote -->
{!! Html::style('result/plugins/bootstrap-select-master/css/bootstrap-select.min.css') !!}
{!! Html::style('result/plugins/intl-tel-input-master/build/css/intlTelInput.css') !!}

{!! Html::style('result/css/custom.css?v='.time()) !!}
<style>
    .swMain.wizard-headding-style > ul{position:static;margin-bottom:25px}
    .wizard-headding-style .control-label{text-align:left}

    /*/////////only for this page///////////*/
    #spaceless{margin-bottom:-392px;}
    #spaceless2{margin-bottom: -298px;}
    @media (max-width: 768px){
      #spaceless{margin-bottom:0px;}
    #spaceless2{margin-bottom: 0px;}  
    }
    /*/////////only for this page///////////*/
</style>

@stop
@section('content')
<!-- start: acc1 -->
<div class="panel panel-white">

    <!-- start: PANEL BODY -->
    <div class="panel-body">
        <div class="row review-mode">
            @include('Result.parq.trace-replace.preview-page')
        </div>
        <div class="row editable-mode" style="display: none">
            @include('Result.parq.trace-replace.editable-page')
        </div>
    </div>
    <!-- end: PANEL BODY -->
</div>
<!-- end: acc1 -->

<!--Start: Extra field -->

<!--End: Extra field -->

@endsection

@section('required-script')
{!! Html::script('result/js/jquery-ui.min.js') !!}

<!-- start: Moment Library -->
{!! Html::script('result/plugins/moment/moment.min.js') !!}
<!-- end: Moment Library -->

<!-- start: Summernote -->
{!! Html::script('result/plugins/summernote/dist/summernote.min.js') !!}
<!-- end: Summernote -->
<!-- start: Rating -->
{!! Html::script('result/plugins/bootstrap-rating/bootstrap-rating.min.js') !!}
<!-- end: Rating -->
<!-- start: Bootstrap Typeahead -->
{!! Html::script('result/plugins/bootstrap3-typeahead/js/bootstrap3-typeahead.min.js') !!}  
<!-- end: Bootstrap Typeahead --> 
<!-- start: Bootstrap Typeahead -->
{!! Html::script('result/plugins/jQuery-Smart-Wizard/js/jquery.smartWizard.js') !!}  
<!-- end: Bootstrap Typeahead --> 
<!-- start: Bootstrap timepicker -->

{!! Html::script('result/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') !!}
<!-- end: Bootstrap timepicker -->
 {!! Html::script('result/plugins/tooltipster-master/jquery.tooltipster.min.js') !!}


 {!! Html::script('result/plugins/bootstrap-select-master/js/bootstrap-select.min.js') !!}
 {!! Html::script('result/plugins/intl-tel-input-master/build/js/utils.js') !!}
 {!! Html::script('result/plugins/intl-tel-input-master/build/js/intlTelInput.js') !!}
 {!! Html::script('result/plugins/jquery-validation/jquery.validate.min.js') !!}
 <!-- start: image upload js -->
 {!! Html::script('result/plugins/Jcrop/js/jquery.Jcrop.min.js') !!}
 {!! Html::script('result/plugins/Jcrop/js/script.js') !!}
<!-- start: image upload js -->




 {!! Html::script('result/js/helper.js?v='.time()) !!}
{!! Html::script('result/js/form-wizard-clients.js?v='.time()) !!}

<script type="text/javascript">
    $(document).on('click','.edit-mode', function(){
        $(".review-mode").hide();
        $(".editable-mode").show();
    })

    $(document).on('click','.remove-edit-mode', function(){
    $(".editable-mode").hide();
    $(".review-mode").show();
})
</script>
@stop
@section('script-handler-for-this-page')
 $( ".panel-collapse.closed" ).trigger( "click" );
    FormWizard.init('#wizard');

@stop()