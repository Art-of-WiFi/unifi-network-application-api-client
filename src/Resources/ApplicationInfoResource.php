<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Resources;

use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\ApplicationInfo\GetInfoRequest;
use Saloon\Http\Response;

/**
 * Application Info Resource
 *
 * Provides access to general application information endpoints.
 */
class ApplicationInfoResource extends BaseResource
{
    /**
     * Get general information about the UniFi Network application
     *
     * Returns details about the application including version and runtime metadata.
     * Useful for integration validation.
     *
     * @return Response
     * @throws \Saloon\Exceptions\Request\ClientException If the request fails with a 4xx error (bad request, unauthorized, etc.)
     * @throws \Saloon\Exceptions\Request\ServerException If the request fails with a 5xx error (server error)
     * @throws \Saloon\Exceptions\Request\RequestException If the request fails due to network issues or timeout
     */
    public function get(): Response
    {
        return $this->connector->send(new GetInfoRequest());
    }
}
