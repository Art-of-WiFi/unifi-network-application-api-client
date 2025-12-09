<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\TrafficMatchingLists;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetTrafficMatchingListRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $siteId,
        protected string $listId
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/v1/sites/{$this->siteId}/traffic-matching-lists/{$this->listId}";
    }
}
