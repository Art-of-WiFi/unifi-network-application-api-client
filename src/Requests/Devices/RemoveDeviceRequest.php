<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Devices;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Removes (unadopts) a device from a specific site.
 */
class RemoveDeviceRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected string $siteId,
        protected string $deviceId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/sites/{$this->siteId}/devices/{$this->deviceId}";
    }
}
