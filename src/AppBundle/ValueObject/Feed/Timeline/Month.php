<?php declare(strict_types = 1);

namespace AppBundle\ValueObject\Feed\Timeline;

/**
 * Month on a timeline
 */
class Month implements \JsonSerializable
{
    protected $month         = 0;
    protected $year          = 0;
    protected $entriesAmount = 0;

    public function __construct(int $year, int $month, int $amount = 0)
    {
        $this->year          = $year;
        $this->month         = $month;
        $this->entriesAmount = $amount;
    }

    public function increment()
    {
        $this->entriesAmount++;
    }

    public function jsonSerialize()
    {
        return [
            'year'           => $this->year,
            'month'          => $this->month,
            'entries_amount' => $this->entriesAmount,
        ];
    }
}
