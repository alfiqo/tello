<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class TrelloWebHook extends Model
{
    protected $fillable = ['idBoard'];
}
