<?php

namespace Chris\Bundle\FrontRenderBundle\Tests;

use Chris\Bundle\FrontRenderBundle\FrontRenderBundle;
use Phake;
use Phake_IMock;
use PHPUnit_Framework_TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class FrontRenderBundleTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerBuilder|Phake_IMock
     */
    protected $container;

    /**
     * @var FrontRenderBundle
     */
    protected $frontRenderBundle;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->container         = Phake::mock(ContainerBuilder::class);
        $this->frontRenderBundle = new FrontRenderBundle();
    }

    /**
     * Test if the the compiler pass is called
     */
    public function testCompilerPassIsCalled()
    {
        $this->frontRenderBundle->build($this->container);

        Phake::verify($this->container)->addCompilerPass(Phake::anyParameters());
    }
}
