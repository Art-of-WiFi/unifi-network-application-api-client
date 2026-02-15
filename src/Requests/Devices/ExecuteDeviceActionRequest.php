<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Devices;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Executes an action on a specific device (e.g., restart, locate, upgrade firmware).
 */
class ExecuteDeviceActionRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $siteId,
        protected string $deviceId,
        protected array $action
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/sites/{$this->siteId}/devices/{$this->deviceId}/actions";
    }

    protected function defaultBody(): array
    {
        return $this->action;
    }
}
