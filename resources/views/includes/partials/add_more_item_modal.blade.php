<!-- start:Add More Item Modal -->
<div class="modal fade" id="addMoreItemModal" role="dialog">
    <div class="modal-dialog">   
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body bg-white clearfix">
                {!! Form::hidden('field') !!}
                <a class="btn btn-primary pull-right m-b-10 item-addEdit" href="#" data-extra="">
                    <i class="ti-plus"></i> 
                </a>
                <table class="table table-striped table-bordered table-hover" id="client-datatable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th class="center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="hidden">
                            <td>
                                Cat
                            </td>
                            <td class="center">
                                <div>
                                    <a class="btn btn-xs btn-default tooltips item-addEdit" href="#" data-placement="top" data-original-title="Edit" data-entity-id="" data-extra="">
                                        <i class="fa fa-pencil" style="color:#ff4401;"></i>
                                    </a>
                                    
                                    <a class="btn btn-xs btn-default tooltips delLink" href="#" data-placement="top" data-original-title="Delete" data-entity="category" data-ajax-callback="addedRowsDel">
                                        <i class="fa fa-trash-o" style="color:#ff4401;"></i>
                                    </a> 
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary " data-dismiss="modal">Done</button>
            </div>  
        </div>
    </div>      
</div>
<!-- start: Add More Item Modal -->

<!-- start: -->
<div class="modal fade" id="addMoreAddEditItem" role="dialog">
    <div class="modal-dialog">   
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body bg-white ">
                {!! Form::open(['url' => '', 'role' => 'form']) !!}
                    {!! Form::hidden('editId') !!}
                    <div class="form-group">
                        {!! Form::text('text', null ,['class'=>'form-control','required']) !!}
                    </div>
                {!! Form::close() !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary submit">Submit</button>
            </div>  
        </div>
    </div>      
</div>
<!-- start: -->