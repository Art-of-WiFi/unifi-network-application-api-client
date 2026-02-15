<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Resources;

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Filter;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Networks\CreateNetworkRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Networks\DeleteNetworkRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Networks\GetNetworkDetailsRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Networks\GetNetworkReferencesRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Networks\GetNetworksRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Networks\UpdateNetworkRequest;
use RuntimeException;
use Saloon\Exceptions\Request\ClientException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Exceptions\Request\ServerException;
use Saloon\Http\Response;

/**
 * Networks Resource
 *
 * Provides access to network management endpoints.
 * Allows creating, updating, deleting, and inspecting network configurations
 * including VLANs, DHCP, NAT, and IPv4/IPv6 settings.
 */
class NetworksResource extends BaseResource
{
    /**
     * List all networks
     *
     * Retrieves a paginated list of all networks on the specified site.
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

        return $this->connector->send(new GetNetworksRequest($siteId, $offset, $limit, $filter));
    }

    /**
     * Get network details
     *
     * Retrieves detailed information about a specific network.
     *
     * @param  string  $networkId  The network UUID
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (not found, unauthorized, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function get(string $networkId): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new GetNetworkDetailsRequest($siteId, $networkId));
    }

    /**
     * Get network references
     *
     * Retrieves references to a specific network, showing where it is used
     * across the UniFi configuration.
     *
     * @param  string  $networkId  The network UUID
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (not found, unauthorized, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function getReferences(string $networkId): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new GetNetworkReferencesRequest($siteId, $networkId));
    }

    /**
     * Create a new network
     *
     * Creates a new network configuration on the specified site.
     *
     * @param  array  $data  The network configuration data
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (bad request, validation error, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function create(array $data): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new CreateNetworkRequest($siteId, $data));
    }

    /**
     * Update a network
     *
     * Updates an existing network configuration.
     *
     * @param  string  $networkId  The network UUID
     * @param  array  $data  The updated network configuration data
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (not found, bad request, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function update(string $networkId, array $data): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new UpdateNetworkRequest($siteId, $networkId, $data));
    }

    /**
     * Delete a network
     *
     * Deletes an existing network from the specified site.
     *
     * @param  string  $networkId  The network UUID
     * @param  bool  $force  Force deletion (optional)
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (not found, conflict, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function delete(string $networkId, bool $force = false): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new DeleteNetworkRequest($siteId, $networkId, $force));
    }
}
