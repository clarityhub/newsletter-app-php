<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    public $fillable = [
        'issue_id',
        'user_id',
    ];
}
