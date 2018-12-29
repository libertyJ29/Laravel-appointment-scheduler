@extends('layouts.master')
@section('content')

<h3>
    Edit Patient Record
</h3>
<!--Ensure all required fields are entered-->
@if($errors->any())
<div class="alert alert-danger">
    @foreach($errors->all() as $error)
        <p>{{ $error }}</p>
    @endforeach
</div>
@endif
<button class="btn btn-info margin-bottom-15" onclick="history.back();" name="action", value="back"><span class="glyphicon glyphicon-chevron-left"></span> Go Back</button>
{!! Form::model($patient, [
'method' => 'PATCH',
'route' => ['patients.update', $patient->id]
]) !!}
<div class="form-group">
    {!! Form::label('name', 'Name:', ['class' => 'control-label col-sm-2']) !!}
    <div class = "col-sm-10">
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('surname', 'Surname:', ['class' => 'control-label col-sm-2']) !!}
    <div class = "col-sm-10">
        {!! Form::text('surname', null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('address', 'Address:', ['class' => 'control-label col-sm-2']) !!}
    <div class = "col-sm-10">
        {!! Form::text('address', null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('city', 'City:', ['class' => 'control-label col-sm-2']) !!}
    <div class = "col-sm-10">
        {!! Form::text('city', null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('county', 'County:', ['class' => 'control-label col-sm-2']) !!}
    <div class = "col-sm-10">
        {!! Form::text('county', null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('country', 'Country:', ['class' => 'control-label col-sm-2']) !!}
    <div class = "col-sm-10">
        {!! Form::text('country', null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('postcode', 'Postcode:', ['class' => 'control-label col-sm-2']) !!}
    <div class = "col-sm-10">
        {!! Form::text('postcode', null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('mobilenumber', 'Mobile Number:', ['class' => 'control-label col-sm-2']) !!}
    <div class = "col-sm-10">
        {!! Form::text('mobilenumber', null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('email', 'Email Address:', ['class' => 'control-label col-sm-2']) !!}
    <div class = "col-sm-10">
        {!! Form::text('email', null, ['class' => 'form-control']) !!}
    </div>
</div>
<script>
    var date_of = '<?php echo $patient->dateofbirth; ?>';
    var patientrecord = 'true';
</script>
<div class="form-group">
    {!! Form::label('dateofbirth', 'Date Of Birth (DD/MM/YYYY):', ['class' => 'control-label col-sm-2']) !!}
    <div class = "col-sm-10">
        <span class="inner-addon left-addon">
        <span class="glyphicon glyphicon-calendar"></span>
        {!! Form::text('dateofbirth', '', array('id' => 'datepicker', 'class' => 'form-control formcontrol-input-formatting textleft black-icon')) !!} 
        </span>
    </div>
</div>
<div class="form-group">
    <div class = "col-sm-12">
    </div>
</div>
<div class="form-group">
    {!! Form::label('currentpatient', 'Current Patient:', ['class' => 'control-label col-sm-2']) !!}
    <div class = "col-sm-2">
        {!! Form::select('currentpatient', array(
        'Please Select' => array('1' => 'Yes', '0' => 'No')
        ), null, ['class' => 'form-control'])  !!}
    </div>
</div>
<div class="form-group">
    <div class = "col-sm-12">
    </div>
</div>
<div class="form-group">
    <div class = "col-sm-10">
        {!! Form::submit('Update Patient Record', ['class' => 'btn btn-primary', 'name'=>'action', 'value'=>'update']) !!}
        {!! Form::close() !!}
    </div>
</div>

@stop