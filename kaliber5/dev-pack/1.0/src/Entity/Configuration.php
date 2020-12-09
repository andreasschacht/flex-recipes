<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Configuration.
 *
 * @ORM\Table
 * @ORM\Entity
 */
class Configuration
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var array
     *
     * @ORM\Column(name="state", type="json")
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=255, unique=true)
     */
    private $hash;

    /**
     * @var array
     *
     * @ORM\Column(type="json")
     */
    private $bom = [];

    /**
     * @var array
     *
     * @ORM\Column(type="json")
     */
    private $summary = [];

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get state.
     *
     * @return array
     */
    public function getState(): ?array
    {
        return $this->state;
    }

    /**
     * Set state.
     *
     * @param array $state
     *
     * @return Configuration
     */
    public function setState(array $state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get hash.
     *
     * @return string
     */
    public function getHash(): ?string
    {
        return $this->hash;
    }

    /**
     * Set hash.
     *
     * @param string $hash
     *
     * @return Configuration
     */
    public function setHash(string $hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * @return array
     */
    public function getBom(): array
    {
        return $this->bom;
    }

    /**
     * @param array $bom
     */
    public function setBom(array $bom): void
    {
        $this->bom = $bom;
    }

    /**
     * @return array
     */
    public function getSummary(): array
    {
        return $this->summary;
    }

    /**
     * @param array $summary
     */
    public function setSummary(array $summary): void
    {
        $this->summary = $summary;
    }
}
