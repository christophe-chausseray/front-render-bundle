<?php

namespace Chris\Bundle\FrontRenderBundle;

final class FrontRenderEvents
{
    /**
     * The front_render.before_render event is thrown set front path
     * in the system.
     *
     * The event listener receives an
     * Chris\Bundle\FrontRenderBundle\Event\BeforeRenderEvent instance.
     *
     * @var string
     */
    const BEFORE_RENDER = 'front_render.before_render';
}
