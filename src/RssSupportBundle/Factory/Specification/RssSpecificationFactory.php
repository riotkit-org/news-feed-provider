<?php declare(strict_types=1);

namespace RssSupportBundle\Factory\Specification;

use RssSupportBundle\Service\Collector\RssCollector;
use RssSupportBundle\ValueObject\Specification\RssSourceSpecification;

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
