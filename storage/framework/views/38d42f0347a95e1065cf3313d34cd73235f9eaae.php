<?php $__env->startSection('required-styles'); ?>
<?php echo Html::style('result/css/autocomplete.css'); ?>

<?php echo Html::style('result/plugins/tipped-tooltip/css/tipped/tipped.css'); ?>

<?php echo Html::style('result/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css'); ?>

<?php echo Html::style('result/parq-theme/goal.css'); ?>

<style type="text/css">
 textarea.form-control{
  min-height: 100px;
  resize: vertical;
  overflow:auto;
}
/*.outborder{
    border: 2px solid #f94211;
    padding: 0px 10px 10px 10px;
}*/
.outborder textarea.form-control:focus{
  min-height: 100px;
}
.outborder textarea.form-control[aria-invalid="false"]{
  min-height: 100px;
}
.outborder .help-block{
  margin:0px;
}
.modal-popup{
/* background: #212121;
 color: white;*/
 font-weight: normal;
}
.modal-popup .btn-default {
  color: #fff;
  background-color: #f94211 !important;
  border-color: #f94211 !important;
}
.modal-popup .modal-footer{
  text-align: center;
}
.question-mark{
  font-size: 25px;
  color: #f94211;
}
.content-right .form-group .tooltip-sign.tooltip_btn{
  display: inline-block;
  vertical-align: top;
}
.content-right .form-group .tooltip_btn i.question-mark{
  font-size: 25px;
  color: #f94211;
}
.content-right .form-group .tooltip-sign.tooltip_btn:hover{
  text-decoration: none;
}
.show_task-section ul{
   padding-left: 18px;
}
@media (max-width: 768px){
  table th, table td{
   padding:4px !important;
  }
  #client-datatable tr td:nth-child(3) {
    display: none
}
  #client-datatable-task tr td:nth-child(2),  #client-datatable-task tr td:nth-child(3) {
    display: none
}
/*.picCropModel .modal-dialog{
  width: 90%;
}*/
}
.general-notes-button{
     text-align: left;
    width: max-content;
    float: left;
    margin-right: 10px;
}
.general-notes-button button{
    color: #fff;
    background-color: #f94211;
    border-color: #f94211;
}
#general-notes-popup .modal-header .close {
    margin-top: -8px;
}
.table-responsive table td, .table-responsive table th{
      font-size: 12px;
      padding: 2px !important;
      min-width: 78px;
}
.table-responsive table td {
      word-break: break-word;
      white-space: initial !important;
}
.table-responsive table th{
  vertical-align: top !important;
}
textarea::-webkit-scrollbar {
  display: none;
}

/* Hide scrollbar for IE, Edge add Firefox */
textarea{
  -ms-overflow-style: none;
  scrollbar-width: none; /* Firefox */
}
.bootstrap-select.open.show .dropdown-menu.open.show{
  display: block !important;
}
.bootstrap-select.open .dropdown-menu.open{
  display: none !important;
}
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>


<span class="inmotion-total-slides hidden-xs question-section">QUESTIONS<br>
  <span class="inmotion-ts-active-num question-step">01</span>
  <span class="inmotion-ts-active-separator">/</span>
  <span class="inmotion-ts-active-all all-question-step">20</span>
</span>

<span class="qodef-grid-line-right">
  <span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:-450, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); ">
    
  </span>
</span>

<div class="container-fluid">
  <div class="watermark"><p>EPIC GOAL</p></div>
   <div class="row row-height">
      <div class="col-xl-6 col-lg-6 col-md-5 col-xs-11 content-left">
       <img src="<?php echo e(asset('assets/images/logo-epic.png')); ?>" alt="" class="img-fluid logo-img"> 
       <div class="content-left-wrapper">
          <img src="<?php echo e(asset('assets/images/BM-slimming_1.png')); ?>" alt="" class="img-fluid slide-img"> 
     </div>
         <img id="pot" src="<?php echo e(asset('assets/images/h1-slider-img-1.png')); ?>" alt="" class="img-fluid">
      </div>

      <div class="col-xl-6 col-lg-5 col-md-5 col-xs-12 content-right" id="start">        
                   
         <div class="wizard_container" id="wizard_container">
         

            <div id="top-wizard">
                <h2 class="steps-name">DEFINE YOUR GOAL</h2>
            </div>
            <input type="hidden" id="goal_type">
            <input type="hidden" id="goal_due_date">
            <input type="hidden" id="goal_start_date">
            <input type="hidden" id="goal_buddy_id">
            <input type="hidden" id="goal_template_id">
            
            <input type="hidden" id="current_habit_step" value="1">
            <input type="hidden" id="total_habit_step" value="1">
            <input type="hidden" id="current_task_step" value="1">
            <input type="hidden" id="total_task_step" value="1">
            
            <input type="hidden" id="add_new_task" value="0">
            <input type="hidden" id="edit_task" value="0">

            <input type="hidden" id="edit_goal" value="0">


            <input type="hidden" id="last_form_edit_habit" value="0">
            <input type="hidden" id="last_form_edit_task" value="0">

            <input type="hidden" id="section_completed" value="0">

            <!-- <input type="hidden" id="choose_immediate_priority" value="0"> -->
            <form id="wrapped" class="goal_form" method="post" enctype="multipart/form-data">
              
            </form>
                  <!-- /middle-wizard -->
      <div id="bottom-wizard">
                    
         <span class="qodef-grid-line-center"><span class="inmotion-total-slides">
            


            <div class="d-flex">
              
              <a href="#" class="step-back formStepfirst"><span class="prev-name">DEFINE YOUR GOAL</span></a>&nbsp;&nbsp;
              <a href="#" class="arrow step-back">&#8672;</a>               
              
              <div class="current-section">DEFINE YOUR GOAL</div>
              
              <a href="#" class="arrow step-forward">&#8674;</a>&nbsp;&nbsp;
              <a  href="#" class="step-forward formStepSecond"><span class="next-name">ESTABLISH YOUR MILE STONES</span></a>
            
            </div>
         


            <span class="inmotion-ts-active-num section-step">01</span>
            <span class="inmotion-ts-active-separator">/</span>
            <span class="inmotion-ts-active-all all-section-step">05</span>
            </span>
            <span class="inmotion-total-slides visible-xs question-s question-section">QUESTIONS<br>
            <span class="inmotion-ts-active-num question-step">5</span>
            <span class="inmotion-ts-active-separator">/</span>
            <span class="inmotion-ts-active-all">20</span>
            </span>
            <span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>
        </span>
            <div class="row">
            <div class="col-sm-5 col-xs-5">
               <button type="button" name="backward" class="backward">Prev</button>
             </div>
            <div class="col-sm-7 col-xs-7">
               <div class="general-notes-button note_area">

          <a href="#" class="btn btn-primary add-note" data-toggle="modal" data-target="#general-notes-popup"><i class="fa fa-plus"></i>  Notes</a>


        </div>
            <!-- Scroll Top for each step -->
            <a href="#wizard_container" id="scroll_top"></a>

            <button type="button" name="forward" class="forward data-save nextData">Next</button>
            <button type="button" class="submit submit-step final-step-submit">Submit</button>
           </div>
            </div>
            <div class="back_to_goal_list backto_dashboard">
                <button type="button" name="" class="btn">Back to Goal List</button>
            </div>
          </div>
         </div>
      </div>
   </div>
</div>
<div class="modal picCropModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Choose Headshot </h4>
         </div>
         <div class="modal-body">
            <div class="btn-group m-b-10">
               <a class="btn btn-primary btn-o toggle-ratio" href="#" data-ratio="1" data-crop-selector="square">Square</a>
               <a class="btn btn-primary btn-o toggle-ratio" href="#" data-ratio="0" data-crop-selector="rectangle">Rectangle</a>
            </div>
            <div class="center">
               <img alt="Loading..." class="preview" />
               <input type="hidden" name="ui-x1" />
               <input type="hidden" name="ui-y1" />
               <input type="hidden" name="ui-w" />
               <input type="hidden" name="ui-h" />
               <input type="hidden" name="widthScale" />
               <input type="hidden" name="heightScale" />
               <input type="hidden" name="photoName">
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary save">Save</button>
         </div>
      </div>
   </div>
</div>
<!--goal modal-->
<!-- Modal -->


<div id="general-notes-popup" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">

      <div class="modal-header">
      
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
         <h4 style='color: #f94211'><b>Enter notes</b></h4>
          <textarea class="form-control" id="goal_notes" name="" placeholder="General Notes"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div> 
<div id="goal-step" class="modal fade" role="dialog">
   <div class="modal-dialog">
     <!-- Modal content-->
     <div class="modal-content modal-popup">
       <div class="modal-body">
          <h4 style='color: #f94211'><b>TOOL TIP</b></h4>
           <p class="message"></p>
       </div>
       <div class="modal-footer">
         <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
       </div>
     </div>
 
   </div>
 </div>
 <!--template modal-->

 <div id="temp-modal" class="temp-modal modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog"> 

    <!-- Modal content-->
    <div class="modal-content modal-popup">
       
      <div class="modal-body">
         
      <div class="row">
         <div class="form-group col-md-3 col-xs-4">
            <label class="container_radio version_2"><img src="<?php echo e(asset('result/images/weightmanagement.jpg')); ?>">
            <input type="radio" name="template" data-from="popup" value="1" data-id='1'>
            <span class="checkmark"></span>
            </label>
         </div>
            <div class="form-group col-md-3 col-xs-4">
            <label class="container_radio version_2"><img src="<?php echo e(asset('result/images/drop_a_size.jpg')); ?>">
            <input type="radio" name="template" data-from="popup" value="2" data-id='2'>
            <span class="checkmark"></span>
            </label>
         </div>
            <div class="form-group col-md-3 col-xs-4">
            <label class="container_radio version_2"><img src="<?php echo e(asset('result/images/eat.jpg')); ?>">
            <input type="radio" name="template" data-from="popup" value="3" data-id='3'>
            <span class="checkmark"></span>
            </label>
         </div>
            <div class="form-group col-md-3 col-xs-4">
            <label class="container_radio version_2"><img src="<?php echo e(asset('result/images/improve_h.jpg')); ?>">
            <input type="radio" name="template" data-from="popup" value="4" data-id='4'>
            <span class="checkmark"></span>
            </label>
         </div>
            <div class="form-group col-md-3 col-xs-4">
            <label class="container_radio version_2"><img src="<?php echo e(asset('result/images/reduce_stress.jpg')); ?>">
            <input type="radio" name="template" data-from="popup" value="5" data-id='5'>
            <span class="checkmark"></span>
            </label>
         </div>
            <div class="form-group col-md-3 col-xs-4">
            <label class="container_radio version_2"><img src="<?php echo e(asset('result/images/improve_my_sleep.jpg')); ?>">
            <input type="radio" name="template" data-from="popup" value="6" data-id='6'>
            <span class="checkmark"></span>
            </label>
         </div>
            <div class="form-group col-md-3 col-xs-4">
            <label class="container_radio version_2"><img src="<?php echo e(asset('result/images/improve_health.jpg')); ?>">
            <input type="radio" name="template" data-from="popup" value="7" data-id='7'>
            <span class="checkmark"></span>
            </label>
         </div>
            <div class="form-group col-md-3 col-xs-4">
            <label class="container_radio version_2"><img src="<?php echo e(asset('result/images/injury.jpg')); ?>">
            <input type="radio" name="template" data-from="popup" value="8" data-id='8'>
            <span class="checkmark"></span>
            </label>
         </div>
            <div class="form-group col-md-3 col-xs-4">
            <label class="container_radio version_2"><img src="<?php echo e(asset('result/images/increase_activity.jpg')); ?>">
            <input type="radio" name="template" data-from="popup" value="9" data-id='9'>
            <span class="checkmark"></span>
            </label>
         </div>
            <div class="form-group col-md-3 col-xs-4">
            <label class="container_radio version_2"><img src="<?php echo e(asset('result/images/balance.jpg')); ?>">
            <input type="radio" name="template" data-from="popup" value="10" data-id='10'>
            <span class="checkmark"></span>
            </label>
         </div>
            <div class="form-group col-md-3 col-xs-4">
            <label class="container_radio version_2"><img src="<?php echo e(asset('result/images/health.jpg')); ?>">
            <input type="radio" name="template" data-from="popup" value="11" data-id='11'>
            <span class="checkmark"></span>
            </label>
         </div>
            <div class="form-group col-md-3 col-xs-4">
            <label class="container_radio version_2"><img src="<?php echo e(asset('result/images/improve_posture.jpg')); ?>">
            <input type="radio" name="template" data-from="popup" value="12" data-id='12'>
            <span class="checkmark"></span>
            </label>
         </div>
            <div class="form-group col-md-3 col-xs-4">
            <label class="container_radio version_2"><img src="<?php echo e(asset('result/images/time_man.jpg')); ?>">
            <input type="radio" name="template" data-from="popup" value="13" data-id='13'>
            <span class="checkmark"></span>
            </label>
         </div>
            <div class="form-group col-md-3 col-xs-4">
            <label class="container_radio version_2"><img src="<?php echo e(asset('result/images/improve_per.jpg')); ?>">
            <input type="radio" name="template" data-from="popup" value="14" data-id='14'>
            <span class="checkmark"></span>
            </label>
         </div>
            <div class="form-group col-md-3 col-xs-4">
            <label class="container_radio version_2"><img src="<?php echo e(asset('result/images/improve_c.jpg')); ?>">
            <input type="radio" name="template" data-from="popup" value="15" data-id='15'>
            <span class="checkmark"></span>
            </label>
         </div>
            <div class="form-group col-md-3 col-xs-4">
            <label class="container_radio version_2"><img src="<?php echo e(asset('result/images/become_proactive.jpg')); ?>">
            <input type="radio" name="template" data-from="popup" value="16" data-id='16'>
            <span class="checkmark"></span>
            </label>
         </div>
      </div>
      </div>
      <div class="modal-footer">
         <a type="button" class="btn btn-default choose-immediate-priority" disabled="true" style="background:black;color: white;">Continue with above selected goal</a>
       
        <button type="button" class="btn btn-default same-template" data-dismiss="modal">No, I am ok with previously selected goal</button>
         <a type="button" class="btn-default btn choose-create-new-goal popup-create-new-goal">Create New Goal</a>
      </div>
    </div>

  </div>
</div>
 <!-- <?php echo Html::script('result/js/autocomplete.js?v='.time()); ?> -->
<script type="text/javascript">

    $(document).ready(function() {
       $(window).scroll(function() {
     $('.bootstrap-select').removeClass('open');
    });
    });

    $(document).on('click','.goal-step',function(){
         goal_type = $("#goal_type").val();
         if(goal_type == 'create_new_goal'){
            var message = $(this).data('message');
         }else if(goal_type == 'choose_form_template'){
            var message = $(this).data('message1');
         }
         $(this).attr('data-toggle','modal')
         $(this).attr('data-target','#goal-step')
         $("#goal-step").attr('aria-modal',true)
         $("#goal-step").addClass('in')
         
         $("#goal-step").find('.message').html(message);
         $("#goal-step").find('.message').css('color','#333');


      })


</script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('required-script'); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('Result.goal-buddy.main_goal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/mahendra/Documents/projects/epicfitlaravelv6/resources/views/Result/goal-buddy/goal/create.blade.php ENDPATH**/ ?>