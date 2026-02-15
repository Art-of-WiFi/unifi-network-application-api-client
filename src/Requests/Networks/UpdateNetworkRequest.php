<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Networks;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Updates an existing network configuration.
 */
class UpdateNetworkRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(
        protected string $siteId,
        protected string $networkId,
        protected array $data
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/sites/{$this->siteId}/networks/{$this->networkId}";
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }
}
