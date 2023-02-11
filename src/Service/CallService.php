<?php

namespace App\Service;

use App\Component\Exception\RepositoryException;
use App\Component\Exception\ServiceException;
use App\Repository\UserRepository;
use App\Component\Message\CallMessage;
use App\Dto\ControllerRequest\BaseRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;

class CallService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserService $userService,
        private readonly MessageBusInterface $bus
    ) {
    }

    /**
     * Созвон с друзьями
     *
     * @param BaseRequest $request
     * @return JsonResponse
     * @throws ServiceException
     * @throws RepositoryException
     */
    public function callFriends(BaseRequest $request): JsonResponse
    {
        $this->userService->updateFriends($request);
        $user = $this->userRepository->getUserBySessionId($request->session);

        $this->bus->dispatch(new CallMessage($user->getId()));

        return new JsonResponse(['status' => true]);
    }
}
