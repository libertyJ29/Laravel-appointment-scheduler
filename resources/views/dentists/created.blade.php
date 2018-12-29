<?php 
    //redirect to dentists index page after 2 seconds
    header( "refresh:2; url=$redirectroute" ); 
    ?>

@extends('layouts.master')
@section('content')

<h3>
    Staff Record for <?php echo $dentist->name." ".$dentist->surname ; ?> added to database
</h3>

@stop