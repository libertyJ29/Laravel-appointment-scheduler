@extends('layouts.master')
@section('content')

<h3>
    Showing all Current Patient Records
</h3>
<hr>
<table id="patientsall" class="display" cellspacing="0">
    <thead>
        <tr>
            <th>Delete Record</th>
            <th>Edit Record</th>
            <th>Show Record</th>
            <th>Show all Appointments</th>
            <th>Patient Information</th>
        </tr>
    </thead>
    <tbody>
        @foreach($patients as $patient)
        <tr>
            <td>
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#myModal<?php echo $patient->id;?>">DELETE Record</button>
                {!! Form::open([
                'method' => 'DELETE',
                'route' => ['patients.destroy', $patient->id]
                ]) !!}
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
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </td>
            <td><a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-primary">Edit Record</a></td>
            <td><a href="{{ route('patients.showrecord', [$patient->id, '1', 'create']) }}" class="btn btn-info">Show Record</a></td>
            <td><a href="{{ route('appointments.show', [$patient->id, '00', '1', 'create']) }}" class="btn btn-warning">Show all Appointments</a> </td>
            <td><b>{{ $patient->name }} {{ $patient->surname }}</b>
                {{ $patient->address}}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<hr>

@stop