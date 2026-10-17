<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi;

use ArtOfWiFi\UnifiNetworkApplicationApi\Resources\AclRulesResource;
use ArtOfWiFi\UnifiNetworkApplicationApi\Resources\ApplicationInfoResource;
use ArtOfWiFi\UnifiNetworkApplicationApi\Resources\ClientsResource;
use ArtOfWiFi\UnifiNetworkApplicationApi\Resources\DevicesResource;
use ArtOfWiFi\UnifiNetworkApplicationApi\Resources\DnsPoliciesResource;
use ArtOfWiFi\UnifiNetworkApplicationApi\Resources\FirewallResource;
use ArtOfWiFi\UnifiNetworkApplicationApi\Resources\HotspotResource;
use ArtOfWiFi\UnifiNetworkApplicationApi\Resources\NetworksResource;
use ArtOfWiFi\UnifiNetworkApplicationApi\Resources\SitesResource;
use ArtOfWiFi\UnifiNetworkApplicationApi\Resources\SupportingResourcesResource;
use ArtOfWiFi\UnifiNetworkApplicationApi\Resources\SwitchingResource;
use ArtOfWiFi\UnifiNetworkApplicationApi\Resources\TrafficMatchingListsResource;
use ArtOfWiFi\UnifiNetworkApplicationApi\Resources\WifiBroadcastsResource;
use InvalidArgumentException;

/**
 * UniFi API Client - Main Entry Point
 *
 * This is the main client class that provides a fluent interface to interact with the UniFi Network API.
 * It provides easy access to all API resources through method chaining.
 *
 * Example usage:
 * ```php
 * $apiClient = new UnifiClient('https://192.168.1.1', 'your-api-key');
 * $sites = $apiClient->sites()->list();
 * ```
 */
class UnifiClient
{
    protected UnifiConnector $connector;

    protected ?string $siteId = null;

    /**
     * Create a new UniFi API client instance
     *
     * @param  string  $baseUrl  The base URL of your UniFi controller (e.g., 'https://192.168.1.1')
     * @param  string  $apiKey  Your UniFi API key (generate this in the Integrations section of your UniFi application)
     * @param  bool  $verifySsl  Whether to verify SSL certificates (set to false for self-signed certificates)
     * @param  bool  $throwOnErrors  Whether to throw an exception for 4xx/5xx responses instead of returning them
     *                               (default false: check `$response->failed()` or call `$response->throw()`)
     */
    public function __construct(
        string $baseUrl,
        string $apiKey,
        bool $verifySsl = true,
        bool $throwOnErrors = false
    ) {
        $this->connector = new UnifiConnector($baseUrl, $apiKey, $verifySsl, $throwOnErrors);
    }

    /**
     * Create a client that reaches the console through the Site Manager connector at api.ui.com
     *
     * Use this when there is no direct network path to the console. Requests are proxied by
     * Ubiquiti's cloud, which adds latency and is subject to Site Manager limits (100 requests
     * per minute per console, 25 second request timeout, 10 MB response size).
     *
     * Example usage:
     * ```php
     * $apiClient = UnifiClient::connectViaSiteManager('your-console-id', 'your-site-manager-api-key');
     * $sites = $apiClient->sites()->list();
     * ```
     *
     * @param  string  $consoleId  The console (host) ID, visible in the unifi.ui.com URL:
     *                             https://unifi.ui.com/consoles/{consoleId}/...
     * @param  string  $apiKey  A Site Manager API key generated at unifi.ui.com (not a local Network API key)
     * @param  bool  $throwOnErrors  Whether to throw an exception for 4xx/5xx responses instead of returning them
     *
     * @throws InvalidArgumentException If the console ID or API key is empty
     */
    public static function connectViaSiteManager(string $consoleId, string $apiKey, bool $throwOnErrors = false): static
    {
        $connector = UnifiConnector::viaSiteManager($consoleId, $apiKey, $throwOnErrors);

        $client = new static($connector->resolveBaseUrl(), $apiKey); // @phpstan-ignore new.static
        $client->connector = $connector;

        return $client;
    }

    /**
     * Throw an exception for 4xx/5xx responses (or stop doing so)
     *
     * By default failed responses are returned like any other response. When enabled, every
     * failed response throws a ClientException (4xx) or ServerException (5xx) subclass, such as
     * NotFoundException or UnauthorizedException.
     *
     * @return self Returns the client instance for method chaining
     */
    public function throwOnErrors(bool $throwOnErrors = true): self
    {
        $this->connector->throwOnErrors($throwOnErrors);

        return $this;
    }

    /**
     * Get the version of the API client library
     */
    public function getVersion(): string
    {
        return UnifiConnector::VERSION;
    }

    /**
     * Get the underlying Saloon connector
     */
    public function getConnector(): UnifiConnector
    {
        return $this->connector;
    }

    /**
     * Set the site ID for subsequent API calls
     *
     * Many UniFi API endpoints require a site ID. Use this method to set the site ID
     * that will be used for all subsequent API calls.
     *
     * @param  string  $siteId  The UUID of the site
     * @return self Returns the client instance for method chaining
     */
    public function setSiteId(string $siteId): self
    {
        $this->siteId = $siteId;

        return $this;
    }

    /**
     * Get the currently set site ID
     */
    public function getSiteId(): ?string
    {
        return $this->siteId;
    }

    /**
     * Access application info endpoints
     */
    public function applicationInfo(): ApplicationInfoResource
    {
        return new ApplicationInfoResource($this->connector);
    }

    /**
     * Access site management endpoints
     */
    public function sites(): SitesResource
    {
        return new SitesResource($this->connector, $this->siteId);
    }

    /**
     * Access device management endpoints
     */
    public function devices(): DevicesResource
    {
        return new DevicesResource($this->connector, $this->siteId);
    }

    /**
     * Access client management endpoints
     */
    public function clients(): ClientsResource
    {
        return new ClientsResource($this->connector, $this->siteId);
    }

    /**
     * Access network management endpoints
     */
    public function networks(): NetworksResource
    {
        return new NetworksResource($this->connector, $this->siteId);
    }

    /**
     * Access WiFi broadcast (SSID) management endpoints
     */
    public function wifiBroadcasts(): WifiBroadcastsResource
    {
        return new WifiBroadcastsResource($this->connector, $this->siteId);
    }

    /**
     * Access hotspot voucher management endpoints
     */
    public function hotspot(): HotspotResource
    {
        return new HotspotResource($this->connector, $this->siteId);
    }

    /**
     * Access firewall zone and policy management endpoints
     */
    public function firewall(): FirewallResource
    {
        return new FirewallResource($this->connector, $this->siteId);
    }

    /**
     * Access ACL rule management endpoints
     */
    public function aclRules(): AclRulesResource
    {
        return new AclRulesResource($this->connector, $this->siteId);
    }

    /**
     * Access traffic matching list endpoints
     */
    public function trafficMatchingLists(): TrafficMatchingListsResource
    {
        return new TrafficMatchingListsResource($this->connector, $this->siteId);
    }

    /**
     * Access DNS policy management endpoints
     */
    public function dnsPolicies(): DnsPoliciesResource
    {
        return new DnsPoliciesResource($this->connector, $this->siteId);
    }

    /**
     * Access switching feature endpoints
     *
     * Provides access to switch stacks, MC-LAG domains, and LAGs (Link Aggregation Groups).
     * All endpoints in this resource are read-only.
     */
    public function switching(): SwitchingResource
    {
        return new SwitchingResource($this->connector, $this->siteId);
    }

    /**
     * Access supporting resources endpoints
     *
     * Provides access to read-only reference data such as WAN interfaces,
     * DPI categories, country codes, RADIUS profiles, and device tags.
     */
    public function supportingResources(): SupportingResourcesResource
    {
        return new SupportingResourcesResource($this->connector, $this->siteId);
    }
}
