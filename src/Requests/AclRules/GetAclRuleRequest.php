<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\AclRules;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetAclRuleRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $siteId,
        protected string $ruleId
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/v1/sites/{$this->siteId}/acl/rules/{$this->ruleId}";
    }
}
