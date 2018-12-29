@extends('layouts.master')
@section('content')

<div class = "col-sm-6">
    <h3>
        Staff/Treatment Records
    </h3>
    <a href="{{ route('dentists.create') }}" class="btn btn-info">Add New Staff</a>
    <a href="{{ route('dentists.treatments') }}" class="btn btn-warning">Treatments Available</a>
    <a href="{{ route('dentists.inactive') }}" class="btn btn-primary">Show Inactive Staff</a>
    <br><br>
    <div class="alert alert-info">
        <div class="panel-group" id="accordion">
            <?php $counter = '1'; ?>
            <center>
                <h4>Staff currently at our practice</h4>
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
                	    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#myModal<?php echo $counter;?>">DELETE Staff member</button>
			</div>
                	{!! Form::open([
                	'method' => 'DELETE',
                	'route' => ['dentists.destroy', $dentist->id]
                	]) !!}
                	<!-- Modal -->
                	<div class="modal fade" id="myModal<?php echo $counter;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel<?php echo $counter;?>">
                	    <div class="modal-dialog" role="document">
                	    <div class="modal-content">
                	        <div class="modal-header">
                		    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                		    <h4 class="modal-title" id="myModalLabel<?php echo $counter;?>">Are you sure you want to delete this record for Staff <b><?php echo $dentist->name." ".$dentist->surname;?></b>?</h4>
                		</div>
                	        <div class="modal-footer">
                		    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                		    {!! Form::submit('YES, DELETE Staff', ['class' => 'btn btn-danger']) !!}<br>
                		    {!! Form::close() !!}
                		</div>
                	    </div>
                	    </div>
                	</div>
                    </div>
                </div>
                <?php $counter = $counter+1; ?>
                {!! Form::close() !!}
                @endforeach
            </div>
        </div>
    </div>
</div>

@stop