<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Requests\Networks;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Creates a new network on a specific site.
 */
class CreateNetworkRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $siteId,
        protected array $data
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/sites/{$this->siteId}/networks";
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }
}
