<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Resources;

use ArtOfWiFi\UnifiNetworkApplicationApi\UnifiConnector;

/**
 * Base Resource Class
 *
 * All resource classes extend this base class to gain access to the connector
 * and common functionality.
 */
abstract class BaseResource
{
    /**
     * @param  UnifiConnector  $connector  The Saloon connector instance
     * @param  string|null  $siteId  The site ID for API calls that require it
     */
    public function __construct(
        protected UnifiConnector $connector,
        protected ?string $siteId = null
    ) {}

    /**
     * Get the connector instance
     */
    public function getConnector(): UnifiConnector
    {
        return $this->connector;
    }

    /**
     * Get the site ID
     */
    public function getSiteId(): ?string
    {
        return $this->siteId;
    }

    /**
     * Ensure a site ID is set
     *
     * @throws \RuntimeException If no site ID is set
     */
    protected function requireSiteId(): string
    {
        if ($this->siteId === null) {
            throw new \RuntimeException(
                'Site ID is required for this operation. '.
                'Please call setSiteId() on the client before using this endpoint.'
            );
        }

        return $this->siteId;
    }
}
