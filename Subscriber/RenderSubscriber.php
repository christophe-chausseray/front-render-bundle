<?php

namespace Chris\Bundle\FrontRenderBundle\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Twig_Environment;
use Twig_Lexer;

class RenderSubscriber implements EventSubscriberInterface
{
    /**
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * @var Twig_Lexer
     */
    protected $defaultLexer;

    /**
     * RenderSubscriber constructor.
     *
     * @param Twig_Environment $twig
     */
    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            ['kernel.request' => 'updateTagTwig'],
            ['kernel.exception' => 'onKernelException'],
        ];
    }

    /**
     * Set the custom twig lexer to display custom tags on the front
     *
     * @param GetResponseEvent $event
     */
    public function updateTagTwig(GetResponseEvent $event)
    {
        $this->defaultLexer = $this->twig->getLexer();

        $lexer = $this->getFrontLexer();
        $this->twig->setLexer($lexer);
    }

    /**
     * Set the default twig lexer to display default tags on exceptions
     *
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $this->twig->setLexer($this->defaultLexer);

        $event->stopPropagation();
    }

    /**
     * Retrieve the custom twig lexer for the front
     *
     * @return Twig_Lexer
     */
    public function getFrontLexer()
    {
        $lexer = new Twig_Lexer($this->twig, [
            'tag_comment'  => ['{*', '*}'],
            'tag_block'    => ['{@', '@}'],
            'tag_variable' => ['{$', '$}'],
        ]);

        return $lexer;
    }
}
