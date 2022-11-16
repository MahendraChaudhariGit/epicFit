@extends('Result.masters.app')
@section('page-title')
<!-- <span >Recipes</span>  -->
<style type="text/css">
#spaceless2{
	display: none;
}
.main-content{
	background: white;
}
html{
    scroll-behavior: smooth;
}
.bootstrap-select.btn-group .dropdown-toggle .filter-option{
    color: #8e8e93;
    font-size: 12px;
    font-weight: 500;
}
.btn.dropdown-toggle.btn-default{
    background: white !important;
}
.btn.dropdown-toggle.btn-default{
    border: 0px;
    width: 95px;
    font-weight: 600;
}
.bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn){
    width: 89px;
}
.bootstrap-select.btn-group .dropdown-menu li a{
    font-size: 12px;
}
#ReviewsPanel .btn--link{
    display: block !important;
}
.navbar{
    z-index: 999999999;
}

.dropdown-toggle,.btn.dropdown-toggle.btn-default{
    outline: 0px !important;
}
.class-name-text {
  white-space: pre-wrap;
}
</style>
@stop
@section('required-styles')
{!! Html::style('result/css/custom.css?v=' . time()) !!}
{!! Html::style('result/plugins/bootstrap-select-master/css/bootstrap-select.min.css?v=' . time()) !!}


@stop
@section('content')
<div class="recipe">
    <nav class="breadcrumbs" aria-label="breadcrumb">
        <ul class="p-0">
            <li><a href="{{ route('recipes.list') }}">Recipes</a></li>
            <li><a>{{ $meal_detail->name }}</a></li>
            {{-- <li><a href="#">Dinner</a></li>
            <li><a href="#">Souffle</a></li> --}}
        </ul>
    </nav>
</div>
<section class="recipes-section recipe">
    <div class="content__container">


        <header class="recipe__header">
            <h3 class="recipe__icon-br">
                <a href="#" class="tag">
                    Bake
                </a>
            </h3>
            <input hidden class="recipe_id" value="{{$meal_detail->id}}">
            <h1 class="recipe__title" data-val="{{$meal_detail->name}}">
                {{ $meal_detail->name }}
                {{-- Pimento Cheese&nbsp;Soufflé --}}
            </h1>

            <div class="meta">


                <div class="meta__date">
                    <span>{{ $meal_detail->created_at->format('F j, Y') }}</span>
                    {{-- <span>{{ $meal_detail->created_at->format('l jS \\of F Y ') }}</span> --}}
                </div>

                <div class="recipe__meta-reviews">
                    <div id="recipeRateRoot" class="recipe__meta-rating">
                        <div class="rating rating--btns rating--btns-large" role="radiogroup" aria-labelledby="ratingLabel">
                          <span class="rating-label"></span>
                          {{-- {{dd($totalAvgRating)}} --}}
                          @php
                             $stars = [
                                "1" => "Needs a do-over.",
                                "2" => "Not feeling it.",
                                "3" => "Meh...",
                                "4" => "I'd have seconds!",
                                "5" => "Love Love Love",
                            ];
                            // dd( $stars[1]); 
                          @endphp
                          @for($i=5;$i>= 1;$i--)
                            <input type="radio" name="rating"  id="rating_btn_comment{{$i}}" value="{{$i}}">
                            <label for="rating_btn_comment{{$i}}" data-val="{{$i}}" aria-label="{{$i}} Stars:{{$stars[$i]}}" class="rating__star rating__star--btns submitStarRating @if($totalAvgRating > 0)@if($i <= $totalAvgRating)showfullStar @elseif($i >$totalAvgRating && ($i-1) <$totalAvgRating )showHalfStar @endif @endif">
                                <a data-toggle="tooltip" data-placement="bottom" title="{{$i}} Stars:{{$stars[$i]}}" style="color:inherit;">
                                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 23.8">
                                        <path fill="currentColor" d="M12.5 0l2.9 9.1H25l-7.7 5.6 2.9 9.1-7.7-5.7-7.7 5.7 2.9-9.1L0 9.1h9.6z"></path>
                                    </svg>
                                </a>
                             </label>
                            @endfor

                          {{-- <input type="radio" name="rating" tabindex="-1" id="rating_btn_comment5" value="5">
                          <label for="rating_btn_comment5" class="rating__star rating__star--btns" aria-label="5 Stars: Love Love Love." data-toggle="modal" data-target="#rating-popupshow">
                            <a data-toggle="tooltip" data-placement="bottom" title="5 Stars: Love Love Love." style="color:inherit;">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 23.8"><path fill="currentColor" d="M12.5 0l2.9 9.1H25l-7.7 5.6 2.9 9.1-7.7-5.7-7.7 5.7 2.9-9.1L0 9.1h9.6z"></path></svg>
                        </a>
                        </label>

                        <input type="radio" name="rating" tabindex="-1" id="rating_btn_comment4" value="4">
                        <label for="rating_btn_comment4" class="rating__star rating__star--btns" aria-label="4 Stars: I'd have seconds!" data-toggle="modal" data-target="#rating-popupshow">
                              <a data-toggle="tooltip" data-placement="bottom" title="4 Stars: I'd have seconds!" style="color:inherit;">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 23.8"><path fill="currentColor" d="M12.5 0l2.9 9.1H25l-7.7 5.6 2.9 9.1-7.7-5.7-7.7 5.7 2.9-9.1L0 9.1h9.6z"></path></svg>
                        </a>
                        </label>

                        <input type="radio" name="rating" tabindex="-1" id="rating_btn_comment3" value="3">
                        <label for="rating_btn_comment3" class="rating__star rating__star--btns" aria-label="3 Stars: Meh..." data-toggle="modal" data-target="#rating-popupshow">
                              <a data-toggle="tooltip" data-placement="bottom" title="3 Stars: Meh..." style="color:inherit;">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 23.8"><path fill="currentColor" d="M12.5 0l2.9 9.1H25l-7.7 5.6 2.9 9.1-7.7-5.7-7.7 5.7 2.9-9.1L0 9.1h9.6z"></path></svg>
                        </a>
                        </label>

                        <input type="radio" name="rating" tabindex="-1" id="rating_btn_comment2" value="2">
                        <label for="rating_btn_comment2" class="rating__star rating__star--btns" aria-label="2 Stars: Not feeling it." data-toggle="modal" data-target="#rating-popupshow">

                              <a data-toggle="tooltip" data-placement="bottom" title="2 Stars: Not feeling it." style="color:inherit;">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 23.8"><path fill="currentColor" d="M12.5 0l2.9 9.1H25l-7.7 5.6 2.9 9.1-7.7-5.7-7.7 5.7 2.9-9.1L0 9.1h9.6z"></path></svg>
                        </a>
                        </label> --}}
{{-- 
                        <input type="radio" name="rating" tabindex="0" id="rating_btn_comment1" value="1">
                        <label for="rating_btn_comment1" class="rating__star rating__star--btns" aria-label="1 Star: Needs a do-over." data-toggle="modal" data-target="#rating-popupshow">
                              <a data-toggle="tooltip" data-placement="bottom" title="1 Star: Needs a do-over." style="color:inherit;">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 23.8"><path fill="currentColor" d="M12.5 0l2.9 9.1H25l-7.7 5.6 2.9 9.1-7.7-5.7-7.7 5.7 2.9-9.1L0 9.1h9.6z"></path></svg>
                        </a>
                        </label> --}}

                    </div>
                    <span class="recipe__rating-label">
                        <span class="sr-only">0 out of 5 stars / </span>
                       <span class="star-rating"> {{$meal_detail->getRatingCount()}} Ratings </span>
                    </span>
            </div>

            <div class="meta__comments">
                <a href="#ReviewsPanel" style="color: #909090;">
                    <span class="js-comments-count totalReview" data-old="{{$totalReview}}">{{$totalReview}}</span>&nbsp;Reviews
                </a>
            </div>
        </div>
</div>

<div class="user-actions">


    <ul class="social social--outline">
        <li>
            <a aria-label="Share on Facebook" href="https://www.facebook.com/" target="_blank"
            class="social__link social__link--facebook">
            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10.4 19.3">
                <path
                d="M0 10.3h3v9h3.7v-9h3.7V6.6H6.7V4.8c0-.6.4-1.1.7-1.1h3V0h-3C5 0 3 2.2 3 4.8v1.8H0v3.7z"
                fill="currentColor">

            </path>
        </svg>

    </a>
</li>

<li>
    <a aria-label="Share on Twitter" href="#" target="_blank"
    class="social__link social__link--twitter">
    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 19.8 15.8">
        <path
        d="M19.8 1.9c-.4.1-.8.3-1.1.4l-1.2.3c.4-.3.8-.6 1.1-.9.3-.4.6-.8.7-1.2-.4.2-.9.4-1.3.6-.4.2-.9.3-1.4.4-.3-.4-.8-.7-1.3-.9-.4-.5-1-.6-1.6-.6-1.1 0-2.1.4-2.9 1.2-.7.8-1.1 1.7-1.1 2.8 0 .2 0 .5.1.9-1.7-.1-3.3-.5-4.7-1.2C3.6 3 2.4 1.9 1.4.7 1 1.3.9 2 .9 2.7c0 1.5.6 2.6 1.8 3.3-.8 0-1.4-.1-1.9-.4v.1c0 1 .3 1.8.9 2.5.6.7 1.4 1.2 2.3 1.4-.2 0-.3.1-.5.1h-.6c-.2 0-.4 0-.7-.1.2.8.7 1.4 1.4 2 .7.4 1.5.7 2.4.7C4.6 13.5 2.9 14 1 14H0c.9.6 1.8 1 2.9 1.3 1.1.3 2.2.5 3.3.5 1.9 0 3.5-.3 5-1 1.4-.7 2.7-1.6 3.6-2.7 1-1.1 1.7-2.3 2.2-3.6.5-1.3.7-2.7.7-4V4c.4-.3.8-.6 1.1-1 .4-.3.8-.7 1-1.1"
        fill="currentColor">

    </path>
</svg>

</a>
</li>

<li>
    <a href="#" target="_blank" class="social__link social__link--pinterest">
        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16.9 20.8">
            <path
            d="M16.8 6.3C16.5 3.2 13.6.4 10.1.1 4.8-.5.1 2.7 0 7.9c0 2.4.8 4 2.9 4.8 1.5-1.5-.3-2.4-.5-4C2 4.3 7.1.1 11.7 2.9c3.2 1.9 2.6 9.9-1.2 10.7-.8.1-1.8-.1-2.1-.5-1.9-1.8 1.5-4.8.4-7.3-.9-2.1-3.6-.4-3.9 1.5-.2 1 .3 2 .3 2.9 0 2.1-1.3 5-1.7 7.1-.2.9-.3 2.6-.1 3.4h1.5c1-1.6 1.6-4.1 2.1-6.3.3-.2.5.4.7.6 1.8 1.4 5 .6 6.4-.7 2.1-1.7 3-5.2 2.7-8"
            fill="currentColor">

        </path>
    </svg>
</a>
</li>

<li>
    <a aria-label="Email to a friend" href="#" target="_blank"
    class="social__link social__link--email">
    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 17.1 12.5">
        <path
        d="M5.9 6.4L.6 12.5h16l-5.3-6.1-2.5 2.4c-.1.2-.3.2-.5 0L5.9 6.4zM17.1.6l-5.3 5.2 5.3 6.1V.6zM0 .6v11.3l5.3-6.1L0 .6zM.6 0l7.9 7.8 8-7.8H.6z"
        fill="currentColor">

    </path>
</svg>
</a>
</li>
</ul>

</div>
</header>

<div class="content__row">
    <div class="content__main">
        <div id="recipeCarouselRoot" class="img img--mobile-full recipe__hero-container   ">
            <div class="img">

                <img src="{{ dpSrc($meal_detail->mealimages()->pluck('mmi_img_name')->first())}}"
                class="img-responsive img-show">
                {{-- <img src="{{asset('assets/images/bake.webp')}}"> --}}
            </div>
        </div>




        <div class="content__text-container">
            <div class="user-actions--hero">

                <div class="user-actions__btns">


                    <a class="btn--pill js-comments-scroll" href="#ReviewsPanel">
                        <span class="icon icon__comments icon__comments--hover-fill"></span>Review
                    </a>

                    <a class="btn--pill recipe__print mealDiv" title="Print this recipe"
                    data-id="{{ $meal_detail->id }}" data-type="Meal">
                    <span class="recipe__print-icon"></span>
                    Print
                </a>
            </div>



        </div>

        <div class="recipe__text js-subnav-content">

            <div class="recipe__notes">
                <div id="recipeJumpRoot"></div>

                <h5 class="recipe__list-heading">Description</h5>
                <p>{!! $meal_detail->description !!}</p>
                {{-- <p>Here I French-ify an American classic, by transforming perky pimento cheese into a fluffy soufflé. A cheese soufflé is just a thick cheesy base, lightened and leavened with whipped egg whites. It starts with a béchamel, a milk sauce thickened with flour and butter. Then we load it up with cheddar, finely chopped pimentos, chives, and paprika. Keep your mix-ins small so they don’t interfere with how this soufflé rises. Serve this with crackers or toasted white bread and a crisp salad. Or try my <a href="#" rel="nofollow" target="_blank">French Onion Soup-fflé</a>! And find all of my tips and tricks <a href="#" rel="nofollow" target="_blank">right here</a>, so you can take soufflés off-script.  —<a href="#">Sohla El-Waylly</a></p> --}}

            </div>
            @php
            $prep_time = json_decode( $meal_detail->time);
            $cook_time = json_decode( $meal_detail->cook_time);
            @endphp
            <ul class="recipe__details">
                <li>
                    <span class="recipe__details-heading">Prep time</span>
                    @if(isset($prep_time->prep_hrs) && $prep_time->prep_hrs)
                    {{$prep_time->prep_hrs ?? ''}} hour 
                    @endif
                    @if(isset($prep_time->prep_mins) && $prep_time->prep_mins)
                    {{$prep_time->prep_mins ?? ''}} minutes 
                    @endif
                    {{-- {{ $meal_detail->time != null ? $meal_detail->time : 0 }} minutes --}}

                </li>

                <li>
                    <span class="recipe__details-heading">Cook time</span>
                    @if(isset($cook_time->cook_hrs) && $cook_time->cook_hrs)
                    {{$cook_time->cook_hrs ?? ''}} hour 
                    @endif
                    @if(isset($cook_time->cook_mins) && $cook_time->cook_mins)
                    {{$cook_time->cook_mins ?? ''}} minutes 
                    @endif
                    {{-- {{ $meal_detail->cook_time != null ? $meal_detail->cook_time : 0 }} minutes --}}
                </li>

                <li>
                    <span class="recipe__details-heading">Serves</span>
                    {{ $meal_detail->serves }}
                </li>
            </ul>
            <div class="recipe__list recipe__list--ingredients">
                <div class="row">
                    <div class="col-md-7 col-xs-12">
                     <h5 class="recipe__list-heading" style="border:0;">Ingredients </h5>

                 </div>

                 <div class="col-md-5 col-xs-12 text-right text-mob-center">

                    <div class="ingredient-update-number">
                        <span class="minus">-</span>
                        <input type="text" data-old="{{$meal_detail->serves}}" disabled class="ppl-changes" value="{{ $meal_detail->serves }}"/>
                        <span class="plus">+</span>
                    </div> 
                    <input type="text" class="adjust-value" data-old="{{$meal_detail->serves}}" value="{{ $meal_detail->serves }}" hidden/>
                    <button class="recipe-adjust-servings__adjust-button">Adjust</button>
                </div>
                <div class="col-xs-12">
                   <a class="link1 email-ingredient">Email Ingredients</a>
                   <hr class="mtw">

                   {{-- <a  class="link1 recipe-shopping-list-meal"><span class="add-recips">Add </span><span class="checked-count">all</span> Ingredients to shopping list</a> --}}
                   <a class="link1 recipe-shopping-list-meal"><span class="add-recips">Add </span><span class="checked-count">all</span> Ingredients to shopping list</a>
                   <div class="recipe-adjust-servings__original-serving">Original recipe yields {{ $meal_detail->serves }} servings</div>


               </div>
           </div>


           @php
           if($meal_detail->ingredient_set_no != 1){
            $set_name = json_decode($meal_detail->ingredient_set_name);
        }
        @endphp
        <!-- {!! $meal_detail->ingredients !!} -->
        <ul>
         @if(($meal_detail->ingredient_set_no != 1) && isset($set_name))
         <li class="recipe__list-subheading">{{$set_name->set_name_1}}:</li>
         @endif
         @if(count($meal_detail->mealIngredientSetPart1) > 0)
         @foreach($meal_detail->mealIngredientSetPart1 as $key => $ingr_val_1)

         {{-- <li><span class="recipe__list-qty">
            <span>@if($ingr_val_1->qty != 0){{$ingr_val_1->qty}}@endif</span> {{$ingr_val_1->measurement??''}}
        </span>
        {{$ingr_val_1->item??''}}<br>
    </li> --}}
    <li>
        <label for="recipe-ingredients-{{$key}}" class="checkbox-list checkbox-list1">
            <input class="checkbox-list-input" value="{{$ingr_val_1->id}}" type="checkbox" id="recipe-ingredients-{{$key}}"> 
            <span class="checkbox-list-checkmark" style="display: inline-block;">
                <span class="recipe__list-qty">
                    {{-- @php
                    if($ingr_val_1->qty != 0){
                        $number = $ingr_val_1->qty/$meal_detail->serves;
                        if (!is_int($number)) {
                            $number = floatval( $number);
                            $denominator = round(1 / $number);    
                            // $qty_one_person_val = "1/{$denominator}";
                            $qty_one_person_val =   1 . "/" .$denominator;                                                                  
                        }
                        else {                                                       
                            $qty_one_person_val =  $number;
                        }  
                    }
                    @endphp --}}

                    <span class="ingr1-qty-{{$key}}" data-id="@if($ingr_val_1->qty != 0){{$ingr_val_1->qty/$meal_detail->serves }}@endif">@if($ingr_val_1->qty != 0){{$ingr_val_1->qty}}@endif</span> {{$ingr_val_1->measurement??''}}
                </span>
                {{$ingr_val_1->item??''}}<br>
            </span>
        </label>
    </li> 
    @endforeach
    @endif 
    {{-- <li class="recipe__list-subheading">Pudding & Caramelized Bananas:</li> --}}
    {{-- <li>
        <label for="recipe-ingredients" class="checkbox-list">
            <input class="checkbox-list-input" type="checkbox" id="recipe-ingredients"> 
            <span class="checkbox-list-checkmark" style="display: inline-block;">
                <span class="recipe__list-qty">
                    <span>1</span> teaspoon
                </span>
                mayonnaise<br>
            </span>
        </label>
    </li> --}}

</ul>

<ul>
   @if(($meal_detail->ingredient_set_no != 1) && isset($set_name))
   <li class="recipe__list-subheading">{{$set_name->set_name_2}}:</li>
   @endif

   @if(count($meal_detail->mealIngredientSetPart2) > 0)
   @foreach($meal_detail->mealIngredientSetPart2 as $key2 => $ingr_val_2)
   {{-- <li><span class="recipe__list-qty">
    <span  class="ingr2-qty-{{$key}}" data-id="@if($ingr_val_2->qty != 0){{$ingr_val_2->qty/$meal_detail->serves }}@endif">@if($ingr_val_2->qty != 0){{$ingr_val_2->qty}}@endif</span> {{$ingr_val_2->measurement}}
</span>
{{$ingr_val_2->item}}<br>
</li> --}}
<li>
    <label for="recipe-ingredients-2-{{$key2}}" class="checkbox-list checkbox-list2">
        <input class="checkbox-list-input" value="{{$ingr_val_2->id}}" type="checkbox" id="recipe-ingredients-2-{{$key2}}"> 
        <span class="checkbox-list-checkmark" style="display: inline-block;">
            <span class="recipe__list-qty">    
                <span class="ingr2-qty-{{$key2}}" data-id="@if($ingr_val_2->qty != 0){{$ingr_val_2->qty/$meal_detail->serves }}@endif">@if($ingr_val_2->qty != 0){{$ingr_val_2->qty}}@endif</span> {{$ingr_val_2->measurement??''}}
            </span>
            {{$ingr_val_2->item??''}}<br>
        </span>
    </label>
</li>
@endforeach
@endif
{{-- <span class="ingr1-qty-{{$key}}" data-id="@if($ingr_val_1->qty != 0){{$ingr_val_1->qty/$meal_detail->serves }}@endif">@if($ingr_val_1->qty != 0){{$ingr_val_1->qty}}@endif</span> {{$ingr_val_1->measurement??''}} --}}

{{-- <li><span class="recipe__list-qty">
    <span>2</span> tablespoons
</span>
unsalted butter<br></li>
--}}
</ul>
<div class="recipe-shopping-list__container">
    {{-- @if(count($meal_detail->clientMealplan) > 0 )
    <button class="recipe-shopping-list__add-all-button recipe-shopping-list-meal not-added-meal-calendar">
        <span class="recipe-shopping-list__add-all-button-text"><span class="add-recips">Add </span><span class="checked-count">all</span> ingredients to shopping list</span>

        <span class="recipe-shopping-list__add-all-button-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                <path fill="#000" fill-opacity=".65" d="M15 1.833L13.825.658 8.542 5.942l1.175 1.175L15 1.833zM18.533.658L9.717 9.475 6.233 6 5.058 7.175l4.659 4.658 10-10L18.533.658zM.342 7.175L5 11.833l1.175-1.175L1.525 6 .342 7.175z">

                </path>
            </svg>
        </span>
    </button>    
    @else --}}
    <button class="recipe-shopping-list__add-all-button meal-calendar-btn" data-toggle="modal" data-target="#mealoptionmodal">
        <span class="recipe-shopping-list__add-all-button-text"><span>Add </span> recipe to meal calendar</span>

        <span class="recipe-shopping-list__add-all-button-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                <path fill="#000" fill-opacity=".65" d="M15 1.833L13.825.658 8.542 5.942l1.175 1.175L15 1.833zM18.533.658L9.717 9.475 6.233 6 5.058 7.175l4.659 4.658 10-10L18.533.658zM.342 7.175L5 11.833l1.175-1.175L1.525 6 .342 7.175z">

                </path>
            </svg>
        </span>
    </button> 
    {{-- @endif --}}

    <span class="recipe-shopping-list__add-all-success hidden">
       <span class="recipe-shopping-list__add-all-success-text"><span class="all-success-text">All </span> ingredients added</span>
       <span class="recipe-shopping-list__add-all-success-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
            <path fill="#000" fill-opacity=".65" d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm5 16H7v-2h10v2zm-6.7-4L7 10.7l1.4-1.4 1.9 1.9 5.3-5.3L17 7.3 10.3 14z"></path>
        </svg>
    </span>
</span>
<a href="#" class="recipe-shopping-list__view-items hidden">
    <button class="recipe-shopping-list__view-items-button">
        <span class="recipe-shopping-list__view-items-button-text view-shopping-list">View your list</span>
    </button>
</a>
</div>
</div>
<div id="recipeEditRoot" class="recipe__edit "></div>


<div class="recipe__list recipe__list--ingredients">
    <h5 class="recipe__list-heading">Preparation</h5>
    <!-- 	{!! $meal_detail->method !!} -->			
    <ol>
        @if(($meal_detail->ingredient_set_no == 3) && isset($set_name))
        <li class="recipe__list-subheading">{{$set_name->set_name_1}}</li>
        @endif
        @if(count($meal_detail->mealPreparationPart1) > 0)
        @foreach($meal_detail->mealPreparationPart1 as $key=> $prep_part1)
        <li class="recipe__list-step @if($key == 0)recipe__list-step--active @endif" tabindex="0">
            {{-- <li class="recipe__list-step" tabindex="0"> --}}
                <span>{{ $prep_part1['description'] }} </span>
            </li>
            @endforeach
            @endif
            {{-- <li class="recipe__list-subheading">Broccoli Rabe, Potato and Rosemary Pizza</li>
            <li class="recipe__list-step recipe__list-step--active " tabindex="0">
             <span>Make your own vanilla sugar. (2 cups sugar with 1 tsp vanilla extract or 1 scraped vanilla bean - mixed together thoroughly and stored in airtight container.)
             </span>
         </li>
         <li class="recipe__list-step  " tabindex="0">
             <span>Then add sugar, rum, and ice to a shaker. Shake vigorously and strain into coupe glass.
             </span>
         </li>
         --}}
     </ol>
     <ol>
        @if(($meal_detail->ingredient_set_no == 3) && isset($set_name))
        <li class="recipe__list-subheading">{{$set_name->set_name_2}}</li>
        @endif
        @if(count($meal_detail->mealPreparationPart2) > 0)
        @foreach($meal_detail->mealPreparationPart2 as $key=> $prep_part2)
        <li class="recipe__list-step" tabindex="0">
            <span>{{ $prep_part2['description'] }} </span>
        </li>
        @endforeach
        @endif

    </ol>
    <button type="button" class="btn--link css-1tgfdsd">
        <span aria-label="Open Ingredients" class="css-o9wkyt">Ingredients</span>
    </button>

</div>

</div>


<div class="recipe__list recipe__list--ingredients">
    <div>
        <h5 class="recipe__list-heading">Tips</h5>
        {!! $meal_detail->tips !!}

        {{-- <ul>

          <li>
             <span>You can make your own hash brown or alternatively the frozen Watties Classic hash browns from a supermarket.
             </span>
         </li>


     </ul> --}}
 </div>
 <button type="button" class="btn--link css-1tgfdsd">
    <span aria-label="Open Ingredients" class="css-o9wkyt">Ingredients</span>
</button>

</div>

@php
$nutritionalInfo = json_decode($meal_detail->nutritional_information);
$nutrient_kcal = json_decode($meal_detail->nutrient_kcal);
$total_cal = (( $nutrient_kcal->total_energ_kcal)*100)/2000;
// $total = (int)$total_cal ;
$total = $total_cal ;
$total_ppl = $meal_detail->serves;
$total_cal_per_person =  round($total/ $total_ppl,1);

$total_recipe_cal = (int)(2000 * $total_ppl);
$overall_total_cal = (( $nutrient_kcal->total_energ_kcal)*100)/$total_recipe_cal;
$overall_total = round($overall_total_cal, 1); 

$single_cal = $nutrient_kcal->total_energ_kcal/ $total_ppl;
$single_ppl_cal = round($single_cal, 2);

@endphp
{{-- chart --}}
<div class="col-6">
  <div class="card mt-3 tab-card">
    <div class="card-header tab-card-header">
      <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
        <li class="nav-item active">
            <a class="nav-link" id="one-tab" data-toggle="tab" href="#one" role="tab" aria-controls="One" aria-selected="true">Overall nutritional information</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="two-tab" data-toggle="tab" href="#two" role="tab" aria-controls="Two" aria-selected="false">Per serve nutritional information</a>
        </li>
    </ul>
</div>

<div class="tab-content" id="myTabContent" >
    <div class="tab-pane fade active p-3 active in" id="one" role="tabpanel" aria-labelledby="one-tab" >
        <div class="wizard" style="width:100%;float:left">
          <div id="calories" style="min-width: 300px; height: 300px; margin: 0 auto"></div>
          <div class="daily">
            <div class="barWrapper">
                <div class="progressText">
                    <span class="daily-text">Daily:</span> <span class="daily-cal">{{$total_recipe_cal}}</span>cal
                </div>
                <div class="progress @if($overall_total > 100) daily-red @endif ">
                    <div class="progress-bar" role="progressbar" aria-valuemin="3" aria-valuemax="100" style="@if($overall_total > 100) width: 98%; @else width: {{$overall_total}}%; @endif">
                        <span class="popOver" data-toggle="tooltip" data-placement="bottom" title="{{$overall_total}}%" data-original-title="{{$overall_total}}%" aria-describedby="tooltip430234">                                                   
                        </span>
                        <div class="tooltip fade bottom in" role="tooltip" id="tooltip430234" style="top: 0px; left: 21.0243px; display: block;">
                            <div class="tooltip-arrow" style="left: 50%;">                                                   
                            </div>
                            <div class="tooltip-inner">{{$overall_total}}%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>




        <div class="nutrient-info col-md-12 col-xs-12">
            <div class="circles">
                <div class="circle protein">
                    <div class="value">{{ $nutrient_kcal->cal_from_protein??0 }}</div>
                    <div class="cal">cal</div>
                </div>
                <div class="subcal">Protein</div>
            </div>
            <div class="circles">
                <div class="circle carbs">
                    <div class="value"> {{ $nutrient_kcal->cal_from_carbs??0 }}</div>
                    <div class="cal">cal</div>
                </div>
                <div class="subcal">Carbs</div>
            </div>
            <div class="circles">
                <div class="circle fat">
                    <div class="value">{{ $nutrient_kcal->cal_from_fat??0 }}</div>
                    <div class="cal">cal</div>
                </div>
                <div class="subcal">Fat</div>
            </div>
        </div>
    </div>      
</div>

<div class="tab-pane fade p-3" id="two" role="tabpanel" aria-labelledby="two-tab">
  <div class="wizard" style="width:100%;float:left">
      <div id="calories-per-serve" style="min-width: 300px; height: 300px; margin: 0 auto"></div>
      <div class="daily">
        <div class="barWrapper">
            <div class="progressText">
                <span class="daily-text">Daily:</span> <span class="daily-cal">2000</span>cal
            </div>

            <div class="progress @if($total_cal_per_person > 100) daily-red @endif">
                <div class="progress-bar" role="progressbar" style="@if($total_cal_per_person > 100) width: 98%; @else width: {{$total_cal_per_person}}%; @endif">
                    <span class="popOver" data-toggle="tooltip" data-placement="bottom" >                                                   
                    </span>
                    <div class="tooltip fade bottom in" role="tooltip" style="top: 0px; left: 21.0243px; display: block;">
                        <div class="tooltip-arrow" style="left: 50%;">                                                   
                        </div>
                        <div class="tooltip-inner">{{ $total_cal_per_person }}%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <div class="nutrient-info col-md-12 col-xs-12">
        <div class="circles">
            <div class="circle protein">

                <div class="value">{{ round($nutrient_kcal->cal_from_protein/$total_ppl , 2)??0 }}</div>
                <div class="cal">cal</div>
            </div>
            <div class="subcal">Protein</div>
        </div>
        <div class="circles">
            <div class="circle carbs">
                <div class="value"> {{ round($nutrient_kcal->cal_from_carbs/$total_ppl, 2)??0 }}</div>
                <div class="cal">cal</div>
            </div>
            <div class="subcal">Carbs</div>
        </div>
        <div class="circles">
            <div class="circle fat">
                <div class="value">{{ round($nutrient_kcal->cal_from_fat/$total_ppl, 2)??0 }}</div>
                <div class="cal">cal</div>
            </div>
            <div class="subcal">Fat</div>
        </div>
    </div>
</div>  
</div>

</div>
</div>
</div>


{{-- end chart --}}

<div class="recipe__tags">
    <span>Calories: {{ $nutritionalInfo->energ_kcal }}</span>
    <ul class="tag-list caloriess">
        <li>FAT: {{ $nutritionalInfo->fat }} g</li>
        <li>SATURATED FAT: {{ $nutritionalInfo->fa_sat }} g
        </li>
         <li>CARBOHYDRATE: {{ $nutritionalInfo->protein}} g </li>
        <li>SUGAR: {{ $nutritionalInfo->sugar }} g</li>
        <li>SODIUM: {{ $nutritionalInfo->sodium }} mg</li>
        <li>FIBER: {{ $nutritionalInfo->fiber }} g</li>
        <li>PROTEIN: {{ $nutritionalInfo->protein }} g</li>
        <li>CHOLESTEROL:
            {{ $nutritionalInfo->cholesterol }} mg</li>

            {{-- <li><a href="#" class="tag">Souffle</a></li>
            <li><a href="#" class="tag">French</a></li>
            <li><a href="#" class="tag">Egg</a></li>
            <li><a href="#" class="tag">Chive</a></li>
            <li><a href="#" class="tag">Cheddar</a></li>
            <li><a href="#" class="tag">Cheese</a></li>
            <li><a href="#" class="tag">Bake</a></li>
            <li><a href="#" class="tag">Dinner</a></li> --}}
        </ul>
    </div>
    <div class="recipe__tags">
        <span>Tags:</span>
        <ul class="tag-list">
         @foreach( $meal_detail->mealMainCategoryDetails as $tag)
         <li>{{$tag['subCategory']['name']}}</li>
         @endforeach 
     </ul>
 </div>
 <!-- Trigger the modal with a button -->
 <a class="nutrion-link" data-toggle="modal" data-target="#nutrional-popup">Click here to see  all nutritional data</a>
</div>

@include('Result.mealplanner.recipe-review')

</div>
</div>


</div>

</section>
<!--Start: meal option popup -->
<div class="modal fade" id="mealoptionmodal" role="dialog">
  <div class="modal-dialog " style="width: max-content;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close m-t-10" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
      </button>
      <h4 class="modal-title">Meal Type </h4>
  </div>
  <div class="modal-body bg-white meal-type">

     <table width="300" border="1">

       <tr>
         <td>
           <h5>BREAKFAST 
             <a class="btn btn-xs btn-primary addBtnCat t-right mtype"  data-meal-category="Breakfast" data-cat-id="29" >
                <i class="fa fa-plus"></i>
            </a> 
        </h5>
    </td>
</tr>
<tr>
   <td>
     <h5>SNACK
        <a class="btn btn-xs btn-primary addBtnCat t-right mtype" data-meal-category="Snack" data-cat-id="32" data-snack-type="1" >
          <i class="fa fa-plus"></i>
      </a> 
  </h5>
</td>
</tr>
<tr>
 <td>
   <h5>LUNCH
      <a class="btn btn-xs btn-primary addBtnCat t-right mtype" data-meal-category="Lunch" data-cat-id="30" >
        <i class="fa fa-plus"></i>
    </a> 
</h5>
</td>
</tr>
<tr>
   <td>
     <h5>SNACK
        <a class="btn btn-xs btn-primary addBtnCat t-right mtype" data-meal-category="Snack" data-cat-id="32" data-snack-type="2" >
          <i class="fa fa-plus"></i>
      </a> 
  </h5>
</td>
</tr>
<tr>
 <td>
   <h5>DINNER
      <a class="btn btn-xs btn-primary addBtnCat t-right mtype" data-meal-category="Dinner" data-cat-id="31" >
        <i class="fa fa-plus"></i>
    </a> 
</h5>
</td>
</tr>
<tr>
   <td>
     <h5>SNACK 
        <a class="btn btn-xs btn-primary addBtnCat t-right mtype" data-meal-category="Snack" data-cat-id="32" data-snack-type="3" >
          <i class="fa fa-plus"></i>
      </a>
  </h5>
</td>
</tr>
</table>    

</div>

</div>
</div>
</div>
<!--End: meal option popup -->

<!--Start: date picker popup -->
<div class="modal fade" id="datepickerpopup" role="dialog">
  <div class="modal-dialog " style="max-width: 332px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close m-t-10" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
      </button>
      <h4 class="modal-title">Date Select</h4>
  </div>
  <div class="modal-body bg-white meal-type"  style="min-height:150px">
<script type="text/javascript">
    $(document).ready(function(){
     $('.add-meal-date').val('');
 });
</script>
    <h4 class="meal_type">Breakfast</h4>
    <input type="date" name="trip-start" class="add-meal-date">   

</div>
<div class="modal-footer">
    <button class="btn btn-primary date-popup-change" >Next</button>
    {{-- <button class="btn btn-primary" data-toggle="modal" data-target="#ingredientsrpopup">Next</button> --}}
</div>

</div>
</div>
</div>


<!--Start: date picker popup -->
<div class="modal fade" id="ingredientsrpopup" role="dialog">
    <input id="meal-category" value="" hidden> 
    <input id="cat-id" value="" hidden> 
    <input id="snack-type" value="" hidden> 
    <input id="meal_date" value="" hidden>   
    <div class="modal-dialog " >
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close m-t-10" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
         
          <h4 class="modal-title">All Ingredients</h4>
      </div>
      <div class="modal-body bg-white meal-type recipe__list recipe__list--ingredients recipe__list--ingredients"  style="min-height:200px">
        <ul> 
         <li>
            <label for="select-all" class="checkbox-list">
                <input class="popup-checkbox-list-input" type="checkbox" id="select-all"> 
                <span class="checkbox-list-checkmark" style="display: inline-block;">
                    <span class="recipe__list-qty">

                        <span class="ingr1-qty" >Select All</span> 
                    </span>
                </span>
            </label>
        </li> 
    </ul>
    <hr>
    {{-- first ul --}}
    <ul>
      @if(($meal_detail->ingredient_set_no != 1) && isset($set_name))
      <li class="recipe__list-subheading">{{$set_name->set_name_1}}:</li> 
      @endif 
      @if(count($meal_detail->mealIngredientSetPart1) > 0) 
      @foreach($meal_detail->mealIngredientSetPart1 as $key => $ingr_val_1)                                                                                                            
      <li>
        <label for="recipe-ingredients-popup-{{$key}}" class="checkbox-list checkbox-popup-list1">
            <input class="popup-checkbox popup-checkbox-list-input" value="{{$ingr_val_1->id}}" type="checkbox" id="recipe-ingredients-popup-{{$key}}"> 
            <span class="checkbox-list-checkmark" style="display: inline-block;">
                <span class="recipe__list-qty">
                    <span class="ingr1-popup-qty-{{$key}}">@if($ingr_val_1->qty != 0){{$ingr_val_1->qty}}@endif</span> {{$ingr_val_1->measurement??''}}
                </span>
                {{$ingr_val_1->item??''}}<br>
            </span>
        </label>
    </li> 
    @endforeach 
    @endif
</ul>  
{{-- end first ul --}} 

{{-- second ul --}}
<ul>
    @if(($meal_detail->ingredient_set_no != 1) && isset($set_name))
    <li class="recipe__list-subheading">{{$set_name->set_name_2}}:</li> 
    @endif 
    @if(count($meal_detail->mealIngredientSetPart2) > 0)
    @foreach($meal_detail->mealIngredientSetPart2 as $key2 => $ingr_val_2)                                                                                                           
    <li>
      <label for="recipe-ingredients-popup-2-{{$key2}}" class="checkbox-list checkbox-popup-list2">
          <input class="popup-checkbox" value="{{$ingr_val_2->id}}" type="checkbox" id="recipe-ingredients-popup-2-{{$key2}}"> 
          <span class="checkbox-list-checkmark" style="display: inline-block;">
              <span class="recipe__list-qty">
                  <span class="ingr2-popup-qty-0">@if($ingr_val_2->qty != 0){{$ingr_val_2->qty}}@endif</span> {{$ingr_val_2->measurement??''}}
              </span>
              {{$ingr_val_2->item??''}}<br>
          </span>
      </label>
  </li> 
  @endforeach 
  @endif
</ul>  
{{-- end second ul --}} 

</div>
<div class="modal-footer">
    <button class="btn btn-primary submit-popup-btn">Next</button>
    <button class="btn btn-secondary skip-popup-btn">Skip</button>
    {{-- <button class="btn btn-secondary skip-popup-btn" data-dismiss="modal">Skip</button> --}}
</div>
</div>
</div>
</div>


<!--popup for ingradeient-->
<div class="css-1366nk8-container">
    <div class="panel--ingredientsDrawer css-par0zx">
        <button class="css-1voobpp-close">
            &#10005;
        </button>
        <div class="css-srah9m">
            <h5 id="ingredientsDrawerPanelHeading" class="css-55oath">Ingredients</h5>


            <!--  {!! $meal_detail->ingredients !!} -->
            <ul>
                @if(($meal_detail->ingredient_set_no != 1) && isset($set_name))
                <li class="css-1fgip3q">{{$set_name->set_name_1}}</li>
                @endif
                @if(count($meal_detail->mealIngredientSetPart1) > 0)                
                @foreach($meal_detail->mealIngredientSetPart1 as $ingr_val_1)
                <li>
                  <label class="css-j068ds"><span><span class="recipe__list-qty">
                    {{-- <span>1 <sup>1</sup>/<sub>2</sub></span> cups --}}
                    <span>@if($ingr_val_1->qty != 0){{$ingr_val_1->qty}} @endif</span> {{$ingr_val_1->measurement}}
                </span>
                {{$ingr_val_1->item}}<br>
            </span><input class="sr-only" value="{{$ingr_val_1->id}}" type="checkbox"><span
            class="css-1cr8uco"></span>
        </label>
    </li>
    @endforeach
    @endif
</ul>
<ul>
    @if(($meal_detail->ingredient_set_no != 1) && isset($set_name))
    <li class="css-1fgip3q">{{$set_name->set_name_2}}</li>
    @endif
    @if(count($meal_detail->mealIngredientSetPart2) > 0)                
    @foreach($meal_detail->mealIngredientSetPart2 as $ingr_val_2)
    <li>
      <label class="css-j068ds"><span><span class="recipe__list-qty">
        {{-- <span>1 <sup>1</sup>/<sub>2</sub></span> cups --}}
        <span>@if($ingr_val_2->qty != 0){{$ingr_val_2->qty}} @endif</span> {{$ingr_val_2->measurement}}
    </span>
    {{$ingr_val_2->item}}<br>
</span><input class="sr-only" value="{{$ingr_val_2->id}}" type="checkbox"><span
class="css-1cr8uco"></span>
</label>
</li>
@endforeach
@endif
</ul>
</div>
</div>
<div class="css-ddnm3p-overlay"></div>
</div>

<!-- Modal for nutrion information-->
<div id="nutrional-popup" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><strong>Nutrional Information</strong></h4>
    </div>
    <div class="modal-body">
      <h4 class="">
         <strong>{{ ucfirst($meal_detail->name) }}</strong>

     </h4>
     <p>Servings Per Recipe:{{$meal_detail->serves}}</p>
     <p>Calories: {{ $nutritionalInfo->energ_kcal}} </p>

     <p class="text-right"> Daily Value*</p>
     {{-- <p class="text-right"> % Daily Value*</p> --}}
     <p><b>Calories:</b><span class="float-right">{{ $nutritionalInfo->energ_kcal}} </span></p>
     <p><b>Fat:</b><span class="float-right">{{ $nutritionalInfo->fat}} g</span></p>
     <p><b>Saturated Fat:</b><span class="float-right">{{ $nutritionalInfo->fa_sat}} g</span></p>
     <p><b>Carbohydrate:</b><span class="float-right">{{ $nutritionalInfo->carbohydrate}} g</span></p>
     <p><b>Sugar:</b><span class="float-right">{{ $nutritionalInfo->sugar}} g</span></p>
     <p><b>Sodium:</b><span class="float-right">{{ $nutritionalInfo->sodium}} mg</span></p>
     <p><b>Fiber:</b><span class="float-right">{{ $nutritionalInfo->fiber}} g</span></p>
     <p><b>Protein:</b><span class="float-right">{{ $nutritionalInfo->protein}} g</span></p>
     <p><b>Cholesterol:</b><span class="float-right">{{ $nutritionalInfo->cholesterol}} mg</span></p>
     <p><b>Vitamin D:</b><span class="float-right">{{ $nutritionalInfo->vitamin}} µg</span></p>
     <p><b>Calcium:</b><span class="float-right">{{ $nutritionalInfo->calcium}} mg</span></p>
     <p><b>Iron:</b><span class="float-right">{{ $nutritionalInfo->iron}} mg</span></p>
     <p><b>Potassium:</b><span class="float-right">{{ $nutritionalInfo->potassium}} mg</span></p>
     <p><b>Trans Fat:</b><span class="float-right">{{ $nutritionalInfo->trans_fat}} g</span></p>
     {{-- <p><b>Calories:</b> 17g <span class="float-right">35%</span></p> --}}
 </div>
 <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
</div>

</div>
</div>


<!-- Modal for nutrion information-->
<div id="rating-popupshow" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content" style="min-height: 250px;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
            <br>
            <br>
          
            
        <div class="css-125iry3">

            <h3 style="text-align: center">Thank you for sharing your rating!</h3>
            <h5 style="text-align: center">You have given <span class="star-rating"></span> review to recipe <span class="rep-title"></span></h5>
            <br>
        </div>
 </div>
 
</div>

</div>
</div>
{{-- end --}}
@include('includes.partials.meal-plan-modal')
@section('script')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
       $(document).on('click','.submitStarRating',function(){
           var star = $(this).attr('data-val');
           var recipe_id = $('.recipe_id').val();
           var title = $('.recipe__title').attr('data-val');
          var formData = {
            star: star,
            recipe_id:recipe_id
          }
          $.post(public_url+'meal-planner/post-rating', {formData}, function(response){ 
           console.log('data----', response);
           if(response.status == "success"){
               var html = star +' Stars';
               $('.star-rating').html(html);
               $('#rating-popupshow').modal('show'); 
               $('.rep-title').html(title);
           }

              
          });
       });

       


        $(document).on('click','.submit-review',function(){
          var comment=$('#LeaveaReview').val();
          if (comment.trim() == ''){
              $('#LeaveaReview').addClass('errorBox');
              $('.validation-error').css('display','block');
                return false;
          }
          var recipe_id = $('.recipe_id').val();
          var formData = {
            comment: comment,
            id:recipe_id
          }
        //   console.log('review==', formData);
          $.post(public_url+'meal-planner/post-review', {formData}, function(response){ 
           console.log('data----', response);
           if(response.status == "success"){
               var old_total = $('.totalReview').attr('data-old');
               var new_total = parseInt(old_total) + parseInt(1); 
                $('.totalReview').html(new_total);
                $('.totalReview').attr('data-old',new_total);
            //  if($('.comments__filters').siblings(".comments__comment").length == 0){
                if($('.appendDiv').siblings(".comments__comment").length == 0){
                    $('.appendDiv').after(response.review);
                    // $('.comments__filters').after(response.review);
                } else {
                    $('.comments__comment:first').before(response.review); 
                }
                $('#LeaveaReview').val('');
                $("#LeaveaReview").parent().parent().parent().parent().removeClass("leave-review");
                $(".leave-review-show").hide();
                $("#LeaveaReview").parent().removeClass("css-czzpt7z");
                if(response.first_comment == 'Yes'){
                    $("#LeaveaReview").parent().parent().parent().parent().removeClass("leave-review");
                    $('.rating-popup').show();
                    $('.leave-review').css('min-height','185px');
                }
             }
         });
      });

      $(document).on('click','.reply-submit-comment',function(){
           var id = $(this).attr('data-id');
           var type = $(this).attr('data-type');
           if(type == 'Reply'){
             var comment = $('.Reply-reply-'+id).val();
             var textarea = $('.Reply-reply-'+id);
           } else{
             var comment = $('.Review-reply-'+id).val();
             var textarea = $('.Review-reply-'+id);
           }
        
           console.log('reply ==',comment );
           if (comment.trim() == ''){
                 textarea.addClass('errorBox');
                $('.reply-validation-error-'+id).css('display','block');
                 return false;     
            }
        //    var recipe_id = $('.recipe_id').val();
           var type = $(this).attr('data-type');
           if( type == 'Review'){
            var review_id = id;
           } else {
            var review_id =  $(this).attr('data-review');
           }
          var formData = {
            comment: comment,
            review_id:review_id,
            // recipe_id:recipe_id
          }
          $.post(public_url+'meal-planner/post-reply', {formData}, function(response){ 
           if(response.status == "success"){
              var old_total = $('.totalReview').attr('data-old');
               var new_total = parseInt(old_total) + parseInt(1); 
                $('.totalReview').html(new_total);
                $('.totalReview').attr('data-old',new_total);
                if($('.reply-textbox-'+review_id).siblings(".comments__comment").length == 0){
                    $('.reply-textbox-'+review_id).after(response.review);
                } else {
                    $('.reply-textbox-'+review_id).siblings(".comments__comment:last").after(response.review);
                  
                }
                textarea.val('');
                $('.reply-'+type+'-'+id).trigger('click');  
             }
         });
      });

      $(document).on('click','.upvote',function(){
          var type= $(this).attr('data-type');
          var id = $(this).attr('data-id');
          var recipe_id = $('.recipe_id').val();
          var formData = {
            type: type,
            review_id:id,
            recipe_id:recipe_id
          }
        //   console.log('review==', formData);
          $.post(public_url+'meal-planner/upvote', {formData}, function(response){ 
           console.log('data----', response);
           console.log('type', type, 'id--', id)
           if(response.status == "success"){
              var html = "("+ response.upvote + ")";
               console.log('html', html);
             $('.addCount-'+type+'-'+id).html('('+response.upvote+ ')');
              
             }
         });
      });

     $('.email-ingredient').click(function(){
         var recipe_id = $('.recipe_id').val();
         var title = $('.recipe__title').attr('data-val');
         var checked_array = [];
         $('.checkbox-list-input').each(function(){
            var checked = $(this).val();
            if ($(this).is(':checked')) {
                checked_array.push(checked);
            }
        });

         var checked_array_length = checked_array.length;
         var old_serve = $('.adjust-value').attr('data-old');
         var new_serve = $('.adjust-value').val();
         var formData ={
            id:recipe_id,
            title:title,
            checked_length:checked_array_length,
            old_serve:old_serve,
            new_serve:new_serve,
            ingr:checked_array
        }
        $.post(public_url+'meal-planner/email-ingredient', {formData}, function(data){ 
           console.log('data----', data);
           if(data.status == "success"){
            swal('email send Successfully');
        } else {
            swal(data.msg);
        }
    });

    });

     $('.view-shopping-list').click(function(){
        window.location.href = public_url+"meal-planner/calendar";

    });
     /* all checkbox */

     $('#select-all').click(function () {
      var checked = $(this).is(':checked');
      $('.popup-checkbox').each(function () {
        var checkBox = $(this);
        console.debug(checkBox);
        if (checked) {
            checkBox.prop('checked', true);                
        }else {
            checkBox.prop('checked', false);                
        }
    });        
  });

            //   $('.addBtnCat').click(function(){
                $(document).on('click','.addBtnCat',function(){
                    var meal_category = $(this).data('meal-category');
                    var cat_id = $(this).data('cat-id');
                    if(meal_category == 'Snack'){
                        var snack_type = $(this).attr('data-snack-type');
                    } else {
                        var snack_type = 0; 
                    }   
                    $('#meal-category').val(meal_category);
                    $('#cat-id').val(cat_id);
                    $('#snack-type').val(snack_type);            
                    $('#datepickerpopup').modal('show');
                });

                $('.add-meal-date').change(function(){
                   var meal_date =  $('input[name="trip-start"]').val();
                   $('#meal_date').val(meal_date);
                   console.log('meal_date', meal_date);
               });

                $('.date-popup-change').click(function(){
                    var meal_date =  $('input[name="trip-start"]').val();
                    if(meal_date){
                        $('.popup-checkbox-list-input').prop('checked',false);     
                        $('#ingredientsrpopup').modal('show');
                    } else{
                        return false;
                    }
                });


                /* checkbox */
                $('.checkbox-list-input').on('change', function() {
                    var checkbox = $('.checkbox-list-input').filter(':checked').length;
                    var checked_value = $(this).val();
                    if($(this).is(':checked')){
                     $('input.sr-only[value="'+checked_value +'"]').prop('checked', true);           
                 }else{
                    $('input.sr-only[value="'+checked_value +'"]').prop('checked', false);           
                }
                    // console.log('checkbox==checked_value', checked_value);
                    if(checkbox == 0){
                        checkbox = "all";
                    }
                    $('.checked-count').html(checkbox);
                    $('.all-success-text').html(checkbox);
                });

                $('.sr-only').on('change', function() {
                    var checkbox = $('.sr-only').filter(':checked').length;
                    var checked_value = $(this).val();
                    if($(this).is(':checked')){
                     $('input.checkbox-list-input[value="'+checked_value +'"]').prop('checked', true);           
                 }else{
                    $('input.checkbox-list-input[value="'+checked_value +'"]').prop('checked', false);           
                }
                console.log('checkbox==checked_value', checked_value);
                if(checkbox == 0){
                    checkbox = "all";
                }
                $('.checked-count').html(checkbox);
                $('.all-success-text').html(checkbox);
            });

                /* end checkbox */
                $('body').on('click', '.mealDiv', function() {
                    var modal = $('#detatilModal');
                    var eventType = $(this).data('type');
                    var mealId = $(this).data('id');
                    $('#waitingShield').removeClass('hidden');
                    $.getJSON("{{ url('/') }}" + '/meal-planner/calendar/meal/' + $(this).data('id'),
                        function(response) {
                            if (response.status == 'success') {
                            //form.find('input[name="eventDate"]').val(response.date);
                            displayDeatils(eventType, response);
                            modal.find('.addFromDetail').hide();
                            modal.find('.back-btn').hide();
                            modal.find('.download-btn').show();
                            modal.find('.download-btn').attr('href', "{{ url('/') }}" +
                                "/meal-planner/meals/download/" + mealId);
                            modal.find('.done-btn').show();
                            modal.find('.modal-title').html(eventType + ' Details');
                            $('#waitingShield').addClass('hidden');
                            modal.modal("show");
                        }
                    });
                });

                function displayDeatils(type, data) {
                    console.log('data==data', data);
                    var modal = $('#detatilModal');

                    if (type == 'Meal') {
                        modal.find('#recipeTitle').text(data.name);

                        modal.find('#mealImage').attr('src', "{{ url('/') }}" + '/uploads/thumb_' + data.img);
                    // modal.find('#nutritional_information').html(nuratInfoHtml(data.nutrInfo));
                    // console.log('sfsfs', data.time, data.cook_time);
                    modal.find('.description_data').html(data.description);
                    modal.find('#preparationData').html(data.method);
                    modal.find('#tipsData').html(data.tips);
                    modal.find('#ingredientPara').html(data.ingredients);
                    if(data.total_hrs > 0){
                        modal.find('#preprationTimeHrs').text(data.total_hrs);
                        $('.time-hrs').css('display','inline-block');
                    }
                    if(data.total_mins > 0){
                        modal.find('#preprationTime').text(data.total_mins);
                        $('.time-min').css('display','inline-block');
                    }


                    // modal.find('#preprationTime').text(data.time);
                    modal.find('#servingSize').text(data.serves);
                    modal.find('#deleteEvent').hide();
                    modal.find('.addFromDetail').data('id', data.id);
                    modal.find('.addFromDetail').data('type', data.type);
                    var nutritionInfo = data.nutrInfo;
                    console.log('nutritionInfo', nutritionInfo);
                    // $('#calories').html(nutritionInfo.energ_kcal);
                    $('.calories-val').html((nutritionInfo.energ_kcal/data.serves).toFixed(2));
                    $('#nutriData').html(
                        '<table class="bottom-table"><tr><th><span>Fat</span>:</th><th><span>Saturated Fat</span>:</th><th><span>Sugar</span>:</th><th><span>Carbohydrate</span>:</th><th><span>Sodium</span>:</th><th><span>Fiber</span>:</th><th><span>Protein</span>:</th><th><span>Cholesterol</span>:</td></tr><tr><td>  <span>' +
                        (nutritionInfo.fat/data.serves).toFixed(2) + '</span> g</td><td>  <span>' + (nutritionInfo.fa_sat/data.serves).toFixed(2) +
                        '</span> g</td><td>  <span>' + (nutritionInfo.sugar/data.serves).toFixed(2) + '</span> g</td><td>  <span>' +
                        (nutritionInfo.carbohydrate/data.serves).toFixed(2) + '</span> g</td><td>  <span>' + (nutritionInfo.sodium/data.serves).toFixed(2) +
                        '</span> mg</td><td>  <span>' + (nutritionInfo.sugar/data.serves).toFixed(2) + '</span> g</td><td>  <span>' +
                        (nutritionInfo.protein/data.serves).toFixed(2) + '</span> g</td><td>  <span>' + (nutritionInfo.cholesterol/data.serves).toFixed(2) +
                        '</span> mg</td></tr></table>');
                    $('.meals_details').show();
                    $('.foodModal').hide();
                }
            }
            /*  */

            $('#selectall').click(function () {
                var checked = $(this).is(':checked');
                
                $('.selectedId').each(function () {
                    var checkBox = $(this);
                    console.debug(checkBox);
                    if (checked) {
                        checkBox.prop('checked', true);                
                    }
                    else {
                        checkBox.prop('checked', false);                
                    }
                });
                
            });

            /*  */
        });

var distance = $('#recipeEditRoot').offset().top;
if ($(window).width() > 767) {
    $(window).scroll(function() {
        if ($(this).scrollTop() >= distance - 600) {
            $('.btn--link.css-1tgfdsd').show();
        } else {
            $('.btn--link.css-1tgfdsd').hide();
        }
    });
}
else{
  $(window).scroll(function() {
    if ($(this).scrollTop() >= distance - 60) {
        $('.btn--link.css-1tgfdsd').show();
    } else {
        $('.btn--link.css-1tgfdsd').hide();
    }
});  
}
$(".btn--link.css-1tgfdsd").click(function() {
    $('.css-1366nk8-container').show();
});
$(".css-1voobpp-close").click(function() {
    $('.css-1366nk8-container').hide();
});
$(".recipe__list-step").click(function() {
    $(".recipe__list-step").removeClass("recipe__list-step--active");
    $(this).addClass("recipe__list-step--active");
});

$(".recipe-shopping-list-meal").click(function() {
  var checked_array = [];
  $('.checkbox-list-input').each(function(){
    var checked = $(this).val();
    if ($(this).is(':checked')) {
        checked_array.push(checked);
    }
});
  var title= $('.recipe__title').attr('data-val');
  var recipe_id = $('.recipe_id').val();
  var checked_array_length = checked_array.length;
  var old_serve = $('.adjust-value').attr('data-old');
  var new_serve = $('.adjust-value').val();
  var form_data ={
    old_serve:old_serve,
    new_serve:new_serve
}
$('.add-recips').html('adding ');
$.post(public_url+'meal-planner/shopping-list-ingredients', {title:title,ingr:checked_array,id:recipe_id,checked_length:checked_array_length, form_data:form_data}, function(data){ 
 console.log('data----', data);
 if(data.status == "success"){
    $('.add-recips').html('add ');
    $('.checked-count').html('all ');
                // location.reload();
                // if(checked_array_length == 0){
                //     $('.all-success-text').html('all');
                // }
                
                //  $(".recipe-shopping-list__add-all-success").removeClass("hidden");
                //  $(".recipe-shopping-list__view-items").removeClass("hidden");
                //  $(".recipe-shopping-list__add-all-button").show();
            }
        });
});
/* popup button */
$(".submit-popup-btn").click(function() {
  var checked_array = [];
  $('.popup-checkbox').each(function(){
    var checked = $(this).val();
    if ($(this).is(':checked')) {
        checked_array.push(checked);
    }
});
  var checked_array_length = checked_array.length;
  var formData = {};
  if(checked_array_length > 0){
    formData.title= $('.recipe__title').attr('data-val');
    formData.recipe_id = $('.recipe_id').val();
    formData.meal_category = $('#meal-category').val();
    formData.cat_id = $('#cat-id').val();
    formData.snack_type = $('#snack-type').val(); 
    formData.meal_date = $('#meal_date').val();
    var btn_type = 'submit';
    $.post(public_url+'meal-planner/detail-shopping-list', {ingr:checked_array,data:formData,btn_type:btn_type}, function(data){ 
        if(data.status == "success"){
            location.reload();
                    //  $('#mealoptionmodal').modal('hide');
                    //  $('#datepickerpopup').modal('hide');
                    //  $('#ingredientsrpopup').modal('hide');
                }
            });
}

});

$('.skip-popup-btn').click(function(){
    var formData = {};
    formData.title= $('.recipe__title').attr('data-val');
    formData.recipe_id = $('.recipe_id').val();
    formData.meal_category = $('#meal-category').val();
    formData.cat_id = $('#cat-id').val();
    formData.snack_type = $('#snack-type').val(); 
    formData.meal_date = $('#meal_date').val();
    var btn_type = 'skip';
    $.post(public_url+'meal-planner/detail-shopping-list', {data:formData, btn_type:btn_type}, function(data){ 
        if(data.status == "success"){
           location.reload();
                    //  $('#mealoptionmodal').modal('hide');
                    //  $('#datepickerpopup').modal('hide');
                    //  $('#ingredientsrpopup').modal('hide');
                }
            });
});


/* popup button */

$('.recipe-adjust-servings__adjust-button').click(function(){
    var new_no = $('.ppl-changes').val();
    $('.adjust-value').attr('value', new_no);
    var old_no = $('.ppl-changes').attr("data-old"); 
    var i = 0;
    $('.checkbox-list1').each(function(){
        var qty_val = $('.ingr1-qty-'+i).attr('data-id');
        if(qty_val){
                    // var new_qty_val = parseFloat(qty_val * new_no).toFixed(2);
                    var new_qty_val = parseFloat((qty_val * new_no).toFixed(2));
                    $('.ingr1-qty-'+i).html(new_qty_val);
                }
                i++;
            });
    var j = 0;
    $('.checkbox-list2').each(function(){
        var qty_val = $('.ingr2-qty-'+j).attr('data-id');
        if(qty_val){
                    // var new_qty_val = qty_val * new_no;
                    var new_qty_val = parseFloat((qty_val * new_no).toFixed(2));
                    $('.ingr2-qty-'+j).html(new_qty_val);
                }
                j++;
            });

});

$(document).ready(function(){
    var nutrient_kcal = <?php echo ($meal_detail->nutrient_kcal ? $meal_detail->nutrient_kcal :'null'); ?>;
    var total_ppl = <?php echo ($meal_detail->serves ? $meal_detail->serves : 1); ?>;
    if(nutrient_kcal){
        var total_energ_kcal =  parseInt(nutrient_kcal.total_energ_kcal);
        var carbs = parseInt(nutrient_kcal.cal_from_carbs);
        var protein = parseInt(nutrient_kcal.cal_from_protein);
        var fat = parseInt(nutrient_kcal.cal_from_fat);
    } else{
        var total_energ_kcal =  0;
        var carbs = 0;
        var protein = 0;
        var fat = 0; 
    }

    var single_cal =  <?php echo ($single_ppl_cal ? $single_ppl_cal : 0); ?>;
    var chat_option = [
    {
        name: 'Protein',
        y: protein,
        color:'#6acc00',
        dataLabels: {
            enabled: false,
        }
    },{
        name: 'Carbs',
        y: carbs,
        color:'#ffbe61',
        dataLabels: {
            enabled: false,
        }
    },    
    {
        name: 'Fat',
        y: fat,
        color:'#f14647',
        dataLabels: {
            enabled: false,
        }
    },
    ],

    chart = new Highcharts.Chart({
       credits: { enabled: false },
       chart: {
        renderTo: 'calories',
        type: 'pie'
    },
    title: {
        text: '<strong>'+total_energ_kcal+'</strong><br>CALORIES',
        align: 'center',
        verticalAlign: 'middle',
        y: 20
    },
    plotOptions: {
        pie: {
            shadow: false
        },
        series: {
            enableMouseTracking: false
        }
    },
    series: [{
        name: 'Browsers',
        data: chat_option,   
        size: '100%',
        innerSize: '80%',
        showInLegend:false,
        dataLabels: {
            enabled: false
        }
    }]
});

    $(document).on('click','.done-btn, .close',function(){ 
        chart = new Highcharts.Chart({
            credits: { enabled: false },
            chart: {
                renderTo: 'calories',
                type: 'pie'
            },
            title: {
                text: '<strong>'+total_energ_kcal+'</strong><br>CALORIES',
                align: 'center',
                verticalAlign: 'middle',
                y: 20
            },
            plotOptions: {
                pie: {
                    shadow: false
                },
                series: {
                    enableMouseTracking: false
                }
            },
            series: [{
                name: 'Browsers',
                data: chat_option,   
                size: '100%',
                innerSize: '80%',
                showInLegend:false,
                dataLabels: {
                    enabled: false
                }
            }]
        });

    });
    /* end */
    chart = new Highcharts.Chart({
       credits: { enabled: false },
       chart: {
        renderTo: 'calories-per-serve',
        type: 'pie'
    },
    title: {
     text: '<strong>'+ single_cal +'</strong><br>CALORIES',
     align: 'center',
     verticalAlign: 'middle',
     y: 20
 },
 plotOptions: {
    pie: {
        shadow: false
    },
    series: {
        enableMouseTracking: false
    }
},
series: [{
    name: 'Browsers',
    data: [
    {
        name: 'Protien',
        y: protein/total_ppl,
        color:'#6acc00',
        dataLabels: {
            enabled: false,
        }
    },{
        name: 'Carbs',
        y: carbs/total_ppl,
        color:'#ffbe61',
        dataLabels: {
            enabled: false,
        }
    },    
    {
        name: 'Fat',
        y: fat/total_ppl,
        color:'#f14647',
        dataLabels: {
            enabled: false,
        }
    },
    ],

    size: '100%',
    innerSize: '80%',
    showInLegend:false,
    dataLabels: {
        enabled: false
    }
}]
});

    /* end */

});

</script>

<script type="text/javascript">

   /** plus minus **/
   $(document).ready(function() {
    $('.minus').click(function () {
        var $input = $(this).parent().find('input');
        var count = parseInt($input.val()) - 1;
        count = count < 1 ? 1 : count;
        $input.val(count);
        $input.change();
        return false;
    });
    $('.plus').click(function () {
        var $input = $(this).parent().find('input');
        $input.val(parseInt($input.val()) + 1);
        $input.change();
        return false;
    });
});
   $(document).ready(function(){
    //   $('.ScrollTopp').click(function() {
    //     $('#ReviewsPanel').animate({scrollTop: 0});
    //     return false;
    // });
      $('.reply-textbox').hide();
        $(document).on('click','.comment_reply',function(){ 
           $(this).parent().parent().parent().children('.reply-textbox').toggle();
      });
     
      $("#LeaveaReview").click(function(){
        $("#LeaveaReview").parent().parent().parent().parent().addClass("leave-review");
        $(".leave-review-show").show();
      });

    //   $('.forfocuss').focusin(function () {
       $(document).on( "focusin", ".forfocuss" ,function(){
         $(this).parent().addClass('css-czzpt7z');
       });
       
      $('.forfocuss').focusout(function () {
         $(this).parent().removeClass('css-czzpt7z');
     });

      });
    $(document).ready(function() { 
            $(".forfocuss").focusout(function() { 
                if($(this).val()=='') { 
                      $(this).parent().removeClass('css-czzpt7z');  
                       
                }
                else {
                       $(this).parent().addClass('css-czzpt7z');
                    // If it is not blank.
                  
                }    
            }) .trigger("focusout");
        }); 
  </script>
  <script>
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();   
});
</script>
  @stop

  @stop
