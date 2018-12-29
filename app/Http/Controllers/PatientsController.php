<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Patient;

class PatientsController extends Controller
{
    /**
     * Display the patient records home page
     */
    public function index()
    {
        return view('patients.index');
    }



    /**
     * Show the form for creating a new patient record
     */
    public function create()
    {
        return view('patients.create');
    }



    /**
     * Store a newly created patient record in db
     */
    public function store(Request $request)
    {
        $this->validate($request, ['name' => 'required', 'surname' => 'required', 'address' => 'required', 'mobilenumber' => 'required']);
        $input = $request->all();

        // store the id and created record, for use next to retrieve the record and pass to the view
        $id = Patient::create($input)->id;
        $patient = Patient::find($id);

	//set as a current patient
	$patient->currentpatient = 1;
	$patient->save();

        $redirectroute = route('patients');

        return view('patients.created', ['redirectroute'=> $redirectroute])->withPatient($patient);
    }



    /**
     * Show all patient records
     */
    public function all()
    {

        // patient records in asc order
        $patients = Patient::where('id', '!=', '1')->where('currentpatient', '!=', '0')->orderBy("surname")->get();

        return view('patients.all')->withPatients($patients);
    }



    /**
     * Display the specified patient record, using the supplied surname
     */
    public function show(Request $request, $appointmentid = null, $type = null)
    {
        $surname = $request->surname;

        // get records from db with the matching surname. Dont show patient #1 record
        $patients = Patient::where('surname', $surname)->where('id', '!=', '1')->get();

        return view('patients.show', ['appointment_id' => $appointmentid, 'type' => $type])->withPatients($patients);
    }



    /**
     * Display the specified patient record, using the supplied patient id
     */
    public function showrecord($id = null, $appointmentid = null, $type = null)
    {
        $patient = Patient::find($id);

        // convert USA format db date to UK format for display
        $patient->dateofbirth = PagesController::convertDateToUK($patient->dateofbirth);

        return view('patients.showrecord', ['appointment_id' => $appointmentid, 'type' => $type])->withPatient($patient);
    }



    /**
     * Form to enter a patient name to search for in db
     */
    public function search($appointmentid = null, $type = null)
    {
        return view('patients.search', ['appointment_id' => $appointmentid, 'type' => $type]);
    }



    /**
     * Show the form for editing the specified patient record
     */
    public function edit($id = null)
    {
        $patient = Patient::find($id);

        // convert USA format db date to UK format for display
        $patient->dateofbirth = PagesController::convertDateToUK($patient->dateofbirth);

        return view('patients.edit')->withPatient($patient);
    }



    /**
     * Update the specified patient record in db
     */
    public function update($id, Request $request)
    {
        $action = $request['action'];

        if ($action == "Update Patient Record")
        {
            $patient = Patient::find($id);

            $this->validate($request, ['name' => 'required', 'surname' => 'required', 'address' => 'required', 'mobilenumber' => 'required']);

            // convert input UK date to USA format for storage in db
            $dateusa = PagesController::convertDateToUSA($request->input('dateofbirth'));
            $request->offsetSet('dateofbirth', $dateusa);
            $input = $request->all();
            $patient->fill($input)->save();

            $patient = Patient::find($id);

            $redirectroute = route('patients');

            return view('patients.edited', ['redirectroute'=> $redirectroute])->withPatient($patient);
        }
        else
        {
            return view('patients.index');
        }
    }



    /**
     * Remove the specified patient record from db
     */
    public function destroy($id = null)
    {
        $patient = Patient::find($id);

        // preserve a copy of the patient record, to display the deleted patient name in the following view
        $patientd = Patient::find($id);

        // $patient->delete();

        $patient->currentpatient = 0;
        $patient->save();

        $redirectroute = route('patients');

        return view('patients.destroy', ['redirectroute'=> $redirectroute])->withPatientd($patientd);
    }



}
