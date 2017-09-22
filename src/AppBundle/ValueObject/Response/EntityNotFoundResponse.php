<?php declare(strict_types=1);

namespace AppBundle\ValueObject\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class EntityNotFoundResponse extends JsonResponse
{
    public function __construct(string $entityTypeName, string $identifier = null)
    {
        $data = [
            'errors' => [
                'status' => 404,
                'source' => $entityTypeName,
                'title' => 'Requested entity not found',
                'detail' => $identifier ? 'id: ' . $identifier . ', type: ' . $entityTypeName : '',
            ],
        ];

        parent::__construct($data, 404, ['X-Reason' => 'Dependent entity was not found'], false);
    }
}
