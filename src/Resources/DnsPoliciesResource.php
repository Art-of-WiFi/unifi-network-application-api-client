<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Resources;

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Filter;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\DnsPolicies\CreateDnsPolicyRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\DnsPolicies\DeleteDnsPolicyRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\DnsPolicies\GetDnsPoliciesRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\DnsPolicies\GetDnsPolicyRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\DnsPolicies\UpdateDnsPolicyRequest;
use RuntimeException;
use Saloon\Exceptions\Request\ClientException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Exceptions\Request\ServerException;
use Saloon\Http\Response;

/**
 * DNS Policies Resource
 *
 * Provides access to DNS policy management endpoints.
 * Allows creating, listing, and managing DNS policies for DNS-based
 * content filtering and custom DNS records.
 */
class DnsPoliciesResource extends BaseResource
{
    /**
     * List all DNS policies
     *
     * Retrieves a paginated list of all DNS policies on the specified site.
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

        return $this->connector->send(new GetDnsPoliciesRequest($siteId, $offset, $limit, $filter));
    }

    /**
     * Get DNS policy details
     *
     * Retrieves detailed information about a specific DNS policy.
     *
     * @param  string  $dnsPolicyId  The DNS policy UUID
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (not found, unauthorized, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function get(string $dnsPolicyId): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new GetDnsPolicyRequest($siteId, $dnsPolicyId));
    }

    /**
     * Create a new DNS policy
     *
     * Creates a new DNS policy on the specified site.
     *
     * @param  array  $data  The DNS policy configuration data
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (bad request, validation error, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function create(array $data): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new CreateDnsPolicyRequest($siteId, $data));
    }

    /**
     * Update a DNS policy
     *
     * Updates an existing DNS policy configuration.
     *
     * @param  string  $dnsPolicyId  The DNS policy UUID
     * @param  array  $data  The updated DNS policy configuration data
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (not found, bad request, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function update(string $dnsPolicyId, array $data): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new UpdateDnsPolicyRequest($siteId, $dnsPolicyId, $data));
    }

    /**
     * Delete a DNS policy
     *
     * Deletes an existing DNS policy from the specified site.
     *
     * @param  string  $dnsPolicyId  The DNS policy UUID
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (not found, conflict, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function delete(string $dnsPolicyId): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new DeleteDnsPolicyRequest($siteId, $dnsPolicyId));
    }
}
