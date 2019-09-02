<?php

namespace App\Controller;

use App\Entity\UserProfile;
use App\Exception\AppException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class CreateUserProfileController
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param UserProfile $data
     * @return UserProfile
     * @throws AppException
     */
    public function __invoke($data)
    {
        $userProfileEntity = $this->entityManager->getRepository(UserProfile::class)->findOneBy([
            'user' => $data->getUser()
        ]);
        if (!is_null($userProfileEntity)) {
            throw new AppException('User profile already exists.');
        }
        return $data;
    }
}
