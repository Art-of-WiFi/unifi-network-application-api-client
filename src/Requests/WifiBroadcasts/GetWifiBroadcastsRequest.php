<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\WifiBroadcasts;

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Filter;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Retrieves a paginated list of WiFi broadcasts (SSIDs) for a specific site.
 */
class GetWifiBroadcastsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $siteId,
        protected ?int $offset = null,
        protected ?int $limit = null,
        protected string|Filter|null $filter = null
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/sites/{$this->siteId}/wifi/broadcasts";
    }

    protected function defaultQuery(): array
    {
        $query = [];

        if ($this->offset !== null) {
            $query['offset'] = $this->offset;
        }

        if ($this->limit !== null) {
            $query['limit'] = $this->limit;
        }

        if ($this->filter !== null) {
            $filterString = $this->filter instanceof Filter
                ? $this->filter->toString()
                : $this->filter;
            $query['filter'] = $filterString;
        }

        return $query;
    }
}
