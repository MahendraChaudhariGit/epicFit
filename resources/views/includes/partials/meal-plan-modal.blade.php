<style type="text/css">
.recipe__list-subheading{
  list-style: none !important;
  font-style: normal;
  font-family: inherit;
  font-weight: 400;
  margin-bottom: 5px;
}
</style>
<style type="text/css">
h1 {
 font-family: "Gotham SSm A", sans-serif;
 text-align: center;
}
.main-content {
 background: white;
}
.modal-content {
 background: white;
 box-shadow: none;
 border-radius: 0px;
}
</style>
<style type="text/css">
.pagination>li>a,
.pagination>li>span {
 border-radius: 0px;
 width: 50px;
 height: 50px;
 line-height: 40px;
 background: #eaeaea;
 color: #444;
 font-weight: 400;
 font-family: "Gotham SSm A", sans-serif;
 font-size: 17px;
 font-weight: normal;
 line-height: 40px;
 border: 0px;
}
.pagination>.disabled>span,
.pagination>.disabled>span:hover,
.pagination>.disabled>span:focus,
.pagination>.disabled>a,
.pagination>.disabled>a:hover,
.pagination>.disabled>a:focus {
 color: #777;
 cursor: not-allowed;
 background-color: #eaeaea;
 border-color: #ddd;
}
.pagination>li:first-child>a,
.pagination>li:first-child>span {
 font-size: 32px;
 font-weight: normal;
 line-height: 36px;
 border-top-left-radius: 0;
 border-bottom-left-radius: 0;
}
.pagination>.active>a,
.pagination>.active>span,
.pagination>.active>a:hover,
.pagination>.active>span:hover,
.pagination>.active>a:focus,
.pagination>.active>span:focus {
 border-color: #d1d1d1 !important;
 background: #d1d1d1 !important;
 color: #444;
}
.pagination>li:last-child>a,
.pagination>li:last-child>span {
 border-top-right-radius: 4px;
 border-bottom-right-radius: 4px;
 font-size: 32px;
 font-weight: normal;
 line-height: 36px;
}
.pagination>li:last-child>a,
.pagination>li:last-child>span {
 color: #ffffff;
 background-color: #f64c1e;
 border-color: #ddd;
 border-radius: 0px;
}
@media(max-width: 767px) {
 .pagination li {
   display: none;
 }
 .pagination li:first-child,
 .pagination li:last-child {
   display: inline-block;
 }
 #mealplanmodal .modal-dialog{
 max-width: 1100px;
 width: 98%;
 margin: 1% !important;
}
#mealplanmodal .modal-content{
  height: 100%;
}
#mealplanmodal .modal-dialog{
  height: 100%;
}
}
.paginationn>.col-md-6.col-xs-12 {
 width: 100%;
 text-align: center;
}
.paginationn .col-sm-text {
 text-align: center;
}
#mealplanmodal .modal-dialog{
 max-width: 1100px;
 width: 97%;
}
.addBtn.addBtnMeal{
  float: right;
  position: relative;
  bottom: 25px;
  right: 6px;
}
#mealplanmodal .modal-footer{
  background: white;
}

</style>

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
               <a class="btn btn-xs btn-primary addBtnCat t-right mtype" data-meal-category="Breakfast" data-cat-id="{{isset($catType)?$catType['Breakfast']:''}}">
                <i class="fa fa-plus"></i>
              </a> 
            </h5>
          </td>
        </tr>
        <tr>
         <td>
           <h5>SNACK
            <a class="btn btn-xs btn-primary addBtnCat t-right mtype" data-meal-category="Snack" data-cat-id="{{isset($catType)?$catType['Snack']:''}}" data-snack-type="1">
              <i class="fa fa-plus"></i>
            </a> 
          </h5>
        </td>
      </tr>
      <tr>
       <td>
         <h5>LUNCH
          <a class="btn btn-xs btn-primary addBtnCat t-right mtype" data-meal-category="Lunch" data-cat-id="{{isset($catType)?$catType['Lunch']:''}}">
            <i class="fa fa-plus"></i>
          </a> 
        </h5>
      </td>
    </tr>
    <tr>
     <td>
       <h5>SNACK
        <a class="btn btn-xs btn-primary addBtnCat t-right mtype" data-meal-category="Snack" data-cat-id="{{isset($catType)?$catType['Snack']:''}}" data-snack-type="2">
          <i class="fa fa-plus"></i>
        </a> 
      </h5>
    </td>
  </tr>
  <tr>
   <td>
     <h5>DINNER
      <a class="btn btn-xs btn-primary addBtnCat t-right mtype" data-meal-category="Dinner" data-cat-id="{{isset($catType)?$catType['Dinner']:''}}">
        <i class="fa fa-plus"></i>
      </a> 
    </h5>
  </td>
</tr>
<tr>
 <td>
   <h5>SNACK 
    <a class="btn btn-xs btn-primary addBtnCat t-right mtype" data-meal-category="Snack" data-cat-id="{{isset($catType)?$catType['Snack']:''}}" data-snack-type="3">
      <i class="fa fa-plus"></i>
    </a>
  </h5>
</td>
</tr>
</table>    
<a class="btn btn-primary" href="#" data-toggle="modal" data-target="#customMealplanmodal" style="margin-top: 10px;">Add Custom
  <i class="fa fa-plus"></i>
</a>
</div>
<div class="modal-footer">
 {{--    <button type="button" class="btn btn-primary" data-dismiss="modal">Done</button> --}}
 <!-- <button type="button" class="btn btn-primary submit">Done</button> -->
</div>
</div>
</div>
</div>
<!--End: meal option popup -->

<!--Start: Meal plan Modal -->
<div class="modal fade" id="mealplanmodal" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close m-t-10 meal-modal" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Meal plan</h4>
      </div>
      <div class="modal-body bg-white">
        <div class="row">
          <div class="col-md-12">
            <div class="recipes-section">
              <div class="recipes-filter">
                <div class="col-md-12 col-xs-12 form-group text-center p-0">
                  {{-- <form action="{{ Request::url() }}" method="get"> --}}
                   <div class="filter-box">
            <!--  <strong>Showing:</strong> 
               <select class="form-control" name="filter" onchange="javascript:$(this).closest('form').subm`it();">
                   <option value="">All</option>
                   @foreach ($mealCategories as $category)
                   <option value="{{ $category['name'] }}" {{ Request::get('filter') == $category->name ? 'selected' : '' }}>{{ $category['name'] }}</option>
                   @endforeach
                 </select> -->

                 <div class="form-group  has-feedback search-box">
                   {{-- {!! Form::open(['url' => Request::url(), 'method' => 'get']) !!} --}}
                   <input type="text" name="search" value="{{ Request::get('search') }}" autofocus="autofocus"
                   class="form-control search-wd search-height" style="<?php echo Request::get('search') ? 'width:100%' : 'width:100%'; ?>"
                   placeholder="Search recipes and more…">
                   <button class="btn btn-primary btn-sm search-submit-btn"></button>
                   {{-- {!! Form::submit('', ['class' => 'btn btn-primary btn-sm search-submit-btn']) !!} --}}
                   <button class="btn--link css-wga544" type="button"><span class="icon__remove"
                    aria-label="Clear search input"></span></button>
                    @if (Request::get('search'))
               <!--  <a class="btn btn-primary btn-sm search-clear-btn" href="{{ Request::url() }}">
                  Clear
                </a> -->
                @endif
                {{-- {!! Form::close() !!} --}}
                {{-- suggestion list --}}
                <div class="search-suggested-recipes"></div>
                {{-- end suggestion list --}}
              </div>
            </div>
          {{-- </form> --}}
        </div>
        <div class="content__row">
          <div class="col-md-3 col-xs-12 col-sm-3" style="padding-left:0px">
           <a class="btn btn--default btn--outline css-u5bt32" data-toggle="modal"
           href="#filtermodal">Filter</a>
           <input id="category-name" name="category" value="" hidden>
           <div class="hidden-xs include-filter-popup" id="include-filter-popup">
            {{-- @include('includes.partials.filter_popup') --}}
          </div>
        </div>
        <div class="content__main">
          <div class="dm-flex">
            <div class="matching-recipes">
             <span class="search-header__count hidden-sm hidden-md hidden-lg"><span class="total-count"></span> result</span>
             <span class="search-header__count hidden-xs"><span class="total-count"></span> matching recipes</span>
           </div>

           <div class="filter-data">     
          </div>
         </div>

         {!! Form::open(['url' => '', 'role' => 'form','id' => 'meal-plan','onkeydown' => "return event.key != 'Enter';"]) !!}
         {!! Form::hidden('eventDate') !!}
         {!! Form::hidden('eventId') !!}
         {!! Form::hidden('eventCat') !!}
         {!! Form::hidden('eventSnackType',0) !!}
         <!--  <div class="tabbable"> -->
         <!--  <ul id="classTabs" class="nav nav-tabs ">
            <li class="active">
              <a href="#mealDetail" data-toggle="tab">
                <i class="fa fa-cutlery"></i> Meal
              </a>
            </li>
           
          </ul> -->
          <div>
            <!--   <div id="mealDetail"> -->
              <!-- <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    {!! Form::text('meal_name', null, ['class'=>'form-control','placeholder'=>'Type and wait']) !!}
                  </div>
                </div>
              </div> -->
              <ul class="recipe_list" id="list-area-Meal">

                <!-- Meal list here --> 

              </ul>
              <!--   </div> -->
              <!-- foodDetail -->
            <!--<div class="tab-pane fade" id="foodDetail">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    {!! Form::text('food_name', null, ['class'=>'form-control','placeholder'=>'Type and wait']) !!}
                  </div>
                </div>
              </div>
              <div class="row" id="list-area-Food">
              
              </div>
            </div> -->
            <!-- foodDetail -->
          </div>
          <!-- </div>   -->    
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-primary meal-modal" data-dismiss="modal">Done</button>
  <!-- <button type="button" class="btn btn-primary submit">Done</button> -->
</div>
</div>
</div>
</div>
<!--End: Meal plan Modal -->

<!-- start of details modal -->
<div id="detatilModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-dialog modal-lg">
    <div class="modal-content panel-white">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body bg-white">
        <div class="meals_details">
          <h1 id="recipeTitle"></h1>
          <img src="" class="mainimg" id="mealImage">
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
                {{-- <h4>Prep<br>Time</h4>                   --}}
                <h4>Time</h4> 
                <div class="right_hd">
                  <span id="preprationTimeHrs" class="value"></span>
                  <span class="time-hrs"  style="display: none">Hour</span>
                  <span id="preprationTime" class="value"></span>
                  <span class="time-min" style="display: none">Minutes</span>
                </div>
              </li>
              <li>
                <div class="icon">
                  <img src="{{asset('assets/images/serving-icon.png')}}">
                </div>
                <h4>Serving Size</h4>
                <div class="right_hd">
                  <span id="servingSize" class="value"></span>
                </div>
              </li>
            </ul>
            <div class="description_data">
            </div>
          </div>
          <div class="bottom_data">
            <div class="prepation_box">
              <h2><img src="{{asset('assets/images/ingrediant-icon.png')}}"> Ingredients</h2>
              <!-- <div id="ingredientPara">
              </div> -->
              <ul>

                @if(($meal_detail->ingredient_set_no != 1) && isset($set_name))
                <li class="recipe__list-subheading">{{$set_name->set_name_1}}:</li>
                @endif
                @if(count($meal_detail->mealIngredientSetPart1) > 0)
                @foreach($meal_detail->mealIngredientSetPart1 as $ingr_val_1)
                <li>
                  <span style="display: inline-block;">
                    <span class="">
                      <span>@if($ingr_val_1->qty != 0){{$ingr_val_1->qty}}@endif</span> {{$ingr_val_1->measurement??''}}
                    </span>
                    {{$ingr_val_1->item??''}}<br>
                  </span>         
                </li> 
                @endforeach
                @endif 

                {{-- <li class="recipe__list-subheading">Pudding & Caramelized Bananas:</li>
                <li>
                  <span style="display: inline-block;">
                    <span class="">
                      <span>1</span> teaspoon
                    </span>
                    mayonnaise<br>
                  </span>

                </li> --}}

              </ul>
              <ul>
                @if(($meal_detail->ingredient_set_no != 1) && isset($set_name))
                <li class="recipe__list-subheading">{{$set_name->set_name_2}}:</li>
                @endif
                @if(count($meal_detail->mealIngredientSetPart2) > 0)
                @foreach($meal_detail->mealIngredientSetPart2 as $ingr_val_2)
                <li>
                  <span style="display: inline-block;">
                    <span class="">
                      <span>@if($ingr_val_2->qty != 0){{$ingr_val_2->qty}}@endif</span> {{$ingr_val_2->measurement??''}}
                    </span>
                    {{$ingr_val_2->item??''}}<br>
                  </span>

                </li> 
                @endforeach
                @endif 


              </ul>
            </div>
            <div class="prepation_box">
              <h2><img src="{{asset('assets/images/preparation-icon.png')}}"> Preparation</h2>
              @if(($meal_detail->ingredient_set_no == 3) && isset($set_name))
              <p class="recipe__list-subheading">{{$set_name->set_name_1}}</p>
              @endif
              <ol>
                @if(count($meal_detail->mealPreparationPart1) > 0)
                @foreach($meal_detail->mealPreparationPart1 as $key=> $prep_part1)
                <li class="">
                  {{ $prep_part1['description'] }} 
                </li>
                @endforeach
                @endif
              </ol>

              @if(($meal_detail->ingredient_set_no == 3) && isset($set_name))
              <p  class="recipe__list-subheading">{{$set_name->set_name_2}}</p>
              @endif
              <ol>

                @if(count($meal_detail->mealPreparationPart2) > 0)
                @foreach($meal_detail->mealPreparationPart2 as $key=> $prep_part2)
                <li class="">
                  {{ $prep_part2['description'] }}
                </li>
                @endforeach
                @endif
              </ol>
             <!--  <div id="preparationData">
             </div> -->
             <br>
             <h3><img src="{{asset('assets/images/preparation-icon.png')}}"> Tips</h3>
             <div id="tipsData">
             </div>
           </div>                        
         </div>
         <div class="bootom_area">
          <h3><span>Calories:</span><span id="calories" class="calories-val"></span></h3>
          <p id="nutriData"></p>
        </div>
      </div>


      <!--  Custom Meal Details start -->
      <div class="custom_meals_details" style="display: none">
        <h1 id="recipe_name"></h1>

        <div class="custom_food_img">
          <img src="{{asset('result/images/food-img.jpg')}}" width="320" height="100%">
        </div>

        <div class="custom_ingrediant">
          <h2>Ingredients</h2>
          <div id="ingredients"> </div>
        </div>
        <div class="quant">
          <h2>Quantity</h2>              
          <div id="quantity"> </div>
        </div>
        
        <h2>Serving size</h2>
        <div id="serving_size"> </div>

        <h2>When</h2>
        <div id="type"> </div>
        <div class="extra-data" style="display:none">
          <h2 class="nutritionTIme">Time</h2>
          <div id="nutritionTIme"></div>
          <h2>Hunger Level (Rated from 1 Nit Hungry to 10 Famished)</h2>
          <div id="">
            <div class="rating_icon">
            </div>
          </div>
          <h2>Activity Lebel, note down your activity including workout intensity and duration</h2>
          <div id="activityLabel"></div>

          <h2>General Notes</h2>
          <div id="generalNotes"></div>
          <h2>Meal Rating</h2>
          <div id="mealRating"> </div>
          <h2>Enjoyed Your Meal?</h2>
          <div id="mealPortion"> </div>
        </div>
      </div>
      <!--  Custom Meal Details end -->



      <div class="row foodModal" style="display: none">
        <div class="col-md-6">
          <div style="max-height:500px;overflow-x:hidden">
            <div class="panel-group accordion" id="accordion-fit">
              <div class="panel panel-white">
                <div class="panel-heading">
                  <h4 class="panel-title text-center">
                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion-fit" href="#collapseOne1">
                      NUTRITIONAL INFORMATION
                    </a>
                  </h4>
                </div>
                <div id="collapseOne1" class="panel-collapse collapse">
                  <div class="panel-body" id="nutritional_information">
                    <!-- here message inject throught js --> 
                  </div>
                </div>
              </div>

              <div class="panel panel-white">
                <div class="panel-heading">
                  <h4 class="panel-title text-center">
                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion-fit" href="#collapseTwo2">
                      DESCRIPTION
                    </a>
                  </h4>
                </div>
                <div id="collapseTwo2" class="panel-collapse collapse">
                  <div class="panel-body" id="description">     
                   <!-- here message inject throught js --> 
                 </div>
               </div>
             </div>

             <div class="panel panel-white">
              <div class="panel-heading">
                <h4 class="panel-title text-center">
                  <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion-fit" href="#collapseThree3">
                    INGREDIENTS
                  </a>
                </h4>
              </div>
              <div id="collapseThree3" class="panel-collapse collapse">
                <div class="panel-body" id="ingredients"> 
                 <!-- here message inject throught js --> 
               </div>
             </div>
           </div>

           <div class="panel panel-white">
            <div class="panel-heading">
              <h4 class="panel-title text-center">
                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion-fit" href="#collapsefour4">
                  METHOD
                </a>
              </h4>
            </div>
            <div id="collapsefour4" class="panel-collapse collapse">
              <div class="panel-body" id="method">
               <!-- here message inject throught js --> 
             </div>
           </div>
         </div>

         <div class="panel panel-white">
          <div class="panel-heading">
            <h4 class="panel-title text-center">
              <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion-fit" href="#collapsefive5">
                TIPS
              </a>
            </h4>
          </div>
          <div id="collapsefive5" class="panel-collapse collapse">
            <div class="panel-body" id="tips">
              <!-- here message inject throught js --> 
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div id="img-area" >
      <!-- <img src="" class="exerciseImg" height="100%" width="100%"> -->
    </div>
    <div class="m-t-10" id="tags-area">

    </div>
  </div>
</div>
</div>
<div class="modal-footer data-btn" data-id='' data-type=''>
  <a class="btn btn-primary btn-o pull-left back-btn" href="#" data-dismiss="modal"> <i class="fa fa-arrow-left"></i> Back </a>
  <a class="btn btn-primary addFromDetail" href="#" data-id="" data-type=""> <i class="fa fa-plus"></i> Add</a>
  <a class="btn btn-primary pull-left" href="#" id="deleteEvent" data-id="" data-type=""> <i class="fa fa-trash-o"></i> Delete</a>
  <a class="btn btn-primary pull-left download-btn" id="downloadEvent" href="" data-placement="top" data-original-title="Download" style="display: none;"> <i class="fa fa-download"></i> Download </a>


  <a class="btn btn-primary done-btn" href="#" data-dismiss="modal" style="display: none;"> Done </a>
</div>
</div>
</div>
</div> 
<!-- end of details modal -->


<!--Start: Custom Meal plan Modal -->
<div class="modal fade custom-meal-model" id="customMealplanmodal" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="custom">
      <div class="modal-content custom data-btn">
        <div class="modal-header">
          <input type="hidden" name="event_date">
          <button type="button" class="close m-t-10" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">Custom Meal plan </h4>
        </div>
        <div class="modal-body bg-white">
          <div class="tabbable">
            <ul id="classTabs" class="nav nav-tabs ">
              <li class="active">
                <a href="#customDetail" data-toggle="tab">
                  <i class="fa fa-file-text-o"></i> Custom Meal plan 
                </a>
              </li>
            </ul>
            <div class="tab-content">
              <!-- custom Data tab start -->
              <div class="tab-pane fade in active" id="customDetail">
               <form id="customFormModal">
                <div class="row">
                                    <!-- <div class="col-md-12">
                                        <h5>Nutritional Journal</h5>
                                      </div> -->
                                    </div>
                                    <div class="row">
                                      <div class="col-md-12">
                                        <div class="form-group">
                                          <label class="strong">When</label>
                                          <select class="form-control" name="cat_id" id="catId">
                                            <option value="{{isset($catType)?$catType['Breakfast']:''}}" data-is-snack="0">Breakfast</option>
                                            <option value="{{isset($catType)?$catType['Snack']:''}}" data-is-snack="1" data-snack-type="1">Snack 1</option>
                                            <option value="{{isset($catType)?$catType['Lunch']:''}}" data-is-snack="0">Lunch</option>
                                            <option value="{{isset($catType)?$catType['Snack']:''}}" data-is-snack="1" data-snack-type="2">Snack 2</option>
                                            <option value="{{isset($catType)?$catType['Dinner']:''}}" data-is-snack="0">Dinner</option>
                                            <option value="{{isset($catType)?$catType['Snack']:''}}" data-is-snack="1" data-snack-type="3">Snack 3</option>
                                          </select>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-md-12">
                                        <div class="add_time_section">
                                          <ul class="time_selectable">
                                            <li  class="manual_time active time_opt" data-time-opt="manual">Manual Time Entry</li>
                                            <li class="automatic_time time_opt" data-time-opt="automatic">Automatic Time Entry</li>
                                          </ul>
                                          <input type="hidden" name="time_opt" id="time_opt" value="">
                                          <input type="hidden" name="nutritionalTime" id="automaticTime" value="">
                                        </div>
                                        <div class="form-group add_time_manual clearfix">
                                          <label>Time</label>
                                          <div class="row">
                                            <div class="col-md-6 form-group">
                                              <select class="form-control hour-value" name="bm_time_hour" id="time_hour" required="required">
                                                <option data-hidden="true" value ="">HOUR</option>
                                                <option value="00">00</option>
                                                <option value="01">01</option>
                                                <option value="02">02</option>
                                                <option value="03">03</option>
                                                <option value="04">04</option>
                                                <option value="05">05</option>
                                                <option value="06">06</option>
                                                <option value="07">07</option>
                                                <option value="08">08</option>
                                                <option value="09">09</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                                <option value="13">13</option>
                                                <option value="14">14</option>
                                                <option value="15">15</option>
                                                <option value="16">16</option>
                                                <option value="17">17</option>
                                                <option value="18">18</option>
                                                <option value="19">19</option>
                                                <option value="20">20</option>
                                                <option value="21">21</option>
                                                <option value="22">22</option>
                                                <option value="23">23</option>
                                              </select>
                                            </div>
                                            <div class="col-md-6 form-group">
                                              <select class="form-control min-value" name="bm_time_min" id="time_min" required>
                                                <option data-hidden="true" value = "">MINUTES</option>
                                                <option value="00">00</option>
                                                <option value="05">05</option>
                                                <option value="10">10</option>
                                                <option value="15">15</option>
                                                <option value="20">20</option>
                                                <option value="25">25</option>
                                                <option value="30">30</option>
                                                <option value="35">35</option>
                                                <option value="40">40</option>
                                                <option value="45">45</option>
                                                <option value="50">50</option>
                                                <option value="55">55</option>
                                              </select>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-md-12">
                                        <div class="form-group" id="hungerCustomSection">
                                          <h3>Hunger Level (Rated from 1 Nit Hungry to 10 Famished)</h3>
                                          <div class="bottle-scroll-bar common-scrollbar arrowicon">
                                            <div class="number">
                                              <div class="">
                                                <span class="minus" id="hungerCustomMinus">-</span>
                                                <input class="hunger_custom_rate" name="hunger_rate" type="range" value="1" min="1" max="10" step="1">
                                                <span class="plus" id="hungerCustomplus">+</span>
                                              </div>
                                            </div>
                                            <span class="smallwidth hunger-custom-range-value">1</span><span class="smallwidth">&nbsp;Star</span>
                                          </div>
                                          {{-- <div class="star-rate">
                                            <input type="radio" id="hungerStar10" name="hunger_rate" value="10" required/>
                                            <label for="hungerStar10" title="text">
                                              <span class="select-multiple">10</span>
                                            </label>
                                            <input type="radio" id="hungerStar9" name="hunger_rate" value="9" required/>
                                            <label for="hungerStar9" title="text">
                                              <span class="select-multiple">9</span>
                                            </label>
                                            <input type="radio" id="hungerStar8" name="hunger_rate" value="8" required/>
                                            <label for="hungerStar8" title="text">
                                              <span class="select-multiple">8</span>
                                            </label>
                                            <input type="radio" id="hungerStar7" name="hunger_rate" value="7" required/>
                                            <label for="hungerStar7" title="text">
                                              <span class="select-multiple">7</span>
                                            </label>
                                            <input type="radio" id="hungerStar6" name="hunger_rate" value="6" required/>
                                            <label for="hungerStar6" title="text">
                                              <span class="select-multiple">6</span>
                                            </label>
                                            <input type="radio" id="hungerStar5" name="hunger_rate" value="5" required/>
                                            <label for="hungerStar5" title="text">
                                              <span class="select-multiple">5</span>
                                            </label>
                                            <input type="radio" id="hungerStar4" name="hunger_rate" value="4" required/>
                                            <label for="hungerStar4" title="text">
                                              <span class="select-multiple">4</span>
                                            </label>
                                            <input type="radio" id="hungerStar3" name="hunger_rate" value="3" required/>
                                            <label for="hungerStar3" title="text">
                                              <span class="select-multiple">3</span>
                                            </label>
                                            <input type="radio" id="hungerStar2" name="hunger_rate" value="2" required/>
                                            <label for="hungerStar2" title="text">
                                              <span class="select-multiple">2</span>
                                            </label>
                                            <input type="radio" id="hungerStar1" name="hunger_rate" value="1" required/>
                                            <label for="hungerStar1" title="text">
                                              <span class="select-multiple">1</span>
                                            </label>
                                          </div> --}}
                                        </div>
                                      </div>
                                    </div>
                                    
                                    <div class="row margin-top-30">
                                      <div class="col-md-12">
                                        <h3>Custom Meal plan</h3>
                                      </div>
                                      <div class="col-md-12">
                                        <div class="form-group">
                                          <label for="field" class="strong">Recipe Name</label>
                                          <input type="text" name="recipe_name" class="form-control" required>                      
                                        </div>
                                      </div>
                                      <div class="col-md-12">
                                        <div class="form-group meal-images-section">
                                          <label class="strong">Click Meal Image </label>
                                          {{-- <button type="button" class="camera-btn">
                                            <img src="{{asset('result/images/camera-icon.png')}}">
                                          </button> --}}
                                          <input type="hidden" name="clickedPic" id="clickedPic">
                                          <input type="file" accept="image/*" capture onchange="fileSelectHandlerClick(this)" class="chooseFileBtn" id="chooseFileBtn">
                                          <label for="chooseFileBtn">
                                            <img src="{{asset('result/images/camera-icon.png')}}">
                                          </label>
                                        </div>
                                        <div class="form-group">
                                          <div id="mealImageBox" style="display:none">
                                            <div class="meal_image">
                                              <img src="{{asset('result/images/food-img.jpg')}}">
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-md-12">
                                        <div class="row-box">
                                          <div class="form-group">                       
                                            <div class="row">
                                              <div class="col-md-8">
                                                <label class="strong">Ingredient</label>
                                                <input type="text" class="form-control" name="ingredients" required>
                                              </div>
                                              <div class="col-md-2">
                                                <label class="strong">Quantity </label>
                                                <input type="text" name="quantity" class="form-control" required>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <button type="button" class="btn btn-primary addMoreCustomRow">Add More <i class="fa fa-plus" aria-hidden="true"></i></button>    
                                        </div>
                                      </div>                                                        
                                    </div>
                                    <div class="row">
                                      <div class="col-md-12">
                                        <div class="form-group">
                                          <label class="strong">Serving size</label>
                                          <select class="form-control" name="serving_size" required title="Choose one of the following...">
                                            <option value="Small">Small</option>
                                            <option value="Medium">Medium</option>
                                            <option value="Large">Large</option>
                                          </select>
                                        </div>
                                      </div> 
                                    </div>
                                    <div class="row">
                                      <div class="col-md-12">
                                        <div class="form-group">
                                          <label class="strong">How healthy</label>
                                          <select class="form-control" name="meal_rating" required title="Choose one of the following...">
                                            <option value="Healthy">Healthy</option>
                                            <option value="Average">Average</option>
                                            <option value="Unhealthy">Unhealthy</option>
                                          </select>
                                        </div>
                                      </div> 
                                    </div>
                                    <div class="row">
                                      <div class="col-md-12">
                                        <div class="form-group">
                                          <label class="strong">How much enjoyed the meal</label>
                                          <select class="form-control" name="enjoyed_meal" required title="Choose one of the following...">
                                            <option value="Very">Very</option>
                                            <option value="Somewhat">Somewhat</option>
                                            <option value="Not">Not</option>
                                          </select>
                                        </div>
                                      </div> 
                                    </div>
                                    <div class="row">
                                      <div class="col-md-12">
                                        <div class="form-group">
                                          <label class="strong">Activity Lebel, note down your activity including workout intensity and duration</label>
                                          <textarea class="form-control" name="activity_label" required></textarea>
                                        </div>
                                      </div>
                                      <div class="col-md-12">
                                        <div class="form-group">
                                          <label class="strong">General Notes</label>
                                          <textarea class="form-control" name="general_notes" required></textarea>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-md-12">
                                        <div class="col-md-12 col-ms-12 col-xs-12 text-center">
<!--                                             <button type="button" class="cancl-btn pull-left btn btn-danger" data-dismiss="modal">Cancel</button>
-->                                            {{-- <button type="button" class="camera-btn">
  <img src="{{asset('result/images/camera-icon.png')}}">
</button> --}}
<!--                                             <button type="button" class="save-btn saveCustomFormModal pull-right btn btn-danger">Save</button>
-->                                            {{-- <img src="{{asset('result/images/epic-icon-orenge.png')}}" class="bottom_logo"> --}}
</div>
</div>
</div>
</form>
</div>
<!-- custom Data tab end -->            
</div>
</div>      
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-primary save-btn saveCustomFormModal" type="submit">Done</button>
  <button type="button" class="btn btn-primary cancl-btn pull-left btn" data-dismiss="modal">Cancel</button>
</div>
</div>
</div>
</div>
</div>
<!--End: Custom Meal plan Modal -->
<!-- Modal -->
<div class="modal fade" id="filtermodal" role="dialog">
  <div class="modal-dialog" style="margin: 0;height:100%">
     <!-- Modal content-->
     <div class="modal-content" style="height:100%">
        <button type="button" class="close" data-dismiss="modal"></button>
        <div class="modal-body">
           <div class="row">
              <div class="col-md-12 include-filter-popup-mob">
              
                 {{-- @include('includes.partials.filter_popup_mob') --}}
              </div>
           </div>
        </div>
     </div>
  </div>
</div>

@section('required-script')
{!! Html::script('result/plugins/bootstrap-select-master/js/bootstrap-select.min.js?v=' . time()) !!}
<script>
   $(function() {
      $('.meal-modal').click(function(){
          $('.icon__remove').click();
     });

      $(document).on('click','.include-close-btn',function(){
           var tag = $(this).data("val");
           $('input[name="include[]"][value="' + tag + '"]').remove();
           filterChange();
       });
   
        $(document).on('click','.exclude-close-btn',function(){
           var tag = $(this).data("val");
           $('input[name="exclude[]"][value="' + tag + '"]').remove();
           filterChange();
       });
       
   
       $('select').selectpicker();
   
       var minlength = 3;
       $('.search-box .search-wd.search-height').keyup(function(e) {
        if(e.keyCode != 13) {
           //  $(".search-header-autosuggest__suggestions-container").show();
           $(".search-submit-btn").addClass("search-history");
           $(".btn--link").show();
           value = $(this).val();
           var category = $('#category-name').val();
           data = {category_type:category, value:value};
           if (value.length >= minlength) {
          
            $.get("{{ url('/') }}" + '/meal-planner/calendar-filter', data, function(response) {
              //  $.get("{{ url('/') }}" + '/meal-planner/recipes/' + value, function(response) {
                   if (response.status == 'success') {
                       console.log(response);
                       $('.search-suggested-recipes').html(response.search);
                       $(".search-header-autosuggest__suggestions-container").show();
                   }
               });
           } else {
               $(".search-header-autosuggest__suggestions-container").hide();
           }
           if (value.length == 0) {
               $(".btn--link").hide();
               $(".search-submit-btn").removeClass("search-history");
           }
          }
   
       });

     
   
       $(".btn--link").click(function() {
           $(".search-header-autosuggest__suggestions-container").hide();
           $(".search-submit-btn").removeClass("search-history");
           $(".btn--link").hide();
           $('.search-wd').val('');
       });

       $('.search-submit-btn').click(function(){
          filterChange();
       });
        $(document).on('click','.include-btn-submit',function(){
            filterChange();

       });

       $(document).on('click','.exclude-btn-submit',function(){
            filterChange();

       });
       $(document).on('keypress','.include-ingr',function(e){
            if(e.keyCode == 13) {
                 filterChange();
            }
        });

        $(document).on('keypress','.exclude-ingr',function(e){
            if(e.keyCode == 13) {
                filterChange();
            }
        });
        $(document).on('keypress','.search-height',function(e){
            if(e.keyCode == 13) {
              // $('.search-header-autosuggest__suggestions-container').css('display','none');
                filterChange();
            }
        });
        
   
       /* end */
   });
   $(document).ready(function() {
      // $('.clear-data').click(function() {
        $(document).on('click','.clear-data',function(){
          $(":checkbox").attr("checked", false);
          filterChange();
     });

    $(".btn--link").hide();
    let searchParams = new URLSearchParams(window.location.search);
    let param = searchParams.has('search') ;
    var search_val = searchParams.get('search');
    $("#mob-search").attr('value',search_val);
    console.log(param,'search_val',search_val);
    if(param && search_val.length > 0){
          $(".search-submit-btn").addClass("search-history");
          $(".btn--link").show();   
      }
    // 
       $(".clear-data").click(function() {
           $(".filters-options").hide();
           $(".clear-data").hide();
       });
   
      //  $('.sub-cat-filter-tag-remove').click(function() {
        $(document).on('click','.sub-cat-filter-tag-remove',function(){
           var sub_cat = $(this).data('id');
           $('input[value="' + sub_cat + '"]').click();
       });

        // $('.recipe_cat-filter-tag-remove').click(function() {
        $(document).on('click','.recipe_cat-filter-tag-remove',function(){
           var recipe_cat = $(this).data('id');
           console.log('recipe_cat', recipe_cat);
           $('input[name="recipe_tags[]"][value="' + recipe_cat + '"]').click();
       });

       /* new  */
   
   });
   function removeInclude(){
         if($('input[name="include[]"]').val()){
            $('input[name="include[]"]').val('');
          }
      }
   function removeExclude(){
         if($('input[name="exclude[]"]').val()){
            $('input[name="exclude[]"]').val('');
          }
      }

  function filterChange(){
      var filterTags = []
      $('[name="filter_tags[]"]').each(function() {
         if($(this).is(':checked')){
            filterTags.push($(this).val());
         }
      });
      var recipeTags = []
      $('[name="recipe_tags[]"]').each(function() {
         if($(this).is(':checked')){
          recipeTags.push($(this).val());
         }
      });
       var includeTags = [];
        $('[name="include[]"]').each(function() {
              if($(this).val()){
                includeTags.push($(this).val());
              }
              
        });
        var excludeTags = [];
        $('[name="exclude[]"]').each(function() {
              if($(this).val()){
                excludeTags.push($(this).val());
              }
              
        });
        data = {};
        var type = 'Meal';
        var url = public_url+'meal-planner/calendar/meallist';
        var category = $('#category-name').val();
        var search = $('.search-wd').val();
        data = {category_type:category, filter_tags:filterTags, recipe_tags:recipeTags,include:includeTags,exclude:excludeTags,search:search};
        $.getJSON(url,data, function(response){
            // console.log('response', response);
            if(response.status == 'success'){
               $( "#filtermodal .close" ).click();
                $('.search-header-autosuggest__suggestions-container').css('display','none');
                displayList(type, response.data, response, category);
              
            }
          });
     
    }
</script>
<script type="text/javascript">
   $(document).ready(function() {
   
       $('html, body').animate({
           scrollTop: 0
       }, 100);
       return false;
   
   });
</script>
@stop
