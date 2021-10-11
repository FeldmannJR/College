<?php

namespace App\Events;

use App\Indicators\IndicatorOld;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UpdateIndicatorEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $indicator;
    private $newValue;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(IndicatorOld $indicator, $newvalue)
    {
        $this->indicator = $indicator;
        $this->newValue = $newvalue;

        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('indicators');
    }

    private function formatData()
    {
        

    }

    public function broadcastWith()
    {
        return [
            'id' => $this->indicator->getId(),
            'valor' => $this->newValue
        ];
    }
}
