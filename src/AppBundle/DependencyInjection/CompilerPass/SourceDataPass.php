<?php declare(strict_types=1);

namespace AppBundle\DependencyInjection\CompilerPass;

use AppBundle\Service\Form\FeedSourceDataFormHandler;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class SourceDataPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $taggedForms = [];

        foreach (array_keys($container->findTaggedServiceIds('nfp.forms.feedsource.sourceData', true)) as $name) {
            $taggedForms[] = new Reference($name);
        }

        $machine = new Definition(FeedSourceDataFormHandler::class);
        $machine->addArgument($taggedForms);

        $container->setDefinition(FeedSourceDataFormHandler::class, $machine);
    }
}
