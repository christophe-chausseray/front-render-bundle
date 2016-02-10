<?php

namespace Chris\Bundle\FrontRenderBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Twig_Environment;
use Twig_Lexer;

class TwigListener
{
    /**
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * @param Twig_Environment $twig
     */
    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Set the custom twig lexer to display custom tags on the front
     *
     * Update twig tags
     */
    public function updateTagTwig(GetResponseEvent $event)
    {
        $lexer = $this->getFrontLexer();
        $this->twig->setLexer($lexer);
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
