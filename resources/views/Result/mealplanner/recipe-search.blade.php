 <ul class="listbox search-header-autosuggest__suggestions-container" role="listbox">
     <li class="listbox__item" role="option" tabindex="0">
         <strong>{{$value}}</strong>
     </li>
     <li class="listbox__item">
         <h5 class="search-header__results-header">Suggested Recipes</h5>
     </li>
     @foreach($meals as $meal)
     <li class="listbox__item" role="option" tabindex="0">
         <a class="search-header__result css-0" href="{{ route('recipes.details', $meal->id) }}">
             <div class="search-header__result-img">
                 <div class="css-1odkioe">
                     <div class="css-1kdubnn">
                          <img src="{{dpSrc($meal->mealimages()->pluck('mmi_img_name')->first())}}"
                            onerror="this.onerror=null;this.src='{{asset('assets/images/no-image.jpg')}}';" alt="Lemon Basil Sherbet" class="  css-prwkze-img" width="45" height="45">
                       
                     </div>
                 </div>
             </div>
             <div class="search-header__result-body css-0">
                 <span>{{$meal->name}}</span>
           
             </div>
         </a>
     </li>
     @endforeach
    
 </ul>
