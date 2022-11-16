@foreach ($final_ingr_val as $i => $final_val)
<div class="col-md-12 list list-2 list-{{ $i }}" data-id="{{ $i }}">
    <div class="row">
        <div class="col-md-10 col-xs-8 line">
            <span class="line-text-2-{{ $i }}">{{ $final_val[text] }}</span>
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
            <div class="row edit-ing-2-{{ $i }}" style="display: none;">

                <div class="form-group">
                    <input type="text" data-old="{{ $final_val[text] ?? '' }}" value="{{ $final_val[text] ?? '' }}"
                         class="form-control title-sel full-name-2-{{ $i }}" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="row edit-ing-2-{{ $i }}" style="display: none;">

            <div class="col-md-12 text-center">
                <a href="javascript:void(0)" class="more updateRecipeIngr-2" data-id="{{ $i }}">Update
                    Ingredient</a>
                <a href="javascript:void(0)" class="more edit-ingr-btn-2"
                    data-id="{{ $i }}">Cancel</a>
                    <hr style="border-color: #bfc1c3;">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group bg-white">
                   
                    <select class="form-control food-match-2-{{ $i }} food-match-select-2"
                        data-id="{{ $i }}">
                        <optgroup>                      
                           @foreach($final_val['hints'] as $value)
                           @php
                               $measureURIs = implode(", ",$value['measureURIs']) ;  
                               $measures = implode(", ",$value['measures']) ;  
                           @endphp
                   
                            <option value="{{ $value['label']}}" data-label="{{$value['foodId']}}" data-measureuris="{{$measureURIs}}" data-measures="{{$measures}}">
                               {{$value['label']}}
                            </option>
                           @endforeach
                        </optgroup>
                    </select>
                    <span class="bs-caret cr food-match-dropdown"><span class="caret"></span></span>
                </div>

            </div>
        </div>

        @php
           $new_val = round($final_val['parsed'][0]['quantity'], 2);                                   
       @endphp
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <input type="text" value="{{ $new_val ?? '' }}"
                        class="form-control quantity-sel food-qty-2-{{ $i }}">
                </div>
            </div>

            <div class="col-md-8">
                <div class="form-group bg-white">
                    <select class="form-control food-measure-2-{{ $i }}">
                        <optgroup>
                            @foreach($final_val['hints'][0]['measures'] as $key => $value)
                                @if($value == $final_val['parsed'][0]['measure']['label'])
                                    <option value="{{ $value }}" data-label="{{$final_val['hints'][0]['foodId']}}" data-measureuris="{{ $final_val['hints'][0]['measureURIs'][$key] }}" data-measures="{{ $value }}" selected>
                                    {{ $value }}
                                    </option>     
                                @else
                                    <option value="{{ $value }}" data-label="{{$final_val['hints'][0]['foodId']}}" data-measureuris="{{ $final_val['hints'][0]['measureURIs'][$key] }}" data-measures="{{ $value }}">
                                        {{ $value }}
                                    </option>   
                                @endif
                           @endforeach
                        </optgroup>
                    </select>
                    <span class="bs-caret cr"><span class="caret"></span></span>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <a href="javascript:void(0)" class="more updateRecipe-2" data-id="{{ $i }}">Update</a>
            </div>
        </div>
    </div>
</div>
</div>

@endforeach
<script type="text/javascript">
$(document).ready(function() {
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
