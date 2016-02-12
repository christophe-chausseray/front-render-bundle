<?php

namespace Chris\Bundle\FrontRenderBundle\Twig;

use Twig_Environment;
use Twig_Lexer;

class LexerManager
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
     * LexerManager constructor.
     *
     * @param Twig_Environment $twig
     */
    public function __construct(Twig_Environment $twig)
    {
        $this->defaultLexer = $twig->getLexer();
        $this->twig         = $twig;
    }

    /**
     * Get DefaultLexer
     *
     * @return Twig_Lexer
     */
    public function getDefaultLexer()
    {
        return $this->defaultLexer;
    }

    /**
     * Set the custom twig lexer to display custom tags on the front
     */
    public function updateLexer()
    {
        $this->twig->setLexer($this->getNewLexer());
    }

    /**
     * Rollback the lexer of twig
     */
    public function rollbackLexer()
    {
        $this->twig->setLexer($this->getDefaultLexer());
    }

    /**
     * Retrieve the custom twig lexer for the front
     *
     * @return Twig_Lexer
     */
    public function getNewLexer()
    {
        $lexer = new Twig_Lexer($this->twig, [
            'tag_comment'  => ['{*', '*}'],
            'tag_block'    => ['{@', '@}'],
            'tag_variable' => ['{$', '$}'],
        ]);

        return $lexer;
    }
}
