@extends('blank')
@section('required-styles')
    
@stop
 
@section('page-title')
    
@stop

@section('content')
<div class="breakfast_view">
    <h1>{{strtoupper($mpMeals->name)}}</h1>
    @php
    $mealImg = $mpMeals->mealimages()->pluck('mmi_img_name')->first();
    @endphp
    <img src="{{ dpSrc($mealImg) }}" class="mainimg">
    <div class="description_section">
        <ul>
            <li>
                <div class="icon">
                  <img src="{{asset('assets/images/discription-icon.png')}}">
                </div>
                <div class="right_hd">
                    <h3>Description</h3>
                </div>
            </li>
            <li>
                <div class="icon">
                    <img src="{{asset('assets/images/time-icon.png')}}">
                </div>
                <div class="right_hd">
                    <h4>Preparation Time</h4><br>
                    <span>{{$mpMeals->time}} Minutes</span>
                </div>
            </li>
            <li>
                <div class="icon">
                  <img src="{{asset('assets/images/serving-icon.png')}}">
                </div>
                <div class="right_hd">
                    <h4>Serving Size</h4><br>
                    <span>{{$mpMeals->serves}}</span>
                </div>
            </li>
        </ul>
        <div class="description_data">
            {!! $mpMeals->description !!}
        </div>
    </div>
    <div class="bottom_data">
        <div class="prepation_box">
            <h2><img src="{{asset('assets/images/preparation-icon.png')}}"> Preparation</h2>
            <ol>
                {!! $mpMeals->method !!}
            </ol>
        </div>
        <div class="prepation_box">
            <h2><img src="{{asset('assets/images/ingrediant-icon.png')}}"> Ingredients</h2>
            <ul>
                {!! $mpMeals->ingredients !!}
            </ul>
        </div>
        @php
        $nutritionalInfo = json_decode($mpMeals->nutritional_information);
        @endphp
        <div class="bootom_area">
            <h3><span>Calories:</span> {{$nutritionalInfo->energ_kcal}}</h3>
            <p><span>FAT</span>: {{$nutritionalInfo->fat}}g<span>SATURATED FAT</span>: {{$nutritionalInfo->fa_sat}}g<span>Sugar</span>: {{$nutritionalInfo->sugar}}g<span>Sodium</span>: {{$nutritionalInfo->sodium}}g<span>Fiber</span>: {{$nutritionalInfo->fiber}}g<span>Protein</span>: {{$nutritionalInfo->protein}}g<span>Cholesterol</span>: {{$nutritionalInfo->cholesterol}}g</p>
        </div>
    </div>
</div>

@stop
@section('script')

@stop
