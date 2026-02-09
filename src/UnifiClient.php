<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi;

use ArtOfWiFi\UnifiNetworkApplicationApi\Resources\SitesResource;
use ArtOfWiFi\UnifiNetworkApplicationApi\Resources\DevicesResource;
use ArtOfWiFi\UnifiNetworkApplicationApi\Resources\ClientsResource;
use ArtOfWiFi\UnifiNetworkApplicationApi\Resources\NetworksResource;
use ArtOfWiFi\UnifiNetworkApplicationApi\Resources\WifiBroadcastsResource;
use ArtOfWiFi\UnifiNetworkApplicationApi\Resources\HotspotResource;
use ArtOfWiFi\UnifiNetworkApplicationApi\Resources\FirewallResource;
use ArtOfWiFi\UnifiNetworkApplicationApi\Resources\AclRulesResource;
use ArtOfWiFi\UnifiNetworkApplicationApi\Resources\TrafficMatchingListsResource;
use ArtOfWiFi\UnifiNetworkApplicationApi\Resources\ApplicationInfoResource;
use ArtOfWiFi\UnifiNetworkApplicationApi\Resources\DnsPoliciesResource;
use ArtOfWiFi\UnifiNetworkApplicationApi\Resources\SupportingResourcesResource;

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
     * @param string $baseUrl The base URL of your UniFi controller (e.g., 'https://192.168.1.1')
     * @param string $apiKey Your UniFi API key (generate this in the Integrations section of your UniFi application)
     * @param bool $verifySsl Whether to verify SSL certificates (set to false for self-signed certificates)
     */
    public function __construct(
        string $baseUrl,
        string $apiKey,
        bool $verifySsl = true
    ) {
        $this->connector = new UnifiConnector($baseUrl, $apiKey, $verifySsl);
    }

    /**
     * Get the underlying Saloon connector
     *
     * @return UnifiConnector
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
     * @param string $siteId The UUID of the site
     * @return self Returns the client instance for method chaining
     */
    public function setSiteId(string $siteId): self
    {
        $this->siteId = $siteId;
        return $this;
    }

    /**
     * Get the currently set site ID
     *
     * @return string|null
     */
    public function getSiteId(): ?string
    {
        return $this->siteId;
    }

    /**
     * Access application info endpoints
     *
     * @return ApplicationInfoResource
     */
    public function applicationInfo(): ApplicationInfoResource
    {
        return new ApplicationInfoResource($this->connector);
    }

    /**
     * Access site management endpoints
     *
     * @return SitesResource
     */
    public function sites(): SitesResource
    {
        return new SitesResource($this->connector, $this->siteId);
    }

    /**
     * Access device management endpoints
     *
     * @return DevicesResource
     */
    public function devices(): DevicesResource
    {
        return new DevicesResource($this->connector, $this->siteId);
    }

    /**
     * Access client management endpoints
     *
     * @return ClientsResource
     */
    public function clients(): ClientsResource
    {
        return new ClientsResource($this->connector, $this->siteId);
    }

    /**
     * Access network management endpoints
     *
     * @return NetworksResource
     */
    public function networks(): NetworksResource
    {
        return new NetworksResource($this->connector, $this->siteId);
    }

    /**
     * Access WiFi broadcast (SSID) management endpoints
     *
     * @return WifiBroadcastsResource
     */
    public function wifiBroadcasts(): WifiBroadcastsResource
    {
        return new WifiBroadcastsResource($this->connector, $this->siteId);
    }

    /**
     * Access hotspot voucher management endpoints
     *
     * @return HotspotResource
     */
    public function hotspot(): HotspotResource
    {
        return new HotspotResource($this->connector, $this->siteId);
    }

    /**
     * Access firewall zone and policy management endpoints
     *
     * @return FirewallResource
     */
    public function firewall(): FirewallResource
    {
        return new FirewallResource($this->connector, $this->siteId);
    }

    /**
     * Access ACL rule management endpoints
     *
     * @return AclRulesResource
     */
    public function aclRules(): AclRulesResource
    {
        return new AclRulesResource($this->connector, $this->siteId);
    }

    /**
     * Access traffic matching list endpoints
     *
     * @return TrafficMatchingListsResource
     */
    public function trafficMatchingLists(): TrafficMatchingListsResource
    {
        return new TrafficMatchingListsResource($this->connector, $this->siteId);
    }

    /**
     * Access DNS policy management endpoints
     *
     * @return DnsPoliciesResource
     */
    public function dnsPolicies(): DnsPoliciesResource
    {
        return new DnsPoliciesResource($this->connector, $this->siteId);
    }

    /**
     * Access supporting resources endpoints
     *
     * Provides access to read-only reference data such as WAN interfaces,
     * DPI categories, country codes, RADIUS profiles, and device tags.
     *
     * @return SupportingResourcesResource
     */
    public function supportingResources(): SupportingResourcesResource
    {
        return new SupportingResourcesResource($this->connector, $this->siteId);
    }
}
