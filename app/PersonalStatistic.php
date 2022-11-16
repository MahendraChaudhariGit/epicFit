<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonalStatistic extends Model
{
    use SoftDeletes;
    protected $table = 'personal_statistics';

    protected $guarded = [];
}
 