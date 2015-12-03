<?php

namespace Chris\Bundle\FrontRenderBundle\Render;

use Chris\Bundle\FrontRenderBundle\Event\BeforeRenderEvent;
use Chris\Bundle\FrontRenderBundle\Exception\FrontRenderException;
use Chris\Bundle\FrontRenderBundle\FrontRenderEvents;
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
    public function render()
    {
        $event = new BeforeRenderEvent($this->frontPath);
        $this->dispatcher->dispatch(FrontRenderEvents::BEFORE_RENDER, $event);

        $frontPath = $event->getFrontPath();
        if (empty($frontPath)) {
            throw new FrontRenderException('You need to configure a front path.');
        }

        return $this->engine->render($this->frontPath, $this->parameters);
    }
}
