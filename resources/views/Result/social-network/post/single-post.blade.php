@extends('Result.masters.app')
@section('page-title')
@stop
@section('required-styles')
<!-- End: NEW timepicker css --> 
{!! Html::style('result/css/social-style.css') !!}
{!! Html::style('result/css/legacy-grid.css') !!}
<style type="text/css">
   .comments{
   width: fit-content;
   background: #f1f1f1;
   padding: 10px;
   border-radius: 13px;
   font-size: 12px;
   margin-bottom: 6px;
   }
   .comments span{
   font-size: 12px;
   }
   .joms-stream__header, .joms-comment__header, .joms-poll__header {
   display: table;
   padding: 9px 8px;
   width: 100%;
   }
   .comment-section{
   width: calc(100% - 40px);
   display: inline-block;
   }
   .comment-delete{
   width: 35px;
   display: inline-block;
   vertical-align: top;
   }
</style>
@stop
@section('content')
    <div class="joms-app--wrapper">
   <!-- begin: .joms-stream__wrapper -->
     <div class="joms-stream__wrapper">
      <!-- begin: .joms-stream__container -->
      <div class="joms-stream__container">
   <!-- post page -->
        @include('Result.social-network.post.show-post')
     </div>
    </div>
   </div>
   <!-- edit post popup  -->
   @include('Result.social-network.post.edit-post')
   <!-- show who like user   -->
   @include('Result.social-network.post.like-popup')
<!-------------- end ----------- -->
@endsection
@section('required-script')
{!! Html::script('result/social-network/social-network.js?v='.time()) !!}
{!! Html::script('result/js/social-network/post.js') !!}
<script>
   $(document).ready(function(){
   	$(".comment").click(function(){
   		$(".comments-show-hide").toggle();;
   	});
   
   });
   $(".edit-information").hide();
   $(".close-info").hide();
   $(".edit-info").click(function(){
   	$(".edit-information").show();
   	$(".edit-information-detail").hide();
   	$(".edit-info").hide();
   	$(".close-info").show();
   });
   $(".close-info").click(function(){
   	$(".edit-information").hide();
   	$(".edit-information-detail").show();
   	$(".edit-info").show();
   	$(".close-info").hide();
   
   });
</script>
@stop