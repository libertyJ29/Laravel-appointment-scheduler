@extends('layouts.master')
@section('content')

<h3>
    Showing all Appointment Records
</h3>
<hr>
<table id="appointmentsall" class="display" cellspacing="0">
    <thead>
        <tr>
            <th>Show Patient Record</th>
            <th>Edit Appointment</th>
            <th>Show Appointment</th>
            <th>Delete Appointment</th>
            <th>Appointment Information</th>
        </tr>
    </thead>
    <tbody>
        @foreach($appointments as $appointment)
        <?php $patient = $patients->find( $appointment->patient_id)  ?>
        <!--if patient record has been deleted, then create a blank record-->
        @if ($patient === null)
            <?php $patient = $patients->find(1); ?>
            <?php $patient->name = "Record";?>
            <?php $patient->surname = "Deleted";?>
        @endif
        <tr>
            {!! Form::open([
            'method' => 'DELETE',
            'route' => ['appointments.destroy', $appointment->id]
            ]) !!}
            <td><a href="{{ route('patients.showrecord', [$appointment->patient_id, '1', 'create']) }}" class="btn btn-info">Show Patient Record</a></td>
            <!--pass default argument for the time-->
            <td><a href="{{ route('appointments.edit', [$appointment->id, '00', '1', '1']) }}" class="btn btn-primary">Edit Appointment</a></td>
            <td><a href="{{ route('appointments.showrecord', [$appointment->id, '1', 'create']) }}" class="btn btn-primary">Show Appointment</a></td>
            <td><button type="button" class="btn btn-danger" data-toggle="modal" data-target="#myModal<?php echo $appointment->id;?>">DELETE Appointment</button></td>
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
            {!! Form::close() !!}
            <td><b>Patient:</b>
                {{$patient->name }} {{$patient->surname.","}}
                <b>Appointment:</b> {{ $appointment->date_of_appointment}}, 
                {{ $appointment->time_of_appointment}}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<br>

@stop