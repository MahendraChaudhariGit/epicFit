
@extends('masters.app')

@section('page-title')
<span >Add Serving Size</span> 
@stop
@section('required-styles')
{!! Html::style('plugins/tooltipster-master/tooltipster.css') !!}

<!-- start: Summernote -->
{!! Html::style('plugins/summernote/dist/summernote.css') !!}
<!-- end: Summernote -->
{!! Html::style('plugins/bootstrap-select-master/css/bootstrap-select.min.css') !!}
{!! Html::style('plugins/intl-tel-input-master/build/css/intlTelInput.css') !!}
{!! Html::style('plugins/sweetalert/sweet-alert.css') !!} 
{!! Html::style('plugins/Jcrop/css/jquery.Jcrop.min.css') !!}

{!! Html::style('css/custom.css') !!}
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
        
    <div class="col-md-12">
    <fieldset class="padding-15">
        <legend>
            Add Serving Size
        </legend>
        <div class="row">
            <div class="col-md-6 ">
                <div class="form-group">
                    <div>
                        <label for="food_id" class="strong">Food Name *</label>
                        <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                            <i class="fa fa-question-circle"></i>
                        </span>
                    </div>
                    <select id="food_id" name="food_id">
                    @foreach($foods as $food)
                         <option value="{{$food->food_id}}">{{$food->short_desc}}</option>
                    <!--input class="form-control" data-realtime="meal_cat" required="required" name="meal_cat" value="" id="meal_cat" type="text"-->
                    @endforeach
                    </select>
                    <span id="food_id" class="help-block" style="color: #a94442;display: none;" ></span>
                </div>

                <div class="form-group">
                    <div>
                        <label for="seq" class="strong">Sequence *</label>
                        <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                            <i class="fa fa-question-circle"></i>
                        </span>
                    </div>
                    <input class="form-control" data-realtime="seq" required="required" name="seq" value="" id="seq" type="text">
                    <span id="seq" class="help-block" style="color: #a94442;display: none;" ></span>

                </div>  
                <div class="form-group">
                    <div>
                        <label for="serving_desc" class="strong">Serving Description *</label>
                        <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                            <i class="fa fa-question-circle"></i>
                        </span>
                    </div>
                    <input class="form-control" data-realtime="serving_desc" required="required" name="serving_desc" value="" id="serving_desc" type="text">
                    <span id="serving_desc" class="help-block" style="color: #a94442;display: none;" ></span>

                </div>  
                <div class="form-group">
                    <div>
                        <label for="multiplier" class="strong">Multiplier *</label>
                        <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                            <i class="fa fa-question-circle"></i>
                        </span>
                    </div>
                    <input class="form-control" data-realtime="multiplier" required="required" name="multiplier" value="" id="multiplier" type="text">
                    <span id="multiplier" class="help-block" style="color: #a94442;display: none;" ></span>

                </div>
                <div class="form-group">
                    <div>
                        <label for="serving_weight" class="strong">Serving Weight *</label>
                        <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                            <i class="fa fa-question-circle"></i>
                        </span>
                    </div>
                    <input class="form-control" data-realtime="serving_weight" required="required" name="serving_weight" value="" id="serving_weight" type="text">
                    <span id="serving_weight" class="help-block" style="color: #a94442;display: none;" ></span>

                </div>                   


                <div class="form-group">
                    <!--button class="btn btn-primary btn-wide pull-right btn-add-more-form update-client"-->
                        <button type="button" class="btn btn-primary btn-wide pull-right btn-add-more-form" id="add_servings"> Add
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
{!! Html::script('js/jquery-ui.min.js') !!}

<!-- start: Moment Library -->
{!! Html::script('plugins/moment/moment.min.js') !!}
<!-- end: Moment Library -->

<!-- start: Summernote -->
{!! Html::script('plugins/summernote/dist/summernote.min.js') !!}
<!-- end: Summernote -->
<!-- start: Rating -->
{!! Html::script('plugins/bootstrap-rating/bootstrap-rating.min.js') !!}
<!-- end: Rating -->
<!-- start: Bootstrap Typeahead -->
{!! Html::script('plugins/bootstrap3-typeahead/js/bootstrap3-typeahead.min.js') !!}  
<!-- end: Bootstrap Typeahead --> 
<!-- start: Bootstrap Typeahead -->
{!! Html::script('plugins/jQuery-Smart-Wizard/js/jquery.smartWizard.js') !!}  
<!-- end: Bootstrap Typeahead --> 
<!-- start: Bootstrap timepicker -->

{!! Html::script('plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') !!}
<!-- end: Bootstrap timepicker -->
{!! Html::script('plugins/tooltipster-master/jquery.tooltipster.min.js') !!}


{!! Html::script('plugins/bootstrap-select-master/js/bootstrap-select.min.js') !!}
{!! Html::script('plugins/intl-tel-input-master/build/js/utils.js') !!}
{!! Html::script('plugins/intl-tel-input-master/build/js/intlTelInput.js') !!}
{!! Html::script('plugins/jquery-validation/jquery.validate.min.js') !!}
{!! Html::script('js/meal-planner.js') !!}
{!! Html::script('plugins/sweetalert/sweet-alert.min.js') !!}
<!-- start: image upload js -->
{!! Html::script('plugins/Jcrop/js/jquery.Jcrop.min.js') !!}
{!! Html::script('plugins/Jcrop/js/script.js') !!}
<!-- start: image upload js -->

{!! Html::script('js/form-wizard-clients.js') !!}
{!! Html::script('js/form-wizard-benchmark.js') !!}
{!! Html::script('js/benchmark.js') !!}
{!! Html::script('js/helper.js') !!}
<!--{!! Html::script('js/events.js') !!}-->
{!! Html::script('js/bench.js') !!}
{!! Html::script('js/clients.js') !!}





<script>
    var loggedInUser = {
        type: '{{ Auth::user()->account_type }}',
        id: '{{ Auth::user()->account_id }}',
        name: '{{ Auth::user()->name }}'
    },
            popoverContainer = $('#container');
</script>

@stop