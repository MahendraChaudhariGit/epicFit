$(function () {
    var add_post_id;
	$(".add-emoji").emojioneArea({
		// inline: true,
		pickerPosition: "bottom",
		tonesStyle: "radio",
        saveEmojisAs:'image',
		// hideSource: false
		// standalone: true,
		pickerPosition: "bottom",
		events: {
			keyup: function (editor, event) {
     
				if (this.getText().trim() != '') {
					if (event.which == 13 && ($.trim(editor.text()).length > 0 || $.trim(editor.html()).length > 0)) {
                        $(".add-emoji").data("emojioneArea").hidePicker();
						const form = event.currentTarget.closest('.emoji-div');
						const field = form.querySelector('.add-emoji[name="emoji"]');
						let post_id = field.getAttribute('data-emoji-id');
						var comment =  field.emojioneArea.getText().trim();
						submitComment(comment, post_id);
						event.preventDefault();
						event.stopPropagation();
						editor.focus();
					}
				} else {
                    if(event.which == 13){
                        event.preventDefault();
                        return false;
                     }
				}
			},
            emojibtn_click: function (button, event) {
                $("#comment-submit-button-"+ add_post_id)[0].emojioneArea.hidePicker();
                $("#comment-submit-button2-"+ add_post_id)[0].emojioneArea.hidePicker(); 
            },
           focus: function (editor, event) {
            const form = event.currentTarget.closest('.emoji-div');
            const field = form.querySelector('.add-emoji[name="emoji"]');
            let post_id = field.getAttribute('data-emoji-id');
             add_post_id = post_id;
          },

          blur: function (editor, event) {
              const form = event.currentTarget.closest('.emoji-div');
              const field = form.querySelector('.add-emoji[name="emoji"]');
              var html =  field.emojioneArea.getText().trim();
              var rx = /<img\s+(?=(?:[^>]*?\s)?class="[^">]*emojione)(?:[^>]*?\s)?alt="([^"]*)"[^>]*>(?:[^<]*<\/img>)?/gi;
              var text   = html.replace(rx, "$1") ;
              field.emojioneArea.setText(text);
          },
           
		}
	});

	// $(window).scroll(function () {
    //     var position = $(window).scrollTop();
    //     var bottom = $(document).height() - $(window).height();
    //     // console.log('position',position, bottom );
	//     if ($(window).scrollTop() == $(document).height() - $(window).height()) {
    //          console.log('hiii');
	//         //  fetchForOlderPosts();
	//     }
	// });
});

/* load more  */
var fetch_end = false;
var count_empty_query = 0;

function fetchForOlderPosts(){
    var limit = 5;
    if (!fetch_end) {
        fetch_end = true;
        $.ajax({
            url: BASE_URL + '/social/home',
            type: "GET",
            timeout: 5000,
            success: function (render) {
                console.log('render-------', render);
                if (render != "") {
                    // $('.post-list .post_data_filter_' + location).remove();
                    // if (location == 'bottom') {
                    //     $('.post-list').append(render);
                    // } else if (location == 'top') {
                    //     $('.post-list').prepend(render);
                    // } else {
                    //     $('.post-list').html(render);
                    // }
                }else{
                    // if (location == 'bottom') {
                    //     count_empty_query = count_empty_query + 1;
                    // }
                }
               
                fetch_end = false;
            },
            error: function () {
                fetch_end = false;
            }
        });
    }
    

    }

 

/* end  */
 window.resetFile = function (e) {
        e.wrap('<form>').closest('form').get(0).reset();
        e.unwrap();
  };
    
/* ----   image preview  ------------*/
// $('body').on('change', '#upload_image', function() {
//     $("#add-post-image").removeClass("hide");
//     var total_file = document.getElementById("upload_image").files.length;
//     if(total_file > 5){
//         $(".more5img").css("display", "block");
//         $val = total_file - 5;
//         $(".more5img").html('+'+ $val);
//         total_file = 5;
//     }
//     for(var i=0;i<total_file;i++){
//         $('#post_image').append("<img class='preview-post-image' src='"+URL.createObjectURL(event.target.files[i])+"'>");
//      }
//   });

  $('body').on('change', '#upload_video', function() {
    $("#remove-post-video").removeClass("hide");
    $("#preview-post-video").removeClass("hidden");
    $('#preview-post-video').attr('src', URL.createObjectURL(this.files[0]));
  });

/* ----  image upload in html  ------------*/
function uploadPostImage(){
    /* add image one by one for multiple image */
    //  $('#post_image').html('');
    //  var form_name = '#form-new-post';
    //  resetFile($(form_name + ' .image-input'));
    /*  */
    // var form_name = '#form-new-post';
    //  $(form_name+' .image-input').click();
    $('input[name="images[]"]').attr("accept", "image/*");
     var form_name = '#form-new-post';
     $(form_name+' .upload-text').click();
     $(".image-uploader").css("display", "block")
   
    
}

function uploadPostVideo(){
    var form_name = '#form-new-post';
    $(form_name+' .video-input').click();
}


/* ----  update post image upload in html  ------------*/
function uploadUpdatePostImage(id){
    var form_name = '#form-update-post-'+id;
    $(form_name+' .image-input').click();
}




/* ----   image preview  ------------*/
$('body').on('change', '.add-more-image', function() {
    var post_id = $(this).data('post-id');
     $(".delete-image-btn").removeClass("hide");
    var total_file = document.getElementById("add-more-image-"+post_id).files.length;
    if(total_file > 5){
        $(".more5img").css("display", "block");
        $val = total_file - 5;
        $(".more5img").html('+'+ $val);
        total_file = 5;
    }
    for(var i=0;i<total_file;i++){
        $('.update-post-image').append("<img class='preview-post-image' src='"+URL.createObjectURL(event.target.files[i])+"'>");
     }
  });

$('body').on('change', '.update_video', function() {
    var post_id = $(this).data('post-id');
    $(".remove-post-video").removeClass("hide");
    $("#update-video-"+post_id).removeClass("hidden");
    $('#update-video-'+post_id).attr('src', URL.createObjectURL(this.files[0])); 
  });

function uploadUpdatePostVideo(id){
    var form_name = '#form-update-post-'+id;
    $(form_name+' .video-input').click();
}

// function previewVideo($this,id) {
//     $("#update-video-"+id).removeClass("hidden");
//     const [file] = $this.files;
//     if (file) {
//         $("#update-video-"+id).src = URL.createObjectURL(file)
//     }
// }
/* remove uploaded image */

function removePostImage(){
    $('#post_image').html('');
    $("#add-post-image").addClass("hide");
    $(".more5img").css("display", "none");
     var form_name = '#form-new-post';
     resetFile($(form_name + ' .image-input'));
}
/* remove image at the add for edit post */
function removeEditPostImage(post_id){
    $('.update-post-image').html('');
    $(".delete-image-btn").css("display", "none");
    $(".more5img").css("display", "none");
    var form_name = '#form-update-post-'+ post_id;
     resetFile($(form_name + ' .add-more-image'));
}

function removePostVideo(){
    var form_name = '#form-new-post';
    $(form_name + ' #preview-post-video').attr('src', " ");
    $(form_name + ' #preview-post-video').addClass('hidden');
    $("#remove-post-video").addClass("hide");
    resetFile($(form_name + ' .video-input'));
}
function removeEditPostVideo(post_id){
    var form_name = '#form-update-post-'+ post_id;
    $(".remove-post-video").addClass('hide');
    $(form_name + ' #update-video-'+post_id).attr('src','');
    $(form_name + ' #update-video-'+post_id).addClass('hidden');
    resetFile($(form_name + ' .update_video'));
}
/* ---------like or unlike  post ----------*/   
function likePost(id){
   var post_id = id;
    $.ajax({
        url: BASE_URL+'/social/post/like',
        type: "POST",
        data: {'post_id':post_id},
        headers: {'X-CSRF-TOKEN': CSRF},
        success: function(response){
            if (response.code == 'success'){
                if (response.type == 'like'){
                    $('#like-'+post_id+' span').html('Unlike');
                    $('#like-'+post_id+' i').removeClass('fa-thumbs-o-up').addClass('fa-thumbs-up');

                    $('#like2-'+post_id+' span').html('Unlike');
                    $('#like2-'+post_id+' i').removeClass('fa-thumbs-o-up').addClass('fa-thumbs-up');
                }else{
                    $('#like-'+post_id+' span').html('Like');
                    $('#like-'+post_id+' i').removeClass('fa-thumbs-up').addClass('fa-thumbs-o-up');

                    $('#like2-'+post_id+' span').html('Like');
                    $('#like2-'+post_id+' i').removeClass('fa-thumbs-up').addClass('fa-thumbs-o-up');
                }           
                 $('#all-likes-'+post_id).html(response.like_count);

                 $('#all-likes2-'+post_id).html(response.like_count);
               
            }else{
                swal({
                    type: 'warning',
                    title:'Oops...',
                    text: 'Something went wrong!',
                    allowOutsideClick: true,
                }); 
            }
        },
        error: function(){
            swal({
                type: 'warning',
                title:'Oops...',
                text: 'Something went wrong!',
                allowOutsideClick: true,
            }); 
        }
    });
}


/* ---------delete post ----------*/   
function deletePost(id){
    var post_id = id;
    swal({
            type: 'warning',
            title: 'Are you sure you want to Delete this post?',
            allowOutsideClick: true,
            showCancelButton: true,
            confirmButtonText: 'Yes',
            confirmButtonColor: '#ff4401',
            cancelButtonText: "No"
        },
        function (isConfirm) {
            if (isConfirm) {
                $.get(BASE_URL+'/social/post/delete/' + post_id, function (response) {
                    if (response.status = 'success') {
                        $('#panel-post-'+id).remove();
                        $('.my-photo-'+id).remove();
                        $('.my-video-'+id).remove();
                        $(".fbphotobox-close-btn a").click();

                        swal({
                                type: 'success',
                                title: response.message,
                                allowOutsideClick: true,
                                showCancelButton: false,
                            });   
                       } else {
                            swal({
                                type: 'warning',
                                title:'Oops...',
                                text: 'Something went wrong!',
                                allowOutsideClick: true,
                            }); 
                       }
                  });
              }
          });
      }


 /* --------- submit comment  ----------*/   
// function submitComment(e,id){
function submitComment(comment, id){
    if (comment.trim() == ''){
        return false;
    }
  
        $.ajax({
            url: BASE_URL + '/social/post/comment',
            type: "POST",
             data: {'post_id':id,'comment':comment},
            headers: {'X-CSRF-TOKEN': CSRF},
            success: function (response) {
                if (response.status == 'success') {
                    $(".emojionearea-editor").html('');
                    $('#panel-post-'+id+' #form-new-comment textarea').val("");
                    // $('#panel-post-'+id+' .show_comments').html(response.show_comments);
                    $('#panel-post-'+id+' .post-comments').prepend(response.comment);

                    $('#panel-post2-'+id+' #form-new-comment2 textarea').val("");
                    // $('#panel-post2-'+id+' .show_comments').html(response.show_comments);
                    $('#panel-post2-'+id+' .post-comments').prepend(response.comment_preview);
                    if(response.comment_count > 1){
                        $('#comment-count-'+id).html(response.comment_count + " comments");
                    } else{
                        $('#comment-count-'+id).html(response.comment_count + " comment");
                    }
                   
                } else {
                    swal({
                        type: 'warning',
                        title:'Oops...',
                        text: 'Something went wrong!',
                        allowOutsideClick: true,
                    }); 
                }
            },
            error: function () {
                swal({
                    type: 'warning',
                    title:'Oops...',
                    text: 'Something went wrong!',
                    allowOutsideClick: true,
                }); 
            }
        });
  }

  /* delete comment */

  function removeComment(id, post_id){
    swal({
        type: 'warning',
        title: 'Are you sure you want to Delete this comment?',
        allowOutsideClick: true,
        showCancelButton: true,
        confirmButtonText: 'Yes',
        confirmButtonColor: '#ff4401',
        cancelButtonText: "No"
    },
    function (isConfirm) {
        if (isConfirm) {
            $.post(BASE_URL+'/social/post/comment/delete', {comment_id:id, post_id: post_id}, function (response) {
                if (response.status = 'success') {
                   
                    $('#post-comment-'+id).remove();
                    $('#post-comment2-'+id).remove();
                    $('#panel-post-'+post_id+' .show_comments').html(response.show_comments);
                    $('#panel-post2-'+post_id+' .show_comments').html(response.show_comments);
                    if(response.comment_count > 1){
                        $('#comment-count-'+post_id).html(response.comment_count + " comments");
                        $('#comment-count2-'+post_id).html(response.comment_count + " comments");
                    } else{
                        $('#comment-count-'+post_id).html(response.comment_count + " comment");
                        $('#comment-count2-'+post_id).html(response.comment_count + " comments");
                    }
                    swal({
                            type: 'success',
                            title: response.message,
                            allowOutsideClick: true,
                            showCancelButton: false,
                        });   
                } else {
                        swal({
                            type: 'warning',
                            title:'Oops...',
                            text: 'Something went wrong!',
                            allowOutsideClick: true,
                        }); 
                    }
                });
            }
        });
    }


  /*   edit  Comment */


    function editComment(id, post_id){
        if($('.panel-post-div').hasClass('fb-popup-image-page')){
            var html2 = $('#comment-value2-'+id).html();
            var rx = /<img\s+(?=(?:[^>]*?\s)?class="[^">]*emojione)(?:[^>]*?\s)?alt="([^"]*)"[^>]*>(?:[^<]*<\/img>)?/gi;
            var text2   = html2.replace(rx, "$1") ;
   
            var text_html2 = '';
            text_html2 += '<div class="textarea-container">';
            text_html2 += '<textarea id="update-comment2-'+id+'" name="emoji" class="edit-emoji" rows="1" placeholder="Write a Comment..." required>'+ text2 +'</textarea>';
            text_html2 += '<button onclick="updateComment('+$(this).siblings('textarea').val()+','+id+','+post_id+')"><img src="'+BASE_URL+'/assets/images/arrow.png"></button>';
            text_html2 += '</div>'; 
            $("#comment2-"+id).replaceWith($(text_html2));
            $("#delete-option2-"+id).remove();
        } else{
            var html = $('#comment-value-'+id).html();
            var rx = /<img\s+(?=(?:[^>]*?\s)?class="[^">]*emojione)(?:[^>]*?\s)?alt="([^"]*)"[^>]*>(?:[^<]*<\/img>)?/gi;
            var text   = html.replace(rx, "$1") ;
            var text_html = '';
            text_html += '<div class="textarea-container">';
            text_html += '<textarea id="update-comment-'+id+'" name="emoji" class="edit-emoji" rows="1" placeholder="Write a Comment..." required>'+ text +'</textarea>';
            text_html += '<button onclick="updateComment('+$(this).siblings('textarea').val()+','+id+','+post_id+')"><img src="'+BASE_URL+'/assets/images/arrow.png"></button>';
            text_html += '</div>';
            $("#comment-"+id).replaceWith($(text_html));
            $("#delete-option-"+id).remove();
        }
         /* emoji */
         $(".edit-emoji").emojioneArea({
            pickerPosition: "bottom",
            tonesStyle: "radio",
            pickerPosition: "bottom",
            saveEmojisAs:'image',
            events: {
                keyup: function (editor, event) {
                    if (this.getText().trim() != '') {
                        if (event.which == 13 && ($.trim(editor.text()).length > 0 || $.trim(editor.html()).length > 0)) {
                            $(".edit-emoji").data("emojioneArea").hidePicker();
                            const form = event.currentTarget.closest('.edit-emoji-div');
                            const field = form.querySelector('.edit-emoji[name="emoji"]');
                            // var comment = editor.html();
                            var comment =  field.emojioneArea.getText().trim();
                            updateComment(comment, id, post_id);
                            event.preventDefault();
                            event.stopPropagation();
                            editor.focus();
                        }
                    } else {
                        if(event.which == 13){
                            event.preventDefault();
                            return false;
                         }
                    }
    
                },
                emojibtn_click: function (button, event) {
                    $(".edit-emoji")[0].emojioneArea.hidePicker(); 
               },
               blur: function (editor, event) {
                const form = event.currentTarget.closest('.edit-emoji-div');
                const field = form.querySelector('.edit-emoji[name="emoji"]');
                var html =  field.emojioneArea.getText().trim();
                var rx = /<img\s+(?=(?:[^>]*?\s)?class="[^">]*emojione)(?:[^>]*?\s)?alt="([^"]*)"[^>]*>(?:[^<]*<\/img>)?/gi;
                var text   = html.replace(rx, "$1") ;
                field.emojioneArea.setText(text);
            },
    
            }
    
        });
      /* emoji */
         
        
     }

      /* --------- update  comment  ----------*/   
// function updateComment(e, id, post_id){
function updateComment(comment, id, post_id){
    if(comment == undefined){
        var comment = $('#update-comment-'+id).val();
        var comment2 = $('#update-comment2-'+id).val();
    }
    if (comment.trim() == ''){
        return false;
    }
        $.ajax({
            url: BASE_URL + '/social/post/update/comment',
            type: "POST",
             data: {'post_id':post_id,'comment_id':id,'comment':comment},
            headers: {'X-CSRF-TOKEN': CSRF},
            success: function (response) {
                if (response.status == 'success') {
                     $('#update-comment-'+id).val("");
                     $('#update-comment2-'+id).val("");
                     $('#panel-post-'+id+' .show_comments').html(response.show_comments);
                     $('#panel-post2-'+id+' .show_comments').html(response.show_comments);
                     $("#post-comment-"+id).replaceWith($(response.comment));
                     $("#post-comment2-"+id).replaceWith($(response.comment));
                } else {
                    swal({
                        type: 'warning',
                        title:'Oops...',
                        text: 'Something went wrong!',
                        allowOutsideClick: true,
                    }); 
                }
            },
            error: function () {
                swal({
                    type: 'warning',
                    title:'Oops...',
                    text: 'Something went wrong!',
                    allowOutsideClick: true,
                }); 
            }
        });
  }

/* show likes */
  function showLikes(id){
    $.ajax({
        url: BASE_URL + '/social/post/user-likes',
        type: "POST",
        data: {'post_id':id},
        headers: {'X-CSRF-TOKEN': CSRF},
        success: function (response) {
            if (response.status == 'success') {
                $('#user_list').html(response.likes);
                $('#show-like').modal('show');
            } else {
                swal({
                    type: 'warning',
                    title:'Oops...',
                    text: 'Something went wrong!',
                    allowOutsideClick: true,
                }); 
            }
        },
        error: function () {
            swal({
                type: 'warning',
                title:'Oops...',
                text: 'Something went wrong!',
                allowOutsideClick: true,
            }); 
        }
    });
}
  
/* remove image */
function removeImage(id,post_id){
    var status = confirm("Are you sure you want to delete?"); 
    if(status){
        $.post(BASE_URL+'/social/post/image/delete', {image_id:id, post_id: post_id}, function (response) {
        // $.get(BASE_URL+'/social/post/image/delete/' + post_id, function (response) {
            if (response.status = 'success') {
                 $('#multiple-post-image-'+id).remove();  
                } 
            });
      } else {
          return false;
      }
      
 }

 function removeVideo(id){
    var post_id = id;
    var status = confirm("Are you sure you want to delete?"); 
    if(status){
        $.get(BASE_URL+'/social/post/video/delete/' + post_id, function (response) {
            if (response.status = 'success') {
                  $('#remove-update-video-'+post_id).remove();  
                } 
            });
      } else {
          return false;
      }
      
 }
/* search friend */
 $(document).on("keyup",".search-suggestags-input",function(event){
    var search = $(this).val();
    if (search.trim() == ''){
        $(".search-popup").html('');
        $(".search-popup").addClass('hidden');
    }else {
        $(".search-popup").removeClass("hidden");
        searchFriends(search);
    }
 })

 function searchFriends(search){
    $.post(BASE_URL+'/social/post/search_friend', {'search':search}, function(response){
        // $(".search-popup").removeClass("hidden");
        $(".search-popup").html(response.search);
     }); 
  }
 /* end search friend */
$('body').on( 'change keyup keydown paste cut', 'textarea', function (){
    $(this).height(0).height(this.scrollHeight);
}).find( 'textarea' ).change();
/* ------------------------------------------------- */
/**multiple images design**/
$(document).ready(function() {
// $('.panel-post-div .show-post a:nth-child(5)').css("width", "33.33%");
// $('.panel-post-div .show-post a:nth-child(5)').css("float", "left");
var postno = $('.joms-stream__container').find(".panel-post-div").length;
for(i=0;i<=postno;i++)
{
// var post_id = $(".panel-post-div").data("post-id");
var post_id = $("#post-name-dev-"+i).data("post-id");
var count = $('#panel-post-'+post_id+' .show-post').find("a").length;
if(count == 1){
    $('#panel-post-'+post_id+' .show-post a').css("float", "left");
    $('#panel-post-'+post_id+' .show-post a').css("width", "100%");
    $('#panel-post-'+post_id+' .show-post a').css("height", "auto");
}
else if(count == 2){
    $('#panel-post-'+post_id+' .show-post a').css("float", "left");
    $('#panel-post-'+post_id+' .show-post a').css("width", "50%");    
    var divWidth2= $('#panel-post-'+post_id+' .show-post a').width();
    $('#panel-post-'+post_id+' .show-post a').css({"height": divWidth2 + 2});

}
else if(count == 3){
    $('#panel-post-'+post_id+' .show-post a').css("float", "left");
    $('#panel-post-'+post_id+' .show-post a:nth-child(1)').css("width", "100%");
      var divWidth5= $('#panel-post-'+post_id+' .show-post a:nth-child(1)').width();
    $('#panel-post-'+post_id+' .show-post a:nth-child(1)').css({"height": divWidth5 + 2});
    $('#panel-post-'+post_id+' .show-post a:nth-child(2)').css("width", "50%");
     var divWidth6= $('#panel-post-'+post_id+' .show-post a:nth-child(2)').width();
    $('#panel-post-'+post_id+' .show-post a:nth-child(2)').css({"height": divWidth6 + 2});
    $('#panel-post-'+post_id+' .show-post a:nth-child(3)').css("width", "50%");
    var divWidth7= $('#panel-post-'+post_id+' .show-post a:nth-child(3)').width();
    $('#panel-post-'+post_id+' .show-post a:nth-child(3)').css({"height": divWidth7 + 2});

}
else if(count == 4){
    $('#panel-post-'+post_id+' .show-post a').css("float", "left");
    $('#panel-post-'+post_id+' .show-post a').css("width", "50%");
     var divWidth3= $('#panel-post-'+post_id+' .show-post a').width();
    $('#panel-post-'+post_id+' .show-post a').css({"height": divWidth3 + 2});
}
else if(count == 5 ){
    $('#panel-post-'+post_id+' .show-post a').css("float", "left");
    $('#panel-post-'+post_id+' .show-post a:nth-child(1)').css("width", "50%");
    $('#panel-post-'+post_id+' .show-post a:nth-child(2)').css("width", "50%");
    $('#panel-post-'+post_id+' .show-post a:nth-child(3)').css("width", "33.33%");
    $('#panel-post-'+post_id+' .show-post a:nth-child(4)').css("width", "33.33%");
    $('#panel-post-'+post_id+' .show-post a:nth-child(5)').css("width", "33.33%");
    $('#panel-post-'+post_id+' .show-post a:nth-child(1)').css("height", "234.5px");
    $('#panel-post-'+post_id+' .show-post a:nth-child(2)').css("height", "234.5px");
    $('#panel-post-'+post_id+' .show-post a:nth-child(3)').css("height", "156.31px");
    $('#panel-post-'+post_id+' .show-post a:nth-child(4)').css("height", "156.31px");
    $('#panel-post-'+post_id+' .show-post a:nth-child(5)').css("height", "156.31px"); 


}
else if(count > 5 ){
    $('#panel-post-'+post_id+' .show-post a').css("display", "none");
    $('#panel-post-'+post_id+' .show-post a:nth-child(6)').css("display", "block");
    $('#panel-post-'+post_id+' .show-post a:nth-child(2)').css("display", "block");
    $('#panel-post-'+post_id+' .show-post a:nth-child(3)').css("display", "block");
    $('#panel-post-'+post_id+' .show-post a:nth-child(4)').css("display", "block");
    $('#panel-post-'+post_id+' .show-post a:nth-child(5)').css("display", "block");
    $('#panel-post-'+post_id+' .show-post a:nth-child(2)').css("width", "50%");
    $('#panel-post-'+post_id+' .show-post a:nth-child(3').css("width", "50%");
    $('#panel-post-'+post_id+' .show-post a:nth-child(4)').css("width", "33.33%");
    $('#panel-post-'+post_id+' .show-post a:nth-child(5)').css("width", "33.33%");
    $('#panel-post-'+post_id+' .show-post a:nth-child(6').css("width", "33.33%");
    $('#panel-post-'+post_id+' .show-post a').css("float", "left");
     var divWidth8= $('#panel-post-'+post_id+' .show-post a:nth-child(2)').width();
    $('#panel-post-'+post_id+' .show-post a:nth-child(2)').css({"height": divWidth8 + 2});
     var divWidth9= $('#panel-post-'+post_id+' .show-post a:nth-child(3)').width();
    $('#panel-post-'+post_id+' .show-post a:nth-child(3)').css({"height": divWidth9 + 2});
     var divWidth10= $('#panel-post-'+post_id+' .show-post a:nth-child(4)').width();
    $('#panel-post-'+post_id+' .show-post a:nth-child(4)').css({"height": divWidth10 + 2});
    var divWidth11= $('#panel-post-'+post_id+' .show-post a:nth-child(5)').width();
    $('#panel-post-'+post_id+' .show-post a:nth-child(5)').css({"height": divWidth11 + 2});
    var divWidth12= $('#panel-post-'+post_id+' .show-post a:nth-child(6)').width();
    $('#panel-post-'+post_id+' .show-post a:nth-child(6)').css({"height": divWidth12 + 2});

    var heightss = (divWidth12/2);
    $('.more5img-show').css({"bottom": heightss - 20});

    $('#panel-post-'+post_id+' .show-post a:nth-child(6)').addClass('bg1color'); 


}
}
});


var content= '',images = 0,video = 0;
$('#submit_post').on('click',function() {
    $('#new-post').addClass('hidden');
    content = $("textarea[name=content]").val();
    // images = $('#upload_image')[0].files;
    images = $('.uploaded-image').length;
    console.log('images =======', images );
    video = $("#upload_video")[0].files;
    // if(content.trim() != '' || images.length > 0 || video.length > 0){
    if(content.trim() != '' || images > 0 || video.length > 0){
        $('#submit_post').submit();
    }else{
        if(content.trim() == ''){
            $('#new-post').removeClass('hidden');
          }
        return false;
    }
})


/* see more button */

function seeMore(id) {
    var dots = document.getElementById("dots-"+id);
    var moreText = document.getElementById("more-"+id);
    var btnText = document.getElementById("seeMoreBtn-"+id);
    var firstText = document.getElementById("content-"+id);

    if (dots.style.display === "none") {
        dots.style.display = "inline";
        btnText.innerHTML = "See more";
        moreText.style.display = "none";
    } else {
        dots.style.display = "none";
        btnText.innerHTML = "";
        moreText.style.display = "inline";
        firstText.style.display = "none";
    }
}


   function editPostModal(id){
       $("#edit-post-"+id).modal('show');
       setTimeout(function() {
        textareaHeight();
       }, 400);
   }

  function textareaHeight(){
    $("textarea").each( function( i, el ) {
        $(el).height( el.scrollHeight );
    });
   }
   /* my photo and my video */

   function showEditPostPhoto(id){
    $("#my-photo-edit-post-"+id).modal('show');    
   }

   function showEditPostVideo(id){
    $("#my-video-edit-post-"+id).modal('show');    
   }

   function uploadUpdatePostImagePhoto(id){
     var form_name = '#my-photo-form-update-post-'+id;
      $(form_name+' .image-input').click();
}


$('body').on('change', '.add-more-image', function() {
    var post_id = $(this).data('post-id');
     $(".delete-image-btn").removeClass("hide");
    var total_file = document.getElementById("my-photo-add-more-image-"+post_id).files.length;
    if(total_file > 5){
        $(".more5img").css("display", "block");
        $val = total_file - 5;
        $(".more5img").html('+'+ $val);
        total_file = 5;
    }
    for(var i=0;i<total_file;i++){
        $('.update-post-image').append("<img class='preview-post-image' src='"+URL.createObjectURL(event.target.files[i])+"'>");
     }
  });

  function uploadUpdatePostVideoPhoto(id){
    var form_name = '#my-video-form-update-post-'+id;
     $(form_name+' .video-input').click();
  }

  $('body').on('change', '.update_video', function() {
    var post_id = $(this).data('post-id');
    $(".remove-post-video").removeClass("hide");
    $("#my-video-update-video-"+post_id).removeClass("hidden");
    $('#my-video-update-video-'+post_id).attr('src', URL.createObjectURL(this.files[0])); 
  });

/* remove image */
function removeImage(id,post_id){
    var status = confirm("Are you sure you want to delete?"); 
    if(status){
        $.post(BASE_URL+'/social/post/image/delete', {image_id:id, post_id: post_id}, function (response) {
        // $.get(BASE_URL+'/social/post/image/delete/' + post_id, function (response) {
            if (response.status = 'success') {
                 $('#my-photo-multiple-post-image-'+id).remove();  
                } 
            });
      } else {
          return false;
      }
      
 }

 function removeVideo(id){
    var post_id = id;
    var status = confirm("Are you sure you want to delete?"); 
    if(status){
        $.get(BASE_URL+'/social/post/video/delete/' + post_id, function (response) {
            if (response.status = 'success') {
                  $('#my-video-remove-update-video-'+post_id).remove();  
                } 
            });
      } else {
          return false;
      }
      
 }

/* my photo */

function showAllComment(post_id){
    $.get(BASE_URL+'/social/post/show_all_comment/' + post_id, function (response) {
        if (response.status = 'success') {
           if($('.show_comments').hasClass("fb-image-popup")) {
                $('#panel-post2-'+post_id+' .show_comments').html('');
                $('#panel-post2-'+post_id+' .post-comments').html(response.html);
           } else{
               $('#panel-post-'+post_id+' .show_comments').html('');
               $('#panel-post-'+post_id+' .post-comments').html(response.html);
           }   
         } 
    });
  }

function showCommentBox(post_id){
          $(".comments-show-hide-"+post_id).toggle();
}


/* end see more */

