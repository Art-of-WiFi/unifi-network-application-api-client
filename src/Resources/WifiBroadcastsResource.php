<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Resources;

use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\WifiBroadcasts\GetWifiBroadcastsRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\WifiBroadcasts\GetWifiBroadcastDetailsRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\WifiBroadcasts\CreateWifiBroadcastRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\WifiBroadcasts\UpdateWifiBroadcastRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\WifiBroadcasts\DeleteWifiBroadcastRequest;
use Saloon\Http\Response;

/**
 * WiFi Broadcasts Resource
 *
 * Provides access to WiFi broadcast (SSID) management endpoints.
 * Allows creating, updating, or removing WiFi networks and configuring
 * security, band steering, multicast filtering, and captive portals.
 */
class WifiBroadcastsResource extends BaseResource
{
    /**
     * List all WiFi broadcasts
     *
     * Retrieves a paginated list of all WiFi broadcasts (SSIDs) on the specified site.
     *
     * @param int|null $page Page number (optional)
     * @param int|null $limit Number of results per page (optional)
     * @param string|null $filter Filter expression (optional)
     * @return Response
     */
    public function list(?int $page = null, ?int $limit = null, ?string $filter = null): Response
    {
        $siteId = $this->requireSiteId();
        return $this->connector->send(new GetWifiBroadcastsRequest($siteId, $page, $limit, $filter));
    }

    /**
     * Get WiFi broadcast details
     *
     * Retrieves detailed information about a specific WiFi broadcast.
     *
     * @param string $wifiBroadcastId The WiFi broadcast UUID
     * @return Response
     */
    public function get(string $wifiBroadcastId): Response
    {
        $siteId = $this->requireSiteId();
        return $this->connector->send(new GetWifiBroadcastDetailsRequest($siteId, $wifiBroadcastId));
    }

    /**
     * Create a new WiFi broadcast
     *
     * Creates a new WiFi broadcast (SSID) on the specified site.
     *
     * @param array $data The WiFi broadcast configuration data
     * @return Response
     */
    public function create(array $data): Response
    {
        $siteId = $this->requireSiteId();
        return $this->connector->send(new CreateWifiBroadcastRequest($siteId, $data));
    }

    /**
     * Update a WiFi broadcast
     *
     * Updates an existing WiFi broadcast configuration.
     *
     * @param string $wifiBroadcastId The WiFi broadcast UUID
     * @param array $data The updated WiFi broadcast configuration data
     * @return Response
     */
    public function update(string $wifiBroadcastId, array $data): Response
    {
        $siteId = $this->requireSiteId();
        return $this->connector->send(new UpdateWifiBroadcastRequest($siteId, $wifiBroadcastId, $data));
    }

    /**
     * Delete a WiFi broadcast
     *
     * Deletes an existing WiFi broadcast from the specified site.
     *
     * @param string $wifiBroadcastId The WiFi broadcast UUID
     * @param bool $force Force deletion (optional)
     * @return Response
     */
    public function delete(string $wifiBroadcastId, bool $force = false): Response
    {
        $siteId = $this->requireSiteId();
        return $this->connector->send(new DeleteWifiBroadcastRequest($siteId, $wifiBroadcastId, $force));
    }
}
