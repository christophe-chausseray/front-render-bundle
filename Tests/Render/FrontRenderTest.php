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
    /**
     * @var string TEMPLATE_PATH
     */
    const TEMPLATE_PATH = 'Tests/Template';

    /**
     * @var string TEMPLATE
     */
    const TEMPLATE = 'index.html.twig';

    /**
     * @var string FAILED_TEMPLATE
     */
    const FAILED_TEMPLATE = 'failed.html.twig';

    /**
     * @var string FAILED_PATH
     */
    const FAILED_PATH = 'failedPath.html.twig';

    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * @var \Twig_Loader_Filesystem
     */
    protected $twigLoader;

    /**
     * @var \Twig_Environment
     */
    protected $twigEnvironment;

    /**
     * @var TwigListener
     */
    protected $twigListener;

    /**
     * @var TemplateNameParser
     */
    protected $templateNameParser;

    /**
     * @var FileLocator
     */
    protected $fileLocator;

    /**
     * @var TwigEngine
     */
    protected $engine;

    /**
     * @var FrontRender
     */
    protected $frontRender;

    /**
     * Set up the front render test
     */
    public function setUp()
    {
        parent::setUp();

        $this->eventDispatcher = \Phake::mock(EventDispatcher::class);
        $this->twigLoader      = \Phake::partialMock(\Twig_Loader_Filesystem::class, [$_SERVER['KERNEL_DIR'] . self::TEMPLATE_PATH]);
        $this->twigEnvironment = \Phake::partialMock(\Twig_Environment::class, $this->twigLoader);
        $this->twigListener    = \Phake::partialMock(TwigListener::class, $this->twigEnvironment);

        $this->templateNameParser = \Phake::mock(TemplateNameParser::class);
        $this->fileLocator        = \Phake::mock(FileLocator::class);
        $this->engine             = \Phake::partialMock(TwigEngine::class, $this->twigEnvironment, $this->templateNameParser, $this->fileLocator);

        $lexer = $this->twigListener->getLexer();
        $this->twigEnvironment->setLexer($lexer);
    }

    /**
     * Test render of the template with parameter
     */
    public function testTemplateRenderWithParameter()
    {
        $this->frontRender = new FrontRender($this->engine, $this->eventDispatcher, self::TEMPLATE);

        $this->frontRender->setParameters(
            [
                'applicationName' => 'Test Render',
            ]
        );

        $this->assertContains(
            'Welcome on Test Render',
            $this->frontRender->render()
        );
    }

    /**
     * Test render of the template without parameter
     */
    public function testTemplateRenderWithoutParameter()
    {
        $this->frontRender = new FrontRender($this->engine, $this->eventDispatcher, self::TEMPLATE);

        $this->assertNotContains(
            'Test Render',
            $this->frontRender->render()
        );
    }

    /**
     * Test on the update of lexer
     *
     * @dataProvider lexerTags
     *
     * @param string $oldTags
     * @param string $newTags
     */
    public function testSetLexer($oldTags, $newTags)
    {
        $this->frontRender = new FrontRender($this->engine, $this->eventDispatcher, self::TEMPLATE);

        $this->frontRender->setParameters(
            [
                'applicationName' => 'Test Render',
            ]
        );

        $render = $this->frontRender->render();

        $this->assertContains(
            $oldTags,
            $render
        );

        $this->assertNotContains(
            $newTags,
            $render
        );
    }

    /**
     * Test on the failed template
     *
     * @expectedException Twig_Error_Syntax
     */
    public function testExceptionOnSyntax()
    {
        $this->frontRender = new FrontRender($this->engine, $this->eventDispatcher, self::FAILED_TEMPLATE);

        $this->frontRender->render();
    }

    /**
     * Test when the template doesn't exist
     *
     * @expectedException InvalidArgumentException
     */
    public function testExceptionOnFailedPath()
    {
        $this->frontRender = new FrontRender($this->engine, $this->eventDispatcher, self::FAILED_PATH);

        $this->frontRender->render();
    }

    /**
     * Test when the template path is empty
     *
     * @expectedException Chris\Bundle\FrontRenderBundle\Exception\FrontRenderException
     */
    public function testExceptionOnEmptyPath()
    {
        $this->frontRender = new FrontRender($this->engine, $this->eventDispatcher, '');

        $this->frontRender->render();
    }

    /**
     * Provide the lexer tags
     *
     * @return array
     */
    public function lexerTags()
    {
        return [
            ['{#', '{*'],
            ['{%', '{@'],
            ['{{', '{$']
        ];

    }
}
