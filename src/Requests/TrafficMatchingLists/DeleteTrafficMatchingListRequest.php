<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\TrafficMatchingLists;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Deletes a traffic matching list from a specific site.
 */
class DeleteTrafficMatchingListRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected string $siteId,
        protected string $listId,
        protected bool $force = false
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/sites/{$this->siteId}/traffic-matching-lists/{$this->listId}";
    }

    protected function defaultQuery(): array
    {
        return $this->force ? ['force' => 'true'] : [];
    }
}
