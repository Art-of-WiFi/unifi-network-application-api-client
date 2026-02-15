<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Sites;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Retrieves a paginated list of all sites managed by the UniFi Network Application.
 */
class GetSitesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected ?int $offset = null,
        protected ?int $limit = null,
        protected ?string $filter = null
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v1/sites';
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
            $query['filter'] = $this->filter;
        }

        return $query;
    }
}
