<?php

namespace Chris\Bundle\FrontRenderBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class FrontRenderExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $frontPath = (array_key_exists('front_path', $config) ? $config['front_path'] : '');
        $container->setParameter('front_render_bundle.front_path', trim($frontPath));

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config/services'));
        $loader->load('render.xml');
        $loader->load('manager.xml');
    }
}
