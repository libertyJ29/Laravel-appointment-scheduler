<?php 
    //redirect to appointments index page after 3 seconds
    header( "refresh:3; url=$redirectroute" );
    ?>

@extends('layouts.master')
@section('content')

<h3>
    The Appointment for {{$patient->name}} {{$patient->surname}} on {{$appointmentcopy->date_of_appointment}} @ {{$appointmentcopy->time_of_appointment}} was deleted
</h3>

@stop