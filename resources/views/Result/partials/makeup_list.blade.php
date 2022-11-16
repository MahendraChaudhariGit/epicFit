
<div class="page-header">
  <h1>Epic Cash</h1>
  <div>
      <span class="strong">Total epic cash : </span>
      $<span id="netamount">{{ $clients->epic_credit_balance }}</span>
  </div>
</div>
<table class="table table-striped table-bordered table-hover m-t-10 dataTable" id="makeup-datatable">
   <thead>
      <tr>
          <th>Date</th>
          <th>Time</th>
          <th>Amount</th>
          <th>Purpose</th>
          <th>Issued By</th>
      </tr>
   </thead>
   <tbody role="alert" aria-live="polite" aria-relevant="all">
    
    @if($allMakeups->count())
      @foreach($allMakeups as $makeup)
          <tr>
            <td>
              {{ setLocalToBusinessTimeZone($makeup->created_at, 'dateString') }}
            </td>
            <td>
              {{ setLocalToBusinessTimeZone($makeup->created_at, 'timeString') }}
            </td>
            <td>
              @if($makeup->makeup_amount < 0)
                <span data-toggle="tooltip" data-placement="top" title="Drop Make-Up" class="epic-tooltip tooltipclass" rel="tooltip">
                  <i class="fa fa-chevron-circle-down text-danger"></i>
                </span>
              @else
                <span data-toggle="tooltip" data-placement="top" title="Raise Make-Up" class="epic-tooltip tooltipclass" rel="tooltip">
                  <i class="fa fa-chevron-circle-up text-success"></i> 
                </span>     
              @endif
                ${{ abs(round($makeup->makeup_amount,2)) }}
            </td>
            <td>
              <div> 
                {{ ucfirst($makeup->makeup_purpose) }}
                @if(count($allNotes))
                  <?php $notes=$allNotes->where('cn_id',$makeup->makeup_notes_id)->first(); ?>
                  @if(count($notes))
                    <span data-placement="top" data-toggle="popover" data-trigger="hover" data-content="{{ $notes->cn_notes }}" class="makeup-{{$notes->cn_id}}">
                      <i class="fa fa-comment"></i>
                    </span>   
                  @endif
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

<script>
  $(document).ready(function(){
    $.fn.dataTable.moment('ddd, D MMM YYYY');
    $('#makeup-datatable').dataTable({"searching": false, "paging": false, "info": false });  
  });
</script>