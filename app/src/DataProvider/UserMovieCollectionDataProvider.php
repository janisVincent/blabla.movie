<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\User;
use App\Entity\UserMovie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use function Doctrine\ORM\QueryBuilder;

final class UserMovieCollectionDataProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var User */
    private $userEntity;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->userEntity = $security->getToken()->getUser();
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return UserMovie::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null)
    {
        return $this->entityManager->getRepository(UserMovie::class)->findBy([
            'user' => $this->userEntity
        ]);
    }
}