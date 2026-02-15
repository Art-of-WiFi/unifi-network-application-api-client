<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Devices;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Executes an action on a specific port of a device (e.g., power cycle PoE).
 */
class ExecutePortActionRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $siteId,
        protected string $deviceId,
        protected int $portIdx,
        protected array $action
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/sites/{$this->siteId}/devices/{$this->deviceId}/interfaces/ports/{$this->portIdx}/actions";
    }

    protected function defaultBody(): array
    {
        return $this->action;
    }
}
