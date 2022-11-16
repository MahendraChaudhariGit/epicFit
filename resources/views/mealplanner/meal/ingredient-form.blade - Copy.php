{{-- <div class="ingredients-new form-group"> --}}
{{-- <div class="col-md-12 list" style="display:none"> --}}
{{-- start loop --}}
@php
$i = 0;
@endphp
@foreach ($ingr_val as $key => $val)

    @php
        
        if (count($val['parsed']) > 1) {
            $text = null;
        } else {
            $text = $val['text'];
        }
        
    @endphp

    @foreach ($val['parsed'] as $index => $final_val)

        <div class="col-md-12 list list-{{ $i }}" data-id="{{ $i }}">
            <div class="row">
                <div class="col-md-10 col-xs-8 line">
                    @if ($text == null)
                        <span class="line-text-{{ $i }}">{{ $final_val['quantity'] ?? 0 }}
                            {{ $final_val['measure'] ?? '' }} {{ $final_val['foodMatch'] ?? '' }}</span>
                    @else
                        <span class="line-text-{{ $i }}">{{ $text }}</span>
                    @endif
                    {{-- {{ $final_val['foodMatch']??''}} --}}
                    <span class="err">
                        {{-- <i class="glyphicon glyphicon-exclamation-sign"></i>
                    <span>&nbsp;Incomplete ingredient match.</span> --}}
                    </span>
                </div>

                <div class="col-md-2 col-xs-4">
                    <span class="pull-right delete-ingr" data-id="{{ $i }}"></span>
                    <span class="pull-right edit-ingr" data-id="{{ $i }}"></span>
                </div>
            </div>
            <div class="line-ingr">
                <div class="col-md-12">
                    {{-- <div class="row edit-ing"> --}}
                    <div class="row edit-ing-{{ $i }}" style="display: none;">

                        <div class="form-group">
                            @if ($text == null)

                                <input type="text"
                                    data-old="{{ $final_val['quantity'] ?? 0 }} {{ $final_val['measure'] ?? '' }} {{ $final_val['foodMatch'] ?? '' }}"
                                    value="{{ $final_val['quantity'] ?? 0 }} {{ $final_val['measure'] ?? '' }} {{ $final_val['foodMatch'] ?? '' }}"
                                    class="form-control title-sel full-name-{{ $i }}" autocomplete="off">
                            @else

                                <input type="text" data-old="{{ $text ?? '' }}" value="{{ $text ?? '' }}"
                                    class="form-control title-sel full-name-{{ $i }}" autocomplete="off">
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row edit-ing-{{ $i }}" style="display: none;">

                    <div class="col-md-12 text-center">
                        <a href="javascript:void(0)" class="more updateRecipeIngr" data-id="{{ $i }}">Update
                            Ingredient</a>
                        <a href="javascript:void(0)" class="more edit-ingr-btn"
                            data-id="{{ $i }}">Cancel</a>
                            <hr style="border-color: #bfc1c3;">
                    </div>
                </div>

                <input class="fetch-other-data"
                    value="{{ $final_val['quantity'] ?? '' }} {{ $final_val['measure'] ?? '' }} {{ $final_val['foodMatch'] ?? '' }}"
                    hidden>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group bg-white">
                            <select class="form-control food-match-{{ $i }} food-match-select"
                                data-id="{{ $i }}">
                                <optgroup>
                                    <option>{{ $final_val['foodMatch'] ?? '' }}</option>
                                    {{-- <option>Milk</option> --}}
                                </optgroup>
                            </select>
                            <span class="bs-caret cr"><span class="caret"></span></span>
                        </div>

                    </div>
                </div>


                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="text" value="{{ $final_val['quantity'] ?? '' }}"
                                class="form-control quantity-sel food-qty-{{ $i }}">
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="form-group bg-white">
                            <select class="form-control food-measure-{{ $i }}">
                                <optgroup>
                                    <option>{{ $final_val['measure'] ?? '' }}</option>
                                    {{-- <option>Gram</option>
                                <option>Pound</option>
                                <option>Ounce </option>
                                <option>Litre</option>
                                <option>Millilitre</option>
                                <option>Cubic inch</option>
                                <option>Quart</option>
                                <option>Drop</option>
                                <option>Gallon</option>
                                <option>Dash</option>
                                <option>Fluid ounce</option>
                                <option>Pint</option>
                                <option>Pinch</option>
                                <option>Cup</option>
                                <option>Teaspoon</option>
                                <option>Tablespoon</option>
                                <option>Dessert spoon</option>
                                <option>Cube</option>
                                <option>Box</option>
                                <option>Piece</option>
                                <option>Slice</option>
                                <option>Roll</option>
                                <option>Round</option>
                                <option>Loaf</option>
                                <option>Serving</option>
                                <option>Stick</option> --}}
                                </optgroup>
                            </select>
                            <span class="bs-caret cr"><span class="caret"></span></span>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <a href="javascript:void(0)" class="more updateRecipe" data-id="{{ $i }}">Update</a>
                    </div>
                </div>
            </div>
        </div>
        </div>

        @php
            $i++;
        @endphp
    @endforeach

@endforeach
{{-- end loop --}}
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
{{-- </div> --}}

<script type="text/javascript">
    $(document).ready(function() {
        var i = 0;
        var j = 0;
        $('.list').each(function() {

            var ingr = $('.line-text-' + j).html();
            var hints = 'cut-hits';
            //  console.log('ingr==', ingr , hints);
            $.post(public_url + 'meal-planner/single-ingredients', {
                ingr: ingr,
                hints: hints
            }, function(data) {
                console.log('data==', data);
                var list_data = data.hints;
                var selected_unit ='';
                if(data.parsed[0].measure){
                    var selected_unit = data.parsed[0].measure.label;
                }
                //  console.log('selected_unit==', selected_unit);
                // console.log(' list_data', list_data);
                $('.food-match-' + i).html('');
                $.each(list_data, function(key, value) {
                    // console.log('pinki', key, value);
                    $('.food-match-' + i).append(`<option value="${value.label}" data-label="${value.foodId}" data-measureuris="${value.measureURIs}" data-measures="${value.measures}">
                                       ${value.label}
                                  </option>`);
                    // $('.food-match-'+ i).append(`<option value="${value.foodId}" data-label="${value.foodId}" data-measureuris="${value.measureURIs}" data-measures="${value.measures}">
                    //                        ${value.label}
                    //                   </option>`);

                    var measureURI = value.measureURIs;
                    var measures = value.measures;
                    if (key == 0) {
                        $('.food-measure-' + i).html('');
                        $.each(measureURI, function(index, el) {
                            // <option selected="selected" data-hints="food_at0h9hean2v4bobxbjzvgbhm51h7" data-measureuri="http://www.edamam.com/ontologies/edamam.owl#Measure_kilogram" value="Kilogram">Kilogram</option>
                            if (measures[index] == selected_unit) {
                                $('.food-measure-' + i).append(`<option value="${ measures[index]}" data-hints="${value.foodId}" data-measureuri="${el}" selected>
                                       ${ measures[index]}
                                  </option>`);
                            } else {
                                $('.food-measure-' + i).append(`<option value="${ measures[index]}" data-hints="${value.foodId}" data-measureuri="${el}">
                                       ${ measures[index]}
                                  </option>`);
                            }


                        });
                    }
                });
                /* end first loop */
                i++;
            });
            j++;
        });


        $(document).on('change', '.food-match-select', function() {
            var id = $(this).attr('data-id');
            var data_hints = $('.food-match-select').find(":selected").val();
            var measure_uri = $('.food-match-select').find(":selected").attr('data-measureuris');
            var measure_uri_array = measure_uri.split(",");
            var measure = $('.food-match-select').find(":selected").attr('data-measures');
            var measure_array = measure.split(",");
            // console.log(measure_uri, measure, measure_uri_array, measure_array);
            $('.food-measure-' + id).html('');
            $.each(measure_uri_array, function(index, el) {
                // console.log('el==', el);
                // console.log('el123==', measure_array[index]);
                $('.food-measure-' + id).append(`<option value="${ measure_array[index]}" data-hints="${data_hints}" data-measureuri="${el}">
                                    ${ measure_array[index]}
                         </option>`);

            });
            //    console.log(id, data_hints);
        })

        /* end  */
    });
</script>
