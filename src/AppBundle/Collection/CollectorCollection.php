<?php declare(strict_types=1);

namespace AppBundle\Collection;

use AppBundle\Service\Collector\CollectorInterface;
use Doctrine\Common\Collections\ArrayCollection;
use \TypeError;

/**
 * @method CollectorInterface[] toArray()
 */
class CollectorCollection extends ArrayCollection
{
    private $indexByName = [];

    public function __construct(array $elements)
    {
        array_walk($elements, function (CollectorInterface $element) {
            $this->indexByName[$element->getCollectorName()] = $element;
        });

        parent::__construct($elements);
    }

    /**
     * @param mixed $value
     *
     * @throws TypeError
     * @return bool
     */
    public function add($value)
    {
        if (!$value instanceof CollectorInterface) {
            throw new TypeError('$value should be a CollectorInterface type');
        }

        return parent::add($value);
    }

    public function findByName(string $collectorName) : CollectorInterface
    {
        if (isset($this->indexByName[$collectorName])) {
            return $this->indexByName[$collectorName];
        }

        throw new \LogicException('Cannot find collector "' . $collectorName . '"');
    }
}
