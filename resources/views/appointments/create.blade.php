@extends('layouts.master')
@section('content')

<html ng-app="appointmentsApp">
    <h3>
        Create New Patient Appointment
    </h3>
    <!--Ensure all required fields are entered-->
    @if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
    @endif
    <button class="btn btn-info margin-bottom-15" onclick="history.back();"><span class="glyphicon glyphicon-chevron-left"></span> Go Back</button>
    <?php 
        if ($newtime == '00'){
        	$newtime = '09:00:00';
        }
        //$newdate == '' before delete the date after a validation error, so set it to current date if none supplied
        if ($newdate == '1'){
        	$newdate = date('d-m-Y');
        }
        ?>
    {!! Form::open([
    'route' => 'appointments.store'
    ]) !!}
    {!! Form::model($patient, [
    'method' => 'GET',
    'route' => ['appointments.create', $patient->id]
    ]) !!}
    {!! Form::hidden('patient_id', $patient->id) !!}
    <?php 
        if ($patient->id != 1)
        {
        	$fullname = $patient->name . ' ' . $patient->surname . ' - ' . $patient->address . ', ' .  $patient->postcode;
        }
        else
        {
        	$fullname = "Please select a patient";
        }
        ?>
    <div class="form-group">
        {!! Form::label('patient_name', 'Patient:', ['class' => 'control-label col-sm-2']) !!}
        <div class = col-sm-6>
            {!! Form::text('patient_name', $fullname, ['class' => 'form-control', 'readonly' => true]) !!}
            {{-- save all the values to temp appointment record 1 here, if click button below --}}
            <input type = "submit" class = "btn btn-info"  name = "submitbtn" value = "Select patient">
            </input>
        </div>
    </div>
    <div class="form-group">
        <div class = "col-sm-12">
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('treatment_type', 'Treatment Type:', ['class' => 'control-label col-sm-2']) !!}
        <div class = "col-sm-2">
            <i id="appointmentsCtrl1" ng-controller="appointmentsCtrl" ng-init="$scope.checker = false; $parent.treatment.newprice = ''; treatment_type = '0'; paymentstatusflag = ''; treatments=<?php echo htmlspecialchars(json_encode($treatments)) ?>; ">
                <select ng-change="gettreatmentprice(treatment_type)" ng-model = "treatment_type" class="form-control" id="treatment_type" name="treatment_type">
                    <option value="0">Please Select</option>
                    <option ng-repeat="treatment in treatments"  value="@{{treatment.treatment}}">
                        @{{treatment.treatment}} 
                    </option>
                </select>
        </div>
    </div>
    <div class="form-group">
        <div class = "col-sm-12">
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('dentists', 'Dentist:', ['class' => 'control-label col-sm-2 pull-left']) !!}
        <div class = "col-sm-2">
            <select class="form-control" name="dentist">
            <option value=''>Please Select</option>
            <optgroup label="Dentist">
            @foreach($dentists as $dentist)
                <option value="{{$dentist->id}}"  
                @if ($dentist->id == 1)
                    selected="selected"
                @endif
                >{{$dentist->name." ".$dentist->surname}}</option>
            @endforeach
            </optgroup>
            <optgroup label="Hygienist">
            @foreach($hygienists as $hygienist)
                <option value="{{$hygienist->id}}">{{$hygienist->name." ".$hygienist->surname}}</option>
            @endforeach
            </optgroup>
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class = "col-sm-12">
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('payment_method', 'Payment Method:', ['class' => 'control-label col-sm-2 pull-left']) !!}
        <div class = "col-sm-2">
            {!! Form::select('payment_method', array(
                'Please Select',
                'Card' => array('Visa' => 'Visa', 'Mastercard' => 'Mastercard'),
                'Cash' => array('Cash' => 'Cash', 'Cheque' => 'Cheque')
            ), null, ['class' => 'form-control'])  !!}
        </div>
    </div>
    <div class="form-group">
        <div class = "col-sm-12">
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('invoice_amount', 'Invoice Amount: (&pound;)', ['class' => 'control-label col-sm-2 pull-left']) !!}
        <div class = "col-sm-2">
            <input class="form-control" name="invoice_amount" ng-model="treatment.newprice" type="text" data-toggle="popover" data-trigger="focus" data-content='<?php foreach ($treatments as $treatment){ echo $treatment->treatment.'&nbsp;&#163;'.$treatment->price.'<br>';}?>' id="invoice_amount">
        </div>
        <div class = 'col-sm-2'>
            <input ng-model="$scope.appointmentid" value="1" class="hidden" ng-init="$scope.appointmentid = '1'">
            <div ng-if="paymentstatusflag != '1'">
                <input class = 'btn btn-warning' ng-click="paidbutton($scope.appointmentid)" value = 'Mark This as Paid' ng-init="paidbuttontext = 'Invoice Not Paid Yet!';">
                </input>
            </div>
            <div ng-if="paymentstatusflag == '1'">
                <input class = 'btn btn-info' ng-click="paidbutton($scope.appointmentid)" value = 'Invoice Paid in Full' ng-init="paidbuttontext = 'Paid in Full';">
                </input>
            </div>
            </input>
        </i>
        </div>
    </div>
    <div class="form-group">
        <div class = "col-sm-12">
        </div>
    </div>
    <script>
        var patientrecord = '';
        var appointmentrecord = 'true';
        var date_of = '<?php echo $newdate; ?>';
    </script>
    @{{$scope.checker = false;
    scope.$apply();}}
    <div class="form-group">
        {!! Form::label('date_of_appointment', 'Date of Appointment:', ['class' => 'control-label col-sm-2']) !!}
        <div class = "col-sm-10">
            <span class="inner-addon left-addon">
            <span class="glyphicon glyphicon-calendar"></span>
            {!! Form::text('date_of_appointment', '$newdate', ['id' => 'datepicker', 'class' => 'form-control formcontrol-input-formatting textleft marginright25']) !!} 
            </span>
            <b id="appointmentsCtrl" ng-controller="appointmentsCtrl" ng-init="$scope.checker = false">
                Appointments Booked on date: @{{data.length}}
                <div ng-repeat="appointment in data" ng-if="appointment.time_of_appointment == '<?php echo $newtime;?>'">
                    @{{$scope.checker = true;""}}
                </div>
                <div ng-if= "$scope.checker == true">
                    <font color=red>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    The time is booked already
                    </font>
                </div>
                <div ng-if= "$scope.checker == false">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    The time is available
                </div>
                {{--reset the value of checker to its default after use--}}
                @{{$scope.checker = false;""}} 
            </b>
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('time_of_appointment', 'Time of Appointment:', ['class' => 'control-label col-sm-2']) !!}
        <div class = "col-sm-10">
            {!! Form::text('time_of_appointment', $newtime, $attributes = ['class' => 'form-control formcontrol-input-formatting marginright25', 'readonly' => true]) !!}
            {{-- if click button below, save all the values to temp appointment record 1 --}}
            <input type = "submit" class = "btn btn-info"  name = "submitbtn" value = "Select appointment time">
            </input>
        </div>
    </div>
    <div class="form-group">
        <div class = "col-sm-12">
        </div>
    </div>
    <div class="form-group">
        <div class = "col-sm-10">
            {!! Form::submit('Create New Appointment', ['class' => 'btn btn-primary', 'value' => 'SubmitFinal', 'name' => 'submitbtn']) !!}
        </div>
    </div>
    {!! Form::close() !!}

@stop