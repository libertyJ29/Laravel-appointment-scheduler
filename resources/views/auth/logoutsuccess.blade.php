<?php 
    //redirect to login page after 2 seconds
    header( "refresh:2; url=$redirectroute" ); 
    ?>

@extends('layouts.master')
@section('content')

<h3>
    You have successfully logged out of the system
</h3>

@stop