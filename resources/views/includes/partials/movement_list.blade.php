<!-- start: Delete Form -->

<?php $extraField=[];
$clientsid = \Request::path();
$c = explode('/', $clientsid);
    $extraField=array(
                      'clientId'=>$c[1]
                     );
   // print_r($extraField);
?>

  @include('includes.partials.delete_form',['extraFields'=>$extraField])
    <!-- end: Delete Form -->
<table class="table table-striped table-bordered table-hover m-t-10" id="movement-datatable">
  <thead>
      <tr>
        <th>S.No.</th>
        <th>Date</th>
        <th>Time</th>
        <th class="center">Actions</th>
      </tr>
  </thead>
  <tbody>
    @if(count($movementData))
      @foreach($movementData as $key => $movement)
      @php
          if($movement['data_from'] == 'client-side'){
              $color = 'background-color: #f94211';
              $text_color = 'color: white !important;';
          }else{
            $color = '';
            $text_color = '';
          }
      @endphp 
        <tr style="{{ $color }}">
          <td style="{{ $text_color }}"> {{ $key+1 }} </td>
          <td style="{{ $text_color }}">
            {{ setLocalToBusinessTimeZone($movement->created_at, 'dateString') }}
          </td>
          <td style="{{ $text_color }}">
            <?php echo setLocalToBusinessTimeZone($movement->created_at)->format('h').' Hour  '.setLocalToBusinessTimeZone($movement->created_at)->format('i').' Minutes'; ?>
          </td>
          <td class="center" style="{{ $text_color }}">
            <div>
              @if($movement->save_status == 'draft')
                <a class="btn btn-xs btn-default tooltips movement-edit" href="#" data-placement="top" data-original-title="Edit" data-movementid="{{ $movement->id }}" data-btntype="edit-list">
                  <i class="fa fa-pencil" style="color:#253746;"></i>
                </a>
                
                <a class="btn btn-xs btn-default tooltips delLink" href="{{ route('movement.destroy', $movement->id ) }}" data-placement="top" data-original-title="Delete" data-entity="movement">
                    <i class="fa fa-trash-o" style="color:#253746;"></i>
                </a>
              @else
              @if(isSuperUser() ||  isUserType(['Admin']))
               <!-- <a href="#" class="btn btn-xs btn-default tooltips movement-view-edit" data-placement="top" data-original-title="View" data-benchmarkid="{{ $movement->id }}" data-btntype="view-list">
                  <i class="fa fa-share" style="color:#253746;"></i>
                </a> -->
          
                <a class="btn btn-xs btn-default tooltips movement-edit" href="#" data-placement="top" data-original-title="Edit" data-movementid="{{ $movement->id }}" data-btntype="edit-list">
                  <i class="fa fa-pencil" style="color:#253746;"></i>
                </a>
                
                <a class="btn btn-xs btn-default tooltips delLink" href="{{ route('movement.destroy', $movement->id ) }}" data-placement="top" data-original-title="Delete" data-entity="movement">
                    <i class="fa fa-trash-o" style="color:#253746;"></i>
                </a>
              @endif
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
    $('#movement-datatable').dataTable({"searching": false, "paging": false, "info": false });   
});
</script>

