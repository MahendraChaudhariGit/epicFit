<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PersonalDiary extends Model
{
    use SoftDeletes;

    protected $table = 'personal_diaries';

    protected $guarded = [];
}
