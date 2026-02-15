<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\ApplicationInfo;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Retrieves application information and version details from the UniFi Network Application.
 */
class GetInfoRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/v1/info';
    }
}
