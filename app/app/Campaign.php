<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    public $fillable = [
        'title',
        'subject_line',
        'greeting',
        'preview_text',
        'mailchimp_id',
        'mailchimp_url',
    ];

    public function issues()
    {
        return $this->belongsToMany('App\Issue')->withPivot('order_by');
    }
}
