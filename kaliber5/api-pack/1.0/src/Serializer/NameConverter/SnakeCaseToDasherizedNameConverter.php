<?php

namespace App\Serializer\NameConverter;

use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

/**
 * Class SnakeCaseToDasherizedNameConverter.
 */
class SnakeCaseToDasherizedNameConverter extends CamelCaseToSnakeCaseNameConverter
{
    /**
     * @param $propertyName
     *
     * @return mixed|string
     */
    public function normalize($propertyName)
    {
        return str_replace('_', '-', parent::normalize($propertyName));
    }

    /**
     * @param $propertyName
     *
     * @return mixed|string|string[]|null
     */
    public function denormalize($propertyName)
    {
        return parent::denormalize(str_replace('-', '_', $propertyName));
    }
}
