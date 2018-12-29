@extends('layouts.master')
@section('content')

<h3>
    Search Patient Records
</h3>
Please enter a Patients Surname to search for in the database
{!! Form::open ([ 
'route' => ['patients.show',$appointment_id,$type ]
]) !!}
<div class="form-group">
    {!! Form::label('surname', 'Surname:', ['class' => 'control-label col-sm-2']) !!}
    <div class = "col-sm-4">
        {!! Form::text('surname', null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    <div class = "col-sm-10">
        {!! Form::submit('Search for Patient', ['class' => 'btn btn-primary']) !!}
    </div>
</div>
{!! Form::close() !!}

@stop