<?php

namespace Chris\Bundle\FrontRenderBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class TwigListener
{
    /**
     * @var \Twig_Environment $twig
     */
    protected $twig;

    /**
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $lexer = new \Twig_Lexer($this->twig, [
            'tag_comment'  => ['<%#', '%>'],
            'tag_block'    => ['<%', '%>'],
            'tag_variable' => ['<%=', '%>'],
        ]);
        $this->twig->setLexer($lexer);
    }
}
