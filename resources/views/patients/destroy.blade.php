<?php 
    //redirect to patients index page after 2 seconds
    header( "refresh:2; url=$redirectroute" ); 
    ?>

@extends('layouts.master')
@section('content')

<h3>
    Patient Record for {{$patientd->name}} {{$patientd->surname}} Deleted
</h3>

@stop