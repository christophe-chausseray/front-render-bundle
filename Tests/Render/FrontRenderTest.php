<?php

namespace Chris\Bundle\FrontRenderBundle\Tests\Render;

use Chris\Bundle\FrontRenderBundle\Listener\TwigListener;
use Chris\Bundle\FrontRenderBundle\Render\FrontRender;
use InvalidArgumentException;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\TemplateNameParser;
use Twig_Error_Syntax;

class FrontRenderTest extends \PHPUnit_Framework_TestCase
{
    const TEMPLATE = 'index.html.twig';

    const FAILED_TEMPLATE = 'failed.html.twig';

    const FAILED_PATH = 'failedPath.html.twig';

    protected $engine;

    protected $eventDispatcher;

    protected $frontRender;

    protected $twigEnvironment;

    protected $twigListener;

    protected $templateNameParser;

    protected $fileLocator;

    protected $twigLoader;

    public function setUp()
    {
        parent::setUp();

        $this->eventDispatcher    = \Phake::mock(EventDispatcher::class);
        $this->twigLoader         = \Phake::partialMock(\Twig_Loader_Filesystem::class, [$_SERVER['KERNEL_DIR'] . 'Tests/Template']);
        $this->twigEnvironment    = \Phake::partialMock(\Twig_Environment::class, $this->twigLoader);
        $this->twigListener       = \Phake::partialMock(TwigListener::class, $this->twigEnvironment);

        $this->templateNameParser = \Phake::mock(TemplateNameParser::class);
        $this->fileLocator        = \Phake::mock(FileLocator::class);
        $this->engine             = \Phake::partialMock(TwigEngine::class, $this->twigEnvironment, $this->templateNameParser, $this->fileLocator);

        $lexer = $this->twigListener->getLexer();
        $this->twigEnvironment->setLexer($lexer);
    }

    public function testTemplateRenderWithParameter()
    {
        $this->frontRender = new FrontRender($this->engine, $this->eventDispatcher, self::TEMPLATE);

        $this->frontRender->setParameters(
            [
                'applicationName' => 'Test Render',
            ]
        );

        $response = new Response($this->frontRender->render());

        $this->assertContains(
            'Welcome on Test Render',
            $response->getContent()
        );
    }


    public function testTemplateRenderWithoutParameter()
    {
        $this->frontRender = new FrontRender($this->engine, $this->eventDispatcher, self::TEMPLATE);

        $response = new Response($this->frontRender->render());

        $this->assertNotContains(
            'Test Render',
            $response->getContent()
        );
    }

    /**
     * @dataProvider lexerTags
     */
    public function testSetLexer($oldTags, $newTags)
    {
        $this->frontRender = new FrontRender($this->engine, $this->eventDispatcher, self::TEMPLATE);

        $this->frontRender->setParameters(
            [
                'applicationName' => 'Test Render',
            ]
        );

        $response = new Response($this->frontRender->render());

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertContains(
            $oldTags,
            $response->getContent()
        );

        $this->assertNotContains(
            $newTags,
            $response->getContent()
        );
    }

    /**
     * @expectedException Twig_Error_Syntax
     */
    public function testExceptionOnSyntax()
    {
        $this->frontRender = new FrontRender($this->engine, $this->eventDispatcher, self::FAILED_TEMPLATE);

        $this->frontRender->render();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testExceptionOnFailedPath()
    {
        $this->frontRender = new FrontRender($this->engine, $this->eventDispatcher, self::FAILED_PATH);

        $this->frontRender->render();
    }

    /**
     * @expectedException Chris\Bundle\FrontRenderBundle\Exception\FrontRenderException
     */
    public function testExceptionOnEmptyPath()
    {
        $this->frontRender = new FrontRender($this->engine, $this->eventDispatcher, '');

        $this->frontRender->render();
    }

    public function lexerTags()
    {
        return array(
            array('{#', '{*'),
            array('{%', '{@'),
            array('{{', '{\$')
        );

    }


}
