@section('required-styles')
{{-- <style type="text/css">
   .input_msg_write.emojionearea .emojionearea-button.active+.emojionearea-picker-position-top {
    margin-top: -245px !important;
} --}}
</style>
@stop
<div class="col-md-12" id="message-div">
   <div class="message-heading d-flex">
    
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4><i class="fa fa-comments-o"></i> Messages </h4>
      <!-- friend list  head-->   
            <div class="headind_srch" data-toggle="tooltip" data-placement="bottom" title="New Message">
               <div class="recent_heading">
                  <span id="hide-message">
                    <button class="btn btn-success btn-sm" id="show-search-popup" type="button" data-toggle="modal" data-target="#friend-search-popup" >
                    <i class="fa fa-edit"></i> 
                  </button>
                  </span>
                  <span class="input-group-addon1" id="show-friend-chat-list">
                  </span> 
               </div>
            </div>
            <!-- friend list -->  
   </div>
   <div class="messaging">
      <div class="inbox_msg">
         <div class="inbox_people">
           
            <div class="inbox_chat" id="people_list">
               @include('Result.social-network.message.people_list')
            </div>
            <!-- friend list -->
         </div>
      </div>
   </div>
</div>
</div>
<!--show search popup-->
<div class="modal fade" id="friend-search-popup" role="dialog">
   <div class="modal-dialog modal-md">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">New Message</h4>
         </div>
         <div class="modal-body">
            <div class="srch_bar">
               <div class="stylish-input-group">  
                  <input id="friend-search-box" type="text" class="search-bar"  placeholder="Search for names" >
                  <span class="input-group-addon1">
                  <button type="button" onclick="friendSearch()"> <i class="fa fa-search" aria-hidden="true"></i> </button>
                  </span> 
               </div>
            </div>
            <div id="input-search-frined-div">
            </div>
         </div>
      </div>
   </div>
</div>
<!--show search popup -->

<!--direct message chat-->
<div class="col-md-12 direct-message show-direct-message" >

</div>
<!--end direct message-->
@section('required-script')
{!! Html::script('result/js/social-network/dm.js') !!}
{{-- {!! Html::script('result/js/social-network/post.js?v='.time()) !!} --}}
<script>
   $(function() { 

      $("#message-link").click(function(){
         $("#message-div").show();
      });

      $("#message-div .close").click(function(){
         $("#message-div").hide();
      });

      // $(".direct-message .close").click(function(){
      //    $(".direct-message").hide();
      // });

      $("#message-div #people_list").click(function(){
            $(".direct-message").show();
            $("#message-div").hide();
      });

      $(document).on('click','#show-search-popup',function(){
         $("#message-div").hide();
      });

      // $(document).on('click','#input-search-frined-div',function(){
      //    $(".direct-message").show();
      // });

      

   });
   
</script>
<script>
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();   
});
</script>
@stop