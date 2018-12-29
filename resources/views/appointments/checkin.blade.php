@extends('layouts.master')
@section('content')

<h3>
    Check-in Patient
</h3>
Appointment
<?php 
    $patientname = $patient->name. ' ' .$patient->surname;
    
    if ($appointment->checked_in == 'Yes')
    {
    	echo "Patient $patientname is Now Checked In.";
    }
    else
    {
    	echo "Patient $patientname is Now Checked Out.";
    }
    ?>

@stop