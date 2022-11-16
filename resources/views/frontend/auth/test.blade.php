?v='.time()@extends('frontend.layouts.login')

@section('content')
<div class="row">
    <div class="main-login col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
        <div class="logo margin-top-30">
            <img src="assets/images/logo.png" alt="Clip-Two"/>
        </div>
        <!-- start: REGISTER BOX -->
        <div class="box-register">
            <form class="form-register">
                <fieldset>
                    <legend>
                        Sign Up
                    </legend>
                    <p>
                        Enter your personal details below:
                    </p>
                    <div class="form-group">
                        <input type="text" class="form-control" name="full_name" placeholder="Full Name">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="address" placeholder="Address">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="city" placeholder="City">
                    </div>
                    <div class="form-group">
                        <label class="block">
                            Gender
                        </label>
                        <div class="clip-radio radio-primary">
                            <input type="radio" id="rg-female" name="gender" value="female">
                            <label for="rg-female">
                                Female
                            </label>
                            <input type="radio" id="rg-male" name="gender" value="male">
                            <label for="rg-male">
                                Male
                            </label>
                        </div>
                    </div>
                    <p>
                        Enter your account details below:
                    </p>
                    <div class="form-group">
								<span class="input-icon">
									<input type="email" class="form-control" name="email" placeholder="Email">
									<i class="fa fa-envelope"></i> </span>
                    </div>
                    <div class="form-group">
								<span class="input-icon">
									<input type="password" class="form-control" id="password" name="password" placeholder="Password">
									<i class="fa fa-lock"></i> </span>
                    </div>
                    <div class="form-group">
								<span class="input-icon">
									<input type="password" class="form-control" name="password_again" placeholder="Password Again">
									<i class="fa fa-lock"></i> </span>
                    </div>
                    <div class="form-group">
                        <div class="checkbox clip-check check-primary">
                            <input type="checkbox" id="agree" value="agree">
                            <label for="agree">
                                I agree
                            </label>
                        </div>
                    </div>
                    <div class="form-actions">
                        <p>
                            Already have an account?
                            <a href="login_signin.html">
                                Log-in
                            </a>
                        </p>
                        <button type="submit" class="btn btn-primary pull-right">
                            Submit <i class="fa fa-arrow-circle-right"></i>
                        </button>
                    </div>
                </fieldset>
            </form>
            <!-- start: COPYRIGHT -->
            <div class="copyright">
                &copy; <span class="current-year"></span><span class="text-bold text-uppercase"> ClipTheme</span>. <span>All rights reserved</span>
            </div>
            <!-- end: COPYRIGHT -->
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