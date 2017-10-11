<?php declare(strict_types = 1);

namespace ImageRepositoryBundle\Service\Processor;

use AppBundle\AppEvents;
use AppBundle\Entity\FeedSource;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * @see FeedSource
 */
class FeedSourceProcessor extends AbstractProcessor implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            AppEvents::FEED_SOURCE_PRE_PERSIST => [
                'process',
                900,
            ],
        ];
    }

    /**
     * @param GenericEvent $event
     */
    public function process(GenericEvent $event)
    {
        if (!$this->isEnabled()) {
            return;
        }

        /**
         * @var FeedSource $source
         */
        $source = $event->getSubject();

        $source->setIcon($this->processText($source->getIcon()));
        $source->setDescription($this->processText($source->getDescription()));
    }
}
