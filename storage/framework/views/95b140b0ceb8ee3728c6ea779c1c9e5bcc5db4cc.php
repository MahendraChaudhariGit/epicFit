

<?php $__env->startSection('title', 'Login | Epic Result'); ?>
<?php $__env->startSection('content'); ?>
<!-- <?php
    $page_name = session()->get( 'page_name' );
    $data[] = session()->get( 'data' );
?> -->
    <div class="row">
        <div class="main-login col-md-4 col-md-offset-6 col-sm-5 col-sm-offset-6">
            
            <!-- start: SIGN-IN BOX -->
            <?php echo displayAlert(); ?>

                <?php echo Form::open(['url' => 'login', 'class' => 'form-login']); ?>

                <?php echo Form::hidden('businessUrl', $businessUrl); ?>

                <input type="hidden" name="businessUrl" value="">
                <div class="box-login">
                    <div class="logo">
                        <img  class="center-block" src="<?php echo e(asset('result/images/epic-result-logo.png')); ?>" alt="Clip-Two" />
                        
                    </div>
               
                    <fieldset>
                       
                       <?php echo $__env->make('Result.partials.messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                      
                        
                        <div class="form-group">
                            <span class="input-icon">
                                <?php echo Form::email('uname', null, ['class' => 'form-control', 'required' => '', 'pattern' => '^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$', 'oninvalid' => 'setCustomValidity("Please fill with valid '.trans('validation.attributes.frontend.email').'.")', 'oninput' => 'setCustomValidity("")', 'title' => trans('validation.attributes.frontend.email'), 'placeholder' => trans('validation.attributes.frontend.email')]); ?>

                                <!-- <i class="fa fa-envelope"></i> -->
                            </span>
                        </div>
                        <div class="form-group form-actions">
                            <span class="input-icon">
                                <?php echo Form::password('password', ['class' => 'form-control password', 'required' => '', 'oninvalid' => 'setCustomValidity("Please fill out '.trans('validation.attributes.frontend.email').'.")', 'oninput' => 'setCustomValidity("")', 'title' => trans('validation.attributes.frontend.password'), 'placeholder' => trans('validation.attributes.frontend.password')]); ?>

                              
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
                  <!-- <input type="hidden"  name="my_challenge" value="<?php echo e($page_name ? $page_name:''); ?>">
                  <input type="hidden"   name="challenge_data[]" value="<?php echo e($data ? $data:''); ?>"> -->
                  <!-- challenge data end  -->
                    <!-- end: COPYRIGHT -->
                    <div class="form-actions">
                            <div>
                                 <?php echo HTML::decode(link_to('password/reset', trans('labels.frontend.passwords.forgot_password'), ['class' => 'forgot shortTxt' ])); ?>                                
                            </div>                            
                            <?php echo Form::button('Login <!-- <i class="fa fa-arrow-circle-right"></i> -->', array('type' => 'button', 'class' => 'btn btn-primary pull-right clientLogin')); ?>

                        <div class="checkbox clip-check check-primary" style="float: left;">
                                <?php echo Form::checkbox('remember', null, null, ['id' => 'remember']); ?>

                                <?php echo Form::label('remember', trans('labels.frontend.auth.remember_me')); ?>

                            </div>
                        </div>
                </div>
            <!-- end: REGISTER BOX -->


   
                        <!-- <p class="signup-text">Don't have an account ? <span><a href="">signup now</a></span></p> -->

                    <!-- start: COPYRIGHT -->
                    <div class="copyright">
                        &copy; <span class="current-year"></span><span class="text-bold text-uppercase"> EPIC TRAINER</span>. <span>All rights reserved</span>
                    </div>
                     <?php echo Form::close(); ?>




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
    <?php $__env->stopSection(); ?>

    <?php $__env->startSection('scripts'); ?>
    <?php echo Html::style('result/plugins/sweetalert/sweet-alert.css'); ?>

            <!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
    <?php echo Html::script('result/plugins/jquery-validation/jquery.validate.min.js?v='.time()); ?>

            <!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->

    <!-- start: JavaScript Event Handlers for this page -->
    <?php echo Html::script('result/js/login.js?v='.time()); ?>

   
    <?php echo Html::script('result/plugins/sweetalert/sweet-alert.min.js'); ?>

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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('Result.masters.login', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/mahendra/Documents/projects/epicFit/epicfitlaravelv6/resources/views/Result/login.blade.php ENDPATH**/ ?>