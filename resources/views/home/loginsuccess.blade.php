<?php 
    //redirect to appointment calendar page after 2 seconds
    header( "refresh:2; url=$redirectroute" ); 
    ?>

@extends('layouts.master')
@section('content')

<?php 
    if (Auth::check()) {
        echo "<h3>You are now logged in : ".Auth::user()->name."</h3>";
    }else{
        echo "<h3>Not logged in</h3>";
    }
    ?>

@stop