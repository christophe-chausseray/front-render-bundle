<?php

namespace Chris\Bundle\FrontRenderBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Twig_Environment;
use Twig_Lexer;

class ExceptionListener
{
    /**
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * ExceptionListener constructor.
     *
     * @param Twig_Environment $twig
     */
    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Set the default twig lexer to display default tags on exceptions
     *
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $lexer = $this->getExceptionLexer();
        $this->twig->setLexer($lexer);

        $event->stopPropagation();
    }

    /**
     * Retrieve the default twig lexer for exceptions
     *
     * @return Twig_Lexer
     */
    public function getExceptionLexer()
    {
        $lexer = new Twig_Lexer($this->twig, [
            'tag_comment'  => ['{#', '#}'],
            'tag_block'    => ['{%', '%}'],
            'tag_variable' => ['{{', '}}'],
        ]);

        return $lexer;
    }
}
