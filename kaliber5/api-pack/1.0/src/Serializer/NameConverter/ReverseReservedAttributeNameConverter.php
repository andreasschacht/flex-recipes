<?php

namespace App\Serializer\NameConverter;

use ApiPlatform\Core\JsonApi\Serializer\ReservedAttributeNameConverter;
use Symfony\Component\Serializer\NameConverter\AdvancedNameConverterInterface;

/**
 * Class ReverseReservedAttributeNameConverter.
 *
 * Reverts the unnecessary converting of the ReservedAttributeNameConverter.
 * e.g. it underscored "included"-key in the attributes section
 */
class ReverseReservedAttributeNameConverter implements AdvancedNameConverterInterface
{
    /**
     * @var AdvancedNameConverterInterface
     */
    private $decorated;

    /**
     * @var ReservedAttributeNameConverter
     */
    private $reservedAttributeNameConverter;

    /**
     * ReverseReservedAttributeNameConverter constructor.
     *
     * @param AdvancedNameConverterInterface $decorated
     */
    public function __construct(AdvancedNameConverterInterface $decorated)
    {
        $this->decorated = $decorated;
        $this->reservedAttributeNameConverter = new ReservedAttributeNameConverter();
    }

    /**
     * @param string      $propertyName
     * @param string|null $class
     * @param string|null $format
     * @param array       $context
     *
     * @return bool|string
     */
    public function normalize($propertyName, string $class = null, string $format = null, array $context = [])
    {
        // Undo underscore "reserved attributes"
        return $this->reservedAttributeNameConverter->denormalize($this->decorated->normalize($propertyName, $class, $format, $context), $class, $format, $context);
    }

    /**
     * @param string      $propertyName
     * @param string|null $class
     * @param string|null $format
     * @param array       $context
     *
     * @return string
     */
    public function denormalize($propertyName, string $class = null, string $format = null, array $context = [])
    {
        return $this->decorated->denormalize($propertyName, $class, $format, $context);
    }
}
