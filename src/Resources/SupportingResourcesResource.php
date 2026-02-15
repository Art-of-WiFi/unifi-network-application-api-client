<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Resources;

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Filter;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\SupportingResources\GetCountriesRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\SupportingResources\GetDeviceTagsRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\SupportingResources\GetDpiApplicationsRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\SupportingResources\GetDpiCategoriesRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\SupportingResources\GetRadiusProfilesRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\SupportingResources\GetSiteToSiteVpnTunnelsRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\SupportingResources\GetVpnServersRequest;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\SupportingResources\GetWanInterfacesRequest;
use RuntimeException;
use Saloon\Exceptions\Request\ClientException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Exceptions\Request\ServerException;
use Saloon\Http\Response;

/**
 * Supporting Resources Resource
 *
 * Provides access to read-only reference endpoints used to retrieve supporting data
 * such as WAN interfaces, DPI categories, country codes, RADIUS profiles, and device tags.
 */
class SupportingResourcesResource extends BaseResource
{
    /**
     * List WAN interfaces
     *
     * Returns available WAN interface definitions for a given site,
     * including identifiers and names. Useful for network and NAT configuration.
     *
     * @param  int|null  $offset  Pagination offset (optional)
     * @param  int|null  $limit  Number of results per page (optional)
     *
     * @throws RuntimeException If site ID is not set
     * @throws ClientException If the request fails with a 4xx error (bad request, unauthorized, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function listWanInterfaces(?int $offset = null, ?int $limit = null): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new GetWanInterfacesRequest($siteId, $offset, $limit));
    }

    /**
     * List site-to-site VPN tunnels
     *
     * Retrieves a paginated list of all site-to-site VPN tunnels on a site.
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
    public function listSiteToSiteVpnTunnels(?int $offset = null, ?int $limit = null, string|Filter|null $filter = null): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new GetSiteToSiteVpnTunnelsRequest($siteId, $offset, $limit, $filter));
    }

    /**
     * List VPN servers
     *
     * Retrieves a paginated list of all VPN servers on a site.
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
    public function listVpnServers(?int $offset = null, ?int $limit = null, string|Filter|null $filter = null): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new GetVpnServersRequest($siteId, $offset, $limit, $filter));
    }

    /**
     * List RADIUS profiles
     *
     * Returns available RADIUS authentication profiles, including configuration origin metadata.
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
    public function listRadiusProfiles(?int $offset = null, ?int $limit = null, string|Filter|null $filter = null): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new GetRadiusProfilesRequest($siteId, $offset, $limit, $filter));
    }

    /**
     * List device tags
     *
     * Retrieves a paginated list of all device tags on a site.
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
    public function listDeviceTags(?int $offset = null, ?int $limit = null, string|Filter|null $filter = null): Response
    {
        $siteId = $this->requireSiteId();

        return $this->connector->send(new GetDeviceTagsRequest($siteId, $offset, $limit, $filter));
    }

    /**
     * List DPI categories
     *
     * Returns available Deep Packet Inspection (DPI) categories.
     * These categories are used for traffic classification and firewall rules.
     *
     * Note: This endpoint does not require a site ID.
     *
     * @param  int|null  $offset  Pagination offset (optional, default: 0)
     * @param  int|null  $limit  Number of results per page (optional)
     * @param  string|Filter|null  $filter  Filter expression or Filter object (optional)
     *
     * @throws ClientException If the request fails with a 4xx error (bad request, unauthorized, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function listDpiCategories(?int $offset = null, ?int $limit = null, string|Filter|null $filter = null): Response
    {
        return $this->connector->send(new GetDpiCategoriesRequest($offset, $limit, $filter));
    }

    /**
     * List DPI applications
     *
     * Returns available Deep Packet Inspection (DPI) application signatures.
     * These applications are used for traffic identification and policy enforcement.
     *
     * Note: This endpoint does not require a site ID.
     *
     * @param  int|null  $offset  Pagination offset (optional, default: 0)
     * @param  int|null  $limit  Number of results per page (optional)
     * @param  string|Filter|null  $filter  Filter expression or Filter object (optional)
     *
     * @throws ClientException If the request fails with a 4xx error (bad request, unauthorized, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function listDpiApplications(?int $offset = null, ?int $limit = null, string|Filter|null $filter = null): Response
    {
        return $this->connector->send(new GetDpiApplicationsRequest($offset, $limit, $filter));
    }

    /**
     * List countries
     *
     * Returns ISO-standard country codes and names,
     * used for region-based configuration or regulatory compliance.
     *
     * Note: This endpoint does not require a site ID.
     *
     * @param  int|null  $offset  Pagination offset (optional, default: 0)
     * @param  int|null  $limit  Number of results per page (optional)
     * @param  string|Filter|null  $filter  Filter expression or Filter object (optional)
     *
     * @throws ClientException If the request fails with a 4xx error (bad request, unauthorized, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function listCountries(?int $offset = null, ?int $limit = null, string|Filter|null $filter = null): Response
    {
        return $this->connector->send(new GetCountriesRequest($offset, $limit, $filter));
    }
}
