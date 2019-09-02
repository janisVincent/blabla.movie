<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\UserProfile;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class RequestPreReadSubscriber implements EventSubscriberInterface
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
            KernelEvents::REQUEST => ['decorateUserProfileURIs', EventPriorities::PRE_READ],
        ];
    }

    /**
     * @param RequestEvent $requestEvent
     */
    public function decorateUserProfileURIs(RequestEvent $requestEvent)
    {
        $request = $requestEvent->getRequest();
        $route = $request->attributes->get('_route');
        // PUT and GET /user/profile do not declare required {id} param, let's help them
        if (in_array($route, [
            'api_user_profiles_get_item',
            'api_user_profiles_put_item',
        ])) {
            $token = $this->tokenStorage->getToken();
            /** @var UserProfile $userProfileEntity */
            $userProfileEntity = $token->getUser()->getProfile();
            if (!is_null($userProfileEntity)) {
                $request->attributes->set('id', $userProfileEntity->getId());
            }
        }
    }
}