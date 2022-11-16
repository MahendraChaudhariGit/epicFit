@extends('masters.login')

@section('title', 'Signup | '.app_name())

@section('content')

    <div class="row">
        <div class="main-login col-md-4 col-md-offset-4">
            <div class="logo margin-top-30">
               <img  class="center-block" src="{{ asset('images/epic-icon.png') }}" alt="Clip-Two" style="width: 50px;" />
               <h1>EPIC<span>Result</span></h1>
            </div>
            <!-- start: SIGN-IN BOX -->
            {!! displayAlert()!!}
                {!! Form::open(['url' => 'login', 'class' => 'form-login']) !!}
                {!! Form::hidden('businessId', $businessId) !!}
            <div class="box-login">
                
               
                    <fieldset>
                        <div class="form-group">
                            <input type="text" placeholder="Username" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <input type="text" placeholder="Email" name="email"  class="form-control"  required>
                        </div>
                         <div class="form-group">
                            <input type="password" placeholder="Password" name="pwd" class="form-control"  required>
                        </div>
                         <div class="form-group">
                            <div class="country">
                            <select class="form-control">
                               
                             <option value="0">Country</option>
                                <option value="1">India</option>
                                 <option value="2">USA</option>
                                  <option value="2">Australia</option>
                        
                            </select>
                        </div>
                            <div class="city">
                            <select class="form-control">
                               
                             <option value="0">City</option>
                                <option value="1">Delhi</option>
                                 <option value="2">Noida</option>
                                  <option value="2">Gurgaon</option>
                            </select>
                        </div>

                        </div>

                       @include('partials.messages')
               
                    </fieldset>
                  
                    <!-- end: COPYRIGHT -->
               
            </div>
            <!-- end: REGISTER BOX -->

            <div class="signup-btn">
                <button type="submit" class="btn btn-primary">SignUp</button>

            </div><!--signup-btn-->

            <p class="note text-center">Already have an account? <span><a href="{{ route('login', $businessId) }}">LOGIN</a></span></p>

             {!! Form::close() !!}
    
        </div>
    </div>
    <!-- end: REGISTRATION -->
    @stop

    @section('scripts')
            <!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
    {!! Html::script('plugins/jquery-validation/jquery.validate.min.js') !!}
            <!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->

    <!-- start: JavaScript Event Handlers for this page -->
    {!! Html::script('js/login.js') !!}
            <!-- end: JavaScript Event Handlers for this page -->
    <script>
        jQuery(document).ready(function() {
            Main.init();
            Login.init();
        });
    </script>
@stop