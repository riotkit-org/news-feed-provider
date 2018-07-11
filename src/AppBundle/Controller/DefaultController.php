<?php declare(strict_types=1);

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        return new JsonResponse(['message' => 'Hello'], 200);
    }

    public function notFoundAction(): void
    {
        throw new NotFoundHttpException();
    }
}
