@extends('layouts.master')
@section('content')

<h3>
    Showing all appointment records for patient {{$patient->name. ' ' .$patient->surname. ', ' .$patient->address }}
</h3>
<button class="btn btn-info" onclick="history.back();"><span class="glyphicon glyphicon-chevron-left"></span> Go Back</button>
<a href="{{ route('patients.showrecord', [$patient->id, $appointment_id, $type]) }}" class="btn btn-primary">Show Patient Record</a>
<?php 
if($type == 'edit'){
    ?>
    <a href="{{ route('appointments.edit', [$appointment_id, '00', $patient->id, '1']) }}" class="btn btn-warning">Create Appointment</a>
    <?php
}else{
    ?>
    <a href="{{ route('appointments.create', [$patient->id, '00', '1']) }}" class="btn btn-warning">Create Appointment</a>
    <?php 
} ?>
<a href="{{ route('patients.search', [$appointment_id, $type]) }}" class="btn btn-primary">Search for different Patient</a>
<hr>
@foreach($appointments as $appointment)
    {!! Form::open([
    'method' => 'DELETE',
    'route' => ['appointments.destroy', $appointment->id]
    ]) !!}
    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#myModal<?php echo $appointment->id;?>">DELETE Appointment</button>
    <!-- Modal -->
    <div class="modal fade" id="myModal<?php echo $appointment->id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel<?php echo $appointment->id;?>">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel<?php echo $appointment->id;?>">Are you sure you want to delete this record for Patient <b><?php echo $patient->name." ".$patient->surname;?></b> - Appointment <?php echo $appointment->id;?> on <b><?php echo $appointment->date_of_appointment;?></b> at <b><?php echo $appointment->time_of_appointment;?></b>?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                {!! Form::submit('YES, DELETE Appointment', ['class' => 'btn btn-danger']) !!}<br>
            </div>
        </div>
    </div>
    </div>
    <a href="{{ route('appointments.edit', [$appointment->id, '00', $patient->id, '1']) }}" class="btn btn-primary">Edit Appointment</a>
    <a href="{{ route('appointments.showrecord', [$appointment->id, $appointment_id, $type]) }}" class="btn btn-info">Show Appointment</a>
    <b>Date of Appointment: </b>{{ $appointment->date_of_appointment }}, 
    <b>Time of Appointment: </b>{{ $appointment->time_of_appointment }}, 
    <?php $appointment->treatment_type ? $treatmenttype = $appointment->treatment_type : $treatmenttype = 'Undefined';?>
    <b>Treatment: </b>{{ $treatmenttype }}
    <br><br>
    <hr class="zero">
    <br>
@endforeach
<br>

@stop