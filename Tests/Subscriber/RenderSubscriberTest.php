<?php

namespace Chris\Bundle\FrontRenderBundle\Tests\Listener;

use Chris\Bundle\FrontRenderBundle\Subscriber\RenderSubscriber;
use Phake;
use Phake_IMock;
use PHPUnit_Framework_TestCase;
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
     * @var GetResponseEvent
     */
    protected $responseEvent;

    /**
     * @var GetResponseForExceptionEvent
     */
    protected $exceptionEvent;

    /**
     * @var RenderSubscriber
     */
    protected $renderSubscriber;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->twigLoader      = Phake::partialMock(Twig_Loader_Filesystem::class, [__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . self::TEMPLATE_PATH]);
        $this->twigEnvironment = Phake::partialMock(Twig_Environment::class, $this->twigLoader);
        $this->responseEvent   = Phake::mock(GetResponseEvent::class);
        $this->exceptionEvent  = Phake::mock(GetResponseForExceptionEvent::class);

        $this->renderSubscriber = new RenderSubscriber($this->twigEnvironment);
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
        $this->renderSubscriber->updateTagTwig($this->responseEvent);

        Phake::verify($this->twigEnvironment)->setLexer(Phake::anyParameters());
    }

    /**
     * Test the lexer update to have the default lexer for exception
     */
    public function testSetLexerForException()
    {
        $this->renderSubscriber->updateTagTwig($this->responseEvent);
        $this->renderSubscriber->onKernelException($this->exceptionEvent);

        Phake::verify($this->twigEnvironment, Phake::times(2))->setLexer(Phake::anyParameters());
    }
}
