<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Networks;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteNetworkRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected string $siteId,
        protected string $networkId,
        protected bool $force = false
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/v1/sites/{$this->siteId}/networks/{$this->networkId}";
    }

    protected function defaultQuery(): array
    {
        return $this->force ? ['force' => 'true'] : [];
    }
}
