<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\PaginatorInterface;

final class ArrayPaginator implements \IteratorAggregate, PaginatorInterface
{
    /** @var array */
    private $items;
    /** @var float */
    private $totalItems;
    /** @var float */
    private $itemsPerPage;
    /** @var float */
    private $lastPage;
    /** @var float */
    private $currentPage = 1;

    public function __construct(array $items, float $totalItems, float $itemsPerPage)
    {
        $this->items = $items;
        $this->totalItems = $totalItems;
        $this->itemsPerPage = $itemsPerPage;
        $this->lastPage = ceil($totalItems / $itemsPerPage);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    public function count()
    {
        return $this->totalItems;
    }

    public function getCurrentPage(): float
    {
        return $this->currentPage;
    }

    public function setCurrentPage(float $currentPage): ArrayPaginator
    {
        $this->currentPage = $currentPage;
        return $this;
    }

    public function getItemsPerPage(): float
    {
        return $this->itemsPerPage;
    }

    public function getLastPage(): float
    {
        return $this->lastPage;
    }

    public function getTotalItems(): float
    {
        return $this->totalItems;
    }
}