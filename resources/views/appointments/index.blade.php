@extends('layouts.master')
@section('content')

<div class = col-sm-3>
<div class="alert alert-info">
<h4>
    Appointment Calendar for <input id="date-fill" class="form-control appointment-scheduler-date-alignment"></input>
</h4>
<script>
    var date_of = <?php echo json_encode($searchdate, JSON_PRETTY_PRINT) ?>;
    <!-- display selected date in the input form -->
    document.getElementById("date-fill").value = date_of;
</script>
<div id="datepicker"></div>
<p id="output"></p>
<?php
    if (count($appointments) == '16'){
    	echo "<b>All Appointments booked today!</b>";
    }else{
    	echo "<b>Appointments Booked: ". count($appointments)."</b><br>";
    
    	//remove the 2 timeslots for lunch buttons
    	$totalnumofappointments = count($timeslots) - 2;
    	echo "Appointments Available: ".$totalnumofappointments;
    }
    echo "<br></div></div>";
    ?>
<div class = col-sm-4>
    <div class="alert alert-info">
        <b>
	    09.00 <p id="m09-00-00"></p><br>
	    09.30 <p id="m09-30-00"></p><br>
	    10.00 <p id="m10-00-00"></p><br>
	    10.30 <p id="m10-30-00"></p><br>
	    11.00 <p id="m11-00-00"></p><br>
	    11.30 <p id="m11-30-00"></p><br>
	    12.00 <p id="m12-00-00"></p><br>
	    12.30 <p id="m12-30-00"></p><br>
	    13.00 <p id="m12-45-00"></p><p id="m13-00-00"></p><br>
	    13.30 <p id="m13-15-00"></p><p id="m13-30-00"></p><br>
	    14.00 <p id="m14-00-00"></p><br>
	    14.30 <p id="m14-30-00"></p><br>
	    15.00 <p id="m15-00-00"></p><br>
	    15.30 <p id="m15-30-00"></p><br>
	    16.00 <p id="m16-00-00"></p><br>
	    16.30 <p id="m16-30-00"></p>
	</b>
    </div>
</div>

@include('appointments.index_javascript')

<script>
    if (type == false)
    {
    	type = 'edit';
    }
</script>

@parent
@stop
