<?php

namespace App\Serializer;

use ApiPlatform\Core\JsonApi\Serializer\ItemNormalizer;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ItemNormalizerDecorator.
 */
class ItemNormalizerDecorator implements NormalizerInterface, DenormalizerInterface, SerializerAwareInterface, CacheableSupportsMethodInterface
{
    /**
     * @var ItemNormalizer
     */
    private $itemNormalizer;

    /**
     * ItemNormalizerDecorator constructor.
     *
     * @param ItemNormalizer $itemNormalizer
     */
    public function __construct(AbstractItemNormalizer $itemNormalizer)
    {
        $this->itemNormalizer = $itemNormalizer;
    }

    /**
     * @param SerializerInterface $serializer
     */
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->itemNormalizer->setSerializer($serializer);
    }

    /**
     * @param mixed  $data
     * @param string $type
     * @param null   $format
     * @param array  $context
     *
     * @return array|null|object
     *
     * @throws ExceptionInterface
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        return $this->itemNormalizer->denormalize($data, $type, $format, $context);
    }

    /**
     * @param mixed  $data
     * @param string $type
     * @param null   $format
     *
     * @return bool
     */
    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return $this->itemNormalizer->supportsDenormalization($data, $type, $format);
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return $this->itemNormalizer->hasCacheableSupportsMethod();
    }

    /**
     * @param mixed $object
     * @param null  $format
     * @param array $context
     *
     * @return array|bool|float|int|string
     *
     * @throws ExceptionInterface
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        $context['fetch_data'] = false;
        $isRoot = $context['is_root'] ?? true;
        $doc = $this->itemNormalizer->normalize($object, $format, array_merge($context, ['is_root' => false]));
        if (!is_array($doc)) {
            return $doc;
        }

        if (true === $isRoot) { // only once per serialization
            array_walk_recursive($doc, function (&$item, $key) use ($object) {
                if ('id' === $key) {
                    $item = $this->iriToId($item);
                }
            });
        }

        return $doc;
    }

    /**
     * @param mixed $data
     * @param null  $format
     *
     * @return bool
     */
    public function supportsNormalization($data, string $format = null)
    {
        return $this->itemNormalizer->supportsNormalization($data, $format);
    }

    /**
     * @param string $iri
     *
     * @return string
     */
    private function iriToId(string $iri): string
    {
        if (false !== ($pos = strrpos($iri, '/'))) {
            return substr($iri, $pos + 1);
        }

        return $iri;
    }
}
