<?php declare(strict_types=1);

namespace AppBundle\ValueObject\Response;

use AppBundle\Entity\EntityInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class EntityRemovedResponse extends JsonResponse
{
    public function __construct(EntityInterface $entity, array $headers = [])
    {
        $data = [
            'status_message' => 'Entity was removed',
            'data' => [
                'type' => $entity::getPublicTypeName(),
                'id' => $entity->getId(),
                'attributes' => $entity,
            ],
        ];

        $headers['X-Reason'] = $entity::getPublicTypeName() . ' was deleted';
        parent::__construct($data, 200, $headers, false);
    }
}
