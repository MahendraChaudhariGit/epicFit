@extends('blank')
@section('required-styles')
<style type="text/css">
.dropdown-toggle,.search-wd.search-height{
  border-radius: 0px !important;
  height: 34px !important;
}
.search-submit-btn{
  margin-top: 0px !important;
}
@media(max-width: 767px){
  .f-left{
    text-align: left;
  }
}
#viewmealsmodal .recipe__list-subheading{
 list-style: none;
    padding-left: 17px;
}
</style>
@stop
 
@section('page-title')
    <div class="col-md-8">
      Recipe List
    </div>
    <div class="col-md-4 f-left" align="right">
      <a class="btn btn-primary" href="{{ route('meals.create') }}"><i class="ti-plus"></i> Add Recipe</a>
      <label class="btn btn-primary btn-file">
        <span><i class="fa fa-upload"></i> &nbsp;Select csv file</span> 
        <input type="file" data-import-type="meal" class="hidden" onChange="excelFileHandler(this)" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel,.xlsx,.xls">
      </label>
    </div>
@stop

@section('content')
{!! displayAlert()!!}
  <!-- start: Delete Form -->
  @include('includes.partials.delete_form')
  <!-- end: Delete Form --> 

<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <!-- start: Datatable Header -->
            @include('includes.partials.datatable_header',['source' => 'meal','mealCategories'])
            <!-- end: Datatable Header -->
        </div>
        <table class="table table-striped table-bordered table-hover m-t-10" id="mp-datatable">
          <thead>
              <tr>
                  <th class="mw-70 w70">Photo</th>
                  <th>Recipe Name</th>
                  <th>Recipe Description</th>
                  <th>Recipe Category</th>
                  <th>Staff</th>
                  <th class="center">Actions</th>
              </tr>
          </thead>
          <tbody>

            @if($meals->count())
            @foreach($meals as $mealInfo)
              <tr class="meal-row">
                <td class="mw-70 w70">
                  <?php 
                    $mealImg = $mealInfo->mealimages()->pluck('mmi_img_name')->first();
                  ?>
                  <img src="{{ dpSrc($mealImg) }}" alt="{{ $mealInfo->mealname }}" class="mw-50 mh-50"/>
                </td>
                <td>
                  {{ $mealInfo->name ?? ''}}
                </td>
                <td>
                  {!! $mealInfo->description ?? '' !!}
                </td>
                <td>
                  <?php
                    $catName = '';
                    if(count($mealInfo->categories)){
                      $i = 0;
                      foreach ($mealInfo->categories as $mealcat) {
                        if($i == 0)
                          $catName .= $mealcat->name;
                        else
                          $catName .= ', '.$mealcat->name;
                        $i++;
                      }
                    }
                  ?>
                  {{ $catName }}
                </td>
                <td>{{$mealInfo->staff->fullName ?? '--'}}</td>
                <td class="center">
                  <div>
                    <button class="btn btn-xs btn-default tooltips viewModal" data-id="{{$mealInfo->id}}">
                        <i class="fa fa-share text-primary" style="color:#253746;"></i>
                    </button>
                    <a class="btn btn-xs btn-default tooltips " href="{{ route('meals.download', $mealInfo->id) }}" data-placement="top" data-original-title="Download">
                      <i class="fa fa-download" style="color:#253746;"></i>
                    </a>
                    <a class="btn btn-xs btn-default tooltips " href="{{ route('meals.edit', $mealInfo->id) }}" data-placement="top" data-original-title="Edit">
                        <i class="fa fa-pencil" style="color:#253746;"></i>
                    </a>
                    <a class="btn btn-xs btn-default tooltips delLink" href="{{ route('meals.destroy', $mealInfo->id) }}" data-placement="top" data-original-title="Delete" data-entity="Meal">
                        <i class="fa fa-trash-o" style="color:#253746;"></i>
                    </a>
                  </div>
                </td>
              </tr> 
            @endforeach
            @endif 
          </tbody>
        </table>
        <!-- start: Paging Links -->
        @include('includes.partials.paging', ['entity' => $meals])
        <!-- end: Paging Links -->
    </div>
</div>

<!-- Start:Recipe Details Modal -->
<div class="modal fade" id="viewmealsmodal" role="dialog">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="close m-t-10" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title">Recipe Details</h4>
          </div>
          <div class="modal-body bg-white">
            <div class="breakfast_view">
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
                            {{-- <h4>Prep<br>Time</h4> --}}
                            <h4>Time</h4>
                            <div class="right_hd">
                              <span id="preprationTimeHrs" class="value"></span>
                              <span class="time-hrs"  style="display: none">Hour</span>
                              <span id="preprationTime" class="value"></span>
                              <span class="time-min" style="display: none">Minutes</span>
                            </div>
                          {{-- <div class="right_hd">
                              <span id="preprationTime" class="value"></span>
                              <span>Minutes</span>
                          </div> --}}
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
                  {{-- <div class="prepation_box">
                      <h2><img src="{{asset('assets/images/ingrediant-icon.png')}}"> Ingredients</h2>
                      <ul>
            
                                <li class="recipe__list-subheading">Whole Oregano</li>
                                <li>
                                  <span style="display: inline-block;">
                                    <span class="">
                                      <span>2</span> Whole
                                    </span>
                                    Oregano<br>
                                  </span>         
                                </li>
                                  <li>
                                  <span style="display: inline-block;">
                                    <span class="">
                                      <span>2</span> Whole
                                    </span>
                                    Oregano<br>
                                  </span>         
                                </li> <li>
                                  <span style="display: inline-block;">
                                    <span class="">
                                      <span>2</span> Whole
                                    </span>
                                    Oregano<br>
                                  </span>         
                                </li> <li>
                                  <span style="display: inline-block;">
                                    <span class="">
                                      <span>2</span> Whole
                                    </span>
                                    Oregano<br>
                                  </span>         
                                </li>
                                </ul>
                                 <ul>
            
                                <li class="recipe__list-subheading">Whole Oregano</li>
                                <li>
                                  <span style="display: inline-block;">
                                    <span class="">
                                      <span>2</span> Whole
                                    </span>
                                    Oregano<br>
                                  </span>         
                                </li>
                                  <li>
                                  <span style="display: inline-block;">
                                    <span class="">
                                      <span>2</span> Whole
                                    </span>
                                    Oregano<br>
                                  </span>         
                                </li> <li>
                                  <span style="display: inline-block;">
                                    <span class="">
                                      <span>2</span> Whole
                                    </span>
                                    Oregano<br>
                                  </span>         
                                </li> <li>
                                  <span style="display: inline-block;">
                                    <span class="">
                                      <span>2</span> Whole
                                    </span>
                                    Oregano<br>
                                  </span>         
                                </li>
                         </ul>
                      <div id="ingredientPara">
                      </div>
                  </div>
                  <div class="prepation_box">
                      <h2><img src="{{asset('assets/images/preparation-icon.png')}}"> Preparation</h2>
                        <p class="recipe__list-subheading">{{$set_name->set_name_1}}</p>
                      <ol>
                           <li class="">
                            Prepare grill for medium heat.  
                          </li>
                                                <li class="">
                            Mix oregano, sesame seeds, cumin, salt, and red pepper flakes in a small bowl to combine; set spice mixture aside. 
                          </li>
                                                <li class="">
                            Beginning and ending with salmon, thread salmon and folded lemon slices onto 8 pairs of parallel skewers to make 8 kebabs total.  
                          </li>
                                                <li class="">
                            Brush with oil and season with reserved spice mixture. 
                          </li>
                                                <li class="">
                            Grill, turning occasionally until fish is opaque throughout, 5-8 minutes. 
                          </li>
                      </ol>
                        <p class="recipe__list-subheading">{{$set_name->set_name_1}}</p>
                      <ol>
                           <li class="">
                            Prepare grill for medium heat.  
                          </li>
                                                <li class="">
                            Mix oregano, sesame seeds, cumin, salt, and red pepper flakes in a small bowl to combine; set spice mixture aside. 
                          </li>
                                                <li class="">
                            Beginning and ending with salmon, thread salmon and folded lemon slices onto 8 pairs of parallel skewers to make 8 kebabs total.  
                          </li>
                                                <li class="">
                            Brush with oil and season with reserved spice mixture. 
                          </li>
                                                <li class="">
                            Grill, turning occasionally until fish is opaque throughout, 5-8 minutes. 
                          </li>
                      </ol>
                      <div id="preparationData">
                      </div>
                      <br>
                      <h3><img src="{{asset('assets/images/preparation-icon.png')}}"> Tips</h3>
                      <div id="tipsData">
                      </div>
                  </div>                    --}}
              </div>
              <div class="bootom_area">
                      <h3><span>Calories:</span><span id="calories"></span></h3>
                      <p id="nutriData"></p>
                  </div>
            </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
              <!-- <button type="button" class="btn btn-primary submit">Done</button> -->
          </div>
      </div>
  </div>
</div>
<!-- End:Recipe Details Modal -->

@stop
@section('script')
  {!! Html::script('assets/js/helper.js') !!}
  {!! Html::script('assets/js/meal-planner.js') !!}

  <script>
    var cookieSlug = "mealplanner";
    $.fn.dataTable.moment('ddd, D MMM YYYY');
    $('#mp-datatable').dataTable({"searching": false, "paging": false, "info": false }); 
  </script>
  <script>
    /* uplode excel file */
    function excelFileHandler($this){
      toggleWaitShield("show");
      var elem = $($this),
          files = elem[0].files,
          formData = new FormData();

      $.each(files, function(key, value){
        formData.append(key, value);
      });
      formData.append('import-type',elem.data('import-type'));
      $.ajax({
        url: public_url+'excel-to-db',
        type: 'POST',
        data: formData,
        cache: false,
        dataType: 'json',
        processData: false, // Don't process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        success: function(response){
          toggleWaitShield("hide");
          uplodePopup(response.status, response.msg)
        }
      });
    }

    function uplodePopup(status, msg){
      swal({
        title: msg,
        type: (status=='success')?"success":"error",
        showCancelButton: false,
        confirmButtonColor: "#253746",
        confirmButtonText: "Ok",
        allowOutsideClick: true
      }, 
      function(){
        // for success
      });
    }
  </script>
@stop
