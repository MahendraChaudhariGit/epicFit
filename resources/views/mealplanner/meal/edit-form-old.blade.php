<!-- <div class="form-group">
    <label class="strong">Choose a format that best fits your recipe</label>
    <input value="{{ isset($mealInfo->ingredient_set_no) ? $mealInfo->ingredient_set_no : '' }}"
        class="reipe_format" name="reipe_format" hidden>
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
</div> -->




<!-- <div class="form-group">
    <label class="strong">List your ingredients one at a time</label>
     @php
        $ingredient_set_name = json_decode($mealInfo->ingredient_set_name);
     @endphp
    <span class="ingredient-name-1-help-block" style="display: none;color: #d71616;">This field is required</span>
    <input type="text" name="ingredient_name_1" value="{{$ingredient_set_name->set_name_1 ?? null}}" class="form-control ingredients2"
        placeholder="Name your first set of ingredients">
</div> -->

<!-- <div class="form-group recipes-form">
    <div class="draggable-list__item ">
        <span class="draggable-list__hint" aria-label="Drag to rearrange"></span>
        {{-- ingredient -  1  - div   --}}
        <span class="ingredient-help-block" style="display: none;color: #d71616;">please add ingredient</span>
  @if(count($ingredientSetPart1) > 0)
    @foreach($ingredientSetPart1 as $key => $ingr_part1)
        <div class="recipes-form__fieldset form-group ingredient-1-div">
            <div class="recipes-form__item">
                <div class="css-1juasd6-container">
                    <label for="item10" class="css-v23pgw">
                        <span class="css-hlp6ko">Item</span>
                    </label>
                    <input placeholder=" " name="ingredient_item_1[{{$key}}]" type="text"
                        class="forfocuss form-control ingredient-item-1" value="{{ $ingr_part1['item'] }}">
                </div>
            </div>
            
            <div class="recipes-form__measure">
                <div class="css-1juasd6-container">
                    <label for="measurement10" class="css-1dn068x">
                        <span class="css-1j7byy8">Measurement</span>
                    </label>
                    <input class="ingredient_measurement_value_1" name="ingredient_measurement_value_1[{{$key}}]" value="{{$ingr_part1['measurement']??''}}"
                        hidden>
                    <select class="form-control ingredient_measurement_select_1" data-id="{{$key}}"
                        name="ingredient_measurement_1[{{$key}}]">
                        <option value=""> (none)</option>
                        @foreach ($measurement_array as $val)
                            <option value="{{ $val }}" {{ $ingr_part1['measurement'] == $val ? 'selected' : ''}}>{{ ucfirst($val) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="recipes-form__qty">
                <div class="css-1juasd6-container">
                    <label for="qty10" class="css-v23pgw">
                        <span class="css-hlp6ko">Qty</span>
                    </label>
                    <input placeholder=" " name="ingredient_quantity_1[{{$key}}]" type="text"
                        class="forfocuss form-control ingredient-input-1" value="{{$ingr_part1['qty']??''}}">
                </div>
            </div>
            
            <span class="icon__remove remove ingredient-1" data-step="{{$key}}" aria-label="Remove step"></span>
        </div>

        @endforeach
     @else 
         @for ($i = 0; $i < 3; $i++)
            <div class="recipes-form__fieldset form-group ingredient-1-div">
                <div class="recipes-form__item">
                    <div class="css-1juasd6-container">
                        <label for="item10" class="css-v23pgw">
                            <span class="css-hlp6ko">Item</span>
                        </label>
                        <input placeholder=" " name="ingredient_item_1[{{$i}}]" type="text"
                            class="forfocuss form-control ingredient-item-1" value="">
                    </div>
                </div>
                <div class="recipes-form__measure">
                    <div class="css-1juasd6-container">
                        <label for="measurement10" class="css-1dn068x">
                            <span class="css-1j7byy8">Measurement</span>
                        </label>
                        <input class="ingredient_measurement_value_1" name="ingredient_measurement_value_1[{{$i}}]" value=""
                            hidden>
                        <select class="form-control ingredient_measurement_select_1" data-id="{{$i}}"
                            name="ingredient_measurement_1[{{$i}}]">
                            <option value=""> (none)</option>
                            @foreach ($measurement_array as $val)
                                <option value="{{ $val }}">{{ ucfirst($val) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="recipes-form__qty">
                    <div class="css-1juasd6-container">
                        <label for="qty10" class="css-v23pgw">
                            <span class="css-hlp6ko">Qty</span>
                        </label>
                        <input placeholder=" " name="ingredient_quantity_1[{{$i}}]" type="text"
                            class="forfocuss form-control ingredient-input-1" value="">
                    </div>
                </div>
            
                <span class="icon__remove remove ingredient-1" data-step="{{$i}}" aria-label="Remove step"></span>
            </div>
         @endfor
     @endif
        {{-- end ingredient -  1  - div   --}}
        <div class="recipes-form__fieldset form-group">
            <span class="add-ingredient ingredient-1">+ Add another ingredient</span>
        </div>
    </div>
</div>
<hr> -->
<!-- <div class="ingredients2">
  
    <div class="form-group">
        <span class="ingredient-name-2-help-block" style="display: none;color: #d71616;">This field is required</span>
        <input type="text" name="ingredient_name_2" value="{{$ingredient_set_name->set_name_2??null}}"  class="form-control"
            placeholder="Name your second set of ingredients">
    </div>
    <div class="form-group recipes-form">
        <div class="draggable-list__item ">
           <span class="draggable-list__hint" aria-label="Drag to rearrange"></span>
           {{-- ingredient- 2- div --}}
           <span class="ingredient-help-block-2" style="display: none;color: #d71616;">please add ingredient</span>
           @if(count($ingredientSetPart2) > 0)
           @foreach($ingredientSetPart2 as $key => $ingr_part2)   
            <div class="recipes-form__fieldset form-group ingredient-2-div">
                <div class="recipes-form__item">
                    <div class="css-1juasd6-container">
                        <label for="item10" class="css-v23pgw">
                            <span class="css-hlp6ko">Item</span>
                        </label>
                        <input placeholder=" " name="ingredient_item_2[{{$key}}]" type="text"
                            class="forfocuss form-control ingredient-item-2" value="{{$ingr_part2['item']??''}}">
                    </div>
                </div>
                <div class="recipes-form__measure">
                    <div class="css-1juasd6-container">
                        <label for="measurement10" class="css-1dn068x">
                            <span class="css-1j7byy8">Measurement</span>
                        </label>
                        <input class="ingredient_measurement_value_2" name="ingredient_measurement_value_2[{{$key}}]" value="{{$ingr_part2['measurement']??''}}"
                            hidden>
                        <select class="form-control ingredient_measurement_select_2" data-id="{{$key}}"
                            name="ingredient_measurement_2[{{$key}}]">
                            <option value=""> (none)</option>
                            @foreach ($measurement_array as $val)
                                <option value="{{ $val }}" {{ $ingr_part2['measurement'] == $val ? 'selected':'' }}>{{  ucfirst($val) }}</option>
                                {{-- <option value="{{$val['value']}}">{{$val['name']}}</option> --}}
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="recipes-form__qty">
                    <div class="css-1juasd6-container">
                        <label for="qty10" class="css-v23pgw">
                            <span class="css-hlp6ko">Qty</span>
                        </label>
                        <input placeholder=" " name="ingredient_quantity_2[{{$key}}]" type="text"
                            class="forfocuss form-control ingredient-input-2" value="{{$ingr_part2['qty']??''}}">
                    </div>
                </div>
                
                <span class="icon__remove remove ingredient-2" data-step="{{$key}}" aria-label="Remove step"></span>
            </div>  
            {{-- end --}}
            @endforeach    
            @else
              @for ($i = 0; $i < 3; $i++)
                <div class="recipes-form__fieldset form-group ingredient-2-div">
                    <div class="recipes-form__item">
                        <div class="css-1juasd6-container">
                            <label for="item10" class="css-v23pgw">
                                <span class="css-hlp6ko">Item</span>
                            </label>
                            <input placeholder=" " name="ingredient_item_2[{{$i}}]" type="text"
                                class="forfocuss form-control ingredient-item-2" value="">
                        </div>
                    </div>
                    <div class="recipes-form__measure">
                        <div class="css-1juasd6-container">
                            <label for="measurement10" class="css-1dn068x">
                                <span class="css-1j7byy8">Measurement</span>
                            </label>
                            <input class="ingredient_measurement_value_2" name="ingredient_measurement_value_2[{{$i}}]" value=""
                                hidden>
                            <select class="form-control ingredient_measurement_select_2" data-id="{{$i}}"
                                name="ingredient_measurement_2[{{$i}}]">
                                <option value=""> (none)</option>
                                @foreach ($measurement_array as $val)
                                    <option value="{{ $val }}">{{  ucfirst($val) }}</option>
                                    {{-- <option value="{{$val['value']}}">{{$val['name']}}</option> --}}
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="recipes-form__qty">
                        <div class="css-1juasd6-container">
                            <label for="qty10" class="css-v23pgw">
                                <span class="css-hlp6ko">Qty</span>
                            </label>
                            <input placeholder=" " name="ingredient_quantity_2[{{$i}}]" type="text"
                                class="forfocuss form-control ingredient-input-2" value="">
                        </div>
                    </div>
                    
                    <span class="icon__remove remove ingredient-2" data-step="{{$i}}" aria-label="Remove step"></span>
                </div>   
                @endfor
            @endif

            <div class="recipes-form__fieldset form-group">
                <span class="add-ingredient ingredient-2">+ Add another ingredient</span>
            </div>

        </div>
    </div>
    <hr>
</div> -->
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
    <span class="preparation-help-block" style="display: none;color: #d71616;">please add preparation</span>
    <label for="method" class="strong">Preparation *</label>
    <span class="epic-tooltip tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="left">
        <i class="fa fa-question-circle"></i>
    </span>
      <div class="optionBox">
     @if(count($preparationPart1) > 0)
        @foreach($preparationPart1 as $key =>$prep_part1 )
            <div class="blockk preparation-1-div">
                <label for="body00" class="css-v23pgw"><span class="css-hlp6ko">Step {{$key+1}}</span></label>
                <div class="transparent-bg"></div>
                <textarea class="form-control steps forfocuss preparation-1-input"
                    name="meal_preparation_1[{{$key}}]">{{$prep_part1->description}}</textarea><span class="icon__remove remove preparation-1" data-step="{{$key}}"
                    aria-label="Remove step"></span>
            </div>
        @endforeach     
        @else
            <div class="blockk preparation-1-div">
                <label for="body00" class="css-v23pgw"><span class="css-hlp6ko">Step 1</span></label>
                <div class="transparent-bg"></div>
                <textarea class="form-control steps forfocuss preparation-1-input"
                    name="meal_preparation_1[0]"></textarea><span class="icon__remove remove preparation-1" data-step="0"
                    aria-label="Remove step"></span>
            </div> 
            <div class="blockk preparation-1-div">
                <label for="body00" class="css-v23pgw"><span class="css-hlp6ko">Step 2</span></label>
                <div class="transparent-bg"></div>
                <textarea class="form-control steps forfocuss preparation-1-input"
                    name="meal_preparation_1[1]"></textarea><span class="icon__remove remove preparation-1" data-step="1"
                    aria-label="Remove step"></span>
            </div>   
      @endif   
        <div class="blockk"> <span class="add preparation-1">+ Add another step</span>

        </div>
    </div>
    <hr>
    <div class="optionBox preparation2">
        <span class="preparation-help-block-2" style="display: none;color: #d71616;">please add preparation</span>
        @if(count($preparationPart2) > 0)
        @foreach($preparationPart2 as $key =>$prep_part2 )
        <div class="blockk preparation-2-div">
            <label for="body00" class="css-v23pgw"><span class="css-hlp6ko">Step {{$key+1}}</span></label>
             <div class="transparent-bg"></div>
            <textarea class="form-control steps forfocuss preparation-2-input"
                name="meal_preparation_2[{{$key}}]">{{$prep_part2->description}}</textarea><span class="icon__remove remove preparation-2" data-step="{{$key}}"
                aria-label="Remove step"></span>

        </div>
        @endforeach     
        @else
        <div class="blockk preparation-2-div">
            <label for="body00" class="css-v23pgw"><span class="css-hlp6ko">Step 1</span></label>
             <div class="transparent-bg"></div>
            <textarea class="form-control steps forfocuss preparation-2-input"
                name="meal_preparation_2[0]"></textarea><span class="icon__remove remove preparation-2" data-step="0"
                aria-label="Remove step"></span>

        </div>
        <div class="blockk preparation-2-div">
            <label for="body00" class="css-v23pgw"><span class="css-hlp6ko">Step 2</span></label>
             <div class="transparent-bg"></div>
            <textarea class="form-control steps forfocuss preparation-2-input"
                name="meal_preparation_2[1]"></textarea><span class="icon__remove remove preparation-2" data-step="1"
                aria-label="Remove step"></span>

        </div>
        @endif
        <div class="blockk"> <span class="add preparation-2">+ Add another step</span>

        </div>
        <hr>
    </div>
    <!--  <textarea class="ckeditor form-control rounded-0" required name="method" id="method" rows="10">{{ isset($mealInfo) ? $mealInfo->method : '' }}</textarea> -->
    <span id="method" class="help-block" style="color: #a94442;display: none;"></span>
    {{-- <span>Count: <span id=countMethod>0</span>/850</span> --}}
</div>
