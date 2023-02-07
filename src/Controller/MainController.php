<?php

namespace App\Controller;

use App\Dto\ControllerRequest\BaseRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/api', name: 'api_cart_')]
class MainController extends AbstractController
{
    public function __construct()
    {
    }

    /**
     * Получить содержимое корзины
     *
     * @OA\Response(
     *      response=200,
     *      description="Тестовый контроллер",
     *      @Model(type=JsonResponse::class)
     * )
     * @OA\Tag(name="Cart")
     * @param BaseRequest $request
     * @return JsonResponse
     */
    #[Route('/test', name: 'test', methods: ['GET'])]
    public function test(BaseRequest $request): JsonResponse
    {
        return new JsonResponse(['test']);
    }
}
