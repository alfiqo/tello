<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Board extends Model
{
    protected $fillable = ['idModel', 'idOrganization', 'name', 'desc', 'url'];
}
