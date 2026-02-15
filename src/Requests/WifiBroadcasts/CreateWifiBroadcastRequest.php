<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\WifiBroadcasts;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Creates a new WiFi broadcast (SSID) on a specific site.
 */
class CreateWifiBroadcastRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $siteId,
        protected array $data
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/sites/{$this->siteId}/wifi/broadcasts";
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }
}
