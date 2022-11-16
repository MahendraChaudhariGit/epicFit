@extends('frontend.layouts.login')

@section('title', 'Register | '.app_name())

@section('content')
<div class="row">
    <div class="main-login col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4 register">
        <div class="logo margin-top-30">
            <img style="width:180px;" class="center-block" src="assets/images/logo.png" alt="Clip-Two"/>
        </div>
        <!-- start: REGISTER BOX -->
        <div class="box-register">
            {!! displayAlert()!!}
            {!! Form::open(['url' => 'test', 'class' => 'form-register']) !!}

                <fieldset>
                    <legend>
                        Sign Up
                    </legend>
					<p>
                        Enter your personal details below:
                    </p>

                    <div class="form-group">
                        {!! Form::input('text', 'name', null, ['class' => 'form-control', 'required' => '', 'oninvalid' => 'setCustomValidity("This field is required.")', 'oninput' => 'setCustomValidity("")', 'maxlength' => 255, 'title' => 'This field is required', 'placeholder' => 'First Name']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::input('text', 'last_name', null, ['class' => 'form-control', 'required' => '', 'oninvalid' => 'setCustomValidity("Please fill out '.trans('validation.attributes.frontend.last_name').'.")', 'oninput' => 'setCustomValidity("")', 'maxlength' => 255, 'title' => 'This field is required', 'placeholder' => trans('validation.attributes.frontend.last_name')]) !!}
                    </div>

                    <div class="form-group">
                        <span class="input-icon">
                            {!! Form::email('email', null, ['class' => 'form-control','placeholder' => 'E-mail Address', 'required']) !!}
                            <i class="fa fa-envelope"></i>
                        </span>
                    </div>
                    <div class="form-group">
                        <span class="input-icon">
                        	{!! Form::password('password', ['class' => 'form-control', 'required' => '', 'oninvalid' => 'setCustomValidity("Please fill out '.trans('validation.attributes.frontend.password').'.")', 'oninput' => 'setCustomValidity("")', 'title' => 'Please enter at least 6 characters.', 'placeholder' => trans('validation.attributes.frontend.password')]) !!}
                            <i class="fa fa-lock"></i>
                        </span>
                    </div>

                    <div class="form-group form-actions">
                        {!! Form::button('<i class="fa fa-arrow-circle-right"></i> '.trans('labels.frontend.auth.register_button'), array('type' => 'submit', 'class' => 'btn btn-primary pull-right')) !!}
                    </div>
                        <p class="new-account">
                            Already have an account?
                            <a href="{{ url('login') }}">
                                Log-in
                            </a>
                        </p>
                </fieldset>
                <!-- start: COPYRIGHT -->
                <div class="copyright">
                    &copy; <span class="current-year"></span><span class="text-bold text-uppercase"> EPIC TRAINER</span>. <span>All rights reserved</span>
                </div>
                <!-- end: COPYRIGHT -->
            {!! Form::close() !!}
        </div>
        <!-- end: REGISTER BOX -->
    </div>
</div>
<!-- end: REGISTRATION -->
@stop

@section('scripts')
        <!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
{!! Html::script('vendor/jquery-validation/jquery.validate.min.js?v='.time()) !!}
        <!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->

<!-- start: JavaScript Event Handlers for this page -->
{!! Html::script('assets/js/login.js?v='.time()) !!}
        <!-- end: JavaScript Event Handlers for this page -->
<script>
    jQuery(document).ready(function() {
        Main.init();
        Login.init();
    });
</script>
@stop