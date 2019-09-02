<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class ViewPreRespondSubscriber implements EventSubscriberInterface
{
    /** @var TokenStorageInterface */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['manageIRIs', EventPriorities::POST_SERIALIZE]
        ];
    }

    public function manageIRIs(ViewEvent $viewEvent)
    {
        $request = $viewEvent->getRequest();
        if ('jsonld' == $request->getRequestFormat()) {
            $route = $request->attributes->get('_route');
            // PUT and GET /user/profile do not declare required {id} param, let's help them
            if (in_array($route, [
                'api_user_profiles_get_item',
                'api_user_profiles_put_item',
            ])) {
                $this->editControllerResult($viewEvent, function ($controllerResult) {
                    $controllerResult['@id'] = preg_replace('/\?id.*$/', '', $controllerResult['@id']);
                    return $controllerResult;
                });

            } elseif ('api_movies_get_collection' == $route) {
                $this->editControllerResult($viewEvent, function ($controllerResult) {
                    foreach ($controllerResult['hydra:member'] as &$resultMember) {
                        unset($resultMember['@id']);
                    }
                    return $controllerResult;
                });
            }
        }
    }

    private function editControllerResult(ViewEvent $viewEvent, callable $fn)
    {
        $controllerResult = json_decode($viewEvent->getControllerResult(), true);
        $controllerResult = $fn($controllerResult);
        $viewEvent->setControllerResult(json_encode($controllerResult));
    }
}