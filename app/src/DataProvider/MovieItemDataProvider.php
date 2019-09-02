<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Movie;
use App\Service\OmdbApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class MovieItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    /** @var OmdbApiService */
    private $movieApi;
    /** @var Request */
    private $request;
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(
        OmdbApiService $movieApi,
        RequestStack $requestStack,
        EntityManagerInterface $entityManager
    ) {
        $this->movieApi = $movieApi;
        $this->request = $requestStack->getCurrentRequest();
        $this->entityManager = $entityManager;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Movie::class === $resourceClass;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        $movieEntity = $this->entityManager->getRepository(Movie::class)->find($id);
        if (is_null($movieEntity)) {
            $result = $this->movieApi->fetch($id);

            $movieEntity = new Movie();
            $movieEntity->setImdbId($result->imdbID)
                ->setTitle($result->Title)
                ->setYear($result->Year)
                ->setPoster($result->Poster);
            $this->entityManager->persist($movieEntity);
            $this->entityManager->flush();
        }
        return $movieEntity;
    }
}