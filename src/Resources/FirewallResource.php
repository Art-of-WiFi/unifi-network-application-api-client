<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Resources;

use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Firewall\GetFirewallZonesRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Firewall\GetFirewallZoneRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Firewall\CreateFirewallZoneRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Firewall\UpdateFirewallZoneRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Firewall\DeleteFirewallZoneRequest;
use Saloon\Http\Response;

/**
 * Firewall Resource
 *
 * Provides access to firewall zone management endpoints.
 * Allows managing custom firewall zones and policies to define network
 * segmentation and security boundaries.
 */
class FirewallResource extends BaseResource
{
    /**
     * List all firewall zones
     *
     * Retrieves a paginated list of all firewall zones on the specified site.
     *
     * @param int|null $page Page number (optional)
     * @param int|null $limit Number of results per page (optional)
     * @param string|null $filter Filter expression (optional)
     * @return Response
     */
    public function listZones(?int $page = null, ?int $limit = null, ?string $filter = null): Response
    {
        $siteId = $this->requireSiteId();
        return $this->connector->send(new GetFirewallZonesRequest($siteId, $page, $limit, $filter));
    }

    /**
     * Get firewall zone details
     *
     * Retrieves detailed information about a specific firewall zone.
     *
     * @param string $zoneId The firewall zone UUID
     * @return Response
     */
    public function getZone(string $zoneId): Response
    {
        $siteId = $this->requireSiteId();
        return $this->connector->send(new GetFirewallZoneRequest($siteId, $zoneId));
    }

    /**
     * Create a new firewall zone
     *
     * Creates a new firewall zone on the specified site.
     *
     * @param array $data The firewall zone configuration data
     * @return Response
     */
    public function createZone(array $data): Response
    {
        $siteId = $this->requireSiteId();
        return $this->connector->send(new CreateFirewallZoneRequest($siteId, $data));
    }

    /**
     * Update a firewall zone
     *
     * Updates an existing firewall zone configuration.
     *
     * @param string $zoneId The firewall zone UUID
     * @param array $data The updated firewall zone configuration data
     * @return Response
     */
    public function updateZone(string $zoneId, array $data): Response
    {
        $siteId = $this->requireSiteId();
        return $this->connector->send(new UpdateFirewallZoneRequest($siteId, $zoneId, $data));
    }

    /**
     * Delete a firewall zone
     *
     * Deletes an existing firewall zone from the specified site.
     *
     * @param string $zoneId The firewall zone UUID
     * @param bool $force Force deletion (optional)
     * @return Response
     */
    public function deleteZone(string $zoneId, bool $force = false): Response
    {
        $siteId = $this->requireSiteId();
        return $this->connector->send(new DeleteFirewallZoneRequest($siteId, $zoneId, $force));
    }
}
