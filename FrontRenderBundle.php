<?php

namespace Chris\Bundle\FrontRenderBundle;

use Chris\Bundle\FrontRenderBundle\DependencyInjection\CompilerPass\RenderSubscriberCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class FrontRenderBundle extends Bundle
{
    /**
     * {@inheridoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new RenderSubscriberCompilerPass());
    }
}
