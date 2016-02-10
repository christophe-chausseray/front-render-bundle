<?php

namespace Chris\Bundle\FrontRenderBundle\Tests\CompilerPass;

use Chris\Bundle\FrontRenderBundle\DependencyInjection\CompilerPass\RenderSubscriberCompilerPass;
use Chris\Bundle\FrontRenderBundle\Subscriber\RenderSubscriber;
use PHPUnit_Framework_TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RenderSubscriberCompilerPassTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerBuilder
     */
    protected $containerBuilder;

    /**
     * @var RenderSubscriberCompilerPass
     */
    protected $compilerPass;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->containerBuilder = new ContainerBuilder();
        $this->compilerPass     = new RenderSubscriberCompilerPass();
    }

    /**
     * Test the success on the compiler pass
     */
    public function testProcessSuccessful()
    {
        $this->containerBuilder->setParameter('templating.engines', ['php']);

        $this->containerBuilder
            ->register('front_render_bundle.render_subscriber', RenderSubscriber::class);

        $this->compilerPass->process($this->containerBuilder);

        $this->assertFalse($this->containerBuilder->hasDefinition('front_render_bundle.render_subscriber'));
    }
}
