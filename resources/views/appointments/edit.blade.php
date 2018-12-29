@extends('layouts.master')
@section('content')

<html ng-app="appointmentsApp">
    <h3>
        <?php
            echo $title;
            ?>
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
        if ($patient->id != 1){
        	$fullname = $patient->name . ' ' . $patient->surname . ' - ' . $patient->address . ', ' .  $patient->postcode;
        }else{
        	$fullname = "Please select a patient";
        }
        ?>
    {!! Form::model($appointment, [
    'method' => 'PATCH',
    'route' => ['appointments.update', $appointment->id]
    ]) !!}
    {{--only show Booked Appointment if the appointment is already created ie if 'checked_in' has a value stored in it--}}
    <?php if ($originalappointmentid != 1){		
        ?>
    <div class="form-group">
        {!! Form::label('name', 'Booked Appointment:', ['class' => 'control-label col-sm-2']) !!}
        <div class = "col-sm-8">
            {{$originaldate}} at {{$originaltime}}
        </div>
        <div class = "col-sm-12">
        </div>
    </div>
    <?php 
        } ?>
    <div class="form-group">
        {!! Form::label('name', 'Patient:', ['class' => 'control-label col-sm-2']) !!}
        <div class = "col-sm-6">
            {!! Form::text('name', $fullname, ['class' => 'form-control', 'readonly' => true]) !!}
            {{-- save all the values to temp appointment record 1 here, if on click button below --}}
            <input type = "submit" class = "btn btn-info"  name = "submitbtn" value = "Select patient">
            </input>
        </div>
    </div>
    <div class="form-group">
        <div class = "col-sm-12">
        </div>
    </div>
    {!! Form::hidden('patient_id', $patient->id, ['class' => 'form-control', 'readonly' => true]) !!}
    <div class="form-group">
        {!! Form::label('treatment_type', 'Treatment Type:', ['class' => 'control-label  col-sm-2']) !!}
        <div class = "col-sm-2">
            <i id="appointmentsCtrl1" ng-controller="appointmentsCtrl" ng-init="$scope.checker = false; $parent.treatment.newprice = '<?php echo $appointment->invoice_amount ?>'; treatment_type = '<?php echo $appointment->treatment_type ?>'; paymentstatusflag = '<?php echo $appointment->payment_status ?>'; treatments=<?php echo htmlspecialchars(json_encode($treatmentsjson)) ?>; ">
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
    	            <option value="<?php echo $dentist->id;?>"<?php if ($appointment->dentist == $dentist->id){ echo ' selected';}?> >
    	            {{$dentist->name." ".$dentist->surname}}
    	            </option>
                @endforeach
                </optgroup>
                <optgroup label="Hygienist">
                @foreach($hygienists as $hygienist)
    	            <option value="<?php echo $hygienist->id;?>"<?php if ($appointment->dentist == $hygienist->id){ echo ' selected';}?> >{{$hygienist->name." ".$hygienist->surname}}</option>
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
                <input class = 'btn btn-warning' ng-click="paidbutton($scope.appointmentid)" value = 'Mark This as Paid' ng-init="paidbuttontext = 'Invoice Not Paid Yet!'">
                </input>
            </div>
            <div ng-if="paymentstatusflag == '1'">
                <input class = 'btn btn-info' ng-click="paidbutton($scope.appointmentid)" value = 'Invoice Paid in Full' ng-init="paidbuttontext = 'Paid in Full'">
                </input>
            </div>
            </input>
        </div>
    </div>
    </i>
    <div class="form-group">
        <div class = "col-sm-12">
        </div>
    </div>
    <script>
        var patientrecord = '';
        var appointmentrecord = 'true';
        var date_of = '<?php echo $appointment->date_of_appointment; ?>';
        var time_of = '<?php echo $appointment->time_of_appointment; ?>'; 
    </script>
    @{{$scope.checker = false;	{{--default value--}} 
    scope.$apply();}}
    <div class="form-group">
        {!! Form::label('date_of_appointment', 'Date of Appointment:', ['class' => 'control-label col-sm-2']) !!}
        <div class = "col-sm-10">
            <span class="inner-addon left-addon">
            <span class="glyphicon glyphicon-calendar"></span>
            {!! Form::text('date_of_appointment', '', ['id' => 'datepicker', 'class' => 'form-control formcontrol-input-formatting textleft marginright25']) !!} 
            </span>
            <b id="appointmentsCtrl" ng-controller="appointmentsCtrl" ng-init="$scope.checker = false">
                Appointments Booked on date: @{{data.length}}
                {{--<tr ng-repeat="appointment in data"></tr>--}}
                <div ng-repeat="appointment in data" ng-if="appointment.time_of_appointment == '<?php echo $appointment->time_of_appointment;?>'">
                    @{{$scope.checker = true;""}}
                </div>
                {{--appointment time found is the appointment record being edited--}}
                <div ng-repeat="appointment in data" ng-if="appointment.time_of_appointment == '<?php echo $appointment->time_of_appointment;?>' && appointment.id == '<?php echo $originalappointmentid;?>'">
                    {{--replace $appointment->id with $originalid - get the original id in controller and pass it here--}}
                    @{{$scope.checker = same;""}}
                </div>
                <div ng-if= "$scope.checker == true"><font color=red>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    The time is booked by another appointment</font>
                </div>
                <div ng-if= "$scope.checker == same"><font color=red>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <u>The time is booked for this appointment</u></font>
                </div>
                <div ng-if= "$scope.checker == false">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    The time is available
                </div>
                {{--reset value of checker to its default after use--}}
                @{{$scope.checker = false;""}} 
            </b>
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('time_of_appointment', 'Time of Appointment:', ['class' => 'control-label col-sm-2']) !!}
        <div class = "col-sm-10">
            {!! Form::text('time_of_appointment', null, ['class' => 'form-control formcontrol-input-formatting marginright25', 'readonly' => true])  !!}
            <?php $aid = $appointment->id;  ?>
            <input type = "submit" class = "btn btn-info"  name = "submitbtn" value = "Select appointment time">
            </input>
        </div>
    </div>
    <div class="form-group">
        <div class = "col-sm-10">
            {!! Form::submit($button, ['class' => 'btn btn-primary', 'value' => 'SubmitFinal', 'name' => 'submitbtn']) !!}
        </div>
    </div>

@stop
