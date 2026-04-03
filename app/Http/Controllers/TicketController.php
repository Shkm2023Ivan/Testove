<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Notifications\TicketCreatedNotification;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with('customer')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%$search%")
                  ->orWhereHas('customer', function($cq) use ($search) {
                      $cq->where('name', 'like', "%$search%")
                         ->orWhere('email', 'like', "%$search%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return view('dashboard', [
            'tickets' => $query->paginate(10)->withQueryString(),
            'stats' => [
                'total' => Ticket::count(),
                'new' => Ticket::where('status', 'NEW')->count(),
                'closed' => Ticket::where('status', 'CLOSED')->count(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'required|string',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $customer = Customer::firstOrCreate(
            ['email' => $validated['email']],
            ['name' => $validated['name'], 'phone' => $validated['phone']]
        );

        $ticket = Ticket::create([
            'customer_id' => $customer->id,
            'subject'     => $validated['subject'],
            'message'     => $validated['message'],
            'status'      => 'NEW',
        ]);

        $customer->notify(new TicketCreatedNotification($ticket));

        return $request->ajax() 
            ? response()->json(['success' => true, 'message' => "Заявка #{$ticket->id} создана!"])
            : back()->with('success', 'Отправлено!');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $ticket->update($request->validate([
            'status' => 'required|in:NEW,IN PROGRESS,CLOSED',
            'admin_comment' => 'nullable|string|max:1000',
        ]));
        return back();
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return back();
    }
}