@extends('blank')

@section('required-styles') 
    {!! Html::style('assets/plugins/Jcrop/css/jquery.Jcrop.min.css') !!}
    {!! Html::style('assets/js/custom-plugin/multiple-in-textbox.css') !!}
    {!! Html::style('assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') !!}
@stop

@section('page-title')
<span >Update Recipe</span> 
@stop

@section('content')
@include('includes.partials.pic_crop_model',['source' => 'meal'])

<div id="panel_edit_account" class="tab-pane active">
    <div class="alert alert-success" style="display:none;" id="suc_msg"></div>                
    <div class="row swMain">
        @include('mealplanner.meal.form')
    </div>
</div>

@include('includes.partials.add_more_modal');
@endsection

@section('script')
{!! Html::script('assets/js/validator-helper.js?v='.time()) !!}
{!! Html::script('assets/plugins/Jcrop/js/jquery.Jcrop.min.js') !!}
{!! Html::script('assets/plugins/Jcrop/js/script.js') !!}
{{-- {!! Html::script('assets/plugins/ckeditor/ckeditor.js') !!} --}}
{{-- {!! Html::script('assets/plugins/ckeditor/adapters/jquery.js?') !!} --}}
{!! Html::script('assets/plugins/bootstrap3-typeahead.min.js') !!}
{!! Html::script('assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js') !!}
{!! Html::script('assets/js/custom-plugin/multiple-in-textbox.js') !!}

<script>
    var nutrationalData = $('textarea#tool-name').val();
</script>

{!! Html::script('assets/js/helper.js?v='.time()) !!}
{!! Html::script('assets/js/meal-tools.js?v='.time()) !!}
{!! Html::script('assets/js/meal-planner.js?v='.time()) !!}
{!! Html::script('assets/js/meal-planner-helper.js?v='.time()) !!}

<script>
    $(document).ready(function(){
        if($(window).width() <= 800) {
            $("html, body").animate({ scrollTop: 0 }, "slow");
        };
        let format_type = $('.reipe_format').val();
        if(format_type == 1){
           $('.analyze_data').trigger('click');
         } else{
            $('.analyze_data').trigger('click');
            $('.analyze_data-2').trigger('click');
         }
         console.log(format_type);
        switch(format_type) {
            case '1': 
               $('.recipes-form__style-label.simple').trigger('click');
                break;
            case '2': 
              $('.recipes-form__style-label.two-part-i').trigger('click');
                break;
            case '3':
               $('.recipes-form__style-label.two-part-r').trigger('click');
                break;
            default:
                $('.recipes-form__style-label.simple').trigger('click');
         }
    })
    // CKEDITOR.replace('description', {
    //     height: 120
    // });
    // CKEDITOR.replace('method', {
    //     height: 120
    // });
    // CKEDITOR.replace('tips', {
    //     height: 120
    // });

    // CKEDITOR.replace('ingredients', {
    //     height: 120
    // });

    // $(document).ready(function(){
    //    CKEDITOR.env.isCompatible = true;
    // });

    // var timer = setInterval(updateTextArea, 1000);
    // function updateTextArea(){
    //     $('#description').html(CKEDITOR.instances.description.getData());
    //     $('#method').html(CKEDITOR.instances.method.getData());
    //     $('#tips').html(CKEDITOR.instances.tips.getData());
    //     $('#ingredients').html(CKEDITOR.instances.ingredients.getData());
    // }

    // var str = CKEDITOR.instances.ingredients.getData();
    // $('#countIngredients').text(str.length);
    // CKEDITOR.instances.ingredients.on('change', function() {
    //     var str = CKEDITOR.instances.ingredients.getData();
    //     $('#countIngredients').text(str.length);
    // });

    // var str = CKEDITOR.instances.description.getData();
    // $('#countDescription').text(str.length);
    // CKEDITOR.instances.description.on('change', function() {
    //     var str = CKEDITOR.instances.description.getData();
    //     $('#countDescription').text(str.length);
    // });

    // var str = CKEDITOR.instances.method.getData();
    // $('#countMethod').text(str.length);
    // CKEDITOR.instances.method.on('change', function() {
    //     var str = CKEDITOR.instances.method.getData();
    //     $('#countMethod').text(str.length);
    // });

    // var str = CKEDITOR.instances.tips.getData();
    // $('#countTips').text(str.length);
    // CKEDITOR.instances.tips.on('change', function() {
    //     var str = CKEDITOR.instances.tips.getData();
    //     $('#countTips').text(str.length);
    // });
</script>
@stop