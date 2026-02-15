<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Clients;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Retrieves detailed information about a specific connected client.
 */
class GetClientDetailsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $siteId,
        protected string $clientId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/sites/{$this->siteId}/clients/{$this->clientId}";
    }
}
