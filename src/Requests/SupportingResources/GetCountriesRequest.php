<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\SupportingResources;

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Filter;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Retrieves a paginated list of available countries.
 */
class GetCountriesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected ?int $offset = null,
        protected ?int $limit = null,
        protected string|Filter|null $filter = null
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v1/countries';
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
