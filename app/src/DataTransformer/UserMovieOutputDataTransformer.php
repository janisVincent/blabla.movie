<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\Out;
use App\Entity\UserMovie;

final class UserMovieOutputDataTransformer implements DataTransformerInterface
{
    /**
     * @param UserMovie $data
     * @param string $to
     * @param array $context
     * @return Out\UserMovieDto
     */
    public function transform($data, string $to, array $context = [])
    {
        $movieOutputDataTransformer = new MovieOutputDataTransformer();
        return new Out\UserMovieDto($movieOutputDataTransformer->transform($data->getMovie(), 'Out\MovieDto'));
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return Out\UserMovieDto::class === $to && $data instanceof UserMovie;
    }
}