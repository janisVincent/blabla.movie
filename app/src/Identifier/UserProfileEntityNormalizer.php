<?php

namespace App\Identifier;

use App\Exception\AppException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

class UserProfileEntityNormalizer extends DateTimeNormalizer
{
    /**
     * @param mixed $data
     * @param string $class
     * @param null $format
     * @param array $context
     * @return array|bool|\DateTime|\DateTimeImmutable|false|object
     * @throws AppException
     * @throws ExceptionInterface
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        try {
            return parent::denormalize($data, $class, $format, $context);
        } catch (NotNormalizableValueException $e) {
            throw new AppException("Expected date format is '{$context[self::FORMAT_KEY]}'.");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}