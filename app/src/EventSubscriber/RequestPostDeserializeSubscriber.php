<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class RequestPostDeserializeSubscriber implements EventSubscriberInterface
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
            KernelEvents::REQUEST => ['decorateUserRequest', EventPriorities::POST_DESERIALIZE],
        ];
    }

    /**
     * @param RequestEvent $requestEvent
     */
    public function decorateUserRequest(RequestEvent $requestEvent)
    {
        $request = $requestEvent->getRequest();
        $route = $request->attributes->get('_route');
        // POST /user/profile and /user/movies need to know current user, let's help them
        if (in_array($route, [
            'api_user_profiles_post_collection',
            'api_user_moviess_post_collection',
        ])) {
            $token = $this->tokenStorage->getToken();
            /** @var User $userEntity */
            $userEntity = $token->getUser();
            if (!is_null($userEntity)) {
                $request->attributes->get('data')->setUser($userEntity);
            }
        }
    }
}