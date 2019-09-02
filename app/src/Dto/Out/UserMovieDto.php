<?php

namespace App\Dto\Out;

final class UserMovieDto
{
    /**
     * @var MovieDto
     */
    public $movie;

    public function __construct(MovieDto $outMovieDto)
    {
        $this->movie = $outMovieDto;
    }
}
