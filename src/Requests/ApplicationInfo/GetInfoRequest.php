<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\ApplicationInfo;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get Application Info Request
 *
 * Retrieves general information about the UniFi Network application.
 */
class GetInfoRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/v1/info';
    }
}
