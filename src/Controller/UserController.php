<?php

namespace App\Controller;

use App\Component\Exception\ServiceException;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Dto\ControllerRequest\BaseRequest;
use App\Entity\Friend;
use App\Entity\User;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use OpenApi\Annotations as OA;

#[Route('/api/user', name: 'api_user_')]
class UserController extends AbstractController
{
    public function __construct(private readonly UserService $userService)
    {
    }

    /**
     * Актуализировать список друзей пользователя
     *
     * @OA\Response(
     *      response=200,
     *      description="Возвращает статус обновления информации",
     *      @Model(type=JsonResponse::class)
     * )
     * @OA\Tag(name="User")
     * @param BaseRequest $request
     * @return JsonResponse
     * @throws ServiceException
     */
    #[Route('/update', name: 'update_friends', methods: ['POST'])]
    public function updateFriends(BaseRequest $request): JsonResponse
    {
        return $this->userService->updateFriends($request);
    }

    /**
     * Получить список друзей пользователя
     *
     * Подгружаются "неактуальный" список друзей пользователя (из БД)
     *
     * @OA\Response(
     *      response=200,
     *      description="Возвращает список друзей пользователя",
     *      @Model(type=User::class)
     * )
     * @OA\Tag(name="User")
     * @param BaseRequest $request
     * @return User
     */
    #[Route('/list', name: 'get_list', methods: ['GET'])]
    public function getList(BaseRequest $request): User
    {
        return $this->userService->getList($request);
    }

    /**
     * Получить информацию о друге по внешнему идентификатору
     *
     * @OA\Response(
     *      response=200,
     *      description="Возвращает информацию о друге",
     *      @Model(type=Friend::class)
     * )
     * @OA\Tag(name="User")
     * @param Friend $friend
     * @return Friend
     */
    #[Route('/friend/{externalId}', name: 'get_list', methods: ['GET'])]
    public function getFriend(Friend $friend): Friend
    {
        return $friend;
    }
}
