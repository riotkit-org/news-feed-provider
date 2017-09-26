<?php declare(strict_types = 1);

namespace AppBundle\ValueObject\Response;

use AppBundle\Collection\PaginatedFeedCollection;

class FeedListingResponse extends EntityListingResponse
{
    public function __construct(PaginatedFeedCollection $feedCollection)
    {
        parent::__construct(
            $feedCollection->toArray(),
            [],
            $feedCollection->getPagination()
        );
    }
}
