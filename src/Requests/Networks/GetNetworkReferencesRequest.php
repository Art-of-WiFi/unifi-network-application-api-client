<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Networks;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get Network References
 *
 * Retrieves references to a specific network, showing where it is used
 * across the UniFi configuration.
 */
class GetNetworkReferencesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $siteId,
        protected string $networkId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/sites/{$this->siteId}/networks/{$this->networkId}/references";
    }
}
