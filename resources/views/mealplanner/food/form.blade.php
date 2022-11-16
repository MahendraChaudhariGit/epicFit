@if(isset($foodInfo))
    {!! Form::model($foodInfo, ['method' => 'get', 'route' => ['food.update', $foodInfo->id], 'id' => 'food-form', 'class' => 'margin-bottom-30']) !!}
@else
    {!! Form::open(['route' => ['food.store'], 'id' => 'food-form', 'class' => 'margin-bottom-30']) !!}
@endif
    {!! Form::hidden('food_id', isset($foodInfo)?$foodInfo->id:'') !!}  
        <div class="col-md-6">
            <fieldset class="padding-15">
                <legend>
                    General
                </legend>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('name', 'Name *',['class'=>'strong']) !!}
                            <span class="epic-tooltip" data-toggle="tooltip" title="This is tooltip"><i class="fa fa-question-circle"></i></span>
                            {!! Form::text('name', isset($foodInfo)?$foodInfo->name:'', ['class'=>'form-control','required','autocomplete'=>'off']) !!}
                            {!! Form::hidden('pre_food_id',null, ['class'=>'food_id']) !!}
                            {!! Form::hidden('pre_serv_id',null, ['id'=>'pre_serv_id']) !!}
                            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                        </div>
                         <div class="form-group">
                            {!! Form::label('serving_size', 'Serving Size *',['class'=>'strong']) !!}
                            <span class="epic-tooltip" data-toggle="tooltip" title="This is tooltip"><i class="fa fa-question-circle"></i></span>

                            <a href="{{ route('serving.getSize') }}" class="pull-right add-serving" data-modal-title="Serving Size" data-field="servingSize">Manage Serving Size</a>
                            
                            {!! Form::select('serving_size',$servingSize, isset($foodInfo)?$foodInfo->serving_size:'', ['class'=>'form-control servingSize','id'=>'serving-size','required','data-title'=>'-- Select --']) !!}
                            {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
                        </div>
                        <div class="form-group food-toggel-field {{ isset($foodInfo)?'':'hidden'}}">
                            {!! Form::label('brand', 'Brand',['class'=>'strong']) !!}
                            <span class="epic-tooltip" data-toggle="tooltip" title="This is tooltip"><i class="fa fa-question-circle"></i></span>
                            {!! Form::text('brand',isset($foodInfo)?$foodInfo->brand:'',['class'=>'form-control']) !!}
                            {!! $errors->first('brand', '<p class="help-block">:message</p>') !!}
                        </div>
                        <div class="form-group food-toggel-field {{ isset($foodInfo)?'':'hidden'}}">
                            {!! Form::label('supplier', 'Supplier',['class'=>'strong']) !!}
                            <span class="epic-tooltip" data-toggle="tooltip" title="This is tooltip"><i class="fa fa-question-circle"></i></span>
                            {!! Form::text('supplier',isset($foodInfo)?$foodInfo->supplier:'',['class'=>'form-control']) !!}
                            {!! $errors->first('supplier', '<p class="help-block">:message</p>') !!}
                        </div>
                        <div class="form-group food-toggel-field {{ isset($foodInfo)?'':'hidden'}}">
                            {!! Form::label('description', 'Description *',['class'=>'strong']) !!}
                            <span class="epic-tooltip" data-toggle="tooltip" title="This is tooltip"><i class="fa fa-question-circle"></i></span>
                            {!! Form::textarea('description', isset($foodInfo)?$foodInfo->description:'', ['class'=>'form-control textarea','required','id'=>'description']) !!}
                            {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
                        </div>
                        <div class="form-group food-toggel-field {{ isset($foodInfo)?'':'hidden'}}">
                            {!! Form::label('is_drink', 'Is Drink? *',['class'=>'strong']) !!}
                            <div>
                                <div class="radio clip-radio radio-primary radio-inline m-b-0">
                                    <input type="radio" name="is_drink" id="is_drink1" value="1" {{ isset($foodInfo)?(( $foodInfo['is_drink'] == "1")? "checked" : "" ):''}}>
                                    <label for="is_drink1"> Yes </label>
                                </div>
                                <div class="radio clip-radio radio-primary radio-inline m-b-0">
                                    <input type="radio" name="is_drink" id="is_drink2" value="0" {{ isset($foodInfo)?(( $foodInfo['is_drink'] == "0")? "checked" : ""):'' }}>
                                    <label for="is_drink2"> No </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group upload-group">
                            {!! Form::label('food_img', 'Ingredient image *',['class'=>'strong']) !!}
                            <div>
                                <input type="hidden" name="prePhotoName" value="{{ isset($foodInfo)?$foodInfo->food_img:'' }}" class="no-clear">
                                <input type="hidden" name="entityId" value="" class="no-clear">
                                <input type="hidden" name="saveUrl" value="" class="no-clear">
                                <input type="hidden" name="photoHelper" value="food_img" class="no-clear">
                                <input type="hidden" name="cropSelector" value="">
                                <label class="btn btn-primary btn-file">
                                    <span><i class="fa fa-plus"></i> Select File</span> <input type="file" class="hidden" onChange="fileSelectHandler(this)" accept="image/*">
                                </label>
                                <div class="m-t-10">
                                    @if(isset($foodInfo))
                                        <img class="food_imgPreviewPics previewPics" src="{{ dpSrc($foodInfo->food_img) }}" />
                                    @else
                                        <img class="hidden food_imgPreviewPics previewPics"/>
                                    @endif
                                </div>
                                <span class="help-block m-b-0"></span>
                                <input type="hidden" name="food_img" value="{{ isset($foodInfo)?$foodInfo->food_img:''  }}">
                            </div>
                        </div>
                    </div>             
                </div>
            </fieldset>
        </div>
        <div class="col-md-6 food-toggel-field {{ isset($foodInfo)?'':'hidden'}}">
            <fieldset class="padding-15" id="updated-input">
                <legend>
                    Nutritional Information
                </legend>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="water" class="strong">Water </label>
                            <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left"><i class="fa fa-question-circle"></i></span>
                            <input class="form-control price-field" name="water" value="{{isset($foodInfo)?$foodInfo->water:''}}" type="text">
                            <span id="water" class="help-block" style="color: #a94442;display: none;" ></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="energ_kcal" class="strong">Energy(Kcal)</label>
                            <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left"><i class="fa fa-question-circle"></i></span>
                            <input class="form-control price-field" data-realtime="energ_kcal" name="energ_kcal" value="{{  isset($foodInfo)?$foodInfo->energ_kcal:'' }}" id="energ_kcal" type="text">
                            <span id="energ_kcal" class="help-block" style="color: #a94442;display: none;" ></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="protein" class="strong">Protein </label>
                            <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left"><i class="fa fa-question-circle"></i></span>
                            <input class="form-control price-field" data-realtime="protein" name="protein" value="{{ isset($foodInfo)?$foodInfo->protein:'' }}" id="protein" type="text">
                            <span id="protein" class="help-block" style="color: #a94442;display: none;" ></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="lipid_total" class="strong">Lipid Total </label>
                            <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left"><i class="fa fa-question-circle"></i></span>
                            <input class="form-control price-field" data-realtime="lipid_total" name="lipid_total" value="{{ isset($foodInfo)?$foodInfo->lipid_total:'' }}" id="lipid_total" type="text">
                            <span id="lipid_total" class="help-block" style="color: #a94442;display: none;" ></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div>
                                <label for="carbohydrate" class="strong">Carbohydrate</label>
                                <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                    <i class="fa fa-question-circle"></i>
                                </span>
                            </div>
                            <input class="form-control price-field" data-realtime="carbohydrate" name="carbohydrate" value="{{ isset($foodInfo)?$foodInfo->carbohydrate:'' }}" id="carbohydrate" type="text">
                            <span id="carbohydrate" class="help-block" style="color: #a94442;display: none;" ></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div>
                                <label for="fiber" class="strong">Fiber</label>
                                <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                    <i class="fa fa-question-circle"></i>
                                </span>
                            </div>
                            <input class="form-control price-field" data-realtime="fiber" name="fiber" value="{{ isset($foodInfo)?$foodInfo->fiber:''}}" id="fiber" type="text">
                            <span id="fiber" class="help-block" style="color: #a94442;display: none;" ></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div>
                                <label for="sugar" class="strong">Sugar </label>
                                <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                    <i class="fa fa-question-circle"></i>
                                </span>
                            </div>
                            <input class="form-control price-field" data-realtime="sugar" name="sugar" value="{{ isset($foodInfo)?$foodInfo->sugar:'' }}" id="sugar" type="text">
                            <span id="sugar" class="help-block" style="color: #a94442;display: none;" ></span>
                        </div>              
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div>
                                <label for="calcium" class="strong"> Calcium </label>
                                <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                    <i class="fa fa-question-circle"></i>
                                </span>
                            </div>
                            <input class="form-control price-field" data-realtime="calcium" name="calcium" value="{{ isset($foodInfo)?$foodInfo->calcium:''}}" id="calcium" type="text">
                            <span id="calcium" class="help-block" style="color: #a94442;display: none;" ></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div>
                                <label for="iron" class="strong">Iron </label>
                                <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                    <i class="fa fa-question-circle"></i>
                                </span>
                            </div>
                            <input class="form-control price-field" data-realtime="iron" name="iron" value="{{ isset($foodInfo)?$foodInfo->iron:'' }}" id="iron" type="text">
                            <span id="iron" class="help-block" style="color: #a94442;display: none;" ></span>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div>
                                <label for="magnesium" class="strong">Magnesium</label>
                                <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                    <i class="fa fa-question-circle"></i>
                                </span>
                            </div>
                            <input class="form-control price-field" data-realtime="magnesium" name="magnesium" value="{{ isset($foodInfo)?$foodInfo->magnesium:''}}" id="magnesium" type="text">
                            <span id="magnesium" class="help-block" style="color: #a94442;display: none;" ></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div>
                                <label for="phosphorus" class="strong">Phosphorus</label>
                                <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                    <i class="fa fa-question-circle"></i>
                                </span>
                            </div>
                            <input class="form-control price-field" data-realtime="phosphorus" name="phosphorus" value="{{ isset($foodInfo)?$foodInfo->phosphorus:'' }}" id="phosphorus" type="text">
                            <span id="phosphorus" class="help-block" style="color: #a94442;display: none;" ></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div>
                                <label for="potassium" class="strong">Potassium </label>
                                <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                    <i class="fa fa-question-circle"></i>
                                </span>
                            </div>
                            <input class="form-control price-field" data-realtime="potassium" name="potassium" value="{{ isset($foodInfo)?$foodInfo->potassium:'' }}" id="potassium" type="text">
                            <span id="potassium" class="help-block" style="color: #a94442;display: none;" ></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div>
                                <label for="sodium" class="strong">Sodium </label>
                                <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                    <i class="fa fa-question-circle"></i>
                                </span>
                            </div>
                            <input class="form-control price-field" data-realtime="sodium" name="sodium" value="{{ isset($foodInfo)?$foodInfo->sodium:'' }}" id="sodium" type="text">
                            <span id="sodium" class="help-block" style="color: #a94442;display: none;" ></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div>
                                <label for="zinc" class="strong">Zinc</label>
                                <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                    <i class="fa fa-question-circle"></i>
                                </span>
                            </div>
                            <input class="form-control price-field" data-realtime="zinc" name="zinc" value="{{ isset($foodInfo)?$foodInfo->zinc:''}}" id="zinc" type="text">
                            <span id="zinc" class="help-block" style="color: #a94442;display: none;" ></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div>
                                <label for="vit_c" class="strong">Vitamin C </label>
                                <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                    <i class="fa fa-question-circle"></i>
                                </span>
                            </div>
                            <input class="form-control price-field" data-realtime="vit_c" name="vit_c" value="{{ isset($foodInfo)?$foodInfo->vit_c:'' }}" id="vit_c" type="text">
                            <span id="vit_c" class="help-block" style="color: #a94442;display: none;" ></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div>
                                <label for="thiamin" class="strong">Thiamin </label>
                                <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                    <i class="fa fa-question-circle"></i>
                                </span>
                            </div>
                            <input class="form-control price-field" data-realtime="thiamin" name="thiamin" value="{{ isset($foodInfo)?$foodInfo->thiamin:''}}" id="thiamin" type="text">
                            <span id="thiamin" class="help-block" style="color: #a94442;display: none;" ></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div>
                                <label for="riboflavin" class="strong">Riboflavin </label>
                                <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                    <i class="fa fa-question-circle"></i>
                                </span>
                            </div>
                            <input class="form-control price-field" data-realtime="riboflavin" name="riboflavin" value="{{ isset($foodInfo)?$foodInfo->riboflavin:'' }}" id="riboflavin" type="text">
                            <span id="riboflavin" class="help-block" style="color: #a94442;display: none;" ></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div>
                                <label for="niacin" class="strong">Niacin </label>
                                <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                    <i class="fa fa-question-circle"></i>
                                </span>
                            </div>
                            <input class="form-control price-field" data-realtime="niacin" name="niacin" value="{{ isset($foodInfo)?$foodInfo->niacin:'' }}" id="niacin" type="text">
                            <span id="niacin" class="help-block" style="color: #a94442;display: none;" ></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div>
                                <label for="vit_b6" class="strong">Vitamin B6 </label>
                                <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                    <i class="fa fa-question-circle"></i>
                                </span>
                            </div>
                            <input class="form-control price-field" data-realtime="vit_b6" name="vit_b6" value="{{ isset($foodInfo)?$foodInfo->vit_b6:'' }}" id="vit_b6" type="text">
                            <span id="vit_b6" class="help-block" style="color: #a94442;display: none;" ></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div>
                                <label for="folate" class="strong">Folate </label>
                                <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                    <i class="fa fa-question-circle"></i>
                                </span>
                            </div>
                            <input class="form-control price-field" data-realtime="folate" name="folate" value="{{ isset($foodInfo)?$foodInfo->folate:'' }}" id="folate" type="text">
                            <span id="folate" class="help-block" style="color: #a94442;display: none;" ></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div>
                                <label for="vit_b12" class="strong">Vitamin B12 </label>
                                <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                    <i class="fa fa-question-circle"></i>
                                </span>
                            </div>
                            <input class="form-control price-field" data-realtime="vit_b12" name="vit_b12" value="{{ isset($foodInfo)?$foodInfo->vit_b12:''}}" id="vit_b12" type="text">
                            <span id="vit_b12" class="help-block" style="color: #a94442;display: none;" ></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div>
                                <label for="vit_aiu" class="strong">Vitamin AIU</label>
                                <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                    <i class="fa fa-question-circle"></i>
                                </span>
                            </div>
                            <input class="form-control price-field" data-realtime="vit_aiu" name="vit_aiu" value="{{ isset($foodInfo)?$foodInfo->vit_aiu:'' }}" id="vit_aiu" type="text">
                            <span id="vit_aiu" class="help-block" style="color: #a94442;display: none;" ></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div>
                                <label for="vit_arae" class="strong">Vitamin ARAE </label>
                                <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                    <i class="fa fa-question-circle"></i>
                                </span>
                            </div>
                            <input class="form-control price-field" data-realtime="vit_arae" name="vit_arae" value="{{isset($foodInfo)?$foodInfo->vit_arae:''}}" id="vit_arae" type="text">
                            <span id="vit_arae" class="help-block" style="color: #a94442;display: none;" ></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div>
                                <label for="vit_e" class="strong">Vitamin E </label>
                                <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                    <i class="fa fa-question-circle"></i>
                                </span>
                            </div>
                            <input class="form-control price-field" data-realtime="vit_e" name="vit_e" value="{{ isset($foodInfo)?$foodInfo->vit_e:'' }}" id="vit_e" type="text">
                            <span id="vit_e" class="help-block" style="color: #a94442;display: none;" ></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div>
                                <label for="vit_dmcg" class="strong">Vitamin DMCG </label>
                                <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                    <i class="fa fa-question-circle"></i>
                                </span>
                            </div>
                            <input class="form-control price-field" data-realtime="vit_dmcg" name="vit_dmcg" value="{{isset($foodInfo)?$foodInfo->vit_dmcg:'' }}" id="vit_dmcg" type="text">
                            <span id="vit_dmcg" class="help-block" style="color: #a94442;display: none;" ></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div>
                                <label for="vivit_diu" class="strong">Vivit DIU </label>
                                <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                    <i class="fa fa-question-circle"></i>
                                </span>
                            </div>
                            <input class="form-control price-field" data-realtime="vivit_diu" name="vivit_diu" value="{{isset($foodInfo)?$foodInfo->vivit_diu:'' }}" id="vivit_diu" type="text">
                            <span id="vivit_diu" class="help-block" style="color: #a94442;display: none;" ></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div>
                                <label for="vit_k" class="strong">Vitamin K </label>
                                <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                    <i class="fa fa-question-circle"></i>
                                </span>
                            </div>
                            <input class="form-control price-field" data-realtime="vit_k" name="vit_k" value="{{isset($foodInfo)?$foodInfo->vit_k:'' }}" id="vit_k" type="text">
                            <span id="vit_k" class="help-block" style="color: #a94442;display: none;" ></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div>
                                <label for="fa_sat" class="strong">Saturated Fat </label>
                                <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                    <i class="fa fa-question-circle"></i>
                                </span>
                            </div>
                            <input class="form-control price-field" data-realtime="fa_sat" name="fa_sat" value="{{isset($foodInfo)?$foodInfo->fa_sat:''}}" id="fa_sat" type="text">
                            <span id="fa_sat" class="help-block" style="color: #a94442;display: none;" ></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div>
                                <label for="fa_mono" class="strong">Monostaurated Fat </label>
                                <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                    <i class="fa fa-question-circle"></i>
                                </span>
                            </div>
                            <input class="form-control price-field" data-realtime="fa_mono" name="fa_mono" value="{{ isset($foodInfo)?$foodInfo->fa_mono:''}}" id="fa_mono" type="text">
                            <span id="fa_mono" class="help-block" style="color: #a94442;display: none;" ></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div>
                                <label for="fa_poly" class="strong">Polysaturated Fat </label>
                                <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                    <i class="fa fa-question-circle"></i>
                                </span>
                            </div>
                            <input class="form-control price-field" data-realtime="fa_poly" name="fa_poly" value="{{ isset($foodInfo)?$foodInfo->fa_poly:''  }}" id="fa_poly" type="text">
                            <span id="fa_poly" class="help-block" style="color: #a94442;display: none;" ></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div>
                                <label for="cholestrl" class="strong">Cholestrl </label>
                                <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                    <i class="fa fa-question-circle"></i>
                                </span>
                            </div>
                            <input class="form-control price-field" data-realtime="cholestrl" name="cholestrl" value="{{ isset($foodInfo)?$foodInfo->cholestrl:'' }}" id="cholestrl" type="text">
                            <span id="cholestrl" class="help-block" style="color: #a94442;display: none;" ></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div>
                                <label for="caffeine" class="strong">Caffeine </label>
                                <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                    <i class="fa fa-question-circle"></i>
                                </span>
                            </div>
                            <input class="form-control" name="caffeine" value="{{ isset($foodInfo)?$foodInfo->caffeine:'' }}" id="caffeine" type="text">
                            <span id="caffeine" class="help-block" style="color: #a94442;display: none;" ></span>
                        </div>
                    </div> 
                    <div class="col-md-6">
                        <div class="form-group">
                            <div>
                                <label for="priority" class="strong">Priority </label>
                                <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                    <i class="fa fa-question-circle"></i>
                                </span>
                            </div>
                            <input class="form-control" data-realtime="priority" name="priority" value="{{ isset($foodInfo)?$foodInfo->priority:'' }}" id="priority" type="text">
                            <span id="priority" class="help-block" style="color: #a94442;display: none;" ></span>
                        </div>
                    </div> 
                </div>
            </fieldset>
        </div>
    </form>
    <div class="col-md-12">
        @if(isset($foodInfo))
            <button type="button" class="btn btn-primary btn-wide pull-right submit_food"> <i class="fa fa-edit"></i> Update Ingredient</button>
        @else
            <button type="button" class="btn btn-primary btn-wide pull-right submit_food"> <i class="fa fa-plus"></i> Add Ingredient</button>
        @endif
    </div>