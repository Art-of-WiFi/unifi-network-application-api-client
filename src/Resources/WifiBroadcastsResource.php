<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Resources;

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Filter;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\WifiBroadcasts\CreateWifiBroadcastRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\WifiBroadcasts\DeleteWifiBroadcastRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\WifiBroadcasts\GetWifiBroadcastDetailsRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\WifiBroadcasts\GetWifiBroadcastsRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\WifiBroadcasts\UpdateWifiBroadcastRequest;
use RuntimeException;
use Saloon\Exceptions\Request\ClientException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Exceptions\Request\ServerException;
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
     * @param  int|null  $offset  Pagination offset (optional)
     * @param  int|null  $limit  Number of results per page (optional)
     * @param  string|Filter|null  $filter  Filter expression or Filter object (optional)
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (bad request, unauthorized, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function list(?int $offset = null, ?int $limit = null, string|Filter|null $filter = null): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new GetWifiBroadcastsRequest($siteId, $offset, $limit, $filter));
    }

    /**
     * Get WiFi broadcast details
     *
     * Retrieves detailed information about a specific WiFi broadcast.
     *
     * @param  string  $wifiBroadcastId  The WiFi broadcast UUID
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (not found, unauthorized, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
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
     * @param  array  $data  The WiFi broadcast configuration data
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (bad request, validation error, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
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
     * @param  string  $wifiBroadcastId  The WiFi broadcast UUID
     * @param  array  $data  The updated WiFi broadcast configuration data
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (not found, bad request, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
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
     * @param  string  $wifiBroadcastId  The WiFi broadcast UUID
     * @param  bool  $force  Force deletion (optional)
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (not found, conflict, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function delete(string $wifiBroadcastId, bool $force = false): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new DeleteWifiBroadcastRequest($siteId, $wifiBroadcastId, $force));
    }
}
