
@if(isset($mealInfo))
    {!! Form::model($mealInfo, ['method' => 'get', 'route' => ['meals.update', $mealInfo->meal_id], 'id' => 'form', 'class' => 'margin-bottom-30']) !!}
@else
    {!! Form::open(['route' => ['meals.store'], 'id' => 'form', 'class' => 'margin-bottom-30']) !!}
@endif
     <input type="hidden" name="record_id" id="record_id" value="{{ isset($mealInfo)?$mealInfo->meal_id:'' }}">
     <input type="hidden" name="mealfood_id" id="mealfood_id" value="{{ isset($mealInfo)?$foods->mealfood_id:'' }}">
        
    <div class="col-md-12">
    <fieldset class="padding-15">
        <legend>
             Meal Information
        </legend>
        <div class="row">
            <div class="col-md-6 ">
                <div class="form-group">
                    <div>
                        <label for="mealname" class="strong"> Meal Name </label>
                        <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                            <i class="fa fa-question-circle"></i>
                        </span>
                    </div>
                    <input class="form-control" required="required" data-realtime="mealname" name="mealname" value="{{ isset($mealInfo)?$mealInfo->mealname:'' }}" id="mealname" type="text">
                    <span id="mealname" class="help-block" style="color: #a94442;display: none;" ></span>
                </div>

                <div class="form-group">
                    <div>
                        <label for="mealDesc" class="strong">Meal Description *</label>
                        <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                            <i class="fa fa-question-circle"></i>
                        </span>
                    </div>                   
                    <textarea class="ckeditor form-control rounded-0" data-realtime="mealDesc" required="required" name="mealDesc" id="mealDesc" rows="10">{{ isset($mealInfo)?$mealInfo->meal_description:'' }}</textarea>
                    <span id="mealDesc" class="help-block" style="color: #a94442;display: none;" ></span>

                </div>

                <div class="form-group">
                    <div>
                        <label for="meal_cat" class="strong">Meal Category *</label>
                        <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                            <i class="fa fa-question-circle"></i>
                        </span>
                    </div>
                    {!! Form::select('meal_cat',isset($mealsCategory)?$mealsCategory:'', isset($mealInfo)?$mealInfo->meal_categories:0, ['class'=>'form-control','id'=>'meal_cat']) !!}
                    <span id="meal_cat" class="help-block" style="color: #a94442;display: none;" ></span>
                </div>
                <div class="form-group">
                    <div>
                        <label for="food" class="strong">Food Name</label>
                        <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                            <i class="fa fa-question-circle"></i>
                        </span>
                    </div>  
                    <input class="typeahead form-control" required="required" data-realtime="food" name="food" value="{{ isset($mealInfo)?$foods->short_desc:'' }}" id="food" type="text" autocomplete="off">  
                    <input type="hidden" name ="food_id" value="{{ isset($mealInfo)?$foods->food_id:'' }}" type="text">              
                    <span id="food" class="help-block" style="color: #a94442;display: none;" ></span>

                </div>
                <div class="form-group">
                    <div>
                        <label for="servingSize" class="strong">Serving Size</label>
                        <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                            <i class="fa fa-question-circle"></i>
                        </span>
                    </div>  
                    <input class="typeahead form-control" required="required" data-realtime="servingSize" name="servingSize" value="{{ isset($mealInfo)?$foods->serving_desc:'' }}" id="servingSize" type="text" autocomplete="off">  
                    <input type="hidden" name ="servingSize_id" value="{{ isset($mealInfo)?$foods->servingsize_id:'' }}" type="text">              
                    <span id="servingSize" class="help-block" style="color: #a94442;display: none;" ></span>

                </div>

                <div class="form-group">
                    <div>
                        <label for="ingredients" class="strong">Ingredients *</label>
                        <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                            <i class="fa fa-question-circle"></i>
                        </span>
                    </div>                
                    <textarea class="ckeditor form-control rounded-0" data-realtime="ingredients" required="required" name="ingredients" id="ingredients" rows="10">{{ isset($mealInfo)?$mealInfo->ingredients:'' }}</textarea>
                    <span id="ingredients" class="help-block" style="color: #a94442;display: none;" ></span>

                </div>
                <div class="form-group">
                    <div>
                        <label for="methods" class="strong">Method *</label>
                        <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                            <i class="fa fa-question-circle"></i>
                        </span>
                    </div>
                    <textarea class="ckeditor form-control rounded-0" data-realtime="methods" required="required" name="methods" id="methods" rows="10">{{ isset($mealInfo)?$mealInfo->method:'' }}</textarea>
                    <span id="methods" class="help-block" style="color: #a94442;display: none;" ></span>
                </div>

                <div class="form-group">
                    <div>
                        <label for="tips" class="strong">Tips *</label>
                        <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                            <i class="fa fa-question-circle"></i>
                        </span>
                    </div>
                    <textarea class="ckeditor form-control rounded-0" data-realtime="tips" required="required" name="tips" id="tips" rows="10">{{ isset($mealInfo)?$mealInfo->tips:'' }}</textarea>
                    <span id="tips" class="help-block" style="color: #a94442;display: none;" ></span>
                </div>

                <div class="form-group">
                    <div>
                        <label for="serves" class="strong">Serving Size *</label>
                        <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                            <i class="fa fa-question-circle"></i>
                        </span>
                    </div>
                    <input class="form-control" data-realtime="serves" required="required" name="serves" value="{{ isset($mealInfo)?$mealInfo->serves:'' }}" id="serves" type="text">                
                    <span id="serves" class="help-block" style="color: #a94442;display: none;" ></span>
                </div>

                <div class="form-group">
                   <div>
                        <div class="user-image">
                            <div class="thumbnail">
                                <img src="{{ isset($mealInfo)?dpSrc($mealInfo->main_image):'' }}" class="img-responsive mainPreviewPics previewPics" id="profile-userpic-img" alt="" data-realtime="gender">
                            </div>
                            <div class="form-group upload-group">
                                <input type="hidden" class="prePhotoName mainPhotoName" name="mainPhotoName" value="{{ isset($mealInfo)?$mealInfo->main_image:'' }}">
                                <input type="hidden" name="entityId" value="">
                                <input type="hidden" name="saveUrl" value="">
                                <input type="hidden" name="photoHelper" value="main">
                                <input type="hidden" name="cropSelector" value="rectangle">
                                <div>
                                    <label class="btn btn-primary btn-file">
                                        <span>Main Image</span> <input type="file" class="hidden" onChange="mpfileSelectHandler(this)" accept="image/*">
                                    </label>
                                </div>
                            </div>
                            <div class="user-image-buttons" style="display:none;">
                                <span class="btn btn-teal btn-file btn-sm"><span class="fileupload-new"><i class="fa fa-pencil"></i></span><span class="fileupload-exists"><i class="fa fa-pencil"></i></span>
                                    <input type="file">
                                </span>
                                <a href="#" class="btn fileupload-exists btn-bricky btn-sm" data-dismiss="fileupload">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="form-group">
                   <div>
                        <div class="user-image">
                            <div class="thumbnail">
                                <img src="{{ isset($mealInfo)?dpSrc($mealInfo->thumb_image):''}}" class="img-responsive thumbPreviewPics previewPics" id="profile-userpic-img" alt="" data-realtime="gender">
                            </div>
                            <div class="form-group upload-group">
                                <input type="hidden" class="prePhotoName thumbPhotoName" name="thumbPhotoName" value="{{ isset($mealInfo)?$mealInfo->thumb_image:'' }}">
                                <input type="hidden" name="entityId" value="">
                                <input type="hidden" name="saveUrl" value="">
                                <input type="hidden" name="photoHelper" value="thumb">
                                <input type="hidden" name="cropSelector" value="square">
                                <div>
                                    <label class="btn btn-primary btn-file">
                                        <span>Thumbnail Image</span> <input type="file" class="hidden" onChange="mpfileSelectHandler(this)" accept="image/*">
                                    </label>
                                </div>
                            </div>
                            <div class="user-image-buttons" style="display:none;">
                                <span class="btn btn-teal btn-file btn-sm"><span class="fileupload-new"><i class="fa fa-pencil"></i></span><span class="fileupload-exists"><i class="fa fa-pencil"></i></span>
                                    <input type="file">
                                </span>
                                <a href="#" class="btn fileupload-exists btn-bricky btn-sm" data-dismiss="fileupload">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </div> 
                </div> 
            </div>
        </div>
    </fieldset> 
    </div>
</form>
    <div class="form-group">
        @if(isset($mealInfo))
            <button type="button" class="btn btn-primary btn-wide pull-right btn-add-more-form" id="update_meal"> Update</button>
        @else
            <button type="button" class="btn btn-primary btn-wide pull-right btn-add-more-form" id="add_meal"> Create</button>
        @endif
    </div>