@extends('frontend.layouts.login')

@section('title', 'Login | '.app_name())

@section('content')
    <div class="row">
        <div class="main-login col-md-4 col-md-offset-4">
            <div class="logo margin-top-30">
                <img class="center-block" 
                src="{{ asset('assets/images/epic-icon.png') }}" alt="Clip-Two" style="width: 50px;" />
                <h1>EPIC<span>Trainer</span></h1>
            </div>
            <!-- start: SIGN-IN BOX -->
              {!! displayAlert()!!}
                {!! Form::open(['url' => 'login', 'class' => 'form-login']) !!}
                {!! Form::hidden('businessUrl', $businessUrl) !!}
                {!! Form::hidden('businessId')  !!}
                {!! Form::hidden('userType') !!}
            <div class="box-login">
              
                    <fieldset>
                      <!--   <legend>
                            Sign in to your account
                        </legend> -->
                        @include('includes.partials.messages')
                       <!--  <p>
                            Please enter your email and password to log in.
                        </p> -->
                        <!--<div class="form-group">
                            @if (count($errors) > 0)
                                <ul class="list-group">
                                    @foreach ($errors->all() as $error)
                                        <li class="list-group-item text-danger">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                             @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif
                        </div>-->
                        @if(count($user_data) > 1)
                        <label class="text-center r-message">It seems you are registered with more than one businesses. Please select the business name where you want to log in</label>
                        <div class="login-type">
                        @foreach($user_data as $data)
                         @if($data['business_id'] != 0 )
                         <div class="radio clip-radio radio-primary radio-inline m-b-0">
                            <input hidden name="userType" value = "{{$data['account_type']}}">
                            <input type="radio" id="{{$data['business_id']}}" name="businessUrl" value="{{$data['businesParent']['cp_web_url']}}">
                            <label for="{{$data['business_id']}}">{{$data['businesParent']['cp_web_url']}}</label>
                        </div>
                         @endif
                       @endforeach
                   </div>
                       @endif
                    @php
                        if(count($user_data) > 0){
                            $hide = 'hidden';    
                        }else{
                            $hide = '';
                        }
                    @endphp
                        <div class="form-group {{ $hide }}" >
                            <span class="input-icon">
                                {!! Form::email('uname', null, ['class' => 'form-control', 'required' => '', 'pattern' => '^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$', 'oninvalid' => 'setCustomValidity("Please fill with valid '.trans('validation.attributes.frontend.email').'.")', 'oninput' => 'setCustomValidity("")', 'title' => trans('validation.attributes.frontend.email'), 'placeholder' => trans('validation.attributes.frontend.email')]) !!}
                                <!-- <i class="fa fa-envelope"></i> -->
                            </span>
                        </div>
                        <div class="form-group form-actions {{ $hide }}">
                            <span class="input-icon">
                                {!! Form::password('password', ['class' => 'form-control password', 'required' => '', 'oninvalid' => 'setCustomValidity("Please fill out '.trans('validation.attributes.frontend.email').'.")', 'oninput' => 'setCustomValidity("")', 'title' => trans('validation.attributes.frontend.password'), 'placeholder' => trans('validation.attributes.frontend.password')]) !!}
                                <!-- {!! HTML::decode(link_to('password/reset', trans('labels.frontend.passwords.forgot_password'), ['class' => 'forgot shortTxt' ])) !!} -->
                              <!--   <i class="fa fa-lock"></i> -->
                            </span>
                        </div>
                    </fieldset>
                    <!-- start: COPYRIGHT -->
                   
                    <!-- end: COPYRIGHT -->
                
            </div>
            <!-- end: REGISTER BOX -->


            <div class="form-actions">
                <div class="{{ $hide }}">
                    {!! HTML::decode(link_to('password/reset', trans('labels.frontend.passwords.forgot_password'), ['class' => 'forgot shortTxt' ])) !!}
                </div>
                {!! Form::button(count($user_data) < 1 ? 'Login' : 'Proceed'.'<!--  <i class="fa fa-arrow-circle-right"></i> -->', array('type' => 'submit', 'class' => 'btn btn-primary pull-right loginclass')) !!}
                
                <div class="checkbox clip-check check-primary {{ $hide }}" style="float: left;">
                    {!! Form::checkbox('remember', null, null, ['id' => 'remember']) !!}
                    {!! Form::label('remember', trans('labels.frontend.auth.remember_me')) !!}
                </div>
            </div>
            
            <div class="new-account {{ $hide }}">
                
                <a href="{{ url('register') }}">
                    Create a Business Account?
                </a>
            </div>
            
                <div class="copyright">
            &copy; <span class="current-year"></span><span class="text-bold text-uppercase"> EPIC TRAINER</span>. <span>All rights reserved</span>
        </div>
        {!! Form::close() !!}
        </div>
    </div>
    <!-- end: REGISTRATION -->
    <!-- Default Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Select Account type</h4>
                </div>
                <div class="modal-body">
                <form class="model-form">
                <div class="form-group m-b-0" style="margin-bottom: 0px">
                    <div class="radio clip-radio radio-primary radio-inline m-b-0 onchange-set-neutral">
                        <input type="radio" name="acctype" id="admin" value="Admin" required>
                        <label for="admin"> Admin </label>
                    </div>
                    <div class="radio clip-radio radio-primary radio-inline m-b-0 onchange-set-neutral">
                        <input type="radio" name="acctype" id="staff" value="Staff" required>
                        <label for="staff"> Staff </label>
                    </div>
                    <span class="help-block" style="margin-bottom: 0px"></span> 
                </div>
                </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary modelloginbutton">
                        Login
                    </button>
                </div>

               
            </div>
        </div>
    </div>
          <!-- /Default Modal -->
    @stop

    @section('scripts')
    {!! Html::script('vendor/bootstrap-select-master/js/bootstrap-select.min.js?v='.time()) !!}
    {!! Html::script('assets/js/helper.js?v='.time()) !!}
            <!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
    {!! Html::script('vendor/jquery-validation/jquery.validate.min.js?v='.time()) !!}
            <!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->

    <!-- start: JavaScript Event Handlers for this page -->
    {!! Html::script('assets/js/login.js?v='.time()) !!}
    
    <!-- end: JavaScript Event Handlers for this page -->
    <script>
        $( ".login-type" ).parent().css( "background-color", "transparent" );
         $( ".login-type" ).parent().parent().css( "background-color", "transparent" );
        jQuery(document).ready(function() {
            Main.init();
            Login.init();
            
            $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            

            //Popup model login Function
            $('#myModal').modal({ show : false, keyboard : false, backdrop : 'static' });
            $('.modelloginbutton').click(function(){
                var modelform=$('.model-form');
                var Accounttype=$('input[name="acctype"]:checked').val();
                if(validateRadioButton(modelform,"acctype")){
                    $('input[name="userType"]').val(Accounttype);                
                    $('.form-login').submit(); 
                }
                else {
                    formGroup=modelform.find("input[name='acctype']").closest('.form-group');
                    setFieldInvalid(formGroup,'Please select a type.');
                }
            });

            
            $('.loginclass').click(function(e){
                var form=$('.form-login'),
                isValid=form.valid();

                if(isValid){
                    $('.loginclass').attr('disabled',true);
                    var emailField = $('input[name="uname"]');
                    var HiddenSlug = $('input[name="businessUrl"]').val(); 
                    if(HiddenSlug){
                        e.preventDefault();
                        var public_url = $('meta[name="public_url"]').attr('content'),
                        email = emailField.val();
                        $.ajax({
                            url: public_url+'checkuser',
                            type: 'POST',
                            data: {'slug':HiddenSlug, 'userName':email},
                            success: function(response){ 
                                $('input[name="businessId"]').val(response['businessid']); 
                                if(response['totalaccounts'] <= 1){
                                    $('input[name="userType"]').val(response['usertype']);
                                    $('.loginclass').removeAttr("disabled");
                                    $('.form-login').submit();
                                }
                                else{
                                    $("#myModal").modal('show');
                                }           
                            }
                        });
                    }
                    else $('.form-login').submit();
                }
            });     
        });
    </script>
@stop