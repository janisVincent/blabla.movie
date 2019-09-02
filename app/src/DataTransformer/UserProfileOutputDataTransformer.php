<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\Out;
use App\Entity\UserProfile;

final class UserProfileOutputDataTransformer implements DataTransformerInterface
{
    /**
     * @param UserProfile $data
     * @param string $to
     * @param array $context
     * @return Out\UserProfileDto
     */
    public function transform($data, string $to, array $context = [])
    {
        return new Out\UserProfileDto($data->getAlias(), $data->getBirthDate()->format('Y-m-d'));
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return Out\UserProfileDto::class === $to && $data instanceof UserProfile;
    }
}