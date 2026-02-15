<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Firewall;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Deletes a firewall policy from a specific site.
 */
class DeleteFirewallPolicyRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected string $siteId,
        protected string $firewallPolicyId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/sites/{$this->siteId}/firewall/policies/{$this->firewallPolicyId}";
    }
}
