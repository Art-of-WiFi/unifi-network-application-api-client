<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Hotspot;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Deletes a specific hotspot voucher.
 */
class DeleteVoucherRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected string $siteId,
        protected string $voucherId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/sites/{$this->siteId}/hotspot/vouchers/{$this->voucherId}";
    }
}
