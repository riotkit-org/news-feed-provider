<?php declare(strict_types=1);

namespace AppBundle\Event\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;

/**
 * Converts exceptions to API responses
 */
class ApiResponsesSubscriber implements EventSubscriberInterface
{
    /**
     * @var bool
     */
    private $isDebugging;

    public function __construct(bool $isDebugging)
    {
        $this->isDebugging = $isDebugging;
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.exception' => 'onKernelException'
        ];
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if (!$this->isJsonExpectedAsAResponseFormat($event->getRequest())) {
            return;
        }

        $exception = $event->getException();

        switch (true) {
            case $exception instanceof HttpException: {
                $event->setResponse($this->createHttpExceptionResponse($exception));
                return;
            }

            case $exception instanceof InsufficientAuthenticationException: {
                $event->setResponse($this->createHttpAccessDeniedResponse($exception));
                return;
            }

            default: {
                // unexpected exceptions
                $event->setResponse($this->createUnexpectedExceptionResponse($exception));
            }
        }
    }

    private function createHttpExceptionResponse(HttpException $exception): JsonResponse
    {
        return new JsonResponse(
            array_merge(
                [
                    'type'    => 'error',
                    'message' => $exception->getMessage()
                ],
                $this->getDevFields($exception)
            ),
            $exception->getStatusCode()
        );
    }

    private function createUnexpectedExceptionResponse(\Throwable $exception): JsonResponse
    {
        return new JsonResponse(
            array_merge(
                [
                    'type' => 'error',
                    'message' => 'Internal server error'
                ],
                $this->getDevFields($exception)
            )
        );
    }

    private function createHttpAccessDeniedResponse(InsufficientAuthenticationException $exception): JsonResponse
    {
        return new JsonResponse(
            array_merge(
                [
                    'type' => 'error',
                    'message' => 'Authentication is required to access this resource'
                ],
                $this->getDevFields($exception)
            ),
            Response::HTTP_FORBIDDEN
        );
    }

    private function getDevFields(\Throwable $exception): array
    {
        if ($this->isDebugging) {
            return [
                'dev' => [
                    'message' => $exception->getMessage(),
                    'type'    => \get_class($exception),
                    'code'    => $exception->getCode(),
                    'trace'   => $exception->getTrace()
                ]
            ];
        }

        return [];
    }

    private function isJsonExpectedAsAResponseFormat(Request $request): bool
    {
        return \in_array('application/json', $request->getAcceptableContentTypes(), true);
    }
}
