<?php declare(strict_types=1);

namespace AppBundle\ValueObject\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class FailureResponse extends JsonResponse
{
    public function __construct(array $plainErrors, int $code = 400, array $headers = [])
    {
        $errors = [];

        foreach ($plainErrors as $fieldName => $errorTitle) {
            $errors[] = [
                'status' => $code,
                'source' => $fieldName,
                'title' => $errorTitle,
            ];
        }

        $headers['X-Reason'] = 'Validation failed';
        parent::__construct(
            [
                'errors' => $errors
            ],
            $code,
            $headers,
            false
        );
    }
}
