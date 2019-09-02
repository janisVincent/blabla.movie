<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\Out;
use App\Entity\Movie;
use App\Entity\UserMovie;

final class MovieOutputDataTransformer implements DataTransformerInterface
{
    /**
     * @param Movie $data
     * @param string $to
     * @param array $context
     * @return Out\MovieDto
     */
    public function transform($data, string $to, array $context = [])
    {
        $userProfileOutputDataTransformer = new UserProfileOutputDataTransformer();
        return new Out\MovieDto($data->getImdbId(), $data->getTitle(), (int)$data->getYear(), $data->getPoster(),
            $data->getUsers()->map(function (UserMovie $userMovieEntity) use ($userProfileOutputDataTransformer) {
                return $userProfileOutputDataTransformer->transform($userMovieEntity->getUser()->getProfile(),
                    'Out\UserProfileDto');
            })->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return Out\MovieDto::class === $to && $data instanceof Movie;
    }
}