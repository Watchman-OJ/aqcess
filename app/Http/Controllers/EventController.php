<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Guest;
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

    public function store(Request $request) 
    { 
        $validateData = $request->validate([ 
            'title' => 'required|string|max:255', 
            'date' => 'required|date', 
            'time' => 'nullable|date_format:H:i', 
            'location' => 'nullable|string|max:255', 
            'description' => 'nullable|string|max:500', 
            'check_in_option' => 'required|string|in:Pass,Qr Code', 
        ]); 

        $event = Event::create([ 
            'user_id' => auth()->id(),
            ...$validateData,
        ]); 

        // Generate QR code and cache it if the check-in ption is QR Code
        $qrCode = $this->qrCodeService->generateEventQrCode(route('events.verify', $event->id));

        Redis::set('event_qr_code_' . $event->id, base64_encode($qrCode));

        return redirect()->route('events.index'); 
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

    public function create() 
    { 
        return Inertia::render('Events/Create'); 
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
            'date' => 'required|date', 
            'time' => 'nullable|date_format:H:i', 
            'location' => 'nullable|string|max:255', 
            'description' => 'nullable|string|max:500', 
            'check_in_option' => 'required|string|in:Pass,Qr Code', 
        ]);

        $event->update($request->all()); 

        return redirect()->route('events.show', $event->id); 
    }

    public function guestList(Event $event)
    {
        $guests = $event->guests;
        $hasGuests = $guests->isNotEmpty();
        return Inertia::render('Events/GuestList', [
            'event' => $event,
            'guests' => $guests,
            'hasGuests' => $hasGuests,
        ]);
    }

    public function AddGuest(Event $event) 
    {
        return Inertia::render('Events/AddGuest', ['event' => $event]);
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

        return Inertia::render('Events/GuestQrCode', [ 
            'guest' => $guest, 
            'qrCode' => $guestQrCode
        ]);
    }

    public function viewGuest(Event $event, Guest $guest)
    {
        return Inertia::render('Events/ViewGuest', [
            'event' => $event,
            'guest' => $guest
        ]);
    }

    public function editGuest(Guest $guest)
    {
        return Inertia::render('Events/EditGuest', ['guest' => $guest]);
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
