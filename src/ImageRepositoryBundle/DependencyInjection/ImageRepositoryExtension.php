<?php declare(strict_types=1);

namespace ImageRepositoryBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class ImageRepositoryExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $finder = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources'));
        $finder->load('services.yml');
    }
}
