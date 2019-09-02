<?php

namespace App\Dto\In;

use Symfony\Component\Validator\Constraints as Assert;

final class UserMovieDto
{
    /**
     * @var string
     * @Assert\NotBlank
     */
    public $imdbId;
}
