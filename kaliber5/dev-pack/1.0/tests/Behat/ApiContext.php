<?php

namespace App\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Mink\Session;
use Coduo\PHPMatcher\PHPUnit\PHPMatcherAssertions;

/**
 * Class ApiContext
 */
class ApiContext implements Context
{
    Use PHPMatcherAssertions;

    /**
     * @var Session
     */
    private $session;

    /**
     * ApiContext constructor.
     *
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @Then the api response should match:
     */
    public function theApiResponseShouldMatch2(PyStringNode $string)
    {
        $content = $this->session->getPage()->getContent();

        $content = json_encode(json_decode($content, JSON_PRETTY_PRINT));

        $this->assertMatchesPattern($string->getRaw(), $content, 'The value is not as expected');
    }

}