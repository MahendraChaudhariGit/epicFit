@extends('blank')
@section('page-title')
    Permission Group List
    <?php if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'create-permission-group')){ ?>
		{{-- <a class="btn btn-primary pull-right" href="#" data-toggle="modal" data-target="#addGroupModal"><i class="ti-plus"></i> Add Group Type</a> --}}
    <?php } ?>
@stop
@section('content')
{!! displayAlert()!!}


<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default" id ="group-listing">
            <div class="panel-body">
            	<!--<div class="table-responsive">-->
	                <table class="table table-striped table-bordered table-hover m-t-10" id="permission-datatable">
	                    <thead>
	                        <tr>
	                            <th>Permission Group</th>
	                            <!-- <th class="center">Actions</th> -->
	                        </tr>
	                    </thead>
	                    <tbody>
	        	 		@foreach($userTypes as $key=>$userTypesName)
	        	 			<?php 

								$parmsChkData= array();
	        	 				$perms = $userTypesName->perms;
	        	 				foreach ($perms as  $permsVal) {
	        	 					$parmsChkData[$permsVal->perm_id]=array('p_groupname' =>$permsVal->permission_group,'p_actiontype' =>$permsVal->perm_action_type);
	        	 					
	        	 				}


	        	 		    $totalAssinedPermissionArr=array_count_values(array_column($parmsChkData, 'p_groupname'));
	        	 		// dd($totalAssinedPermissionArr);
                        

                        
                         

	        	 			?>
	                   	<tr>
	                     <td>
	                    <a href="#" data-type-id ="{{ $userTypesName['ut_id'] ?? '' }}" class="show-permission" > 			<div>{{ $userTypesName['ut_name'] ?? '' }}</div></a>
	                      <div class ="permission-section" style ="margin-top:20px;" data-display-status ="">

	                      	<div class="row">
							  <div class="col-md-12">
							      <div class="well well-sm  clearfix">
							      {!! checkboxOptions(['value' => '', 'id' =>$userTypesName['ut_id'].'_'. 'all_add_Checkbox','class'=>'all_Checkbox','text'=>'Add','data-permission-class'=>'add']) !!}
							      {!! checkboxOptions(['value' => '', 'id' => $userTypesName['ut_id'].'_'.'all_edit_Checkbox','class'=>'all_Checkbox','text'=>'Edit','data-permission-class'=>'edit']) !!}
							      {!! checkboxOptions(['value' => '', 'id' => $userTypesName['ut_id'].'_'.'all_view_Checkbox','class'=>'all_Checkbox','text'=>'View','data-permission-class'=>'view']) !!}
							      {!! checkboxOptions(['value' => '', 'id' => $userTypesName['ut_id'].'_'.'all_list_Checkbox','class'=>'all_Checkbox','text'=>'List','data-permission-class'=>'list']) !!}
							      {!! checkboxOptions(['value' => '', 'id' => $userTypesName['ut_id'].'_'.'all_delete_Checkbox','class'=>'all_Checkbox','text'=>'Delete','data-permission-class'=>'delete']) !!}
								  </div>
							  </div>
							</div>
	                             
	                                     @foreach($allPermissionData as $pkey=>$permissionData)
	                                     

                                          {!! permissionPanel($userTypesName['ut_id'],$pkey,$permissionData,$parmsChkData,$totalAssinedPermissionArr) !!}
                                         @endforeach
	                                    
	                              		
	                         	 </div>
	                         </td>
	                            <!-- <td class="center"> -->
	                            	<?php //if(Auth::user()->hasPermission(Auth::user(), 'delete-permission-group')){?>
	        								<!-- <div>
	          								<a href="#" data-type-id ="{!! $userTypesName['ut_id'] or '' !!}" class="btn btn-xs btn-default tooltips delete-type" data-placement="top" data-original-title="delete"><i class="fa fa-trash-o text-primary"></i></a>
	                                		</div> -->
	                                <?php //} ?>
	                           	<!-- </td> -->
							 </tr>
	                        @endforeach 

	                    </tbody>
	                </table>
	            <!--</div>-->
            </div>
        </div>
    </div>
</div>
<div class="modal mediaPop" id="addGroupModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<!--<div id="myModal" class="modal fade" role="dialog" >-->
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" data-dismiss="modal" class="close">&times;</button>
        <h3 class="modal-title" id="myModalLabel">Add Group Type</h3>
      </div>
      <div class="modal-body">
      <form name ="group" id ="add-group" method ="get">
       <div class="row">
       <div class="col-md-12">
 	   <div class="form-group">
 		<label><b>Group Name:
        </b></label>
  		<input type="text" placeholder="Enter Group Type Name" id="group-name" class="form-control" value ="">
   		<span class="errormsg" style ="color:red;">Please enter Group Name.</span>
		</div>
       </div>
      </div>
      </form>
      </div>
      <div class="modal-footer">
       <button id ="save-group" style ="margin-right:5px;" type="button" class="btn btn-primary">Save</button>
        </div>
      </div>
@stop
@section('script')
{!! Html::script('assets/js/permission.js?v='.time()) !!}
{!! Html::script('assets/js/helper.js?v='.time()) !!}
<script>
	$.fn.dataTable.moment('ddd, D MMM YYYY');
    $('#permission-datatable').dataTable({"searching": false, "paging": false, "info": false });
</script>
@stop