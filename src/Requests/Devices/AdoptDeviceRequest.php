<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Devices;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Initiates adoption of a pending device into a specific site.
 */
class AdoptDeviceRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $siteId,
        protected string $macAddress,
        protected bool $ignoreDeviceLimit = false
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/sites/{$this->siteId}/devices";
    }

    protected function defaultBody(): array
    {
        return [
            'macAddress' => $this->macAddress,
            'ignoreDeviceLimit' => $this->ignoreDeviceLimit,
        ];
    }
}
