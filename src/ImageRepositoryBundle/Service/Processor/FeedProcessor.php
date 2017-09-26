<?php declare(strict_types = 1);

namespace ImageRepositoryBundle\Service\Processor;

use AppBundle\AppEvents;
use AppBundle\Entity\FeedEntry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * @see FeedEntry
 */
class FeedProcessor extends AbstractProcessor implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            AppEvents::FEED_PRE_PERSIST => [
                'process',
                900,
            ]
        ];
    }

    public function process(GenericEvent $event)
    {
        /**
         * @var FeedEntry $feed
         */
        $feed = $event->getSubject();

        $feed->setContent($this->processText($feed->getContent()));
        $feed->setIcon($this->processText($feed->getIcon()));
    }
}
