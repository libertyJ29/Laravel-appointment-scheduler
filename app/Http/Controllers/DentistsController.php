<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Dentist;
use App\Appointment;
use App\Patient;
use App\Treatment;

class DentistsController extends Controller
{
    /**
     * Display all dentist records
     */
    public function index()
    {
        $dentists = Dentist::where('currentlyactive', '=', '1')->orderBy("surname")->get();
        foreach($dentists as $dentist)
        {
            $dentist->dateofbirth = PagesController::convertDateToUK($dentist->dateofbirth);
        }

        return view('dentists.index')->withDentists($dentists);
    }



    /**
     * Display all inactive dentist records
     */
    public function inactive()
    {
        $dentists = Dentist::where('currentlyactive', '=', '0')->orderBy("surname")->get();
        foreach($dentists as $dentist)
        {
            $dentist->dateofbirth = PagesController::convertDateToUK($dentist->dateofbirth);
        }

        return view('dentists.inactive')->withDentists($dentists);
    }



    /**
     * Get all the treatments
     * Used on treatments pages to show the treatments and prices
     */
    public function getAllTreatments()
    {
        $treatments = Treatment::all();
        $data = array();
        foreach($treatments as $row)
        {
            $data[] = $row;
        }

        print json_encode($data);
    }



    /**
     * Save newly entered treatment to Database.
     * Return the new treatment in the data array
     */
    public function addnewtreatment($treatment_name = null)
    {
        $treatment = new treatment;
        $treatment->treatment = $treatment_name;
        $treatment->save();

        // get all the treatments from db and return them to angular
        $treatments = Treatment::all();
        $data = array();
        foreach($treatments as $row)
        {
            $data[] = $row;
        }

        print json_encode($data);
    }



    /**
     * Show the form for creating a new dentist record
     */
    public function create()
    {
        $treatments = Treatment::all();
        return view('dentists.create')->withTreatments($treatments);
    }



    /**
     * Store a newly created dentist record to db
     */
    public function store(Request $request)
    {
        $this->validate($request, ['name' => 'required', 'surname' => 'required', 'address' => 'required', 'mobilenumber' => 'required', 'role' => 'required']);
        $input = $request->all();

        // store the id and created record, for use next to retrieve the record and pass to the view
        $id = Dentist::create($input)->id;
        $dentist = Dentist::find($id);

        $redirectroute = route('dentists.index');

        return view('dentists.created', ['redirectroute'=> $redirectroute])->withDentist($dentist);
    }
 


    /**
     * Display all treatments
     */
    public function treatments()
    {
        $treatments = Treatment::all();
        return view('dentists.treatments');
    }



    /**
     * Update a treatment price in db using angular
     */
    public function updatetreatments($treatment_id, $newprice)
    {

        // save the new price in the database for the treatment
        $treatment = Treatment::find($treatment_id);
        $treatment->price = $newprice;
        $treatment->save();

        // preserve the treatment for later
        $atreatmentname = $treatment->treatment;

        // get all the treatments from db and return them to angular
        $treatments = Treatment::all();
        $data = array();
        foreach($treatments as $row)
        {
            $data[] = $row;
        }

        print json_encode($data);
        print json_encode($atreatmentname);
    }



    /**
     * Delete a treatment, from the treatments page
     */
    public function deletethistreatment($treatment_id)
    {
        // find the treatment record to delete
        $treatmenta = Treatment::find($treatment_id);
        $treatmenta->delete();

        // get all the treatments from db and return them to angular
        $treatments = Treatment::all();
        $data = array();
        foreach($treatments as $row)
        {
            $data[] = $row;
        }

        print json_encode($data);
    }



    /**
     * Show the form for editing a dentist record
     */
    public function edit($id = null)
    {
        $dentist = Dentist::find($id);
        $treatments = Treatment::all();

        // Collection of the roles for employees of the practice
        $dentistroles = Dentist::distinct()->get(['role']);

        // convert USA format db date to UK format for display
        $dentist->dateofbirth = PagesController::convertDateToUK($dentist->dateofbirth);
        $selectedt = $dentist->treatments;

        return view('dentists.edit')->withDentist($dentist)->withTreatments($treatments)->withSelectedt($selectedt)->withDentistroles($dentistroles);
    }



    /**
     * Update the specified dentist record in db
     */
    public function update($id, Request $request)
    {
        $dentist = Dentist::find($id);
        $this->validate($request, ['name' => 'required', 'surname' => 'required', 'address' => 'required', 'mobilenumber' => 'required', 'role' => 'required']);

        // convert input UK date to USA format for storage in db
        $dateusa = PagesController::convertDateToUSA($request->input('dateofbirth'));
        $request->offsetSet('dateofbirth', $dateusa);
        $input = $request->all();
        $dentist->fill($input);
        if ($request->selectedtreatments != '')
        {
            // take the selected values from the checkboxes and convert them to a string using CSV
            $theselectedstreatments = implode("','", $request->selectedtreatments);

            // format data with [' at start and '] at end, so it can be read by angular
            $dentist->treatments = "['" . $theselectedstreatments . "']";
        }
        else
        {
            $dentist->treatments = "[]";
        }

        $dentist->save();

        $redirectroute = route('dentists.index');

        return view('dentists.edited', ['redirectroute'=> $redirectroute])->withDentist($dentist);
    }



    /**
     * Remove the specified dentist record from db
     */
    public function destroy($id = null)
    {
        $dentist = Dentist::find($id);

        // $dentist1 = Dentist::find($id);
        // $dentist1->delete();

        $dentist->currentlyactive = 0; //soft delete the dentist, to avoid crashes for records linked to this dentist
        $dentist->save();

        $redirectroute = route('dentists.index');

        return view('dentists.destroy', ['redirectroute'=> $redirectroute])->withDentist($dentist);
    }



}
