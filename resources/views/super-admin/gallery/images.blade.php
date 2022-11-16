@extends('super-admin.layout.master')

  @section('page-title')
 
  <div class="col-md-5">
    {{ $get_cat->cat_name }}
  </div>
  <div class="col-md-1" align="center">
    <a class="btn btn-primary go-back" href="javascript:void(0)" data-id="{{ $get_cat->id }}"> Go
    Back</a>
  </div>
  <div class="col-md-2" align="center">
    <a class="btn btn-primary delete-image" href="javascript:void(0)"> Delete
    Images</a>
  </div>
  <div class="col-md-2" align="center">
    <a class="btn btn-primary add-sub-category" href="javascript:void(0)" data-id="{{ $get_cat->id }}" data-user-id="{{ $get_cat->user_id }}" data-type="add"><i class="fa fa-plus"></i> Add
    Sub Category</a>
  </div>
  <div class="col-md-1" align="center">
    <a class="btn btn-primary" href="{{ route('superadmin.images.list', ['id' => $get_cat->id]) }}"><i class="fa fa-plus"></i> Add
    Images</a>
  </div>
  @stop

        @section('content')
        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/masonry/3.3.2/masonry.pkgd.js"></script>
        
        <style>
          * {
            box-sizing: border-box;
          }
          .grid {
            -webkit-column-count: 4;
            -moz-column-count:4;
            column-count: 4;
            -webkit-column-gap: 1em;
            -moz-column-gap: 1em;
            column-gap: 1em;
            padding: 0;
            -moz-column-gap: .2em;
            -webkit-column-gap: .2em;
            column-gap:.2em;
          }
          @media only screen and (max-width: 600px) {
           .grid{
            -moz-column-count: 1;
            -webkit-column-count: 1;
            column-count: 1;
          }
        }
        @media only screen and (min-width: 601px) and (max-width: 991px){
         .grid{
          -moz-column-count: 3;
          -webkit-column-count: 3;
          column-count: 3;
        }
        }
        .clip-check{
          position: absolute;
          left: 14px;
          top: 10px;
        }
        .grid-item {
          display: inline-block;
          position: relative;
          padding: .5em;
          margin: 0 0 1em;
          width: 100%;
          -webkit-transition:1s ease all;
        }
        
        .grid-item img {
          width: 100%;
        }
        
        .st-btn {
          display: inline-block !important;
        }
        
        .sharethis-inline-share-buttons {
          text-align: center !important;
          margin-bottom: 15px;
        }
        .imageShare {
          text-align: center !important;
          margin-bottom: 15px;
        }
        
        </style>
        <div class="container-fluid">
          @if (session()->has('msg'))
          <div class="px-6">
            <div class="alert alert-dismissable alert-success">
              <span> {!! session()->get('msg') !!} </span>
              <button type="button" class="flex items-center p-1 focus:outline-none ml-auto" data-dismiss="alert"
              aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        </div>
        @endif
        {{-- <h1 class="my-4 font-weight-bold">Masonry - images option</h1>
        --}}
        
        @if(count($sub_categories) > 0)
        <div class="row">
          @foreach($sub_categories as $key => $sub_category)
          
          <div class="col-sm-2">
            <div class="panel panel-white no-radius text-center">
              <div class="panel-body">
                <a href="{{ route('superadmin.images', ['id' => $sub_category->id]) }}">
                  <span class="fa-stack fa-2x">
                    <i class="fa fa-square fa-stack-2x text-primary"></i>
                    <i class="fa fa-image fa-stack-1x fa-inverse"></i>
                  </span>
                  <h5>{{ $sub_category->cat_name }}</h5>
                </a>
                <a class="btn btn-xs btn-default tooltips edit-sub-category" data-id="{{ $sub_category->id }}" data-name="{{ $sub_category->cat_name }}" href="javascript:void(0)" data-type="edit" >
                  <i class="fa fa-pencil" style="color:#253746;"></i>
                </a>
                <a class="btn btn-xs btn-default tooltips delete-subcat" data-id="{{ $sub_category->id }}" cat-img="{{ count($sub_category->gallery_category_list) }}">
                  <i class="fa fa-trash-o" style="color:#253746;"></i>
                </a>
              </div>
            </div>
          </div>
          @endforeach
          
        </div>
        <hr>
        @endif
        
        
        @if (count($get_images))
        <div class="grid">
         
          @foreach ($get_images as $image)
          <div class="grid-item">
            <div class="checkbox clip-check check-primary checkbox-inline">
              <input id="1_all_add_Checkbox{{ $image->id }}" value="{{ $image->id }}" class="all_Checkbox" type="checkbox">
              <label for="1_all_add_Checkbox{{ $image->id }}"></label>
            </div>
            <img src="{{ asset('category-images/' . $image->cat_image) }}" class="img-responsive"
            data-toggle="modal" data-target="#myModal{{ $image->id }}" />
          </div>
          
          <!-- Modal -->
          <div class="modal fade" id="myModal{{ $image->id }}" role="dialog">
            <div class="modal-dialog">

              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <!--   <h4 class="modal-title">Modal Header</h4> -->
                </div>
                <div class="modal-body">
                  <div class="row">

                    <!-- ShareThis BEGIN -->
                    {{-- <div class="sharethis-inline-share-buttons"></div> --}}
                    <!-- ShareThis END -->
                    <div class="imageShare"> 
                      <button onclick="fbs_click({{ $image->id }})" title="facebook"><i class="fab fa-facebook-f"> </i></button>
                      {{-- <button onclick="twitter_click({{ $image->id }})" title="twitter"><i class="fab fa-twitter"> </i></button> --}}
                      <button onclick="pinterest_click({{ $image->id }})" title="pinterest"><i class="fab fa-pinterest"> </i></button>
                    </div>
                    
                    
                    <div class="col-md-6">

                      <img src="{{ asset('category-images/' . $image->cat_image) }}"
                      class="img-responsive" id="image1{{ $image->id }}" alt='' width="auto" height="auto" />
                    </div>
                    <div class="col-md-6">
                      <a class="btn btn-xs btn-default tooltips" href="{{ route('superadmin.edit.image', $image->id) }}" >
                        <i class="fa fa-pencil" style="color:#253746;"></i>
                      </a>
                      <a class="btn btn-xs btn-default tooltips delete-cat" data-id="{{ $image->id }}">
                        <i class="fa fa-trash-o" style="color:#253746;"></i>
                      </a>
                      <a class="btn btn-xs btn-default tooltips" href="{{ asset('category-images/' . $image->cat_image) }}" download>
                        <i class="fa fa-download" style="color:#253746;"></i>
                      </a>
                      <p id="desc{{ $image->id }}">{{ $image->description }}</p>
                    </div>

                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>

            </div>
          </div>   
          @endforeach
        </div>
        @else
        <div class="col-md-12 text-center">
          <h5>No image found</h5>
        </div>
        @endif
        

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
                    <input type="hidden" name="user_id" value="" class="user-id">
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

        <script>
        $(".delete-cat").click(function(){
          var id = $(this).attr('data-id')
          swal({
          title: "Are you sure, you want to delete this image?",
          // text: "Once deleted, you will not be able to recover this Account!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            var site_url = '{{ url("epic-super-admin/delete/image") }}/'+id;
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
          }else
          {  
           return false;
         }
        });
        
        })

    //   
    var arr = [];
    $('.all_Checkbox').click(function () {
      if(this.checked){
        arr.push($(this).val());
        console.log(arr);
      }else{
        var id = $(this).val().split(',')
        arr = $(arr).not(id).get();
        console.log(arr);
      }
      
    });
    
    $(".delete-image").click(function(){
      if(arr.length > 0){
        swal({
          title: "Are you sure, you want to delete these images?",
          // text: "Once deleted, you will not be able to recover this Account!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            var site_url = '{{ url("epic-super-admin/delete/images") }}';
            $.ajax({
              headers: {
                'X-CSRF-TOKEN': $('#token').val()
              },
              type: "post",
              url: site_url,
              data:{'ids':arr},
              success: function(data) {
                window.location.reload();
              }
            }); 
          }else
          {  
           return false;
         }
        });

      }else{
        swal({
          title: "Please select image",
          icon: "warning",
          // buttons: true,
          // dangerMode: true,
        })
      }
    })
    
    
    function fbs_click(id) {
      var u = $('#image1'+id).attr('src');
      var desc = $('#desc'+id).html();
      // t=document.title;
      // t=TheImg.getAttribute('');
      window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(u)+'&quote='+desc,'sharer','toolbar=0,status=0,width=auto,height=auto');return false;
    }

  // function twitter_click(id) {
  //      var u = $('#image1'+id).attr('src');
  //      var desc = $('#desc'+id).html();
  //     window.open('https://twitter.com/intent/tweet?url='+encodeURIComponent(u)+'&text='+desc,'sharer','toolbar=0,status=0,width=626,height=436');return false;
  // }

  function pinterest_click(id) {
    var u = $('#image1'+id).attr('src');
    var desc = $('#desc'+id).html();
    window.open('http://www.pinterest.com/pin/create/button/?media='+encodeURIComponent(u)+'&description='+desc);return false;
  }

  $(document).on('click','.add-sub-category', function(){
    var cat_id = $(this).attr('data-id');
    var user_id = $(this).attr('data-user-id');
    var data_type = $(this).attr('data-type');
    $('.cat-id').val(cat_id);
    $('.user-id').val(user_id);
    $('.data-type').val(data_type);
    $('#cat_name').val('');
    $('.title-name').html('Add Sub Category');
    $('#sub-category-popup').show();
  })
  $(document).on('click','.edit-sub-category', function(){
    var cat_id = $(this).attr('data-id');
    var user_id = $(this).attr('data-user-id');
    var data_type = $(this).attr('data-type');
    var data_name = $(this).attr('data-name');
    $('.cat-id').val(cat_id);
    $('.user-id').val(user_id);
    $('.data-type').val(data_type);
    $('#cat_name').val(data_name);
    $('.title-name').html('Edit Sub Category');
    $('#sub-category-popup').show();
  })
  
  $(document).on('click','.close-popup',function(){
    $('#sub-category-popup').hide();
    $('#delete-category-popup').hide();
  })

  $(".delete-subcat").click(function(){
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
  $(".go-back").click(function(){
    var id = $(this).attr('data-id');
    $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('#token').val()
          },
          type: "get",
          url:'{{ url("epic-super-admin/go/back") }}/'+id,
          success: function(data) {
            // alert(data)
            window.location.replace(data);
          }
        });
  })
</script>
@stop
