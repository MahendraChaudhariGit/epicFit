@extends('blank')
@section('required-styles')
    
@stop
    
@section('page-title')
  <div class="col-md-8">
    Ingredient List
  </div>
  <div class="col-md-4" align="right">
    <a class="btn btn-primary" href="{{ route('food.create') }}"><i class="fa fa-plus"></i> Add Ingredient</a>
    <label class="btn btn-primary btn-file">
      <span><i class="fa fa-upload"></i> &nbsp;Select excel/csv file</span> 
      <input type="file" class="hidden" onChange="excelFileHandler(this)" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
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
            @include('includes.partials.datatable_header')
            <!-- end: Datatable Header -->
        </div>
        <table class="table table-striped table-bordered table-hover m-t-10" id="mp-datatable">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Ingredient Name</th>
                    <th>Ingredient Description</th>
                    <th class="center">Actions</th>
                </tr>
            </thead>
            <tbody>
              @foreach($foods as $foodInfo)
                <tr>
                  <td>
                      <img src="{{ dpSrc($foodInfo->food_img)}}" alt="{{ $foodInfo->name }}" class="mw-50 mh-50" />
                  </td>
                  <td>
                    {{ $foodInfo->name ?? '' }}
                  </td>
                  <td>
                    {{ $foodInfo->description ?? '' }}
                  </td>
                  <td class="center">
                    <div>
                      <a class="btn btn-xs btn-default tooltips" href="{{ route('food.edit', $foodInfo->id) }}" data-placement="top" data-original-title="Edit">
                          <i class="fa fa-pencil" style="color:#253746;"></i>
                      </a>
                      <a class="btn btn-xs btn-default tooltips delLink" href="{{ route('food.destroy', $foodInfo->id) }}" data-placement="top" data-original-title="Delete" data-entity="Food">
                          <i class="fa fa-trash-o" style="color:#253746;"></i>
                      </a>
                    </div>
                  </td>
                </tr> 
              @endforeach
            </tbody>
        </table>
        <!-- start: Paging Links -->
        @include('includes.partials.paging', ['entity' => $foods])
        <!-- end: Paging Links --> 
    </div>
</div>
@stop
@section('script')
  {!! Html::script('assets/js/helper.js') !!}
  <script>
    var cookieSlug = "foodplanner";
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
