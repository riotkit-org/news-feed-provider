<?php declare(strict_types=1);

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction(): Response
    {
        return new JsonResponse(['message' => 'Hello'], 200);
    }

    public function notFoundAction(): void
    {
        throw $this->createNotFoundException();
    }
}
