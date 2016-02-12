<?php

namespace Chris\Bundle\FrontRenderBundle\DependencyInjection\CompilerPass;

use Chris\Bundle\FrontRenderBundle\Subscriber\WebDebugToolbarListener;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class DebugToolbarCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->has('web_profiler.debug_toolbar')) {
            $container->setParameter('web_profiler.debug_toolbar.class', WebDebugToolbarListener::class);
            $debugToolbarDefinition = $container->getDefinition('web_profiler.debug_toolbar');
            $debugToolbarDefinition->addArgument(new Reference('front_render_bundle.twig_lexer_manager'));
        }
    }
}
