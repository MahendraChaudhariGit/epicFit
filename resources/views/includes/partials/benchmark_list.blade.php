<!-- start: Delete Form -->
<?php $extraField=[];
    $extraField=array(
                      'clientId'=>$clients_details->id
                     );
   // print_r($extraField);
?>
@if(hasPermission('delete-benchmark'))
  @include('includes.partials.delete_form',['extraFields'=>$extraField])
@endif  
<!-- end: Delete Form -->
<table class="table table-striped table-bordered table-hover m-t-10" id="benchmark-datatable">
  <thead>
      <tr>
          <th>Date</th>
          <th>Time</th>
          <th class="center">Actions</th>
      </tr>
  </thead>
  <tbody>    
    @if(hasPermission('list-benchmark') && count($clients_details->benchmarks))
      @foreach($clients_details->benchmarks as $benchmark)
        <tr>
         <td>
              <?php print_r(date(' D, j M Y',strtotime($benchmark->nps_day))); ?>
         </td>
         <td>
              {{ $benchmark->nps_time_hour }} Hour {{ $benchmark->nps_time_min }} Minutes
         </td>
         <td class="center">
           <div>
             @if(hasPermission('view-benchmark'))
              <a href="#" class="btn btn-xs btn-default tooltips benchmark-view-edit" data-placement="top" data-original-title="View" data-benchmarkid="{{ $benchmark->id }}" data-btntype="view-list">
                <i class="fa fa-share" style="color:#253746;"></i>
              </a><!--url('staff/'.$staffs->id)-->
             @endif  
             @if(hasPermission('edit-benchmark'))
              <a class="btn btn-xs btn-default tooltips benchmark-view-edit" href="#" data-placement="top" data-original-title="Edit" data-benchmarkid="{{ $benchmark->id }}" data-btntype="edit-list">
                <i class="fa fa-pencil" style="color:#253746;"></i>
              </a>
              @endif
              @if(hasPermission('delete-benchmark'))
              <a class="btn btn-xs btn-default tooltips delLink" href="{{ route('benchmark.destroy', $benchmark->id ) }}" data-placement="top" data-original-title="Delete" data-entity="benchmark">
                  <i class="fa fa-trash-o" style="color:#253746;"></i>
              </a>
              @endif
            </div>
         </td>
        </tr>
      @endforeach
    @endif
  </tbody>
</table>  

<script>
$(document).ready(function(){
    $.fn.dataTable.moment('ddd, D MMM YYYY');
    $('#benchmark-datatable').dataTable({"searching": false, "paging": false, "info": false });   
});
</script>

