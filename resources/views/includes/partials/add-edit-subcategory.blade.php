<!-- start:Add More Modal -->
<div class="modal fade" id="addSubcat" role="dialog">
    <div class="modal-dialog">   
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body bg-white clearfix">
                {!! Form::hidden('fields') !!}
                <a class="btn btn-primary pull-right m-b-10 addEditSubCat" href="#" data-extra="">
                    <i class="ti-plus"></i> 
                </a>
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Subcategory</th>
                            <th>Category</th>
                            <th class="center hidden-xs mw-50 mh-50">Image</th>
                            <th class="center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="hidden" data-procatid="" data-subtitle="" data-slug="">
                            <td class="subcat-text">
                                ----
                            </td>
                            <td class="cat-text">
                                ----
                            </td>
                            <td class="center mw-50 mh-50 hidden-xs subCatImg-body">
                                <img class="mw-50 mh-50" src="" />
                            </td>
                            <td class="center">
                                <div>
                                    <a class="btn btn-xs btn-default tooltips addEditSubCat" href="#" data-placement="top" data-original-title="Edit" data-entity-id="">
                                        <i class="fa fa-pencil" style="color:#ff4401;"></i>
                                    </a>
                                    
                                    <a class="btn btn-xs btn-default tooltips delLink" href="#" data-placement="top" data-original-title="Delete" data-entity="category" data-ajax-callback="addMoreSubCatDel">
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
<div class="modal fade" id="addMoreSubCat" role="dialog">
    <div class="modal-dialog">   
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-subtitle"></h4>
            </div>
            <div class="modal-body bg-white clearfix">
                {!! Form::open(['url' => '', 'role' => 'form']) !!}
                    {!! Form::hidden('editId') !!}
                    {!! Form::hidden('old_slug') !!}
                    

                    <div class="form-group">
                        {!! Form::label('text', 'Category *', ['class' => 'strong']) !!}
                        {!! Form::text('text', null ,['class'=>'form-control','required']) !!}
                    </div>

                     <div class="form-group">
                        {!! Form::label('sub_title', 'Category sub title *', ['class' => 'strong']) !!}
                        {!! Form::text('sub_title', null ,['class'=>'form-control','required']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('slug', 'Category slug', ['class' => 'strong']) !!}
                        {!! Form::text('slug', null ,['class'=>'form-control']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('productCat-id', 'Parent Category *', ['class' => 'strong']) !!}
                        {!! Form::select('productCat-id', [''=>' -- Select -- '], null ,['class'=>'form-control cat-select-picker','required']) !!}
                    </div>
                    <div class="form-group upload-group ">
                        {!! Form::label(null, 'Upload Image *', ['class' => 'strong']) !!}
                        <input type="hidden" name="prePhotoName" value="" class="no-clear">
                        <input type="hidden" name="imgSrcCls" value="subCategoryImagePreviewPics" class="no-clear">
                        <input type="hidden" name="inputElem" value="subCategoryImage" class="no-clear">
                        <div>
                            <label class="btn btn-primary btn-file">
                                <span><i class="fa fa-plus"></i> Select File</span> 
                                <input type="file" class="hidden uploded-image" onChange="imageUploadHandel(this)" accept="image/*" data-type="category">
                            </label>
                        </div>
                        <span class="help-block m-b-0"></span>
                        <input type="hidden" name="subCategoryImage" value="">

                        <img src="" class="subCategoryImagePreviewPics previewImg m-t-5 hidden" width="100px" height="100px" />
                    </div>
                    <!-- <div class="form-group upload-group ">
                        {!! Form::label(null, 'Upload Image *', ['class' => 'strong']) !!}
                        <input type="hidden" name="prePhotoName" value="" class="no-clear">
                        <input type="hidden" name="entityId" value="" class="no-clear">
                        <input type="hidden" name="saveUrl" value="" class="no-clear">
                        <input type="hidden" name="photoHelper" value="subCategoryImage" class="no-clear">
                        <input type="hidden" name="cropSelector" value="square">
                        <div>
                            <label class="btn btn-primary btn-file">
                                <span><i class="fa fa-plus"></i> Select File</span> 
                                <input type="file" class="hidden" onChange="fileSelectHandler(this)" accept="image/*">
                            </label>
                        </div>
                        <span class="help-block m-b-0"></span>
                        <input type="hidden" name="subCategoryImage" value="">

                        <img src="" class="subCategoryImagePreviewPics previewPics m-t-5 hidden" width="100px" height="100px" />
                    </div> -->
                {!! Form::close() !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary submit">Submit</button>
            </div>  
        </div>
    </div>      
</div>
<!-- start: 