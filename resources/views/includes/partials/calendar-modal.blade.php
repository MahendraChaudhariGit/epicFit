<div class="prepation_box">
    <h2><img src="{{asset('assets/images/ingrediant-icon.png')}}"> Ingredients</h2>
    <!-- <div id="ingredientPara">
    </div> -->
    <ul>
        @php
        if($meals->ingredient_set_no != 1){
            $set_name = json_decode($meals->ingredient_set_name);
        }
       @endphp

        @if(($meals->ingredient_set_no != 1) && isset($set_name))
             <li class="recipe__list-subheading">{{$set_name->set_name_1}}:</li>
        @endif
        @if(count($meals->mealIngredientSetPart1) > 0)
            @foreach($meals->mealIngredientSetPart1 as $ingr_val_1)
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
          @if(($meals->ingredient_set_no != 1) && isset($set_name))
             <li class="recipe__list-subheading">{{$set_name->set_name_2}}:</li>
          @endif
          @if(count($meals->mealIngredientSetPart2) > 0)
              @foreach($meals->mealIngredientSetPart2 as $ingr_val_2)
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
        @if(($meals->ingredient_set_no == 3) && isset($set_name))
        <p class="recipe__list-subheading">{{$set_name->set_name_1}}</p>
        @endif
        <ol>
          @if(count($meals->mealPreparationPart1) > 0)
            @foreach($meals->mealPreparationPart1 as $key=> $prep_part1)
                <li class="">
                  {{ $prep_part1['description'] }} 
                </li>
            @endforeach
         @endif
        </ol>

         @if(($meals->ingredient_set_no == 3) && isset($set_name))
         <p  class="recipe__list-subheading">{{$set_name->set_name_2}}</p>
          @endif
        <ol>
       
        @if(count($meals->mealPreparationPart2) > 0)
            @foreach($meals->mealPreparationPart2 as $key=> $prep_part2)
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