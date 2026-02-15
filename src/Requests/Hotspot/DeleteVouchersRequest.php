<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Hotspot;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Deletes multiple hotspot vouchers matching a required filter expression.
 */
class DeleteVouchersRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected string $siteId,
        protected string $filter
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/sites/{$this->siteId}/hotspot/vouchers";
    }

    protected function defaultQuery(): array
    {
        return ['filter' => $this->filter];
    }
}
