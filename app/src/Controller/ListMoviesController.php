<?php

namespace App\Controller;

use ApiPlatform\Core\DataProvider\Pagination;
use App\DataProvider\ArrayPaginator;

final class ListMoviesController
{
    /** @var Pagination */
    private $pagination;

    public function __construct(Pagination $pagination)
    {
        $this->pagination = $pagination;
    }

    public function __invoke($data)
    {
        $paginator = new ArrayPaginator($data['items'], $data['totalItems'], $this->pagination->getLimit());
        $paginator->setCurrentPage($this->pagination->getPage());
        return $paginator;
    }
}