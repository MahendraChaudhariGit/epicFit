<?php

namespace App\Http\Controllers\MealPlanner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\{Clients, RecipeReview, ReviewUpvote, ReplyRecipeReview, RecipeRating};
use View;
class MealReviewController extends Controller
{
    public function postReview(Request $request){
        $data = $request->formData;
        $client_id =  Auth::user()->account_id;
        $client_data = Clients::select('id','firstname','lastname','profilepic')
                      ->where('id',$client_id)
                      ->first();
        $exit_user_comment = RecipeReview::where('client_id',$client_id)
                           ->where('meal_id',$data['id'])
                           ->get();
        if(count($exit_user_comment) == 0){
            $exit_rating = RecipeRating::where('client_id',$client_id)
                         ->where('meal_id',$data['id'])
                         ->get();
            if(count($exit_rating) == 0){
                $response['first_comment'] = 'Yes';
            } else {
                $response['first_comment'] = 'No';
            }
          
         }else {
            $response['first_comment'] = 'No';
         }
        $create_review = RecipeReview::create([
            'meal_id'=>$data['id'],
            'client_id'=> $client_id,
            'comment'=>$data['comment']
        ]);
        if( $create_review){
            $response['status']= "success";
            $response['data']= $create_review;    
            $type = "Review"  ;
            $html = View::make('Result.mealplanner.single-review', compact('create_review','type','client_data'));
            $response['review'] = $html->render();
          return response()->json( $response);
        } else {
            $response['status']= "error"; 
           return response()->json( $response);
        }
    }

    public function postReply(Request $request){
        $data = $request->formData;
    
        $client_id =  Auth::user()->account_id;
        $client_data = Clients::select('id','firstname','lastname','profilepic')
                      ->where('id',$client_id)
                      ->first();
        $create_review = ReplyRecipeReview::create([
            // 'meal_id'=>$data['recipe_id'],
            'client_id'=> $client_id,
            'recipe_review_id'=>$data['review_id'],
            'comment'=>$data['comment']
        ]);
        if( $create_review){
            $review = RecipeReview::find($data['review_id']);
            $old_reply =$review->reply_count;
            $new_reply = $old_reply + 1;
            $review->update([
                'reply_count' =>  $new_reply
            ]);
            $response['status']= "success";
            $response['data']= $create_review;     
            $type = "Reply"  ; 
            $html = View::make('Result.mealplanner.single-review', compact('create_review','type','client_data'));
            $response['review'] = $html->render();
          return response()->json( $response);
        } else {
            $response['status']= "error"; 
           return response()->json( $response);
        }
    }

    /* -------  upvote ------------- */
   public function upvote(Request $request){
    $input = $request->formData;
    $user_id = Auth::user()->account_id;
    $response = [];
    if($input[type] == "Review"){
        $upvote = RecipeReview::find($input['review_id']);
    } else{
        $upvote = ReplyRecipeReview::find($input['review_id']);
    }
    if ($upvote){
        $review_upvote = ReviewUpvote::where('review_id', $input['review_id'])
                       ->where('client_id', $user_id)
                       ->where('type',$input['type'])
                       ->first();
        if(!$review_upvote){
              $total = $upvote->getUpvoteCount() + 1;
              $upvote->update([
                 'upvote' => $total
              ]);
              $review_upvote = new ReviewUpvote();
              $review_upvote->review_id = $input['review_id'];
              $review_upvote->client_id = $user_id;
              $review_upvote->type = $input['type'];
              if ($review_upvote->save()){
                  $response['status'] = 'success';
              }
           }
        if ($response['status'] == 'success'){
              $response['upvote'] = $total;
            // $response['upvote'] = $upvote->getUpvoteCount();
        }
     }
     return response()->json($response);
  } 

  public function starRating(Request $request){
      $response['status'] = 'error';
      $input = $request->formData;
      $user_id = Auth::user()->account_id;
      $response = [];
      $rating = RecipeRating::where('meal_id',$input['recipe_id'])
                ->where('client_id',$user_id)
                ->first();
           
       if(!$rating){
            $createRating = RecipeRating::create([
                'meal_id'=>$input['recipe_id'],
                'client_id'=>$user_id,
                'star'=>$input['star'],
            ]);
           $response['status'] = 'success';
       } else { 
          $rating->update([
            'star'=>$input['star'],
          ]);
         $response['status'] = 'success';
       }
     return response()->json($response);
    
  }

  public function reviewFilter(Request $request){
      $input = $request->formData;
      $dropdown_val = $input['option'];
      if($input['option'] =='Oldest'){
        $review = RecipeReview::with('replyRecipeReview')
               ->where('meal_id',$input['recipe_id'])
               ->orderBy('id','asc')
               ->get();
      }elseif ($input['option'] =='Newest') {
        $review = RecipeReview::with('replyRecipeReview')
                ->where('meal_id',$input['recipe_id'])
                ->orderBy('id','desc')
                ->get();
      }else{
        $review = RecipeReview::with('replyRecipeReview')
               ->where('meal_id',$input['recipe_id'])
               ->orderBy('upvote','desc')
               ->orderBy('reply_count','desc')
               ->get();

       }
       $auth_user = Clients::select('id','firstname','lastname','profilepic')
                    ->where('id', Auth::user()->account_id)
                    ->first();
       $html = View::make('Result.mealplanner.recipe-filter', compact('review','auth_user','dropdown_val'));
       $response['review'] = $html->render();
       $response['status'] = 'success';
       return response()->json($response);
  }
  /* end */
}
