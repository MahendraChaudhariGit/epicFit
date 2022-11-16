<div>
    <!-- start:Add More Modal -->
    <div class="modal fade" id="addServingSize" role="dialog">
        <div class="modal-dialog">   
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body bg-white clearfix">
                    {!! Form::hidden('field') !!}
                    <a class="btn btn-primary pull-right m-b-10 addEdit-servingSize" href="#" data-extra="">
                        <i class="ti-plus"></i> 
                    </a>
                    <table class="table table-striped table-bordered table-hover" id="client-datatable">
                        <thead>
                            <tr>
                                <th id="field-name">Name</th>
                                <th class="">Size</th>
                                <th class="">Quantity</th>
                                <th class="">Unit</th>
                                <th class="">Other</th>
                                <th class="center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="hidden" data-id="" data-tags="">
                                <td class="name">
                                    Cat
                                </td>
                                <td class="text-capitalize size" >
                                    Dummy Text
                                </td>
                                <td class="quantity" >
                                    quantity
                                </td>
                                <td class="units" >
                                    units
                                </td>
                                <td class="other" >
                                    Other
                                </td>
                                <td class="center">
                                    <div>
                                        <a class="btn btn-xs btn-default tooltips addEdit-servingSize" href="#" data-placement="top" data-original-title="Edit" data-entity-id="" data-extra="">
                                            <i class="fa fa-pencil" style="color:#ff4401;"></i>
                                        </a>
                                        
                                        <a class="btn btn-xs btn-default tooltips delLink" href="#" data-placement="top" data-original-title="Delete" data-entity="category" data-ajax-callback="addMoreDel">
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
    <!-- start: Add More Modal -->

    <!-- start: -->
    <div class="modal fade" id="addMoreServingSize" role="dialog">
        <div class="modal-dialog">   
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body bg-white clearfix">
                    {!! Form::open(['url' => '', 'role' => 'form']) !!}
                        {!! Form::hidden('editId') !!}
                        <div class="form-group">
                            {!! Form::label('text', 'Size Name *', ['class' => 'strong textbox-lable']) !!}
                            {!! Form::text('text', null ,['class'=>'form-control','required']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('servingsize', 'Size * (like-1,2)', ['class' => 'strong']) !!}
                            {!! Form::text('servingsize', null, ['class' => 'form-control', 'required' => 'required']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('servingquant', 'Quantity * (like-200,100)', ['class' => 'strong']) !!}
                            {!! Form::text('servingquant', null, ['class' => 'form-control', 'required' => 'required']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('units', 'Unit (like-g,l,kg etc)', ['class' => 'strong']) !!}
                            {!! Form::text('units', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('other', 'Other (large (3" to 4-1/4" dia) )', ['class' => 'strong']) !!}
                            {!! Form::text('other', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('servingTags', 'Key words (Enter with comma separated like-g,gm,gr,gram)', ['class' => 'strong']) !!}
                            {!! Form::text('servingTags', null, ['class' => 'form-control']) !!}
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
    <!-- End: -->
</div>
