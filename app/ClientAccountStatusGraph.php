<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientAccountStatusGraph extends Model
{
    protected $table = 'client_account_status_graph';

    protected $fillable = ['business_id', 'client_id', 'account_status', 'created_at', 'updated_at']; 
}
