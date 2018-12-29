<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'treatments';
    protected $fillable = array('treatment', 'price', 'created_at', 'updated_at');

}
