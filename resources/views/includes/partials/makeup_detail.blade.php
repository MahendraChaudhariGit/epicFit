<div class="row">
  
    <div class="text-center">
      <form action="" method="get">
        <input type="hidden" name="tab" value="makeup">
        <div class="col-md-3">&nbsp;</div>
        <div class="col-md-2">
            <select class="form-control" name="credit_debit" id="credit_debit">
              <option value="">--Select--</option>
              <option value="credit" <?php echo $credit_debit == 'credit'?'selected':''; ?>>Credit</option>
              <option value="debit" <?php echo $credit_debit == 'debit'?'selected':''; ?>>Debit</option>
            </select>
        </div>
        <div class="col-md-2">
          <select class="form-control" name="classes_services" id="classes_services">
            <option value="">--Select--</option>
            <option value="class" <?php echo $classes_services == 'class'?'selected':''; ?>>Classes</option>
            <option value="service" <?php echo $classes_services == 'service'?'selected':''; ?>>Services</option>
            <option value="manual" <?php echo $classes_services == 'manual'?'selected':''; ?>>Manual</option>
          </select>
        </div>
        <div class="col-md-2">
          <button class="btn btn-primary float-left" type="submit" name="search" value="search">Search</button>
          <button class="btn btn-primary btn-o" type="submit" name="reset" value="reset">Reset</button>
        </div>
    </form>
    </div>
</div>
<hr>
<div class="page-header">
  <h1>EPIC Credit
    <div class="btn-group pull-right">
      @if(Session::get('hostname') == 'crm')
        <button class="btn btn-primary raise-btn" href="#" data-toggle="modal" data-target="#raiseMakeUpModel" type="button" data-client-id="{{ $clients->id }}" data-check="yes" data-labelval="rise" data-netamount="{{ $clients->AllNetAmount }}">
          <i class="fa fa-dollar"></i>
        </button>
      @endif
    </div>
    <br>
    <div class="invoice-price-heading">
        Total Amount: ${{  number_format($clients->epic_credit_balance, 2, '.', ',') }}
    </div> 
  </h1>
</div>

<!-- Start: Delete Form -->
<?php 
    $extraField=[];
    $extraField=array('clientId'=>$clients->id);
?>
@include('includes.partials.delete_form',['extraFields'=>$extraField])
<!-- End: Delete Form -->

<table class="table table-striped table-bordered table-hover m-t-10" id="makeup-datatable">
  <thead>
      <tr>
          <th>Date</th>
          <th>Time</th>
          <th>Amount</th>
          <th>Remaining</th>
          <th>Purpose</th>
          <th>Issued By</th>
      </tr>
  </thead>
  <tbody>
    @if(count($allmakeup))
    <?php  $remaingAmount = $clients->epic_credit_balance; ?>
      @foreach($allmakeup as $makeup)
          <tr>
            <td>
              {{ setLocalToBusinessTimeZone($makeup->created_at, 'dateString') }}
            </td>
            <td>
              {{ setLocalToBusinessTimeZone($makeup->created_at, 'timeString') }}
            </td>
            <td>
                 @if($makeup->makeup_amount < 0)
                 <span data-toggle="tooltip" data-placement="top" title="Deducted to EPIC Credit" class="epic-tooltip tooltipclass" rel="tooltip">
                     <i class="fa fa-chevron-circle-down text-danger"></i>
                 </span>
                 @else
                 <span data-toggle="tooltip" data-placement="top" title="Added to EPIC Credit" class="epic-tooltip tooltipclass" rel="tooltip">
                     <i class="fa fa-chevron-circle-up text-success"></i> 
                 </span>     
                @endif
                <?php $makeupAmount = abs($makeup->makeup_amount); ?>
                ${{ number_format($makeupAmount, 2, '.', ',') }}
            </td>
            <td>
                ${{ number_format($remaingAmount, 2, '.', ',') }}
                <?php $remaingAmount -= $makeup->makeup_amount; ?>
            </td>
            <td>
              <div>  
                {{ $makeup->Purpose }}
                @if(count($allnotes))
                     <?php $notes=$allnotes->where('cn_id',$makeup->makeup_notes_id)->first(); ?>
                     @if(count($notes))
                          <span data-placement="top" data-toggle="popover" data-trigger="hover" data-content="{{ $notes->cn_notes }}" class="makeup-{{$notes->cn_id}}">
                          <i class="fa fa-comment"></i>
                          </span><br />
                     @endif
                @endif
                <br>
                @if(Session::get('hostname') == 'crm')
                  {!! $makeup->makeup_extra !!} 
                @endif 
              </div>
           </td>
           <td>
              {!! $makeup->makeup_user_name !!}
           </td>
          </tr>
      @endforeach
    @endif
  </tbody>
</table>  


<!-- start: makeup modal -->
@include('includes.partials.makeup_modal')
<!-- end: makeup modal -->
{!! Html::script('assets/js/makeup.js') !!}


<script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover();
    $.fn.dataTable.moment('ddd, D MMM YYYY');
    $('#makeup-datatable').dataTable({"searching": false, "paging": false, "info": false, 'order': [[0,'desc']] });  
    
    $("button[name='reset']").click(function(){
      $("#credit_debit").val('');
      $("#classes_services").val('');
      $(this).submit();
    })
});
</script>

