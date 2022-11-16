<div class="step data-step" data-step="11">

                    <div class="row">
                      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
                      <div class="heading-text border-head mb-10">
                        <div class="watermark1" data-id="10"><span>10.</span></div>

                        <label class="steps-head">What is the <strong>due date</strong> for this <strong>EPIC </strong>Goal? * </label>
                      </div>

                      <div class="tooltip-sign mb-10">
                         <a href="javascript:void(0)" class="goal-step" 
                           data-message="A realistic <span style='color:#f64c1e;'><b>EPIC</b> Goal</span> date must be considered, and the requirements and expectations need to be addressed. 
                           <br/><br/>
                           • how much can you lose each week sustainably? <br/>
                           • Are they willing to commit to the required tasks? <br/>
                           • Are they willing to make lifestyle changes in order to meet the goal?
                           <br/><br/>
                       When using the Date Selector - Be sure to select a realistic due date for your <span style='color:#f64c1e;'><b>EPIC</b> Goal</span> and do not set yourself up failure before beginning. You must consider your willingness to commit to your habit related tasks, the <span style='color:#f64c1e;'><b>EPIC</b> Goal</span> may need to be adjusted/lowered if the date cannot be moved (special occasion date)"
                           data-message1="<b>Date Selector-</b> Be sure to select a realistic due date for your Goal and do not set yourself up failure before beginning. You must consider your willingness to commit to your habit related tasks. "><i class="fa fa-question-circle question-mark"></i></a>
                      </div>

                    </div>
                     <div class="form-group">
                        <input type="text" data-toggle="tooltip" title="" class="form-control vdp" name="due_date" id='datepicker_SYG' autocomplete="off" value="{{isset($goalDetails)? $goalDetails->goal_due_date:null}}"  required data-provide="datepicker">
                     </div>
                  </div>
                  
<script type="text/javascript">
  $("#datepicker_SYG").datepicker({
    todayHighlight: 'TRUE',
    startDate: '-0d',
    autoclose: true,
    minDate: moment(),
    format: 'D, d M yyyy'
    }).on('changeDate', function (selected) {
      var minDate = new Date(selected.date.valueOf());
      minDate.setDate(minDate.getDate() - 1);
      /*$('.milestones-date').datepicker({
        todayHighlight: 'TRUE',
        startDate: '-0d',
        autoclose: true,
        minDate: moment(),
        format: 'D, d M yyyy',
        // endDate: '+'+days+'d',
      });*/
     // $('.create-milestones-date').datepicker('setEndDate', minDate);
      $("#goal_due_date").val(minDate);
      var due_date =  moment(minDate, 'ddd, D MMM YYYY').format("YYYY-MM-DD");
      $('#goalDueDate').val(due_date);
  });
  $(document).ready(function() {
    loadFormData();
  });

  function loadFormData(){
     var current_step = $('.step').data('step');
     $.ajax({
         url: public_url + 'goal-buddy/load-form-data',
         type: "POST",
         data: {'current_step': current_step},
         success: function (data) {
           console.log(data);
              if(data.data && data.data.due_date != undefined ){
                 $("#datepicker_SYG").val(data.data.due_date);
              }
          }
     });
  }
</script>                  