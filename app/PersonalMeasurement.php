<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonalMeasurement extends Model
{
    use SoftDeletes;
    protected $table = 'personal_measurements';

    protected $guarded = [];
}
