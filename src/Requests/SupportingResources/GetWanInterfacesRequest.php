<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\SupportingResources;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetWanInterfacesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $siteId,
        protected ?int $page = null,
        protected ?int $limit = null
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/v1/sites/{$this->siteId}/wans";
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

        return $query;
    }
}
