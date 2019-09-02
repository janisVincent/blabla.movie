<?php

namespace App\Dto\Out;

/**
 * Class MovieDto
 * @package App\Dto\Out
 */
final class MovieDto
{
    /**
     * Movie unique IMDB id
     * @var string
     */
    public $imdbId;
    /**
     * Movie title
     * @var string
     */
    public $title;
    /**
     * Movie release year
     * @var int
     */
    public $year;
    /**
     * Movie poster
     * @var string
     */
    public $poster;
    /**
     * User's votes for this movie
     * @var UserProfileDto[]
     */
    public $users;

    public function __construct(string $imdbId, string $title, int $year, string $poster, array $users)
    {
        $this->imdbId = $imdbId;
        $this->title = $title;
        $this->year = $year;
        $this->poster = $poster;
        $this->users = $users;
    }
}
