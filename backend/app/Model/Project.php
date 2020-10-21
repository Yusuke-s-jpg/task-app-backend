<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title',
        'description',
        'state',
        'user_id'
    ];

    public function issues()
    {
        return $this->hasMany('App\Model\Issue');
    }
}
