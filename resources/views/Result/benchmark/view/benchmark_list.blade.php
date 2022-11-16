<!-- start: Delete Form -->
<?php 
//dd($benchmarks_details);

?>

<div id="client-datatable_wrapper" class="dataTables_wrapper form-inline" role="grid">
    
       <table class="table table-striped table-bordered table-hover m-t-10 dataTable" id="client-datatable">
         <thead>
            <tr role="row">
                <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="client-datatable" rowspan="1" colspan="1" style="width: 353px;" aria-label="Date: activate to sort column ascending">Date
                </th>
                <th class="hidden-xxs sorting" role="columnheader" tabindex="0" aria-controls="client-datatable" rowspan="1" colspan="1" style="width: 305px;" aria-label="Time: activate to sort column ascending">Time
                </th>
                <th class="center sorting" role="columnheader" tabindex="0" aria-controls="client-datatable" rowspan="1" colspan="1" style="width: 162px;" aria-label="Actions: activate to sort column ascending">Actions
                </th>
            </tr>
         </thead>
         <tbody role="alert" aria-live="polite" aria-relevant="all">
         
             
              @foreach($benchmarks_details as $key=>$benchmark)
              <?php
             //echo "<pre>";print_r($benchmark);exit;
             
             ?>
                <tr>
                 <td>
                      <?php print_r(date(' D, j M Y',strtotime($benchmark->nps_day))); ?>
                 </td>
                 <td>
                      {{ $benchmark->nps_time_hour }} Hour {{ $benchmark->nps_time_min }} Minutes
                 </td>
                 <td class="center">
                   <div>
                      <a href="#" class="btn btn-xs btn-default tooltips benchmark-view-edit" data-placement="top" data-original-title="View" data-benchmarkid="{{ $benchmark->id }}" data-btntype="view-list">
                        <i class="fa fa-share" style="color:#ff4401;"></i>
                      </a><!--url('staff/'.$staffs->id)-->
                      <a class="btn btn-xs btn-default tooltips benchmark-view-edit" href="#" data-placement="top" data-original-title="Edit" data-benchmarkid="{{ $benchmark->id }}" data-btntype="edit-list">
                        <i class="fa fa-pencil" style="color:#ff4401;"></i>
                      </a>
<!--                      <a class="btn btn-xs btn-default tooltips delLink" href="{{ route('benchmark.destroy', $benchmark->id ) }}" data-placement="top" data-original-title="Delete" data-entity="benchmark">
                          <i class="fa fa-trash-o" style="color:#ff4401;"></i>
                      </a>-->
                    </div>
                 </td>
                </tr>
              @endforeach
              
          
          </tbody>
       </table>  
</div>

