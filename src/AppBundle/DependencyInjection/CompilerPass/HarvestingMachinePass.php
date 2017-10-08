<?php declare(strict_types=1);

namespace AppBundle\DependencyInjection\CompilerPass;

use AppBundle\Manager\FeedManager;
use AppBundle\Service\HarvestingMachine;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class HarvestingMachinePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $collectors = [];

        foreach (array_keys($container->findTaggedServiceIds('nfp.harvesting.collector', true)) as $name) {
            $collectors[] = new Reference($name);
        }

        $machine = new Definition(HarvestingMachine::class);
        $machine->addArgument(new Reference(FeedManager::class));
        $machine->addArgument(new Reference('logger'));
        $machine->addMethodCall('setCollectors', [$collectors]);

        $container->setDefinition(HarvestingMachine::class, $machine);
    }
}
