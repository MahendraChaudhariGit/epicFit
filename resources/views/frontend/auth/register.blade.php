@extends('frontend.layouts.login')

@section('title', 'Register | '.app_name())

{!! Html::style('assets/plugins/intl-tel-input-master/build/css/intlTelInput.css') !!}
<style type="text/css">
    .intl-tel-input {
    display: inherit;
}
.intl-tel-input.allow-dropdown input{
    padding-left: 52px !important;
}
.intl-tel-input .country-list{
    z-index: 999;
    white-space: normal;
}
.intl-tel-input .country-list .country{

    width: 100%;
    float: none;
    border:0px;
    min-width: 290px;
}
</style>
@section('content')
<div class="row">
    <div class="main-login col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
        <div class="logo margin-top-30">
            <img  class="center-block" src="{{ asset('assets/images/epic-icon.png') }}" alt="Clip-Two" style="width: 50px;" />
               <h1>EPIC<span>Trainer</span></h1>
        </div>
        <!-- start: REGISTER BOX -->
         {!! Form::open(['url' => 'register', 'class' => 'form-register']) !!}
        <div class="box-register">
           <input type="hidden" id="register-val" value="register">

                <fieldset>
                   <!--  <legend>
                        Sign Up
                    </legend>
                     -->
                    @include('includes.partials.messages')  
                    
                   <!--  <p>
                        Enter your personal details below:
                    </p> -->

                    <div class="form-group">
                        {!! Form::input('text', 'name', null, ['class' => 'form-control', 'required' => '', 'oninvalid' => 'setCustomValidity("Please fill out '.trans('validation.attributes.frontend.full_name').'.")', 'oninput' => 'setCustomValidity("")', 'maxlength' => 255, 'title' => trans('validation.attributes.frontend.full_name'), 'placeholder' => trans('validation.attributes.frontend.full_name')]) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::input('text', 'last_name', null, ['class' => 'form-control', 'required' => '', 'oninvalid' => 'setCustomValidity("Please fill out '.trans('validation.attributes.frontend.last_name').'.")', 'oninput' => 'setCustomValidity("")', 'maxlength' => 255, 'title' => trans('validation.attributes.frontend.last_name'), 'placeholder' => trans('validation.attributes.frontend.last_name')]) !!}
                    </div>

                    {{-- <div class="form-group">
                        {!! Form::input('text', 'web_url', null, ['class' => 'form-control', 'required' => '', 'oninvalid' => 'setCustomValidity("Please fill out required url.")', 'oninput' => 'setCustomValidity("")', 'maxlength' => 255, 'title' => 'Required URL', 'placeholder' => 'http://crm.epictrainer.com/{YOUR_URL}']) !!}
                    </div> --}}
                      <div class="row no-margin">
                        <div class="form-group col-md-6 no-padding">
                            {!! Form::input('text', null, url('/').'/login/', ['class' => 'form-control', 'title' => 'Required URL', 'readonly' => 'readonly']) !!}
                        </div>
                        <div class="form-group col-md-6 no-padding">
                          {!! Form::input('text', 'web_url', null, ['class' => 'form-control', 'required' => '', 'oninvalid' => 'setCustomValidity("Please fill out required url.")', 'oninput' => 'setCustomValidity("")', 'maxlength' => 255, 'title' => 'Required URL', ]) !!}
                      </div>
                  </div>
                    <div class="form-group">
                        <span class="input-icon">
                        	{!! Form::email('email', null, ['class' => 'form-control', 'required' => '', 'pattern' => '^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$', 'oninvalid' => 'setCustomValidity("Please fill with valid '.trans('validation.attributes.frontend.email').'.")', 'oninput' => 'setCustomValidity("")', 'title' => trans('validation.attributes.frontend.email'), 'placeholder' => trans('validation.attributes.frontend.email')]) !!}
                         <!--    <i class="fa fa-envelope"></i> -->
                        </span>
                    </div>
                    <div class="form-group">
                        <span class="input-icon">
                        	{!! Form::password('password', ['class' => 'form-control', 'required' => '', 'oninvalid' => 'setCustomValidity("Please fill out '.trans('validation.attributes.frontend.password').'.")', 'oninput' => 'setCustomValidity("")', 'title' => trans('validation.attributes.frontend.password'), 'placeholder' => trans('validation.attributes.frontend.password')]) !!}
                           <!--  <i class="fa fa-lock"></i> -->
                        </span>
                    </div>
                    <div class="form-group">
                        <span class="input-icon">
                        	{!! Form::password('password_confirmation', ['class' => 'form-control', 'required' => '', 'oninvalid' => 'setCustomValidity("Please fill out '.trans('validation.attributes.frontend.password_confirmation').'.")', 'oninput' => 'setCustomValidity("")', 'title' => trans('validation.attributes.frontend.password_confirmation'), 'placeholder' => trans('validation.attributes.frontend.password_confirmation')]) !!}
                          <!--   <i class="fa fa-lock"></i> -->
                        </span>
                    </div>
                    <div class="form-group">
                        <span class="input-icon">
                            <input type="hidden" name="country_code" id="country_code">
                            {!! Form::input('tel', 'telephone', null, ['class' => 'form-control countryCode numericField', 'maxlength' => '16', 'minlength' => '5', 'data-realtime' => 'phone', 'required' => '', 'oninvalid' => 'setCustomValidity("Please fill with valid '.trans('validation.attributes.frontend.telephone').'.")', 'oninput' => 'setCustomValidity("")', 'title' => trans('validation.attributes.frontend.telephone'), 'placeholder' => trans('validation.attributes.frontend.telephone')]) !!}
                          <!--   <i class="fa fa-phone"></i> -->
                        </span>
                    </div>
                    <div class="form-group">
                        <span class="input-icon">
                        	{!! Form::input('text', 'referral', null, ['class' => 'form-control', 'required' => '', 'oninvalid' => 'setCustomValidity("Please fill out '.trans('validation.attributes.frontend.referral').'.")', 'oninput' => 'setCustomValidity("")', 'maxlength' => 255, 'title' => trans('validation.attributes.frontend.referral'), 'placeholder' => trans('validation.attributes.frontend.referral')]) !!}                            
                           <!--  <i class="fa fa-paw"></i> -->
                        </span>
                    </div>


                    <div class="panel panel-transparent">
                        <div class="panel-heading no-padding" style="min-height: 0px">
                            <h4 class="panel-title">What are your expectations?</h4>
                        </div>
                        <div class="panel-body  no-padding">
                            <div class="checkbox clip-check check-primary">
                                {!! Form::checkbox('client_management', '1', null, ['id' => 'client_management']) !!}
                                {!! Form::label('client_management', 'Client Management') !!}
                            </div>
                            <div class="checkbox clip-check check-primary">
                                {!! Form::checkbox('business_support', '1', null, ['id' => 'business_support']) !!}
                                {!! Form::label('business_support', 'Business Support') !!}
                            </div>
                            <div class="checkbox clip-check check-primary">
                                {!! Form::checkbox('Knowledge', '1', null, ['id' => 'Knowledge']) !!}
                                {!! Form::label('Knowledge', 'Knowledge') !!}
                            </div>
                            <div class="checkbox clip-check check-primary">
                                {!! Form::checkbox('resources', '1', null, ['id' => 'resources']) !!}
                                {!! Form::label('resources', 'Tools & Resources') !!}
                            </div>
                            <div class="checkbox clip-check check-primary">
                                {!! Form::checkbox('mentoring', '1', null, ['id' => 'mentoring']) !!}
                                {!! Form::label('mentoring', 'Mentoring') !!}
                            </div>
                        </div>
                    </div>


                   
				</fieldset>
                <!-- start: COPYRIGHT -->
              
                <!-- end: COPYRIGHT -->
           
        </div>
        <!-- end: REGISTER BOX -->


         <div class="form-group">
                        <div class="checkbox clip-check check-primary"><!--style="width: 200px;"-->

                            {!! Form::checkbox('agree', '1', null, ['id' => 'agree']) !!}
                            {!! HTML::decode(Form::label('agree', 'I agree to the <a href="javascript.void(0)">terms</a> of service and <a href="javascript.void(0)">privacy policy</a>.')) !!}
                        </div>
                    </div>
                    <div class="g-recaptcha" data-sitekey="6LdR8O0ZAAAAAAuux2vOxD3MCTvAi1HxdKBSGmec"></div>

                    <div class="form-group form-actions">
                        {!! Form::button('<!-- <i class="fa fa-arrow-circle-right"></i>  -->'.trans('labels.frontend.auth.register_button'), array('type' => 'submit', 'class' => 'btn btn-primary pull-right')) !!}
                    </div>
                     <p class="new-account">
                            Already have an account?
                            <a href="{{ url('login') }}">
                                Login
                            </a>
                        </p>
                          <div class="copyright">
                    &copy; <span class="current-year"></span><span class="text-bold text-uppercase"> EPIC TRAINER</span>. <span>All rights reserved</span>
                </div>
                 {!! Form::close() !!}
    </div>
</div>
<!-- end: REGISTRATION -->
@stop

@section('scripts')
    {!! Html::script('assets/js/helper.js?v='.time()) !!}
  <!-- start: Country Code Selector -->
  {!! Html::script('assets/plugins/intl-tel-input-master/build/js/utils.js?v='.time()) !!}
  {!! Html::script('assets/plugins/intl-tel-input-master/build/js/intlTelInput.js?v='.time()) !!}
  <!-- end: Country Code Selector -->

        <!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
{{-- <!--{!! Html::script('vendor/jquery-validation/jquery.validate.min.js?v='.time()) !!}--> --}}
        <!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->

<!-- start: JavaScript Event Handlers for this page -->
{!! Html::script('assets/js/login.js?v='.time()) !!}
        <!-- end: JavaScript Event Handlers for this page -->
        <script src="https://www.google.com/recaptcha/api.js?render=6LdR8O0ZAAAAAEFSaX-6XN0PDtPPx5kWKcOLA2-Y"></script>
<script>

    jQuery(document).ready(function() {
        $("#country_code").val($('.dial-code').html())
        $(".country").on('click',function(){
            $("#country_code").val($(this).find('.dial-code').html())
        })
        Main.init();
        Login.init();
    });
</script>
@stop