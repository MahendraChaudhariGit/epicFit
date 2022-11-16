@extends('blank')

@section('required-styles')

@stop
    
@section('page-title')
  <div class="col-md-8">
    Category List
  </div>
  <div class="col-md-4" align="right">
    <a class="btn btn-primary" href="{{ route('add.gallery.category') }}"><i class="fa fa-plus"></i> Add Category</a>
  </div>
@stop

@section('content')
{!! displayAlert()!!}
  <!-- start: Delete Form -->
  {{-- @include('includes.partials.delete_form') --}}
  <!-- end: Delete Form --> 

<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <!-- start: Datatable Header -->
            {{-- @include('includes.partials.datatable_header') --}}
            <!-- end: Datatable Header -->
        </div>
        @if(session()->has('msg'))
            <div class="px-6">
                <div class="alert alert-dismissable alert-success">
                    <span> {!! session()->get('msg') !!} </span>
                    <button type="button" class="flex items-center p-1 focus:outline-none ml-auto" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        @endif
        <table class="table table-striped table-bordered table-hover m-t-10" id="mp-datatable">
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Category Name</th>
                    {{-- <th class="center">Actions</th> --}}
                </tr>
            </thead>
            <tbody>
              @if(count($categories) > 0)
              @foreach($categories as $key => $category)
                <tr>
                  <td> {{ $key+1 }} </td>
                  <td> {{ $category->cat_name }} </td>
                  {{-- <td class="center">
                    <div>
                      <a class="btn btn-xs btn-default tooltips" href="{{ route('edit.gallery.category', $category->id) }}" >
                          <i class="fa fa-pencil" style="color:#253746;"></i>
                      </a>
                      <a class="btn btn-xs btn-default tooltips delete-cat" data-id="{{ $category->id }}" cat-img="{{ count($category->gallery_category_list) }}">
                          <i class="fa fa-trash-o" style="color:#253746;"></i>
                      </a>
                    </div>
                  </td> --}}
                </tr> 
              @endforeach
              @else
                <tr>
                  <td colspan="3" class="text-center">
                    No data found
                  </td>
                </tr>
              @endif
            </tbody>
        </table>
        <!-- start: Paging Links -->
        {{-- @include('includes.partials.paging', ['entity' => $categories]) --}}
        <!-- end: Paging Links --> 
    </div>
</div>
@stop
@section('script')
  {!! Html::script('assets/js/helper.js') !!}

  <script>
      $(".delete-cat").click(function(){
        var id = $(this).attr('data-id')
        var cat_img = $(this).attr('cat-img')
        if(cat_img > 0){
          var title = 'There are images associated with this category. Deleting category will delete all images associated with this category. Are you sure you want to delete this category?';
        }else{
          var title = 'Are you sure, you want to delete this category?'
        }
        swal({
            title: title,
            type: 'warning',
            allowEscapeKey: false,
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText:'No',
            confirmButtonColor: '#ff4401'
            },
            function(isConfirm) {
            if(isConfirm) 
            {  
                
                var site_url = 'delete/category/'+id;
                $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('#token').val()
                },
                type: "get",
                url: site_url,
                success: function(data) {
                    window.location.reload();
                }
                }); 
            } 
            else
            {  
               return false;
            }
            });  
      })
  </script>
@stop
