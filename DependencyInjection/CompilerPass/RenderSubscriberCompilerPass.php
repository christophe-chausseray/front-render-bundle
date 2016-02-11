<?php

namespace Chris\Bundle\FrontRenderBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RenderSubscriberCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!in_array('twig', $container->getParameter('templating.engines')) &&
            $container->hasDefinition('front_render_bundle.render_subscriber')) {
            $container->removeDefinition('front_render_bundle.render_subscriber');
        }
    }
}
