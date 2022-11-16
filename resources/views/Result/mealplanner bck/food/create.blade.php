
@extends('Result.masters.app')

@section('page-title')
<span >Add Food</span> 
@stop
@section('required-styles')
{!! Html::style('result/plugins/tooltipster-master/tooltipster.css?v='.time()) !!}

<!-- start: Summernote -->
{!! Html::style('result/plugins/summernote/dist/summernote.css?v='.time()) !!}
<!-- end: Summernote -->
{!! Html::style('result/plugins/bootstrap-select-master/css/bootstrap-select.min.css?v='.time()) !!}
{!! Html::style('result/plugins/intl-tel-input-master/build/css/intlTelInput.css?v='.time()) !!}
{!! Html::style('result/plugins/sweetalert/sweet-alert.css?v='.time()) !!} 

{!! Html::style('result/css/custom.css?v='.time()) !!}
<!-- End: NEW timepicker css -->

@stop
@section('content')

<div id="panel_edit_account" class="tab-pane active">
    <div class="alert alert-success" style="display:none;" id="suc_msg"></div>                
     <div>

    <div class="row swMain">
    <form method="POST" accept-charset="UTF-8" id="form" class="margin-bottom-30">
    <input name="_token" value="" type="hidden">
        
    <div class="col-md-6">
    <fieldset class="padding-15">
        <legend>
            General
        </legend>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div>
                        <label for="short_desc" class="strong"> Food Name </label>
                        <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                            <i class="fa fa-question-circle"></i>
                        </span>
                    </div>
                    <input class="form-control" required="required" data-realtime="short_desc" name="short_desc" value="" id="short_desc" type="text">
                    <span id="short_desc" class="help-block" style="color: #a94442;display: none;" ></span>
                </div>

                <div class="form-group">
                    <div>
                        <label for="long_desc" class="strong">Food Description *</label>
                        <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                            <i class="fa fa-question-circle"></i>
                        </span>
                    </div>
                    <textarea class="form-control rounded-0" data-realtime="long_desc" required="required" name="long_desc" value="" id="long_desc" rows="10"></textarea>
                    <span id="long_desc" class="help-block" style="color: #a94442;display: none;" ></span>

                </div>

                <div class="form-group">
                    <div>
                        <label for="shopping_category" class="strong">Shopping Category *</label>
                        <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                            <i class="fa fa-question-circle"></i>
                        </span>
                    </div>
                    <select id="shopping_category" name="shopping_category">
                    @foreach($shoppingCat as $cat)
                         <option value="{{$cat->id}}">{{$cat->shopping_category_desc}}</option>
                    @endforeach
                    </select>
                    <!-- <input class="form-control" data-realtime="shopping_category" required="required" name="shopping_category" value="" id="shopping_category" type="text"> -->
                    <span id="shopping_category" class="help-block" style="color: #a94442;display: none;" ></span>
                </div>
                    <div class="form-group"></div>
                    <div class="form-group">
                        <label for="is_drink" class="strong">Is Drink? *</label>
                        <div class="">
                            <div class="radio clip-radio radio-primary radio-inline m-b-0">
                                <input type="radio" name="is_drink" id="gridRadios1" value="1">
                                <label for="gridRadios1"> Yes </label>
                            </div>
                            <div class="radio clip-radio radio-primary radio-inline m-b-0">
                                <input type="radio" name="is_drink" id="gridRadios2" value="0">
                                <label for="gridRadios2"> No </label>
                            </div>
                        </div>
                    </div>
            

            </div>
        </div>
    </fieldset> 
    </div>
    <div class="col-md-6">
        <fieldset class="padding-15">
        <legend>
            Nutritional Information
        </legend>
        <div class="row">
                            <div class="col-md-6">
                    <div class="form-group">
                        <div>
                            <label for="water" class="strong">Water </label>
                            <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                <i class="fa fa-question-circle"></i>
                            </span>
                        </div>
                        <input class="form-control" data-realtime="water" name="water" value="" id="water" type="text">
                        <span id="water" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div>
                            <label for="energ_kcal" class="strong">Energy(Kcal) </label>
                            <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                <i class="fa fa-question-circle"></i>
                            </span>
                        </div>
                        <input class="form-control" data-realtime="energ_kcal" name="energ_kcal" value="" id="energ_kcal" type="text">
                        <span id="energ_kcal" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div>
                            <label for="protein" class="strong">Protein </label>
                            <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                <i class="fa fa-question-circle"></i>
                            </span>
                        </div>
                        <input class="form-control" data-realtime="protein" name="protein" value="" id="protein" type="text">
                        <span id="protein" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div>
                            <label for="lipid_total" class="strong">Lipid Total </label>
                            <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                <i class="fa fa-question-circle"></i>
                            </span>
                        </div>
                        <input class="form-control" data-realtime="lipid_total" name="lipid_total" value="" id="lipid_total" type="text">
                        <span id="lipid_total" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div>
                            <label for="ash" class="strong">Ash </label>
                            <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                <i class="fa fa-question-circle"></i>
                            </span>
                        </div>
                        <input class="form-control" data-realtime="ash" name="ash" value="" id="ash" type="text">
                        <span id="ash" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div>
                            <label for="carbohydrate" class="strong">Carbohydrate </label>
                            <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                <i class="fa fa-question-circle"></i>
                            </span>
                        </div>
                        <input class="form-control" data-realtime="carbohydrate" name="carbohydrate" value="" id="carbohydrate" type="text">
                        <span id="carbohydrate" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div>
                            <label for="fiber" class="strong">Fiber </label>
                            <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                <i class="fa fa-question-circle"></i>
                            </span>
                        </div>
                        <input class="form-control" data-realtime="fiber" name="fiber" value="" id="fiber" type="text">
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
                        <input class="form-control" data-realtime="sugar" name="sugar" value="" id="sugar" type="text">
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
                        <input class="form-control" data-realtime="calcium" name="calcium" value="" id="calcium" type="text">
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
                        <input class="form-control" data-realtime="iron" name="iron" value="" id="iron" type="text">
                        <span id="iron" class="help-block" style="color: #a94442;display: none;" ></span>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div>
                            <label for="magnesium" class="strong">Magnesium </label>
                            <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                <i class="fa fa-question-circle"></i>
                            </span>
                        </div>
                        <input class="form-control" data-realtime="magnesium" name="magnesium" value="" id="magnesium" type="text">
                        <span id="magnesium" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div>
                            <label for="phosphorus" class="strong">Phosphorus </label>
                            <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                <i class="fa fa-question-circle"></i>
                            </span>
                        </div>
                        <input class="form-control" data-realtime="phosphorus" name="phosphorus" value="" id="phosphorus" type="text">
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
                        <input class="form-control" data-realtime="potassium" name="potassium" value="" id="potassium" type="text">
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
                        <input class="form-control" data-realtime="sodium" name="sodium" value="" id="sodium" type="text">
                        <span id="sodium" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div>
                            <label for="zinc" class="strong">Zinc </label>
                            <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                <i class="fa fa-question-circle"></i>
                            </span>
                        </div>
                        <input class="form-control" data-realtime="zinc" name="zinc" value="" id="zinc" type="text">
                        <span id="zinc" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div>
                            <label for="copper" class="strong">Copper </label>
                            <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                <i class="fa fa-question-circle"></i>
                            </span>
                        </div>
                        <input class="form-control" data-realtime="copper" name="copper" value="" id="copper" type="text">
                        <span id="copper" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div>
                            <label for="manganese" class="strong">Manganese </label>
                            <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                <i class="fa fa-question-circle"></i>
                            </span>
                        </div>
                        <input class="form-control" data-realtime="manganese" name="manganese" value="" id="manganese" type="text">
                        <span id="manganese" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div>
                            <label for="selenium" class="strong">Selenium </label>
                            <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                <i class="fa fa-question-circle"></i>
                            </span>
                        </div>
                        <input class="form-control" data-realtime="selenium" name="selenium" value="" id="selenium" type="text">
                        <span id="selenium" class="help-block" style="color: #a94442;display: none;" ></span>
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
                        <input class="form-control" data-realtime="vit_c" name="vit_c" value="" id="vit_c" type="text">
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
                        <input class="form-control" data-realtime="thiamin" name="thiamin" value="" id="thiamin" type="text">
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
                        <input class="form-control" data-realtime="riboflavin" name="riboflavin" value="" id="riboflavin" type="text">
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
                        <input class="form-control" data-realtime="niacin" name="niacin" value="" id="niacin" type="text">
                        <span id="niacin" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div>
                            <label for="panto_acid" class="strong">Panto Acid </label>
                            <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                <i class="fa fa-question-circle"></i>
                            </span>
                        </div>
                        <input class="form-control" data-realtime="panto_acid" name="panto_acid" value="" id="panto_acid" type="text">
                        <span id="panto_acid" class="help-block" style="color: #a94442;display: none;" ></span>
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
                        <input class="form-control" data-realtime="vit_b6" name="vit_b6" value="" id="vit_b6" type="text">
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
                        <input class="form-control" data-realtime="folate" name="folate" value="" id="folate" type="text">
                        <span id="folate" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div>
                            <label for="folic_acid" class="strong">Folic Acid </label>
                            <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                <i class="fa fa-question-circle"></i>
                            </span>
                        </div>
                        <input class="form-control" data-realtime="folic_acid" name="folic_acid" value="" id="folic_acid" type="text">
                        <span id="folic_acid" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
                 <div class="col-md-6">
                    <div class="form-group">
                        <div>
                            <label for="food_folate" class="strong">Food Folate </label>
                            <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                <i class="fa fa-question-circle"></i>
                            </span>
                        </div>
                        <input class="form-control" data-realtime="food_folate" name="food_folate" value="" id="food_folate" type="text">
                        <span id="food_folate" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div>
                            <label for="folate_dfe" class="strong">Folate DFE </label>
                            <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                <i class="fa fa-question-circle"></i>
                            </span>
                        </div>
                        <input class="form-control" data-realtime="folate_dfe" name="folate_dfe" value="" id="folate_dfe" type="text">
                        <span id="folate_dfe" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div>
                            <label for="choline" class="strong">Choline </label>
                            <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                <i class="fa fa-question-circle"></i>
                            </span>
                        </div>
                        <input class="form-control" data-realtime="choline" name="choline" value="" id="choline" type="text">
                        <span id="choline" class="help-block" style="color: #a94442;display: none;" ></span>
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
                        <input class="form-control" data-realtime="vit_b12" name="vit_b12" value="" id="vit_b12" type="text">
                        <span id="vit_b12" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div>
                            <label for="vit_aiu" class="strong">Vitamin AIU </label>
                            <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                <i class="fa fa-question-circle"></i>
                            </span>
                        </div>
                        <input class="form-control" data-realtime="vit_aiu" name="vit_aiu" value="" id="vit_aiu" type="text">
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
                        <input class="form-control" data-realtime="vit_arae" name="vit_arae" value="" id="vit_arae" type="text">
                        <span id="vit_arae" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div>
                            <label for="retinol" class="strong">Retinol </label>
                            <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                <i class="fa fa-question-circle"></i>
                            </span>
                        </div>
                        <input class="form-control" data-realtime="retinol" name="retinol" value="" id="retinol" type="text">
                        <span id="retinol" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div>
                            <label for="alphacarot" class="strong">Alphacarot </label>
                            <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                <i class="fa fa-question-circle"></i>
                            </span>
                        </div>
                        <input class="form-control" data-realtime="alphacarot" name="alphacarot" value="" id="alphacarot" type="text">
                        <span id="alphacarot" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div>
                            <label for="beta_carot" class="strong">Beta Carot </label>
                            <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                <i class="fa fa-question-circle"></i>
                            </span>
                        </div>
                        <input class="form-control" data-realtime="beta_carot" name="beta_carot" value="" id="beta_carot" type="text">
                        <span id="beta_carot" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div>
                            <label for="beta_crypt" class="strong">Beta Crypt </label>
                            <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                <i class="fa fa-question-circle"></i>
                            </span>
                        </div>
                        <input class="form-control" data-realtime="beta_crypt" name="beta_crypt" value="" id="beta_crypt" type="text">
                        <span id="beta_crypt" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div>
                            <label for="lycopene" class="strong">Lycopene </label>
                            <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                <i class="fa fa-question-circle"></i>
                            </span>
                        </div>
                        <input class="form-control" data-realtime="lycopene" name="lycopene" value="" id="lycopene" type="text">
                        <span id="lycopene" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div>
                            <label for="lut_zea" class="strong">Lut Zea </label>
                            <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                <i class="fa fa-question-circle"></i>
                            </span>
                        </div>
                        <input class="form-control" data-realtime="lut_zea" name="lut_zea" value="" id="lut_zea" type="text">
                        <span id="lut_zea" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div>
                            <label for="vit_e" class="strong">Vitamin E</label>
                            <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                <i class="fa fa-question-circle"></i>
                            </span>
                        </div>
                        <input class="form-control" data-realtime="vit_e" name="vit_e" value="" id="vit_e" type="text">
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
                        <input class="form-control" data-realtime="vit_dmcg" name="vit_dmcg" value="" id="vit_dmcg" type="text">
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
                        <input class="form-control" data-realtime="vivit_diu" name="vivit_diu" value="" id="vivit_diu" type="text">
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
                        <input class="form-control" data-realtime="vit_k" name="vit_k" value="" id="vit_k" type="text">
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
                        <input class="form-control" data-realtime="fa_sat" name="fa_sat" value="" id="fa_sat" type="text">
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
                        <input class="form-control" data-realtime="fa_mono" name="fa_mono" value="" id="fa_mono" type="text">
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
                        <input class="form-control" data-realtime="fa_poly" name="fa_poly" value="" id="fa_poly" type="text">
                        <span id="fa_poly" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div>
                            <label for="cholestrl" class="strong">Cholestrol </label>
                            <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
                                <i class="fa fa-question-circle"></i>
                            </span>
                        </div>
                        <input class="form-control" data-realtime="cholestrl" name="cholestrl" value="" id="cholestrl" type="text">
                        <span id="cholestrl" class="help-block" style="color: #a94442;display: none;" ></span>
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
                        <input class="form-control" data-realtime="priority" name="priority" value="" id="priority" type="text">
                        <span id="priority" class="help-block" style="color: #a94442;display: none;" ></span>
                    </div>
                </div>
        </div>
    </fieldset>
    <div class="col-md-12">
        <div class="form-group">
        <!--button class="btn btn-primary btn-wide pull-right btn-add-more-form update-client"-->
            <button type="button" class="btn btn-primary btn-wide pull-right btn-add-more-form" id="add_food"> Add
        </button>
         </div>
         <div class="form-group"></div>
    </div>
    </div>
    </form>
       
    </div>
    </div>
    </div>

@endsection

@section('required-script')
{!! Html::script('result/js/jquery-ui.min.js?v='.time()) !!}

<!-- start: Moment Library -->
{!! Html::script('result/plugins/moment/moment.min.js?v='.time()) !!}
<!-- end: Moment Library -->

<!-- start: Summernote -->
{!! Html::script('result/plugins/summernote/dist/summernote.min.js?v='.time()) !!}
<!-- end: Summernote -->
<!-- start: Rating -->
{!! Html::script('result/plugins/bootstrap-rating/bootstrap-rating.min.js?v='.time()) !!}
<!-- end: Rating -->
<!-- start: Bootstrap Typeahead -->
{!! Html::script('result/plugins/bootstrap3-typeahead/js/bootstrap3-typeahead.min.js?v='.time()) !!}  
<!-- end: Bootstrap Typeahead --> 
<!-- start: Bootstrap Typeahead -->
{!! Html::script('result/plugins/jQuery-Smart-Wizard/js/jquery.smartWizard.js?v='.time()) !!}  
<!-- end: Bootstrap Typeahead --> 
<!-- start: Bootstrap timepicker -->

{!! Html::script('result/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js?v='.time()) !!}
<!-- end: Bootstrap timepicker -->
{!! Html::script('result/plugins/tooltipster-master/jquery.tooltipster.min.js?v='.time()) !!}


{!! Html::script('result/plugins/bootstrap-select-master/js/bootstrap-select.min.js?v='.time()) !!}
{!! Html::script('result/plugins/intl-tel-input-master/build/js/utils.js?v='.time()) !!}
{!! Html::script('result/plugins/intl-tel-input-master/build/js/intlTelInput.js?v='.time()) !!}
{!! Html::script('result/plugins/jquery-validation/jquery.validate.min.js?v='.time()) !!}
{!! Html::script('result/js/meal-planner.js?v='.time()) !!}
{!! Html::script('result/plugins/sweetalert/sweet-alert.min.js?v='.time()) !!}
<!-- start: image upload js -->
{!! Html::script('result/plugins/Jcrop/js/jquery.Jcrop.min.js?v='.time()) !!}
{!! Html::script('result/plugins/Jcrop/js/script.js?v='.time()) !!}
<!-- start: image upload js -->

{!! Html::script('result/js/form-wizard-clients.js?v='.time()) !!}
{!! Html::script('result/js/form-wizard-benchmark.js?v='.time()) !!}
{!! Html::script('result/js/benchmark.js?v='.time()) !!}
{!! Html::script('result/js/helper.js?v='.time()) !!}
<!--{!! Html::script('js/events.js') !!}-->
{!! Html::script('result/js/bench.js?v='.time()) !!}
{!! Html::script('result/js/clients.js?v='.time()) !!}





<script>
    var loggedInUser = {
        type: '{{ Auth::user()->account_type }}',
        id: '{{ Auth::user()->account_id }}',
        name: '{{ Auth::user()->name }}'
    },
            popoverContainer = $('#container');
</script>

@stop