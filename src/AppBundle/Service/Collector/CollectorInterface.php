<?php declare(strict_types=1);

namespace AppBundle\Service\Collector;

use AppBundle\Collection\FeedCollection;
use AppBundle\Entity\FeedSource;

interface CollectorInterface
{
    public function collect(FeedSource $source) : FeedCollection;
    public function isAbleToHandle(FeedSource $source) : bool;
    public static function getCollectorName() : string;
    public function __toString() : string;
}
