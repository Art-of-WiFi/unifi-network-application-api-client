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
     */
    public function get(): Response
    {
        return $this->connector->send(new GetInfoRequest());
    }
}
