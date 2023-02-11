<?php

namespace App\Controller;

use App\Component\Exception\RepositoryException;
use App\Component\Exception\ServiceException;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Dto\ControllerRequest\BaseRequest;
use App\Service\CallService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use OpenApi\Annotations as OA;

#[Route('/api/call', name: 'api_call_')]
class CallController extends AbstractController
{
    public function __construct(private readonly CallService $callService)
    {
    }

    /**
     * Запустить скрипт для созвона с друзьями
     *
     * @OA\Response(
     *      response=200,
     *      description="Возвращает статус создания события",
     *      @Model(type=JsonResponse::class)
     * )
     * @OA\Tag(name="Call")
     * @param BaseRequest $request
     * @return JsonResponse
     * @throws ServiceException
     * @throws RepositoryException
     */
    #[Route('/run', name: 'run', methods: ['POST'])]
    public function callFriends(BaseRequest $request): JsonResponse
    {
        return $this->callService->callFriends($request);
    }
}
