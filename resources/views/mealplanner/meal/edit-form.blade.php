<div class="form-group">
    <label class="strong">Choose a format that best fits your recipe</label>
    <input @if (isset($mealInfo->ingredient_set_no)) value="{{ $mealInfo->ingredient_set_no }}" @else  value="1" @endif class="reipe_format" name="reipe_format" hidden>
    <div class="recipes-form__style">
        <label class="recipes-form__style-label simple @if (isset($mealInfo->ingredient_set_no) && $mealInfo->ingredient_set_no == 1) active @endif">
            <input type="radio" name="recipe_style">Simple
        </label>
        <label class="recipes-form__style-label two-part-i @if (isset($mealInfo->ingredient_set_no) && $mealInfo->ingredient_set_no == 2) active @endif">
            <input type="radio" name="recipe_style">Two-Part<br>Ingredient List
        </label>
        <label class="recipes-form__style-label two-part-r @if (isset($mealInfo->ingredient_set_no) && $mealInfo->ingredient_set_no == 3) active @endif">
            <input type="radio" name="recipe_style">Two-Part Recipe
        </label>
    </div>
    <hr>
</div>
    @php
      $ingredient_set_name = json_decode($mealInfo->ingredient_set_name);
    @endphp
{{-- start  first --}}
<div class="form-group">
    <label class="strong">List your ingredients one at a time</label>
    <div class="ingredients2">
    <span class="ingredient-name-1-help-block" style="display: none;color: red;">This field is
        required</span>
    <input type="text" name="ingredient_name_1"  value="{{$ingredient_set_name->set_name_1 ?? null}}" class="form-control"
        placeholder="Name your first set of ingredients">
        </div>

</div>
<span class="ingredient-help-block" style="display: none;color: red;">please add ingredient</span>
<div class="analyze-your-meal form-group">
    <form id="analyze-form" action="/website/wizard.jsp" method="get">
        <input id="analyze-version" type="hidden" value="" name="ver">
        <div class="row">
            <div class="col-md-12 core">
                <div class="row">
                    <div class="col-md-12">
                        <h3><span>Analyze your meal</span>
                            <div class="tips" data-toggle="modal" data-target="#myModalTips">Tips
                            </div>
                        </h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <textarea class="form-control analyze" id="ingredients" name="ingr_textarea_1" placeholder="For example:
1 cup orange juice
3 tablespoons olive oil
2 carrots" rows="">{{isset($mealInfo->ingredients)?$mealInfo->ingredients:''}}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-6">
                {{-- <input type="text" value="1" placeholder="1" name="serving" class="serving" maxlength="5">
                <span>Serving</span> --}}
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6">
                <a class="more analyze_data" id="homeAnalyzeBtn">Analyze</a>
            </div>
        </div>
    </form>

</div>

<div class="ingredients-new form-group">
    {{-- edit list --}}
    {{-- <div class="edit-ingr-div"> 
     
    </div> --}}
    {{-- edit list  --}}
    <div class="show-all-list-div"> </div>

    {{-- <div class="col-md-12 bottom add-area" style="display: none;">
<div class="input-group">
<input type="text" class="form-control" id="addIngrField">
<span class="input-group-btn">
    <a href="javascript:void(0)" class="more" id="addIngrButton">Add</a>
</span>
</div>

</div>
<div class="row bottom">
<div class="col-md-6">
<input type="text" value="1" placeholder="1" id="recipeServings" name="serving" class="serving"
    maxlength="5"><span>Serving</span>
</div>
<div class="col-md-6 text-right">
<a href="javascript:void(0)" class="add">
    <span class="ing">Add Ingredient</span>
    <span class="plus">+</span>
</a>
</div>
</div> --}}
    {{-- end add --}}

</div>
{{-- end first --}}

{{-- second --}}

<div class="ingredients2 form-group">
    <hr>
    <!--for TWO-PART
INGREDIENT LIST TWO-PART RECIPE-->
    <div class="form-group">
        <span class="ingredient-name-2-help-block" style="display: none;color: red;">This field is
            required</span>
        <input type="text" name="ingredient_name_2" value="{{$ingredient_set_name->set_name_2??null}}" class="form-control"
            placeholder="Name your second set of ingredients">
    </div>
    <span class="ingredient-help-block-2" style="display: none;color: red;">please add ingredient</span>
    <div class="analyze-your-meal form-group">
        <input id="" type="hidden" value="" name="ver">
        <div class="row">
            <div class="col-md-12 core">
                <div class="row">
                    <div class="col-md-12">
                        <h3><span>Analyze your meal</span>
                            <div class="tips" data-toggle="modal" data-target="#myModalTips">Tips
                            </div>
                        </h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <textarea class="form-control analyze" id="ingredients-2" name="ingr_textarea_2" placeholder="For example:
1 cup orange juice
3 tablespoons olive oil
2 carrots" rows="" autocomplete="off">{{isset($mealInfo->second_ingredients)?$mealInfo->second_ingredients:''}}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-6">
                {{-- <input type="text" value="1" placeholder="1" name="serving-2" class="serving" maxlength="5">
                <span>Serving</span> --}}
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6">
                <a class="more analyze_data-2">Analyze</a>
                {{-- <a class="more analyze_data" id="homeAnalyzeBtn">Analyze</a> --}}
            </div>
        </div>


    </div>
    <hr>
</div>


{{-- end second --}}

<div class="ingredients-new form-group">
    <div class="edit-ingr-div-2"> </div>
    <div class="show-all-list-div-2">
    </div>
</div>
{{-- ingredients for second --}}

<div class="mobile-view">

</div>
{{-- button --}}
{{-- <span class="analyze_data">
<button type="button" class="btn">Analyze </button>
</span> --}}
{{-- button --}}
<div class="form-group">
    <label class="strong">Add your instructions one at a time</label>
</div>
<div class="form-group">
    <span class="preparation-help-block" style="display: none;color: red;margin-bottom: 10px;">please add preparation</span>
    <label for="method" class="strong">Preparation *</label>
    <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
        <i class="fa fa-question-circle"></i>
    </span>
    <div class="optionBox">
        @if (count($preparationPart1) > 0)
            @foreach ($preparationPart1 as $key => $prep_part1)
                <div class="blockk preparation-1-div">
                    <label for="body00" class="css-v23pgw"><span class="css-hlp6ko">Step
                            {{ $key + 1 }}</span></label>
                    <div class="transparent-bg"></div>
                    <textarea class="form-control steps forfocuss preparation-1-input"
                        name="meal_preparation_1[{{ $key }}]">{{ $prep_part1->description }}</textarea><span
                        class="icon__remove remove preparation-1" data-step="{{ $key }}"
                        aria-label="Remove step"></span>
                </div>
            @endforeach
        @else
            <div class="blockk preparation-1-div">
                <label for="body00" class="css-v23pgw"><span class="css-hlp6ko">Step 1</span></label>
                <div class="transparent-bg"></div>
                <textarea class="form-control steps forfocuss preparation-1-input"
                    name="meal_preparation_1[0]"></textarea><span class="icon__remove remove preparation-1"
                    data-step="0" aria-label="Remove step"></span>
            </div>
            <div class="blockk preparation-1-div">
                <label for="body00" class="css-v23pgw"><span class="css-hlp6ko">Step 2</span></label>
                <div class="transparent-bg"></div>
                <textarea class="form-control steps forfocuss preparation-1-input"
                    name="meal_preparation_1[1]"></textarea><span class="icon__remove remove preparation-1"
                    data-step="1" aria-label="Remove step"></span>
            </div>
        @endif
        <div class="blockk"> <span class="add preparation-1">+ Add another step</span>

        </div>
    </div>
    <hr>
    <div class="optionBox preparation2">
        <span class="preparation-help-block-2" style="display: none;color: red;margin-bottom: 10px;">please add preparation</span>
        @if (count($preparationPart2) > 0)
            @foreach ($preparationPart2 as $key => $prep_part2)
                <div class="blockk preparation-2-div">
                    <label for="body00" class="css-v23pgw"><span class="css-hlp6ko">Step
                            {{ $key + 1 }}</span></label>
                    <div class="transparent-bg"></div>
                    <textarea class="form-control steps forfocuss preparation-2-input"
                        name="meal_preparation_2[{{ $key }}]">{{ $prep_part2->description }}</textarea><span
                        class="icon__remove remove preparation-2" data-step="{{ $key }}"
                        aria-label="Remove step"></span>

                </div>
            @endforeach
        @else
            <div class="blockk preparation-2-div">
                <label for="body00" class="css-v23pgw"><span class="css-hlp6ko">Step 1</span></label>
                <div class="transparent-bg"></div>
                <textarea class="form-control steps forfocuss preparation-2-input"
                    name="meal_preparation_2[0]"></textarea><span class="icon__remove remove preparation-2"
                    data-step="0" aria-label="Remove step"></span>

            </div>
            <div class="blockk preparation-2-div">
                <label for="body00" class="css-v23pgw"><span class="css-hlp6ko">Step 2</span></label>
                <div class="transparent-bg"></div>
                <textarea class="form-control steps forfocuss preparation-2-input"
                    name="meal_preparation_2[1]"></textarea><span class="icon__remove remove preparation-2"
                    data-step="1" aria-label="Remove step"></span>

            </div>
        @endif
        <div class="blockk"> <span class="add preparation-2">+ Add another step</span>

        </div>
        <hr>
    </div>
    <!--  <textarea class="ckeditor form-control rounded-0" required name="method" id="method" rows="10">{{ isset($mealInfo) ? $mealInfo->method : '' }}</textarea> -->
    <span id="method" class="help-block" style="color: red;display: none;"></span>
    {{-- <span>Count: <span id=countMethod>0</span>/850</span> --}}
</div>
