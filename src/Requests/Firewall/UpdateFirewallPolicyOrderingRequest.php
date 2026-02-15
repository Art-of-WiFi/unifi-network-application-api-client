<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Firewall;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Updates the ordering of firewall policies between two firewall zones.
 */
class UpdateFirewallPolicyOrderingRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(
        protected string $siteId,
        protected string $sourceFirewallZoneId,
        protected string $destinationFirewallZoneId,
        protected array $data
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

    protected function defaultBody(): array
    {
        return $this->data;
    }
}
