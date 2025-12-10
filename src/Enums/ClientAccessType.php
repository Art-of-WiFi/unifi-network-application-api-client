<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Enums;

/**
 * Client Access Types
 *
 * Defines the access level types for clients.
 */
enum ClientAccessType: string
{
    /**
     * Default/Standard network access
     */
    case DEFAULT = 'DEFAULT';

    /**
     * Guest network access (limited/restricted)
     */
    case GUEST = 'GUEST';
}
