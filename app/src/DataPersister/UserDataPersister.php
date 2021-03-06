<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserDataPersister implements DataPersisterInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function supports($data): bool
    {
        return $data instanceof User;
    }

    /**
     * @param User $data
     * @return User
     * @throws \Exception
     */
    public function persist($data)
    {
        if (is_null($data->getId())) {
            $data->setCreationDate(new \DateTime());
            $data->setPassword($this->passwordEncoder->encodePassword($data, $data->getPassword()));
        }
        $this->entityManager->persist($data);
        $this->entityManager->flush();
        return $data;
    }

    public function remove($data)
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}