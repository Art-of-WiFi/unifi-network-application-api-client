<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Resources;

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Filter;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Firewall\CreateFirewallPolicyRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Firewall\CreateFirewallZoneRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Firewall\DeleteFirewallPolicyRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Firewall\DeleteFirewallZoneRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Firewall\GetFirewallPoliciesRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Firewall\GetFirewallPolicyOrderingRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Firewall\GetFirewallPolicyRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Firewall\GetFirewallZoneRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Firewall\GetFirewallZonesRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Firewall\PatchFirewallPolicyRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Firewall\UpdateFirewallPolicyOrderingRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Firewall\UpdateFirewallPolicyRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Firewall\UpdateFirewallZoneRequest;
use RuntimeException;
use Saloon\Exceptions\Request\ClientException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Exceptions\Request\ServerException;
use Saloon\Http\Response;

/**
 * Firewall Resource
 *
 * Provides access to firewall zone and policy management endpoints.
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
     * @param  int|null  $offset  Pagination offset (optional)
     * @param  int|null  $limit  Number of results per page (optional)
     * @param  string|Filter|null  $filter  Filter expression or Filter object (optional)
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (bad request, unauthorized, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function listZones(?int $offset = null, ?int $limit = null, string|Filter|null $filter = null): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new GetFirewallZonesRequest($siteId, $offset, $limit, $filter));
    }

    /**
     * Get firewall zone details
     *
     * Retrieves detailed information about a specific firewall zone.
     *
     * @param  string  $zoneId  The firewall zone UUID
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (not found, unauthorized, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
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
     * @param  array  $data  The firewall zone configuration data
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (bad request, validation error, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
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
     * @param  string  $zoneId  The firewall zone UUID
     * @param  array  $data  The updated firewall zone configuration data
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (not found, bad request, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
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
     * @param  string  $zoneId  The firewall zone UUID
     * @param  bool  $force  Force deletion (optional)
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (not found, conflict, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function deleteZone(string $zoneId, bool $force = false): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new DeleteFirewallZoneRequest($siteId, $zoneId, $force));
    }

    // ===== Firewall Policies =====

    /**
     * List all firewall policies
     *
     * Retrieves a paginated list of all firewall policies on the specified site.
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
    public function listPolicies(?int $offset = null, ?int $limit = null, string|Filter|null $filter = null): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new GetFirewallPoliciesRequest($siteId, $offset, $limit, $filter));
    }

    /**
     * Get firewall policy details
     *
     * Retrieves detailed information about a specific firewall policy.
     *
     * @param  string  $firewallPolicyId  The firewall policy UUID
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (not found, unauthorized, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function getPolicy(string $firewallPolicyId): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new GetFirewallPolicyRequest($siteId, $firewallPolicyId));
    }

    /**
     * Create a new firewall policy
     *
     * Creates a new firewall policy on the specified site.
     *
     * @param  array  $data  The firewall policy configuration data
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (bad request, validation error, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function createPolicy(array $data): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new CreateFirewallPolicyRequest($siteId, $data));
    }

    /**
     * Update a firewall policy
     *
     * Fully updates an existing firewall policy configuration (PUT).
     *
     * @param  string  $firewallPolicyId  The firewall policy UUID
     * @param  array  $data  The complete updated firewall policy configuration data
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (not found, bad request, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function updatePolicy(string $firewallPolicyId, array $data): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new UpdateFirewallPolicyRequest($siteId, $firewallPolicyId, $data));
    }

    /**
     * Partially update a firewall policy
     *
     * Partially updates an existing firewall policy configuration (PATCH).
     * Only the fields provided in the data array will be updated.
     *
     * @param  string  $firewallPolicyId  The firewall policy UUID
     * @param  array  $data  The partial firewall policy configuration data to update
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (not found, bad request, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function patchPolicy(string $firewallPolicyId, array $data): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new PatchFirewallPolicyRequest($siteId, $firewallPolicyId, $data));
    }

    /**
     * Delete a firewall policy
     *
     * Deletes an existing firewall policy from the specified site.
     *
     * @param  string  $firewallPolicyId  The firewall policy UUID
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (not found, conflict, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function deletePolicy(string $firewallPolicyId): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new DeleteFirewallPolicyRequest($siteId, $firewallPolicyId));
    }

    /**
     * Get firewall policy ordering
     *
     * Retrieves the ordering of firewall policies between two zones.
     *
     * @param  string  $sourceFirewallZoneId  The source firewall zone UUID
     * @param  string  $destinationFirewallZoneId  The destination firewall zone UUID
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (bad request, unauthorized, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function getPolicyOrdering(string $sourceFirewallZoneId, string $destinationFirewallZoneId): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new GetFirewallPolicyOrderingRequest($siteId, $sourceFirewallZoneId, $destinationFirewallZoneId));
    }

    /**
     * Update firewall policy ordering
     *
     * Updates the ordering of firewall policies between two zones.
     *
     * @param  string  $sourceFirewallZoneId  The source firewall zone UUID
     * @param  string  $destinationFirewallZoneId  The destination firewall zone UUID
     * @param  array  $data  The ordering data (e.g., ['orderedFirewallPolicyIds' => [...]])
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (bad request, unauthorized, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function updatePolicyOrdering(string $sourceFirewallZoneId, string $destinationFirewallZoneId, array $data): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new UpdateFirewallPolicyOrderingRequest($siteId, $sourceFirewallZoneId, $destinationFirewallZoneId, $data));
    }
}
