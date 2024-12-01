<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\Webinar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebinarController extends Controller
{

    private $GOTOWEBINAR_CLIENT_ID;
    private $GOTOWEBINAR_CLIENT_SECRET;
    private $organizerKey;
    private $accessToken;


    public function __construct()
    {
        $gotoauth = Auth::user()->gotoauth;
        if ($gotoauth) {
            $this->GOTOWEBINAR_CLIENT_ID = $gotoauth->client_id;
            $this->GOTOWEBINAR_CLIENT_SECRET = $gotoauth->client_secret;
            $this->organizerKey = $gotoauth->organizer_key;
            $this->accessToken = $gotoauth->access_token;
        }
    }

    public function index()
    {
        $webinars = Auth::user()->webinars; // Use the relationship to fetch webinars
        return view('admin.webinar.index', compact('webinars'));
    }

    public function create() {
        return view('admin.webinar.create');
    }

    public function edit(Webinar $webinar) {

        //check if webinar is canceled
        if($webinar->canceled) return redirect()->route('webinar.index');


        //this check is only because the create endpoint not working
        //so we check if the webinar in the DB is linked with a webinar in the api

        $webinarDataFromApi = null;
        $startDate = null;
        $startTimeOnly = null;
        $endTimeOnly = null;
        $registrants = null;

        if ($webinar->webinarKey) {

            $url = "https://api.getgo.com/G2W/rest/v2/organizers/{$this->organizerKey}/webinars/{$webinar->webinarKey}";

            $response = Http::withToken($this->accessToken)->get($url);

            if ($response->successful()) {

                $webinarDataFromApi = $response->json();

                //prepare date and time for display
                $startTime = $webinarDataFromApi['times'][0]['startTime'];
                $endTime = $webinarDataFromApi['times'][0]['endTime'];

                // Parse the date-time strings into Carbon instances
                $startCarbon = Carbon::parse($startTime);
                $endCarbon = Carbon::parse($endTime);

                // Extract the date and time parts
                $startDate = $startCarbon->format('Y-m-d');
                $startTimeOnly = $startCarbon->format('H:i');
                $endTimeOnly = $endCarbon->format('H:i');
            } else {
                if ($response->status() === 403) return redirect()->route('gotoauth.edit');   
            }


            //get registrants list
            $url = "https://api.getgo.com/G2W/rest/v2/organizers/{$this->organizerKey}/webinars/{$webinar->webinarKey}/registrants";

            $response = Http::withToken($this->accessToken)->get($url);

            if ($response->successful()) {
                $registrants = $response->json();
            } else {
                if ($response->status() === 403) return redirect()->route('gotoauth.edit');   
            }

        } 

        

        return view('admin.webinar.edit', compact('webinar',
                                                'webinarDataFromApi',
                                                'startDate',
                                                'startTimeOnly',
                                                'endTimeOnly',
                                                'registrants'
                                            ));
        
    }

    public function store(Request $request) {

        //Validate the input
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'date' => 'required', // Ensure date is not in the past
            'start_time' => 'required',
            'end_time' => 'required'
        ]);

        //Combine the date and time fields to create start and end times
        $startDateTime = Carbon::parse($request->input('date') . ' ' . $request->input('start_time'))->toIso8601String();
        $endDateTime = Carbon::parse($request->input('date') . ' ' . $request->input('end_time'))->toIso8601String();

        $response = Http::withToken($this->accessToken)
            ->post("https://api.getgo.com/G2W/rest/v2/organizers/{$this->organizerKey}/webinars", [
                'subject' => $request->input('name'),
                'description' => $request->input('description'),
                'times' => [
                    [
                        'startTime' => $startDateTime,
                        'endTime' => $endDateTime
                    ]
                ]
            ]);

        //******************     
        //because the create endpoint not working the check for seccessful 
        //response was bypassed to create the entry in the database anyway
        //******************   
        
        //if ($response->successful()) {

            $data = $response->json(); 
            //$webinarKey = $data['webinarKey'];
            //$recurrenceKey = $data['recurrenceKey'];

            // Store the webinar in the database
            Auth::user()->webinars()->create([
                'subject' => $validated['name'],
                'description' => $validated['description'],
                'startTime' => $startDateTime,
                'endTime' => $endDateTime,
                'type' => 'single_session',
                'webinarKey' =>  '',
                'recurrenceKey' => ''
            ]);

            return redirect()->route('webinar.index');

        //}
    }

    public function update(Request $request, Webinar $webinar) {

        //check if webinar is canceled
        if($webinar->canceled) return redirect()->route('webinar.index');
        
         //Validate the input
         $validated = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'date' => 'required', // Ensure date is not in the past
            'start_time' => 'required',
            'end_time' => 'required',
            'webinarKey' =>  'required',
        ]);


        //Combine the date and time fields to create start and end times
        $startDateTime = Carbon::parse($request->input('date') . ' ' . $request->input('start_time'))->toIso8601String();
        $endDateTime = Carbon::parse($request->input('date') . ' ' . $request->input('end_time'))->toIso8601String();

        $response = Http::withToken($this->accessToken)
            ->put("https://api.getgo.com/G2W/rest/v2/organizers/{$this->organizerKey}/webinars/{$validated['webinarKey']}", [
                'subject' => $request->input('name'),
                'description' => $request->input('description'),
                'times' => [
                    [
                        'startTime' => $startDateTime,
                        'endTime' => $endDateTime
                    ]
                ]
            ]);


        if ($response->successful()) {

            // save changes in the database
            $webinar->subject = $validated['name'];
            $webinar->description = $validated['description'];
            $webinar->startTime = $validated['start_time'];
            $webinar->endTime = $validated['end_time'];
            $webinar->webinarKey = $validated['webinarKey'];
            $webinar->save();

            return redirect()->route('webinar.index');
        } else {
            if ($response->status() === 403) return redirect()->route('gotoauth.edit');   
        }

        

    }

    public function destroy(Webinar $webinar) {

        //check if webinar is canceled
        if($webinar->canceled) return redirect()->route('webinar.index');

        $response = Http::withToken($this->accessToken)
        ->delete("https://api.getgo.com/G2W/rest/v2/organizers/{$this->organizerKey}/webinars/{$webinar->webinarKey}", [
                'sendCancellationEmails' => false,
            ]);

            if ($response->successful()) {

                // save changes in the database
                $webinar->canceled = 1;
                $webinar->save();
    
                return redirect()->route('webinar.index');
    
            } else {
                if ($response->status() === 403) return redirect()->route('gotoauth.edit');   
            }

        return redirect()->route('webinar.index');
    }

    public function register(Webinar $webinar, Request $request) {

        if ($request->isMethod('get')) {
            if($webinar->canceled) abort(404, 'Resource not found');
            return view('admin.webinar.register', compact('webinar'));
        }


        if ($request->isMethod('post')) {

            //check if webinar is canceled
            if($webinar->canceled) return redirect()->route('welcome');

            //Validate the input
            $validated = $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required'
            ]);

            $response = Http::withToken($this->accessToken)
            ->post("https://api.getgo.com/G2W/rest/v2/organizers/{$this->organizerKey}/webinars/{$webinar->webinarKey}/registrants", [
                'firstName' => $validated['first_name'], 
                'lastName' => $validated['last_name'],
                'email' => $validated['email'],
            ]);

            if ($response->successful()) {
                return redirect()->route('webinar.index');
            } else {
                if ($response->status() === 403) return redirect()->route('gotoauth.edit');   
            }

        }
        
    }
}
