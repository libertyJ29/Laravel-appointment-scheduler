<?php 
    //redirect to appointments index page after 3 seconds
    header( "refresh:3; url=$redirectroute" ); 
    ?>

@extends('layouts.master')
@section('content')

<h3>
    The appointment for {{$patient->name}} {{$patient->surname}} on {{$appointment->date_of_appointment}} @ {{$appointment->time_of_appointment}} was successfully 
        <?php 
            if ($type=='create'){ 
            	echo "created";
            }else{ 
            	echo "updated";
            } ?>
</h3>


@stop