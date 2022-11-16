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
            {!! Form::open(['route' => 'superadmin.authenticate', 'class' => 'form-login','method' => 'post']) !!}
            {{csrf_field()}}
            <div class="box-login">              
                <fieldset>
                    @include('includes.partials.messages')
                    <div class="form-group">
                        <span class="input-icon">
                            {!! Form::email('email', null, ['class' => 'form-control', 'required' => '', 'pattern' => '^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$', 'oninvalid' => 'setCustomValidity("Please fill with valid '.trans('validation.attributes.frontend.email').'.")', 'oninput' => 'setCustomValidity("")', 'title' => trans('validation.attributes.frontend.email'), 'placeholder' => trans('validation.attributes.frontend.email')]) !!}
                        </span>
                    </div>
                    <div class="form-group form-actions">
                        <span class="input-icon">
                            {!! Form::password('password', ['class' => 'form-control password', 'required' => '', 'oninvalid' => 'setCustomValidity("Please fill out '.trans('validation.attributes.frontend.email').'.")', 'oninput' => 'setCustomValidity("")', 'title' => trans('validation.attributes.frontend.password'), 'placeholder' => trans('validation.attributes.frontend.password')]) !!}
                        </span>
                    </div>    
                </fieldset>
                <!-- start: COPYRIGHT -->
               
                <!-- end: COPYRIGHT -->
                
            </div>
            <!-- end: REGISTER BOX -->
            <div class="form-actions">
                <div>
                    {!! HTML::decode(link_to('password/reset', trans('labels.frontend.passwords.forgot_password'), ['class' => 'forgot shortTxt' ])) !!}
                </div> 
                {!! Form::button('Login<!--  <i class="fa fa-arrow-circle-right"></i> -->', array('type' => 'submit', 'class' => 'btn btn-primary pull-right loginclass')) !!}
                <div class="checkbox clip-check check-primary" style="float: left;">
                    {!! Form::checkbox('remember', null, null, ['id' => 'remember']) !!}
                    {!! Form::label('remember', trans('labels.frontend.auth.remember_me')) !!}
                </div>
            </div>
            <div class="copyright">
                &copy; <span class="current-year"></span><span class="text-bold text-uppercase"> EPIC TRAINER</span>. <span>All rights reserved</span>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <!-- end: REGISTRATION -->
    @stop

    @section('scripts')
    {!! Html::script('vendor/bootstrap-select-master/js/bootstrap-select.min.js') !!}
    {!! Html::script('assets/js/helper.js') !!}
            <!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
    {!! Html::script('vendor/jquery-validation/jquery.validate.min.js') !!}
            <!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->

    <!-- start: JavaScript Event Handlers for this page -->
    {!! Html::script('assets/js/login.js') !!}
    
    <!-- end: JavaScript Event Handlers for this page -->
    <script>
        jQuery(document).ready(function() {
            Main.init();
            Login.init();
            
            $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
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