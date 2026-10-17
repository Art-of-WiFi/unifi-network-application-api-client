<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Resources;

use ArtOfWiFi\UnifiNetworkApplicationApi\Filters\Filter;
use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Sites\GetSitesRequest;
use Saloon\Exceptions\Request\ClientException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Exceptions\Request\ServerException;
use Saloon\Http\Response;

/**
 * Sites Resource
 *
 * Provides access to site management endpoints.
 * Site ID is required for most other API requests.
 */
class SitesResource extends BaseResource
{
    /**
     * List all sites
     *
     * Retrieves a paginated list of all sites within your UniFi Network application.
     *
     * @param  int|null  $offset  Pagination offset (optional, default: 0)
     * @param  int|null  $limit  Number of results per page (optional)
     * @param  string|Filter|null  $filter  Filter expression or Filter object (optional)
     *
     * @throws ClientException If the request fails with a 4xx error and throw-on-errors is enabled
     * @throws ServerException If the request fails with a 5xx error and throw-on-errors is enabled
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function list(?int $offset = null, ?int $limit = null, string|Filter|null $filter = null): Response
    {
        return $this->connector->send(new GetSitesRequest($offset, $limit, $filter));
    }

    /**
     * Alias for list() method
     *
     * @param  int|null  $offset  Pagination offset (optional, default: 0)
     * @param  int|null  $limit  Number of results per page (optional)
     * @param  string|Filter|null  $filter  Filter expression or Filter object (optional)
     *
     * @throws ClientException If the request fails with a 4xx error and throw-on-errors is enabled
     * @throws ServerException If the request fails with a 5xx error and throw-on-errors is enabled
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function all(?int $offset = null, ?int $limit = null, string|Filter|null $filter = null): Response
    {
        return $this->list($offset, $limit, $filter);
    }
}
