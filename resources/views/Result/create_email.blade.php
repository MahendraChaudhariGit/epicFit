
@extends('masters.app')

@section('page-title')
<span >Subscribe Emails</span> 
@stop
@section('required-styles')
{!! Html::style('plugins/tooltipster-master/tooltipster.css') !!}

<!-- start: Summernote -->
{!! Html::style('plugins/summernote/dist/summernote.css') !!}
<!-- end: Summernote -->
{!! Html::style('plugins/bootstrap-select-master/css/bootstrap-select.min.css') !!}
{!! Html::style('plugins/intl-tel-input-master/build/css/intlTelInput.css') !!}

{!! Html::style('css/custom.css?v='.time()) !!}
<!-- End: NEW timepicker css -->

@stop
@section('content')

<div id="panel_edit_account" class="tab-pane active">
    <div class="alert alert-success" style="display:none;" id="suc_msg"></div>                
     <div>

    <div class="row swMain">
    <form method="POST" accept-charset="UTF-8" id="sendy" class="margin-bottom-30">
    <input name="_token" value="" type="hidden">
        
    <div class="col-md-12">
    <fieldset class="padding-15">
        <legend>
            Subscribe Email
        </legend>
        <div class="row">
            <div class="col-md-6 ">
                <div class="form-group">
                    <div>
                        <label for="first_name" class="strong">Name </label>
                        <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                            <i class="fa fa-question-circle"></i>
                        </span>
                    </div>
                    <input class="form-control" required="required" data-realtime="firstName" name="name" value="" id="name" type="text">
                    <span id="name" class="help-block" style="color: #a94442;display: none;" ></span>
                </div>

                <div class="form-group">
                    <div>
                        <label for="last_name" class="strong">Email *</label>
                        <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                            <i class="fa fa-question-circle"></i>
                        </span>
                    </div>
                    <input class="form-control" data-realtime="email" required="required" name="email" value="" id="email" type="email">
                    <span id="email" class="help-block" style="color: #a94442;display: none;" ></span>

                </div>

                <div class="form-group">
                    <!--button class="btn btn-primary btn-wide pull-right btn-add-more-form update-client"-->
                        <button type="button" class="btn btn-primary btn-wide pull-right btn-add-more-form" id="sendy_save">
                        <i class="fa fa-envelope" aria-hidden="true"></i> Subscribe
                    </button>
                </div>
            
                
            </div>
        </div>
    </fieldset> 
    </div>
    </form>
    <!--  Unsubscribe -->
    <form method="POST" accept-charset="UTF-8" id="sendy_unsubscribe" class="margin-bottom-30">
    <input name="_token" value="" type="hidden">
        
    <div class="col-md-12">
    <fieldset class="padding-15">
        <legend>
            Unsubscribe Email
        </legend>
        <div class="row">
            <div class="col-md-6 ">
                <div class="form-group">
                    <div>
                        <label for="first_name" class="strong">Name </label>
                        <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                            <i class="fa fa-question-circle"></i>
                        </span>
                    </div>
                    <input class="form-control" required="required" data-realtime="firstName" name="name" value="" id="name" type="text">
                    <span id="name" class="help-block" style="color: #a94442;display: none;" ></span>
                </div>

                <div class="form-group">
                    <div>
                        <label for="last_name" class="strong">Email *</label>
                        <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                            <i class="fa fa-question-circle"></i>
                        </span>
                    </div>
                    <input class="form-control" data-realtime="email" required="required" name="email" value="" id="email" type="email">
                    <span id="email" class="help-block" style="color: #a94442;display: none;" ></span>

                </div>

                <div class="form-group">
                    <!--button class="btn btn-primary btn-wide pull-right btn-add-more-form update-client"-->
                        <button type="button" class="btn btn-primary btn-wide pull-right btn-add-more-form" id="sendy_unsubscribe">
                        <i class="fa fa-envelope" aria-hidden="true"></i> Unsubscribe
                    </button>
                </div>
            
                
            </div>
        </div>
    </fieldset> 
    </div>
    </form>
    <!-- End Unsubscribe-->

    <!-- Sending mail  -->
    <form method="POST" accept-charset="UTF-8" id="send_mail" class="margin-bottom-30">
    <input name="_token" value="" type="hidden">
        
    <div class="col-md-12">
    <fieldset class="padding-15">
        <legend>
            Send Email
        </legend>
        <div class="row">
            <div class="col-md-6 ">
                <div class="form-group">
                    <!--button class="btn btn-primary btn-wide pull-right btn-add-more-form update-client"-->
                        <button type="button" class="btn btn-primary btn-wide pull-right btn-add-more-form" id="sendy_mail">
                        <i class="fa fa-envelope" aria-hidden="true"></i> Create and Send
                    </button>
                </div>
            
                
            </div>
        </div>
    </fieldset> 
    </div>
    </form>
    <!--  End sending mail-->
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
<!-- start: image upload js -->
{!! Html::script('plugins/Jcrop/js/jquery.Jcrop.min.js') !!}
{!! Html::script('plugins/Jcrop/js/script.js') !!}
<!-- start: image upload js -->

{!! Html::script('js/form-wizard-clients.js?v='.time()) !!}
{!! Html::script('js/form-wizard-benchmark.js?v='.time()) !!}
{!! Html::script('js/benchmark.js?v='.time()) !!}
{!! Html::script('js/helper.js?v='.time()) !!}
<!--{!! Html::script('js/events.js') !!}-->
{!! Html::script('js/bench.js?v='.time()) !!}
{!! Html::script('js/calculator.js?v='.time()) !!}
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