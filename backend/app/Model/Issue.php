<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    protected $fillable = [
        'ordering',
        'title',
        'description',
        'state',
        'project_id'
    ];

    public function project()
    {
        return $this->belongsTo('App\Model\Project');
    }
}