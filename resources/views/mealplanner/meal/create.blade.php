@extends('blank')

@section('required-styles') 
    {!! Html::style('assets/plugins/Jcrop/css/jquery.Jcrop.min.css') !!}
    {!! Html::style('assets/js/custom-plugin/multiple-in-textbox.css') !!}
@stop

@section('page-title')
<span >Add Recipe</span> 
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
{{-- {!! Html::script('assets/plugins/ckeditor/ckeditor.js') !!}
{!! Html::script('assets/plugins/ckeditor/adapters/jquery.js') !!} --}}
{!! Html::script('assets/plugins/bootstrap3-typeahead.min.js') !!}
{!! Html::script('assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js') !!}
{!! Html::script('assets/js/custom-plugin/multiple-in-textbox.js') !!}

<script>
    var nutrationalData = '';
</script>
{!! Html::script('assets/js/helper.js?v='.time()) !!}
{!! Html::script('assets/js/meal-tools.js?v='.time()) !!}
{!! Html::script('assets/js/meal-planner-helper.js?v='.time()) !!}
<script>
    $(document).ready(function(){
        $('.recipes-form__style-label.simple').addClass('active');
        // $('.meallist').css('display','none')
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
    
    // CKEDITOR.instances.ingredients.on('change', function() {
    //     var str = CKEDITOR.instances.ingredients.getData();
    //     $('#countIngredients').text(str.length);
    // });

    // CKEDITOR.instances.description.on('change', function() {
    //     var str = CKEDITOR.instances.description.getData();
    //     $('#countDescription').text(str.length);
    // });

    // CKEDITOR.instances.method.on('change', function() {
    //     var str = CKEDITOR.instances.method.getData();
    //     $('#countMethod').text(str.length);
    // });

    // CKEDITOR.instances.tips.on('change', function() {
    //     var str = CKEDITOR.instances.tips.getData();
    //     $('#countTips').text(str.length);
    // });
</script>
{!! Html::script('assets/js/meal-planner.js?v='.time()) !!}

@stop