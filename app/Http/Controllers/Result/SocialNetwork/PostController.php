<?php

namespace App\Http\Controllers\Result\SocialNetwork;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\{SocialPost,SocialPostImage,SocialPostVideo,SocialPostComment,SocialPostLike, Clients};
use Auth;
use Response;
use View;

class PostController extends Controller
{
    /* ------- post listing  ------------- */
    public function store(Request $request){
        $data = $request->all();
        // dd(   $data);
        // post
        $post = new SocialPost();
        $post->content = !empty($data['content']) ? $data['content'] : '';
        $post->client_id = Auth::user()->account_id;
        $post->save();
        $file_name = '';
        if ($request->hasFile('images')) {     
            $images = $request->file('images');
            /*  */
            // if(isset($data['delete_image'])){
            //     foreach($images as $elementKey => $element) {
            //         foreach($data['delete_image'] as $valueKey => $value) {
            //             if($value == $element->getClientOriginalName()){
            //                 unset($images[$elementKey]);
            //             } 
            //         }
            //     }
            //  }

            if(isset($data['delete_image'])){
                foreach($data['delete_image'] as $valueKey => $value) {
                   foreach($images as $elementKey => $element) {
                        if($value == $element->getClientOriginalName()){
                            unset($images[$elementKey]);
                            break;
                        } 
                    }
                }
             }

            /*  */
            foreach($images as $file) {
                    $file_name = md5(uniqid() . time()) . '.' . $file->getClientOriginalExtension();
                    $destinationPath = public_path() . '/uploads/posts';
                    if (!file_exists('uploads/posts')) {
                        mkdir('uploads/posts', 0777, true);
                    }
                    $upload = $file->move($destinationPath, $file_name);
                // post image
                    $postImage = new SocialPostImage();
                    $postImage->post_id =  $post->id;
                    $postImage->image_path = $file_name;
                    $postImage->save();
                   
            }
        }
        if ($request->hasFile('video')) {     
            $file = $request->file('video');
            $file_name = md5(uniqid() . time()) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path() . '/uploads/posts';
            if (!file_exists('uploads/posts')) {
                mkdir('uploads/posts', 0777, true);
            }
            $upload = $file->move($destinationPath, $file_name);
         // post image
            $postImage = new SocialPostVideo();
            $postImage->post_id =  $post->id;
            $postImage->video_path = $file_name;
            $postImage->save();
        }
        return redirect()->back();    
   }

/* -------  like and unlike ------------- */
   public function like(Request $request){
    $user_id = Auth::user()->account_id;
    $post = SocialPost::find($request->post_id);
    if ($post){
        $post_like = SocialPostLike::where('post_id', $post->id)->where('client_id', $user_id)->first();
        if ($post_like) { // UnLike
            if ($post_like->client_id == $user_id) {
                $deleted = $post_like->delete();
                if ($deleted){
                    $response['code'] = 'success';
                    $response['type'] = 'unlike';
                }
            }
        }else{
            // Like
            $post_like = new SocialPostLike();
            $post_like->post_id = $post->id;
            $post_like->client_id = $user_id;
            if ($post_like->save()){
                $response['code'] = 'success';
                $response['type'] = 'like';
            }
        }
        if ($response['code'] == 'success'){
            $response['like_count'] = $post->getLikeCount();
        }
    }
     return Response::json($response);
  } 

/*------------- delete--------------------- */
    public function delete($post_id){
        $post = SocialPost::find($post_id);
        if ($post){
            if ($post->client_id == Auth::user()->account_id) {
                if ($post->delete()) {
                    SocialPostImage::where('post_id',$post_id)->delete();
                    SocialPostVideo::where('post_id',$post_id)->delete();
                    SocialPostComment::where('post_id',$post_id)->delete();
                    SocialPostLike::where('post_id',$post_id)->delete();
                    $response['status'] = 'success';
                    $response['message'] = 'Post deleted successfully';
                } else{
                    $response['status'] = 'error';
                }
            }
        }
        return Response::json($response);
    }

/*------------- comment--------------------- */
    public function comment(Request $request){
        $user_id = Auth::user()->account_id;
        $post = SocialPost::find($request->input('post_id'));
        $text = $request->input('comment');
        if ($post && !empty($text)){
            $comment = new SocialPostComment();
            $comment->post_id = $post->id;
            $comment->client_id = $user_id;
            $comment->comment = $text;
            if ($comment->save()){
                $response['status'] = 'success';
                 $html = View::make('Result.social-network.post.single-comment', compact('post', 'comment'));
                 $response['comment'] = $html->render();
                 /*  */
                //  image preview for like fb 
                 $html = View::make('Result.social-network.post.single-comment-preview', compact('post', 'comment'));
                 $response['comment_preview'] = $html->render();
                 /*  */
                 $html = View::make('Result.social-network.post.show-comment', compact('post'));
                 $response['show_comments'] = $html->render();
            }
        }
        if ($response['status'] == 'success'){
            $response['comment_count'] = $post->getCommentCount();
        }
        return Response::json($response);
    }


    /* delete comment */

    public function delete_comment(Request $request){
        $post_comment = SocialPostComment::where('id', $request->comment_id)
                     ->where('post_id', $request->post_id)
                     ->first();
        if ($post_comment){
             $post = $post_comment->social_post;
            if ($post_comment->client_id == Auth::user()->account_id || $post_comment->social_post->client_id == Auth::user()->account_id) {
                if ($post_comment->delete()) {
                    $response['status'] = 'success';
                    $response['message'] = 'Comment deleted successfully';
                    $html = View::make('Result.social-network.post.show-comment', compact('post'));
                    $response['show_comments'] = $html->render();
                    
                }
             }
             if ($response['status'] == 'success'){
                $response['comment_count'] = $post->getCommentCount();
            }

            return Response::json($response);
         }   
     }


    public function single_comment(){
        return view('Result.social-network.post.single-comment');
    }

    public function single_post($post_id){
        $social_post = SocialPost::find($post_id);
        if($social_post){
            $comment_count = 2000000; // show all comment 
            $user_id = Auth::user()->account_id;
            $client_profile = Clients::select('id','profilepic')->where('id',$user_id)->first();
            $post = SocialPost::with(['social_post_image','client'=>function($q){
                    $q->select('id','firstname','lastname','profilepic');
                   }])->where('id', $post_id)
                   ->orderBY('id', 'DESC')
                   ->first();
               
            return view('Result.social-network.post.single-post',compact('post', 'user_id', 'comment_count','client_profile'));   
        }
    }

    public function update_comment(Request $request){
            $user_id = Auth::user()->account_id;
            $post = SocialPost::find($request->input('post_id'));
            $text = $request->input('comment');
            if ($post && !empty($text)){
                 $comment = SocialPostComment::find($request->input('comment_id'));
                 $comment->edit ='1';
                 $comment->comment = $text;   
                if ($comment->save()){
                    $response['status'] = 'success';
                     $html = View::make('Result.social-network.post.single-comment', compact('post', 'comment'));
                     $response['comment'] = $html->render();
                     $html = View::make('Result.social-network.post.show-comment', compact('post'));
                     $response['show_comments'] = $html->render();
                }
             }
            if ($response['status'] == 'success'){
                $response['comment_count'] = $post->getCommentCount();
            }
            return Response::json($response);
     }

    /* update post */
    public function update_post(Request $request, $post_id){
        $data = $request->all();
        // post
        $post = SocialPost::find($post_id);
        $post->content = !empty($data['content']) ? $data['content'] : '';
        $post->client_id = Auth::user()->account_id;
        $post->save();
        $file_name = '';
        if ($request->hasFile('images')) {     
            $images = $request->file('images');
            foreach($images as $file) {
                $file_name = md5(uniqid() . time()) . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path() . '/uploads/posts';
                if (!file_exists('uploads/posts')) {
                    mkdir('uploads/posts', 0777, true);
                }
                $upload = $file->move($destinationPath, $file_name);
            // post image
                $postImage = new SocialPostImage();
                $postImage->post_id =  $post->id;
                $postImage->image_path = $file_name;
                $postImage->save();
             }
          }
        if ($request->hasFile('video')) {     
            $file = $request->file('video');
            $file_name = md5(uniqid() . time()) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path() . '/uploads/posts';
            if (!file_exists('uploads/posts')) {
                mkdir('uploads/posts', 0777, true);
            }
            $upload = $file->move($destinationPath, $file_name);
         // post video
            $find_video = SocialPostVideo::where('post_id',$post_id)->first();
            if( $find_video){
                $find_video->delete();
             }
            $postvideo = new SocialPostVideo();
            $postvideo->post_id =  $post->id;
            $postvideo->video_path = $file_name;
            $postvideo->save();
        }
        return redirect()->back(); 
   }

 public function likes(Request $request){
    $post = SocialPost::find($request->input('post_id'));
    if ($post){
        $response['status'] = 'success';
        $html = View::make('Result.social-network.post.show-like', compact('post'));
        $response['likes'] = $html->render();
    }
    return Response::json($response);
 }


 /* remove post image */
 public function delete_image(Request $request){
    $post = SocialPostImage::where('post_id',$request->post_id)
           ->where('id',$request->image_id)
           ->first();
    if ($post){
            if ($post->delete()) {
                $response['status'] = 'success';
                $response['message'] = 'Post deleted successfully';
            } else{
                $response['status'] = 'error';
            }
    } else {
        $response['status'] = 'error'; 
    }
    return Response::json($response);
 }

 public function delete_video($post_id){
    $post = SocialPostVideo::where('post_id',$post_id)->first();
    if ($post){
            if ($post->delete()) {
                $response['status'] = 'success';
                $response['message'] = 'Post deleted successfully';
            } else{
                $response['status'] = 'error';
            }
    } else {
        $response['status'] = 'error'; 
    }
    return Response::json($response);
 }

 public function search_friend(Request $request){
     $search = $request->search;
     $auth_id = Auth::user()->account_id;
     $status = ['Active','Contra'];
     if(!empty($search)){
        $all_clients = Clients::where(\DB::raw('concat(firstname," ",lastname)'), 'like', '%'.$search.'%')
            ->OfBusiness()
            ->whereIn('account_status',$status)
            ->where('id','!=',$auth_id)
            ->get();

        $html = View::make('Result.social-network.post.search-list', compact('all_clients'));
        $response['search'] = $html->render();    
        $response['status'] = 'success';
        return Response::json($response);
     }
    
   }

  public function show_all_comment($id){
        $comment_count = 2000000; // show all comment
        $user_id = Auth::user()->account_id;
        $client_profile = Clients::select('id','profilepic')->where('id',$user_id)->first();
        $post = SocialPost::with(['social_post_image','social_post_video','client'=>function($q){
                    $q->select('id','firstname','lastname','profilepic');
                },'goal_client'=>function($q){
                    $q->select('id','firstname','lastname','profilepic');
                },
                ])
                ->where('id', $id)
                ->orderBY('id', 'DESC')
                ->first();
        $html = View::make('Result.social-network.post.show-all-comment', compact('post','user_id', 'comment_count','client_profile'));
        $response['html'] = $html->render();
        return Response::json($response);
}
// C:\xampp\htdocs\epicfitlaravelv6\resources\views\Result\social-network\post\show-all-comment.blade.php
    
/* ----------- end --------------------------- */

}
