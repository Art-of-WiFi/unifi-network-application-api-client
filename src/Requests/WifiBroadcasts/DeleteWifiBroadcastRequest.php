<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\WifiBroadcasts;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Deletes a WiFi broadcast from a specific site.
 */
class DeleteWifiBroadcastRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected string $siteId,
        protected string $wifiBroadcastId,
        protected bool $force = false
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/sites/{$this->siteId}/wifi/broadcasts/{$this->wifiBroadcastId}";
    }

    protected function defaultQuery(): array
    {
        return $this->force ? ['force' => 'true'] : [];
    }
}
