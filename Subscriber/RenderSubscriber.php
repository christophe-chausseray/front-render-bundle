<?php

namespace Chris\Bundle\FrontRenderBundle\Subscriber;

use Chris\Bundle\FrontRenderBundle\Twig\LexerManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class RenderSubscriber implements EventSubscriberInterface
{
    /**
     * @var LexerManager
     */
    protected $twigLexerManager;

    /**
     * @var bool
     */
    protected $stopPropagation = false;

    /**
     * RenderSubscriber constructor.
     *
     * @param LexerManager $twigLexerManager
     */
    public function __construct(LexerManager $twigLexerManager)
    {
        $this->twigLexerManager = $twigLexerManager;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST   => ['updateTwigLexer'],
            KernelEvents::RESPONSE  => ['rollbackTwigLexer'],
        ];
    }

    /**
     * Set the custom twig lexer to display custom tags on the front
     *
     * @param GetResponseEvent $event
     */
    public function updateTwigLexer(GetResponseEvent $event)
    {
        if (false === $this->stopPropagation && $event->isMasterRequest() && !$event->getRequest()->isXmlHttpRequest()) {
            $this->twigLexerManager->updateLexer();
            $this->stopPropagation = true;
        }
    }

    /**
     * Set the custom twig lexer to display custom tags on the front
     *
     * @param FilterResponseEvent $event
     */
    public function rollbackTwigLexer(FilterResponseEvent $event)
    {
        $this->twigLexerManager->rollbackLexer();
        $this->stopPropagation = true;
    }
}
