<script>
    //Make buttons for the booked appointments
    var buttons = <?php echo json_encode($buttondata, JSON_PRETTY_PRINT) ?>;
    
    //when have loaded a patient for a new appointment, go to edit appointment and load from record 1
    var loadfrom1 = <?php echo json_encode($loadfrom1, JSON_PRETTY_PRINT) ?>;
    
    var combined;
    var i;
    var time;
    var patient_id;
    var checked_in;
    
    for (i = 0; i < buttons.length; i++) {
        combined = buttons[i].name;					//buttons[i].time_of_appointment + " " + buttons[i].name;
        time = buttons[i].time_of_appointment;
        patient_id = buttons[i].patient_id;
        checked_in = buttons[i].checked_in;
        appointment_id = buttons[i].appointment_id;
    
    	//get time and convert to m09-00-00 as jquery ids cannot start with number or contain :
    	var timeconvert = time.replace(/:/g , '-');			// /g is global, meaning replace all instances of :
    
    	timeconvertm = "m"+timeconvert;
    
    	$( "#"+timeconvertm ).button();					//create button
    	$("span", "#"+timeconvertm).text(combined);			//set button name
    	$( "#"+timeconvertm ).attr('id', timeconvertm);			//set button id eg 'm09-00-00'
    
    
    	if (checked_in == 'Yes'){
    		$("#"+timeconvertm ).css("background", "blue");		//blue button for checked in
    		$("#"+timeconvertm ).css("color", "white");
    	}else{
    		$("#"+timeconvertm ).css("background", "Gainsboro");	//light-grey button for not checked in
    		$("#"+timeconvertm ).css("color", "black");
    	}
    
    	//create ul ids for each menu and links
        var x = document.createElement("UL");
        x.setAttribute('id', 'menu'+timeconvert);
        document.body.appendChild(x);
    
    	if (checked_in == 'Yes'){
        	var checked = "Un Check Patient";
    	}else{
    		var checked = "Check in Patient";
   	 }
    
    	$('<a />', {
    	    text: checked,
    	    href: "../appointments/checkin"+appointment_id+",1,create",
   	 }).wrap('<li />').parent().appendTo('#menu'+timeconvert);
    
  	$('<a />', {
  	    text: "Patient Record",
            href: "../patients/showrecord"+patient_id+",1,create",
        }).wrap('<li />').parent().appendTo('#menu'+timeconvert);
    
    	$('<a />', {
            text: "Appointment Record",
            href: "../appointments/showrecord"+appointment_id+",1,create",
    	}).wrap('<li />').parent().appendTo('#menu'+timeconvert);
    
        //hide the <ul> menus just created
        $(document).ready(function () { $('ul').hide(); });
    }
    
    
    //Make buttons for where there are no appointments booked
    var freeappointments = <?php echo json_encode($timeslots, JSON_PRETTY_PRINT) ?>;
    var appointment_id = <?php echo json_encode($appointmentid, JSON_PRETTY_PRINT) ?>;
    var patient_id = <?php echo json_encode($patientid, JSON_PRETTY_PRINT) ?>;
    var type = <?php echo json_encode($type, JSON_PRETTY_PRINT) ?>;
    
    
    var size = Object.keys(freeappointments).length;		//get size of object
    combined = "BOOK THIS APPOINTMENT";
    
    
    for (var key in freeappointments) {
    time = freeappointments[key];
    timeconvert = time.replace(/:/g , '-');			// /g means replace all instances
    timeconvertm = "m"+timeconvert;				//prefix the time with m
    
    <!--on index page if create appointment clicking on button, then type has no definintion -->
    if ((timeconvertm == 'm12-45-00')  || (timeconvertm == 'm13-15-00'))
    {
    	type = '';		//lunch buttons
    }else
    {
    	type = 'create';	//free appointment buttons
    }
    
    
    $( "#"+timeconvertm ).button();				//create button
    
    //Lunch button
    if ((timeconvertm != 'm12-45-00')  && (timeconvertm != 'm13-15-00'))
    {
    	$("span", "#"+timeconvertm).text(combined);		//set button name
    }
    else {
    	$("span", "#"+timeconvertm).text("LUNCH");		//set button name
    	type = '';
    }
    
    
    $( "#"+timeconvertm ).attr('id', timeconvertm);		//set button id eg 'm09-00-00'
    
    //only allow clicking on the buttons for free appointments, not on the Lunch Buttons
    if ((timeconvertm != 'm12-45-00') && (timeconvertm != 'm13-15-00')){
    
    	//get the appointment time and pass it to the create appointment page
    	$( "#"+timeconvertm ).click (function(){
    
    		//get the time of appointment. go to create page. pass it to there in the time_of_appointment field
    		var mtime = this.id
    		var ntime = mtime.substring(1, mtime.length);
    		var time = ntime.replace(/-/g , ':');			// /g means replace all instances
    
    
    		if(type == "create"){
     			if (loadfrom1 == '1')
     			{
    				window.location.href="../appointments/edit1,"+time+",1,"+date_of;
     			}
     			else
     			{
    				window.location.href="../appointments/create1,"+time+","+date_of;
     			}
    		}
    		if (type == "edit"){
    			window.location.href="../appointments/edit"+appointment_id+","+time+",1,"+date_of;
    		}
    
    	});
    
    }
    
    
    
    	
    //style for timeslot buttons
    if ((timeconvertm != 'm12-45-00')  && (timeconvertm != 'm13-15-00'))
    {
    	$("#"+timeconvertm ).css("background", "orange");	//button colour for free appointment
    	$("#"+timeconvertm ).css("color", "black");		//black text for the orange button
    }else
    {
    	$("#"+timeconvertm ).css("background", "blue");		//button colour for lunch button
    	$("#"+timeconvertm ).css("color", "black");		//black text for the lunch button
    }
    

    }
</script>






<script>
    //on mouseover appointment button, change its color to red for highlight. on mouseout, change back to the original color
    var color = '';
    $(document).ready(function(){
        $("p").mouseenter(function(){
            color = $(this).css("background-color");  
            $(this).effect("highlight", { color: "#ff0000" }, 500);
        });
       
        $("p").mouseout(function(){
     	    $(this).css("background-color", color);
        });
    });

    $("p").click(function(){
        $(this).css("background-color", "red");
    });
</script>






<script>
    //jquery ui menu
    $(function() {
    	$( "p" ).click(function() {
    	    var theid = this.id;	//get button id
    	    //remove the m off the start of id and then add the=at number after the static '#menu'+theidwithoutm
    	    var strid = theid.substring(1, theid.length);
    
    	    var menul = $( "#menu"+strid ).menu();
            menul.show().position({
                my: "left top", at: "left bottom", of: this });
            menul.effect("slide", {color:"grey"}, 100);
            //Register a click outside the menu to close it
            $( document ).on( "click", function() {
                menul.hide();
            });

     	    return false;
    	});
    })
</script>