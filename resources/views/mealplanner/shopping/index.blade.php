<?php
if(count($shoppingNewData)){
  $result=[];
  $matchRec = [];

 foreach ($shoppingNewData as $key => $value) {
  $isMatch = false;
  if(!array_key_exists($key,$matchRec)){
  $matchRec[$key] = $value;
  $recName =explode(' ', $value->rec_name);
 
   foreach ($recName as  $rec) {
     if($isMatch==false){
      foreach ($shoppingNewData as $key1 => $data) {
        if($key1 != $key){
            if (strpos($data->rec_name,  $rec) !== false) {
              if(!array_key_exists($key1,$matchRec)){
                $matchRec[$key1] = $data;
                $isMatch = true;
              }
              
            }
        }
      }   
    }else{
      break;
    }
   }
  }
  
  // $result[metaphone( $value->rec_name, 4)][] = $value->rec_name;

 }
}
?>
@extends('Result.masters.app')

@section('required-styles') 
    {!! Html::style('assets/plugins/Jcrop/css/jquery.Jcrop.min.css') !!}
    {!! Html::style('assets/plugins/fullcalendar-2.9.1/fullcalendar.min.css') !!}
    {!! Html::style('vendor/bootstrap-select-master/css/bootstrap-select.min.css') !!}
    {!! Html::style('vendor/sweetalert/sweet-alert.css') !!}
    {!! Html::style('result/css/custom.css?v='.time()) !!}
    <style type="text/css">
      .nav-tabs > li.active a, .nav-tabs > li.active a:hover, .nav-tabs > li.active a:focus
      {
        border-width: 1px;
      }
      .checkbox-inline{
        margin: 0px !important;
        display: flex;

      }
      #shopping-list table td, #purchased-items table td {
        padding: 5px;
      }
      .nav-tabs > .active a, .nav-tabs > .active a:hover, .nav-tabs > .active a:focus{
        box-shadow: none;
      }
      .btn-primary {
    background-color: #f94211;
    border-color: #f94211;
    color: #ffffff;
    font-size: 14px;
    font-weight: 500;
    float: left;
    margin-right: 10px;
    margin-top: 10px;
    min-width: 144px;
}
    </style>
@stop

@section('page-title')
    Shopping List
@stop

@section('content')
{!! displayAlert()!!}
  <!-- start: Delete Form -->
  @include('includes.partials.delete_form')
  <!-- end: Delete Form --> 


<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#shopping-list">Shopping list</a></li>
  <li><a data-toggle="tab" href="#purchased-items">Purchased items</a></li>
</ul>

  
<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12 p-0">
              <input type="hidden" name="startDate" value="{{$startDate}}">
              <input type="hidden" name="endDate" value="{{$endDate}}">
              <input type="hidden" name="clientId" value="{{$clientId}}">
              <div class="tab-content">
    
            <div id="shopping-list" class="tab-pane fade in active">
                <h3>Shopping list</h3>
              <table class="table table-hover">
                <tbody>
                  @if(count($matchRec))
                  <table>
                    <tr>
                      <td><h4>Name</h4></td>
                      <td><h4>Quantity</h4></td>
                   
                    <td><h4>Recipe</h4></td>
                    </tr>
                    <tr>
                      @if(count($onlyShoppingList))
                        <td>
                          <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0" style="font-weight: 600;">
                            <input type="checkbox" class="shoppingValue" id="select-all" > 
                          
                                <label for="select-all"></label>
                            
                              Select All
                          </div>
                        </td>
                      @endif
                      <td></td>
                      <td></td>
                    </tr>

                    @foreach($matchRec as $key => $shoppinglist)
                    {{-- @if($shoppinglist->puchased_quan != $shoppinglist->quantity && $shoppinglist->quantity != null && $shoppinglist->quantity != 0) --}}
                   @if($shoppinglist->purchased_date == null)
                      <tr>
                      
                        <td>
                          <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                           {{-- <input type="checkbox" class="shoppingValue selectedId" @if(isset($shoppinglist->quantity) && ){{$shoppinglist->puchased_quan == $shoppinglist->quantity?'checked' :''}} @endif id="sl_{{$shoppinglist->id}}" data-id="{{$shoppinglist->id}}" value="{{$shoppinglist->rec_name}}" data-quantity="{{$shoppinglist->quantity}}" />  --}}
                           <input type="checkbox" class="shoppingValue selectedId" id="sl_{{$shoppinglist->id}}" data-id="{{$shoppinglist->id}}" value="{{$shoppinglist->rec_name}}" data-quantity="{{$shoppinglist->quantity}}" /> 
                        
                              <label for="sl_{{$shoppinglist->id}}"></label>
                          
                              {{$shoppinglist->rec_name}}
                        </div>
                       </td> 
                       @if($shoppinglist->quantity == 0)
                         <td></td>
                        @else
                          @if(strpos($shoppinglist->quantity, 'g') !== false)
                          <td>{{$shoppinglist->puchased_quan != null ? $shoppinglist->quantity - $shoppinglist->puchased_quan.'g' : $shoppinglist->quantity}}</td>
                          @else
                          <td>{{$shoppinglist->puchased_quan != null ? $shoppinglist->quantity - $shoppinglist->puchased_quan : $shoppinglist->quantity}}</td>
                          @endif
                        @endif
                          <td>
                            <a href="javascript:void(0)" class="viewRecipe" data-recipe-name="{{$shoppinglist->meal_recipe_name}}">View recipe</a>
                          </td>
                       </div>
                   
                      </tr>
                      @endif
                  {{-- @endif --}}
             
                 @endforeach
                  </table>
                  <button type="button" style="display:block;" class="btn btn-primary" id="UpdateShoppingList">Mark as purchased</button>
                  <button type="button" style="/* display:block; */background: white;color: #f94211;" class="btn btn-primary" id="deleteShoppingList">Delete</button>

                  @else
                  <tr>
                    <td>
                      <div>
                        <p> No record found.</p>
                      </div>
                    </td>
                  </tr>
                  @endif
                </tbody>
              </table>
            </div>
            <div id="purchased-items" class="tab-pane fade">
              <h3>Purchased items</h3>
              <table class="table table-hover">
                <tbody>
                @if(count($matchRec))
                 
                  <table>
                    <tr>
                      <td><h4>Name</h4></td>
                    
                    <td><h4> Quantity</h4></td>
                    <td><h4> Date</h4></td>

                    </tr>

                    @foreach($matchRec as $key => $shoppinglist)
                      @if($shoppinglist->puchased_quan != null)
                      <tr>

                      <td> {{$shoppinglist->rec_name}}</td> 
                      <td style="
                      padding: 10px;
                  ">{{ $shoppinglist->puchased_quan }}</td>
                     <td style="
                      padding: 10px;
                  ">{{$shoppinglist->purchased_date }}</td>
                    </tr>

                      @endif
                     
                    @endforeach
                  </table>
                  
                  @else
                  <tr>
                    <td>
                      <div>
                        <p> No record found.</p>
                      </div>
                    </td>
                  </tr>
                  @endif
                </tbody>
              </table>
            </div>
            </div>
        </div>
        </div>
        {{-- <div class="row">
          <div class="col-md-12">
            <button class="btn btn-primary btn-wide"><i class="fa fa-envelope-o"></i> Email</button>
            <button class="btn btn-primary btn-wide"><i class="fa fa-print"></i> Print</button>
            <button class="btn btn-primary btn-wide"><i class="fa fa-file-pdf-o"></i> Saved as PDF</button>
            <button class="btn btn-primary btn-wide"><i class="fa fa-share-square-o"></i> Share</button>
          </div>
        </div> --}}
    </div>
</div>




<div class="modal" id="recipe-modal" role="dialog">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                  <span aria-hidden="true">
                      ×
                  </span>
              </button>
              <h4 class="modal-title">
                  Recipe List
              </h4>
          </div>
          <div class="modal-body bg-white">
              <form id="recurSessionDeleteFormProRate" method="POST">
                  <input type="hidden" name="sessionType" value="">
                  <div class="row">
                      <div class="col-md-12">
                         
                          <div class="form-group">
                          
                              <ul width="100%" class="showRecipe">
                                 
                              </ul>
                          </div>
                      </div>
                  </div>
              </form>
          </div>

       
          <div class="modal-footer">
              <button class="btn btn-default" data-dismiss="modal" type="button">
                  Close
              </button>
             
          </div>
      </div>
  </div>
</div>
@stop
@section('script')
{!! Html::script('vendor/jquery-validation/jquery.validate.min.js') !!}
{!! Html::script('assets/js/validator-helper.js?v='.time()) !!}
{!! Html::script('vendor/bootstrap-select-master/js/bootstrap-select.min.js') !!}
{!! Html::script('vendor/tooltipster-master/jquery.tooltipster.min.js') !!}
{!! Html::script('assets/plugins/fullcalendar-2.9.1/fullcalendar.min.js') !!}
{!! Html::script('assets/plugins/Jcrop/js/jquery.Jcrop.min.js') !!}
{!! Html::script('assets/plugins/Jcrop/js/script.js') !!}
{!! Html::script('assets/plugins/bootstrap3-typeahead.min.js') !!}
{!! Html::script('assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js') !!}
{!! Html::script('vendor/sweetalert/sweet-alert.min.js') !!}

{!! Html::script('assets/js/helper.js?v='.time()) !!}
{!! Html::script('assets/js/shopping-list.js?v='.time()) !!}

<script type="text/javascript">
    $(document).ready(function() {
         $('#select-all').click(function () {
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
                })   
            });

          $('#deleteShoppingList').click(function(){
               var checked_array = [];
                $('.selectedId').each(function(){
                    var checked = $(this).attr('data-id');
                    if ($(this).is(':checked')) {
                        checked_array.push(checked);
                      }
                });
                console.log('checked_array', checked_array);
                if(checked_array.length > 0){
                  var delete_msg = confirm('Are you sure you want to delete this item?');
                    if(delete_msg) { 
                       $.post(public_url+'meal-planner/delete-shopping-list', {id:checked_array}, function(data){ 
                         console.log(data, data.status);
                         if(data.status == 'success'){
                              location.reload();
                         } 
                      })
                  }       
              }
          });

         

            /* end */
    });
      
  </script>
@stop
