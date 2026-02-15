<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\WifiBroadcasts;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Updates an existing WiFi broadcast configuration.
 */
class UpdateWifiBroadcastRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(
        protected string $siteId,
        protected string $wifiBroadcastId,
        protected array $data
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/sites/{$this->siteId}/wifi/broadcasts/{$this->wifiBroadcastId}";
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }
}
