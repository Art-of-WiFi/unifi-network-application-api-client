<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Resources;

use ArtOfWiFi\UnifiNetworkApplicationApi\Requests\ApplicationInfo\GetInfoRequest;
use Saloon\Exceptions\Request\ClientException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Exceptions\Request\ServerException;
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
     * Returns details about the application, including version and runtime metadata.
     * Useful for integration validation.
     *
     * @throws ClientException If the request fails with a 4xx error (bad request, unauthorized, etc.)
     * @throws ServerException If the request fails with a 5xx error (server error)
     * @throws RequestException|FatalRequestException If the request fails due to network issues or timeout
     */
    public function get(): Response
    {
        return $this->connector->send(new GetInfoRequest);
    }
}
