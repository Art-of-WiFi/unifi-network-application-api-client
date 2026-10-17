<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Resources;

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Filter;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Switching\GetLagRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Switching\GetLagsRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Switching\GetMcLagDomainRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Switching\GetMcLagDomainsRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Switching\GetSwitchStackRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Switching\GetSwitchStacksRequest;
use RuntimeException;
use Saloon\Exceptions\Request\ClientException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Exceptions\Request\ServerException;
use Saloon\Http\Response;

/**
 * Switching Resource
 *
 * Provides access to switching feature endpoints including switch stacks,
 * MC-LAG (Multi-Chassis Link Aggregation Group) domains, and LAGs
 * (Link Aggregation Groups).
 *
 * All endpoints in this resource are read-only.
 */
class SwitchingResource extends BaseResource
{
    // ===== Switch Stacks =====

    /**
     * List all switch stacks
     *
     * Retrieves a paginated list of all switch stacks on the specified site.
     *
     * @param  int|null  $offset  Pagination offset (optional)
     * @param  int|null  $limit  Number of results per page (optional, max 200)
     * @param  string|Filter|null  $filter  Filter expression or Filter object (optional)
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error and throw-on-errors is enabled
     * @throws ServerException If the request fails with a 5xx error and throw-on-errors is enabled
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function listSwitchStacks(?int $offset = null, ?int $limit = null, string|Filter|null $filter = null): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new GetSwitchStacksRequest($siteId, $offset, $limit, $filter));
    }

    /**
     * Get switch stack details
     *
     * Retrieves detailed information about a specific switch stack.
     *
     * @param  string  $switchStackId  The switch stack UUID
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error and throw-on-errors is enabled
     * @throws ServerException If the request fails with a 5xx error and throw-on-errors is enabled
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function getSwitchStack(string $switchStackId): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new GetSwitchStackRequest($siteId, $switchStackId));
    }

    // ===== MC-LAG Domains =====

    /**
     * List all MC-LAG domains
     *
     * Retrieves a paginated list of all MC-LAG (Multi-Chassis Link Aggregation Group)
     * domains on the specified site.
     *
     * @param  int|null  $offset  Pagination offset (optional)
     * @param  int|null  $limit  Number of results per page (optional, max 200)
     * @param  string|Filter|null  $filter  Filter expression or Filter object (optional)
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error and throw-on-errors is enabled
     * @throws ServerException If the request fails with a 5xx error and throw-on-errors is enabled
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function listMcLagDomains(?int $offset = null, ?int $limit = null, string|Filter|null $filter = null): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new GetMcLagDomainsRequest($siteId, $offset, $limit, $filter));
    }

    /**
     * Get MC-LAG domain details
     *
     * Retrieves detailed information about a specific MC-LAG domain.
     *
     * @param  string  $mcLagDomainId  The MC-LAG domain UUID
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error and throw-on-errors is enabled
     * @throws ServerException If the request fails with a 5xx error and throw-on-errors is enabled
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function getMcLagDomain(string $mcLagDomainId): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new GetMcLagDomainRequest($siteId, $mcLagDomainId));
    }

    // ===== LAGs =====

    /**
     * List all LAGs
     *
     * Retrieves a paginated list of all LAGs (Link Aggregation Groups) on the specified site.
     * LAG responses are polymorphic — each item has a `type` field that is
     * `LOCAL`, `SWITCH_STACK` or `MULTI_CHASSIS`.
     *
     * @param  int|null  $offset  Pagination offset (optional)
     * @param  int|null  $limit  Number of results per page (optional, max 200)
     * @param  string|Filter|null  $filter  Filter expression or Filter object (optional)
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error and throw-on-errors is enabled
     * @throws ServerException If the request fails with a 5xx error and throw-on-errors is enabled
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function listLags(?int $offset = null, ?int $limit = null, string|Filter|null $filter = null): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new GetLagsRequest($siteId, $offset, $limit, $filter));
    }

    /**
     * Get LAG details
     *
     * Retrieves detailed information about a specific LAG. The response is polymorphic —
     * the `type` field indicates whether it is a `LOCAL`, `SWITCH_STACK` or `MULTI_CHASSIS` LAG.
     *
     * @param  string  $lagId  The LAG UUID
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error and throw-on-errors is enabled
     * @throws ServerException If the request fails with a 5xx error and throw-on-errors is enabled
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function getLag(string $lagId): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new GetLagRequest($siteId, $lagId));
    }
}
