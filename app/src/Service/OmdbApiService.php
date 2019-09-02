<?php

namespace App\Service;

use aharen\OMDbAPI;

final class OmdbApiService implements MovieApiServiceInterface
{
    const TYPE_MOVIE = 'movie';
    /**
     * var OMDbAPI
     */
    private $api;

    /**
     * @param OMDbApi $api
     */
    public function __construct(OMDbApi $api)
    {
        $this->api = $api;
    }

    /**
     * @param string $term
     * @param int $page
     * @return array|null
     */
    public function search(string $term, int $page = 1)
    {
        /** @var \stdClass $result */
        $result = $this->api->search($term, self::TYPE_MOVIE, null, $page);
        if (property_exists($result, 'data') && 'True' === $result->data->Response) {
            return $result->data;
        }
        return null;
    }

    /**
     * @param string $identifier
     * @return \stdClass|null
     */
    public function fetch(string $identifier)
    {
        $result = $this->api->fetch('i', $identifier);
        if (property_exists($result, 'data') && !empty($result->data)) {
            return $result->data;
        }
        return null;
    }
}
