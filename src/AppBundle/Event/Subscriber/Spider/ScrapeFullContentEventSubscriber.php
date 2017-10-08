<?php declare(strict_types = 1);

namespace AppBundle\Event\Subscriber\Spider;

use AppBundle\AppEvents;
use AppBundle\Entity\FeedEntry;
use AppBundle\Service\Spider\WebSpider;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Fills up the full article/news content before persist
 */
class ScrapeFullContentEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var WebSpider $spider
     */
    protected $spider;

    public function __construct(WebSpider $spider)
    {
        $this->spider = $spider;
    }

    public static function getSubscribedEvents()
    {
        return [
            AppEvents::FEED_PRE_PERSIST => 'onFeedPrePersist',
        ];
    }

    /**
     * @see AppEvents::FEED_PRE_PERSIST
     * @param GenericEvent $event
     */
    public function onFeedPrePersist(GenericEvent $event)
    {
        /**
         * @var FeedEntry $feed
         */
        $feed = $event->getSubject();

        if (!$feed->hasFullContent()) {
            $spider = $this->spider->open($feed->getSourceUrl());

            $feed->setFullContent(
                $spider->findPageContent(
                    $feed->getFeedSource()->getScrapingSpecification()
                )
            );
        }
    }
}
