<?php

namespace Chris\Bundle\FrontRenderBundle\Render;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Templating\EngineInterface;

class FrontRender
{
    /**
     * @var EngineInterface $twig
     */
    protected $engine;

    /**
     * @var string $frontPath
     */
    protected $frontPath;

    /**
     * @var EventDispatcherInterface $dispatcher
     */
    protected $dispatcher;

    /**
     * @var array $parameters
     */
    protected $parameters = [];

    /**
     * @param EngineInterface          $engine
     * @param EventDispatcherInterface $dispatcher
     * @param string                   $frontPath
     */
    public function __construct(EngineInterface $engine, EventDispatcherInterface $dispatcher, $frontPath)
    {
        $this->engine     = $engine;
        $this->dispatcher = $dispatcher;
        $this->frontPath  = $frontPath;
    }

    /**
     * Set Parameters
     *
     * @param array $parameters
     *
     * @return $this
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @return string
     */
    public function dispatch()
    {
        return $this->engine->render($this->frontPath, $this->parameters);
    }
}
