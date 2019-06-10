<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{

    protected $table = 'links';

    public function title()
    {
        return $this->belongsTo('App\Title', 'title_id');
    }
}
