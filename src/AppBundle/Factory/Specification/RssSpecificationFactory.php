<?php declare(strict_types=1);

namespace AppBundle\Factory\Specification;

use AppBundle\Service\Collector\RssCollector;
use AppBundle\ValueObject\Specification\RssSourceSpecification;

class RssSpecificationFactory
{
    public function isAbleToHandle(string $collectorName) : bool
    {
        return $collectorName === RssCollector::getCollectorName();
    }

    public function create(array $data)
    {
        return new RssSourceSpecification($data['url']);
    }
}
