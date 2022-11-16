@extends('super-admin.layout.master')
    
@section('page-title')
  <div class="col-md-8">
    Gallery
  </div>
  {{-- <div class="col-md-4" align="right">
    <a class="btn btn-primary" href="{{ route('add.gallery.category') }}"><i class="fa fa-plus"></i> Add Category</a>
  </div> --}}
@stop

@section('content')
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
        {{-- @if(session()->has('msg'))
            <div class="px-6">
                <div class="alert alert-dismissable alert-success">
                    <span> {!! session()->get('msg') !!} </span>
                    <button type="button" class="flex items-center p-1 focus:outline-none ml-auto" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        @endif --}}
        <div class="row">
            @if(count($categories) > 0)
            @foreach($categories as $key => $category)
            
            <div class="col-sm-2">
                <div class="panel panel-white no-radius text-center">
                    <div class="panel-body">
                        <a href="{{ route('superadmin.images', ['id' => $category->id]) }}">
                            <span class="fa-stack fa-2x">
                            <i class="fa fa-square fa-stack-2x text-primary"></i>
                            <i class="fa fa-image fa-stack-1x fa-inverse"></i>
                            </span>
                            <h5>{{ $category->cat_name }}</h5>
                        </a>
                        <a class="btn btn-xs btn-default tooltips edit-category" data-id="{{ $category->id }}" data-name="{{ $category->cat_name }}" href="javascript:void(0)" data-type="edit" >
                          <i class="fa fa-pencil" style="color:#253746;"></i>
                        </a>
                        <a class="btn btn-xs btn-default tooltips delete-cat" data-id="{{ $category->id }}" cat-img="{{ count($category->gallery_category_list) }}">
                          <i class="fa fa-trash-o" style="color:#253746;"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            @endforeach
            @else
            <div class="col-sm-12 text-center">
               <h3> Category not found </h3> 
            </div>
          @endif
        </div>
              
        <!-- start: Paging Links -->
        {{-- @include('includes.partials.paging', ['entity' => $categories]) --}}
        <!-- end: Paging Links --> 
    </div>
</div>

 <!-- Modal -->
 <div class="modal" id="sub-category-popup" role="dialog" style="display: none">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close-popup" data-dismiss="modal">&times;</button>
        <h4 class="modal-title title-name">Add Sub Category</h4> 
      </div>
      <form action="{{ route('superadmin.save.subcategory') }}" method="post">
        @csrf
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <input type="hidden" name="cat_id" value="" class="cat-id">
              <input type="hidden" name="data_type" value="" class="data-type">
              <div class="col-md-4 col-md-offset-4 form-group">
                <label for="cat_name" class="strong">Category Name *</label>
                <span class="epic-tooltip" data-toggle="tooltip" title="This is tooltip"><i class="fa fa-question-circle"></i></span>
                <input type="text" name="cat_name" id="cat_name" class="form-control" value="" required>
                @error('cat_name')
                <span class="text-danger"> {{ $message }}</span>
                @enderror
              </div>
            </div>  
            <div class="col-md-12 text-center">
              {{-- @if(isset($category))
                <button type="submit" class="btn btn-primary btn-wide"> <i class="fa fa-edit"></i> Update Category</button>
                @else --}}
                <button type="submit" class="btn btn-primary"> Save </button>
                {{-- @endif --}}
              </div>   
            </div>
          </div>
          {{-- <div class="modal-footer">
            <button type="button" class="btn btn-default close-popup" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" data-dismiss="modal">Save</button>
          </div> --}}
        </form>
      </div>

    </div>
  </div> 
    <!-- Modal -->
    <div class="modal" id="delete-category-popup" role="dialog" style="display: none">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close close-popup" data-dismiss="modal">&times;</button>
            <h4 class="modal-title title-name">Delete Category</h4> 
          </div>
          {{-- <form  method="post">
            @csrf --}}
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <h4 class="text-center" id="title"></h4>
                  <h4 class="text-center">if "Yes" then fill this code "<span id="code"></span>" in this field.</h4>
                  {{-- <input type="hidden" name="cat_id" value="" class="cat-id">
                  <input type="hidden" name="data_type" value="" class="data-type"> --}}
                  <div class="col-md-4 col-md-offset-4 form-group">
                    <label for="password" class="strong">Code *</label>
                    <span class="epic-tooltip" data-toggle="tooltip" title="This is tooltip"><i class="fa fa-question-circle"></i></span>
                    <input type="text" name="password" id="password" class="form-control" value="">
                    <span class="text-danger password-error"> </span>
                  </div>
                </div>  
                {{-- <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-primary"> Save </button>
                  </div>    --}}
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default close-popup" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="delete-category" data-dismiss="modal">ok</button>
              </div>
            {{-- </form> --}}
          </div>

        </div>
      </div>   
  {{-- {!! Html::script('assets/js/helper.js') !!} --}}
  <script>
    $(document).on('click','.edit-category', function(){
    var cat_id = $(this).attr('data-id');
    var data_type = $(this).attr('data-type');
    var data_name = $(this).attr('data-name');
    $('.cat-id').val(cat_id);
    $('.data-type').val(data_type);
    $('#cat_name').val(data_name);
    $('.title-name').html('Edit Category');
    $('#sub-category-popup').show();
  })
  
  $(document).on('click','.close-popup',function(){
    $('#sub-category-popup').hide();
    $('#delete-category-popup').hide();
  })

  $(document).on('click',".delete-cat",function(){
    $(".password-error").html('')
    $("#password").val('');
    var randomstring = Math.random().toString(36).slice(-8);
   
    var id = $(this).attr('data-id')
    var cat_img = $(this).attr('cat-img')
    if(cat_img > 0){
      var title = 'There are images associated with this category. Deleting category will delete all images associated with this category. Are you sure you want to delete this category?';
    }else{
      var title = 'Are you sure, you want to delete this category?'
    }
    $("#title").html(title);
    $("#code").html(randomstring);
    $("#delete-category-popup").show()
    $(document).on('click','#delete-category',function(){
      var password = $("#password").val();
      if(password != ''){
        if(password != randomstring){
          $(".password-error").html('wrong code')
        }else{
          var site_url = '{{ url("epic-super-admin/delete/category") }}/'+id;
          $.ajax({
            headers: {
              'X-CSRF-TOKEN': $('#token').val()
            },
            type: "get",
            url: site_url,
            success: function(data) {
              swal({
                  title: "Category deleted successfully",
                  icon: "success",
              })
              .then((isConfirm) => {
                if (isConfirm) {
                  window.location.reload();
                }
              });
              
            }
          });
        }
      }else{
        $(".password-error").html('password field is required')
      }
    })
  })
  </script>
@stop
