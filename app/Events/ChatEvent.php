<?php

namespace App\Events;

use App\Chat;
use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Auth;
class ChatEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $receiver_id;
    public $message;
    public $created_at;
    public $sender_id;
    public $total_unread_messages;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $receiver_id = $request->receiver_id;
        $total_unread_messages =  Chat::Where(function($q) use($receiver_id){ $q->where('sender_id' , Auth::user()->id); $q->where('receiver_id' , $receiver_id); })->where('is_seen' , 0)->count();
        $this->total_unread_messages = $total_unread_messages + 1;
        $this->receiver_id = $request->receiver_id;
        $this->message = $request->message;
        $this->sender_id = Auth::user()->id;
        $this->created_at = Carbon::now();
        $this->dontBroadcastToCurrentUser();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('chat-'.$this->receiver_id);
    }
}
