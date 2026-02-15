<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Clients;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Executes an action on a specific client (e.g., block, unblock, reconnect).
 */
class ExecuteClientActionRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $siteId,
        protected string $clientId,
        protected array $action
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/sites/{$this->siteId}/clients/{$this->clientId}/actions";
    }

    protected function defaultBody(): array
    {
        return $this->action;
    }
}
