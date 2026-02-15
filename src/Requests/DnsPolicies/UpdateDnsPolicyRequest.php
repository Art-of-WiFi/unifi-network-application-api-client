<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\DnsPolicies;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Updates an existing DNS policy configuration.
 */
class UpdateDnsPolicyRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(
        protected string $siteId,
        protected string $dnsPolicyId,
        protected array $data
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/sites/{$this->siteId}/dns/policies/{$this->dnsPolicyId}";
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }
}
