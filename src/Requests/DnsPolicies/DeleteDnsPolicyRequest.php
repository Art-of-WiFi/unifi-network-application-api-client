<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\DnsPolicies;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Deletes a DNS policy from a specific site.
 */
class DeleteDnsPolicyRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected string $siteId,
        protected string $dnsPolicyId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/sites/{$this->siteId}/dns/policies/{$this->dnsPolicyId}";
    }
}
