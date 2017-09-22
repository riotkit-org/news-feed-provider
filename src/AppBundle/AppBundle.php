<?php declare(strict_types=1);

namespace AppBundle;

use AppBundle\DependencyInjection\AppBundleExtension;
use AppBundle\DependencyInjection\CompilerPass\HarvestingMachinePass;
use AppBundle\DependencyInjection\CompilerPass\SourceDataPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new AppBundleExtension();
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new HarvestingMachinePass());
        $container->addCompilerPass(new SourceDataPass());
    }
}
