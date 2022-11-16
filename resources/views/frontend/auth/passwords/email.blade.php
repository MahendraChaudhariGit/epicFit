@extends('frontend.layouts.login')

@section('title', 'Password Reset | '.app_name())

@section('content')
    <div class="row">
        <div class="main-login col-xs-10 margin-top-30 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
            <div class="logo margin-top-30">
                <img class="center-block" 
                src="{{ asset('assets/images/epic-icon.png') }}" alt="Clip-Two" style="width: 50px;" />
                <h1>EPIC<span>Trainer</span></h1>
            </div>
            <!-- start: SIGN-IN BOX -->
            <div class="">

                {!! Form::open(['url' => 'password/forgot']) !!}
                <fieldset><!--class="no-padding"-->
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    @include('includes.partials.messages')
					
                    <h5 class="margin-top-20 margin-left-10">Please enter your registered email address.</h5>
                    <div class="form-group">
                        <span class="input-icon">
                            {!! Form::email('uname', null, ['class' => 'form-control forgotemailField', 'required' => '', 'pattern' => '^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$', 'oninvalid' => 'setCustomValidity("Please fill with valid '.trans('validation.attributes.frontend.email').'.")', 'oninput' => 'setCustomValidity("")', 'title' => trans('validation.attributes.frontend.email'), 'placeholder' => trans('validation.attributes.frontend.email')]) !!}
                            {!! link_to('/login', 'Login', ['class' => 'forgot' ]) !!}
                            <i class="fa fa-envelope" style="top:5px;"></i>
                        </span>
                    </div>
                        
                    <!--<div class="row form-group">
                        {!! Form::label('email', trans('validation.attributes.frontend.email'), ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-7">
                            {!! Form::input('email', 'email', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.frontend.email')]) !!}
                        </div><!--col-md-6-->
                   <!-- </div><!--form-group-->

                    <div class="form-group form-actions">
                        <!--<div class="col-md-6 col-md-offset-4">
                            {!! Form::submit(trans('labels.frontend.passwords.send_password_reset_link_button'), ['class' => 'btn btn-primary']) !!}
                        </div>--><!--col-md-6-->
                        <!--{!! Form::submit(trans('labels.frontend.passwords.send_password_reset_link_button'), ['class' => 'btn btn-primary pull-right shortTxt']) !!}-->
                        {!! Form::button(trans('labels.frontend.passwords.send_password_reset_link_button'), array('type' => 'submit', 'class' => 'btn btn-primary pull-right shortTxt reset-custom-btn')) !!}
                    </div><!--form-group-->
                    
                    <!-- <div class="new-account">
                        Don't have an account yet?
                        <a href="{{ url('register') }}">
                            Create an account
                        </a>
                    </div> -->
                </fieldset>
                {!! Form::close() !!}
                <!-- start: COPYRIGHT -->
                <div class="copyright">
                    &copy; <span class="current-year"></span><span class="text-bold text-uppercase"> ClipTheme</span>. <span>All rights reserved</span>
                </div>
                <!-- end: COPYRIGHT -->
            </div>
            <!-- end: REGISTER BOX -->
        </div>
    </div>
    @stop

    @section('scripts')
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
        });
    </script>
@stop