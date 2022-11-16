{{-- <div class="filter-data"> --}}

    @if (count($sub_cat_name) > 0)
       @foreach ($sub_cat_name as $cat)
          <div class="alert alert-success alert-dismissible filters-options">
             <a data-id={{ $cat->id }} class="close sub-cat-filter-tag-remove"
             data-dismiss="alert" aria-label="close">&#10005;</a>
             {{ $cat->name }}
          </div>
       @endforeach
    @endif
    @if (count($recipe_cat_name) > 0)
    @foreach ($recipe_cat_name as $recipe_cat)
       <div class="alert alert-success alert-dismissible filters-options">
          <a data-id={{ $recipe_cat->id }} class="close recipe_cat-filter-tag-remove"
          data-dismiss="alert" aria-label="close">&#10005;</a>
          {{ $recipe_cat->name }}
       </div>
     @endforeach
   @endif
    {{-- <a href="{{ route('recipes.list') }}" class="clear-data">Clear all</a> --}}
    @if(count($recipe_cat_name) > 0 || count($sub_cat_name) > 0)
    <button class="clear-data">Clear all</button>
    @endif
 {{-- </div> --}}