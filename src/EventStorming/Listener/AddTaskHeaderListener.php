<?php

declare(strict_types=1);

namespace App\EventStorming\Listener;

use Symfony\Component\HttpKernel\Event\ResponseEvent;

class AddTaskHeaderListener
{
    public function onKernelResponse(ResponseEvent $event)
    {
        $response = $event->getResponse();
        $response->headers->set('x-task', '1');
    }
}
