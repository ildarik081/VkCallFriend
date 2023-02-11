<?php

namespace App\Component\Requester;

use App\Component\Interface\AbstractDtoControllerRequest;
use App\Component\Utils\Aliases;
use VK\Client\VKApiClient;
use VK\OAuth\Scopes\VKOAuthUserScope;
use VK\OAuth\VKOAuth;
use VK\OAuth\VKOAuthDisplay;
use VK\OAuth\VKOAuthResponseType;

class VkRequester
{
    public function __construct(
        private readonly VKOAuth $vkOAuth,
        private readonly VKApiClient $vkApiClient,
        private readonly int $vkAppId
    ) {
    }

    /**
     * Получить ссылку для предоставления доступа
     *
     * @param AbstractDtoControllerRequest $request
     * @return string
     */
    public function getAuthorizeUrl(AbstractDtoControllerRequest $request): string
    {
        return $this
            ->vkOAuth
            ->getAuthorizeUrl(
                response_type: VKOAuthResponseType::TOKEN,
                client_id: $this->vkAppId,
                redirect_uri: Aliases::VK_REDIRECT_URL,
                display: VKOAuthDisplay::PAGE,
                scope: [
                    VKOAuthUserScope::FRIENDS,
                    VKOAuthUserScope::OFFLINE
                ],
                state: $request->session
            )
        ;
    }

    /**
     * Получить экземпляр клиента
     *
     * @return VKApiClient
     */
    public function getClient(): VKApiClient
    {
        return $this->vkApiClient;
    }
}
