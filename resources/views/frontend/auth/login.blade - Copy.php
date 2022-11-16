@extends('frontend.layouts.login')

@section('title', 'Login | '.app_name())

@section('content')
    <div class="row">
        <div class="main-login col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
            <div class="logo margin-top-30">
                <img style="width: 180px" class="center-block" src="assets/images/logo.png" alt="Clip-Two"/>
            </div>
            <!-- start: SIGN-IN BOX -->
            <div class="box-login">

                {!! Form::open(['url' => 'login', 'class' => 'form-login']) !!}

                    <fieldset>
                        <legend>
                            Sign in to your account
                        </legend>
                        @include('includes.partials.messages')
                        <p>
                            Please enter your email and password to log in.
                        </p>
                        <!--<div class="form-group">
                            @if (count($errors) > 0)
                                <ul class="list-group">
                                    @foreach ($errors->all() as $error)
                                        <li class="list-group-item text-danger">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>-->
                        <div class="form-group">
                            <span class="input-icon">
                                <!--{!! Form::input('email', 'email', null, ['class' => 'form-control', 'required' => '', 'pattern' => '^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$', 'oninvalid' => 'setCustomValidity("Please fill with valid email address.")', 'oninput' => 'setCustomValidity("")', 'title' => 'E-mail Address', 'placeholder' => trans('validation.attributes.frontend.email')]) !!}-->
                                {!! Form::email('email', null, ['class' => 'form-control', 'required' => '', 'pattern' => '^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$', 'oninvalid' => 'setCustomValidity("Please fill with valid email address.")', 'oninput' => 'setCustomValidity("")', 'title' => 'E-mail Address', 'placeholder' => trans('validation.attributes.frontend.email')]) !!}
                                <i class="fa fa-envelope"></i>
                            </span>
                        </div>
                        <div class="form-group form-actions">
                            <span class="input-icon">
                                {!! Form::input('password', 'password', null, ['class' => 'form-control password', 'required' => '', 'oninvalid' => 'setCustomValidity("Please fill out password.")', 'oninput' => 'setCustomValidity("")', 'title' => 'Password', 'placeholder' => trans('validation.attributes.frontend.password')]) !!}
                                <a class="forgot" href="{{ url('/password/reset') }}">{!! trans('labels.frontend.passwords.forgot_password') !!}</a>
                               <!-- {!! link_to('password/reset', trans('labels.frontend.passwords.forgot_password'), ['class' => 'forgot' ]) !!}-->
                                <i class="fa fa-lock"></i>
                            </span>
                        </div>
                        <div class="form-actions">
                            <div class="checkbox clip-check check-primary" style="float: left;">
                                {!! Form::checkbox('remember', null, null, ['id' => 'remember']) !!}
                                {!! Form::label('remember', trans('labels.frontend.auth.remember_me')) !!}
                            </div>
                            {!! Form::button('Login <i class="fa fa-arrow-circle-right"></i>', array('type' => 'submit', 'class' => 'btn btn-primary pull-right')) !!}
                            <!--<button type="submit" class="btn btn-primary pull-right">
                                Login <i class="fa fa-arrow-circle-right"></i>
                            </button>-->
                        </div>
                        <div class="new-account">
                            Don't have an account yet?
                            <a href="{{ url('register') }}">
                                Create an account
                            </a>
                        </div>
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