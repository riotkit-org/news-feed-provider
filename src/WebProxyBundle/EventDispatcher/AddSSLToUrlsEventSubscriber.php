<?php declare(strict_types=1);

namespace WebProxyBundle\EventDispatcher;

use AppBundle\AppEvents;
use AppBundle\ValueObject\Response\FeedListingResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use WebProxyBundle\Service\OneTimeViewUrlGenerator;

/**
 * Subscribes to Feed List controller to modify the response
 */
class AddSSLToUrlsEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var OneTimeViewUrlGenerator $urlGenerator
     */
    private $urlGenerator;

    /**
     * @var bool $enabled
     */
    private $enabled;

    /**
     * @param OneTimeViewUrlGenerator $urlGenerator
     * @param bool $enabled
     */
    public function __construct(OneTimeViewUrlGenerator $urlGenerator, bool $enabled)
    {
        $this->urlGenerator = $urlGenerator;
        $this->enabled = $enabled;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            AppEvents::FEED_LIST_POST_PROCESS => 'onFeedListResponseEmitted'
        ];
    }

    /**
     * Modifies the response
     *
     * @param GenericEvent $event
     */
    public function onFeedListResponseEmitted(GenericEvent $event)
    {
        if (!$this->enabled) {
            return;
        }
        
        /**
         * @var FeedListingResponse|JsonResponse $response
         */
        $response = $event->getSubject();

        $decoded = json_decode($response->getContent(), true);

        foreach ($decoded['data'] as $objectId => $object) {
            $sourceUrl = $object['attributes']['source_url'];

            if (strtolower(parse_url($sourceUrl, PHP_URL_SCHEME)) === 'https') {
                continue;
            }

            $decoded['data'][$objectId]['attributes']['source_url_ssl'] = $this->urlGenerator->generate($sourceUrl);
        }

        $response->setJson(json_encode($decoded));
    }
}
