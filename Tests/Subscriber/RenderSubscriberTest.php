<?php

namespace Chris\Bundle\FrontRenderBundle\Tests\Listener;

use Chris\Bundle\FrontRenderBundle\Subscriber\RenderSubscriber;
use Chris\Bundle\FrontRenderBundle\Twig\LexerManager;
use Phake;
use Phake_IMock;
use PHPUnit_Framework_TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Twig_Environment;
use Twig_Loader_Filesystem;

class ExceptionListenerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var string TEMPLATE_PATH
     */
    const TEMPLATE_PATH = 'Template';

    /**
     * @var Twig_Loader_Filesystem
     */
    protected $twigLoader;

    /**
     * @var Twig_Environment|Phake_IMock
     */
    protected $twigEnvironment;

    /**
     * @var GetResponseEvent|Phake_IMock
     */
    protected $getResponseEvent;

    /**
     * @var FilterResponseEvent|Phake_IMock
     */
    protected $filterResponseEvent;

    /**
     * @var GetResponseForExceptionEvent|Phake_IMock
     */
    protected $getResponseForExceptionEvent;

    /**
     * @var RenderSubscriber|Phake_IMock
     */
    protected $renderSubscriber;

    /**
     * @var LexerManager|Phake_IMock
     */
    protected $lexerManager;

    /**
     * @var Request|Phake_IMock
     */
    protected $request;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->twigLoader                   = Phake::partialMock(Twig_Loader_Filesystem::class, [__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . self::TEMPLATE_PATH]);
        $this->twigEnvironment              = Phake::partialMock(Twig_Environment::class, $this->twigLoader);
        $this->lexerManager                 = Phake::partialMock(LexerManager::class, $this->twigEnvironment);
        $this->getResponseEvent             = Phake::mock(GetResponseEvent::class);
        $this->filterResponseEvent          = Phake::mock(FilterResponseEvent::class);
        $this->getResponseForExceptionEvent = Phake::mock(GetResponseForExceptionEvent::class);
        $this->request                      = Phake::mock(Request::class);

        $this->renderSubscriber = new RenderSubscriber($this->lexerManager);
    }

    /**
     * Test we have register events subscribed
     */
    public function testGetSubscriberEvent()
    {
        $eventsSubscribed = RenderSubscriber::getSubscribedEvents();

        $this->assertInternalType('array', $eventsSubscribed);
    }

    /**
     * Test the lexer update to have a custom lexer for the front
     */
    public function testSetLexerForTheFront()
    {
        Phake::when($this->getResponseEvent)->getRequest()->thenReturn($this->request);
        Phake::when($this->getResponseEvent)->isMasterRequest()->thenReturn(true);
        Phake::when($this->request)->isXmlHttpRequest()->thenReturn(false);
        $this->renderSubscriber->updateTwigLexer($this->getResponseEvent);

        Phake::verify($this->lexerManager)->updateLexer(Phake::anyParameters());
    }

    /**
     * Test the lexer update to have the default lexer for exception
     */
    public function testSetLexerForResponse()
    {
        $this->renderSubscriber->updateTwigLexer($this->getResponseEvent);
        $this->renderSubscriber->rollbackTwigLexer($this->filterResponseEvent);

        Phake::verify($this->lexerManager)->rollbackLexer(Phake::anyParameters());
    }

    /**
     * Test the lexer update to have the default lexer for exception
     */
    public function testSetLexerForException()
    {
        $this->renderSubscriber->updateTwigLexer($this->getResponseEvent);
        $this->renderSubscriber->rollbackTwigLexerForException($this->getResponseForExceptionEvent);

        Phake::verify($this->lexerManager)->rollbackLexer(Phake::anyParameters());
    }
}
