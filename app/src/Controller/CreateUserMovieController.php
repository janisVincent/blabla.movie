<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\User;
use App\Entity\UserMovie;
use App\Exception\AppException;
use App\Service\MovieApiServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;

class CreateUserMovieController
{
    /** @var Request */
    private $request;
    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var User */
    private $userEntity;
    /** @var MovieApiServiceInterface */
    private $movieApi;

    public function __construct(
        RequestStack $requestStack,
        EntityManagerInterface $entityManager,
        Security $security,
        MovieApiServiceInterface $movieApi
    ) {
        $this->request = $requestStack->getCurrentRequest();
        $this->entityManager = $entityManager;
        $this->userEntity = $security->getUser();
        $this->movieApi = $movieApi;
    }

    /**
     * @param UserMovie $data
     * @return UserMovie
     * @throws AppException
     */
    public function __invoke($data)
    {
        $imdbId = $this->request->request->get('imdbId');
        if (empty($imdbId)) {
            throw new AppException('imdbId: This value should not be blank.');
        }

        $nbMaxMovies = 3;
        $userMovieEntities = $this->entityManager->getRepository(UserMovie::class)->findBy([
            'user' => $this->userEntity,
        ]);
        if (count($userMovieEntities) >= $nbMaxMovies) {
            throw new AppException(sprintf('User has already voted for %s movies.', $nbMaxMovies));
        }

        $userMovieEntity = array_filter($userMovieEntities, function (UserMovie $userMovieEntity) use ($imdbId) {
            return $userMovieEntity->getMovie()->getImdbId() === $imdbId;
        });
        if (!empty($userMovieEntity)) {
            throw new AppException('User has already voted for this movie.');
        }

        $movieEntity = $this->entityManager->getRepository(Movie::class)->findOneBy([
            'imdbId' => $imdbId
        ]);
        if (is_null($movieEntity)) {
            $apiMovie = $this->movieApi->fetch($imdbId);
            $movieEntity = new Movie();
            $movieEntity
                ->setImdbId($apiMovie->imdbID)
                ->setTitle($apiMovie->Title)
                ->setPoster($apiMovie->Poster)
                ->setYear($apiMovie->Year);
        }

        $userMovieEntity = new UserMovie();
        $userMovieEntity
            ->setMovie($movieEntity)
            ->setUser($this->userEntity);

        return $userMovieEntity;
    }
}
