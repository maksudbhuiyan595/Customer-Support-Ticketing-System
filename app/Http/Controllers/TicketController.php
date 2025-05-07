<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketRequest;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        try {
            $tickets = Ticket::orderBy("created_at","desc")->get();
            return $this->sendResponse($tickets, 'Tickets retrieved successfully.');
        } catch (\Exception $e) {
            return $this->sendError('An error occurred: ' . $e->getMessage(), [], 500);
        }
    }
    public function store(TicketRequest $request)
    {
        try{
            $validated = $request->validated();
             $ticket = Ticket::create($validated);
             return $this->sendResponse($ticket,'Ticket created successfully.');
        }catch(\Exception $e){
            return $this->sendError("An error occurred: ". $e->getMessage(),[],500);
        }
    }
    public function update(TicketRequest $request, $id)
    {
        try {
            $ticket = Ticket::find($id);
            if (!$ticket) {
                return $this->sendError('Ticket not found.', [], 404);
            }
            $validated = $request->validated();
            $ticket->update($validated);
            return $this->sendResponse($ticket, 'Ticket updated successfully.');
        } catch (\Exception $e) {
            return $this->sendError('An error occurred: ' . $e->getMessage(), [], 500);
        }
    }
    public function destroy($id)
    {
        try {
            $ticket = Ticket::find($id);
            if (!$ticket) {
                return $this->sendError('Ticket not found.', [], 404);
            }
            $ticket->delete();
            return $this->sendResponse([], 'Ticket deleted successfully.');
        } catch (\Exception $e) {
            return $this->sendError('An error occurred: ' . $e->getMessage(), [], 500);
        }
    }

}
