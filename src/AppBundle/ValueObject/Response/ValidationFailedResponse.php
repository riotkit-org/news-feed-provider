<?php declare(strict_types=1);

namespace AppBundle\ValueObject\Response;

use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\HttpFoundation\JsonResponse;

class ValidationFailedResponse extends JsonResponse
{
    public function __construct(FormErrorIterator $data, int $code = 400, array $headers = [])
    {
        $errors = [];

        foreach ($data as $error) {
            $errors[] = [
                'status' => 400,
                'source' => $error->getOrigin()->getName(),
                'title' => 'Field has invalid or missing value',
                'detail' => $error->getMessage(),
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
