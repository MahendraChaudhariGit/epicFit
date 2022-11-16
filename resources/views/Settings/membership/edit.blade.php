@extends('Settings.business_setup')

@section('required-styles-for-this-page')
    <!-- start: Bootstrap datepicker -->
    <!--{!! Html::style('assets/plugins/datepicker/css/datepicker.css') !!}-->
    <!-- end: Bootstrap datepicker -->
    <!-- {!! Html::style('vendor/sweetalert/sweet-alert.css') !!} -->
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
    @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'delete-staff'))
      <!-- start: Delete Form -->
      @include('includes.partials.delete_form')
      <!-- end: Delete Form -->
    @endif
    <!-- start: Add More Model -->
    @include('includes.partials.add_more_modal')
    <!-- end: Add More Model -->
    @include('Settings.membership.form')
@stop

@section('script')

    <!-- start: Bootstrap datepicker -->
    <!--{!! Html::script('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}-->
    <!-- end: Bootstrap datepicker -->

<script type="text/javascript">
  var rowCount = 0;
  $(document).ready(function(){

    $(document).on( 'click', '#public_member_website', function(e) {
      var checked = $(this).is(":checked");
      if(checked)
        $('.online_div').removeClass('hidden');

      else
        $('.online_div').addClass('hidden');
    });

    var totalcheckedTaxBox=$('.tax-label-cls:checked').length;
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

     $(document).ready(function(e) {
         var toaltax=0;
       $('.tax-label-cls').each(function(){
       console.log( $(this));
      var checked = $(this).is(":checked");
      var taxRateField = $(this).closest('.form-group').find('.tax-rate-cls');
      var taxFieldVal=$(this).closest('.form-group').find('.tax-rate-cls input[type="text"]').val();
       console.log(taxFieldVal);
      totalcheckedTaxBox=$('.tax-label-cls:checked').length;
      $('.totaltaxcls').removeClass('hidden');

      if(checked){
        console.log('here');
        $(taxRateField).removeClass('hidden');
        console.log(toaltax,taxFieldVal );
        toaltax =parseInt(toaltax) + parseInt(taxFieldVal);
        
      }
console.log(toaltax);
      // if(totalcheckedTaxBox >0)
        $('input[name="membership_totaltax"]').val(toaltax);
    });
     });
  	
    $(".class-limit").change(function(){
      var classLimtVal = $( ".class-limit option:selected" ).val();
      if(classLimtVal == 'unlimited'){
       $(".class-limit-div").addClass('hidden');
       //$('.required-add').prop('required',false);
      }
      else if(classLimtVal == 'limited'){
        $(".class-limit-div").removeClass('hidden');
        //$('.required-add').prop('required',true);
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
      var form = $('#add-membership-tax-label'), 
          validForm = form.valid(),
          formData = {};

      if(validForm) {  
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
            $('.noTaxRate').addClass('hidden');

           }
          }
        });
      }
      else
        return false;
    });

    $('#add-memb-service').click(function(e){
      e.preventDefault();
      rowCount++;

      var row = $('#service-clone-row');
          newRow = row.clone(),
          fieldset = row.closest('fieldset');

      fieldset.find('.service-warning').addClass('hidden');
      newRow.removeClass('hidden');

      var ddGroup = newRow.find('.serviceDDGroup');
      var dd = ddGroup.find('select.mem_service');
      ddGroup.empty();
      ddGroup.append(dd);
      ddGroup.find('select.mem_service').val("").attr('name','mem_servicesjs'+rowCount).addClass('mem-service-cls').prop('required',true).selectpicker('refresh');

      newRow.find('.mem_service_limit').val("1").attr('name', 'mem_limitjs'+rowCount).addClass('mem-service-cls').prop('required',true);

      var ddGroup2 = newRow.find('.limitTypeDDGroup');
      var dd2 = ddGroup2.find('select.mem_type');
      ddGroup2.empty();
      ddGroup2.append(dd2);
      ddGroup2.find('select.mem_type').val("").attr('name','mem_typejs'+rowCount).addClass('mem-service-cls').prop('required',true).selectpicker('refresh');
      
      fieldset.append(newRow);
    })

    $('body').on('click', '.remove-memb-service-row', function(e){
      e.preventDefault();

      var $this = $(this);
          fieldset = $this.closest('fieldset');
          rowLength = fieldset.find('.remove-memb-service-row').length;
      $this.closest('.row').remove();
      if(rowLength <= 2)
        fieldset.find('.service-warning').removeClass('hidden');
      
    })
   
  });
</script>
     
@stop()