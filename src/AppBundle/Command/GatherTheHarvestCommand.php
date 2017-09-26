<?php declare(strict_types=1);

namespace AppBundle\Command;

use AppBundle\Repository\FeedSourceRepository;
use AppBundle\Service\HarvestingMachine;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\{
    ContainerAwareInterface, ContainerInterface
};

/**
 * Collects data from data sources
 */
class GatherTheHarvestCommand extends Command implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface $container
     */
    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function configure()
    {
        $this
            ->setName('collector:harvest:gather')
            ->setDescription('Gather the harvest from data sources')
            ->setHelp('This command allows to start data collection from various sources eg. RSS channels, social media');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $sources = $this->getRepository()->findSourcesToCollect();
        $this->getMachine()->collect($sources, $output);
    }

    protected function getMachine() : HarvestingMachine
    {
        return $this->container->get(HarvestingMachine::class);
    }

    protected function getRepository() : FeedSourceRepository
    {
        return $this->container->get(FeedSourceRepository::class);
    }
}
