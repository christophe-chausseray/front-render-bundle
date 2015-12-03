<?php

namespace Chris\Bundle\FrontRenderBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TwigListenerCompilerPass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!in_array('twig', $container->getParameter('templating.engines')) &&
            $container->hasDefinition('front_render_bundle.twig_listener')) {
            $container->removeDefinition('front_render_bundle.twig_listener');
        }
    }
}
