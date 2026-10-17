<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi;

use InvalidArgumentException;
use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;

/**
 * UniFi Network API Connector
 *
 * This connector handles the base configuration and authentication for the UniFi Network API.
 * The UniFi Network API uses API keys for authentication, which are passed via the X-API-KEY header.
 *
 * Requests either go directly to a console ({baseUrl}/proxy/network/integration), or through the
 * Site Manager connector at api.ui.com, which forwards them to the console's /proxy path
 * (https://api.ui.com/v1/connector/consoles/{consoleId}/network/integration).
 */
class UnifiConnector extends Connector
{
    use AcceptsJson;

    /**
     * The version of the API client library
     */
    public const VERSION = '1.1.0';

    /**
     * The base URL of the Site Manager API
     */
    public const SITE_MANAGER_BASE_URL = 'https://api.ui.com';

    /**
     * The console (host) ID when requests are routed through the Site Manager connector
     */
    protected ?string $siteManagerConsoleId = null;

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
     * Create a connector that routes all requests through the Site Manager connector at api.ui.com
     *
     * SSL verification is always enabled because api.ui.com uses a publicly trusted certificate.
     *
     * @param  string  $consoleId  The console (host) ID, visible in the unifi.ui.com URL:
     *                             https://unifi.ui.com/consoles/{consoleId}/...
     * @param  string  $apiKey  A Site Manager API key generated at unifi.ui.com (not a local Network API key)
     *
     * @throws InvalidArgumentException If the console ID or API key is empty
     */
    public static function viaSiteManager(string $consoleId, string $apiKey): static
    {
        $consoleId = trim($consoleId);
        $apiKey = trim($apiKey);

        if ($consoleId === '') {
            throw new InvalidArgumentException('Console ID cannot be empty');
        }

        if ($apiKey === '') {
            throw new InvalidArgumentException('Site Manager API key cannot be empty');
        }

        $connector = new static(self::SITE_MANAGER_BASE_URL, $apiKey, true); // @phpstan-ignore new.static
        $connector->siteManagerConsoleId = $consoleId;

        return $connector;
    }

    /**
     * Whether requests are routed through the Site Manager connector
     */
    public function isSiteManagerProxy(): bool
    {
        return $this->siteManagerConsoleId !== null;
    }

    /**
     * Get the console (host) ID used for the Site Manager connector, or null for direct connections
     */
    public function getSiteManagerConsoleId(): ?string
    {
        return $this->siteManagerConsoleId;
    }

    /**
     * Define the base URL for the connector
     */
    public function resolveBaseUrl(): string
    {
        if ($this->siteManagerConsoleId !== null) {
            return self::SITE_MANAGER_BASE_URL.'/v1/connector/consoles/'.$this->siteManagerConsoleId.'/network/integration';
        }

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
