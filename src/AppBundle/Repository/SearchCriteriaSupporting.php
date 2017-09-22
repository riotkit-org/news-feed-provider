<?php declare(strict_types=1);

namespace AppBundle\Repository;

use AppBundle\Exception\LogicException;
use AppBundle\Form\Model\SearchCriteriaInterface;
use Doctrine\DBAL\Query\QueryBuilder;

trait SearchCriteriaSupporting
{
    /**
     * Applies conditions to the WHERE block basing on the search criteria
     * coming from eg. user
     *
     * @param SearchCriteriaInterface $criteria
     * @param QueryBuilder $qb
     *
     * @throws LogicException
     */
    protected function applyCriteriaToQueryBuilder(SearchCriteriaInterface $criteria, QueryBuilder $qb)
    {
        foreach ($criteria->getDefinition() as $definition) {
            $handler = $definition['handler'] ?? '';

            if ($handler === SearchCriteriaInterface::HANDLER_MULTIPLE_VALUE) {
                $this->handleMultipleValue($definition, $criteria, $qb);
                continue;

            } elseif ($handler === SearchCriteriaInterface::HANDLER_CONTAINS_VALUE) {
                $this->handleContainsValue($definition, $criteria, $qb);
                continue;
            }

            throw new LogicException(get_class($criteria) . ' uses unknown handler');
        }
    }

    protected function handleMultipleValue(array $definition, SearchCriteriaInterface $criteria, QueryBuilder $qb)
    {
        $value = $this->getFieldValue($definition['name'], $criteria);
        $bindParameterName = 'sc_' . $definition['name'];

        $qb->andWhere($definition['name'] . ' IN (:' . $bindParameterName . ')');
        $qb->setParameter($bindParameterName, $value);
    }

    protected function handleContainsValue(array $definition, SearchCriteriaInterface $criteria, QueryBuilder $qb)
    {
        $value = $this->getFieldValue($definition['name'], $criteria);
        $bindParameterName = 'sc_' . $definition['name'];

        $qb->andWhere($definition['name'] . ' LIKE ' . $bindParameterName . ')');
        $qb->setParameter($bindParameterName, '%' . $value . '%');
    }

    /**
     * Executes a getter on a
     *
     * @param string $fieldName
     * @param SearchCriteriaInterface $criteria
     * @throws LogicException
     *
     * @return mixed
     */
    protected function getFieldValue(string $fieldName, SearchCriteriaInterface $criteria)
    {
        $methodName = 'get' . ucfirst($fieldName);

        if (!method_exists($criteria, $methodName)) {
            throw new LogicException('Expected method ' . get_class($criteria) . '::' . $methodName . '() to be implemented as a class method');
        }

        return $criteria->$methodName();
    }
}
