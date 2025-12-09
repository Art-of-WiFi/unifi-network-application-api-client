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
     */
    public function delete(string $networkId, bool $force = false): Response
    {
        $siteId = $this->requireSiteId();
        return $this->connector->send(new DeleteNetworkRequest($siteId, $networkId, $force));
    }
}
