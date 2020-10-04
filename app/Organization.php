<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = ['idModel','name','displayName','teamType','desc','url','boards','memberships'];
}
