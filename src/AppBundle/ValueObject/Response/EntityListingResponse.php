<?php declare(strict_types=1);

namespace AppBundle\ValueObject\Response;

use AppBundle\Entity\EntityInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class EntityListingResponse extends JsonResponse
{
    public function __construct(array $entities, array $headers = [])
    {
        $data = [
            'data' => array_map(
                function (EntityInterface $entity) {
                    return [
                        'type' => $entity->getPublicTypeName(),
                        'id' => $entity->getId(),
                        'attributes' => $entity,
                    ];
                },
                $entities
            ),
        ];

        parent::__construct($data, 200, $headers, false);
    }
}
