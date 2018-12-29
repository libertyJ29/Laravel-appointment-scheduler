@extends('layouts.master')
@section('content')

<html ng-app="appointmentsApp">
    <h3>
        Show Appointment Record
    </h3>
    <button class="btn btn-info margin-bottom-15" onclick="history.go(-1);"><span class="glyphicon glyphicon-chevron-left"></span> Go Back</button>
    <?php $fullname = $patient->name . ' ' . $patient->surname . ' - ' . $patient->address . ', ' .  $patient->postcode ?>
    <div class="form-group">
        {!! Form::label('name', 'Patient:', ['class' => 'control-label col-sm-2']) !!}
        <div class = col-sm-10>
            {!! Form::text('name', $fullname, ['class' => 'form-control', 'readonly' => true]) !!}
        </div>
    </div>
    {!! Form::model($appointment, [
    'method' => 'GET',
    'route' => ['appointments.showrecord', $appointment->id]
    ]) !!}
    <div class="form-group">
        {!! Form::label('treatment_type', 'Treatment Type:', ['class' => 'control-label col-sm-2']) !!}
        <div class = "col-sm-10">
            {!! Form::text('treatment_type', null, ['class' => 'form-control', 'readonly' => true]) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('dentist', 'Dentist:', ['class' => 'control-label col-sm-2']) !!}
        <div class = "col-sm-10">
            {!! Form::text('dentistname', $dentistname, ['class' => 'form-control', 'readonly' => true]) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('payment_method', 'Payment Method:', ['class' => 'control-label col-sm-2']) !!}
        <div class = "col-sm-10">
            {!! Form::text('payment_method', null, ['class' => 'form-control', 'readonly' => true]) !!}
        </div>
    </div>
    <?php
        //add £ symbol before price
        if($appointment->invoice_amount != ''){
        	$invoice_amount_pound = '&pound;'.$appointment->invoice_amount;
        }else{
        	$invoice_amount_pound = $appointment->invoice_amount;
        }
        ?>
    <div class="form-group">
        {!! Form::label('invoice_amount', 'Invoice Amount:', ['class' => 'control-label col-sm-2']) !!}
        <div class = "col-sm-10">
            {!! Form::text('invoice_amount', $invoice_amount_pound, ['class' => 'form-control', 'readonly' => true]) !!}
        </div>
    </div>
    <i id="appointmentsCtrl1" ng-controller="appointmentsCtrl" ng-init="$scope.checker = 'false' ;  paymentstatusflag = '<?php echo $appointment->payment_status?>';">
        <input ng-model="$scope.appointmentid" class="hidden" ng-init="$scope.appointmentid = <?php echo $appointment->id; ?>"></input>
        <div class="form-group">
            {!! Form::label('payment_status', 'Payment Status:', ['class' => 'control-label col-sm-2']) !!}
            <div class = "col-sm-4">
                <input type="text" class="form-control" readonly="1" ng-model="$scope.paymentstatustext"></input>
            </div>
        </div>
        <div ng-if="paymentstatusflag != '1'">
            <input class = 'btn btn-warning' ng-click="paidbutton($scope.appointmentid)"  ng-model="paidbuttontext" ng-init="paidbuttontext= 'Invoice Not Paid Yet!'; $scope.paymentstatustext = 'Invoice Not Paid Yet!';">
            </input>
        </div>
        <div ng-if="paymentstatusflag == '1'">
            <input class = 'btn btn-info' ng-click="paidbutton($scope.appointmentid)" ng-model="paidbuttontext" ng-init="paidbuttontext= 'Paid in Full'; $scope.paymentstatustext = 'Paid in Full';">
            </input>
        </div>
    </i>
    <div class="form-group">
        {!! Form::label('date_of_appointment', 'Date of Appointment:', ['class' => 'control-label col-sm-2']) !!}
        <div class = "col-sm-10">
            {!! Form::text('date_of_appointment', null, ['class' => 'form-control', 'readonly' => true]) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('time_of_appointment', 'Time of Appointment:', ['class' => 'control-label col-sm-2']) !!}
        <div class = "col-sm-10">
            {!! Form::text('time_of_appointment', null, ['class' => 'form-control', 'readonly' => true]) !!}
        </div>
    </div>
    <?php
        if($appointment->checked_in == '')
        {
        	$check = "Not checked in yet";
        }else{
        	$check = "Patient is checked in";
        }?>
    <div class="form-group">
        {!! Form::label('checked_in', 'Checked In:', ['class' => 'control-label col-sm-2']) !!}
        <div class = "col-sm-10">
            {!! Form::text('checked_in', $check, ['class' => 'form-control', 'readonly' => true]) !!}
        </div>
    </div>
    <!--buttons at the bottom-->
    <div class="form-group">
        <div class = "col-sm-2"></div>
        <div class = "col-sm-10">
            <br>
            {!! Form::close() !!}
            {!! Form::open([
            'method' => 'DELETE',
            'route' => ['appointments.destroy', $appointment->id]
            ]) !!}
            <a href="{{ route('appointments.edit', [$appointment->id, '00', '1', '1']) }}" class="btn btn-primary">Edit This Appointment</a>
            <?php 
                if($type == 'edit'){
                	//echo "<B>edit appointment</b>";
                	?>
            <a href="{{ route('appointments.edit', [$appointment_id, '00', $patient->id, '1']) }}" class="btn btn-warning">Create New Appointment</a>
            <?php
                }else{
                	?>
            <a href="{{ route('appointments.create', [$patient->id, '00', '1']) }}" class="btn btn-warning">Create New Appointment</a>
            <?php 
                } ?>
            <a href="{{ route('appointments.show', [$patient->id, '1', $appointment_id, $type]) }}" class="btn btn-primary">Show Appointments List</a>
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
            <a href="{{ route('patients.showrecord', [$appointment->patient_id, '1', 'create']) }}" class="btn btn-info">Show Patient Record</a>
            <br>
        </div>
    </div>
    {!! Form::close() !!}

@stop