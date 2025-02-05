<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Guest;
use App\Models\FieldConfig;
use App\Models\FormField;
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

    public function index() 
    { 
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
            'startDate' => 'required|date', 
            'startTime' => 'nullable|date_format:H:i',
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
        $qrCode = $this->getQrCodeForEvent($event);

        return Inertia::render('Events/Show', [
            'event' => $event,
            'qrCode' => $qrCode,
        ]);
    }

    protected function getQrCodeForEvent(Event $event)
    {
        // Retrieve QR Code from Redis if available
        $qrCode = Redis::get('event_qr_code_' . $event->id);
        \Log::info('Check for QR code retrieval: event_qr_code_' . $event->id, ['cachedQrCode' => $qrCode]);

        if (!$qrCode) {
            try {
                $qrCode = $this->qrCodeService->generateEventQrCode(route('events.verify', $event->id));
                \Log::info('Generated QR Code Data:', ['qrCode' => $qrCode]);

                if ($qrCode) {
                    Redis::set('event_qr_code_' . $event->id, $qrCode);
                    \Log::info('QR Code successfully stored in Redis');
                } else {
                    \Log::error('QR Code generation failed: No data returned from qrCodeService');
                }
            } catch (\Exception $e) {
                \Log::error('Error generating QR Code:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            }
        }

        return $qrCode;
    }


    public function guestTicket(Event $event, Guest $guest)
    {
        // Retrieve QR Code from redis if available
        $qrCode = $this->getQrCodeForEvent($event);

        $guest->form_data = json_decode($guest->form_data, true);

        return Inertia::render('Guests/GuestTicket', [
            'event' => $event,
            'guest' => $guest,
            'qrCode' => $qrCode
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
            'category' => 'required|string|max:255',
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
        $guests = $event->guests->map(function ($guest) {
            $guest->form_data = json_decode($guest->form_data, true);
            \Log::info('Form Data:', $guest->form_data);
            return $guest;
        });

        $hasGuests = $guests->isNotEmpty();

        return Inertia::render('Guests/GuestList', [
            'event' => $event,
            'guests' => $guests,
            'hasGuests' => $hasGuests,
        ]);
    }

    public function viewGuest(Event $event, Guest $guest)
    {
        $guest->form_data = json_decode($guest->form_data, true);
        
        return Inertia::render('Guests/ViewGuest', [
            'event' => $event,
            'guest' => $guest
        ]);
    }

    public function formHandler(Request $request, Event $event)
    {
        return Inertia::render('Guests/FormHandler', [
            'event' => $event,
            'eventId' => $event->id,
        ]);
    }

    public function getAllFields()
    {
        \Log::info('Entering getAllFields method');
        try {
            $allFields = FormField::all()->keyBy('name');
            \Log::info('Fetched all fields', ['fields' => $allFields]);

            return response()->json($allFields);
        } catch (\Exception $e) {
            \Log::error('Error in getAllFields method', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
        
    }

    public function getCategoryFields($category)
    {
        try {
            \Log::info('Entering getCategoryFields method', ['category' => $category]);
            $fieldConfig = FieldConfig::where('category', $category)->first();

            if ($fieldConfig) {
                \Log::info('FieldConfig found', ['fieldConfig' => $fieldConfig]);
                $fields = json_decode($fieldConfig->fields, true);
                \Log::info('Decoded fields', ['fields' => $fields]);

                return response()->json($fields);
            } else {
                \Log::warning('Category fields not found', ['category' => $category]);
                return response()->json(['error' => 'Category fields not found'], 404);
            }
        } catch (\Exception $e) {
            \Log::error('Error in getCategoryFields method', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function storeForm(Request $request)
    {
        \Log::info('storeForm called with request:', $request->all());

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'fields' => 'required|array',
            'event_id' => 'required|integer|exists:events,id'
        ]);

        \Log::info('Validated data:', $validatedData);

        try {
            $form = Form::create([
                'title' => $validatedData['title'],
                'user_id' => auth()->id(),
                'event_id' => $request->event_id,
                'fields' => json_encode($validatedData['fields'])
            ]);
            \Log::info('Form saved successfully:', ['form' => $form->toArray()]);
            
            return response()->json(['message' => 'Form saved successfully', 'form' => $form]);
        } catch (\Exception $e) {
            \Log::error('Error saving form:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            return response()->json(['error' => 'Error saving form data'], 500);
        }
        
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

    public function getForm($id)
    {
        \Log::info('getForm called with ID:', ['id' => $id]);

        $form = Form::find($id);

        if ($form) {
            \Log::info('Form found:', ['form' => $form->toArray()]);

            $form->fields = json_decode($form->fields, true);
            \Log::info('Decode form fields:', ['fields' => $form->fields]);
            return response()->json($form);
        } else {
            \Log::error('Form not found with ID:', ['id' => $id]);
            return response()->json(['error' => 'Form not found'], 404);
        }
    }

    public function viewForm(Event $event, Form $form) 
    { 
        \Log::info('viewForm called with Event and Form:', ['event' => $event->toArray(), 'form' => $form->toArray()]);

        $form->fields = json_decode($form->fields, true);

        if (is_array($form->fields)) { 
            \Log::info('Decoded form fields:', ['fields' => $form->fields]); 
        } else { 
            \Log::error('Failed to decode form fields:', ['fields' => $form->fields]); 
        }

        return Inertia::render('Guests/ViewForm', [
            'event' => $event,
            'eventId' => $event->id,
            'form' => $form
        ]);
        
    } 

    public function saveGuest(Request $request, Event $event)
    {
        \Log::info('saveGuest called with request:', $request->all());

        // Retrieve the specific form fields for the current form and user
        $form = Form::where('id', $request->form_id)
                    ->where('event_id', $event->id)
                    ->where('user_id', auth()->id())
                    ->first();

        if (!$form) {
            return response()->json(['error' => 'Form not found'], 404);
        }

        $formFields = json_decode($form->fields, true);

        // Generate validation rules dynamically based on form fields
        $validationRules = [];
        $fillableFields = [];
        foreach ($formFields as $field) {
            // Set validation rules based on field type or other criteria
            $validationRules[$field['name']] = 'required|string|max:255'; // Customize rules as needed
            $fillableFields[] = $field['name'];
        }

        $validatedData = $request->validate($validationRules);

        try {
            // Merging validated data with event ID
            $guestData = [
                'event_id' => $event->id,
                'form_data' => json_encode($validatedData)
            ];

            // Creating guest entry
            $guest = Guest::create($guestData);
            \Log::info('Guest saved successfully:', ['guest' => $guest->toArray()]);

            return redirect()->route('events.guestList', ['event' => $event->id, 'guest' => $guest->id]);
        } catch (\Exception $e) {
            \Log::error('Error saving guest:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Error saving guest data'], 500);
        }
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

        return redirect()->route('dashboard'); 
    }
}
