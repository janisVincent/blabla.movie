<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\Out;
use App\Entity\User;

final class UserOutputDataTransformer implements DataTransformerInterface
{
    /**
     * @param User $data
     * @param string $to
     * @param array $context
     * @return Out\UserDto
     */
    public function transform($data, string $to, array $context = [])
    {
        $userProfileOutputDataTransformer = new UserProfileOutputDataTransformer();
        $userProfile = $data->getProfile();
        return new Out\UserDto($data->getEmail(), !is_null($userProfile)
            ? $userProfileOutputDataTransformer->transform($userProfile, 'Out\UserProfileDto') : null);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return Out\UserDto::class === $to && $data instanceof User;
    }
}