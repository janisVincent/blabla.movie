<?php

namespace App\Service;

interface MovieApiServiceInterface
{
    /**
     * @param string $term
     * @param int $page
     * @return array|null
     */
    public function search(string $term, int $page = 1);

    /**
     * @param string $identifier
     * @return \stdClass|null
     */
    public function fetch(string $identifier);
}
