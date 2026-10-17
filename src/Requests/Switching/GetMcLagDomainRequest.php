<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Switching;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Retrieves detailed information about a specific MC-LAG domain.
 */
class GetMcLagDomainRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $siteId,
        protected string $mcLagDomainId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/sites/{$this->siteId}/switching/mc-lag-domains/{$this->mcLagDomainId}";
    }
}
