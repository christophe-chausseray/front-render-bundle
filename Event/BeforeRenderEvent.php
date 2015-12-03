<?php

namespace Chris\Bundle\FrontRenderBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class BeforeRenderEvent extends Event
{
    /**
     * @var string frontPath
     */
    protected $frontPath;

    /**
     * @param string $frontPath
     */
    public function __construct($frontPath)
    {
        $this->frontPath = $frontPath;
    }

    /**
     * @return string
     */
    public function getFrontPath()
    {
        return trim($this->frontPath);
    }

    /**
     * Set FrontPath
     *
     * @param string $frontPath
     *
     * @return $this
     */
    public function setFrontPath($frontPath)
    {
        $this->frontPath = $frontPath;

        return $this;
    }
}
