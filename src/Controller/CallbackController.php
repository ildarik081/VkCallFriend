<?php

namespace App\Controller;

use App\Component\Exception\ServiceException;
use App\Service\CallbackService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[Route('/vk', name: 'vk_')]
class CallbackController extends AbstractController
{
    public function __construct(private readonly CallbackService $callbackService)
    {
    }

    /**
     * Callback для VK
     *
     * @return Response
     */
    #[Route('/callback', name: 'callback', methods: ['GET'])]
    public function callback(): Response
    {
        return $this->render('callback.html.twig', []);
    }

    /**
     * Сохранение данных
     *
     * @param Request $request
     * @return Response
     * @throws ServiceException
     */
    #[Route('/save', name: 'save', methods: ['GET'])]
    public function saveUserData(Request $request): Response
    {
        return new JsonResponse($this->callbackService->saveUserData($request));
    }
}
