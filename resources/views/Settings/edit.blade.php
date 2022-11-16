@extends('Settings.business_setup')

@section('required-styles-for-this-page')
    <!-- start: Bootstrap datepicker -->
    {!! Html::style('assets/plugins/datepicker/css/datepicker.css?v='.time()) !!}
    <!-- end: Bootstrap datepicker -->
@parent
@stop


  @section('page-title')
      @if(isset($membership))
          Edit
      @else
          Add
      @endif
      Membership
  @stop
 

@section('form')
    @include('Settings.membership.form')
@stop

@section('script')

    <!-- start: Bootstrap datepicker -->
    {!! Html::script('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js?v='.time()) !!}
    <!-- end: Bootstrap datepicker -->

<script type="text/javascript">
$(document).ready(function(){

  $(document).on( 'click', '#public_member_website', function(e) {
    var checked = $(this).is(":checked");
    if(checked)
      $('.online_div').removeClass('hidden');

    else
      $('.online_div').addClass('hidden');
    });

  var totalcheckedTaxBox=$('.tax-label-cls:checked').length;
  //alert(totalcheckedTaxBox);
  var toaltax=0;
  $(document).on( 'click', '.tax-label-cls', function(e) {
     var checked = $(this).is(":checked");
     var taxRateField = $(this).closest('.form-group').find('.tax-rate-cls');
     var taxFieldVal=$(this).closest('.form-group').find('.tax-rate-cls input[type="text"]').val();
     

         totalcheckedTaxBox=$('.tax-label-cls:checked').length;
 


      $('.totaltaxcls').removeClass('hidden');
      if(checked){
        $(taxRateField).removeClass('hidden');
        toaltax =parseInt(toaltax) + parseInt(taxFieldVal);
      }
      else{
        $(taxRateField).addClass('hidden');
        toaltax =parseInt(toaltax) - parseInt(taxFieldVal);
      }

      if(totalcheckedTaxBox >0)
        $('input[name="membership_totaltax"]').val(toaltax);

    });

  });
  
	
	


   $(".class-limit").change(function(){
   var classLimtVal = $( ".class-limit option:selected" ).val();

      if(classLimtVal == 'unlimited'){
       $(".class-limit-div").hide();
      }
      else{
      $(".class-limit-div").show();	
      }
     
    });


   $(".Auto-Renewal").change(function(){
   var classLimtVal = $( ".Auto-Renewal option:selected" ).val();
      if(classLimtVal == 'off'){
       $(".Auto-Renewal-div").hide();
      }
      else{
      $(".Auto-Renewal-div").show();	
      }
     
    });
   var noOfCheckBox=$('input[name="checkboxno"]').val();
$('.membershit-tax-save').click(function() {
    initCustomValidator();
    var form = $('#add-membership-tax-label');
 
    
    var validForm = form.valid();
    if (validForm) {
         formData = {}
            $.each($(form).find(':input').serializeArray(), function(i, obj){
              formData[obj.name] = obj.value;
              
            });
           
              $.ajax({
                url: $(form).attr('action'),
                method: "POST",
                data: formData,
                success: function(data) {
                  var data = JSON.parse(data);
                 $('#addTaxModal').modal('hide');
                 if(data.status == 'added'){
                  $('input[name="tax_label"]').val('');
                  $('input[name="tax_rate"]').val('');
                  var trHTML = '';

                  trHTML='<div class="form-group"><div class="row"><div class="col-md-3"><div class="checkbox clip-check check-primary m-b-0"><input type="checkbox" name="member_tax_option'+noOfCheckBox+'" id="'+data.insertId+'_tax" value="1" class="tax-label-cls" > <label for="'+data.insertId+'_tax"> <strong>'+data.mtax_label+'</strong>  </label></div></div><div class="col-md-9 tax-rate-cls hidden"><div><input type="text" class="form-control numericField" name="taxrate'+noOfCheckBox+'" value="'+data.mtax_rate+'" readonly /></div></div></div></div>';
                    noOfCheckBox++;
                  $('.taxrate-cls').append(trHTML);

                 }


                }
              });
       }else
    return false;

  });
 

</script>
     
@stop()