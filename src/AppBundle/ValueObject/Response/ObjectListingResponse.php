<?php declare(strict_types=1);

namespace AppBundle\ValueObject\Response;

use AppBundle\Entity\EntityInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ObjectListingResponse extends JsonResponse
{
    /**
     * @param EntityInterface[] $objects
     * @param array $headers
     */
    public function __construct(array $objects, array $headers = [])
    {
        $data = [
            'data'      => $objects,
            'relations' => [],
        ];

        parent::__construct($data, 200, $headers, false);
    }
}
