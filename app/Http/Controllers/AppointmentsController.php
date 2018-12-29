<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Patient;
use App\Appointment;
use App\Dentist;
use App\Treatment;

class AppointmentsController extends Controller
{
    /**
     * Display the appointment scheduler
     */
    public function index($appointment_id = null, $patient_id = null, $type = null, $searchdate, $loadfrom1 = null)
    {
        if ($searchdate == '1')
        {
            $searchdate = date('d-m-Y'); //get current date
        }

        $dateusa = PagesController::convertDateToUSA($searchdate);

        // do not show the temp record id=1
        $appointments = Appointment::where('date_of_appointment', $dateusa)->where('id', '!=', '1')->orderBy('time_of_appointment', 'asc')->get();

        // get collection of patients that match the patient_ids in $appointments
        if (count($appointments) >= 1)
        {
            // create an empty collection to store the relevant patient records for the day selected in the scheduler
            $patients = new \Illuminate\Database\Eloquent\Collection;
            foreach($appointments as $appointment)
            {
                // if patient record has been hard deleted, then create a blank patient record
                if (Patient::find($appointment->patient_id) == null)
                {
                    $patient = Patient::find(1);
                    $patient->id = $appointment->patient_id;
                    $patient->name = 'Patient Record Deleted';
                }
                else
                {
                    $patient = Patient::find($appointment->patient_id);
                }

                // add each returned patient record to the $patients collection
                $patients->add($patient);
            }
        }
        else
        {
            // if no patient record selected eg just starting on appointments index, then just use default patient index 1
            $patients = Patient::find(1);
        }

        // {appointment_id} in the menu link if there is no id value supplied in the master layout route.
        if ($appointment_id == 'null')
        {
            $appointment_id = 1;
        }

        // all the timeslots for a day
        $timeslots = array(
            1 => '09:00:00',
            2 => '09:30:00',
            3 => '10:00:00',
            4 => '10:30:00',
            5 => '11:00:00',
            6 => '11:30:00',
            7 => '12:00:00',
            8 => '12:30:00',
            9 => '12:45:00', //lunch
            10 => '13:00:00',
            11 => '13:15:00', //lunch
            12 => '13:30:00',
            13 => '14:00:00',
            14 => '14:30:00',
            15 => '15:00:00',
            16 => '15:30:00',
            17 => '16:00:00',
            18 => '16:30:00'
        );

        // init the buttondata array
        $buttondata[] = array(
            "name" => '',
            "patient_id" => '',
            "appointment_id" => '',
            "time_of_appointment" => '',
            "checked_in" => ''
        );

        // only create appointment button if any appointments exit
        if (count($appointments >= '1'))
        {
            foreach($appointments as $appointment)
            {
                $patient = $patients->find($appointment->patient_id);
                $buttondata[] = array(
                    "name" => $patient->name . ' ' . $patient->surname,
                    "patient_id" => $patient->id,
                    "appointment_id" => $appointment->id,
                    "time_of_appointment" => $appointment->time_of_appointment,
                    "checked_in" => $appointment->checked_in
                );

                // delete all the times that have an apppintment booked already in $appointments
                foreach($appointments as $appointment)
                {
                    $theid = $appointment->time_of_appointment;
                    $key = array_search($theid, $timeslots);
                    unset($timeslots[$key]);
                }
            }
        }

        return view('appointments.index')->withAppointments($appointments)->withPatients($patients)->with('appointmentid', $appointment_id)->with('patientid', $patient_id)->with('type', $type)->with('searchdate', $searchdate)->with('timeslots', $timeslots)->with('buttondata', $buttondata)->with('loadfrom1', $loadfrom1);
    }



    /**
     * Show the form for creating a new resource
     */
    public function create($id = null, $newtime = null, $newdate = null)
    {
        // clear the temp appointment record
        $appointment = Appointment::find(1);
        $appointment->patient_id = '1';
        $appointment->treatment_type = '';
        $appointment->dentist = '';
        $appointment->invoice_amount = '';
        $appointment->payment_method = '';
        $appointment->payment_status = '';
        $appointment->date_of_appointment = '';
        $appointment->time_of_appointment = '';
        $appointment->checked_in = '';
        $appointment->save();

        // order treatments by name, in alphabetical order in the popover
        $treatments = Treatment::orderBy("treatment")->get();

        $patient = Patient::find($id);

        // get dentists & hygienists from db to display in select box
        $dentists = Dentist::where('role', 'Dentist')->get(['id', 'name', 'surname', 'role']);
        $hygienists = Dentist::where('role', 'Hygienist')->get(['id', 'name', 'surname', 'role']);

        return view('appointments.create', compact('dentists', $dentists) , compact('hygienists', $hygienists))->withPatient($patient)->with('newtime', $newtime)->with('newdate', $newdate)->withTreatments($treatments);
    }



    /**
     * Store a newly created appointment record in storage
     */
    public function store(Request $request)
    {
        // if selecting a different time, temp save the appointment record
        if ($_POST['submitbtn'] == "Select appointment time")
        {

            // create temporary appointment record = 1
            $appointment = Appointment::find(1);
            $ukdate = $request->input('date_of_appointment');
            if ($ukdate == '')
            {
                $ukdate = '1';
            }

            // convert input uk date to usa format for storage in db
            $dateusa = PagesController::convertDateToUSA($request->input('date_of_appointment'));
            $request->offsetSet('date_of_appointment', $dateusa);

            $input = $request->all();
            $appointment->fill($input)->save();
            $patientid = $appointment->patient_id;

            // creating an appointment. record is temp saved, now redirect to appointments index to pick a time. after selecting a time, will go to edit record #1
            $loadfrom1 = "1"; //this says there is an incomplete appointment record, so go next to edit appointment

            return redirect()->route('appointments', ['appointment_id' => '1', 'patient_id' => $patientid, 'type' => 'edit', 'searchdate' => $ukdate, 'loadfrom1' => $loadfrom1]);

        }
	// if selecting a different patient in create appointment form, store fields to appointment #1 and go to search for patient
        elseif ($_POST['submitbtn'] == "Select patient")
        {
            $appointment = Appointment::find(1);

            // convert input uk date to usa format for storage in db
            $dateusa = PagesController::convertDateToUSA($request->input('date_of_appointment'));
            $request->offsetSet('date_of_appointment', $dateusa);

            $input = $request->all();
            $appointment->fill($input)->save();

            // appointment fields in create appointment form are now stored in appointment #1.
            // pass the appointment_id=1. save the real appointment id to checked_in. if this field is empty then create new record.
            $appointmentid = $appointment->id;
            $type = 'edit'; //when return to the form, want to load from the db so go to edit appointment (but will switch name of title & buttons to create)

            return redirect()->route('patients.search', ['appointment_id' => $appointmentid, 'type' => $type]);

        }
	// create the appointment record for real - user clicked 'update record'
        else
        {
            // check the time selected does not have an appointment already booked on the date specified
            $date = PagesController::convertDateToUSA($request->input('date_of_appointment'));
            $time = $request->input('time_of_appointment');
            $appointmentchecktime = '';
            $appointmentcheck = Appointment::where('date_of_appointment', $date)->where('time_of_appointment', $time)->get();

            // save the time from the matching appointment record for use in the validation rule
            foreach($appointmentcheck as $appointmentc)
            {
                $appointmentchecktime = $appointmentc->time_of_appointment;
            }

            // make sure the required fields are filled in
            $this->validate($request, ['patient_id' => 'not_in:1', 'dentist' => 'required', 'date_of_appointment' => 'required|after:yesterday', 'time_of_appointment' => "required|different:$appointmentchecktime"]);
            $dateusa = PagesController::convertDateToUSA($request->input('date_of_appointment'));
            $request->offsetSet('date_of_appointment', $dateusa);

            // store the payment_status flag from appointment 1
            $appointment = Appointment::find('1');
            $paymentstatusflag = $appointment->payment_status;

            $input = $request->all();
            $appointment = Appointment::create($input); //create a new instance of appointment 1 in the database with the next available pk
            $id = $appointment->id; //store id of the new appointment, so can display info about it on the appointment created page

            // update it the payment_status flag
            $appointment->payment_status = $paymentstatusflag;
            $appointment->save();

            // convert appointment date to uk format for display on 'edited' page
            $originaldate = PagesController::convertDateToUK($appointment->date_of_appointment);
            $appointment->date_of_appointment = $originaldate;

            $patient = Patient::find($appointment->patient_id);

	    $redirectroute = route('appointments', ['appointment_id'=>'1', 'patient_id'=>'1', 'type'=>'create', 'searchdate'=>'1', 'loadfrom1'=>'0']);

            return view('appointments.created', ['redirectroute'=> $redirectroute])->withAppointment($appointment)->withPatient($patient);
        }
    }



    /**
     * Display all the appointment records for a patient
     */
    public function show($patient_id, $newtime = null, $appointmentid = null, $type = null)
    {
        // only get appointment records from db for the matching patient_id
        $appointments = Appointment::where('patient_id', $patient_id)->where('id', '!=', '1')->orderBy("date_of_appointment", "desc")->get();

        foreach($appointments as $appointment)
        {
            $appointment->date_of_appointment = PagesController::convertDateToUK($appointment->date_of_appointment);
        }

        $patient = Patient::find($patient_id);

        $count = count($appointments);
        if ($count <= 0)
        {
            // if patient has no appointments to show
            return view('appointments.shownone');
        }
        else
        {
            return view('appointments.show', ['appointment_id' => $appointmentid, 'type' => $type])->withAppointments($appointments)->withPatient($patient);
        }
    }



    /**
     * Show specified appointment record
     */
    public function showrecord($id, $appointmentid = null, $type = null)
    {
        $appointment = Appointment::find($id);

        // get the dentist name, using the dentist id stored in the appointment record
        $dentist_id = $appointment->dentist;

        // for old format records that do not have a dentist currently selected, need to make sure the dentist id exists for the selected appointment
        if ($dentist_id != '0')
        {
            $dentist = Dentist::find($dentist_id);
            $dentistname = $dentist->name . " " . $dentist->surname;
        }
        else
        {
            $dentist = '0';
            $dentistname = '';
        }

        $patient_id = $appointment->patient_id;
        $patient = Patient::find($patient_id);
        $appointment->date_of_appointment = PagesController::convertDateToUK($appointment->date_of_appointment);

        // if treatment_type field is blank, dont show a 0
        if ($appointment->treatment_type == 0)
        {
            $appointment->treatment_type = '';
        }

        if ($appointment->payment_method == 0)
        {
            $appointment->payment_method = '';
        }

        return view('appointments.showrecord', ['appointment_id' => $appointmentid, 'type' => $type])->withAppointment($appointment)->withPatient($patient)->withDentistname($dentistname);
    }



    /**
     * Show all appointment records
     */
    public function all()
    {
        // get all appointments, excluding appointment 1 'temp', and sort first by date_of_appointment, then secondly by time_of_appointment
        $appointments = Appointment::where('id', '!=', '1')->orderBy("date_of_appointment", "desc")->orderBy("time_of_appointment", "desc")->get();

        foreach($appointments as $appointment)
        {
            $appointment->date_of_appointment = PagesController::convertDateToUK($appointment->date_of_appointment);
        }

        $patients = Patient::all();

        return view('appointments.all')->withAppointments($appointments)->withPatients($patients);
    }



    /**
     * Show the form for editing the specified appointment record
     */
    public function edit($id = null, $newtime = null, $patient_id = null, $newdate = null)
    {
        $appointment = Appointment::find($id);

        // if patient id != 1, then change the patient id in $appointment
        if ($patient_id != '1')
        {
            $appointment->patient_id = $patient_id;
            $appointment->save();
        }

        $originaltime = $appointment->time_of_appointment;
        $originaldate = PagesController::convertDateToUK($appointment->date_of_appointment);

        // if selecting a new time, then create a new appointment record with this temp time stored in it
        if ($newtime != "00")
        {
            if ($id == "1")
            {
                $appointment = Appointment::find(1); //appointment record 1 is temp record
                $originaltime = $appointment->time_of_appointment;
                $originaldate = PagesController::convertDateToUK($appointment->date_of_appointment);

                if (is_numeric($appointment->checked_in)) //save the appointment date/time from the original record
                {
                    $appointmentoriginal = Appointment::find($appointment->checked_in);
                    $originaltime = $appointmentoriginal->time_of_appointment;
                    $originaldate = PagesController::convertDateToUK($appointmentoriginal->date_of_appointment);
                }

                $appointment->time_of_appointment = $newtime;
                $appointment->date_of_appointment = PagesController::convertDateToUSA($newdate);
            }
            else
            {
                // get the appointment record for the id and update the time of appointment
                $appointment = Appointment::find($id);
                $appointmentoriginal = Appointment::find($appointment->checked_in);
                $originaltime = $appointmentoriginal->time_of_appointment;
                $appointment->time_of_appointment = $newtime;
                $originaldate = PagesController::convertDateToUK($appointmentoriginal->date_of_appointment);
                $appointment->date_of_appointment = PagesController::convertDateToUSA($newdate);
            }
        }

        // get the date of appointment to display on the edit appointment page
        $appointment->date_of_appointment = PagesController::convertDateToUK($appointment->date_of_appointment);

        if (is_numeric($appointment->checked_in)) //save the appointment date/time from the original record
        {
            $originalappointmentid = $appointment->checked_in;
        }
        else
        {
            $originalappointmentid = $id;
        }

        $patient = Patient::find($appointment->patient_id);

        // get the title and create appointment button text
        if ((is_numeric($appointment->checked_in) && ($appointment->checked_in != '1')) || ($id != '1'))
        {
            $title = "Edit Appointment Record";
            $button = "Update Appointment";
        }
        else
        {
            $title = "Create New Patient Appointment";
            $button = "Create New Appointment";
        }

        // get the payment_status flag (from the appointment record that is != 1) and save it in appointment1
        $paymentstatusflag = $appointment->payment_status;
        $appointmenttemp = Appointment::find("1");
        $appointmenttemp->payment_status = $paymentstatusflag;
        $appointmenttemp->save();

        // get the url route for the getappointmentsfordate data request in edit view - not used currently
        $url = route('get.appointmentsfordate');
        $treatmentsjson = Treatment::orderBy("treatment")->get(['id', 'treatment']);

        // order treatments by name, so they appear in alphabetical order in the popover
        $treatments = Treatment::orderBy("treatment")->get();

        // get dentists & hygienists from db to display in select box
        $dentists = Dentist::where('role', 'Dentist')->get(['id', 'name', 'surname', 'role']);
        $hygienists = Dentist::where('role', 'Hygienist')->get(['id', 'name', 'surname', 'role']);

        return view('appointments.edit', compact('dentists', $dentists) , compact('hygienists', $hygienists))->withAppointment($appointment)->withPatient($patient)->withTitle($title)->withButton($button)->withPatient_id($patient_id)->withUrl($url)->withOriginaltime($originaltime)->withOriginaldate($originaldate)->withOriginalappointmentid($originalappointmentid)->withTreatments($treatments)->withTreatmentsjson($treatmentsjson);
    }



    /**
     * Update the specified appointment record in storage
     */
    public function update($id, Request $request)
    {
        $original_appointment_id = $id; //keep the original appointment id

        // if selecting the time_of_appointment button - temp save record to appointment #1
        if ($_POST['submitbtn'] == "Select appointment time")
        {
            // save the appointment record temporarily
            // if its id==1, create a new appointment. Store the original appointment id in the checked_in field which can only be set when the appointment is created

            $appointment = Appointment::find(1);
            $ukdate = $request->input('date_of_appointment');

            // convert input uk date to usa format for storage in db
            $dateusa = PagesController::convertDateToUSA($request->input('date_of_appointment'));
            $request->offsetSet('date_of_appointment', $dateusa);

            $input = $request->all();
            $appointment->fill($input);

            // store the original appointment_id in the checked_in field
            if ($original_appointment_id != '1')
            {
                $appointment->checked_in = $original_appointment_id;
            }

            $appointment->save();
            $patientid = $appointment->patient_id;

            // redirect to appointments index to pick a time. it will then after selecting a time direct to edit record #1
            return redirect()->route('appointments', ['appointment_id' => '1', 'patient_id' => $patientid, 'type' => 'edit', 'searchdate' => $ukdate, 'loadfrom1' => '1']);

        }
	// if selecting a different patient in edit appointment form, store fields to appointment #1 and go to search for patient
        elseif ($_POST['submitbtn'] == "Select patient")
        {
            $appointment = Appointment::find(1);

            // convert input uk date to usa format for storage in db
            $dateusa = PagesController::convertDateToUSA($request->input('date_of_appointment'));
            $request->offsetSet('date_of_appointment', $dateusa);

            $input = $request->all();
            $appointment->fill($input);

            // store the original appointment_id in the checked_in field

            if ($original_appointment_id != '1')
            {
                $appointment->checked_in = $original_appointment_id;
            }

            $appointment->save();

            // appointment fields in create appointment form are stored in appointment #1
            // save appointment id to checked_in and use appointment1 as temp record. when come back to edit, check checked_in var for an id value. if preset use it

            $appointmentid = $appointment->id;
            $type = 'edit'; //when return to the edit form, want to load from the db so go to edit appointment (but will switch name of title & buttons for create)

            return redirect()->route('patients.search', ['appointment_id' => $appointmentid, 'type' => $type]);

        }
        // regularly update the record
        else
        {
            $date = PagesController::convertDateToUSA($request->input('date_of_appointment'));
            $time = $request->input('time_of_appointment');
            $appointmentchecktime = '';
            $appointmentt = Appointment::find(1);
            $newpaymentstatus = $appointmentt->payment_status; //store the payment_status flag

            if (is_numeric($appointmentt->checked_in)) //if there is an existing appointment id stored
            {
                $id_checkedin = $appointmentt->checked_in; //get the appointment date/time from the existing record that is confirmed, not temp appointment #1
            }
            else
            {
                $id_checkedin = $id;
            }

            $appointmentcheck = Appointment::where('date_of_appointment', $date)->where('time_of_appointment', $time)->where('id', '!=', '1')->where('id', '!=', $id_checkedin)->get();

            // save the time from the matching appointment record to a variable for use in the validation rule
            foreach($appointmentcheck as $appointmentc)
            {
                $appointmentchecktime = $appointmentc->time_of_appointment;
            }

            // if dateselected is earlier than current date & dateselected is same as date in record, Allow to update the appointment record
            // if dateselected is earlier than current date & dateselected is different from date in record, Dont allow to change record as appointment has expired

            $dateinput = $request->input('date_of_appointment');
            $currentdate = date('d-m-Y');
            $datein = Appointment::where('id', $id_checkedin)->get();

            foreach($datein as $dateine)
            {
		//here if id=1, set date $dateofappointment so it fails the validation if() rule below
                if ($dateine->id != 1)
                { 
                    $dateofappointment = PagesController::convertDateToUK($dateine->date_of_appointment);
                }
                else
                {
                    $dateofappointment = '00:00:00';
                }
            }

            if ($dateinput <= $currentdate && $dateinput != $dateofappointment)
            {
                $this->validate($request, ['date_of_appointment' => 'after:yesterday']);
            }

            $type = '';

            // make sure the required fields are filled in
            $this->validate($request, ['patient_id' => 'not_in:1', 'dentist' => 'required', 'date_of_appointment' => 'required', 'time_of_appointment' => "required|different:$appointmentchecktime"]);

            $appointment = Appointment::find($id);
            $testforoldappintmentid = $appointment->checked_in; //get stored original appointment id

            // convert input uk date to usa format for storage in db
            $dateusa = PagesController::convertDateToUSA($request->input('date_of_appointment'));
            $request->offsetSet('date_of_appointment', $dateusa);

            $input = $request->all();
            $appointment->payment_status = $newpaymentstatus;
            $appointment->fill($input)->save();

            // clear payment_status flag when didnt use appointment #1 as the payment_status flag is stored there
            $appointment1 = Appointment::find(1);
            $appointment1->payment_status = '';
            $appointment1->save();

            // if creating the appointment using the temp record, create a new record in the database table at next available pk
            if ($id == '1')
            {
                $lastInsertedId = Appointment::orderBy('id', 'desc')->first()->id;
                $lastInsertedId = $lastInsertedId + 1;

                // if old appointment id is saved, then use it or else create a new record with a new pk
                if ((is_numeric($testforoldappintmentid)) && ($testforoldappintmentid != '1'))
                {
                    $appointment = Appointment::find($testforoldappintmentid);

                    // convert input uk date to usa format for storage in db
                    $dateusa = PagesController::convertDateToUSA($request->input('date_of_appointment'));
                    $request->offsetSet('date_of_appointment', $dateusa);
                    $input = $request->all();
                    $appointment->payment_status = $newpaymentstatus;
                    $appointment->fill($input)->save();
                    $type = 'edit';
                }
                else
                {
                    // use this if there is no existing appointment id
                    $appointment = Appointment::find(1);
                    $appointment->payment_status = $newpaymentstatus;
                    $newAppointment = $appointment->replicate();
                    $newAppointment->id = $lastInsertedId; //change the appointment id for this duplicate record to the original id
                    $newAppointment->save();
                    $type = 'create';
                    $appointment = Appointment::find($lastInsertedId);
                }

                // clear appointment 1
                $appointment1 = Appointment::find(1);
                $appointment1->patient_id = '1';
                $appointment1->treatment_type = '';
                $appointment1->dentist = '';
                $appointment1->invoice_amount = '';
                $appointment1->payment_method = '';
                $appointment1->payment_status = '';
                $appointment1->date_of_appointment = '';
                $appointment1->time_of_appointment = '';
                $appointment1->checked_in = '';
                $appointment1->save();
            }

            // convert appointment date to uk format for display on 'edited' page
            $originaldate = PagesController::convertDateToUK($appointment->date_of_appointment);
            $appointment->date_of_appointment = $originaldate;

            $patient = Patient::find($appointment->patient_id);

            $redirectroute = route('appointments', ['appointment_id'=>'1', 'patient_id'=>'1', 'type'=>'create', 'searchdate'=>'1', 'loadfrom1'=>'0']);

            return view('appointments.edited', ['redirectroute'=> $redirectroute])->withAppointment($appointment)->withPatient($patient)->withType($type);
        }
    }



    /**
     * Remove the specified appointment record from storage
     */
    public function destroy($id)
    {
        $appointmentcopy = Appointment::find($id); //duplicate the appointment record

        // convert appointment date to uk format for display on edited page
        $originaldate = PagesController::convertDateToUK($appointmentcopy->date_of_appointment);
        $appointmentcopy->date_of_appointment = $originaldate;

        $patient = Patient::find($appointmentcopy->patient_id);

        $appointment = Appointment::find($id);
        $appointment->delete();

        $redirectroute = route('appointments', ['appointment_id'=>'1', 'patient_id'=>'1', 'type'=>'create', 'searchdate'=>'1', 'loadfrom1'=>'0']);

        return view('appointments.destroy', ['redirectroute'=> $redirectroute])->withAppointmentcopy($appointmentcopy)->withPatient($patient);
    }
	   


    /**
     * Check in the patient for their appointment
     */
    public function checkin($id, Request $request, $appointmentid = null, $type = null)
    {
        $appointment = Appointment::find($id);
        if ($appointment->checked_in == '')
        {
            $appointment->checked_in = 'Yes'; //Yes is checked in
            $appointment->save();
        }
        else
        {
            $appointment->checked_in = ''; //'' is Not checked in
            $appointment->save();
        }

        $patient = Patient::find($appointment->patient_id);
        $appointment->date_of_appointment = PagesController::convertDateToUK($appointment->date_of_appointment);
        if ($type == null)
        {
            $type = 'edit';
        }

        // get the dentist name, using the dentist id stored in the appointment record
        $dentist_id = $appointment->dentist;
        if ($dentist_id != '0')
        {
            $dentist = Dentist::find($dentist_id);
            $dentistname = $dentist->name . " " . $dentist->surname;
        }
        else
        {
            $dentist = '';
            $dentistname = '';
        }

        // if treatment_type is blank, dont show a 0
        if ($appointment->treatment_type == 0)
        {
            $appointment->treatment_type = '';
        }

        // if payment_method is blank, dont show a 0
        if ($appointment->payment_method == 0)
        {
            $appointment->payment_method = '';
        }

        return view('appointments.showrecord', ['appointment_id' => $appointmentid, 'type' => $type])->withAppointment($appointment)->withPatient($patient)->withDentistname($dentistname);
    }



    /**
     * Update apointment payment status
     * When user clicks mark as paid, then set the payment_status flag
     */
    public function payment_status_flag($appointment_id)
    {
        $appointment = Appointment::find($appointment_id);
        if ($appointment->payment_status != '1')
        {
            // 1 is paid
            $appointment->payment_status = "1";
            $appointment->save();
            print "1"; //return the payment status
        }
        else
        {
            // empty is not paid
            $appointment->payment_status = "";
            $appointment->save();
            print "";
        }
    }



    /**
     * Get the treatment price, used by angular
     * Used on treatments pages to show the treatments and prices
     */
    public function getTreatmentPrice($treatment_type)
    {
        $treatment = Treatment::where('treatment', $treatment_type)->get();
        foreach($treatment as $data)
        {
            print $data->price;
        }
    }


}
