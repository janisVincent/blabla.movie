<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\Pagination;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Movie;
use App\Service\OmdbApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class MovieCollectionDataProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface
{
    /** @var OmdbApiService */
    private $movieApi;
    /** @var Request */
    private $request;
    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var Pagination */
    private $pagination;

    public function __construct(
        OmdbApiService $movieApi,
        RequestStack $requestStack,
        EntityManagerInterface $entityManager,
        Pagination $pagination
    ) {
        $this->movieApi = $movieApi;
        $this->request = $requestStack->getCurrentRequest();
        $this->entityManager = $entityManager;
        $this->pagination = $pagination;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Movie::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null)
    {
        $route = $this->request->attributes->get('_route');
        if ('api_movies_get_ranking_collection' == $route) {
            $query = $this->entityManager->createQuery('
                SELECT movie, 
                    (SELECT COUNT(user_movie.id) FROM App\Entity\UserMovie user_movie WHERE user_movie.movie = movie) AS HIDDEN cUsers
                FROM App\Entity\Movie movie
                GROUP BY movie
                HAVING cUsers > 0
                ORDER BY cUsers DESC');
            return $query
                ->getResult();
        }

        /** @var \stdClass $result */
        $result = $this->movieApi->search($this->request->get('term', ''),
            (int)$this->pagination->getPage());

        $existingMovieEntities = $this->entityManager->getRepository(Movie::class)->findBy([
            'imdbId' => array_map(function ($movie) {
                return $movie->imdbID;
            }, $result->Search)
        ]);

        $movieEntities = [];
        foreach ($result->Search as $i => $movie) {
            $movieEntity = array_filter($existingMovieEntities, function (Movie $movieEntity) use ($movie) {
                return $movieEntity->getImdbId() == $movie->imdbID;
            });
            if (count($movieEntity)) {
                $movieEntity = reset($movieEntity);
            } else {
                $movieEntity = new Movie();
                $movieEntity->setImdbId($movie->imdbID)
                    ->setTitle($movie->Title)
                    ->setYear($movie->Year)
                    ->setPoster($movie->Poster);
            }
            $movieEntities[] = $movieEntity;
        }

        return [
            'items' => $movieEntities,
            'totalItems' => $result->totalResults
        ];
    }
}