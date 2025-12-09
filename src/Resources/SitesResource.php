<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Resources;

use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Sites\GetSitesRequest;
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
     * @param int|null $page Page number (optional)
     * @param int|null $limit Number of results per page (optional)
     * @param string|null $filter Filter expression (optional)
     * @return Response
     * @throws \Saloon\Exceptions\Request\ClientException If the request fails with a 4xx error (bad request, unauthorized, etc.)
     * @throws \Saloon\Exceptions\Request\ServerException If the request fails with a 5xx error (server error)
     * @throws \Saloon\Exceptions\Request\RequestException If the request fails due to network issues or timeout
     */
    public function list(?int $page = null, ?int $limit = null, ?string $filter = null): Response
    {
        return $this->connector->send(new GetSitesRequest($page, $limit, $filter));
    }

    /**
     * Alias for list() method
     *
     * @param int|null $page Page number (optional)
     * @param int|null $limit Number of results per page (optional)
     * @param string|null $filter Filter expression (optional)
     * @return Response
     * @throws \Saloon\Exceptions\Request\ClientException If the request fails with a 4xx error (bad request, unauthorized, etc.)
     * @throws \Saloon\Exceptions\Request\ServerException If the request fails with a 5xx error (server error)
     * @throws \Saloon\Exceptions\Request\RequestException If the request fails due to network issues or timeout
     */
    public function all(?int $page = null, ?int $limit = null, ?string $filter = null): Response
    {
        return $this->list($page, $limit, $filter);
    }
}
