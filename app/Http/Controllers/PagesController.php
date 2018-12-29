<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Patient;
use App\Appointment;

class PagesController extends Controller
{

    /**
     * Display the scheduler home page
     */
    public function home()
    {
    	return view('home.show');
	
    }



    /**
     * Display view after successful login
     */
    public function loginsuccess()
    {
        return view('home.loginsuccess');
    }



    /**
     * Get the appointment data for the supplied date. 
     * Used on create/edit appointment pages to show the number of 
     * appointments available for the supplied datepicker date
     */
    public function getAppointmentsForDate($date=null)
    {
    	// pass in UK format $date from the angular http.get request
	$usadate = PagesController::convertDateToUSA($date);

    	$appointments = Appointment::where('date_of_appointment', $usadate)->where('id', '!=', '1')->get();

    	$data = array();
    	foreach ($appointments as $row) {
    		$data[] = $row;
     	}
    	print json_encode($data);
    }



    /**
     * Convert form input UK date to MYSQL US Date Format
     */
    public static function convertDateToUSA($date)
    {
    	$timestamp = strtotime(str_replace('/', '.', $date));
    	$mysql_date = date('Y-m-d', $timestamp); 
    	return $mysql_date;
    }



    /**
     * Convert MYSQL US Date Format to UK date
     */
    public static function convertDateToUK($date)
    {
    	$timestamp = strtotime(str_replace('/', '.', $date));
    	$uk_date = date('d-m-Y', $timestamp); 
    	return $uk_date;
    }



}
