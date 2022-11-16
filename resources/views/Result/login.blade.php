@extends('Result.masters.login')

@section('title', 'Login | Epic Result')
@section('content')
<!-- @php
    $page_name = session()->get( 'page_name' );
    $data[] = session()->get( 'data' );
@endphp -->
    <div class="row">
        <div class="main-login col-md-4 col-md-offset-6 col-sm-5 col-sm-offset-6">
            
            <!-- start: SIGN-IN BOX -->
            {!! displayAlert()!!}
                {!! Form::open(['url' => 'login', 'class' => 'form-login']) !!}
                {!! Form::hidden('businessUrl', $businessUrl) !!}
                <input type="hidden" name="businessUrl" value="">
                <div class="box-login">
                    <div class="logo">
                        <img  class="center-block" src="{{ asset('result/images/epic-result-logo.png') }}" alt="Clip-Two" />
                        {{-- <h1>EPIC<span>Result</span></h1>/ --}}
                    </div>
               
                    <fieldset>
                       
                       @include('Result.partials.messages')
                      
                        
                        <div class="form-group">
                            <span class="input-icon">
                                {!! Form::email('uname', null, ['class' => 'form-control', 'required' => '', 'pattern' => '^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$', 'oninvalid' => 'setCustomValidity("Please fill with valid '.trans('validation.attributes.frontend.email').'.")', 'oninput' => 'setCustomValidity("")', 'title' => trans('validation.attributes.frontend.email'), 'placeholder' => trans('validation.attributes.frontend.email')]) !!}
                                <!-- <i class="fa fa-envelope"></i> -->
                            </span>
                        </div>
                        <div class="form-group form-actions">
                            <span class="input-icon">
                                {!! Form::password('password', ['class' => 'form-control password', 'required' => '', 'oninvalid' => 'setCustomValidity("Please fill out '.trans('validation.attributes.frontend.email').'.")', 'oninput' => 'setCustomValidity("")', 'title' => trans('validation.attributes.frontend.password'), 'placeholder' => trans('validation.attributes.frontend.password')]) !!}
                              
                               <!--  <i class="fa fa-lock"></i> -->
                            </span>
                        </div>
                        
                       <!-- <div class="new-account">
                            Don't have an account yet?
                            <a href="">
                                Create an account
                            </a>
                        </div>-->
                    </fieldset>
                  <!-- challenge data -->
                  <!-- <input type="hidden"  name="my_challenge" value="{{$page_name ? $page_name:''}}">
                  <input type="hidden"   name="challenge_data[]" value="{{$data ? $data:''}}"> -->
                  <!-- challenge data end  -->
                    <!-- end: COPYRIGHT -->
                    <div class="form-actions">
                            <div>
                                 {!! HTML::decode(link_to('password/reset', trans('labels.frontend.passwords.forgot_password'), ['class' => 'forgot shortTxt' ])) !!}                                
                            </div>                            
                            {!! Form::button('Login <!-- <i class="fa fa-arrow-circle-right"></i> -->', array('type' => 'button', 'class' => 'btn btn-primary pull-right clientLogin')) !!}
                        <div class="checkbox clip-check check-primary" style="float: left;">
                                {!! Form::checkbox('remember', null, null, ['id' => 'remember']) !!}
                                {!! Form::label('remember', trans('labels.frontend.auth.remember_me')) !!}
                            </div>
                        </div>
                </div>
            <!-- end: REGISTER BOX -->


   
                        <!-- <p class="signup-text">Don't have an account ? <span><a href="">signup now</a></span></p> -->

                    <!-- start: COPYRIGHT -->
                    <div class="copyright">
                        &copy; <span class="current-year"></span><span class="text-bold text-uppercase"> EPIC TRAINER</span>. <span>All rights reserved</span>
                    </div>
                     {!! Form::close() !!}



        </div>
    </div>
    <!-- end: REGISTRATION -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Choose Business</h4>
                </div>
                <div class="modal-body">
                <form class="model-form">
                <div class="form-group m-b-0 businessList" style="margin-bottom: 0px">
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
    @stop

    @section('scripts')
    {!! Html::style('result/plugins/sweetalert/sweet-alert.css') !!}
            <!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
    {!! Html::script('result/plugins/jquery-validation/jquery.validate.min.js?v='.time()) !!}
            <!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->

    <!-- start: JavaScript Event Handlers for this page -->
    {!! Html::script('result/js/login.js?v='.time()) !!}
   
    {!! Html::script('result/plugins/sweetalert/sweet-alert.min.js') !!}
            <!-- end: JavaScript Event Handlers for this page -->
    <script>
        jQuery(document).ready(function() {
            Main.init();
            Login.init();
        });

//     jQuery(document).ready(function() {
//       var challenge_status = $('#challenge_status').val();
//         if(challenge_status == 'my_challenge'){
//              var toaster_message = $('#toaster_message').val();
//              if(toaster_message == 'Accepted'){
//                swal({
//                         type: 'success',
//                         title:'Challenge Accepted Sucessfully !',
//                         allowOutsideClick: false,
//                         showCancelButton: false,
//                         confirmButtonColor: '#ff4401',
//                     }); 
//               }

//               if(toaster_message == 'Rejected'){
//                swal({
//                         type: 'warning',
//                         title: 'Challenge has been Rejected !',
//                         allowOutsideClick: false,
//                         showCancelButton: false,
//                         confirmButtonColor: '#ff4401',
//                     }); 
//               }          
//         }
//    });
   /* end */
 </script>
@stop