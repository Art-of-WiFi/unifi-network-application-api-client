<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\AclRules;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Retrieves the current ordering of ACL rules for a specific site.
 */
class GetAclRuleOrderingRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $siteId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/sites/{$this->siteId}/acl-rules/ordering";
    }
}
