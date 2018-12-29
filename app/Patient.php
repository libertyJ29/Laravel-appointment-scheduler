<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'patients';
    protected $fillable = array('name', 'surname', 'address', 'city', 'county', 'country', 'postcode', 'mobilenumber' , 'email', 'dateofbirth', 'currentpatient', 'created_at', 'updated_at');

 public function patients()
    {
        return $this->hasMany('App\Appointment', 'id');
    }
}
