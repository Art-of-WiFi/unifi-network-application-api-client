<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Resources;

use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Networks\GetNetworksRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Networks\GetNetworkDetailsRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Networks\CreateNetworkRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Networks\UpdateNetworkRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Networks\DeleteNetworkRequest;
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
     * @param int|null $page Page number (optional)
     * @param int|null $limit Number of results per page (optional)
     * @param string|null $filter Filter expression (optional)
     * @return Response
     * @throws \RuntimeException If site ID is not set
     * @throws \Saloon\Exceptions\Request\ClientException If the request fails with a 4xx error (bad request, unauthorized, etc.)
     * @throws \Saloon\Exceptions\Request\ServerException If the request fails with a 5xx error (server error)
     * @throws \Saloon\Exceptions\Request\RequestException If the request fails due to network issues or timeout
     */
    public function list(?int $page = null, ?int $limit = null, ?string $filter = null): Response
    {
        $siteId = $this->requireSiteId();
        return $this->connector->send(new GetNetworksRequest($siteId, $page, $limit, $filter));
    }

    /**
     * Get network details
     *
     * Retrieves detailed information about a specific network.
     *
     * @param string $networkId The network UUID
     * @return Response
     * @throws \RuntimeException If site ID is not set
     * @throws \Saloon\Exceptions\Request\ClientException If the request fails with a 4xx error (not found, unauthorized, etc.)
     * @throws \Saloon\Exceptions\Request\ServerException If the request fails with a 5xx error (server error)
     * @throws \Saloon\Exceptions\Request\RequestException If the request fails due to network issues or timeout
     */
    public function get(string $networkId): Response
    {
        $siteId = $this->requireSiteId();
        return $this->connector->send(new GetNetworkDetailsRequest($siteId, $networkId));
    }

    /**
     * Create a new network
     *
     * Creates a new network configuration on the specified site.
     *
     * @param array $data The network configuration data
     * @return Response
     * @throws \RuntimeException If site ID is not set
     * @throws \Saloon\Exceptions\Request\ClientException If the request fails with a 4xx error (bad request, validation error, etc.)
     * @throws \Saloon\Exceptions\Request\ServerException If the request fails with a 5xx error (server error)
     * @throws \Saloon\Exceptions\Request\RequestException If the request fails due to network issues or timeout
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
     * @param string $networkId The network UUID
     * @param array $data The updated network configuration data
     * @return Response
     * @throws \RuntimeException If site ID is not set
     * @throws \Saloon\Exceptions\Request\ClientException If the request fails with a 4xx error (not found, bad request, etc.)
     * @throws \Saloon\Exceptions\Request\ServerException If the request fails with a 5xx error (server error)
     * @throws \Saloon\Exceptions\Request\RequestException If the request fails due to network issues or timeout
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
     * @param string $networkId The network UUID
     * @param bool $force Force deletion (optional)
     * @return Response
     * @throws \RuntimeException If site ID is not set
     * @throws \Saloon\Exceptions\Request\ClientException If the request fails with a 4xx error (not found, conflict, etc.)
     * @throws \Saloon\Exceptions\Request\ServerException If the request fails with a 5xx error (server error)
     * @throws \Saloon\Exceptions\Request\RequestException If the request fails due to network issues or timeout
     */
    public function delete(string $networkId, bool $force = false): Response
    {
        $siteId = $this->requireSiteId();
        return $this->connector->send(new DeleteNetworkRequest($siteId, $networkId, $force));
    }
}
