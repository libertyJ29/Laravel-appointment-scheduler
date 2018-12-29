@extends('layouts.master')
@section('content')

<div class = "col-sm-10">
    <h3>
        Patient Records
    </h3>
    <a href="{{ route('patients.create') }}" class="btn btn-info">Add New Patient</a>
    <a href="{{ route('patients.search', ['1,create']) }}" class="btn btn-warning">Search for a Patient</a>
    <a href="{{ route('patients.all') }}" class="btn btn-primary">Show all Current Patient Records</a>
    <br><br>
</div>
<div class = "col-sm-9">
    <div class="alert alert-info">
        To Make a new appointment, "Search for a patient" by Surname. Then click "Create Appointment".<br><br>
        To Modify an existing appointment, "Search for a patient" by Surname. Click "Show all Appointments". Then click "Edit Appointment" in the Appointment List.<br>
    </div>
</div>

@stop