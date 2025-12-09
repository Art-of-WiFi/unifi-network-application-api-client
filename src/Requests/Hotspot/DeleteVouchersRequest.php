<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Hotspot;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteVouchersRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected string $siteId,
        protected ?string $filter = null
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/v1/sites/{$this->siteId}/hotspot/vouchers";
    }

    protected function defaultQuery(): array
    {
        return $this->filter !== null ? ['filter' => $this->filter] : [];
    }
}
