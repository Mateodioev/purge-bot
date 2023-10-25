<?php

namespace App\Commands;

use Mateodioev\TgHandler\Events\Types\AllEvent;

class All extends AllEvent
{
    public function execute($args = [])
    {
        $ev = $this->ctx()->getReduced();

        $this->logger()->debug('Event: {ev}', ['ev' => json_encode($ev, JSON_PRETTY_PRINT)]);
    }
}
