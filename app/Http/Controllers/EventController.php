<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Guest;
use App\Models\Form;
use Illuminate\Http\Request;
use Inertia\Inertia; 
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;
use App\Services\QrCodeService;
use App\Jobs\GenerateGuestUuid; 
use App\Jobs\GenerateGuestQrCode;

class EventController extends Controller
{
    use AuthorizesRequests;

    protected $qrCodeService;

    public function __construct(QrCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }

    public function index() { 
        $events = Event::where('user_id', auth()->id())->get(); 
        return Inertia::render('Events/Index', [ 
            'events' => $events, 
            'hasEvents' => $events->isNotEmpty(), 
        ]); 
    } 

    public function create() 
    {
        return inertia('Events/Create');
    }

    public function store(Request $request) 
    { 
        $validateData = $request->validate([ 
            'title' => 'required|string|max:255', 
            'category' => 'required|string|max:255',
            'eventType' => 'required|string',
            'startDate' => 'required|date', 
            'startTime' => 'nullable|date_format:H:i', 
            'eventDays' => 'required_if:eventType,multiple|array',
            'timezone' => 'required|string',
            'location' => 'nullable|string|max:255',
        ]); 

        // Encode eventDays to JSON before storing in database
        if (isset($validateData['eventDays'])) {
            $validateData['eventDays'] = json_encode($validateData['eventDays']);
        }


        $event = Event::create([ 
            'user_id' => auth()->id(),
            ...$validateData,
        ]); 

        // Generate QR code and cache it if the check-in ption is QR Code
        $qrCode = $this->qrCodeService->generateEventQrCode(route('events.verify', $event->id));
        Redis::set('event_qr_code_' . $event->id, base64_encode($qrCode));

        return response()->json([
            'id' => $event->id,
            'message' => 'Event created successfully'
        ]);
    }

    public function verify($id)
    {
        $event = Event::findOrFail($id);

        return view('events.verify', ['event' => $event]);
    }

    public function show(Event $event)
    {
        $this->authorize('view', $event);
        $event->loadCount('guests');

        // Retrieve QR Code from Redis if available
        $qrCode = Redis::get('event_qr_code' . $event->id);
        if(!$qrCode) {
            // Fallback if not found in cache
            $qrCode = $this->qrCodeService->generateEventQrCode(route('events.verify', $event->id));
            // Cache it for future use
            Redis::set('event_qr_code_' . $event->id, $qrCode);
        } 

        return Inertia::render('Events/Show', [
            'event' => $event,
            'qrCode' => $qrCode,
        ]);
    }

    // Show the form for editing an event (optional) 
    public function edit(Event $event) 
    { 
        $this->authorize('update', $event); 

        return Inertia::render('Events/Edit', [ 
            'event' => $event, 
        ]); 
    } 

    // Update an existing event in the database (optional) 
    public function update(Request $request, Event $event) 
    { 
        $this->authorize('update', $event); 

        $request->validate([ 
            'title' => 'required|string|max:255', 
            'startDate' => 'required|date', 
            'endDate' => 'nullable|date',
            'startTime' => 'nullable|date_format:H:i', 
            'location' => 'nullable|string|max:255', 
        ]);

        $event->update($request->all()); 

        return redirect()->route('events.show', $event->id); 
    }

    public function guestList(Event $event)
    {
        $guests = $event->guests;
        $hasGuests = $guests->isNotEmpty();
        return Inertia::render('Guests/GuestList', [
            'event' => $event,
            'guests' => $guests,
            'hasGuests' => $hasGuests,
        ]);
    }

    public function formHandler(Request $request, Event $event)
    {
        return Inertia::render('Guests/FormHandler', [
            'event' => $event,
            'eventId' => $event->id,
        ]);
    }

    public function handleSubmit(Request $request, Event $event)
    {
        $validateData = $request->validate([
            'title' => 'required|string|max:255',
            'fields' => 'required|array',
            'fields.*.name' => 'required|string',
        ]);

        // Save form data in the database
        Form::create([
            'user_id' => auth()->id(),
            'event_id' => $event->id,
            'title' => $validateData['title'],
            'fields' => $validateData['fields']
        ]);

        return redirect()->route('events.selectForm', $event);
    }



    public function selectForm(Event $event)
    {
        $forms = Form::where('event_id', $event->id)->get();

        return Inertia::render('Guests/SelectForm', [
            'event' => $event,
            'eventId' => $event->id,
            'forms' => $forms
        ]);
    }

    public function viewForm(Event $event, Form $form) 
    { 
        // Fetch the form data 
        $formData = [ 
            'title' => $form->title, 
            'fields' => $form->fields 
        ]; 

        // Return the form data as JSON 
        return Inertia::render('Guests/ViewForm', [
            'event' => $event,
            'formId' => $form->id,
            'formData' => $formData
        ]);
        // return response()->json($formData);

    } 
        // Existing methods like formHandler, etc. 

    public function getFieldProperties($fieldName)
    {
        // Efficient data structures: PHP associative array (hash map) for lookup table
        $lookupTable = [
            'company' => ['label' => 'Company', 'type' => 'text', 'placeholder' => 'Enter company name', 'name' => 'company'],
            'jobTitle' => ['label' => 'Job Title', 'type' => 'text', 'placeholder' => 'Enter job title', 'name' => 'jobTitle'],
            'topicsOfInterest' => ['label' => 'Topics of Interest', 'type' => 'textarea', 'placeholder' => 'Enter topics of interest', 'name' => 'topicsOfInterest'],
            'linkedIn' => ['label' => 'LinkedIn Profile', 'type' => 'url', 'placeholder' => 'Enter LinkedIn profile URL', 'name' => 'linkedIn'],
            'website' => ['label' => 'Website', 'type' => 'url', 'placeholder' => 'Enter website URL', 'name' => 'website'],
            'gender' => ['label' => 'Gender', 'type' => 'select', 'options' => ['Male', 'Female', 'Non-Binary'], 'name' => 'gender'],
            'preferredDate' => ['label' => 'Preferred Date', 'type' => 'date', 'placeholder' => 'Select preferred date', 'name' => 'preferredDate'],
            'preferredTime' => ['label' => 'Preferred Time', 'type' => 'time', 'placeholder' => 'Select preferred time', 'name' => 'preferredTime'],
            'phone' => ['label' => 'Phone', 'type' => 'tel', 'placeholder' => 'Enter phone number', 'name' => 'phone'],
            'address' => ['label' => 'Address', 'type' => 'text', 'placeholder' => 'Enter address', 'name' => 'address'],
            'zipCode' => ['label' => 'Zip Code', 'type' => 'text', 'placeholder' => 'Enter zip code', 'name' => 'zipCode'],
            'country' => ['label' => 'Country', 'type' => 'select', 'options' => ['Country1', 'Country2', 'Country3'], 'name' => 'country'],
            'contactMethod' => ['label' => 'Preferred Contact Method', 'type' => 'select', 'options' => ['Email', 'Phone', 'SMS'], 'name' => 'contactMethod'],
            'emergencyContact' => ['label' => 'Emergency Contact', 'type' => 'tel', 'placeholder' => 'Enter emergency contact number', 'name' => 'emergencyContact'],
            'dietaryRestrictions' => ['label' => 'Dietary Restrictions', 'type' => 'text', 'placeholder' => 'Enter any dietary restrictions', 'name' => 'dietaryRestrictions'],
            'sessionPreferences' => ['label' => 'Session Preferences', 'type' => 'select', 'options' => ['Morning Session', 'Afternoon Session', 'Networking Event'], 'name' => 'sessionPreferences'],
            'foodPreferences' => ['label' => 'Food Preferences', 'type' => 'select', 'options' => ['Vegetarian', 'Non-Vegetarian', 'Vegan'], 'name' => 'foodPreferences'],
            'musicRequests' => ['label' => 'Music Requests', 'type' => 'textarea', 'placeholder' => 'Enter your music requests', 'name' => 'musicRequests'],
            'allergies' => ['label' => 'Allergies', 'type' => 'textarea', 'placeholder' => 'Enter any allergies', 'name' => 'allergies'],
            'name' => ['label' => 'Name', 'type' => 'text', 'placeholder' => 'Enter name', 'name' => 'name'],
            'sex' => ['label' => 'Sex', 'type' => 'text', 'placeholder' => 'Enter sex', 'name' => 'sex'],
            'plusOne' => ['label' => 'Plus One or More', 'type' => 'number', 'placeholder' => 'Enter number of plus ones', 'name' => 'plusOne'],
            'reasonForAppointment' => ['label' => 'Reason for Appointment', 'type' => 'textarea', 'placeholder' => 'Enter reason for appointment', 'name' => 'reasonForAppointment'],
            'numberOfGuests' => ['label' => 'Number of Guests', 'type' => 'number', 'placeholder' => 'Enter number of guests', 'name' => 'numberOfGuests'],
        ];

        // Check the Redis cache before performing a lookup
        $cachedField = Redis::get('field_properties_' . $fieldName);
        if ($cachedField) {
            return json_decode($cachedField, true);
        }

        // Fetch from the lookup table and update the Redis cache
        if (isset($lookupTable[$fieldName])) {
            $lookupData = $lookupTable[$fieldName];
            Redis::set('field_properties_' . $fieldName, json_encode($lookupData)); // Store in Redis cache
            return $lookupData;
        }

        return null; // Return null if the field name is not found
    }

    public function formSubmit(Request $request, Event $event)
    {
        $validateData = $request->validate([
            'formId' => 'required|exists:forms,id',
            'formData' => 'required|array'
        ]);

        // Process the form data (e.g., save to database)
        // Form submission logic...

        return response()->json(['message' => 'Form submitted successfully']);
    }



    public function AddGuestMethod(Event $event) 
    {
        return Inertia::render('Guests/AddGuestMethod', [
            'eventId' => $event->id,
            'event' => $event,
        ]);
    }

    public function storeGuest(Request $request, Event $event)
    {
        $validateData = $request->validate([
            'name' => 'required|string|max:255',
            'sex' => 'required|in:male,female,other',
            'guest_cap' => 'required|integer|min:1',
            'note' => 'nullable|string|max:500',
        ]);

        $guest = Guest::create([ 
            'event_id' => $event->id, 
            ...$validateData, 
        ]); 

        // Retrieve event QR code from redis
        $eventQrCode = Redis::get('event_qr_code_' . $event->id);
        
        if ($eventQrCode) { 
            Log::info('Event QR code retrieved from Redis', ['eventId' => $event->id, 'eventQrCode' => $eventQrCode]); 
        } else { 
            Log::error('Failed to retrieve event QR code from Redis', ['eventId' => $event->id]); 
        }

        // Ensure only essential data is combined
        $baseURL = 'https://example.com/guest/';
        $dataToEncode = $baseURL . $guest->id;

        // Log length of data being encoded
        Log::info('Length of data being encoded', ['dataLength' =>strlen($dataToEncode)]);

        // Generate guest QR code
        $guestQrCode = $this->qrCodeService->generateEventQrCode($dataToEncode);
        Log::info('Generated guest QR code', ['guestQrCode' => substr($guestQrCode, 0, 50) . '...']);

        // Store guest Qr code in Redis
        Redis::set('guest_qr_code_' . $guest->id, $guestQrCode);
        Log::info('Stored guest QR code in Redis', ['key' => 'guest_qr_code_' . $guest->id, 'value' => substr($guestQrCode, 0, 50) . '...']); 
        
        return redirect()->route('events.guestQrCode', ['event' => $event->id, 'guest' => $guest->id]);
    }

    public function showGuestQrCode(Event $event, Guest $guest) 
    { 
        // Retrieved the generated guest QR code from Redis
        $redisGuestKey = 'guest_qr_code_' . $guest->id;
        $guestQrCode = Redis::get($redisGuestKey);

        // Check if the guest QR code was retrieved 
        if ($guestQrCode) { 
            Log::info('Guest QR code retrieved from Redis', [
                'guestId' => $guest->id,
                'guestQrCode' => substr($guestQrCode, 0, 50) . '...',
                'fullQrCode' => $guestQrCode
            ]); 
        } else { 
            Log::error('Failed to retrieve guest QR code from Redis', ['guestId' => $guest->id]); 
            return back()->withErrors(['qrCode' => 'Failed to retrieve guest QR code.']); 
        } 

        return Inertia::render('Guests/GuestQrCode', [ 
            'guest' => $guest, 
            'qrCode' => $guestQrCode
        ]);
    }

    public function viewGuest(Event $event, Guest $guest)
    {
        return Inertia::render('Guests/ViewGuest', [
            'event' => $event,
            'guest' => $guest
        ]);
    }

    public function editGuest(Guest $guest)
    {
        return Inertia::render('Guests/EditGuest', ['guest' => $guest]);
    }

    public function destroyGuest(Event $event, Guest $guest)
    {
        $guest->delete();

        return redirect()->route('events.guestList', $event);
    }

        // Delete an event
    public function deleteEvent(Event $event) 
    { 
        $this->authorize('delete', $event); 

        $event->delete(); 

        return redirect()->route('events.index'); 
    }
}
