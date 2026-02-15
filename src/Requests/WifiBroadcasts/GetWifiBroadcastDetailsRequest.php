<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\WifiBroadcasts;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Retrieves detailed information about a specific WiFi broadcast.
 */
class GetWifiBroadcastDetailsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $siteId,
        protected string $wifiBroadcastId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/sites/{$this->siteId}/wifi/broadcasts/{$this->wifiBroadcastId}";
    }
}
