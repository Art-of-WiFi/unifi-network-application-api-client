<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Devices;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Retrieves performance statistics for a specific adopted device.
 */
class GetDeviceStatisticsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $siteId,
        protected string $deviceId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/sites/{$this->siteId}/devices/{$this->deviceId}/statistics/latest";
    }
}
