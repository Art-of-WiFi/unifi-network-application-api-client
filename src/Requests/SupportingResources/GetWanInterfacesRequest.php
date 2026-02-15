<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\SupportingResources;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Retrieves a list of WAN interfaces for a specific site.
 */
class GetWanInterfacesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $siteId,
        protected ?int $offset = null,
        protected ?int $limit = null
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/sites/{$this->siteId}/wans";
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

        return $query;
    }
}
