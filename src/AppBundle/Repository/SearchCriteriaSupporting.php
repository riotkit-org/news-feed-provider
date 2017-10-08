<?php declare(strict_types=1);

namespace AppBundle\Repository;

use AppBundle\Exception\InvalidFieldNameException;
use AppBundle\Exception\LogicException;
use AppBundle\Form\Model\SearchCriteriaInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Extends a class by adding possibility to take user submitted "Search Criteria"
 * and translate to ORM conditions, apply using QueryBuilder
 */
trait SearchCriteriaSupporting
{
    /**
     * Applies conditions to the WHERE block basing on the search criteria
     * coming from eg. user
     *
     * @param SearchCriteriaInterface $criteria
     * @param QueryBuilder $qb
     * @param string $alias
     *
     * @throws LogicException
     */
    protected function applyCriteriaToQueryBuilder(SearchCriteriaInterface $criteria, QueryBuilder $qb, string $alias = 'q')
    {
        $alias = $alias ? $alias . '.' : '';

        foreach ($criteria->getDefinition() as $definition) {
            $handler = $definition['handler'] ?? '';

            if ($handler === SearchCriteriaInterface::HANDLER_MULTIPLE_VALUE) {
                $this->handleMultipleValue($definition, $criteria, $qb, $alias);
                continue;

            } elseif ($handler === SearchCriteriaInterface::HANDLER_CONTAINS_VALUE) {
                $this->handleContainsValue($definition, $criteria, $qb, $alias);
                continue;

            } elseif (in_array($handler, [SearchCriteriaInterface::HANDLER_DATE_RANGE_FROM, SearchCriteriaInterface::HANDLER_DATE_RANGE_TO], true)) {
                $this->handleDateValue($definition, $criteria, $qb, $alias);
                continue;

            } elseif ($handler === SearchCriteriaInterface::HANDLER_NONE) {
                continue;
            }

            throw new LogicException(get_class($criteria) . ' uses unknown handler');
        }
    }

    protected function handleDateValue(array $definition, SearchCriteriaInterface $criteria, QueryBuilder $qb, string $alias)
    {
        /** @var \DateTime $value */
        $value    = $this->getFieldValue($definition, $criteria);
        $operator = $definition['handler'] === SearchCriteriaInterface::HANDLER_DATE_RANGE_FROM ? ' > ' : ' < ';

        if ($this->isValueEmpty($value)) {
            return;
        }

        $qb->andWhere(
            $this->applyExclusion(
                $definition,
                $alias . $this->getFieldColumn($definition) . $operator . ' "' . $value->format('Y-m-d H:i:s') . '"'
            )
        );
    }

    protected function handleMultipleValue(array $definition, SearchCriteriaInterface $criteria, QueryBuilder $qb, string $alias)
    {
        $value = $this->getFieldValue($definition, $criteria);

        if ($this->isValueEmpty($value)) {
            return;
        }

        $bindParameterName = 'sc_' . $definition['name'];

        $qb->andWhere(
            $this->applyExclusion(
                $definition,
                $alias . $this->getFieldColumn($definition) . ' IN (:' . $bindParameterName . ')'
            )
        );
        $qb->setParameter($bindParameterName, $value);
    }

    protected function handleContainsValue(array $definition, SearchCriteriaInterface $criteria, QueryBuilder $qb, string $alias)
    {
        $value = $this->getFieldValue($definition, $criteria);

        if ($this->isValueEmpty($value)) {
            return;
        }

        $bindParameterName = 'sc_' . $definition['name'];

        $qb->andWhere(
            $this->applyExclusion(
                $definition,
                $alias . $this->getFieldColumn($definition) . ' LIKE :' . $bindParameterName
            )
        );
        $qb->setParameter($bindParameterName, '%' . $value . '%');
    }

    /**
     * Applies exclusion operator if was specified in definition
     *
     * @param array  $definition
     * @param string $wherePart
     * @return string
     */
    protected function applyExclusion(array $definition, string $wherePart)
    {
        if ($definition['except'] ?? false) {
            $wherePart = ' not (' . $wherePart . ')';
        }

        return $wherePart;
    }

    /**
     * Executes a getter on a Search Criteria object
     *
     * @param array $definition
     * @param SearchCriteriaInterface $criteria
     * @throws InvalidFieldNameException
     *
     * @return mixed
     */
    protected function getFieldValue(array $definition, SearchCriteriaInterface $criteria)
    {
        $fieldName = $definition['name'];

        $methodsStr = [];
        $methodNames = [
            'get' . ucfirst($fieldName),
            'is' . ucfirst($fieldName),
        ];

        foreach ($methodNames as $methodName) {
            if (method_exists($criteria, $methodName)) {
                return $criteria->$methodName();
            }

            $methodsStr[] = get_class($criteria) . '::' . $methodName . '()';
        }

        throw new InvalidFieldNameException('Expected at least one of methods ' . implode(', ', $methodsStr) . 'to be implemented as a class method');
    }

    protected function getFieldColumn(array $definition): string
    {
        return $definition['column'] ?? $definition['name'];
    }

    private function isValueEmpty($value)
    {
        return $value === null || empty($value);
    }
}
