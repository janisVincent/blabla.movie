<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\UserMovie;
use Doctrine\ORM\EntityManagerInterface;

final class UserMoviePersister implements DataPersisterInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function supports($data): bool
    {
        return $data instanceof UserMovie;
    }

    /**
     * @param UserMovie $data
     * @return UserMovie
     * @throws \Exception
     */
    public function persist($data)
    {
        if (is_null($data->getId())) {
            $data->setCreationDate(new \DateTime());
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