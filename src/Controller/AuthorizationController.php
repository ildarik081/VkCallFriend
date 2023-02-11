<?php

namespace App\Controller;

use App\Component\Requester\VkRequester;
use App\Dto\ControllerRequest\BaseRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use OpenApi\Annotations as OA;

#[Route('/api/authorization', name: 'api_authorization_')]
class AuthorizationController extends AbstractController
{
    public function __construct(private readonly VkRequester $vkRequester)
    {
    }

    /**
     * Получить ссылку для получения ключа доступа пользователя
     *
     * @OA\Tag(name="Authorization")
     * @param BaseRequest $request
     * @return JsonResponse
     */
    #[Route('/url', name: 'get_authorize_url', methods: ['GET'])]
    public function getAuthorizeUrl(BaseRequest $request): JsonResponse
    {
        return new JsonResponse(['url' => $this->vkRequester->getAuthorizeUrl($request)]);
    }
}
