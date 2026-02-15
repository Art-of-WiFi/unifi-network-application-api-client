<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Firewall;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Retrieves the ordering of firewall policies between two firewall zones.
 */
class GetFirewallPolicyOrderingRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $siteId,
        protected string $sourceFirewallZoneId,
        protected string $destinationFirewallZoneId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/sites/{$this->siteId}/firewall/policies/ordering";
    }

    protected function defaultQuery(): array
    {
        return [
            'sourceFirewallZoneId' => $this->sourceFirewallZoneId,
            'destinationFirewallZoneId' => $this->destinationFirewallZoneId,
        ];
    }
}
