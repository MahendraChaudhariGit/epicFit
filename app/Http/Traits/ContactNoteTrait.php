<?php
namespace App\Http\Traits;

use Illuminate\Http\Request;
use App\ContactNotes;
use Auth;

trait ContactNoteTrait{
    protected function storeContactNote($param){
        $data = array(
            'user_id' => Auth::id(),
            'client_id' => $param['clientId'],
            'status' => $param['status']
        );
        if(array_key_exists("note", $param) && $param['note'])
            $data['notes'] = $param['note'];
        else
            $data['notes'] = null;

        if(array_key_exists("contactResult", $param) && $param['contactResult'])
            $data['contactResult'] = $param['contactResult'];

        if(array_key_exists("callback", $param) && $param['callback'])
            $data['callback'] = $param['callback'];

        if(array_key_exists("callbackTime", $param) && $param['callbackTime'])
            $data['callback_time'] = $param['callbackTime'];

        $createdContact = ContactNotes::create($data);
        return ['createdDatetime' => $createdContact->created_at/*, 'createdDatetimeUi' => $createdContact->createdDateUi*/, 'id' => $createdContact->id];
    }
}