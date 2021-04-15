<?php

declare(strict_types=1);

namespace App\Test;

class DebugUserListener
{
    public function __invoke(UserCreatedEvent $event)
    {
        dump($event->getId());
        throw new \Exception();
    }
}