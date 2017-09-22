<?php declare(strict_types=1);

namespace AppBundle\Form\Model;

interface SearchCriteriaInterface
{
    const HANDLER_MULTIPLE_VALUE = 'multiple-value';
    const HANDLER_CONTAINS_VALUE = 'contains-value';

    /**
     *   return [
     *       [
     *           'name' => 'enabled',
     *           'handler' => 'multiple-value',
     *       ],
     *       [
     *           'name' => 'description',
     *           'handler' => 'contains-value',
     *       ],
     *   ];
     *
     * @return array
     */
    public function getDefinition() : array;
}
