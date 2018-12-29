@extends('layouts.master')
@section('content')

<h2>Login to System</h2>
{!! Form::open ([
'route' => ['patients.show']
]) !!}
<div class="form-group">
    {!! Form::label('username', 'Username:', ['class' => 'control-label col-sm-2']) !!}
    <div class = "col-sm-10">
        {!! Form::text('username', null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('password', 'Password:', ['class' => 'control-label col-sm-2']) !!}
    <div class = "col-sm-10">
        {!! Form::text('password', null, ['class' => 'form-control']) !!}
    </div>
</div>
{!! Form::submit('Login', ['class' => 'btn btn-primary']) !!}
{!! Form::close() !!}

@stop