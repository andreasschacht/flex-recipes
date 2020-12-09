<?php

namespace App\Utils;

use App\Entity\Configuration;

/**
 * Class FrontendLoadUrlResolver.
 */
class FrontendLoadUrlResolver
{
    /**
     * @var string
     */
    private $frontendLoadUrl;

    /**
     * FrontendLoadUrlResolver constructor.
     *
     * @param string $frontendLoadUrl
     */
    public function __construct(string $frontendLoadUrl)
    {
        $this->frontendLoadUrl = $frontendLoadUrl;
    }

    /**
     * @param Configuration $configuration
     *
     * @return string
     */
    public function getLoadUrl(Configuration $configuration): string
    {
        $configLoadUrl = $this->frontendLoadUrl.$configuration->getHash();

        return $configLoadUrl;
    }
}
