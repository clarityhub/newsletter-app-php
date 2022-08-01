<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    public $fillable = [
        'type',
        'html_url',
        'url', 
        'repository_url', 
        'repository_title', 
        'repository_description',
        'title', 
        'description',
        'html_description',
        'html_short_description',
    ];
    
    public $type = 'github';

    public function campaigns()
    {
        return $this->belongsToMany('App\Campaign')->withPivot('order_by');
    }

    public function votes()
    {
        return $this->hasMany('App\Vote');
    }
}
