<?php declare(strict_types = 1);

namespace AppBundle\ValueObject;

class PaginationInformation
{
    protected $currentPage;
    protected $maxPages;
    protected $totalResults;
    protected $returnedResults;
    protected $currentPosition;

    public function __construct(array $paginationData)
    {
        $this->currentPage     = $paginationData['current_page'];
        $this->maxPages        = $paginationData['max_pages'];
        $this->totalResults    = $paginationData['total_results'];
        $this->returnedResults = $paginationData['returned_results'];
        $this->currentPosition = $paginationData['current_position'];
    }

    /**
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * @return int
     */
    public function getMaxPages(): int
    {
        return $this->maxPages;
    }

    /**
     * @return int
     */
    public function getTotalResults(): int
    {
        return $this->totalResults;
    }

    /**
     * @return int
     */
    public function getReturnedResults(): int
    {
        return $this->returnedResults;
    }

    /**
     * @return int
     */
    public function getCurrentPosition(): int
    {
        return $this->currentPosition;
    }
}