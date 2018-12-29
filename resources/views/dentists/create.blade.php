@extends('layouts.master')
@section('content')

<html ng-app="dentistsApp">
    <h3>
        Add New Staff Record
    </h3>
    <div class = "col-sm-12 nopaddingleft">
        <span class="inner-addon left-addon white-icon">
            <i class="glyphicon glyphicon-chevron-left"></i>
            <input class="btn btn-info textleft inputbutton" onclick="window.location='{!! route('dentists.index'); !!}'" value="Go Back"></input>
        </span>
    </div>
    <br><br><br>
    <!--Ensure all required fields are entered-->
    @if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
        <p>{{ $error }}</p>
        @endforeach
    </div>
    @endif
    {!! Form::open([
    'route' => 'dentists.store'
    ]) !!}
    <div class="form-group">
        {!! Form::label('name', 'Name:', ['class' => 'control-label col-sm-2']) !!}
        <div class = "col-sm-2">
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('surname', 'Surname:', ['class' => 'control-label col-sm-2']) !!}
        <div class = "col-sm-2">
            {!! Form::text('surname', null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class = "col-sm-12"></div>
    <div class="form-group">
        {!! Form::label('address', 'Address:', ['class' => 'control-label col-sm-2']) !!}
        <div class = "col-sm-2">
            {!! Form::text('address', null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class = "col-sm-12"></div>
    <div class="form-group">
        {!! Form::label('city', 'City:', ['class' => 'control-label col-sm-2']) !!}
        <div class = "col-sm-2">
            {!! Form::text('city', null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('county', 'County:', ['class' => 'control-label col-sm-2']) !!}
        <div class = "col-sm-2">
            {!! Form::text('county', null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class = "col-sm-12"></div>
    <div class="form-group">
        {!! Form::label('country', 'Country:', ['class' => 'control-label col-sm-2']) !!}
        <div class = "col-sm-2">
            {!! Form::text('country', null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('postcode', 'Postcode:', ['class' => 'control-label col-sm-2']) !!}
        <div class = "col-sm-2">
            {!! Form::text('postcode', null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class = "col-sm-12"></div>
    <div class="form-group">
        {!! Form::label('mobilenumber', 'Mobile Number:', ['class' => 'control-label col-sm-2']) !!}
        <div class = "col-sm-2">
            {!! Form::text('mobilenumber', null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class = "col-sm-12"></div>
    <div class="form-group">
        {!! Form::label('email', 'Email Address:', ['class' => 'control-label col-sm-2']) !!}
        <div class = "col-sm-2">
            {!! Form::text('email', null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class = "col-sm-12"></div>
    <script>
        var patientrecord = 'true';
    </script>
    <div class="form-group">
        {!! Form::label('dateofbirth', 'Date Of Birth:', ['class' => 'control-label col-sm-2']) !!}
        <div class = "col-sm-3">
            <span class="inner-addon left-addon">
            <span class="glyphicon glyphicon-calendar"></span>
            {!! Form::text('dateofbirth', '', ['id' => 'datepicker', 'class' => 'form-control formcontrol-input-formatting formcontrol-input-formatting-width textleft black-icon']) !!} 
            </span>
            <div class = "col-sm-9">
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class = "col-sm-12">
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('currentlyactive', 'Working Here:', ['class' => 'control-label col-sm-2']) !!}
        <div class = "col-sm-2">
            {!! Form::select('currentlyactive', array(
            'Please Select',
            '1' => 'Yes',
            '0' => 'No'
            ), null, ['class' => 'form-control'])  !!}
        </div>
    </div>
    <div class="form-group">
        <div class = "col-sm-12">
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('Job Role', 'Role:', ['class' => 'control-label col-sm-2']) !!}
        <div class = "col-sm-2">
            {!! Form::select('role', array(
            '' => 'Please Select',
            'Dentist' => 'Dentist',
            'Hygienist' => 'Hygienist',
            'Receptionist' => 'Receptionist'
            ), null, ['class' => 'form-control'])  !!}
        </div>
    </div>
    <div class="form-group">
        <div class = "col-sm-12">
        </div>
    </div>
    <!--display all the dentist treatments from the db-->
    {!! Form::label('treatments', 'Treatments Offered:', ['class' => 'control-label col-sm-2']) !!}
    <div class = "col-sm-2">
        <b id="treatmentsCtrl" ng-controller="treatmentsCtrl" ng-cloak>
            <!--ng-cloak so nothing shows before fully loaded-->
            <div ng-repeat ="treatment in data  | orderBy : 'treatment'">
                <input name="selectedtreatments[]" type="checkbox" value="@{{treatment.id}}" id="@{{treatment.id}}"> &nbsp;@{{treatment.treatment}}
            </div>
    </div>
    <div class = "col-sm-12">&nbsp;
    </div>
    <div class = "col-sm-4">
        {!! Form::submit('Add New Staff Record', ['class' => 'btn btn-primary']) !!}
    </div>
    </form>
    <!--add new dentist treatment to db-->
    <form name="newtreatment" ng-submit="addNewTreatment(treatment_name)">
        <div class = "col-sm-5">
            <input class="form-control formcontrol-input-addnewtreatment-width" type="text" ng-model="treatment_name" placeholder="Enter New Treatment Here">
            <input class="btn btn-primary" type="submit" value="Add New Treatment">
        </div>
    </form>
    </b>
    <br><br>
    <!--get all treatments using angular-->
    <script>
        $(function(){
        	angular.element('#treatmentsCtrl').scope().getAllTreatmentsWithVars();
        });
    </script>

@stop