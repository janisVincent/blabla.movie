<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\AppException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class CreateUserController
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param User $data
     * @return User
     * @throws AppException
     */
    public function __invoke($data)
    {
        $userEntity = $this->entityManager->getRepository(User::class)->findOneBy([
            'email' => $data->getEmail()
        ]);
        if (!is_null($userEntity)) {
            throw new AppException('This email is already in use.');
        }
        return $data;
    }
}
