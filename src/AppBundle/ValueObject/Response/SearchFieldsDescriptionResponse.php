<?php declare(strict_types=1);

namespace AppBundle\ValueObject\Response;

use AppBundle\Entity\EntityInterface;
use AppBundle\Form\Model\SearchCriteriaInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class SearchFieldsDescriptionResponse extends JsonResponse
{
    public function __construct(SearchCriteriaInterface $emptySearchCriteria, array $headers = [])
    {
        $data = [
            'data' => $emptySearchCriteria->getDefinition(),
            'data_comment' => 'List of available fields to use in a search endpoint with value types its taking',
        ];

        parent::__construct($data, 200, $headers, false);
    }
}
