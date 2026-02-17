<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;

/**
 * UniFi Network API Connector
 *
 * This connector handles the base configuration and authentication for the UniFi Network API.
 * The UniFi Network API uses API keys for authentication, which are passed via the X-API-KEY header.
 */
class UnifiConnector extends Connector
{
    use AcceptsJson;

    /**
     * The version of the API client library
     */
    public const VERSION = '1.0.0';

    /**
     * Create a new UniFi API connector instance
     *
     * @param  string  $baseUrl  The base URL of your UniFi controller (e.g., 'https://192.168.1.1')
     * @param  string  $apiKey  Your UniFi API key (generate this in the Integrations section of your UniFi application)
     * @param  bool  $verifySsl  Whether to verify SSL certificates (set to false for self-signed certificates)
     */
    public function __construct(
        protected string $baseUrl,
        protected string $apiKey,
        protected bool $verifySsl = true
    ) {}

    /**
     * Define the base URL for the connector
     */
    public function resolveBaseUrl(): string
    {
        return rtrim($this->baseUrl, '/').'/proxy/network/integration';
    }

    /**
     * Define default headers
     */
    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'X-API-KEY' => $this->apiKey,
            'User-Agent' => 'unifi-api-client-php/'.self::VERSION,
        ];
    }

    /**
     * Default HTTP client options
     */
    protected function defaultConfig(): array
    {
        return [
            'verify' => $this->verifySsl,
            'timeout' => 30,
        ];
    }
}
