@extends('layouts.master')
@section('content')

<h3>
    Search for Patient Record
</h3>
<button class="btn btn-info" onclick="history.back();"><span class="glyphicon glyphicon-chevron-left"></span> Go Back</button>
<hr>
@foreach($patients as $patient)
    <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-info">Edit Patient Record</a>
    <a href="{{ route('patients.showrecord', [$patient->id, $appointment_id, $type]) }}" class="btn btn-primary">Show Patient Record</a>
    <?php 
        if($type == 'edit'){
        ?>
    <a href="{{ route('appointments.edit', [$appointment_id, '00', $patient->id, '1']) }}" class="btn btn-warning">Create Appointment</a>
    <?php
        }else{
            ?>
    <a href="{{ route('appointments.create', [$patient->id, '00', '1']) }}" class="btn btn-warning">Create  Appointment</a>
    <?php 
        } 
        ?>
    <a href="{{ route('appointments.show', [$patient->id, '00', '1', $type]) }}" class="btn btn-primary">Show Appointments List</a>
    <b>{{ $patient->name }} {{ $patient->surname }}</b>,
    {{ $patient->address}},
    {{ $patient->mobilenumber}}
    <hr>
@endforeach

@stop