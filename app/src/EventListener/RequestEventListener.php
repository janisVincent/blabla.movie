<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class RequestEventListener
{
    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $requestContent = $request->getContent();
        if ('json' != $request->getContentType() || empty($requestContent)) {
            return;
        }
        $data = json_decode($requestContent, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new BadRequestHttpException('Invalid JSON request: ' . json_last_error_msg());
        }
        $request->request->replace(is_array($data) ? $data : array());
    }
}