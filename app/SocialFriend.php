<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
class SocialFriend extends Model
{
    protected $table = 'social_friends';
    protected $guarded = [];

    public function clients(){
        return $this->hasOne('App\Clients','id','added_client_id')->select('id','business_id','firstname','lastname','email','phonenumber','address1','gender','birthday','country','profilepic','about_me','address_city');
    }
    public function clients_recieve_request(){
        return $this->hasOne('App\Clients','id','client_id')->select('id','business_id','firstname','lastname','email','phonenumber','address1','gender','birthday','country','profilepic','about_me','address_city');
    }
}
