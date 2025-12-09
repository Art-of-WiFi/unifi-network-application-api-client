<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\SupportingResources;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetDpiApplicationsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected ?int $page = null,
        protected ?int $limit = null,
        protected ?string $filter = null
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/v1/dpi/applications';
    }

    protected function defaultQuery(): array
    {
        $query = [];

        if ($this->page !== null) {
            $query['page'] = $this->page;
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
