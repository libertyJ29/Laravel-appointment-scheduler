@extends('layouts.master')
@section('content')

<h3>
    Show Patient Record
</h3>
<button class="btn btn-info" onclick="history.back();"><span class="glyphicon glyphicon-chevron-left"></span> Go Back</button>
<br><br>

{!! Form::model($patient, [
'method' => 'GET',
'route' => ['patients.showrecord', $patient->id]
]) !!}

<div class="form-group">
    {!! Form::label('name', 'Name:', ['class' => 'control-label col-sm-2']) !!}
    <div class = "col-sm-10">
        {!! Form::text('name', null, ['class' => 'form-control', 'readonly' => 'true']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('surname', 'Surname:', ['class' => 'control-label col-sm-2']) !!}
    <div class = "col-sm-10">
        {!! Form::text('surname', null, ['class' => 'form-control', 'readonly' => 'true']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('address', 'Address:', ['class' => 'control-label col-sm-2']) !!}
    <div class = "col-sm-10">
        {!! Form::text('address', null, ['class' => 'form-control', 'readonly' => 'true']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('city', 'City:', ['class' => 'control-label col-sm-2']) !!}
    <div class = "col-sm-10">
        {!! Form::text('city', null, ['class' => 'form-control', 'readonly' => 'true']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('county', 'County:', ['class' => 'control-label col-sm-2']) !!}
    <div class = "col-sm-10">
        {!! Form::text('county', null, ['class' => 'form-control', 'readonly' => 'true']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('country', 'Country:', ['class' => 'control-label col-sm-2']) !!}
    <div class = "col-sm-10">
        {!! Form::text('country', null, ['class' => 'form-control', 'readonly' => 'true']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('postcode', 'Postcode:', ['class' => 'control-label col-sm-2']) !!}
    <div class = "col-sm-10">
        {!! Form::text('postcode', null, ['class' => 'form-control', 'readonly' => 'true']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('mobilenumber', 'Mobile Number:', ['class' => 'control-label col-sm-2']) !!}
    <div class = "col-sm-10">
        {!! Form::text('mobilenumber', null, ['class' => 'form-control', 'readonly' => 'true']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('email', 'Email Address:', ['class' => 'control-label col-sm-2']) !!}
    <div class = "col-sm-10">
        {!! Form::text('email', null, ['class' => 'form-control', 'readonly' => 'true']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('dateofbirth', 'Date Of Birth:', ['class' => 'control-label col-sm-2']) !!}
    <div class = "col-sm-10">
        {!! Form::text('dateofbirth', null, ['class' => 'form-control', 'readonly' => 'true']) !!}
    </div>
</div>

<?php
    $current_patient = $patient->currentpatient ? 'Yes' : 'No';
    ?>
<div class="form-group">
    {!! Form::label('currentpatient', 'Current Patient:', ['class' => 'control-label col-sm-2']) !!}
    <div class = "col-sm-10">
        {!! Form::text($current_patient, $current_patient, ['class' => 'form-control', 'readonly' => 'true']) !!}
    </div>
</div>
<div class="form-group">
    <div class = "col-sm-12">
    </div>
</div>
{!! Form::close() !!}
<div class="form-group">
    <div class = "col-sm-2">
    </div>
</div>
<div class="form-group">
    <div class = "col-sm-10">
        <br> 
        {!! Form::open([
        'method' => 'DELETE',
        'route' => ['patients.destroy', $patient->id]
        ]) !!}
        <a href="{{ route('patients.edit', [$patient->id, '00']) }}" class="btn btn-primary">Edit This Record</a>
        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#myModal<?php echo $patient->id;?>">DELETE Record</button>
        <?php 
            if($type == 'edit'){
                ?>
        <a href="{{ route('appointments.edit', [$appointment_id, '00', $patient->id, '1']) }}" class="btn btn-warning">Create Appointment</a>
        <?php
            }else{
                ?>
        <a href="{{ route('appointments.create', [$patient->id, '00', '1']) }}" class="btn btn-warning">Create Appointment</a>
        <?php 
            } 
            ?>
        <a href="{{ route('appointments.show', [$patient->id, '00', $appointment_id, $type]) }}" class="btn btn-primary">Show Appointments List</a>
        <!-- Modal -->
        <div class="modal fade" id="myModal<?php echo $patient->id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel<?php echo $patient->id;?>">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel<?php echo $patient->id;?>">Are you sure you want to delete this record for Patient <b><?php echo $patient->name." ".$patient->surname;?></b>?</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        {!! Form::submit('YES, DELETE Record', ['class' => 'btn btn-danger']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}

@stop