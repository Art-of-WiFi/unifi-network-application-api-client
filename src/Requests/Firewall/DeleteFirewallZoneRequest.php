<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Firewall;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Deletes a firewall zone from a specific site.
 */
class DeleteFirewallZoneRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected string $siteId,
        protected string $zoneId,
        protected bool $force = false
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/sites/{$this->siteId}/firewall/zones/{$this->zoneId}";
    }

    protected function defaultQuery(): array
    {
        return $this->force ? ['force' => 'true'] : [];
    }
}
