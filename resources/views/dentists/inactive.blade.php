@extends('layouts.master')
@section('content')

<div class = "col-sm-6">
    <h3>
        Inactive Staff Records
    </h3>
    <button class="btn btn-info margin-top-15" onclick="window.location='{!! route('dentists.index'); !!}'"><span class="glyphicon glyphicon-chevron-left"></span> Go Back</button> 
    <br><br>
    <div class="alert alert-info">
        <div class="panel-group" id="accordion">
            <?php $counter = '1'; ?>
            <center>
                <h4>Inactive Staff</h4>
            </center>
            <hr>
            @foreach($dentists as $dentist)
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $counter; ?>">
			    <div class="flex-container">
  				<div><b>{{ $dentist->name }} {{ $dentist->surname }}</b></div>
  				<div style="flex-basis: 150%">{{ $dentist->address}}</div>
  				<div style="flex-basis: 80%"><i>{{ $dentist->role}}</i></div>  
			    </div>
                    </h4>
                </div>
                <div id="collapse<?php echo $counter; ?>" class="panel-collapse collapse">
                <div class="panel-body">
                    <a href=""></a><!--need this otherwise the text below acts as a collapsable link-->
                    <b>Address:</b> {{ $dentist->city }}, {{ $dentist->county }}, {{ $dentist->country }}, {{ $dentist->postcode }}<br>
                    <b>Mobile Number: </b>{{ $dentist->mobilenumber }}<br>
                    <b>Email: </b>{{ $dentist->email }}<br>
                    <b>Date of Birth: </b>{{ $dentist->dateofbirth }}<br>
                    <b>Joined the Practice: </b>{{ $dentist->whenjoinedpractice }}<br>
                    <b>Role: </b>{{ $dentist->role }}<br>
                    <div class="center-buttons">
                        <a href="{{ route('dentists.edit', $dentist->id) }}" class="btn btn-primary">Edit Staff Record</a>
                    </div>
                </div>
                </div>
                <?php $counter = $counter+1; ?>
                @endforeach
            </div>
        </div>
    </div>
</div>

@stop