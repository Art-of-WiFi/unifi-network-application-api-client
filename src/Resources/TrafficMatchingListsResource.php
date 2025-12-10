<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Resources;

use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\TrafficMatchingLists\GetTrafficMatchingListsRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\TrafficMatchingLists\GetTrafficMatchingListRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\TrafficMatchingLists\CreateTrafficMatchingListRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\TrafficMatchingLists\UpdateTrafficMatchingListRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\TrafficMatchingLists\DeleteTrafficMatchingListRequest;
use RuntimeException;
use Saloon\Exceptions\Request\ClientException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Exceptions\Request\ServerException;
use Saloon\Http\Response;

/**
 * Traffic Matching Lists Resource
 *
 * Provides access to traffic matching list management endpoints.
 * Allows managing port and IP address lists used across firewall policy configurations.
 */
class TrafficMatchingListsResource extends BaseResource
{
    /**
     * List all traffic matching lists
     *
     * Retrieves a paginated list of all traffic matching lists on the specified site.
     *
     * @param int|null $page Page number (optional)
     * @param int|null $limit Number of results per page (optional)
     * @param string|null $filter Filter expression (optional)
     * @return Response
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (bad request, unauthorized, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function list(?int $page = null, ?int $limit = null, ?string $filter = null): Response
    {
        $siteId = $this->requireSiteId();
        return $this->connector->send(new GetTrafficMatchingListsRequest($siteId, $page, $limit, $filter));
    }

    /**
     * Get traffic matching list details
     *
     * Retrieves detailed information about a specific traffic matching list.
     *
     * @param string $listId The traffic matching list UUID
     * @return Response
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (not found, unauthorized, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function get(string $listId): Response
    {
        $siteId = $this->requireSiteId();
        return $this->connector->send(new GetTrafficMatchingListRequest($siteId, $listId));
    }

    /**
     * Create a new traffic matching list
     *
     * Creates a new traffic matching list on the specified site.
     *
     * @param array $data The traffic matching list configuration data
     * @return Response
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (bad request, validation error, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function create(array $data): Response
    {
        $siteId = $this->requireSiteId();
        return $this->connector->send(new CreateTrafficMatchingListRequest($siteId, $data));
    }

    /**
     * Update a traffic matching list
     *
     * Updates an existing traffic matching list configuration.
     *
     * @param string $listId The traffic matching list UUID
     * @param array $data The updated traffic matching list configuration data
     * @return Response
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (not found, bad request, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function update(string $listId, array $data): Response
    {
        $siteId = $this->requireSiteId();
        return $this->connector->send(new UpdateTrafficMatchingListRequest($siteId, $listId, $data));
    }

    /**
     * Delete a traffic matching list
     *
     * Deletes an existing traffic matching list from the specified site.
     *
     * @param string $listId The traffic matching list UUID
     * @param bool $force Force deletion (optional)
     * @return Response
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (not found, conflict, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function delete(string $listId, bool $force = false): Response
    {
        $siteId = $this->requireSiteId();
        return $this->connector->send(new DeleteTrafficMatchingListRequest($siteId, $listId, $force));
    }
}
