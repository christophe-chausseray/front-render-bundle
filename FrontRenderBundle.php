<?php

namespace Chris\Bundle\FrontRenderBundle;

use Chris\Bundle\FrontRenderBundle\DependencyInjection\CompilerPass\TwigListenerCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class FrontRenderBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new TwigListenerCompilerPass());
    }
}
