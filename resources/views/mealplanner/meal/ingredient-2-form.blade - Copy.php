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

        <div class="col-md-12 list list-2 list-{{ $i }}" data-id="{{ $i }}">
            <div class="row">
                <div class="col-md-10 col-xs-8 line">
                    @if ($text == null)
                        <span class="line-text-2-{{ $i }}">{{ $final_val['quantity'] ?? 0 }}
                            {{ $final_val['measure'] ?? '' }} {{ $final_val['foodMatch'] ?? '' }}</span>
                    @else
                        <span class="line-text-2-{{ $i }}">{{ $text }}</span>
                    @endif

                    <span class="err">

                    </span>
                </div>

                <div class="col-md-2 col-xs-4">
                    <span class="pull-right delete-ingr-2" data-id="{{ $i }}"></span>
                    <span class="pull-right edit-ingr-2" data-id="{{ $i }}"></span>
                </div>
            </div>
            <div class="line-ingr">
                <div class="col-md-12">
                    {{-- <div class="row edit-ing"> --}}
                    <div class="row edit-ing-2-{{ $i }}" style="display: none;">

                        <div class="form-group">
                            @if ($text == null)

                                <input type="text"
                                    data-old="{{ $final_val['quantity'] ?? 0 }} {{ $final_val['measure'] ?? '' }} {{ $final_val['foodMatch'] ?? '' }}"
                                    value="{{ $final_val['quantity'] ?? 0 }} {{ $final_val['measure'] ?? '' }} {{ $final_val['foodMatch'] ?? '' }}"
                                    class="form-control title-sel full-name-2-{{ $i }}" autocomplete="off">
                            @else

                                <input type="text" data-old="{{ $text ?? '' }}" value="{{ $text ?? '' }}"
                                    class="form-control title-sel full-name-2-{{ $i }}" autocomplete="off">
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row edit-ing-2-{{ $i }}" style="display: none;">

                    <div class="col-md-12 text-center">
                        <a href="javascript:void(0)" class="more updateRecipeIngr-2"
                            data-id="{{ $i }}">Update
                            Ingredient</a>
                        <a href="javascript:void(0)" class="more edit-ingr-btn-2"
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
                            <select class="form-control food-match-2-{{ $i }} food-match-select-2"
                                data-id="{{ $i }}">
                                <optgroup>
                                    <option>{{ $final_val['foodMatch'] ?? '' }}</option>

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
                                class="form-control quantity-sel food-qty-2-{{ $i }}">
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="form-group bg-white">
                            <select class="form-control food-measure-2-{{ $i }}">
                                <optgroup>
                                    <option>{{ $final_val['measure'] ?? '' }}</option>

                                </optgroup>
                            </select>
                            <span class="bs-caret cr"><span class="caret"></span></span>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <a href="javascript:void(0)" class="more updateRecipe-2"
                            data-id="{{ $i }}">Update</a>
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


<script type="text/javascript">
    $(document).ready(function() {
        var i = 0;
        var j = 0;
        $('.list-2').each(function() {

            var ingr = $('.line-text-2-' + j).html();
            var hints = 'cut-hits';
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
                
                $('.food-match-2-' + i).html('');
                $.each(list_data, function(key, value) {
                    $('.food-match-2-' + i).append(`<option value="${value.label}" data-label="${value.foodId}" data-measureuris="${value.measureURIs}" data-measures="${value.measures}">
                                       ${value.label}
                                  </option>`);


                    var measureURI = value.measureURIs;
                    var measures = value.measures;
                    if (key == 0) {
                        $('.food-measure-2-' + i).html('');
                        $.each(measureURI, function(index, el) {
                            if (measures[index] == selected_unit) {
                                $('.food-measure-2-' + i).append(`<option value="${ measures[index]}" data-hints="${value.foodId}" data-measureuri="${el}" selected>
                                       ${ measures[index]}
                                  </option>`);
                            } else {
                                $('.food-measure-2-' + i).append(`<option value="${ measures[index]}" data-hints="${value.foodId}" data-measureuri="${el}">
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


        $(document).on('change', '.food-match-select-2', function() {
            var id = $(this).attr('data-id');
            var data_hints = $('.food-match-select-2').find(":selected").val();
            var measure_uri = $('.food-match-select-2').find(":selected").attr('data-measureuris');
            var measure_uri_array = measure_uri.split(",");
            var measure = $('.food-match-select-2').find(":selected").attr('data-measures');
            var measure_array = measure.split(",");

            $('.food-measure-2-' + id).html('');
            $.each(measure_uri_array, function(index, el) {

                $('.food-measure-2-' + id).append(`<option value="${ measure_array[index]}" data-hints="${data_hints}" data-measureuri="${el}">
                                    ${ measure_array[index]}
                         </option>`);

            });

        })

        /* end  */
    });
</script>
