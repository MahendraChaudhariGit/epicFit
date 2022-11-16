@extends('blank')

@section('required-styles')
{!! Html::style('assets/plugins/Jcrop/css/jquery.Jcrop.min.css') !!}
<!-- link your Style sheet here --> 
@stop

@section('page-title')
<span >Update Ingredient</span> 
@stop

@section('content')
<div id="panel_edit_account" class="tab-pane active">
    <div class="alert alert-success" style="display:none;" id="suc_msg"></div>                
    <div class="row swMain">
        @include('mealplanner.food.form')
    </div>
</div>
@include('includes.partials.pic_crop_model')
@include('includes.partials.add_serving_size');

@endsection

@section('script')
{!! Html::script('assets/js/validator-helper.js?v='.time()) !!}
{!! Html::script('assets/plugins/bootstrap3-typeahead.min.js?v='.time()) !!}
{!! Html::script('assets/plugins/Jcrop/js/jquery.Jcrop.min.js?v='.time()) !!}
{!! Html::script('assets/plugins/Jcrop/js/script.js?v='.time()) !!}
{!! Html::script('assets/js/helper.js?v='.time()) !!}

{!! Html::script('assets/js/food-planner.js?v='.time()) !!}
{!! Html::script('assets/js/meal-planner-helper.js?v='.time()) !!}
{!! Html::script('assets/js/add-serving-size.js?v='.time()) !!}
@stop