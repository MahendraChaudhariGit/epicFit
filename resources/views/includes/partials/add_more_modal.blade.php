<div>
<!-- start:Add More Modal -->
<div class="modal fade" id="addMoreModal" role="dialog">
    <div class="modal-dialog">   
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body bg-white clearfix">
                {!! Form::hidden('field') !!}
                <a class="btn btn-primary pull-right m-b-10 addMore-addEdit" href="#" data-extra="">
                    <i class="ti-plus"></i> 
                </a>
                <table class="table table-striped table-bordered table-hover" id="client-datatable">
                    <thead>
                        <tr>
                            <th id="field-name">Name</th>
                            <th id="price-name">Price</th>
                            <th class="center hidden" id="extra-field1">Gender</th>
                            <th class="center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="hidden" data-extrafield-id="" data-servtags="">
                            <td>
                                Cat
                            </td>
                            <td></td>
                            <td class="center text-capitalize extra-field1 hidden" >
                                Dummy Text
                            </td>
                            <td class="center">
                                <div>
                                    <a class="btn btn-xs btn-default tooltips addMore-addEdit" href="#" data-placement="top" data-original-title="Edit" data-entity-id="" data-extra="">
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
<div class="modal fade" id="addMoreAddEdit" role="dialog">
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
                        {!! Form::label('text', 'Name *', ['class' => 'strong textbox-lable']) !!}
                        {!! Form::text('text', null ,['class'=>'form-control','required']) !!}
                    </div>
                    <div class="form-group price">
                        {!! Form::label('text', 'Price *', ['class' => 'strong']) !!}
                        {!! Form::text('price', null ,['class'=>'form-control','required','autocomplete'=>'off']) !!}
                    </div>
                    <div class="form-group gender-group hidden">
                        {!! Form::label('gender', 'Gender *', ['class' => 'strong']) !!}
                        {!! Form::select('gender', [''=>' -- Select --','male'=>'Male', 'female'=>'Female', 'unisex'=>'Unisex'], null, ['class' => 'form-control', 'required' => 'required']) !!}
                    </div>
                    <div class="form-group serving-size hidden">
                        {!! Form::label('servingsize', 'Quentity * (like-200,100)', ['class' => 'strong']) !!}
                        {!! Form::text('servingsize', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    </div>
                    <div class="form-group serving-tags hidden">
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
</div>
<!-- start: 