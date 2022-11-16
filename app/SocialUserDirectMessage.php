<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialUserDirectMessage extends Model
{
    // protected $fillable = ['sender_user_id', 'receiver_user_id', 'file' ,'thumbnail','file_type','message'];
    protected $guarded = [];
    public function dataImport($data = []){
        foreach($data as $key => $value) {
            $this->$key = $value;
        }
    }
    // public function searchDataImport($data = [], $search_data){
    //      dd($data ,$search_data );
    //     if (count($search_data) > 0) { 
    //         foreach($data as $key => $value) { 
    //             foreach($search_data as $key1 => $val) {  
    //                 dd($val->id, $value);
    //                 if($value == $val->id){
    //                     $this->$key = $value;
    //                 }
    //             }
    //         }
    //     }
    // }

    public function sender(){
        return $this->belongsTo('App\Clients', 'sender_user_id')->select('id','firstname','lastname','profilepic');
    }

    public function receiver(){
        return $this->belongsTo('App\Clients', 'receiver_user_id')->select('id','firstname','lastname','profilepic');
    }
}
