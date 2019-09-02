<?php

namespace App\Controller;

use ApiPlatform\Core\DataProvider\Pagination;
use App\DataProvider\ArrayPaginator;
use App\DataTransformer\UserProfileOutputDataTransformer;
use App\Entity\Movie;
use App\Entity\UserMovie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class ListMovieUsersController extends AbstractController
{
    const ITEMS_PER_PAGE = 30;

    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var Request */
    private $request;
    /** @var Pagination */
    private $pagination;

    public function __construct(
        RequestStack $requestStack,
        EntityManagerInterface $entityManager,
        Pagination $pagination
    ) {
        $this->request = $requestStack->getCurrentRequest();
        $this->entityManager = $entityManager;
        $this->pagination = $pagination;
    }

    /**
     * @param Movie $data
     * @return ArrayPaginator
     */
    public function __invoke($data)
    {
        if (is_null($data)) {
            $userProfileEntities = [];
            $totalItems = 0;
        } else {
            $movieUserEntities = $data->getUsers();
            $totalItems = $movieUserEntities->count();
            $userProfileDataTransformer = new UserProfileOutputDataTransformer();
            $userProfileEntities = array_map(function (UserMovie $userMovieEntity) use (
                $userProfileDataTransformer
            ) {
                return $userProfileDataTransformer->transform($userMovieEntity->getUser()->getProfile(),
                    'Out\UserProfileDto');
            }, $movieUserEntities->slice($this->pagination->getOffset(), $this->pagination->getLimit()));
        }

        $paginator = new ArrayPaginator($userProfileEntities, $totalItems, $this->pagination->getLimit());
        $paginator->setCurrentPage($this->pagination->getPage());
        return $paginator;
    }
}