<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dentist extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'dentists';
    protected $fillable = array('name', 'surname', 'address', 'city', 'county', 'country', 'postcode', 'mobilenumber' , 'email', 'dateofbirth', 'currentlyactive', 'role', 'treatments', 'created_at', 'updated_at');

     public function dentists()
    {
        return $this->hasMany('App\Patient', 'id');
    }
}
