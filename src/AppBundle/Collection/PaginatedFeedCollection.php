<?php declare(strict_types = 1);

namespace AppBundle\Collection;

use AppBundle\ValueObject\PaginationInformation;

class PaginatedFeedCollection extends FeedCollection
{
    protected $pagination = [];

    public function __construct(FeedCollection $elements, PaginationInformation $paginationInformation)
    {
        parent::__construct($elements->toArray());
        $this->pagination = $paginationInformation;
    }

    public function getPagination(): PaginationInformation
    {
        return $this->pagination;
    }
}
