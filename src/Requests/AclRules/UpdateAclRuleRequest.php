<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\AclRules;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Updates an existing ACL rule configuration.
 */
class UpdateAclRuleRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(
        protected string $siteId,
        protected string $ruleId,
        protected array $data
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/sites/{$this->siteId}/acl-rules/{$this->ruleId}";
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }
}
