<?php

namespace CloudMyn\Bookmark\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Bookmarked
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Model $subject;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        $subject
    ) {
        $this->subject = $subject;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('cloudmyn.bookmark');
    }
}
