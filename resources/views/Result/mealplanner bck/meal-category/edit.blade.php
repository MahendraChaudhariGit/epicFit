
@extends('masters.app')

@section('page-title')
<span >Update Meal Category</span> 
@stop
@section('required-styles')
{!! Html::style('plugins/tooltipster-master/tooltipster.css?v='.time()) !!}

<!-- start: Summernote -->
{!! Html::style('plugins/summernote/dist/summernote.css?v='.time()) !!}
<!-- end: Summernote -->
{!! Html::style('plugins/bootstrap-select-master/css/bootstrap-select.min.css?v='.time()) !!}
{!! Html::style('plugins/intl-tel-input-master/build/css/intlTelInput.css?v='.time()) !!}
{!! Html::style('plugins/sweetalert/sweet-alert.css?v='.time()) !!} 
{!! Html::style('plugins/Jcrop/css/jquery.Jcrop.min.css?v='.time()) !!}

{!! Html::style('css/custom.css?v='.time()) !!}
<!-- End: NEW timepicker css -->

@stop
@section('content')
@include('partials.pic_crop_model')

<div id="panel_edit_account" class="tab-pane active">
    <div class="alert alert-success" style="display:none;" id="suc_msg"></div>                
     <div>

    <div class="row swMain">
    <form method="POST" accept-charset="UTF-8" id="form" class="margin-bottom-30">
    <input name="_token" value="" type="hidden">
    <input type="hidden" name="record_id" id="record_id" value="{{ $mCategory['id'] }}">
        
    <div class="col-md-12">
    <fieldset class="padding-15">
        <legend>
            Update Meal Category
        </legend>
        <div class="row">
            <div class="col-md-6 ">
                <div class="form-group">
                    <div>
                        <label for="mealcategoryDesc" class="strong">Name </label>
                        <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                            <i class="fa fa-question-circle"></i>
                        </span>
                    </div>
                    <input class="form-control" required="required" data-realtime="mealcategoryDesc" name="mealcategoryDesc" value="{{ $mCategory['mealcategoryDesc'] }}" id="mealcategoryDesc" type="text">
                    <span id="mealcategoryDesc" class="help-block" style="color: #a94442;display: none;" ></span>
                </div>

                <!-- <div class="form-group">
                    <div>
                        <label for="mealcategoryDesc" class="strong">Description *</label>
                        <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                            <i class="fa fa-question-circle"></i>
                        </span>
                    </div>
                    <textarea class="form-control rounded-0" data-realtime="mealcategoryDesc" required="required" name="mealcategoryDesc" value="" id="mealcategoryDesc" rows="10">{{ $mCategory['mealcategoryDesc'] }}</textarea>
                    <span id="mealcategoryDesc" class="help-block" style="color: #a94442;display: none;" ></span>

                </div>  -->

                <div class="form-group">
                    <!--button class="btn btn-primary btn-wide pull-right btn-add-more-form update-client"-->
                        <button type="button" class="btn btn-primary btn-wide pull-right btn-add-more-form" id="update_mealcat"> Update 
                    </button>
                </div>
            
                
            </div>
        </div>
    </fieldset> 
    </div>
    </form>
       
    </div>
    </div>
    </div>

@endsection

@section('required-script')
{!! Html::script('js/jquery-ui.min.js?v='.time()) !!}

<!-- start: Moment Library -->
{!! Html::script('plugins/moment/moment.min.js?v='.time()) !!}
<!-- end: Moment Library -->

<!-- start: Summernote -->
{!! Html::script('plugins/summernote/dist/summernote.min.js?v='.time()) !!}
<!-- end: Summernote -->
<!-- start: Rating -->
{!! Html::script('plugins/bootstrap-rating/bootstrap-rating.min.js?v='.time()) !!}
<!-- end: Rating -->
<!-- start: Bootstrap Typeahead -->
{!! Html::script('plugins/bootstrap3-typeahead/js/bootstrap3-typeahead.min.js?v='.time()) !!}  
<!-- end: Bootstrap Typeahead --> 
<!-- start: Bootstrap Typeahead -->
{!! Html::script('plugins/jQuery-Smart-Wizard/js/jquery.smartWizard.js?v='.time()) !!}  
<!-- end: Bootstrap Typeahead --> 
<!-- start: Bootstrap timepicker -->

{!! Html::script('plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js?v='.time()) !!}
<!-- end: Bootstrap timepicker -->
{!! Html::script('plugins/tooltipster-master/jquery.tooltipster.min.js?v='.time()) !!}


{!! Html::script('plugins/bootstrap-select-master/js/bootstrap-select.min.js?v='.time()) !!}
{!! Html::script('plugins/intl-tel-input-master/build/js/utils.js?v='.time()) !!}
{!! Html::script('plugins/intl-tel-input-master/build/js/intlTelInput.js?v='.time()) !!}
{!! Html::script('plugins/jquery-validation/jquery.validate.min.js?v='.time()) !!}
{!! Html::script('plugins/sweetalert/sweet-alert.min.js?v='.time()) !!} 
{!! Html::script('js/meal-planner.js?v='.time()) !!}
<!-- start: JCrop -->
{!! Html::script('plugins/Jcrop/js/jquery.Jcrop.min.js?v='.time()) !!}
{!! Html::script('plugins/Jcrop/js/script.js?v='.time()) !!}
<!-- end: JCrop -->

{!! Html::script('js/form-wizard-clients.js?v='.time()) !!}
{!! Html::script('js/form-wizard-benchmark.js?v='.time()) !!}
{!! Html::script('js/benchmark.js?v='.time()) !!}
{!! Html::script('js/helper.js?v='.time()) !!}
<!--{!! Html::script('js/events.js') !!}-->
{!! Html::script('js/bench.js?v='.time()) !!}
{!! Html::script('js/clients.js?v='.time()) !!}





<script>
    var loggedInUser = {
        type: '{{ Auth::user()->account_type }}',
        id: '{{ Auth::user()->account_id }}',
        name: '{{ Auth::user()->name }}'
    },
            popoverContainer = $('#container');
</script>

@stop