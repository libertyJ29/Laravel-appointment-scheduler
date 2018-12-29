<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'appointments';
    protected $fillable = array('patient_id', 'treatment_type', 'dentist', 'invoice_amount', 'payment_method', 'date_of_appointment', 'time_of_appointment', 'checked_in', 'created_at', 'updated_at');


 public function appointments()
    {
         return $this->belongsTo('App\Patient');
    }
}
