<?php

declare(strict_types=1);

namespace ArtOfWiFi\UnifiNetworkApplicationApi\Enums;

/**
 * Client Connection Types
 *
 * Defines the types of client connections supported by UniFi devices.
 */
enum ClientType: string
{
    /**
     * Wired (Ethernet) connection
     */
    case WIRED = 'WIRED';

    /**
     * Wireless (WiFi) connection
     */
    case WIRELESS = 'WIRELESS';

    /**
     * VPN connection
     */
    case VPN = 'VPN';

    /**
     * Teleport connection (UniFi Teleport VPN)
     */
    case TELEPORT = 'TELEPORT';
}
