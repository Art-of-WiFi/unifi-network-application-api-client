<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Resources;

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
     * @param  string|null  $filter  Filter expression (optional)
     *
     * @throws ClientException If the request fails with a 4xx error (bad request, unauthorized, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function list(?int $offset = null, ?int $limit = null, ?string $filter = null): Response
    {
        return $this->connector->send(new GetSitesRequest($offset, $limit, $filter));
    }

    /**
     * Alias for list() method
     *
     * @param  int|null  $offset  Pagination offset (optional, default: 0)
     * @param  int|null  $limit  Number of results per page (optional)
     * @param  string|null  $filter  Filter expression (optional)
     *
     * @throws ClientException If the request fails with a 4xx error (bad request, unauthorized, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function all(?int $offset = null, ?int $limit = null, ?string $filter = null): Response
    {
        return $this->list($offset, $limit, $filter);
    }
}
