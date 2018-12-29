<?php 
    //redirect to appointments index page after 3 seconds
    header( "refresh:3; url=$redirectroute" );  
    ?>

@extends('layouts.master')
@section('content')

<h3>
    New Appointment for {{$patient->name}} {{$patient->surname}} on {{$appointment->date_of_appointment}} @ {{$appointment->time_of_appointment}} successfully created
</h3>

@stop