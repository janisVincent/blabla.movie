<?php

namespace App\Swagger;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class SwaggerDecorator implements NormalizerInterface
{
    private $decorated;

    public function __construct(NormalizerInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $docs = $this->decorated->normalize($object, $format, $context);

        $basePath = '/api';
        $docs['basePath'] = $basePath;
        $docs['info']['title'] = 'Blabla.movie';
        $docs['info']['version'] = '0.0.7';
        $this->updatePaths($docs, $basePath);

        return $docs;
    }

    private function updatePaths(array $docs, string $basePath)
    {
        /** @var \ArrayObject $paths */
        $paths = $docs['paths'];
        // Remove unavailable GET paths (yet required for POST/PUT operations)
        $removablePaths = ['/api/users/{id}', '/api/user_movies/{id}'];
        $shortPaths = new \ArrayObject();
        foreach ($paths as $path => $resources) {
            if (in_array($path, $removablePaths)) {
                continue;
            }
            $shortPath = str_replace($basePath, '', $path);
            $shortPaths->offsetSet($shortPath, $resources);
        }
        $paths->exchangeArray($shortPaths);

    }

    public function supportsNormalization($data, $format = null)
    {
        return $this->decorated->supportsNormalization($data, $format);
    }
}