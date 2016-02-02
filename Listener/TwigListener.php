<?php

namespace Chris\Bundle\FrontRenderBundle\Listener;

use Twig_Environment;
use Twig_Lexer;

class TwigListener
{
    /**
     * @var Twig_Environment $twig
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
     * Update twig tags
     */
    public function updateTagTwig()
    {
        $lexer = $this->getLexer();
        $this->twig->setLexer($lexer);
    }

    /**
     * @return Twig_Lexer
     */
    public function getLexer()
    {
        $lexer = new Twig_Lexer($this->twig, [
            'tag_comment'  => ['{*', '*}'],
            'tag_block'    => ['{@', '@}'],
            'tag_variable' => ['{$', '$}'],
        ]);

        return $lexer;
    }
}
