<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\AclRules;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Deletes an ACL rule from a specific site.
 */
class DeleteAclRuleRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected string $siteId,
        protected string $ruleId,
        protected bool $force = false
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/sites/{$this->siteId}/acl-rules/{$this->ruleId}";
    }

    protected function defaultQuery(): array
    {
        return $this->force ? ['force' => 'true'] : [];
    }
}
